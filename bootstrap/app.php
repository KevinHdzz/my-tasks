<?php

require '../vendor/autoload.php';

use Kevinhdzz\MyTasks\Database\DB;
use Kevinhdzz\MyTasks\Models\BaseModel;

date_default_timezone_set('America/Mexico_City');

try {
    BaseModel::setDb(new DB());
} catch (PDOException $e) {
    echo $e->getMessage(); 
}

?>
