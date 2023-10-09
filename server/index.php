<?php
require_once "vendor/autoload.php";

if( !defined( 'API_KEY' ) ) define( 'API_KEY', "" );
if( !defined( 'API_SYMBOLS' ) ) define( "API_SYMBOLS", "" );

$app = new \core\Application();

$app->init();