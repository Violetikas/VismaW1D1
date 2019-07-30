<?php

namespace Fikusas\API;

use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Patterns\PatternLoaderFile;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
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
            ->willReturn(explode("\n", "1p2l2
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

    public function testInsertWord(): void
    {
        $controller = new Controller($this->hyphenator, $this->patterns, $this->dbConfig, $this->wordDB, $this->patternDB, $this->hyphenatedWordsDB);
        $response = $controller->insertWord(new Request([], ['word' => 'apple']));
        $this->assertEquals([
            'word' => 'apple',
            'message' => 'Word written to DB'
        ], $response->getContent());
    }

    public function testUpdateWord()
    { $controller = new Controller($this->hyphenator, $this->patterns, $this->dbConfig, $this->wordDB, $this->patternDB, $this->hyphenatedWordsDB);
        $response = $controller->updateWord('apple');
        $this->assertEquals([
            'word' => 'apple',
            'hyphenated'=>'ap-ple',
            'message' => 'Word updated'
        ], $response->getContent());
    }

}


