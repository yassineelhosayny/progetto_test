<?php

namespace App\Jobs\Generators;

use App\Models\ResourceGenerator as ResourceModel;
use Illuminate\Support\Facades\Log;

class ClusterGenerator extends BaseGenerator
{
    public function generate($resource): void
    {
        if (!$resource instanceof ResourceModel) {
            throw new \InvalidArgumentException('Resource must be an instance of ResourceGenerator');
        }

        // Solo se ha categorie (cluster)
        if ($resource->has_categories) {
            $resourceName = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $resource->name)));
            $this->createClusterFile($resourceName);
        }
    }

    /**
     * Crea il file del cluster manualmente
     */
    protected function createClusterFile(string $resourceName): void
    {
        $clusterPath = $this->getBasePath("Filament/Clusters/{$resourceName}");
        $clusterFilePath = $clusterPath . "/{$resourceName}Cluster.php";
        
        $template = require app_path('Jobs/Templates/ClusterTemplate.php');
        $clusterContent = str_replace('{{RESOURCE_NAME}}', $resourceName, $template);
        
        $this->writeFile($clusterFilePath, $clusterContent, 'Cluster');
    }
}