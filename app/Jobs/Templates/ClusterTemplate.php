<?php

return '<?php

namespace App\\Filament\\Clusters\\{{RESOURCE_NAME}};

use Filament\\Clusters\\Cluster;
use Filament\\Support\\Enums\\IconPosition;
use Filament\\Support\\Enums\\IconSize;
use Illuminate\\Support\\Facades\\Log;
use Filament\\Support\\Icons\\Heroicon;
use BackedEnum;

class {{RESOURCE_NAME}}Cluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $clusterBreadcrumb = \'{{RESOURCE_NAME}}\';

    protected static ?string $navigationLabel = \'{{RESOURCE_NAME}}\';
}
';