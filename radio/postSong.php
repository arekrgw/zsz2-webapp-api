<?php 
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header("Access-Control-Allow-Headers: *");

    if(isset($_POST['url']) && isset($_POST['title']) && isset($_POST['annonymous']) && isset($_POST['hash']) && isset($_POST['devicehash'])) {
        require_once('../_userAuth.php');
        echo $_POST['annonymous'];
        $isAuthorized = userAuth($_POST['hash'], $_POST['devicehash'], true);

        if($isAuthorized){

            require_once('../_database.php');
            $db = new DB;

            $postSongQuery = 'INSERT INTO radio VALUES (null, :url, :title, :did, :annonymous, :date)';
            $params = array(
                "url" => htmlentities($_POST['url']),
                "title" => htmlentities($_POST['title']),
                "did" => $isAuthorized['DID'],
                "annonymous" => htmlentities($_POST['annonymous']),
                "date" => date("Y-m-d")
            );

            $db->fetchDb($postSongQuery, $params);

            if($isAuthorized['DID'] && isset($isAuthorized['hash'])) {
                unset($isAuthorized['DID']);
                echo json_encode($isAuthorized);
            }
            else{
                echo 1;
            }
            $db->dbClose();
        }
        else {
            echo 0;
        }

        
    }
    else{
        echo 0;
    }