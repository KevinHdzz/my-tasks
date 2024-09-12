<?php

namespace Kevinhdzz\MyTasks\Exceptions;

use Exception;

class HttpNotFoundException extends Exception {
    public function __construct(string $message = "Resource not found.")
    {
        parent::__construct($message);
    }
}
