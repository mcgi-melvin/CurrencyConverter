<?php

namespace CurrencyConverterServer\core;

use CurrencyConverterServer\core\base\Database;
use CurrencyConverterServer\core\base\Result;
use CurrencyConverterServer\core\model\Rate;
use CurrencyConverterServer\core\model\Symbol;
use Dotenv\Dotenv;
use voku\helper\HtmlDomParser;
use voku\helper\SimpleHtmlDom;

class Application
{
    public Database $db;

    public Result $result;

    public static Application $app;

    public Dotenv $dotenv;

    public \stdClass $currency_codes;

    public array $codes = [];

    public function __construct()
    {
        self::$app = $this;
        $this->dotenv = Dotenv::createImmutable( "./" );
        $this->dotenv->load();

        $this->db = new Database();
        $this->result = new Result();

        $this->currency_codes = json_decode( file_get_contents( "./assets/json/currency-codes.json" ) );

        $this->codes = array_keys( json_decode( file_get_contents("./assets/json/currency-codes.json"), true ) );
    }

    public function init()
    {
        $rate_db = new Rate();

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

    private function save_rates( $code1 )
    {
        foreach ( $this->codes as $code2 ) {
            if( $code1 === $code2 ) continue;
            $url = "https://www.google.com/finance/quote/{$code1}-{$code2}?hl=en";

            /*
            $dom = HtmlDomParser::file_get_html("https://www.google.com/finance/quote/{$code1}-{$code2}?hl=en");

            $element = $dom->findOne('.fxKbKc');

            if( !$element->innertext ) continue;
            */

            $html = file_get_contents( $url );

            preg_match_all('%(<div class="YMlKec fxKbKc">.*?</div>)%i', $html,$posts,PREG_SET_ORDER);

            $value = 0;

            if( $posts && $posts[0] ) $value = (float) strip_tags( $posts[0][0] );

            echo $code1 . "-" . $code2 . "=" . $value . "<br />";

            $rate = new Rate();

            $rate->base_currency = $code1;
            $rate->converting_currency = $code2;
            $rate->value = (float) $value;

            $rate->save();
        }
    }

    function fetchAndProcessUrls(array $urls, callable $f) {

        $multi = curl_multi_init();
        $reqs  = [];

        foreach ($urls as $url) {
            $req = curl_init();
            curl_setopt($req, CURLOPT_URL, $url);
            // curl_setopt($req, CURLOPT_HEADER, 0);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($req, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($req, CURLOPT_SSL_VERIFYPEER, 1);
            // curl_setopt($req, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // curl_setopt($req, CURLOPT_NOBODY, 1);
            curl_multi_add_handle($multi, $req);
            $reqs[] = $req;
        }

        // While we're still active, execute curl
        $active = null;

        // Execute the handles
        do {
            $mrc = curl_multi_exec($multi, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($multi) != -1) {
                do {
                    $mrc = curl_multi_exec($multi, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        // Close the handles
        foreach ($reqs as $req) {
            // $f(curl_getinfo($req));
            $f(curl_multi_getcontent($req));
            curl_multi_remove_handle($multi, $req);
        }
        curl_multi_close($multi);
    }
}