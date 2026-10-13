<?php

namespace App\Filament\Resources\MenusBuilder;

use Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource as BaseMenuItemResource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Fieldset;
use App\Models\Page;
use Biostate\FilamentMenuBuilder\FilamentMenuBuilderPlugin;

class MenuItemResource extends BaseMenuItemResource
{
	public static function getPages(): array
	{
		return [
			'index' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\ListMenuItems::route('/'),
			'create' => \App\Filament\Resources\MenusBuilder\MenuItemResource\Pages\CreateMenuItem::route('/create'),
			'edit' => \App\Filament\Resources\MenusBuilder\MenuItemResource\Pages\EditMenuItem::route('/{record}/edit'),
		];
	}
	
	public static function form(Schema $schema): Schema
    
	{
		return $schema->schema(static::getFormSchema());
	}

	public static function getFormSchema(): array
	{
		return [
			TextInput::make('name')
				->label(__('filament-menu-builder::menu-builder.form_labels.name'))
				->autofocus()
				->required()
				->maxLength(255),

			Select::make('menu_id')
				->label(__('filament-menu-builder::menu-builder.menu_name'))
				->options(fn () => FilamentMenuBuilderPlugin::get()->getMenuModel()::all()->pluck('name', 'id'))
				->hidden(fn ($context) => ! in_array($context, ['edit', 'create']))
				->required(),

			Select::make('target')
				->options(\Biostate\FilamentMenuBuilder\Enums\MenuItemTarget::class)
				->default('_self')
				->required(),

            TextInput::make('link_class')
                ->label(__('filament-menu-builder::menu-builder.form_labels.link_class'))
                ->maxLength(255),
            TextInput::make('wrapper_class')
                ->label(__('filament-menu-builder::menu-builder.form_labels.wrapper_class'))
                ->maxLength(255),
			Toggle::make('is_active')
				->label(__('Attivo/Disattivo') ?? 'Active')
				->helperText(__('Attiva/Disattiva voce di menù') ?? '')
				->default(true),

			Fieldset::make('URL')
				->columns(1)
				->schema([
					Select::make('type_choice')
						->label(__('filament-menu-builder::menu-builder.form_labels.type'))
						->options([
							'link' => 'Link',
							'page' => 'Page',
							'placeholder' => 'Placeholder',
						])
						->afterStateUpdated(function (callable $set) {
							$set('menuable_type', null);
							$set('menuable_id', null);
							$set('url', null);
							$set('page_id', null);
						})
						->default('link')
						->required()
						->live(),

					Hidden::make('type')
						->default(fn ($get) => $get('type_choice') === 'page' ? 'model' : ($get('type_choice') === 'link' ? 'link' : 'route'))
						->reactive(),

					TextInput::make('url')
						->label(__('filament-menu-builder::menu-builder.form_labels.url'))
						->hidden(fn ($get) => $get('type_choice') !== 'link')
						->maxLength(255)
						->required(fn ($get) => $get('type_choice') === 'link'),

					Select::make('page_id')
						->label(__('Page'))
						->options(fn () => Page::all()->mapWithKeys(function ($m) {
							if (method_exists($m, 'getFilamentSearchOptionName')) {
								$label = $m->getFilamentSearchOptionName();
							} elseif (method_exists($m, 'getMenuNameAttribute')) {
								$label = $m->menu_name;
							} elseif (isset($m->title)) {
								$label = $m->title;
							} elseif (isset($m->name)) {
								$label = $m->name;
							} else {
								$label = (string) $m->id;
							}

							return [$m->id => $label];
						})->toArray())
						->searchable()
						->hidden(fn ($get) => $get('type_choice') !== 'page')
						->required(fn ($get) => $get('type_choice') === 'page')
						->getOptionLabelUsing(function ($value) {
							$m = Page::find($value);
							if (! $m) {
								return (string) $value;
							}

							if (method_exists($m, 'getFilamentSearchOptionName')) {
								return $m->getFilamentSearchOptionName();
							}

							if (method_exists($m, 'getMenuNameAttribute')) {
								return $m->menu_name;
							}

							return $m->title ?? $m->name ?? (string) $m->id;
						}),

					Select::make('menuable_type')
						->label(__('filament-menu-builder::menu-builder.form_labels.menuable_type'))
						->options(
							array_flip(config('filament-menu-builder.models', []))
						)
						->live()
						->required(fn ($get) => $get('type_choice') === 'page')
						->afterStateUpdated(fn (callable $set) => $set('menuable_id', null))
						->hidden(fn ($get) => empty(config('filament-menu-builder.models', [])) || $get('type_choice') !== 'page'),

					Hidden::make('menuable_id'),


				]),
		];
	}
}


