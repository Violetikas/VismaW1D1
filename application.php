<?php

require __DIR__ . '/vendor/autoload.php';

$reader = new \Fikusas\FileRead();
$values = $reader->read_values();
$userInput = '';

if (count($argv) < 2) {

    echo "Use commands:\n $argv[0] -w [userInput]\n $argv[0] -s [sentence]\n $argv[0] -email [validate your email]";
} else

    if (isset($argv[1]) && isset($argv[2])){
        $userOption = $argv[1];
        $userInput = $argv[2];
        //TODO divide user options for functions to use every option separately
    }

$hyphenate = new \Fikusas\Hyphenate($values);
$result = $hyphenate->hyphenate($userInput);
$printResults = new \Fikusas\PrintResults();
$printResults->print_result($result);

