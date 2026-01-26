<?php

namespace App\Filament\Resources\FormRegistrations\Tables;

use App\Models\RegistrationSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FormRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label('Link Form')
                    ->formatStateUsing(fn ($state) => url("/form/pendaftaran/{$state}"))
                    ->url(fn ($state) => url("/form/pendaftaran/{$state}"), true)
                    ->copyable()
                    ->copyMessage('Link berhasil disalin')
                    ->toggleable(isToggledHiddenByDefault: false),


                ToggleColumn::make('is_active')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark'),

                TextColumn::make('available_quota')
                    ->label('Kuota Tersedia')
                    ->state(function ($record) {

                        // total quota dari semua coach
                        $totalQuota = $record->schedules
                            ->flatMap(fn ($schedule) => $schedule->coaches)
                            ->sum('quota');

                        // jumlah submission yang sudah masuk
                        $usedQuota = \App\Models\RegistrationSubmission::whereIn(
                            'schedule_coach_id',
                            $record->schedules
                                ->flatMap(fn ($s) => $s->coaches)
                                ->pluck('id')
                        )->count();

                        return max($totalQuota - $usedQuota, 0);
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),



                // TextColumn::make('schedules_count')
                //     ->label('Jadwal')
                //     ->counts('schedules'),

                // TextColumn::make('fields_count')
                //     ->label('Field')
                //     ->counts('fields'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y'),

            ])
            ->defaultSort('created_at', 'desc')

            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->button()
                    ->tooltip('Download hasil pendaftaran (PDF)')
                    ->action(function ($record) {

                        $record->load([
                            'fields',
                            'submissions.answers',
                            'submissions.scheduleCoach.coach.user', // ← TAMBAHIN INI
                            'submissions.scheduleCoach.schedule',
                        ]);

                        $pdf = Pdf::loadView('pdf.form-registration-submissions', [
                            'form' => $record,
                            'fields' => $record->fields,
                            'submissions' => $record->submissions,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'form-' . $record->id . '-submissions-' . now()->format('Ymd_His') . '.pdf'
                        );
                    }),
                Action::make('lihat_hasil')
                    ->label('Lihat Hasil')
                    ->icon('heroicon-o-table-cells')
                    ->color('info')
                    ->button()
                    ->modalHeading('Hasil Formulir')
                    ->modalDescription('Data hasil pengisian form ini ditampilkan di bawah.')
                    ->modalIcon('heroicon-o-document-text')
                    ->modalIconColor('info')
                    ->modalWidth('7xl')
                    ->form(function ($record) {
                        $fields = $record->fields;

                        $schema = [
                            TextInput::make('coach')
                                ->label('Coach')
                                ->disabled()
                                ->dehydrated(false),

                            TextInput::make('schedule')
                                ->label('Jadwal')
                                ->disabled()
                                ->dehydrated(false),
                        ];

                        foreach ($fields as $field) {
                            if ($field->type === 'file') {
                                $schema[] = TextInput::make($field->name)
                                    ->label($field->label)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->suffixAction(
                                        Action::make('lihat_file')
                                            ->icon('heroicon-o-eye')
                                            ->url(
                                                fn ($state) => $state ? Storage::url($state) : null,
                                                true
                                            )
                                    );
                            } else {
                                $schema[] = TextInput::make($field->name)
                                    ->label($field->label)
                                    ->disabled()
                                    ->dehydrated(false);
                            }
                        }

                        $schema[] = TextInput::make('submitted_at')
                            ->label('Waktu Submit')
                            ->disabled()
                            ->dehydrated(false);

                        // ✅ FIX: Fresh data setiap kali modal dibuka
                        $submissions = $record->fresh([
                            'submissions.answers',
                            'submissions.scheduleCoach.coach.user',
                            'submissions.scheduleCoach.schedule',
                        ])->submissions()
                            ->orderByDesc('created_at')
                            ->get();

                        return [
                            Repeater::make('submissions')
                                ->label('Jawaban Peserta')
                                ->schema($schema)
                                ->columns(4)
                                ->default(
                                    $submissions->map(function ($s) use ($fields) {
                                        $row = [];

                                        $scheduleCoach = $s->scheduleCoach;
                                        $schedule      = $scheduleCoach?->schedule;
                                        $coach         = $scheduleCoach?->coach;
                                        $user          = $coach?->user;

                                        $row['coach'] = $user?->name ?? $user?->email ?? '-';
                                        $row['schedule'] = $schedule
                                            ? "{$schedule->day} {$schedule->time} - {$schedule->date}"
                                            : '-';

                                        foreach ($fields as $field) {
                                            $answer = $s->answers
                                                ->firstWhere('registration_field_id', $field->id);
                                            $row[$field->name] = $answer?->value ?? '-';
                                        }

                                        $row['id'] = $s->id;
                                        $row['submitted_at'] = $s->created_at->format('d M Y H:i');

                                        return $row;
                                    })->toArray()
                                )
                                ->addable(false)
                                ->reorderable(false)
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string =>
                                    isset($state['coach'], $state['submitted_at'])
                                        ? '📋 ' . $state['coach'] . ' • ' . $state['submitted_at']
                                        : 'Submission'
                                )
                                ->deleteAction(
                                    fn (Action $action) =>
                                        $action
                                            ->requiresConfirmation()
                                            ->modalHeading('Hapus Submission?')
                                            ->modalDescription('Data jawaban akan dihapus permanen.')
                                            ->before(function ($arguments, Repeater $component) {
                                                $item = $component->getState()[$arguments['item']] ?? null;

                                                if ($item && isset($item['id'])) {
                                                    $submission = RegistrationSubmission::find($item['id']);

                                                    // ✅ HAPUS MANUAL DECREMENT, biar auto-handle sama observer
                                                    // Cuma delete aja
                                                    $submission?->delete();
                                                }
                                            })
                                            ->after(function () {
                                                // ✅ Notif + hint user untuk refresh
                                                Notification::make()
                                                    ->success()
                                                    ->title('Submission berhasil dihapus')
                                                    ->body('Tutup dan buka kembali modal ini untuk melihat data terbaru.')
                                                    ->send();
                                            })
                                ),
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
