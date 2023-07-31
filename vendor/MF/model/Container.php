<?php

namespace MF\model;

use app\Connection;

class Container{

    public static function getModel($model){
        $class = 'app\\models\\'.ucfirst($model);
        $conn = Connection::getDb();
        return new $class($conn);
    }

}