<?php

namespace Fikusas\Tests;

use Fikusas\TimeKeeping\TimeKeeping;
use PHPUnit\Framework\TestCase;

final class TimeKeepingTest extends TestCase
{
   public function testTime(){
       $time = new TimeKeeping();
       $time->startTime();
       sleep(1);
       $duration = round($time->stopTime(),0);
       $this->assertEquals(1,$duration);
   }
}
