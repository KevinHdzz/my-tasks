<?php

namespace Kevinhdzz\MyTasks\Routing;

use Kevinhdzz\MyTasks\Exceptions\HttpNotFoundException;

class Router {
    /**
     * Registered routes.
     * 
     * @var array<string, Route[]> $routes
     */
    public static array $routes = [
        HttpMethod::GET->value => [],
        HttpMethod::POST->value => [],
    ];

    public static function get(Route $route): void
    {
        self::$routes[HttpMethod::GET->value][] = $route;
    }

    public static function post(Route $route): void
    {
        self::$routes[HttpMethod::POST->value][] = $route;
    }

    public static function resolve(): void
    {
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $path = "/" != $path && str_ends_with($path, "/") ? substr($path, 0, strlen($path) - 1) : $path;
        $httpMethod = $_SERVER["REQUEST_METHOD"];

        $route = self::resolveRoute(HttpMethod::from($httpMethod), $path) ?? throw new HttpNotFoundException(
            "Error 404. Resource $path not found."
        );

        call_user_func($route->action(), $route);
    }

    private static function resolveRoute(HttpMethod $httpMethod, string $path): ?Route
    {
        foreach (self::$routes[$httpMethod->value] as $route) {
            if ($route->matches($path)) {
                return $route;
            }
        }

        return null;
    }
}
