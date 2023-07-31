<?php

namespace app;

require __DIR__ .'/Config.php';

class Connection{

    private const HOST = DB['host'];
    private const DB = DB['db'];
    private const USER = DB['user'];
    private const PASS = DB['pass'];

    public static function getDb(){
        try{
            $conn = new \PDO('mysql:host='.self::HOST.';dbname='.self::DB.';charset=utf8',self::USER,self::PASS);
            return $conn;
        }
        catch(\PDOException $e){
          echo '<p>'.$e->getMessage().'</p>';
        }

    }
}
