<?php
require __DIR__ . '/vendor/autoload.php';

$time_start = microtime(true);

$fileReader = new \Fikusas\FileRead();

$sylables = $fileReader->readHyphenationPatterns();

$userInteraction = new \Fikusas\UserInteraction();

$userInput = $userInteraction->getUserInput($argv);

$hyphenate = new \Fikusas\WordHyphenator($sylables);

$sentenceHyphenator = new \Fikusas\SentenceHyphenator($hyphenate);

$optionDivider = new Fikusas\OptionDivider($hyphenate, $sentenceHyphenator);

$result = $optionDivider->divideOptions($userInput);

echo $result . "\n";

$time_end = microtime(true);

$time = $time_end - $time_start;

echo "\n script took $time seconds to execute\n";