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
                $query = 'INSERT INTO admins VALUES (null, :login, :imie, :nazwisko, :email, :password, :latadolaczenia, :type)';
                $params = array(
                    'login' => $_POST['login'],
                    'imie' => $_POST['imie'],
                    'nazwisko' => $_POST['nazwisko'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'latadolaczenia' => $_POST['latadolaczenia'],
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