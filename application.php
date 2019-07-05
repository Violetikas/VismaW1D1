<?php

require __DIR__ . '/vendor/autoload.php';

$reader = new \Fikusas\FileRead();
$values = $reader->read_values();
$word = '';

if (count($argv) < 2) {
    throw new RuntimeException('Must provide word!');
} else $word = $argv[1];
$hyphenate = new \Fikusas\Hyphenate($values);
$result = $hyphenate->hyphenate($word);
$printResults = new \Fikusas\PrintResults();
$printResults->print_result($result);


