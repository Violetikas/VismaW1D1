<?php

declare(strict_types=1);

namespace Fikusas\Log;

use Psr\Log\AbstractLogger;
use RuntimeException;

class Logger extends AbstractLogger
{
    private $fileName;

    public function __construct(string $fileName = 'errorLogger.Log')
    {
        $this->fileName = $fileName;
    }

    public function log($level, $message, array $context = array())
    {
        $this->isLogLevelValid($level);
        $this->writeToFile($message, $context);
    }

    private function writeToFile($message, array $context = array())
    {
        $fileName = $this->fileName;
        $fd = fopen($fileName, "a");
        $message = $this->interpolate($message, $context);
        $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $message;
        fwrite($fd, $str . "\n");
        fclose($fd);
    }


    function interpolate($message, array $context = array()): string
    {
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $message = str_replace('{' . $key . '}', $val, $message);
            }
        }
        return $message;
    }

    private function isLogLevelValid(string $level)
    {
        if ($level != LogLevel::ERROR && $level != LogLevel::EMERGENCY && $level != LogLevel::CRITICAL &&
            $level != LogLevel::DEBUG && $level != LogLevel::WARNING && $level != LogLevel::INFO && $level
            != LogLevel::NOTICE && $level != LogLevel::ALERT) {
            throw new RuntimeException('Log level {$level} does not exist');
        }
    }
}
