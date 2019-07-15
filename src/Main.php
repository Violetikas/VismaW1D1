<?php

declare(strict_types=1);


namespace Fikusas;

use Fikusas\Cache\FileCache;
use Fikusas\FileRead\FileRead;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Log\Logger;
use Fikusas\UserInteraction\OptionDivider;
use Fikusas\UserInteraction\UserInteraction;
use Fikusas\TimeKeeping\TimeKeeping;

class Main
{

    private $timeKeeping;
    private $logger;
    private $cache;
    private $fileReader;
    private $userInteraction;
    private $hyphenate;
    private $sentenceHyphenator;
    private $fileReadFromInput;
    private $optionDivider;
    private $wordHyphenator;

    /**
     * Main constructor.
     * @param $timeKeeping
     * @param $logger
     * @param $cache
     * @param $fileReader
     * @param $userInteraction
     * @param $hyphenate
     * @param $sentenceHyphenator
     * @param $fileReadFromInput
     * @param $optionDivider
     * @param $wordHyphenator
     */
    public function __construct(
        TimeKeeping $timeKeeping,
        Logger $logger,
        FileCache $cache,
        FileRead $fileReader,
        UserInteraction $userInteraction,
        WordHyphenator $hyphenate,
        SentenceHyphenator $sentenceHyphenator,
        FileReadFromInput $fileReadFromInput,
        OptionDivider $optionDivider,
        WordHyphenator $wordHyphenator)
    {
        $this->timeKeeping = $timeKeeping;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->fileReader = $fileReader;
        $this->userInteraction = $userInteraction;
        $this->hyphenate = $hyphenate;
        $this->sentenceHyphenator = $sentenceHyphenator;
        $this->fileReadFromInput = $fileReadFromInput;
        $this->optionDivider = $optionDivider;
        $this->wordHyphenator = $wordHyphenator;
    }


    /**
     * @param $argv
     * @return string
     */
    public function mainApplication($argv)
    {

        $this->timeKeeping->startTime();
        $userInput = $this->userInteraction->getUserInput($argv);
        $result=$this->optionDivider->divideOptions($userInput);
        $this->logger->info(sprintf("Completed in %.6f seconds", $this->timeKeeping->stopTime()));
        return $result;

    }
}
