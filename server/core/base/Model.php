<?php

namespace core\base;

abstract class Model extends Database
{
    abstract function table_name(): string;

    abstract function primary_key(): string;

    abstract function attributes(): array;

    public function save()
    {
        $query = "INSERT INTO {$this->table_name()}";

        $statement = $this->connection->prepare($query);

    }
}