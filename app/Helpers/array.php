<?php

function is_assoc(array $array): bool {
    if (empty($array)) return false;

    $keys = array_keys($array);

    return array_keys($keys) !== $keys;
}

function assoc_array_from_obj_props(object $obj, string ...$props): array {
    $result = [];

    foreach ($props as $prop) {
        $result[$prop] = $obj->$prop;
    }

    return $result;
}
