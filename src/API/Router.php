<?php


namespace Fikusas\API;

class Router
{

    private $controller;

    /**
     * Router constructor.
     * @param $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }


    public function handle()
    {
        // Remove fixed prefix from URL.
        $uri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])));
        $method = $_SERVER['REQUEST_METHOD'];
        if ($uri == '/words') {
            if ($method == 'GET') {

                $response = $this->controller->getWords();
            } elseif ($method == 'POST') {
                $response = $this->controller->insertWord();
            }
        } elseif (preg_match('#^/words/(.+)#', $uri, $matches)) {
            $word = $matches[1];
            if ($method == 'DELETE') {
                $response = $this->controller->deleteWord($word);
            } elseif ($method == 'POST') {
                $response = $this->controller->updateWord($word);
            }
        }

        if (!isset($response)) {
            $response = new Response(['error' => 'Unsupported request'], 400);
        }

        $this->controller->respond($response);
    }


}