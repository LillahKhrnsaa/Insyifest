<?php

namespace App\Filament\Widgets;

use App\Models\FormEksternal;
use App\Models\FormSubmission;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class FormWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return FormEksternal::query()
            ->withCount('submissions')
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')->label('Form'),
            Tables\Columns\TextColumn::make('status')->badge(),
            Tables\Columns\TextColumn::make('submissions_count')->label('Jumlah Submission'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dibuat'),
        ];
    }
}
