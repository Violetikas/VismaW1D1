<?php

require __DIR__ . '/../vendor/autoload.php';


use Fikusas\API\ApiMain;
use Fikusas\API\Router;
//$API = new ApiMain();
//$API->handle();

$API = new Router();
$API->handle();
