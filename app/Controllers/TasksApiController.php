<?php

namespace MyTasks\Controllers;

use MyTasks\Models\Task;

class TasksApiController {
    public static function tasks()
    {
        if (!isAuth()) {
            header("Location: /login");
            return;
        }

        $result = [];

        foreach (Task::all() as $task) {
            $result[] = $task->mapPropertiesToColumns();
        }

        echo json_encode($result);
    }
}
