<?php

namespace core\model;

use core\base\Model;

class Symbol extends Model
{
    public string $id = "";

    public string $symbol = "";


    function table_name(): string
    {
        return "symbols";
    }

    function primary_key(): string
    {
        return "id";
    }

    function attributes(): array
    {
        return ['symbol'];
    }
}