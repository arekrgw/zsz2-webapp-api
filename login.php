<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: *");

if(
    isset($_POST['login']) && 
    isset($_POST['password']) && 
    isset($_POST['fingerprint']) &&
    isset($_POST['osname']) &&
    isset($_POST['browsername'])) {

    require_once('_database.php');
    $db = new DB;

    $findUserQuery = 'SELECT * FROM users WHERE login=:login AND password=:password';
    $params = array(
        "login" => htmlentities($_POST['login']),
        "password" => htmlentities($_POST['password'])
        // "password" => hash("sha256", htmlentities($_POST['password']))
    );
    $user = $db->fetchDb($findUserQuery, $params);

    if($user->rowCount() == 1){
        $user = $user->fetch(PDO::FETCH_ASSOC);
        

        $checkIfDeviceExist = "SELECT * FROM devices WHERE device_fingerprint=:fingerprint AND id_user=:uid";
        $checkParams = array(
            "fingerprint" => $_POST['fingerprint'],
            "uid" => $user['id_user']
        );

        $device = $db->fetchDb($checkIfDeviceExist, $checkParams);

        require_once("_generateRandomHash.php");

        $hash = generateRandomHash(50);
        $hashTime = time() + 60*60*24*7; // 7 days

        if($device->rowCount() == 1) {
            $updateQuery = "UPDATE devices SET hash=:hash, hash_time=:hashtime WHERE device_fingerprint=:fingerprint AND id_user=:uid";
            $updateParams = array(
                "hash" => $hash,
                "hashtime" => $hashTime,
                "uid" => $user['id_user'],
                "fingerprint" => $_POST['fingerprint']
            );

            $db->fetchDb($updateQuery, $updateParams);
        }
        else {
            $insertQuery = "INSERT INTO devices VALUES (null, :osname, :browsername, :fingerprint, :hash, :hashtime, :uid)";
            $insertParams = array(
                "hash" => $hash,
                "hashtime" => $hashTime,
                "uid" => $user['id_user'],
                "osname" => $_POST['osname'],
                "browsername" => $_POST['browsername'],
                "fingerprint" => $_POST['fingerprint']
            );

            $db->fetchDb($insertQuery, $insertParams);
        }

        $response = json_encode(array(
            "hash" => $hash
        ));

        echo $response;

    }
    else
        echo 0;
}