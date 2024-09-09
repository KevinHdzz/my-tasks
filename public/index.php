<?php

require '../bootstrap/app.php';

use Kevinhdzz\MyTasks\Models\Task;
use Kevinhdzz\MyTasks\Models\User;
use Kevinhdzz\MyTasks\Enums\TaskStatus;

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

// $kavin = $newUser("Kavin", "kavin@kavin.com", "kavin123");
// $kavin->save();

// $task = $newTask("New task title", "New task description", TaskStatus::PENDING, 3);
// $task->save();

// debug(User::all());
// debug(Task::all());

// $task = Task::find(1);
// debug($task);

// $user = User::find(4);
// debug($user);

// $testCreateMethod();
// $testFindMethod();
// $testAllMethod();

exit;

// Successfull
$testAllMethod = function (): void {
    $tasks = Task::all();

    println("Tasks:");
    foreach ($tasks as $task)
    {
        debug($task->columnValues());
    }

    println();
    println("Users:");
    foreach (User::all() as $user)
    {
        debug($user->columnValues());
    }
};

// Successfull
$testFindMethod = function (): void {
    $user = User::find(3);
    debug($user);
    debug($user->columnValues());

    $task = Task::find(1);
    debug($task);
    debug($task->columnValues());
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
