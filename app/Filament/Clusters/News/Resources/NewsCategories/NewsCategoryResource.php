<?php

namespace App\Filament\Clusters\News\Resources\NewsCategories;

use App\Filament\Clusters\News\NewsCluster;
use App\Filament\Clusters\News\Resources\NewsCategories\Pages\CreateNewsCategory;
use App\Filament\Clusters\News\Resources\NewsCategories\Pages\EditNewsCategory;
use App\Filament\Clusters\News\Resources\NewsCategories\Pages\ListNewsCategories;
use App\Filament\Clusters\News\Resources\NewsCategories\Pages\ViewNewsCategory;
use App\Filament\Clusters\News\Resources\NewsCategories\Schemas\NewsCategoryForm;
use App\Filament\Clusters\News\Resources\NewsCategories\Tables\NewsCategoriesTable;
use App\Models\NewsCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsCategoryResource extends Resource
    
{

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorie';

    protected static ?string $model = NewsCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static ?string $cluster = NewsCluster::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return NewsCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsCategories::route('/'),
            'create' => CreateNewsCategory::route('/create'),
            'edit' => EditNewsCategory::route('/{record}/edit'),
            'view' => ViewNewsCategory::route('/{record}'),
        ];
    }
}
