<?php


namespace Fikusas;

use Fikusas\Log;

require __DIR__ . '../vendor/autoload.php';

class Main
{
    public function startTime()
    {
        $timeStart = microtime(true);
        return $timeStart;
    }

// TODO decide what functions to make

//
//        $logger = new Log\Logger();
//
//        $fileReader = new FileRead();
//
//        $syllables = $fileReader->readHyphenationPatterns();
//
//        $userInteraction = new UserInteraction();
//
//        $userInput = $userInteraction->getUserInput($argv);
//
//        $hyphenate = new WordHyphenator($syllables);
//
//        $sentenceHyphenator = new SentenceHyphenator($logger, $hyphenate);
//
//        $optionDivider = new OptionDivider($hyphenate, $sentenceHyphenator);
//
//        $result = $optionDivider->divideOptions($userInput);
//
//        echo $result . "\n";
//

    public function stopTime (){
        $time_end = microtime(true);

        $time = $time_end - $this->startTime();

        echo "\n script took $time seconds to execute\n";
    }




}
