<?php 

// require "../app/Helpers/functions.php";
// require "../app/Models/BaseModel.php";
// require "../app/Database/DB.php";
// require "../app/Models/User.php";
// require "../app/Models/Task.php";
// require "../app/Enums/TaskStatus.php";

// use Kevinhdzz\MyTasks\Database\DB;
// use Kevinhdzz\MyTasks\Models\BaseModel;
// use Kevinhdzz\MyTasks\Models\Task;
// use Kevinhdzz\MyTasks\Models\User;

// BaseModel::setDb(new DB());

require "../bootstrap/app.php";

use Kevinhdzz\MyTasks\Models\User;
use Kevinhdzz\MyTasks\Models\Task;

$users = User::all();
$tasks = Task::all();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/records.css">
    <title>All Records</title>
</head>
<body>
    <h2>Users (<?= count($users) ?>):</h2>
    <table>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
        </tr>
        <?php foreach ($users as $user) { ?>
        <tr>
            <td><?= $user->id ?></td>
            <td><?= $user->username ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->password ?></td>
        </tr>
        <?php } ?>
    </table>

    <br>

    <h2>Tasks (<?= count($tasks) ?>):</h2>
    <table>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>User id</th>
        </tr>
        <?php foreach ($tasks as $task) { ?>
        <tr>
            <td><?= $task->id ?></td>
            <td><?= $task->title ?></td>
            <td><?= $task->description ?></td>
            <td><?= $task->status->value ?></td>
            <td><?= $task->user_id ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
