<?php

declare(strict_types=1);


namespace Fikusas;


use Fikusas\Cache;
use Fikusas\FileRead\FileRead;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Log;
use Fikusas\UserInteraction;
use Fikusas\TimeKeeping\TimeKeeping;

class Main
{
    public function mainApplication($argv)
    {
        $timeKeeping = new TimeKeeping();

        $timeKeeping->startTime();

        $logger = new Log\Logger();

        $cache = new Cache\FileCache('cache', 86400);

        $fileReader = new FileRead($cache);

        $syllables = $fileReader->readHyphenationPatterns();

        $userInteraction = new UserInteraction\UserInteraction();

        $userInput = $userInteraction->getUserInput($argv);

        $hyphenate = new WordHyphenator($syllables, $cache);

        $sentenceHyphenator = new SentenceHyphenator($logger, $hyphenate);

        $fileReadFromInput = new FileReadFromInput();

        $optionDivider = new UserInteraction\OptionDivider($hyphenate, $sentenceHyphenator, $fileReader,
            $fileReadFromInput);

        $result = $optionDivider->divideOptions($userInput);

        $logger->info(sprintf("Completed in %.2f seconds", $timeKeeping->stopTime()));

        return $result;
    }
}
