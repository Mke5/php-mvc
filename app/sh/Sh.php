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

         // Convert name to PascalCase (if not already)
        $className = ucfirst($this->singularize($name));

         // Define the file path
        $modelPath = CPATH . "app" . DS . "models" . DS . "{$className}.php";

        // Check if the file already exists
        if (file_exists($modelPath)) {
            echo $this->colorText("\nWarning: Model '{$className}' already exists!", "33") . "\n";
            die();
        }


        // Template for the model file
        $modelTemplate = <<<PHP
        <?php

        namespace App\Models;

        defined('ROOTPATH') OR exit('Access Denied!');

        /**
         * {$className} class
         */
        class {$className}
        {
            use Model;
            use Database;

            protected \$table = 'users'; // Change to relevant table
            protected \$allowedColumns = ['email', 'password'];

            public function validate(\$data)
            {
                \$this->errors = [];

                if(empty(\$data['email'])) {
                    throw new \Exception("Email is required");
                } elseif (!filter_var(\$data['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid email format");
                }

                if(empty(\$data['password'])) {
                    throw new \Exception("Password is required");
                }

                return true;
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

    private function migration()
    {
        echo 'controller class';
    }

    public function make($command)
    {
        if (!isset($command[2])) {
            echo $this->colorText("\nError: Missing argument. Usage: make:migration {name}", "31") . "\n";
            die();
        }

         // Ensure the first character is a valid letter
        if (!ctype_alpha($command[2])) {
            echo $this->colorText("\nWarning: Migration name must start with a letter.", "33") . "\n";
            return;
        }

        switch ($command[1]) {
            case 'make:migration':
                $this->migration($command[2]);
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