<?php

namespace Fikusas\Hyphenation;

use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\Patterns\PatternLoaderFile;
use PDO;
use PHPUnit\Framework\TestCase;

class WordHyphenatorTest extends TestCase
{
    private $patterns;
    private $wordDB;
    private $dbConfig;
    private $patternDB;
    private $hyphenatedWordsDB;
    private $hyphenator;


    protected function setUp(): void
    {
        parent::setUp();
        $this->dbConfig = $this->createMock(DatabaseConnector::class);
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
        $this->hyphenator = new WordHyphenator($this->patterns, $this->dbConfig, $this->wordDB, $this->patternDB, $this->hyphenatedWordsDB);
    }

    public function testHyphenate()
    {
        $word = 'apple';
        $result = $this->hyphenator->hyphenate($word);
        $this->assertEquals('ap-ple', $result);
    }

    public function testFindPatterns(){
        $word = 'apple';
        $result = $this->hyphenator->findPatterns($word);
        $this->assertContains('1p2l2', $result);

    }
}

