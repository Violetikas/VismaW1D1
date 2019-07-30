<?php


namespace Fikusas\API;


class JsonResponse implements ResponseInterface
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

    public function getContent(): string
    {
        return json_encode($this->content);
    }

    public function getHeaders(): array
    {
        return ["Content-Type: application/json"];
    }
}
