<?php

namespace Fikusas\Hyphenation;

use Fikusas\Log\Logger;
use Fikusas\Patterns\PatternLoaderFile;
use PHPUnit\Framework\TestCase;


class SentenceHyphenatorTest extends TestCase
{
    protected $hyphenator;
    private $logger;
    private $patterns;
    private const RESULT_FOR_PATTERNS =
            '1p2l2
            .ach4
            .ppl
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
             4p1p';


    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(Logger::class);
        $this->patterns = $this->createMock(PatternLoaderFile::class);
        $this->patterns
            ->method('loadPatterns')
            ->willReturn(explode("\n", self::RESULT_FOR_PATTERNS));
        $this->hyphenator = $this->createMock(WordHyphenator::class);
       // $this->hyphenator->expects($this->any())->method('hyphenate')->with('apple')->willReturn('a');
    }

    public function testSentenceHyphenator()
    {
        $word = 'apple';
        $this->hyphenator->expects($this->any())->method('hyphenate')->with($word)->willReturn('a');
        $sentenceHyphenator = new SentenceHyphenator($this->logger, $this->hyphenator);
        $result = $sentenceHyphenator->hyphenateSentence('apple 1245/211 apple');
        $this->assertEquals('a 1245/211 a', $result);
    }
}
