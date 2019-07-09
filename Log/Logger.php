<?php


namespace Log;

use Psr\Log\LoggerInterface;
use http\Exception\RuntimeException;

class Logger
{
    private $fileName;

    public function __construct(string $fileName = 'errorLogger.log')
    {
        $this->fileName = $fileName;
    }

    public function critical($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function emergency($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->writeToFile($message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->isLogLevelValid($level);
        $this->$level($message, $context);
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
