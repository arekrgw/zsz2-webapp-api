<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header("Access-Control-Allow-Headers: POST");

    require_once('_userAuth.php');

    if(isset($_POST['hash']) && isset($_POST['fingerprint'])){

        $response = json_encode(userAuth($_POST['hash'], $_POST['fingerprint']));

        echo $response; 

    }
    else echo 0;