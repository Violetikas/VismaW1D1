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
    private $fileReadFromInput;
    private $output;

    /**
     * OptionDivider constructor.
     * @param WordHyphenator $hyphenator
     * @param SentenceHyphenator $sentenceHyphenator
     * @param FileReadFromInput $fileReadFromInput
     * @param Output $output
     */
    public function __construct(WordHyphenator $hyphenator, SentenceHyphenator $sentenceHyphenator, FileReadFromInput $fileReadFromInput, Output $output)
    {
        $this->hyphenator = $hyphenator;
        $this->sentenceHyphenator = $sentenceHyphenator;
        $this->fileReadFromInput = $fileReadFromInput;
        $this->output = $output;
    }


    /**
     * @param InputParameters $inputOption
     * @return void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function divideOptions(InputParameters $inputOption): void
    {
        if ($value = $inputOption->getOption('-w')) {
            $this->output->writeLine($this->hyphenator->hyphenate($value));
            return;

        }
        if ($value = $inputOption->getOption('-s')) {
            $this->output->writeLine($this->sentenceHyphenator->hyphenateSentence($value));
            return;
        }

        if ($value = $inputOption->getOption('-f')) {
            $words = $this->fileReadFromInput->fileReadFromInput($value);

            foreach ($words as $word) {
                $this->output->writeLine($this->hyphenator->hyphenate($word));
            }
            return;

        }

        throw new RuntimeException('Missing option');
    }


}
