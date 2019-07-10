<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 11:04
 */
declare(strict_types=1);

namespace Fikusas;

use RuntimeException;

class OptionDivider
{
    /** @var WordHyphenator */
    private $hyphenator;
    private $word;
    private $sentenceHyphenator;

    /**
     * OptionDivider constructor.
     * @param WordHyphenator $hyphenator
     * @param SentenceHyphenator $sentenceHyphenator
     */
    public function __construct(WordHyphenator $hyphenator, SentenceHyphenator $sentenceHyphenator)
    {
        $this->hyphenator = $hyphenator;
        $this->sentenceHyphenator = $sentenceHyphenator;

    }

    public function divideOptions(InputParameters $inputOption)
    {
        $userOption = $inputOption->getUserOption();
        if ($userOption == '-w') {
            return $this->hyphenator->hyphenate($inputOption->getUserInput());

        }
        if ($userOption == '-s') {

            return $this->sentenceHyphenator->hyphenateSentence($inputOption->getUserInput());
        }

        throw new RuntimeException('Missing option');

    }

}
