<?php

namespace Kevinhdzz\MyTasks\Models;

class User extends BaseModel {
    protected string $table = 'users';

    public array $columns = [
        'id',
        'username',
        'email',
        'password',
        'created_at',
        'updated_at',
    ];

    public string $username;
    public string $email;
    public string $password;

    public function construct(string $username, string $email, string $password): static {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        return $this;
    }

    public static function tasks(int $userId): array
    {
        return self::$db->statement("SELECT * FROM tasks WHERE user_id = ?", [$userId]);
    }
}
