<?php

require __DIR__ . '/vendor/autoload.php';

$fileReader = new \Fikusas\FileRead();
$sylables = $fileReader->readHyphenationPatterns();

$userInteraction = new \Fikusas\UserInteraction();
$userVariables = $userInteraction->getUserInput();

$hyphenate = new \Fikusas\Hyphenate($sylables);
$result = $hyphenate->hyphenate($userVariables->getUserInput());
$printResults = new \Fikusas\UserInteraction();
$printResults->printResult($result);

