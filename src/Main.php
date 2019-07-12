<?php


namespace Fikusas;



use Fikusas\Cache;
use Fikusas\FileRead\FileRead;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Log;
use Fikusas\UserInteraction;

class Main
{
    public function startTime()
    {
        $timeStart = microtime(true);
        return $timeStart;
    }


    public function mainApplication($argv)
    {
        $logger = new Log\Logger();

        $cache = new Cache\FileCache('1', 86400);

        $fileReader = new FileRead($cache);

        $syllables = $fileReader->readHyphenationPatterns();

        $userInteraction = new UserInteraction\UserInteraction();

        $userInput = $userInteraction->getUserInput($argv);

        $hyphenate = new WordHyphenator($syllables, $cache);

        $sentenceHyphenator = new SentenceHyphenator($logger, $hyphenate);

        $optionDivider = new UserInteraction\OptionDivider($hyphenate, $sentenceHyphenator);

        $result = $optionDivider->divideOptions($userInput);

        return $result;
    }


    public function stopTime()
    {
        $startTime = $this->startTime();
        $time = microtime(true)-$startTime;

        return $time;
    }


}
