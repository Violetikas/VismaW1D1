<?php
require __DIR__ . '/vendor/autoload.php';

$time_start = microtime(true);

$fileReader = new \Fikusas\FileRead();

$sylables = $fileReader->readHyphenationPatterns();

$userInteraction = new \Fikusas\UserInteraction();

$userInput = $userInteraction->getUserInput();

$hyphenate = new \Fikusas\Hyphenate($sylables, $userInput);

$result = $hyphenate->hyphenate($userInput->getUserInput());

$printResults = new \Fikusas\Hyphenate($sylables, $userInput);

$printResults->printResult($result);

$time_end = microtime(true);

$time = $time_end - $time_start;

echo "\n script took $time seconds to execute\n";