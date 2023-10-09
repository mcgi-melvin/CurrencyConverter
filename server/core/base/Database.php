<?php

namespace core\base;

use core\Application;

class Database
{
    private string $host = "";

    private string $user = "";

    private string $pass = "";

    private string $db = "";

    protected \PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = new \PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_MULTI_STATEMENTS => true
            ]);
        } catch (\Exception $e) {
            Application::$app->result->
            return false;
        }
    }
}