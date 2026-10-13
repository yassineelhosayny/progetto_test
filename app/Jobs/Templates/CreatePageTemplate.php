<?php

return '<?php

namespace App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\Pages;

use App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\{{RESOURCE_NAME}}Resource;
use Filament\\Resources\\Pages\\CreateRecord;

class Create{{RESOURCE_NAME}} extends CreateRecord
{
    protected static string $resource = {{RESOURCE_NAME}}Resource::class;
}
';