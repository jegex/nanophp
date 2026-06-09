<?php
namespace NanoPHP\Core;

class Router {
    private array $routes = [];

    public function __construct() {
        $this->routes = Config::load('routes');
        $pluginRoutes = Hook::filter('router.routes', []);
        $this->routes = array_merge($this->routes, $pluginRoutes);
    }

    public function dispatch(string $uri): void {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $pattern => $handler) {
            $regex = $this->patternToRegex($pattern);
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                [$controller, $method] = $handler;
                $this->call($controller, $method, $matches);
                return;
            }
        }

        http_response_code(404);
        (new View())->render('404', [
            'page_title' => Language::get('404.title'),
            'current_page' => '404',
            'site_name' => Config::get('app.site_name', ''),
            'site_desc' => Config::get('app.site_desc', ''),
            'base_url' => Config::get('app.base_url', ''),
        ]);
    }

    private function patternToRegex(string $pattern): string {
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }

    private function call(string $controller, string $method, array $params): void {
        if (str_contains($controller, '\\')) {
            $class = $controller;
        } else {
            $class = "NanoPHP\\Controllers\\{$controller}";
        }
        if (!class_exists($class)) {
            http_response_code(500);
            echo "Controller not found: {$controller}";
            return;
        }
        $instance = new $class();
        Hook::run('controller.before', $instance, $method, $params);
        if (!method_exists($instance, $method)) {
            http_response_code(500);
            echo "Method not found: {$controller}::{$method}";
            return;
        }
        $result = $instance->$method(...$params);
        Hook::run('controller.after', $instance, $method, $params, $result);
    }
}
