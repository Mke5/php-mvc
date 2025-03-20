<?php

namespace App\Migrations;

defined('ROOTPATH') OR exit('Access Denied!');

class UserMigration
{
    public function up()
    {
        return "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    INDEX (name),
    email VARCHAR(255) NOT NULL,
    UNIQUE (email),
    password VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);";
    }

    public function down()
    {
        return "DROP TABLE IF EXISTS users;";
    }
}