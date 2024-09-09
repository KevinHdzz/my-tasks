<?php

namespace Kevinhdzz\MyTasks\Models;

use Kevinhdzz\MyTasks\Enums\TaskStatus;

class Task extends BaseModel {
    protected string $table = 'tasks';
    protected array $columns = [
        'id',
        'title',
        'description',
        'status',
        'user_id',
    ];
    protected bool $insertTimestamps = false;

    public string $title;
    public ?string $description;
    public TaskStatus $status;
    public int $user_id;

    protected static function formatColsToProps(): array
    {
        return [
            'status' => fn (string $status): TaskStatus => TaskStatus::from($status),
        ]; # + parent::formatColsToProps();
    }

    protected static function formatPropsToCols(): array
    {
        return [
            'status' => fn (TaskStatus $status): string => $status->value,
        ]; # + parent::formatColsToProps();
    }
}
