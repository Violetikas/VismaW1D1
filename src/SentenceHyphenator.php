<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 12:50
 */

namespace Fikusas;


class SentenceHyphenator
{
    protected $hyphenator;

    public function __construct(WordHyphenator $hyphenator)
    {
        $this->hyphenator = $hyphenator;
    }

    private function extractWordsFromSentence(string $userInput): string
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

    public function hyphenateSentence(string $sentence): string
    {
        $result = '';
        $offset = 0;

        if (preg_match_all('/[a-zA-Z_]+/', $sentence, $matches, PREG_OFFSET_CAPTURE) > 0) {
            foreach ($matches[0] as $match) {
                [$word, $wordStart] = $match;
                $result .= substr($sentence, $offset, $wordStart - $offset);
                $result .= $this->hyphenator->hyphenate($word);
                $offset = $wordStart + strlen($word);
            }
            $result .= substr($sentence, $offset);
        }

        return $result;
    }

}