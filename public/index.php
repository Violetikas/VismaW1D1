<?php

require __DIR__ . '/../vendor/autoload.php';


use Fikusas\API\ApiMain;
$API = new ApiMain();
$API->handle();
//echo $API->getWords();
