<?php
require_once "vendor/autoload.php";

header('Access-Control-Allow-Origin: http://localhost:5173');
header("Access-Control-Allow-Headers: Authorization, X-Requested-With,  Content-Type, Accept");

$app = new \CurrencyConverterServer\core\Application();

$app->route->add( "fetch_currencies", [\CurrencyConverterServer\core\controller\CurrencyController::class, "fetch"] );

$app->route->add( "get_data", [\CurrencyConverterServer\core\controller\CurrencyController::class, "getData"] );

$app->route->add( "convert_currency", [\CurrencyConverterServer\core\controller\CurrencyController::class, "convert"] );

$app->init();