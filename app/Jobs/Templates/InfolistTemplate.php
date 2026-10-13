<?php

return '<?php

namespace App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\Schemas;

use Filament\\Schemas\\Schema;
use Filament\\Schemas\\Components\\Section;
use Filament\\Infolists\\Components\\TextEntry;
use Filament\\Infolists\\Components\\IconEntry;

class {{RESOURCE_NAME}}Infolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(\'General Information\')
                    ->schema([
                        TextEntry::make(\'name\')
                            ->getStateUsing(function ($record) {
                                $name = $record->name;
                                if (is_array($name)) {
                                    return $name[app()->getLocale()] ?? $name[\'it\'] ?? $name[\'en\'] ?? \'No title\';
                                }
                                return $name ?? \'No title\';
                            }),
                        TextEntry::make(\'description\')
                            ->columnSpanFull()
                            ->getStateUsing(function ($record) {
                                $description = $record->description;
                                if (is_array($description)) {
                                    return $description[app()->getLocale()] ?? $description[\'it\'] ?? $description[\'en\'] ?? \'No description\';
                                }
                                return $description ?? \'No description\';
                            }),
                        IconEntry::make(\'is_active\')
                            ->boolean(),
                        TextEntry::make(\'public_url\')
                            ->label(\'Public URL\')
                            ->getStateUsing(function ($record) {
                                if (!$record || !$record->slug) {
                                    return \'No slug available\';
                                }
                                
                                try {
                                    $resourceSlug = $record::getResourceSlug();
                                    if (!$resourceSlug) {
                                        return \'No resource slug\';
                                    }
                                    
                                    $url = route(\'generated.resource.show\', [
                                        \'resource\' => $resourceSlug,
                                        \'slug\' => $record->slug
                                    ]);
                                    
                                    return $url;
                                } catch (Exception $e) {
                                    return \'Error: \' . $e->getMessage();
                                }
                            })
                            ->url(function ($state) {
                                return filter_var($state, FILTER_VALIDATE_URL) ? $state : null;
                            })
                            ->openUrlInNewTab()
                            ->copyable(),
                    ])
                    ->columns(2),
                Section::make(\'Timestamps\')
                    ->schema([
                        TextEntry::make(\'created_at\')
                            ->dateTime(),
                        TextEntry::make(\'updated_at\')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}
';