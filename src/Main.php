<?php


namespace Fikusas;



use Fikusas\Cache;
use Fikusas\FileRead\FileRead;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Log;
use Fikusas\UserInteraction;

class Main
{
//    private $logger;
//    private $cache;
//    private $fileReader;
//    private $syllables;
//    private $userInteraction;
//
//    /**
//     * Main constructor.
//     * @param $logger
//     * @param $cache
//     * @param $fileReader
//     * @param $syllables
//     * @param $userInteraction
//     */
//    public function __construct($logger, $cache, $fileReader, $syllables, $userInteraction)
//    {
//        $this->logger = $logger;
//        $this->cache = $cache;
//        $this->fileReader = $fileReader;
//        $this->syllables = $syllables;
//        $this->userInteraction = $userInteraction;
//    }


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

        $fileReadFromInput = new FileReadFromInput();

        $optionDivider = new UserInteraction\OptionDivider($hyphenate, $sentenceHyphenator, $fileReader, $fileReadFromInput);

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
