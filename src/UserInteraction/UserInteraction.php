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
            '-w',
            '-s',
            '-f'
        ];
    }

    /**
     * @param array $argv
     * @return InputParameters
     */
    public function getUserInput(array $argv): InputParameters
    {
        // Remove script name.
        $argv = array_slice($argv, 1);
        // Initialize result array.
        $result = [];
        while ($argv) {
            if (!in_array($argv[0], $this->options, true) || count($argv) < 2) {
                $this->printHelp();
                exit;
            }
            // Set option value.
            $result[$argv[0]] = $argv[1];
            // Remove processed option.
            $argv = array_slice($argv, 2);
        }

        return new InputParameters($result);
    }

    private function printHelp(): void
    {
        echo <<<EOF
Usage:
    
    php application.php [ -w word ] [ -s sentence ] [ -f path ]
    
Arguments:

    -w word      Hyphenate given word
    -s sentence  Hyphenate given sentence
    -f path      Hyphenate words from given file
EOF;
    }
}
