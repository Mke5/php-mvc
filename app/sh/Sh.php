<?php 

namespace App\Sh;

defined('CPATH') or exit('No direct script access allowed');

class Sh
{
    private $version = '1.0.0';


    private function singularize($word)
    {
        // Basic irregular words
        $irregular = [
            'people' => 'person',
            'men' => 'man',
            'women' => 'woman',
            'children' => 'child',
            'teeth' => 'tooth',
            'feet' => 'foot',
            'mice' => 'mouse',
            'geese' => 'goose',
            'databases' => 'database'
        ];

        // Check for irregular words
        if (isset($irregular[strtolower($word)])) {
            return ucfirst($irregular[strtolower($word)]);
        }

        // Common plural rules
        if (preg_match('/(oes|ses|xes|ches|shes|zes)$/i', $word)) {
            return substr($word, 0, -2);
        }
        if (preg_match('/(ies)$/i', $word)) {
            return substr($word, 0, -3) . 'y';
        }
        if (preg_match('/(s)$/i', $word) && !preg_match('/(ss)$/i', $word)) {
            return substr($word, 0, -1);
        }

        return ucfirst($word);
    }

    private function pluralize($word)
    {
        // Basic irregular words
        $irregular = [
            'person' => 'people',
            'man' => 'men',
            'woman' => 'women',
            'child' => 'children',
            'tooth' => 'teeth',
            'foot' => 'feet',
            'mouse' => 'mice',
            'goose' => 'geese',
            'database' => 'databases'
        ];

        // Check for irregular words
        if (isset($irregular[strtolower($word)])) {
            return $irregular[strtolower($word)];
        }

        // Common plural rules
        if (preg_match('/(ch|sh|x|s|z)$/i', $word)) {
            return $word . 'es';
        }
        if (preg_match('/(y)$/i', $word) && !preg_match('/(ay|ey|iy|oy|uy)$/i', $word)) {
            return substr($word, 0, -1) . 'ies';
        }

        return $word . 's';
    }


    public function colorText($text, $colorCode)
    {
        return "\033[" . $colorCode . "m" . $text . "\033[0m";

        /** USAGE:
         * echo colorText("Success! Controller created.", "32") . "\n";
        *  echo colorText("Error! Something went wrong.", "31") . "\n";
        *  echo colorText("Warning! Check your input.", "33") . "\n";
         */
    }


    private function controller($name = null)
    {
        if(!$name) {
            echo $this->colorText("\nWarning: Please provide a Controller name", "33") . "\n";
            die();
        }

        // Convert name to PascalCase (if not already)
        $className = ucfirst($name);

        // Define the file path
        $controllerPath = CPATH . "app" . DS . "controllers" . DS . "{$className}.php";

        // Check if the file already exists
        if (file_exists($controllerPath)) {
            echo $this->colorText("\nWarning: Controller '{$className}.php' already exists!", "33") . "\n";
            die();
        }


        $controllerTemplate = <<<PHP
        <?php 
        
        namespace Controller;
        
        defined('ROOTPATH') OR exit('Access Denied!');
        
        /**
         * {$className} class
         */
        class {$className} extends Controller
        {
        
            public function index()
            {
                \$this->view('{$name}');
            }
        
        }
        PHP;

        // Attempt to create the file
        if (file_put_contents($controllerPath, $controllerTemplate) !== false) {
            echo $this->colorText("\nSuccess: Controller '{$className}.php' has been created successfully!", "32") . "\n";
        } else {
            echo $this->colorText("\nError: Failed to create controller file!", "31") . "\n";
            die();
        }


    }

