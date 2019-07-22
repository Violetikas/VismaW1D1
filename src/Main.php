<?php

declare(strict_types=1);


namespace Fikusas;

use Fikusas\DB\DatabaseConnector;
use Fikusas\Cache\FileCache;
use Fikusas\Config\JsonConfigLoader;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\CachingHyphenator;
use Fikusas\Hyphenation\DBHyphenator;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Log\Logger;
use Fikusas\Patterns\PatternLoaderFile;
use Fikusas\UserInteraction\OptionDivider;
use Fikusas\UserInteraction\UserInteraction;
use Fikusas\TimeKeeping\TimeKeeping;
use Fikusas\UserInteraction\Output;

class Main
{
    /**
     * @var TimeKeeping
     */
    private $timeKeeping;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var UserInteraction
     */
    private $input;

    public function __construct()
    {
        $this->input = new UserInteraction();
        $this->timeKeeping = new TimeKeeping();
        $this->logger = new Logger();
    }

    public function run(array $argv): void
    {
        $this->timeKeeping->startTime();
        $userInput = $this->input->getUserInput($argv);
        $config = JsonConfigLoader::load('config.json');
        $optionDivider = $this->createOptionDivider($config);
        $optionDivider->divideOptions($userInput);
        $this->logger->info(sprintf('Completed in %.6f seconds', $this->timeKeeping->stopTime()));
    }

    private function createOptionDivider(Config\ConfigInterface $config): OptionDivider
    {
        $cache = new FileCache('cache', 86400);
        $loader = new PatternLoaderFile($config->getParameter('patterns_file'));
        $db = new DatabaseConnector($config);
        $wdb = new WordDB($db);
        $hyphenate = new CachingHyphenator(new DBHyphenator(new WordHyphenator($loader, $db), $wdb), $cache, $wdb);
        return new OptionDivider($hyphenate, new SentenceHyphenator($this->logger, $hyphenate),
            new FileReadFromInput(), new Output(), new PatternDB(new DatabaseConnector($config)), $wdb);
    }

}
