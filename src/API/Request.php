<?php


namespace Fikusas\API;


class Request
{
    /** @var string */
    private $content;

    /**
     * Request constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
