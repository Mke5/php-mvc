<?php 

namespace App\Sh;

defined('CPATH') or exit('No direct script access allowed');

class Sh
{
    private $version = '1.0.0';
    public function make()
    {
        echo 'Sh class';
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
        ";
    }
}