<?php
class DB_CREDENTIALS{
    public $host = 'localhost';
    public $dbname = 'zsz2webapp';
    public $username = 'root';
    public $password = '';
}
class DB extends DB_CREDENTIALS{
    public $con;

    public function __construct(){
        $this->con = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->username, $this->password);
        $this->con->exec("set names utf8");
    }

    public function fetchDb($query, $arr = null){

        $res = $this->con->prepare($query);
        $res->execute($arr);
        return $res;
    }

    public function dbClose(){
        $this->con = null;
    }
}
