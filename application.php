<?php
require __DIR__ . '/vendor/autoload.php';

//use Fikusas\Main;
//
//new Main();

$logger = new Fikusas\Log\Logger();
$time_start = microtime(true);

$fileReader = new \Fikusas\FileRead();

$syllables = $fileReader->readHyphenationPatterns();

$userInteraction = new \Fikusas\UserInteraction();

$userInput = $userInteraction->getUserInput($argv);

$hyphenate = new \Fikusas\WordHyphenator($syllables);

$sentenceHyphenator = new \Fikusas\SentenceHyphenator($logger, $hyphenate);

$optionDivider = new Fikusas\OptionDivider($hyphenate, $sentenceHyphenator);

$result = $optionDivider->divideOptions($userInput);

echo $result . "\n";

$time_end = microtime(true);

$time = $time_end - $time_start;

echo "\n script took $time seconds to execute\n";