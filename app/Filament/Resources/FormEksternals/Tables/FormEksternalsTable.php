<?php

namespace App\Filament\Resources\FormEksternals\Tables;

use App\Models\FormSubmission;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater\TableColumn;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class FormEksternalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Form')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Tautan Form')
                    ->formatStateUsing(fn ($state) => url('/form/' . $state))
                    ->icon('heroicon-o-link')
                    ->url(fn ($record) => url('/form/' . $record->slug), true)
                    ->openUrlInNewTab()
                    ->color('primary'),

                ToggleColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->status === 'ACTIVE')
                    ->updateStateUsing(function ($record, $state) {
                        $record->status = $state ? 'ACTIVE' : 'INACTIVE';
                        $record->save();

                        Notification::make()
                            ->title('Status Form Diperbarui')
                            ->body("Form **{$record->title}** kini berstatus **" . ($state ? 'ACTIVE' : 'INACTIVE') . "**.")
                            ->color($state ? 'success' : 'warning')
                            ->send();
                    })
                    ->onIcon('heroicon-o-bolt')
                    ->offIcon('heroicon-o-power')
                    ->onColor('success')
                    ->offColor('danger'),


                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar-days')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('print_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->button()
                    ->tooltip('Download PDF')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.form-submission', [
                            'form' => $record,
                            'submissions' => $record->submissions()->latest()->get(),
                            'fields' => $record->fields,
                        ])->setPaper('a4', 'landscape');
                        
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "form-{$record->id}-submissions-" . now()->format('Ymd') . ".pdf"
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
                    $fields = collect($record->fields);

                    $tableColumns = $fields
                        ->map(fn (array $field) => TableColumn::make($field['label'] ?? ucfirst($field['name'])))
                        ->push(TableColumn::make('Waktu Submit'))
                        ->all();

                    $schemaComponents = $fields->map(function (array $field) {
                        $fieldName = $field['name'];

                        if (isset($field['type']) && $field['type'] === 'file') {
                            return TextInput::make("data.{$fieldName}")
                                ->hiddenLabel()->disabled()->dehydrated(false)
                                ->suffixAction(
                                    Action::make('view_file')
                                        ->label('Lihat File')->icon('heroicon-o-eye')->color('info')
                                        ->url(fn ($state): ?string => $state ? Storage::url($state) : null, true)
                                );
                        }

                        return TextInput::make("data.{$fieldName}")
                            ->hiddenLabel()->disabled()->dehydrated(false);
                    })->values()->toArray();

                    $schemaComponents[] = TextInput::make('submitted_at')
                        ->hiddenLabel()->disabled()->dehydrated(false);

                    $submissions = $record->submissions()->latest()->get();

                    return [
                        Repeater::make('submissions')
                            ->label('Daftar Jawaban')
                            ->table($tableColumns)
                            ->schema($schemaComponents)
                            ->default(
                                $submissions->map(fn ($s) => [
                                    'id' => $s->id,
                                    'data' => $s->data,
                                    'submitted_at' => $s->submitted_at?->format('d M Y H:i'),
                                ])->toArray()
                            )
                            ->reorderable(false)
                            ->deleteAction(
                        fn (Action $action) => $action
                                ->requiresConfirmation()
                                ->before(function (array $arguments, Repeater $component) {
                                    $itemKey = $arguments['item'] ?? null;
                                    $items = $component->getState();
                                    $item = $items[$itemKey] ?? null;
                                    
                                    if ($item && !empty($item['id'])) {
                                        FormSubmission::find($item['id'])?->delete();
                                    }
                                })
                        )
                    ];
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
                ViewAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('View details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),

                EditAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Edit role')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2']),

                DeleteAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Delete role')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->extraAttributes([
                        'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2']),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->searchDebounce(500);;
    }
}
