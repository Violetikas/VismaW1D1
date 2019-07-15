<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 12:50
 */

namespace Fikusas\Hyphenation;

use Fikusas\Hyphenation\WordHyphenator;
use Psr\Log\LoggerInterface;

class SentenceHyphenator
{
    protected $hyphenator;
    private $logger;

    /**
     * SentenceHyphenator constructor.
     * @param LoggerInterface $logger
     * @param \Fikusas\Hyphenation\WordHyphenator $hyphenator
     */
    public function __construct(LoggerInterface $logger, WordHyphenator $hyphenator)
    {
        $this->logger = $logger;
        $this->hyphenator = $hyphenator;

    }

    /**
     * @param string $sentence
     * @return string
     */
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
            $this->logger->notice("Sentence '{sentence}'hyphenated to '{result}'", array('sentence' => $sentence, 'result' => $result));
        }
        return $result;
    }

}