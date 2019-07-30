<?php


namespace Fikusas\API;

use Fikusas\DI\Container;
use Fikusas\DI\ContainerBuilder;

class ApiHandler
{
    /** @var Container */
    private $container;
    /**
     * @var Router
     */
    private $router;

    public function __construct()
    {
        $this->container = (new ContainerBuilder())->build();
        $this->router = $this->container->get(Router::class);
    }

    public function handle()
    {
        $request = new Request($_SERVER, $_POST);
        try {
            $route = $this->router->findRoute($request);
            $controllerFn = [$this->container->get($route[0]), $route[1]];
            $response = call_user_func($controllerFn, ...array_slice($route, 2));
        } catch (RouteNotFound $e) {
            $response = new JsonResponse(['error' => 'Unsupported request'], 400);
        }

        $this->respond($response);
    }

    public function respond(ResponseInterface $response): void
    {
        http_response_code($response->getStatus());
        foreach ($response->getHeaders() as $header) {
            header($header);
        }
        echo $response->getContent();
    }
}
