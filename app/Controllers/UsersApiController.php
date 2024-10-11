<?php

namespace MyTasks\Controllers;

use MyTasks\Models\User;

class UsersApiController {
    public static function users(): void
    {
        if (!isAuth()) {
            header("Location: /login");
            return;
        }

        echo json_encode(
            array_map(
                fn(User $user) => $user->mapPropertiesToColumns(),
                User::all()
            )
        );
    }
}
