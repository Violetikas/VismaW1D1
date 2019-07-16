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
use Fikusas\UserInteraction\Output;

class Main
{
    private $timeKeeping;
    private $logger;
    private $optionDivider;
    private $input;

    public function __construct()
    {
        $this->input = new UserInteraction();
        $this->timeKeeping = new TimeKeeping();
        $this->logger = new Logger();
        $cache = new FileCache('cache', 86400);
        $fileReader = new FileRead($cache);
        $hyphenate = new WordHyphenator($fileReader->readHyphenationPatterns($cache), $cache);
        $this->optionDivider = new OptionDivider($hyphenate, new SentenceHyphenator($this->logger, $hyphenate), new FileReadFromInput(), new Output());
    }

    /**
     * Run the application.
     *
     * @param array $argv Input arguments
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function run(array $argv): void
    {
        $this->timeKeeping->startTime();
        $userInput = $this->input->getUserInput($argv);
        $this->optionDivider->divideOptions($userInput);
        $this->logger->info(sprintf('Completed in %.6f seconds', $this->timeKeeping->stopTime()));
    }
}
