<?php

namespace CurrencyConverterServer\core\base;

use CurrencyConverterServer\core\Application;

class Database
{
    protected \PDO $connection;

    public function __construct()
    {
        Application::$app->dotenv->required(['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME'])->notEmpty();

        try {
            $this->connection = new \PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
            return true;
        } catch (\Exception $e) {
            Application::$app->result->setMessage( $e->getMessage() );
            return false;
        }
    }

    public function connection(): \PDO
    {
        return $this->connection;
    }
}