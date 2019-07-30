<?php


namespace Fikusas\API;


class Request
{
    /**
     * @var array
     */
    private $server;
    /**
     * @var array
     */
    private $post;

    /**
     * Request constructor.
     * @param array $server
     * @param array $post
     */
    public function __construct(array $server, array $post)
    {
        $this->server = $server;
        $this->post = $post;
    }

    public function getPostValue(string $name): string
    {
        return $this->post[$name];
    }

    public function getUri(): string
    {
        return substr($this->server['REQUEST_URI'], strlen(dirname($this->server['SCRIPT_NAME'])));
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
