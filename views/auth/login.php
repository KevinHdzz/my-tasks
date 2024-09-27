<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/main.css">
    <title>MyTasks | Login</title>
</head>

<body>
    <div class="form-container">
        <form action="/login" method="post" class="form login">
            <p class="text-center">Login</p>
            <?php if (isset($errors) && !is_assoc($errors)) : ?>
                <div class="head-errors">
                    <?php foreach ($errors as $error) : ?>
                        <p class="error-msg"><?= $error ?></p>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            <div class="field">
                <label for="identifier">Identifier: </label>
                <div class="field-input">
                    <input
                        type="text"
                        name="identifier"
                        id="identifier"
                        placeholder="Your username or email"
                        value="<?= htmlspecialchars($user["identifier"] ?? "") ?>"
                        autofocus>
                    <p class="error-msg"><?= $errors["identifier"] ?? "" ?></p>
                </div>
            </div>
            <div class="field">
                <label for="password">Password:</label>
                <div class="field-input">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Your password"
                        value="<?= htmlspecialchars($user["password"] ?? "") ?>">
                    <p class="error-msg"><?= $errors["password"] ?? "" ?></p>
                </div>
            </div>
            <div class="submit">
                <p class="register">You are not registered?
                    <a href="/login">Create account</a>
                </p>
                <input class="btn submit-btn" type="submit" value="Login">
            </div>
        </form>
    </div>
</body>

</html>
