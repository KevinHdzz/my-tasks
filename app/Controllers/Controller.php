<?php

namespace Kevinhdzz\MyTasks\Controllers;

class Controller {
    public static function render(string $view, $data = []): void
    {
        $viewFile = __DIR__ . "/../../views/$view.php";

        if (!file_exists($viewFile)) {
            throw new \Exception("View $view not found.");
        }

        extract($data);

        include $viewFile;
    }
}
