<?php

namespace MyTasks\Enums;

enum TaskStatus: string {
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}
