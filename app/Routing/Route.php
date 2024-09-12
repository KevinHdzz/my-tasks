<?php

namespace Kevinhdzz\MyTasks\Routing;

use Closure;

class Route {
    private string $path;
    private Closure|array $action;
    /** Expected query parameters. */
    private array $queryParams;

    public function __construct(string $path, Closure|array $action, array $queryParams = [])
    {
        $this->path = $path;
        $this->action = $action;
        $this->queryParams = $queryParams;
    }

    public function matches(string $path): bool
    {
        return $this->path == $path;     
    }

    public function action(): Closure|array
    {
        return $this->action;
    }

    public function queryParams(): array
    {
        return $this->queryParams;
    }
}
