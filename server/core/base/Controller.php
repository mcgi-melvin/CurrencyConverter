<?php

namespace CurrencyConverterServer\core\base;

use CurrencyConverterServer\core\Application;

class Controller
{
    protected \stdClass $currencies;

    public function __construct()
    {
        $this->currencies = Application::$app->currencies;
    }
}