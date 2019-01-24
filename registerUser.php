<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: *");

    if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['imie']) && isset($_POST['nazwisko']) && isset($_POST['latadolaczenia']) && isset($_POST['email']) && isset($_POST['code'])){
        require_once("_database.php");

        $db = new DB;

        $query = 'SELECT * FROM kod_szkoly WHERE active=1';

        $code = $db->fetchDb($query);
        if($code->rowCount() == 1){
            $code = $code->fetch(PDO::FETCH_ASSOC);

            if($code['code'] == $_POST['code']){
                $query = 'INSERT INTO users VALUES (null, :login, :imie, :nazwisko, :email, :password, :latadolaczenia, :type)';
                $params = array(
                    'login' => htmlentities($_POST['login']),
                    'imie' => htmlentities($_POST['imie']),
                    'nazwisko' => htmlentities($_POST['nazwisko']),
                    'email' => htmlentities($_POST['email']),
                    'password' => md5(htmlentities($_POST['password'])),
                    'latadolaczenia' => htmlentities($_POST['latadolaczenia']),
                    'type' => ''
                );
                $db->fetchDb($query, $params);
                echo true;
                $db->dbClose();
            }
            else echo 0;


        }
        else echo 0;
        $db->dbClose();
    }
    else echo 0;