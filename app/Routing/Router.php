<?php

namespace Kevinhdzz\MyTasks\Routing;

use Kevinhdzz\MyTasks\Exceptions\HttpNotFoundException;

/**
 * Manages HTTP routes and executes corresponding actions.
 * 
 * Register routes for different HTTP methods and resolve the route and action
 * to be executed based on the current request.
 */
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

    /**
     * Registers a new route for HTTP GET method.
     * 
     * @param Route $route
     */
    public static function get(Route $route): void
    {
        self::$routes[HttpMethod::GET->value][] = $route;
    }

    /**
     * Registers a new route for HTTP POST method.
     * 
     * @param Route $route
     */
    public static function post(Route $route): void
    {
        self::$routes[HttpMethod::POST->value][] = $route;
    }

    /**
     * Executes the action associated with the current request route.
     * 
     * @throws HttpNotFoundException If the requested route is not registered.
     */
    public static function resolve(): void
    {
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $path = "/" != $path && str_ends_with($path, "/") ? substr($path, 0, strlen($path) - 1) : $path;
        $httpMethod = $_SERVER["REQUEST_METHOD"];

        $route = self::resolveRoute(HttpMethod::from($httpMethod), $path, array_keys($_GET));
        
        if (is_null($route)) throw new HttpNotFoundException(
            "Error 404. Resource {$_SERVER['REQUEST_URI']} not found."
        );

        call_user_func($route->action(), $route);
    }

    /**
     * Resolves the route for the given HTTP method, path, and query parameter names.
     * 
     * @param HttpMethod $httpMethod
     * @param string $path The URL path to match.
     * @param array $parameters The names of query parameters to match.
     * @return Route|null The matched route or null if no route matches.
     */
    private static function resolveRoute(HttpMethod $httpMethod, string $path, array $parameters): ?Route
    {
        foreach (self::$routes[$httpMethod->value] as $route) {
            if ($route->matches($path, $parameters)) {
                return $route;
            }
        }

        return null;
    }
}
