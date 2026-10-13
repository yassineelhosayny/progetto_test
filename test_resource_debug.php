<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ResourceGenerator;
use App\Jobs\GenerateFilamentResourceJob;

echo "Creando risorsa test...\n";

// Crea un oggetto ResourceGenerator per test
$resource = new ResourceGenerator();
$resource->name = 'TestDebugSenzaCluster';
$resource->has_categories = false;
$resource->id = 9999; // ID temporaneo per evitare conflitti

echo "Risorsa creata con:\n";
echo "- name: {$resource->name}\n";
echo "- has_categories: " . ($resource->has_categories ? 'true' : 'false') . "\n";

echo "\nTestando la logica del job...\n";

try {
    $job = new GenerateFilamentResourceJob($resource);
    
    // Test solo della logica condizionale
    $resourceName = $resource->name;
    $clusterImport = $resource->has_categories ? 
        "\nuse App\\Filament\\Clusters\\{$resourceName}\\{$resourceName}Cluster;" : '';
    $clusterProperty = $resource->has_categories ? 
        "\n    protected static ?string \$cluster = {$resourceName}Cluster::class;\n" : "\n";
        
    echo "Cluster Import: '$clusterImport'\n";
    echo "Cluster Property: '$clusterProperty'\n";
    
    echo "\nLogica condizionale OK!\n";
    
} catch (Exception $e) {
    echo "ERRORE: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}