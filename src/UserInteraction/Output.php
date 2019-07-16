<?php


namespace Fikusas\UserInteraction;


class Output
{
    public function writeLine(string $line): void
    {
        echo $line . "\n";
    }
}
