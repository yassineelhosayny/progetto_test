<?php

namespace App\Jobs\Generators;

use App\Models\ResourceGenerator as ResourceModel;
use Illuminate\Support\Facades\Log;

class ResourceGenerator extends BaseGenerator
{
    public function generate($resource): void
    {
        if (!$resource instanceof ResourceModel) {
            throw new \InvalidArgumentException('Resource must be an instance of ResourceGenerator');
        }

        $resourceName = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $resource->name)));

        if (!$resource->has_categories) {
            $this->generateSimpleResource($resource, $resourceName);
        } else {
            $this->generateResourceWithCategories($resource, $resourceName);
        }
    }

    /**
     * Genera una risorsa semplice senza categorie
     */
    protected function generateSimpleResource(ResourceModel $resource, string $resourceName): void
    {
        Log::info('[ResourceGenerator] Generazione risorsa semplice senza cluster', ['name' => $resourceName]);
        
        // Crea la struttura delle cartelle per la risorsa semplice (senza cluster)
        $resourcePath = $this->getBasePath("Filament/Resources/{$resourceName}");
        $this->ensureDirectory($resourcePath);
        
        // Crea il file della risorsa principale senza cluster
        $this->createSimpleResourceFile($resource, $resourceName);
        
        Log::info('[ResourceGenerator] Risorsa semplice creata con successo', ['name' => $resourceName]);
    }

    /**
     * Genera una risorsa con categorie (cluster)
     */
    protected function generateResourceWithCategories(ResourceModel $resource, string $resourceName): void
    {
        Log::info('[ResourceGenerator] Generazione risorsa con cluster', ['name' => $resourceName]);
        
        // Crea la risorsa principale
        $this->createMainResourceFile($resource, $resourceName);
        
        // Crea la risorsa delle categorie
        $categoriesModelName = $resourceName . 'Categories';
        $this->createCategoriesResourceFile($resource, $resourceName, $categoriesModelName);
        
        Log::info('[ResourceGenerator] Risorsa con cluster creata con successo', ['name' => $resourceName]);
    }

    /**
     * Crea il file della risorsa semplice senza cluster
     */
    protected function createSimpleResourceFile(ResourceModel $resource, string $resourceName): void
    {
        $resourcePath = $this->getBasePath("Filament/Resources/{$resourceName}");
        $resourceFilePath = $resourcePath . "/{$resourceName}Resource.php";
        
        $template = require app_path('Jobs/Templates/SimpleResourceTemplate.php');
        
        // Crea label con spazi prima delle maiuscole (solo per visualizzazione)
        $labelWithSpaces = preg_replace('/(?<!^)([A-Z])/', ' $1', $resourceName);
        
        $resourceContent = str_replace([
            '{{RESOURCE_NAME}}',
            '{{MODEL_NAME}}',
            '{{RESOURCE_SLUG}}',
            '{{MODEL_LABEL}}',
            '{{PLURAL_MODEL_LABEL}}'
        ], [
            $resourceName,
            $resourceName,
            strtolower($resourceName),
            $labelWithSpaces,
            $labelWithSpaces
        ], $template);
        
        $this->writeFile($resourceFilePath, $resourceContent, 'Simple Resource');
        
        // Crea i file schema e pagine
        $this->createSimpleResourceSchemas($resourceName);
        $this->createSimpleResourcePages($resourceName);
    }

    /**
     * Crea il file della risorsa principale per cluster
     */
    protected function createMainResourceFile(ResourceModel $resource, string $resourceName): void
    {
        $resourcePath = $this->getBasePath("Filament/Clusters/{$resourceName}/Resources/{$resourceName}");
        $resourceFilePath = $resourcePath . "/{$resourceName}Resource.php";
        
        $template = require app_path('Jobs/Templates/ResourceTemplate.php');
        
        $clusterImport = "\nuse App\\Filament\\Clusters\\{$resourceName}\\{$resourceName}Cluster;";
        $clusterProperty = "\n    protected static ?string \$cluster = {$resourceName}Cluster::class;\n";
        
        $labelWithSpaces = preg_replace('/(?<!^)([A-Z])/', ' $1', $resourceName);
        
        $resourceContent = str_replace([
            '{{CLUSTER_NAME}}',
            '{{RESOURCE_NAME}}',
            '{{MODEL_NAME}}',
            '{{CLUSTER_IMPORT}}',
            '{{CLUSTER_PROPERTY}}',
            '{{NAVIGATION_SORT}}',
            '{{RESOURCE_SLUG}}',
            '{{MODEL_LABEL}}',
            '{{PLURAL_MODEL_LABEL}}'
        ], [
            $resourceName,
            $resourceName,
            $resourceName,
            $clusterImport,
            $clusterProperty,
            '0',
            strtolower($resourceName),
            $labelWithSpaces,
            $labelWithSpaces
        ], $template);
        
        $this->writeFile($resourceFilePath, $resourceContent, 'Main Resource');
        
        // Crea i file schema e pagine
        $this->createResourceSchemas($resourceName, $resourceName);
        $this->createResourcePages($resourceName, $resourceName);
    }

    /**
     * Crea il file della risorsa delle categorie
     */
    protected function createCategoriesResourceFile(ResourceModel $resource, string $resourceName, string $categoriesModelName): void
    {
        $resourcePath = $this->getBasePath("Filament/Clusters/{$resourceName}/Resources/{$categoriesModelName}");
        $resourceFilePath = $resourcePath . "/{$categoriesModelName}Resource.php";
        
        $template = require app_path('Jobs/Templates/ResourceTemplate.php');
        
        $clusterImport = "\nuse App\\Filament\\Clusters\\{$resourceName}\\{$resourceName}Cluster;";
        $clusterProperty = "\n    protected static ?string \$cluster = {$resourceName}Cluster::class;\n";
        
        $resourceContent = str_replace([
            '{{CLUSTER_NAME}}',
            '{{RESOURCE_NAME}}',
            '{{MODEL_NAME}}',
            '{{CLUSTER_IMPORT}}',
            '{{CLUSTER_PROPERTY}}',
            '{{NAVIGATION_SORT}}',
            '{{RESOURCE_SLUG}}',
            '{{MODEL_LABEL}}',
            '{{PLURAL_MODEL_LABEL}}'
        ], [
            $resourceName,
            $categoriesModelName,
            $categoriesModelName,
            $clusterImport,
            $clusterProperty,
            '1',
            strtolower($categoriesModelName),
            'Categoria',
            'Categorie'
        ], $template);
        
        $this->writeFile($resourceFilePath, $resourceContent, 'Categories Resource');
        
        // Crea i file schema e pagine
        $this->createResourceSchemas($resourceName, $categoriesModelName);
        $this->createResourcePages($resourceName, $categoriesModelName);
    }

    /**
     * Crea i file schema per una risorsa (Form, Infolist, Table)
     */
    protected function createResourceSchemas(string $clusterName, string $resourceName): void
    {
        $schemasPath = $this->getBasePath("Filament/Clusters/{$clusterName}/Resources/{$resourceName}/Schemas");
        $tablesPath = $this->getBasePath("Filament/Clusters/{$clusterName}/Resources/{$resourceName}/Tables");
        
        $this->ensureDirectory($schemasPath);
        $this->ensureDirectory($tablesPath);
        
        // Form Schema
        $formTemplate = require app_path('Jobs/Templates/FormTemplate.php');
        $formContent = str_replace([
            '{{CLUSTER_NAME}}',
            '{{RESOURCE_NAME}}'
        ], [
            $clusterName,
            $resourceName
        ], $formTemplate);
        $this->writeFile($schemasPath . "/{$resourceName}Form.php", $formContent, 'Form Schema');
        
        // Infolist Schema
        $infolistTemplate = require app_path('Jobs/Templates/InfolistTemplate.php');
        $infolistContent = str_replace([
            '{{CLUSTER_NAME}}',
            '{{RESOURCE_NAME}}'
        ], [
            $clusterName,
            $resourceName
        ], $infolistTemplate);
        $this->writeFile($schemasPath . "/{$resourceName}Infolist.php", $infolistContent, 'Infolist Schema');
        
        // Table Schema
        $tableTemplate = require app_path('Jobs/Templates/TableTemplate.php');
        $tableContent = str_replace([
            '{{CLUSTER_NAME}}',
            '{{RESOURCE_NAME}}'
        ], [
            $clusterName,
            $resourceName
        ], $tableTemplate);
        $this->writeFile($tablesPath . "/{$resourceName}Table.php", $tableContent, 'Table Schema');
        
        Log::info('[ResourceGenerator] Schema e Table files creati', ['cluster' => $clusterName, 'resource' => $resourceName]);
    }

    /**
     * Crea le pagine per una risorsa
     */
    protected function createResourcePages(string $clusterName, string $resourceName): void
    {
        $pagesPath = $this->getBasePath("Filament/Clusters/{$clusterName}/Resources/{$resourceName}/Pages");
        $this->ensureDirectory($pagesPath);
        
        $pages = ['Create', 'Edit', 'List', 'View'];
        
        foreach ($pages as $pageType) {
            $pageTemplate = require app_path("Jobs/Templates/{$pageType}PageTemplate.php");
            $pageContent = str_replace([
                '{{RESOURCE_NAME}}',
                '{{CLUSTER_NAME}}',
                '{{PAGE_TYPE}}'
            ], [
                $resourceName,
                $clusterName,
                $pageType
            ], $pageTemplate);
            
            $pageFilePath = $pagesPath . "/{$pageType}{$resourceName}.php";
            $this->writeFile($pageFilePath, $pageContent, "{$pageType} Page");
        }
        
        Log::info('[ResourceGenerator] Pagine risorsa create', ['cluster' => $clusterName, 'resource' => $resourceName]);
    }

    /**
     * Crea i file schema per una risorsa semplice
     */
    protected function createSimpleResourceSchemas(string $resourceName): void
    {
        // Schemas directory per Form e Infolist
        $schemasPath = $this->getBasePath("Filament/Resources/{$resourceName}/Schemas");
        $this->ensureDirectory($schemasPath);
        
        // Tables directory separata per Table
        $tablesPath = $this->getBasePath("Filament/Resources/{$resourceName}/Tables");
        $this->ensureDirectory($tablesPath);
        
        // Form Schema (in Schemas/) - usa adaptTemplateForSimpleResource
        $formTemplate = require app_path('Jobs/Templates/FormTemplate.php');
        $formContent = $this->adaptTemplateForSimpleResource($formTemplate, $resourceName);
        $this->writeFile($schemasPath . "/{$resourceName}Form.php", $formContent, 'Form Schema');
        
        // Table Schema (in Tables/) - usa adaptTemplateForSimpleResource
        $tableTemplate = require app_path('Jobs/Templates/TableTemplate.php');
        $tableContent = $this->adaptTemplateForSimpleResource($tableTemplate, $resourceName);
        $this->writeFile($tablesPath . "/{$resourceName}Table.php", $tableContent, 'Table Schema');
        
        // Infolist Schema (in Schemas/) - usa adaptTemplateForSimpleResource
        $infolistTemplate = require app_path('Jobs/Templates/InfolistTemplate.php');
        $infolistContent = $this->adaptTemplateForSimpleResource($infolistTemplate, $resourceName);
        $this->writeFile($schemasPath . "/{$resourceName}Infolist.php", $infolistContent, 'Infolist Schema');
    }

    /**
     * Crea le pagine per una risorsa semplice
     */
    protected function createSimpleResourcePages(string $resourceName): void
    {
        $pagesPath = $this->getBasePath("Filament/Resources/{$resourceName}/Pages");
        $this->ensureDirectory($pagesPath);
        
        // Create Page
        $createTemplate = require app_path('Jobs/Templates/CreatePageTemplate.php');
        $createContent = $this->adaptTemplateForSimpleResource($createTemplate, $resourceName);
        $this->writeFile($pagesPath . "/Create{$resourceName}.php", $createContent, 'Create Page');
        
        // Edit Page
        $editTemplate = require app_path('Jobs/Templates/EditPageTemplate.php');
        $editContent = $this->adaptTemplateForSimpleResource($editTemplate, $resourceName);
        $this->writeFile($pagesPath . "/Edit{$resourceName}.php", $editContent, 'Edit Page');
        
        // List Page
        $listTemplate = require app_path('Jobs/Templates/ListPageTemplate.php');
        $listContent = $this->adaptTemplateForSimpleResource($listTemplate, $resourceName);
        $this->writeFile($pagesPath . "/List{$resourceName}.php", $listContent, 'List Page');
        
        // View Page
        $viewTemplate = require app_path('Jobs/Templates/ViewPageTemplate.php');
        $viewContent = $this->adaptTemplateForSimpleResource($viewTemplate, $resourceName);
        $this->writeFile($pagesPath . "/View{$resourceName}.php", $viewContent, 'View Page');
    }

    /**
     * Adatta un template per risorsa semplice rimuovendo i riferimenti al cluster
     */
    protected function adaptTemplateForSimpleResource(string $template, string $resourceName): string
    {
        // Prima sostituisce i placeholder di base
        $content = str_replace('{{RESOURCE_NAME}}', $resourceName, $template);
        
        // Poi rimuove completamente i riferimenti al cluster
        $content = str_replace([
            '\\Clusters\\{{CLUSTER_NAME}}\\',
            '\\Clusters\\\\',
            'App\\Filament\\Clusters\\\\Resources',
            'App\\Filament\\Clusters\\Resources'
        ], [
            '\\',
            '\\',
            'App\\Filament\\Resources',
            'App\\Filament\\Resources'
        ], $content);
        
        return $content;
    }
}