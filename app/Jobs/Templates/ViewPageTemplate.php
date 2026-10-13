<?php

return '<?php

namespace App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\Pages;

use App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\{{RESOURCE_NAME}}Resource;
use Filament\\Actions\\EditAction;
use Filament\\Actions\\DeleteAction;
use Filament\\Resources\\Pages\\ViewRecord;

class View{{RESOURCE_NAME}} extends ViewRecord
{
    protected static string $resource = {{RESOURCE_NAME}}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
';