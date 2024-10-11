<?php

namespace MyTasks\View;

class View {
    /**
     * Renders a view.
     * 
     * @param string $view  View file name (without .php extension).
     * @param array<string, mixed> $data [Optional]  An associative array with data to pass to the view.
     * 
     * @throws Exception  If the view file is not found.
     */
    public static function render(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . "/../../views/$view.php";

        if (!file_exists($viewFile)) {
            throw new \Exception("View $view not found.");
        }

        extract($data);

        include_once $viewFile;
    }
}
