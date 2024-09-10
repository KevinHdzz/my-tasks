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
}
