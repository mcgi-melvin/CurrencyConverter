<?php

namespace CurrencyConverterServer\core\base;

class Route
{
    private array $routes = [];

    public function add( string $action = "", array $instance = [], string $method = "get" ): void
    {
        $this->routes[strtolower($method)][$action] = $instance;
    }

    private function method(): string
    {
        return strtolower( $_SERVER['REQUEST_METHOD'] );
    }

    public function run( string $action ): void
    {
        $route = array_key_exists($this->method(), $this->routes) ? array_key_exists($action, $this->routes[$this->method()] ) ? $this->routes[$this->method()][$action] : [] : [];

        if( $route ) {
            $instance = new $route[0]();

            call_user_func([$instance, $route[1]]);
        }
    }
}