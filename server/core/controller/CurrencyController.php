<?php

namespace CurrencyConverterServer\core\controller;

use CurrencyConverterServer\core\base\Controller;
use CurrencyConverterServer\core\model\Rate;

class CurrencyController extends Controller
{
    private array $codes = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function fetch(): void
    {
        $rate_db = new Rate();

        $this->codes = array_keys( (array) $this->currencies );

        $last_uploaded_rate = $rate_db->findLast();

        if( $last_uploaded_rate ) {
            $last_code = array_search( $last_uploaded_rate->base_currency, $this->codes );

            $index = $last_code + 1;

            if( $index === count( $this->codes ) )
                $index = 0;

            $code1 = $this->codes[$index];

            $this->save_rates( $code1 );
        } else {
            $this->save_rates( $this->codes[0] );
        }
    }

    public function getData(): void
    {
        $rate = new Rate();

        // $currencies = $rate->findAll();

        echo json_encode([
            'currency_codes' => $this->currency_codes()
        ]);
        exit;
    }

    public function convert()
    {
        $rate = new Rate();

        $values = $rate->find([
            'base_currency' => $_REQUEST['base']
        ]);

        echo json_encode( $values );
        exit;
    }

    private function currency_codes(): array
    {
        $symbols = json_decode( file_get_contents( "./assets/json/currency-symbols.json" ) );

        $currencies = [];

        foreach ( $this->currencies as $code => $name ) {
            if( in_array( $code, array_column( $symbols, "abbreviation" ) ) ) {
                $index = array_search( $code, array_column( $symbols, "abbreviation" ) );
                $a = [];
                $a['name'] = $name;
                $a['code'] = $code;
                $a['symbol'] = $symbols[$index]->symbol ? utf8_decode( $symbols[$index]->symbol ) : "";
                $currencies[] = $a;
            }
        }

        return $currencies;
    }

    private function save_rates( $code1 ): void
    {
        foreach ( $this->codes as $code2 ) {
            if( $code1 === $code2 ) continue;
            $url = "https://www.google.com/finance/quote/{$code1}-{$code2}?hl=en";

            $html = file_get_contents( $url );

            preg_match_all('%(<div class="YMlKec fxKbKc">.*?</div>)%i', $html,$posts,PREG_SET_ORDER);

            $value = 0;

            if( $posts && $posts[0] ) $value = (float) strip_tags( $posts[0][0] );

            $rate = new Rate();

            $rate->load_data([
                'base_currency' => $code1,
                'converting_currency' => $code2,
                'value' => (float) $value
            ]);

            $rate->save();
        }
    }
}