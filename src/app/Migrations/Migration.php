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

        foreach ($sqlCommands as $table => $sql) {
            if ($this->db->query($sql)) {
                printf(" %s created successfully.".PHP_EOL, $table);
            }else{
                printf("Could not create table: %s".PHP_EOL, $table);
            }
        }
    }
}