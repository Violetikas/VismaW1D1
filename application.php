<?php
require __DIR__ . '/vendor/autoload.php';

$main = new Fikusas\Main();
echo $main->mainApplication($argv)."\n";



//cache ('cache', 86400)
// $logger->info(sprintf("Completed in %.2f seconds", $timeKeeping->stopTime()));