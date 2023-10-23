<?php

namespace CurrencyConverterServer\core;

use CurrencyConverterServer\core\base\Database;
use CurrencyConverterServer\core\base\Result;
use CurrencyConverterServer\core\base\Route;
use CurrencyConverterServer\core\model\Rate;
use CurrencyConverterServer\core\model\Symbol;
use Dotenv\Dotenv;
use voku\helper\HtmlDomParser;
use voku\helper\SimpleHtmlDom;

class Application
{
    public Database $db;

    public Result $result;

    public Route $route;

    public static Application $app;

    public Dotenv $dotenv;

    public \stdClass $currencies;

    public function __construct()
    {
        self::$app = $this;
        $this->dotenv = Dotenv::createImmutable( "./" );
        $this->dotenv->load();

        $this->db = new Database();
        $this->result = new Result();
        $this->route = new Route();

        $this->currencies = json_decode( file_get_contents( "./assets/json/currency-codes.json" ) );
    }

    /**
     * Initialize Application
     *
     * @return void
     */
    public function init(): void
    {
        $action = "";

        if( isset( $_SERVER['HTTP_CC_ACTION'] ) && $_SERVER['HTTP_CC_ACTION'] )
            $action = $_SERVER['HTTP_CC_ACTION'];

        if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] )
            $action = $_REQUEST['action'];

        $this->route->run( $action );
    }
}