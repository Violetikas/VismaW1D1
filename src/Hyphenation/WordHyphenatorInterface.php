<?php


namespace Fikusas\Hyphenation;


interface WordHyphenatorInterface
{
    /**
     * @param string $word
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function hyphenate(string $word): string;
}
