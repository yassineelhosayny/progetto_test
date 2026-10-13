<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportToMySQL extends Command
{
    protected $signature = 'db:export-mysql {--file=mysql_dump.sql}';
    protected $description = 'Export SQLite database to MySQL format';

    public function handle()
    {
        $outputFile = storage_path('app/' . $this->option('file'));
        
        $this->info('Starting database export...');
        
        // Get all table names
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        
        $output = "-- MySQL dump converted from SQLite\n";
        $output .= "-- Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $output .= "SET AUTOCOMMIT = 0;\n";
        $output .= "START TRANSACTION;\n\n";
        
        foreach ($tables as $table) {
            $tableName = $table->name;
            $this->info("Processing table: {$tableName}");
            
            // Get table structure
            $columns = Schema::getColumnListing($tableName);
            $columnInfo = [];
            
            foreach ($columns as $column) {
                $type = Schema::getColumnType($tableName, $column);
                $columnInfo[$column] = $type;
            }
            
            // Create table structure
            $output .= $this->createTableSQL($tableName, $columnInfo);
            
            // Export data
            $data = DB::table($tableName)->get();
            
            if ($data->count() > 0) {
                $output .= "-- Dumping data for table `{$tableName}`\n";
                $output .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES\n";
                
                $values = [];
                foreach ($data as $row) {
                    $rowValues = [];
                    foreach ($columns as $column) {
                        $value = $row->$column;
                        if ($value === null) {
                            $rowValues[] = 'NULL';
                        } elseif (is_numeric($value)) {
                            $rowValues[] = $value;
                        } else {
                            $rowValues[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $values[] = '(' . implode(', ', $rowValues) . ')';
                }
                
                $output .= implode(",\n", $values) . ";\n\n";
            }
        }
        
        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $output .= "COMMIT;\n";
        
        file_put_contents($outputFile, $output);
        
        $this->info("Export completed successfully!");
        $this->info("File saved to: {$outputFile}");
        $this->info("Total tables processed: " . count($tables));
    }
    
    private function createTableSQL($tableName, $columns)
    {
        $sql = "DROP TABLE IF EXISTS `{$tableName}`;\n";
        $sql .= "CREATE TABLE `{$tableName}` (\n";
        
        $columnDefs = [];
        foreach ($columns as $column => $type) {
            $mysqlType = $this->convertToMySQLType($type);
            $columnDefs[] = "  `{$column}` {$mysqlType}";
        }
        
        // Add primary key if id column exists
        if (isset($columns['id'])) {
            $columnDefs[0] = "  `id` INT AUTO_INCREMENT PRIMARY KEY";
        }
        
        $sql .= implode(",\n", $columnDefs) . "\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
        
        return $sql;
    }
    
    private function convertToMySQLType($sqliteType)
    {
        switch (strtolower($sqliteType)) {
            case 'integer':
                return 'INT';
            case 'text':
                return 'TEXT';
            case 'real':
                return 'DECIMAL(10,2)';
            case 'blob':
                return 'LONGBLOB';
            case 'datetime':
                return 'DATETIME';
            case 'timestamp':
                return 'TIMESTAMP';
            case 'string':
                return 'VARCHAR(255)';
            case 'boolean':
                return 'TINYINT(1)';
            default:
                return 'TEXT';
        }
    }
}