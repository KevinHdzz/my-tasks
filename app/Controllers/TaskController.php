<?php

namespace MyTasks\Controllers;

use MyTasks\Enums\TaskStatus;
use MyTasks\Exceptions\HttpNotFoundException;
use MyTasks\Models\Task;

class TaskController {
    public static function changeStatus(): void
    {
        $task = Task::find($_GET["task-id"]);

        if (is_null($task))
            throw new HttpNotFoundException("HTTP 404 NOT FOUND.");

        if (is_null(TaskStatus::tryFrom($_GET["status"]))) header("Location: /");
        
        $task->status = match (TaskStatus::from($_GET["status"])) {
            TaskStatus::COMPLETED => TaskStatus::PENDING,
            TaskStatus::PENDING => TaskStatus::COMPLETED,
        };
        
        $task->save();

        header("Location: /");
    }
}
