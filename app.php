<?php

include __DIR__ . '/vendor/autoload.php';
 date_default_timezone_set('UTC');
ini_set('display_errors', false);
error_reporting(-1);

$server = new Mbrevda\LogServer\ServerRunner;

$server();
