<?php

namespace App\Filament\Resources\MenusBuilder;

use Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource as BaseMenuResource;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MenuResource extends BaseMenuResource
{

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(static::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament-menu-builder::menu-builder.form_labels.name'))->live(onBlur: true)
                ->afterStateUpdated(function ($state, $set, $get) {
                    // Auto-genera slug solo per la lingua di default (it) o se è la prima lingua
                    if ((!$get('slug')) && !empty($state)) {
                        $currentSlug = $get('slug');
                        if (empty($currentSlug)) {
                            $slug = Str::slug($state);
                            $set('slug', $slug);
                        }
                    }
                }),
            TextInput::make('slug')
                ->label(__('filament-menu-builder::menu-builder.form_labels.slug'))
                ->required()
                ->maxLength(255)
                ->helperText(__('filament-menu-builder::menu-builder.form_labels.slug_helper_text')),
        ];
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-menu-builder::menu-builder.form_labels.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Component')
                    ->copyable()
                    ->copyMessage(__('filament-menu-builder::menu-builder.component_copy_message'))
                    ->copyMessageDuration(3000)
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn(string $state): string => "<x-filament-menu-builder::menu slug=\"{$state}\" />"),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make(__('filament-menu-builder::menu-builder.configure_menu'))
                    ->url(fn($record): string => static::getUrl('build', ['record' => $record]))
                    ->icon('heroicon-o-bars-3'),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getModelLabel(): string
    {
        return 'Custom ' . parent::getModelLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return 'Custom ' . parent::getPluralModelLabel();
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\MenusBuilder\MenuResource\Pages\ListMenus::route('/'),
            'create' => \App\Filament\Resources\MenusBuilder\MenuResource\Pages\CreateMenu::route('/create'),
            'edit' => \App\Filament\Resources\MenusBuilder\MenuResource\Pages\EditMenu::route('/{record}/edit'),
            'build' => \App\Filament\Resources\MenusBuilder\MenuResource\Pages\MenuBuilder::route('/{record}/build'),
        ];
    }
}