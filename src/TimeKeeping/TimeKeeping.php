<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-14
 * Time: 21:44
 */

namespace Fikusas\TimeKeeping;


class TimeKeeping
{
    private $timeStart;

    public function startTime()
    {
        $this->timeStart = microtime(true);
    }

    public function stopTime()
    {
        $time = microtime(true) - $this->timeStart;
        return $time;
    }
}