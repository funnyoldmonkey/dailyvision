<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function add(string $method, string $route, string $controllerAction): void
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'action' => $controllerAction
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Detect the project root directory
        $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $projectRoot = str_replace('/public/index.php', '', $scriptPath);
        
        // Remove project root from URI
        if ($projectRoot !== '/' && strpos($uri, $projectRoot) === 0) {
            $uri = substr($uri, strlen($projectRoot));
        }

        $uri = ($uri === '' || $uri === false) ? '/' : $uri;

        // Clean double slashes and trailing slashes
        $uri = preg_replace('#/+#', '/', $uri);
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        foreach ($this->routes as $route) {
            $pattern = "#^" . $route['route'] . "$#";
            if (preg_match($pattern, $uri) && $route['method'] === $method) {
                [$controller, $action] = explode('@', $route['action']);
                $controller = "App\\Controllers\\" . $controller;
                $instance = new $controller();
                $instance->$action();
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
