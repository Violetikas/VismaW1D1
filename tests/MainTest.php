<?php

namespace Fikusas\Tests;

use Fikusas\Main;
use Fikusas\UserInteraction\InputParameters;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{

    public function testRun()
    {
        $main = $this->createMock(Main::class);
        $inputParameters = $this->createMock(InputParameters::class);

        $inputParameters->getOption('-w');
        $array = ['application.php', '-w', 'mistranslate'];
        $result = $main->run($array);
        $this->assertEquals(null, $result);
    }
}
