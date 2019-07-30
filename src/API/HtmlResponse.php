<?php


namespace Fikusas\API;


class HtmlResponse implements ResponseInterface
{
    /** @var int */
    private $status;
    /** @var string */
    private $content;

    /**
     * HtmlResponse constructor.
     * @param int $status
     * @param string $content
     */
    public function __construct(int $status, string $content)
    {
        $this->status = $status;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getHeaders(): array
    {
        return ['Content-Type: text/html; charset=utf-8'];
    }
}
