<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap" rel="stylesheet">
    <!-- styles -->
    <link rel="stylesheet" href="static/css/normalize.css">
    <link rel="stylesheet" href="/static/css/main.css">
    <title>MyTasks | Home</title>
</head>

<body>
    <header class="header">
        <nav class="navbar container">
            <div class="navbar-logo">
                <a href="/">MyTasks</a>
            </div>
            <div class="navbar-nav">
                <?php if (isAuth()) : ?>
                    <a href="#"><?= $_SESSION["user"]["email"] ?></a>
                    <a href="/">Home</a>
                    <a href="#">Logout</a>
                <?php else: ?>
                    <a href="#">Register</a>
                    <a href="#">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="main small-container">
        <div class="tasks">
            <?php foreach ($tasks as $task) : ?>
                <div class="task">
                    <div class="task-details">
                        <h3 class="task-title"><?= $task->title ?></h3>
                        <p class="task-description"><?= $task->description ?></p>
                    </div>
                    <div class="task-options">
                        <a class="task-status" href="/tasks/change-status?task-id=<?= $task->id ?>&status=<?= $task->status->value ?>">
                            <?= $task->status->value ?>
                        </a>
                        <a href="#">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.94 5 19 10.06 9.062 20a2.25 2.25 0 0 1-.999.58l-5.116 1.395a.75.75 0 0 1-.92-.921l1.395-5.116a2.25 2.25 0 0 1 .58-.999L13.938 5Zm7.09-2.03a3.578 3.578 0 0 1 0 5.06l-.97.97L15 3.94l.97-.97a3.578 3.578 0 0 1 5.06 0Z" fill="#ffffff" />
                            </svg>
                        </a>
                        <a href="#">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.5 6a1 1 0 0 1-.883.993L20.5 7h-.845l-1.231 12.52A2.75 2.75 0 0 1 15.687 22H8.313a2.75 2.75 0 0 1-2.737-2.48L4.345 7H3.5a1 1 0 0 1 0-2h5a3.5 3.5 0 1 1 7 0h5a1 1 0 0 1 1 1Zm-7.25 3.25a.75.75 0 0 0-.743.648L13.5 10v7l.007.102a.75.75 0 0 0 1.486 0L15 17v-7l-.007-.102a.75.75 0 0 0-.743-.648Zm-4.5 0a.75.75 0 0 0-.743.648L9 10v7l.007.102a.75.75 0 0 0 1.486 0L10.5 17v-7l-.007-.102a.75.75 0 0 0-.743-.648ZM12 3.5A1.5 1.5 0 0 0 10.5 5h3A1.5 1.5 0 0 0 12 3.5Z" fill="#ffffff" />
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="/static/js/home.js"></script>
    
</body>
</html>
