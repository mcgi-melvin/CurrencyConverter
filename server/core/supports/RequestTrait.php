<?php

namespace CurrencyConverterServer\core\supports;

trait RequestTrait
{
    private $ch;

    public $request_result;

    abstract function url(): string;

    public function curl_init()
    {
        $this->ch = \curl_init( $this->url() );

        return $this;
    }

    public function options( array $options )
    {
        foreach ( $options as $key => $val ) {
            \curl_setopt($this->ch, $key, $val);
        }

        return $this;
    }

    public function output()
    {
        $output = \curl_exec( $this->ch );

        \curl_close( $this->ch );

        return $output;
    }
}