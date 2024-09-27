<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/main.css">
    <title>MyTasks | Register</title>
</head>
<body>
    <div class="form-container">
        <form action="/register" method="POST" class="form">
            <p class="text-center">Create Account</p>
            <div class="field">
                <label for="username">Username:</label>
                <div class="field-input">
                    <input class="username" type="text" name="username" id="username" placeholder="Your username" value="<?= htmlspecialchars($user["username"] ?? "") ?>" autofocus>
                    <p class="error-msg"><?= $errors["username"] ?? "" ?></p>
                </div>
            </div>
            <div class="field">
                <label for="email">Email:</label>
                <div class="field-input">
                    <input class="email"  name="email" id="email" placeholder="Your email address" value="<?= htmlspecialchars($user["email"] ?? "") ?>">
                    <p class="error-msg"><?= $errors["email"] ?? "" ?></p>
                </div>
            </div>
            <div class="field">
                <label for="password">Password:</label>
                <div class="field-input">
                    <input class="password" type="password" name="password" id="password" placeholder="Your password (at least 6 characteres)" value="<?= htmlspecialchars($user["password"] ?? "") ?>">
                    <p class="error-msg"><?= $errors["password"] ?? "" ?></p>
                </div>
            </div>
            <div class="submit">
                <p class="register">You are registered?
                    <a href="/login">Log in</a>
                </p>
                <input class="btn submit-btn" type="submit" value="Create account">
            </div>
        </form>
    </div>
</body>
</html>
