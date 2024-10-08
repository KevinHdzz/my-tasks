<?php

require '../bootstrap/app.php';

use Kevinhdzz\MyTasks\Controllers\AuthController;
use Kevinhdzz\MyTasks\Controllers\Controller;
use Kevinhdzz\MyTasks\Models\Task;
use Kevinhdzz\MyTasks\Models\User;
use Kevinhdzz\MyTasks\Enums\TaskStatus;
use Kevinhdzz\MyTasks\Routing\Router;
use Kevinhdzz\MyTasks\Routing\Route;
use Kevinhdzz\MyTasks\Exceptions\HttpNotFoundException;


Router::get(new Route(path: "/home", action: function () {
    session_start();
    debug($_SESSION);
    // if (!isset($_SESSION["user"])) {
    //     header("Location: /login");
    // }
    Controller::render("home", [
        "users" => User::all(),
        "tasks" => Task::all(),
    ]);
}, parameters: []));
Router::get(new Route("/register", [AuthController::class, 'register']));
Router::post(new Route("/register", [AuthController::class, 'register']));
Router::get(new Route("/login", [AuthController::class, 'login']));
Router::post(new Route("/login", [AuthController::class, 'login']));
Router::get(new Route("/logout", function () {
    session_start();
    session_destroy();
    header("Location: /home");
}));

Router::get(new Route(path: "/tasks", action: function () {
    println("<h2>Tasks:</h2>");
    $tasks = Task::all();
    foreach ($tasks as $task) {
        println("$task->title  -  $task->description  -  {$task->status->value}  -  $task->user_id");
        println();
    }
}, parameters: ["name", "id"]));

Router::get(new Route("/tasks/create", fn () => print "Add new Task"));
Router::get(new Route("/tasks/update", function (Route $route) {
    debug($route->parameters());
}, parameters: ["id"]));


Router::post(new Route("tasks/create", function (Route $route) {
    debug($_POST);
}));


try {
    Router::resolve();
} catch (HttpNotFoundException $e) {
    println($e->getMessage());
}

// debug(Router::$routes);
exit;


$newUser = function (string $username, string $email, string $password): User {
    $user = new User();
    $user->username = $username;
    $user->email = $email;
    $user->password = $password;

    return $user;
};

$newTask = function (string $title, string $description, TaskStatus $status, int $userId): Task {
    $task = new Task;
    $task->title = $title;
    $task->description = $description;
    $task->status = $status;
    $task->user_id = $userId;

    return $task;
};

$testUpdateMethod = function (): void {
    $task = Task::find(2);
    // debug($task);

    // println("<br>Updated data:");
    // $task->status = TaskStatus::COMPLETED;
    // debug($task->columnValues());

    // println("<br>Saving changes:");
    // $task->save();

    $user = User::find(2);
    debug($user->mapPropertiesToColumns());

    println("<br>Updated data:");
    $user->email = "test@test.com";
    $user->password = "test123";
    debug($user->mapPropertiesToColumns());

    println("<br>Saving changes:");
    $user->save();

    exit;

    $user = User::find(7);
    debug($user->columnValues());

    println("<br>Updated data:");
    $user->username = "Bizor";
    $user->email = "bizor@bizor.com";
    $user->password = "bizor123";
    debug($user->columnValues());

    println("<br>Saving changes:");
    $user->save();
};


// $kavin = $newUser("Kavin", "kavin@kavin.com", "kavin123");
// $kavin->save();

// $task = $newTask("New task title", "New task description", TaskStatus::PENDING, 3);
// $task->save();

// $testUpdateMethod();
// $testCreateMethod();
// $testFindMethod();
// $testAllMethod();

exit;

// Successfull
$testCreateMethod = function () use ($newUser, $newTask): void {
    // $user = (new User())->construct("djfsd", "ldfjs", "ldjflsd");
    // $user->construct("xd", "xdd", "xddd");
    $danna = $newUser("Danna", "danna@danna.com", "danna123");
    // $danna->createImproved();
    $prodem = $newUser("Prodem", "prodem@prodem.com", "prodem123");
    // $prodem->save();
    $dante = $newUser("Dante", "dante@dante.com", "dante123");
    // $dante->save();

    println();
    // $task = new Task();
    // $task->title = 'Complete the create method';
    // $task->description = 'Make the create method work for any model';
    // $task->status = TaskStatus::PENDING;
    // $task->user_id = 2;
    $task = $newTask('Complete the create method', 'Make the create method work for any model', TaskStatus::PENDING, 2);
    // $task->save();
    $task2 = $newTask("Test task title", "Test task description", TaskStatus::PENDING, 4);
    // $task2->save();
};

// Successfull
$testAllMethod = function (): void {
    $tasks = Task::all();

    println("Tasks:");
    foreach ($tasks as $task) {
        debug($task->mapPropertiesToColumns());
    }

    println();
    println("Users:");
    foreach (User::all() as $user) {
        debug($user->mapPropertiesToColumns());
    }
};

// Successfull
$testFindMethod = function (): void {
    $user = User::find(3);
    debug($user);
    debug($user->mapPropertiesToColumns());

    $task = Task::find(1);
    debug($task);
    debug($task->mapPropertiesToColumns());
};

$task->title = "Anything";
$task->description = "Anything too";
$task->status = TaskStatus::PENDING;
$task->user_id = -1;

$task->save();

println();
println();

$user = User::find(3);
debug($user->columnValues());

println();
println();

$user = new User();
// $user->id = 20;
$user->username = 'Jhon';
$user->email = 'jhon@jhon.com';
$user->password = 'jhon123';

// $user->save();

println();
println();

// try {
//     $db = new DB();
// } catch (PDOException $e) {
//     exit("Error to connect to the database: {$e->getMessage()}");
// }

// debug($db->statement('DESCRIBE tasks'));

// debug($db->statement(
//     'INSERT INTO users (username, email, password) VALUES (?, ?, ?)',
//     ['Kavin', 'kavin@kavin.com', 'kavin123']
// ));

// debug($db->statement(
//     statement: 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)',
//     bind: ['test', 'test@test.com', 'test123']
// ));

// debug($db->statement('SELECT * FROM users'));

debug(User::find(2));

echo '<br><br>';
debug(User::all());
echo '<br>';
debug(Task::all());
