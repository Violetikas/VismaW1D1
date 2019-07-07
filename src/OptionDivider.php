<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 11:04
 */
declare(strict_types=1);

namespace Fikusas;

class OptionDivider
{
    /** @var Hyphenate */
    private $hyphenator;

    /**
     * OptionDivider constructor.
     * @param Hyphenate $hyphenator
     */
    public function __construct(Hyphenate $hyphenator, SentencePrepare $sentencePrepare)
    {
        $this->hyphenator = $hyphenator;
        $this->sentencePrepare = $sentencePrepare;
    }

    public function divideOptions(InputParameters $inputOption)
    {
        if ($userOption = '-w') {
            $this->hyphenator->hyphenate($inputOption->getUserInput());
        }
        if ($userOption = '-s') {

            //TODO create sentence algorithm
            echo 'do something';
        }

    }


}
