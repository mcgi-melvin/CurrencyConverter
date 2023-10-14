<?php

namespace CurrencyConverterServer\core\supports;

use CurrencyConverterServer\core\Application;

trait DatabaseTrait
{
    abstract function table_name(): string;

    abstract function primary_key(): string;

    abstract function attributes(): array;

    public function save(): bool
    {
        $table_name = $this->table_name();
        $attributes = $this->attributes();

        $params = array_map(fn($attr) => ":$attr", $attributes);

        try {
            $statement = $this->connection->prepare("INSERT INTO $table_name (".implode(", ", $attributes).") VALUES (". implode(", ", $params) .")");

            foreach( $attributes as $attribute ) {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }

            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            Application::$app->result->setMessage( $e->getMessage() );
            return false;
        }
    }

    public function update(): bool
    {
        $table_name = $this->table_name();
        $attributes = $this->attributes();
        $primary_key = $this->primary_key();

        $params = array_map(fn($attr) => "$attr=:$attr ", $attributes);

        $params = array_filter( $params );

        $sql = "UPDATE {$table_name} SET ". implode( ",", $params ) . " WHERE {$primary_key}=:{$primary_key}";

        try {
            $statement = $this->connection->prepare( $sql );

            foreach( $attributes as $attribute ) {
                $statement->bindValue( $attribute, $this->{$attribute} );
            }

            $statement->bindValue( $primary_key, $this->{$primary_key} );

            $statement->execute();

            return true;
        } catch (\PDOException $e) {
            Application::$app->result->setMessage( $e->getMessage() );
            return false;
        }
    }

    public function findAll()
    {
        $table_name = static::table_name();

        $statement = $this->connection->prepare("SELECT * FROM {$table_name}");

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function findOne( $where )
    {
        $table_name = static::table_name();
        $attributes = array_keys( $where );
        $sql = implode( "AND ", array_map( fn($attr) => "$attr=:$attr ", $attributes ) );

        $query = "SELECT * FROM {$table_name} WHERE $sql";

        $statement = $this->connection->prepare($query);

        foreach( $where as $key => $item ) {
            if( is_numeric( $item ) ) {
                $statement->bindValue(":$key", intval( $item ), \PDO::PARAM_INT);
            } else {
                $statement->bindValue(":$key", $item);
            }
        }

        $statement->execute();

        return $statement->fetch(\PDO::FETCH_OBJ);
    }

    public function find( $where )
    {
        $attributes = array_keys( $where );
        $sql = implode( "AND ", array_map( fn($attr) => "$attr = :$attr ", $attributes ) );

        $statement = $this->connection->prepare("SELECT * FROM {$this->table_name()} WHERE $sql");

        foreach( $where as $key => $item ) {
            if( is_numeric( $item ) ) {
                $statement->bindValue(":$key", intval( $item ), \PDO::PARAM_INT);
            } else {
                $statement->bindValue(":$key", $item);
            }
        }

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function delete(): void
    {
        $table_name = static::table_name();

        $statement = $this->connection->prepare("DELETE FROM {$table_name} WHERE id=:id");

        $statement->execute([
            'id' => $this->{$this->primary_key()}
        ]);
    }

    public function get_next_id()
    {
        $statement = $this->connection->prepare("SHOW TABLE STATUS LIKE '{$this->table_name()}'");
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result['Auto_increment'];
    }

    public function get_last_insert_id()
    {
        return $this->connection->lastInsertId();
    }
}