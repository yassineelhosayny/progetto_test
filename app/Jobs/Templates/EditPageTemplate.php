<?php

return '<?php

namespace App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\Pages;

use App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\{{RESOURCE_NAME}}Resource;
use Filament\\Actions\\DeleteAction;
use Filament\\Actions\\ViewAction;
use Filament\\Resources\\Pages\\EditRecord;

class Edit{{RESOURCE_NAME}} extends EditRecord
{
    protected static string $resource = {{RESOURCE_NAME}}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
';