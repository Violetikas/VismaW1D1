<?php

namespace Fikusas\Hyphenation;

use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\Log\Logger;
use Fikusas\Patterns\PatternLoaderFile;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SentenceHyphenatorTest extends TestCase
{
    protected $hyphenator;
    private $logger;
    private $patterns;
    private $wordDB;
    private $dbConfig;
    private $patternDB;
    private $hyphenatedWordsDB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dbConfig = $this->createMock(DatabaseConnector::class);
        $this->logger = $this->createMock(Logger::class);
        $this->patterns = $this->createMock(PatternLoaderFile::class);
        $this->patterns
            ->method('loadPatterns')
            ->willReturn(explode("\n","1p2l2
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p 
             1p2l2 
             4p1p"));
        $this->wordDB = $this->createMock(WordDB::class);
        $this->patternDB = $this->createMock(PatternDB::class);
        $this->hyphenatedWordsDB = $this->createMock(HyphenatedWordsDB::class);
    }


    public function testSentenceHyphenator()
    {

        $loader = $this->patterns;
        $hyphenator = new WordHyphenator($loader, $this->dbConfig, $this->wordDB, $this->patternDB, $this->hyphenatedWordsDB );
        $sentenceHyphenator = new SentenceHyphenator($this->logger, $hyphenator);
        $result = $sentenceHyphenator->hyphenateSentence('apple');
        $this->assertEquals('ap-ple', $result);

    }
}
