<?php

namespace Src\System;

use PDO;

class DatabaseConnector
{
    private $dbConnection = null;
    public $status;
    public $message = null;

    public function __construct(
        String $database,
        String $username,
        String $password,
        String $host = 'localhost',
        String $port = '3306'
    ) {

        try {
            $this->dbConnection = new PDO(
                "mysql:host=$host;port=$port;dbname=$database",
                $username,
                $password
            );
            $this->status = 'success';
        } catch (\PDOException $e) {
            $this->status = 'error';
            $this->message = $e->getMessage();
        }
    }

    public function getConnection() {
        if ($this->status === 'success') {
            return $this->dbConnection;
        }
        return $this->status;
    }
}
