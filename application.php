<?php
require __DIR__ . '/vendor/autoload.php';

$main = new Fikusas\Main();
echo $main->mainApplication($argv)."\n";
echo " Script took ". $main->stopTime(). "seconds to execute\n";
// TODO put this to log

