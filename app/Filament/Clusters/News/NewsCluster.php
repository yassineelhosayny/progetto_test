<?php

namespace App\Filament\Clusters\News;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class NewsCluster extends Cluster
{
    protected static string | UnitEnum | null $navigationGroup = 'Contenuti';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $clusterBreadcrumb = 'Articoli';

    protected static ?string $navigationLabel = 'Articoli';
}
