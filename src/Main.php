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
use Psr\SimpleCache\CacheInterface;

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
     */
    public function __construct(
        TimeKeeping $timeKeeping,
        Log\Logger $logger,
        CacheInterface $cache,
        FileRead $fileReader,
        UserInteraction\UserInteraction $userInteraction,
        WordHyphenator $hyphenate,
        SentenceHyphenator $sentenceHyphenator,
        FileReadFromInput $fileReadFromInput,
        UserInteraction\OptionDivider $optionDivider
    ) {
        $this->timeKeeping = $timeKeeping;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->fileReader = $fileReader;
        $this->userInteraction = $userInteraction;
        $this->hyphenate = $hyphenate;
        $this->sentenceHyphenator = $sentenceHyphenator;
        $this->fileReadFromInput = $fileReadFromInput;
        $this->optionDivider = $optionDivider;
    }


    public function mainApplication($argv)
    {
        $this->timeKeeping->startTime();
        $this->fileReader->readHyphenationPatterns();


        return $result;
    }
}
