<?php


namespace Fikusas\API;


class Response
{
    private $status;
    private $content;

    /**
     * Response constructor.
     * @param array $content
     * @param int $status
     */
    public function __construct(array $content, int $status = 200)
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

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    public function getContentEncoded(): string
    {
        return json_encode($this->content);
    }
}
