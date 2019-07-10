<?php


namespace Fikusas;

use Fikusas\Log;

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

        $fileReader = new FileRead();

        $syllables = $fileReader->readHyphenationPatterns();

        $userInteraction = new UserInteraction();

        $userInput = $userInteraction->getUserInput($argv);

        $hyphenate = new WordHyphenator($syllables);

        $sentenceHyphenator = new SentenceHyphenator($logger, $hyphenate);

        $optionDivider = new OptionDivider($hyphenate, $sentenceHyphenator);

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
