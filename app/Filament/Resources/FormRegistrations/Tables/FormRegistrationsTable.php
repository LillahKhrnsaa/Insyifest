<?php

namespace App\Filament\Resources\FormRegistrations\Tables;

use App\Models\RegistrationSubmission;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
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
                    ->toggleable(isToggledHiddenByDefault: true),


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

                        $tableColumns = collect($fields)
                            ->map(fn ($field) => TableColumn::make($field->label))
                            ->push(TableColumn::make('Waktu Submit'))
                            ->toArray();

                        $schema = collect($fields)->map(function ($field) {

                            if ($field->type === 'file') {
                                return TextInput::make($field->name)
                                    ->label($field->label)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->suffixAction(
                                        Action::make('lihat_file')
                                            ->icon('heroicon-o-eye')
                                            ->url(fn ($state) => $state ? Storage::url($state) : null, true)
                                    );
                            }

                            return TextInput::make($field->name)
                                ->label($field->label)
                                ->disabled()
                                ->dehydrated(false);

                        })->toArray();

                        $schema[] = TextInput::make('submitted_at')
                            ->label('Waktu Submit')
                            ->disabled()
                            ->dehydrated(false);

                        $submissions = $record->submissions()
                            ->with('answers')
                            ->latest()
                            ->get();

                        return [
                            Repeater::make('submissions')
                                ->label('Jawaban Peserta')
                                ->table($tableColumns)
                                ->schema($schema)
                                ->default(
                                    $submissions->map(function ($s) use ($fields) {

                                        $row = [];

                                        foreach ($fields as $field) {
                                            $answer = $s->answers
                                                ->firstWhere('registration_field_id', $field->id);

                                            $row[$field->name] = $answer?->value;
                                        }

                                        $row['id'] = $s->id;
                                        $row['submitted_at'] = $s->created_at->format('d M Y H:i');

                                        return $row;

                                    })->toArray()
                                )
                                ->reorderable(false)
                                ->deleteAction(
                                    fn (Action $action) =>
                                        $action->requiresConfirmation()
                                            ->before(function ($arguments, Repeater $component) {
                                                $item = $component->getState()[$arguments['item']] ?? null;
                                                if ($item && isset($item['id'])) {
                                                    RegistrationSubmission::find($item['id'])?->delete();
                                                }
                                            })
                                ),
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