    private function model($name = null)
    {
        
        if(!$name) {
            echo $this->colorText("\nWarning: Please provide a Model name", "33") . "\n";
            die();
        }

        // Ensure class name starts with a letter
        if (!ctype_alpha($name[0])) {
            echo $this->colorText("\nWarning: Model name must start with a letter.", "31") . "\n";
            die();
        }

        // Convert name to singular PascalCase
        $className = ucfirst(rtrim($name, 's')); // Removes trailing 's' if present

        // Define the file path
        $modelPath = CPATH . "app" . DS . "models" . DS . "{$className}.php";

        // Check if the file already exists
        if (file_exists($modelPath)) {
            echo $this->colorText("\nWarning: Model '{$className}' already exists!", "33") . "\n";
            die();
        }

        $tableName = strtolower($className) . 's';

        $modelTemplate = <<<PHP
        <?php
        
        namespace App\Models;
        
        defined('ROOTPATH') OR exit('Access Denied!');
        
        /**
         * {$className} Model
         */
        class {$className}
        {
            use Model;
            use Database;
        
            protected \$table = '{$tableName}';
            protected \$allowedColumns = [];
        
            /**
             * Find a record by ID
             */
            public function findById(\$id)
            {
                \$sql = "SELECT * FROM \$this->table WHERE id = :id LIMIT 1";
                \$result = \$this->read(\$sql, ['id' => \$id]);
                return \$result ? \$result[0] : false;
            }
        
            /**
             * Find records by a condition
             */
            public function findWhere(\$column, \$value)
            {
                \$sql = "SELECT * FROM \$this->table WHERE \$column = :value";
                return \$this->read(\$sql, ['value' => \$value]);
            }
        
            /**
             * Insert a new record
             */
            public function insert(\$data)
            {
                \$keys = array_keys(\$data);
                \$columns = implode(", ", \$keys);
                \$placeholders = ":" . implode(", :", \$keys);
        
                \$sql = "INSERT INTO \$this->table (\$columns) VALUES (\$placeholders)";
                return \$this->write(\$sql, \$data);
            }
        
            /**
             * Update a record by ID
             */
            public function update(\$id, \$data)
            {
                \$setPart = "";
                foreach (\$data as \$key => \$value) {
                    \$setPart .= "\$key = :\$key, ";
                }
                \$setPart = rtrim(\$setPart, ", ");
                
                \$sql = "UPDATE \$this->table SET \$setPart WHERE id = :id";
                \$data['id'] = \$id;
        
                return \$this->write(\$sql, \$data);
            }
        
            /**
             * Delete a record by ID
             */
            public function deleteOne(\$id)
            {
                \$sql = "DELETE FROM \$this->table WHERE id = :id";
                return \$this->write(\$sql, ['id' => \$id]);
            }
        
            /**
             * Delete all records
             */
            public function deleteAll()
            {
                \$sql = "DELETE FROM \$this->table";
                return \$this->write(\$sql);
            }
        
            /**
             * Count total records
             */
            public function count()
            {
                \$sql = "SELECT COUNT(*) AS total FROM \$this->table";
                \$result = \$this->read(\$sql);
                return \$result ? \$result[0]->total : 0;
            }
        
            /**
             * Get the first record
             */
            public function first()
            {
                \$sql = "SELECT * FROM \$this->table ORDER BY id ASC LIMIT 1";
                \$result = \$this->read(\$sql);
                return \$result ? \$result[0] : false;
            }
        
            /**
             * Get the last record
             */
            public function last()
            {
                \$sql = "SELECT * FROM \$this->table ORDER BY id DESC LIMIT 1";
                \$result = \$this->read(\$sql);
                return \$result ? \$result[0] : false;
            }
        
            /**
             * Check if a record exists
             */
            public function exists(\$column, \$value)
            {
                \$sql = "SELECT COUNT(*) AS count FROM \$this->table WHERE \$column = :value";
                \$result = \$this->read(\$sql, ['value' => \$value]);
                
                return \$result && \$result[0]->count > 0;
            }
        }
        PHP;

        // Attempt to create the file
        if (file_put_contents($modelPath, $modelTemplate) !== false) {
            echo $this->colorText("\nSuccess: Model '{$className}' has been created successfully!", "32") . "\n";
        } else {
            echo $this->colorText("\nError: Failed to create Model!", "31") . "\n";
            die();
        }


    }

