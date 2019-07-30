<?php


namespace Fikusas\API;


class Router
{
    public function findRoute(Request $request): array
    {
        $uri = $request->getUri();
        $method = $request->getMethod();
        if ($uri == '/words') {
            if ($method == 'GET') {
                return [Controller::class, 'getWords'];
            } elseif ($method == 'POST') {
                return [Controller::class, 'insertWord', $request];
            }
        } elseif (preg_match('#^/words/(.+)#', $uri, $matches)) {
            $word = $matches[1];
            if ($method == 'DELETE') {
                return [Controller::class, 'deleteWord', $word];
            } elseif ($method == 'PUT') {
                return [Controller::class, 'updateWord', $word];
            }
        } elseif ($uri === '/' && $method === 'GET') {
            return [ControlerWordList::class, 'getWords'];
        } elseif ($uri === '/insert' && in_array($method, ['GET', 'POST'])) {
            return [ControlerWriter::class, 'insertWord', $request];
        }

        throw new RouteNotFound();
    }
}
