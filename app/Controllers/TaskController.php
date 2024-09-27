<?php

namespace Kevinhdzz\MyTasks\Controllers;

use Kevinhdzz\MyTasks\Routing\Route;

class TaskController {
    public static function list(Route $route): void
    {
        println("List Task from TaskController");
        debug($route->parameters());
    }
}
