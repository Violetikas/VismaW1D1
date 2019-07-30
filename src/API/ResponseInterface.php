<?php


namespace Fikusas\API;


interface ResponseInterface
{
    /**
     * @return int
     */
    public function getStatus(): int;

    public function getContent(): string;

    public function getHeaders(): array;
}