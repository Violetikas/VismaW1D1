<?php


namespace Fikusas\API;

use Fikusas\DI\ContainerBuilder;

class Router
{
    /** @var Controller */
    private $controller;
    private $container;

    public function __construct()
    {
        $this->container = (new ContainerBuilder())->build();
        $this->controller = $this->container->get(Controller::class);
    }


    public function handle()
    {
        $uri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])));
        $method = $_SERVER['REQUEST_METHOD'];
        if ($uri == '/words') {
            if ($method == 'GET') {
                $response = $this->controller->getWords();
            } elseif ($method == 'POST') {
                $response = $this->controller->insertWord(new Request(file_get_contents('php://input')));
            }
        } elseif (preg_match('#^/words/(.+)#', $uri, $matches)) {
            $word = $matches[1];
            if ($method == 'DELETE') {
                $response = $this->controller->deleteWord($word);
            }
            elseif ($method =='PUT'){
                $response = $this->controller->updateWord($word);
            }
        }
        if (!isset($response)) {
            $response = new Response(['error' => 'Unsupported request'], 400);
        }

        $this->respond($response);
    }
    public function respond(Response $response): void
    {
        http_response_code($response->getStatus());
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        echo $response->getContentEncoded();
    }


}