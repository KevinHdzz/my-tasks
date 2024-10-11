<?php

namespace MyTasks\Models;

use MyTasks\Enums\ConversionFormats;
use MyTasks\Enums\TaskStatus;

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

    public function __construct(?string $title = null, ?string $description = null, ?TaskStatus $status = null, ?int $user_id = null)
    {
        parent::__construct();

        $this->immutableColumns[] = 'user_id';

        if (!is_null($title)) $this->title = $title;
        if (!is_null($description)) $this->description = $description;
        if (!is_null($status)) $this->status = $status;
        if (!is_null($user_id)) $this->user_id = $user_id;
    }
    
    public static function formatPropsAndCols(ConversionFormats $format): array
    {
        return parent::formatPropsAndCols($format) +
            match ($format) {
                ConversionFormats::COLS_TO_PROPS => [
                    'status' => fn (string $status): TaskStatus => TaskStatus::from($status),
                ],
                ConversionFormats::PROPS_TO_COLS => [
                    'status' => fn (TaskStatus $status): string => $status->value,
                ],
            };
    }
}
