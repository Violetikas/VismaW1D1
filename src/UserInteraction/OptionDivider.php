<?php

declare(strict_types=1);

namespace Fikusas\UserInteraction;

use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Hyphenation\WordHyphenatorInterface;
use Fikusas\Patterns\PatternLoaderFile;
use Psr\SimpleCache\InvalidArgumentException;
use RuntimeException;

class OptionDivider
{

    private $hyphenator;
    private $sentenceHyphenator;
    private $fileReadFromInput;
    private $output;
    private $patternDB;
    private $wordDB;
    private $hyphenatedWordsDB;


    public function __construct(WordHyphenatorInterface $hyphenator, SentenceHyphenator $sentenceHyphenator, FileReadFromInput $fileReadFromInput, Output $output, PatternDB $patternDB, WordDB $wordDB, HyphenatedWordsDB $hyphenatedWordsDB)
    {
        $this->hyphenator = $hyphenator;
        $this->sentenceHyphenator = $sentenceHyphenator;
        $this->fileReadFromInput = $fileReadFromInput;
        $this->output = $output;
        $this->patternDB = $patternDB;
        $this->wordDB = $wordDB;
        $this->hyphenatedWordsDB = $hyphenatedWordsDB;
    }


    /**
     * @param $inputOption
     * @throws InvalidArgumentException
     */
    public function divideOptions(InputParameters $inputOption): void
    {
        if ($value = $inputOption->getOption('-w')) {
            $hyphenatedWord = $this->hyphenator->hyphenate($value);
            $this->wordDB->writeToDB($value);
            $this->hyphenatedWordsDB->writeToDB($value, $hyphenatedWord);
            $this->output->writeLine($hyphenatedWord);
            return;
        }

        if ($value = $inputOption->getOption('-p')) {
            $hyphenatedWord = $this->hyphenator->hyphenate($value);
            $this->wordDB->writeToDB($value);
            $this->hyphenatedWordsDB->writeToDB($value, $hyphenatedWord);
            $this->output->writeLine($hyphenatedWord);
            $patterns = $this->patternDB->getFromDB($value);
            foreach ($patterns as $row) {
                $this->output->writeLine($row['pattern']);
            }
            return;
        }

        if ($value = $inputOption->getOption('-s')) {
            $this->output->writeLine($this->sentenceHyphenator->hyphenateSentence($value));
            return;
        }

        if ($value = $inputOption->getOption('-f')) {
            $words = $this->fileReadFromInput->fileReadFromInput($value);
            $hyphenatedWords = [];

            foreach ($words as $word) {
                $hyphenatedWord = $this->hyphenator->hyphenate($word);
                $this->output->writeLine($hyphenatedWord);
                array_push($hyphenatedWords, $hyphenatedWord);
            }
            $this->wordDB->writeWordsToDB($words);
            return;
        }

        if ($value = $inputOption->getOption('-l')) {
            $loader = new PatternLoaderFile($value);
            $patterns = $loader->loadPatterns();
            $this->patternDB->writeToDB($patterns);
        } else throw new RuntimeException('Missing option');

    }
}
