<?php

function debug(mixed $value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

function println(mixed $value = '', $line_break = '<br>') {
    echo $value . $line_break;
}
