<?php

require __DIR__ . '/../vendor/autoload.php';

use MyTasks\Database\DB;
use MyTasks\Models\BaseModel;

date_default_timezone_set('America/Mexico_City');

try {
    BaseModel::setDb(new DB());
} catch (PDOException $e) {
    echo $e->getMessage(); 
    exit;
}

?>
