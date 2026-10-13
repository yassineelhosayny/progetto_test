<?php

return '<?php

namespace App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\Tables;

use Filament\\Tables\\Table;
use Filament\\Tables\\Columns\\TextColumn;
use Filament\\Tables\\Columns\\IconColumn;
use Filament\\Tables\\Filters\\TernaryFilter;
use Filament\\Actions\\ViewAction;
use Filament\\Actions\\EditAction;
use Filament\\Actions\\DeleteAction;
use Filament\\Actions\\BulkActionGroup;
use Filament\\Actions\\DeleteBulkAction;

class {{RESOURCE_NAME}}Table
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(\'name\')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make(\'description\')
                    ->limit(50)
                    ->toggledHiddenByDefault(),
                IconColumn::make(\'is_active\')
                    ->boolean()
                    ->sortable(),
                TextColumn::make(\'created_at\')
                    ->dateTime()
                    ->sortable()
                    ->toggledHiddenByDefault(),
                TextColumn::make(\'updated_at\')
                    ->dateTime()
                    ->sortable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                TernaryFilter::make(\'is_active\')
                    ->label(\'Active\')
                    ->boolean()
                    ->trueLabel(\'Only Active\')
                    ->falseLabel(\'Only Inactive\')
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make()->icon(\'heroicon-o-eye\'),
                EditAction::make()->icon(\'heroicon-o-pencil\'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(\'created_at\', \'desc\')
            ->poll(\'30s\');
    }
}
';