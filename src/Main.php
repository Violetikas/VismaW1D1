<?php

declare(strict_types=1);


namespace Fikusas;

use Fikusas\DI\ContainerBuilder;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Log\Logger;
use Fikusas\UserInteraction\OptionDivider;
use Fikusas\UserInteraction\InputOptionParser;
use Fikusas\TimeKeeping\TimeKeeping;

class Main
{

    private $timeKeeping;
    private $logger;
    private $inputOptionParser;
    private $hyphenator;
    private $container;
    private $optionDivider;

    public function __construct()
    {
        $this->container = (new ContainerBuilder())->build();
        $this->inputOptionParser = $this->container->get(InputOptionParser::class);
        $this->timeKeeping = $this->container->get(TimeKeeping::class);
        $this->logger = $this->container->get(Logger::class);
        $this->hyphenator = $this->container->get(WordHyphenator::class);
        $this->optionDivider = $this->container->get(OptionDivider::class);
    }

    public function run(array $argv): void
    {
        $this->timeKeeping->startTime();
        $userInput = $this->inputOptionParser->parse($argv);
        $this->optionDivider->divideOptions($userInput);
        $this->logger->info(sprintf('Completed in %.6f seconds', $this->timeKeeping->stopTime()));
    }
}
