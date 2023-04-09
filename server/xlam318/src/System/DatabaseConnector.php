<?php

namespace Src\System;

use \PDO;

class DatabaseConnector
{
    private $dbConnection = null;

    public function __construct(
        String $database,
        String $username,
        String $password,
        String $host = 'localhost',
        String $port = '3306'
    ) {


        $this->dbConnection = new PDO(
            "mysql:host=$host;port=$port;dbname=$database",
            $username,
            $password
        );
    }

    public function getConnection()
    {
        $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->dbConnection;
    }
}
