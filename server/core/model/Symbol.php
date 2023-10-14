<?php

namespace CurrencyConverterServer\core\model;

use CurrencyConverterServer\core\base\Model;
use CurrencyConverterServer\core\supports\DatabaseTrait;

class Symbol extends Model
{
    use DatabaseTrait;

    public string $id = "";

    public string $symbol = "";

    public function __construct()
    {
        parent::__construct();
    }

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