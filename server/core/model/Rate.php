<?php

namespace CurrencyConverterServer\core\model;

use CurrencyConverterServer\core\base\Model;
use CurrencyConverterServer\core\supports\DatabaseTrait;
use CurrencyConverterServer\core\supports\RequestTrait;

class Rate extends Model
{
    use DatabaseTrait, RequestTrait;

    public string $id = "";

    public string $base_currency = "";

    public string $converting_currency = "";

    public float $value = 0;

    function table_name(): string
    {
        return "rates";
    }

    function primary_key(): string
    {
        return "id";
    }

    function attributes(): array
    {
        return ['base_currency', 'converting_currency', 'value'];
    }

    function url(): string
    {
        return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['HTTP_HOST'],
            $_SERVER['REQUEST_URI'] . "/assets/json/currency-codes.json"
        );
    }
}