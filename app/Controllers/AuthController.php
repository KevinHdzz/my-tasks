<?php

namespace Kevinhdzz\MyTasks\Controllers;

use Kevinhdzz\MyTasks\Helpers\Validator;
use Kevinhdzz\MyTasks\Models\User;

class AuthController extends Controller {
    public static function register(): void
    {
        if (isAuth()) {
            header("Location: /home");
        }

        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET": self::render("auth/register");
                break;

            case "POST":
                $data = [
                    "username" => trim($_POST["username"] ?? ""),
                    "email" => trim($_POST["email"] ?? ""),
                    "password" => trim($_POST["password"] ?? ""),
                ];

                $validator = new Validator($data);
                $validator->check("username")->notEmpty("Username is required");
                $validator->check("email")->notEmpty("Email is required")->email("Invalid email");
                $validator->check("password")->notEmpty("Password is required")->minLen(6, "Password must be at least 6 characteres");

                if ($validator->hasErrors()) {
                    self::render("auth/register", [
                        "errors" => $validator->firtsErrors(),
                        "user" => $data,
                    ]);

                    return;
                }

                $errors = [];

                if (count(User::where("username", $data["username"])) > 0) {
                    $errors["username"] = "Username not available";
                }
                if (count(User::where("email", $data["email"])) > 0) {
                    $errors["email"] = "This email is already registered";
                }
                
                if (count($errors) > 0) {
                    self::render("auth/register", [
                        "errors" => $errors,
                        "user" => $data,
                    ]);

                    return;
                }

                // Register user in the database
                $user = new User($data["username"], $data["email"], $data["password"]);
                $user->hashPassword();
                $user->save();

                $_SESSION["user"] = [
                    "id" => $user->id,
                    "username" => $user->username,
                    "email" => $user->email,
                ];

                header("Location: /home");
        };
    }

    public static function login(): void
    {
        if (isAuth()) {
            header("Location: /home");
        }

        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                self::render("auth/login");
                break;
            
            case "POST":
                $data = [
                    "identifier" => trim($_POST["identifier"] ?? ""),
                    "password" => trim($_POST["password"] ?? ""),
                ];

                $validator = new Validator($data);
                $validator->check("identifier")->notEmpty("Identifier is required");
                $validator->check("password")->notEmpty("Password is required");

                if ($validator->hasErrors()) {
                    self::render("auth/login", [
                        "errors" => $validator->firtsErrors(),
                        "user" => ["identifier" => $data["identifier"]],
                    ]);
                    
                    return;
                }

                $identifier = !filter_var($data["identifier"], FILTER_VALIDATE_EMAIL) ? "username" : "email";

                $user = User::where($identifier, $data["identifier"])[0] ?? null;

                if (is_null($user) || !password_verify($data["password"], $user->password)) {
                    self::render("auth/login", [
                        "errors" => ["Wrong $identifier or password"],
                        "user" => $data,
                    ]);
                    
                    return;
                }

                $_SESSION["user"] = [
                    "id" => $user->id,
                    "username" => $user->username,
                    "email" => $user->email,
                ];

                header("Location: /home");

                break;
        }
    }
}
