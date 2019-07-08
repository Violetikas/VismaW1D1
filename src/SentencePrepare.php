<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 12:50
 */

namespace Fikusas;


class SentencePrepare
{

    private $userInput = '';
    private $syllables = [];
    protected $hyphenator;

    public function __construct(array $syllables, string $userInput, Hyphenate $hyphenator)
    {
        $this->syllables = $syllables;
        $this->userInput = $userInput;
        $this->hyphenator = $hyphenator;

    }

    public function extractWordsFromSentence(string $userInput): array
    {

        $wordsExtracted = [];
        $word = '';
        $wordOffset = (int)0;
        if (preg_match_all("\w+", $userInput, $wordsExtracted, PREG_OFFSET_CAPTURE) > 0) {
            foreach ($wordsExtracted[0] as $wordExtracted) {
                [$word, $wordOffset] = $wordExtracted;
                $wordsExtracted[$wordOffset] = $word;
            }
        }

        return $word;
    }


    public function hyphenateSentence ($word, $userInput)
    {

        $hyphenatedWordwNumbers = $this->hyphenator->hyphenate($word);
        $extractedNumbers = $this->hyphenator->extractNumbers($argument);
        $hyphenatedWord = $this->hyphenator->printResult($this->hyphenator->hyphenate($word), $this->hyphenator->extractNumbers($syllable));
        $i = 0;
        $sentenceTogether = '';
        while ($i < strlen($userInput)) {

            if ($userInput[$i] = $word) {
                str_replace($word, $hyphenatedWord, $userInput);
            } else continue;
        }


    }

}