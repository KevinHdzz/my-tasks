<?php

namespace Kevinhdzz\MyTasks\Routing;

use Closure;

/**
 * Represents a route in the application.
 */
class Route {
    /**
     * @var string The path associated with the route.
     */
    private string $path;

    /**
     * @var Closure|array Route action (callback or controller method).
     */
    private Closure|array $action;

    /**
     * @var array The list of expected query parameter names for the route.
     */
    private array $parameters;

    /**
     * Initializes a new Route instance.
     * 
     * @param string $path The URL path for the route.
     * @param Closure|array $action Route action.
     * @param array $parameters [Optional] Expected query parameter names for the route.
     */
    public function __construct(string $path, Closure|array $action, array $parameters = [])
    {
        $this->path = $path;
        $this->action = $action;
        $this->parameters = $parameters;
    }

    /**
     * Returns true if the given path and query parameter names match this route.
     * 
     * @param string $path
     * @param array $parameters
     * @return bool
     */
    public function matches(string $path, array $parameters): bool
    {
        if ($path !== $this->path) return false;
        if (count($this->parameters()) == 0) return true;
        
        foreach ($this->parameters as $param) {
            if (!in_array($param, $parameters)) return false;
        }
        
        return true;
    }

    /**
     * Gets the route's action.
     * 
     * @return Closure|array
     */
    public function action(): Closure|array
    {
        return $this->action;
    }

    /**
     * Gets the expected query parameter names.
     * 
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}
