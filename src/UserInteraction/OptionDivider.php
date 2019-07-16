<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 11:04
 */
declare(strict_types=1);

namespace Fikusas\UserInteraction;

use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\FileRead\FileReadFromInput;
use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\Hyphenation\SentenceHyphenator;
use Fikusas\Patterns\PatternLoaderFile;
use RuntimeException;

class OptionDivider
{

    private $hyphenator;
    private $sentenceHyphenator;
    private $fileReadFromInput;
    private $output;
    /**
     * @var PatternDB
     */
    private $db;
    private $wdb;

    /**
     * OptionDivider constructor.
     * @param WordHyphenator $hyphenator
     * @param SentenceHyphenator $sentenceHyphenator
     * @param FileReadFromInput $fileReadFromInput
     * @param Output $output
     * @param PatternDB $db
     */
    public function __construct(WordHyphenator $hyphenator, SentenceHyphenator $sentenceHyphenator, FileReadFromInput $fileReadFromInput, Output $output, PatternDB $db, WordDB $wdb)
    {
        $this->hyphenator = $hyphenator;
        $this->sentenceHyphenator = $sentenceHyphenator;
        $this->fileReadFromInput = $fileReadFromInput;
        $this->output = $output;
        $this->db = $db;
        $this->wdb = $wdb;
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
            $hyphenatedWords =[];

            foreach ($words as $word) {
                $hyphenatedWord = $this->hyphenator->hyphenate($word);
                $this->output->writeLine($hyphenatedWord);
                array_push($hyphenatedWords,$hyphenatedWord);
            }
            $this->wdb->writeWordsToDB($words,$hyphenatedWords);
            return;

        }

        if ($value = $inputOption->getOption('-l')) {
            $loader = new PatternLoaderFile($value);
            $patterns = $loader->loadPatterns();
            $this->db->writePatternsToDB($patterns);
        }

       else throw new RuntimeException('Missing option');
    }


}
