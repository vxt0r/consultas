<?php

namespace MF\model;

abstract class Model{
    
    public function __construct(protected \PDO $db){}

}


?>