    private function migration($args = [])
    {
        if (!isset($args[2])) {
            echo $this->colorText("\nWarning: Please provide a table name", "33") . "\n";
            die();
        }
    
        $tableName = strtolower($args[2]);
        $className = ucfirst($this->singularize($args[2])) . "Migration";
    
        // Default columns
        $columns = [
            "id INT AUTO_INCREMENT PRIMARY KEY",
        ];

        $columnEnd = [
            "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            "deleted_at TIMESTAMP NULL DEFAULT NULL"
        ];
    
        $foreignKeys = [];
    
        // Parse additional columns
        if (count($args) > 3) {
            for ($i = 3; $i < count($args); $i++) {
                $columnParts = explode(":", $args[$i]);
    
                if (count($columnParts) >= 2) {
                    [$columnName, $columnType] = $columnParts;
                    $foreignTable = $columnParts[2] ?? null;
    
                    // Prevent self-referencing foreign keys
                    if ($foreignTable && $foreignTable === $tableName) {
                        echo $this->colorText("\nError: '$columnName' cannot reference the same table '$tableName'!", "31") . "\n";
                        die();
                    }
    
                    switch (strtolower($columnType)) {
                        case "string":
                            $columns[] = "$columnName VARCHAR(255) NOT NULL";
                            break;
                        case "text":
                            $columns[] = "$columnName TEXT NOT NULL";
                            break;
                        case "integer":
                            $columns[] = "$columnName INT NOT NULL";
                            if ($foreignTable) {
                                $foreignKeys[] = "FOREIGN KEY ($columnName) REFERENCES $foreignTable(id) ON DELETE CASCADE";
                            }
                            break;
                        case "boolean":
                            $columns[] = "$columnName TINYINT(1) NOT NULL DEFAULT 0";
                            break;
                        case "datetime":
                            $columns[] = "$columnName DATETIME NOT NULL";
                            break;
                        default:
                            echo $this->colorText("\nWarning: Unknown column type '{$columnType}'", "33") . "\n";
                            die();
                    }
                } else {
                    echo $this->colorText("\nWarning: Invalid column format for '{$args[$i]}'", "33") . "\n";
                    die();
                }
            }
        }

        // Add foreign keys at the end
        if (!empty($foreignKeys)) {
            $columns = array_merge($columns, $foreignKeys);
        }

        $columns = array_merge($columns, $columnEnd);
        
    
        // Format filename: create_{table}_table with timestamp
        $filename = date('Ymd_His') . "_create_{$tableName}_table.php";
        $filepath = CPATH . "app" . DS . "migrations" . DS . $filename;
    
        // Generate SQL
        $sql = "CREATE TABLE {$tableName} (\n    " . implode(",\n    ", $columns) . "\n);";

    
        $migrationTemplate = <<<PHP
        <?php
    
        namespace App\Migrations;
    
        defined('ROOTPATH') OR exit('Access Denied!');
    
        class {$className}
        {
            public function up()
            {
                return "{$sql}";
            }
    
            public function down()
            {
                return "DROP TABLE IF EXISTS {$tableName};";
            }
        }
        PHP;
    
        // Create the migration file
        if (file_put_contents($filepath, $migrationTemplate) !== false) {
            echo $this->colorText("\nSuccess: Migration '{$filename}' has been created!", "32") . "\n";
        } else {
            echo $this->colorText("\nError: Failed to create migration file!", "31") . "\n";
            die();
        }

    }

    public function make($command)
    {
        if (!isset($command[2])) {
            echo $this->colorText("\nError: Missing argument. Usage: make:tool {name}", "31") . "\n";
            die();
        }

        // Ensure the first character is a valid letter
        if (!ctype_alpha($command[2])) {
            echo $this->colorText("\nWarning: Tool name must start with a letter.", "33") . "\n";
            return;
        }

        switch ($command[1]) {
            case 'make:migration':
                $this->migration($command);
                break;
            case 'make:model':
                $this->model($command[2]);
                break;
            case 'make:controller':
                $this->controller($command[2]);
                break;
            default:
                die("Unknown mode: $command[1]");
                break;
        }
    }

    public function db(){
        echo 'db class';
    }

    public function migrate(){
        echo 'migrate class';
    }

    public function help() {

echo "
    Sh v$this->version Command Line Interface

    Database:
        db:create               Create a new database
        db:refresh              Reset and re-run all migrations and seeds
        db:drop                 Drop a database
        db:migrate              Run all pending migrations
        db:seed                 Seed the database with records
        db:rollback             Rollback the last database migration
        db:status               Show the status of each migration


    Migrations:
        make:migration             Create a new migration file
        make:model                 Create a new model file
        make:controller            Create a new controller file
";
    }
}