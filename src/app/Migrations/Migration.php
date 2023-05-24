<?php

namespace App\Migrations;

use App\DB;

class Migration
{
    public function __construct(private DB $db)
    {
        //
    }

    public function run()
    {
        $sqlCommands['database'] = "CREATE DATABASE IF NOT EXISTS mydb";

        $sqlCommands['users'] = "CREATE TABLE IF NOT EXISTS users( " .
            "id INT NOT NULL AUTO_INCREMENT, " .
            "name VARCHAR(50) NOT NULL, " .
            "email VARCHAR(100) NOT NULL, " .
            "password text NOT NULL, " .
            "created_at TIMESTAMP NOT NULL, " .
            "PRIMARY KEY ( id )); ";

        $sqlCommands['blogs'] = "CREATE TABLE IF NOT EXISTS blogs( " .
            "id INT NOT NULL AUTO_INCREMENT, " .
            "user_id INT NOT NULL , " .
            "title VARCHAR(256) NOT NULL, " .
            "image_url text NOT NULL, " .
            "description text NOT NULL, " .
            "created_at TIMESTAMP NOT NULL, " .
            "PRIMARY KEY ( id )," .
            "FOREIGN KEY (user_id) REFERENCES users(id)); ";

        $sqlCommands['comments'] = "CREATE TABLE IF NOT EXISTS comments( " .
            "id INT NOT NULL AUTO_INCREMENT, " .
            "blog_id INT NOT NULL , " .
            "mail VARCHAR(100) NOT NULL, " .
            "url text NOT NULL, " .
            "comment text NOT NULL, " .
            "created_at TIMESTAMP NOT NULL, " .
            "PRIMARY KEY ( id )," .
            "FOREIGN KEY (blog_id) REFERENCES blogs(id)); ";

        foreach ($sqlCommands as $table => $sql) {
            if ($this->db->query($sql)) {
                printf(" %s created successfully.".PHP_EOL, $table);
            }else{
                printf("Could not create table: %s".PHP_EOL, $table);
            }
        }
    }
}