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

    public function __construct(?string $username = null, ?string $email = null, ?string $password = null)
    {
        parent::__construct();

        if (!is_null($username)) $this->username = $username;       
        if (!is_null($email)) $this->email = $email;       
        if (!is_null($password)) $this->password = $password;       
    }

    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
}
