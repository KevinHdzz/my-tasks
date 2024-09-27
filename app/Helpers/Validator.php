<?php

namespace Kevinhdzz\MyTasks\Helpers;

use InvalidArgumentException;
use ValueError;

class Validator {
    private array $data;
    private array $errors;
    private string $currentField;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->errors = [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firtsErrors(): array {
        return array_map(fn (array $errors) => $errors[0], $this->errors);
    }

    public function hasErrors(): bool
    {
        return count($this->errors()) > 0;
    }

    public function check(string $field): self
    {
        if (!isset($this->data[$field]))
            throw new InvalidArgumentException("The '$field' key does not exist in the \$data array.");

        $this->currentField = $field;

        return $this;
    }

    public function notEmpty(string $errMsg): self
    {
        if (empty($this->data[$this->currentField])) {
            $this->errors[$this->currentField][] = $errMsg;
        }
        
        return $this;
    }

    public function email(string $errMsg): self
    {
        if (!filter_var($this->data[$this->currentField], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$this->currentField][] = $errMsg;
        }

        return $this;
    }

    public function minLen(int $minLen, string $errMsg): self
    {
        if (strlen($this->data[$this->currentField]) < $minLen) {
            $this->errors[$this->currentField][] = $errMsg;
        }

        return $this;
    }

    public function maxLen(int $maxLen, string $errMsg): self
    {
        if (strlen($this->data[$this->currentField]) > $maxLen) {
            $this->errors[$this->currentField][] = $errMsg;
        }

        return $this;
    }
}
