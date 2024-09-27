<?php

function isAuth(): bool {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION["user"]);
}

function debug(mixed $value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

function println(mixed $value = '', $line_break = '<br>') {
    echo $value . $line_break;
}
