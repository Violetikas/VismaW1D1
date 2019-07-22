<?php


namespace Fikusas\API;

class Router
{
    public function handle()
    {
        // Remove fixed prefix from URL.

        $controller = new Controller();

        $uri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME'])));
        $method = $_SERVER['REQUEST_METHOD'];
        if ($uri == '/words') {
            if ($method == 'GET') {

                $response = $controller->getWords();
            } elseif ($method == 'POST') {
                $response = $controller->insertWord();
            }
        } elseif (preg_match('#^/words/(.+)#', $uri, $matches)) {
            $word = $matches[1];
            if ($method == 'DELETE') {
                $response = $controller->deleteWord($word);
            } elseif ($method == 'POST') {
                $response = $controller->updateWord($word);
            }
        }

        if (!isset($response)) {
            $response = new Response(['error' => 'Unsupported request'], 400);
        }

        $controller->respond($response);
    }


}