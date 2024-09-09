<?php

namespace Kevinhdzz\MyTasks\Models;

use Kevinhdzz\MyTasks\Enums\ConversionFormats;
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
