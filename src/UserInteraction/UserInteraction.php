<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 14:29
 */
declare(strict_types=1);

namespace Fikusas\UserInteraction;


class UserInteraction
{
    private $options;

    public function __construct()
    {
        $this->options = [
            '-w' => 'string',
            '-p' => 'string',
            '-s' => 'string',
            '-f' => 'string',
            '-l' => 'string',
            '-d' => 'boolean'
        ];
    }

    /**
     * @param array $argv
     * @return InputParameters
     */
    public function getUserInput(array $argv): InputParameters
    {
        // Remove script name.
        array_shift($argv);
        // Initialize result array.
        $result = [];
        while ($argv) {
            $option = array_shift($argv);
            if (!($optionKind = $this->options[$option])) {
                $this->printHelp();
                exit;
            }
            if ($optionKind === 'string') {
                $result[$option] = array_shift($argv);
            } elseif ($option === 'boolean') {
                $result[$option] = true;
            }
        }

        return new InputParameters($result);
    }

    private function printHelp(): void
    {
        echo <<<EOF
Usage:
    
    php application.php [ -w word ] [ -s sentence ] [ -f path ] [ -l path ]
    
Arguments:

    -w word      Hyphenate given word
    -p           Hyphenate given word and print patterns used in hyphenation
    -s sentence  Hyphenate given sentence
    -f path      Hyphenate words from given file
    -l path      Load patterns from file to DB
    -d path      Hyphenate words from database
EOF;
    }
}
