<?php

namespace Fikusas\Tests;

use Fikusas\Hyphenation\WordHyphenator;
use Fikusas\UserInteraction\InputParameters;
use Fikusas\UserInteraction\OptionDivider;
use PHPUnit\Framework\TestCase;

class OptionDividerTest extends TestCase
{
    public function testDivideOptions()
    {
        $optionDivider = $this->createMock(OptionDivider::class);
        $hyphenator = $this->createMock(WordHyphenator::class);
        $inputParameters = $this->createMock(InputParameters::class);

        $hyphenator->hyphenate('mistranslate');
        $inputParameters->getOption('-w');
        $result = $optionDivider->divideOptions($inputParameters);
        $this->assertEquals(null, $result);

    }
}


