<?php 

namespace App\Sh;

defined('CPATH') or exit('No direct script access allowed');

class Sh
{
    private $version = '1.0.0';

    private function colorText($text, $colorCode)
    {
        return "\033[" . $colorCode . "m" . $text . "\033[0m";

        /** USAGE:
         * echo colorText("Success! Controller created.", "32") . "\n";
        *  echo colorText("Error! Something went wrong.", "31") . "\n";
        *  echo colorText("Warning! Check your input.", "33") . "\n";
         */
    }


    private function migration($name = null)
    {
        if(!$name) {
            echo $this->colorText("\nPlease provide a migration name", "33") . "\n";
            die();
        }

        // Convert name to PascalCase (if not already)
        $className = ucfirst($name);

        // Define the file path
        $controllerPath = CPATH . "app" . DS . "controllers" . DS . "{$className}.php";

        // Check if the file already exists
        if (file_exists($controllerPath)) {
            echo $this->colorText("\nController '{$className}.php' already exists!", "33") . "\n";
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
            echo $this->colorText("\nController '{$className}.php' has been created successfully!", "32") . "\n";
        } else {
            echo $this->colorText("\nFailed to create controller file!", "31") . "\n";
            die();
        }


    }

    private function model($name = null)
    {
        echo 'model class';
    }

    private function controller()
    {
        echo 'controller class';
    }

    public function make($command)
    {
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