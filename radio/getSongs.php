<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header("Access-Control-Allow-Headers: *");


    function checkIfAnnonymous($songsArray) {
        foreach($songsArray as &$song){
            if($song['annonymous'] == "1" || $song['autor'] == null){
                $song['autor'] = "Annonyomous";  
            }
            unset($song['annonymous']);
        }

        return $songsArray;
    }

    if(isset($_GET['devicehash']) && isset($_GET['hash'])){
        $songs = array();
        require_once('../_userAuth.php');

        $isAuthorized = userAuth($_GET['hash'], $_GET['devicehash']);

        if($isAuthorized){
            require_once('../_database.php');

            $db = new DB;

            //jezeli data jest ustawiona
            $songsQuery = 'SELECT radio.id_song, radio.url, radio.title, radio.date, radio.annonymous, concat(users.imie, " ", users.nazwisko) as "autor" FROM radio LEFT JOIN devices ON radio.id_device=devices.id_device Left JOIN users ON devices.id_user=users.id_user WHERE radio.date <= :date';

            if(isset($_GET['date'])){
                
                $songsParams = array(
                    'date' => $_GET['date']
                );
                $songsResult = $db->fetchDb($songsQuery, $songsParams)->fetchAll(PDO::FETCH_ASSOC);
                $songs = checkIfAnnonymous($songsResult);

            }
            else { // jezeli nie jest ustawiona
                $date = date('Y-m-d');
                $songsParams = array(
                    'date' => $date
                );
                $songsResult = $db->fetchDb($songsQuery, $songsParams)->fetchAll(PDO::FETCH_ASSOC);

                $songs = checkIfAnnonymous($songsResult);
            }
            //sprawdzenie czy zwracany jest nowy hash

            if(is_array($isAuthorized)){
                echo json_encode(array_merge($songs, $isAuthorized));
            }
            else
                echo json_encode($songs);

        }
    }