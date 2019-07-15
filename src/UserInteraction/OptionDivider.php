<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 11:04
 */
declare(strict_types=1);

namespace Fikusas\UserInteraction;

use Fikusas\FileRead\FileRead;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use RuntimeException;

class OptionDivider
{

    private $hyphenator;
    private $sentenceHyphenator;
    private $fileReader;
    private $fileReadFromInput;

    /**
     * OptionDivider constructor.
     * @param $hyphenator
     * @param $sentenceHyphenator
     * @param $fileReader
     * @param $fileReadFromInput
     */
    public function __construct(WordHyphenator $hyphenator, SentenceHyphenator $sentenceHyphenator, FileRead $fileReader, FileReadFromInput $fileReadFromInput)
    {
        $this->hyphenator = $hyphenator;
        $this->sentenceHyphenator = $sentenceHyphenator;
        $this->fileReader = $fileReader;
        $this->fileReadFromInput = $fileReadFromInput;
    }


    /**
     * @param InputParameters $inputOption
     * @return string
     */
    public function divideOptions(InputParameters $inputOption)
    {
        $userOption = $inputOption->getUserOption();
        if ($userOption == '-w') {
            return $this->hyphenator->hyphenate($inputOption->getUserInput());

        }
        if ($userOption == '-s') {

            return $this->sentenceHyphenator->hyphenateSentence($inputOption->getUserInput());
        }

        if ($userOption == '-f') {

            $words = $this->fileReadFromInput->fileReadFromInput($inputOption->getUserInput());
            $hyphenatedWords = [];

            foreach ($words as $word) {
                $hyphenatedWords[] = $this->hyphenator->hyphenate($word);
            }
            foreach ($hyphenatedWords as $hyphenatedWord) {
                echo $hyphenatedWord . "\n";
            }

        } else  throw new RuntimeException('Missing option');

    }


}
