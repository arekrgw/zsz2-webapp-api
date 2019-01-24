<?php 
    function userAuth($hash, $fingerprint, $returnUid = false) {
        require_once("_database.php");

        $db = new DB;
        
        $searchQuery = "SELECT hash, hash_time, id_device FROM devices WHERE hash=:hash AND device_fingerprint=:fingerprint";
        $searchParams = array(
            "hash" => $hash,
            "fingerprint" => $fingerprint
        );
        $search = $db->fetchDb($searchQuery, $searchParams);

        if($search->rowCount() == 1){
            $search = $search->fetch(PDO::FETCH_ASSOC);
            if($search['hash_time'] < time()){
                require_once("_generateRandomHash.php");
                $newHash = generateRandomHash(50);
                $newHashTime = time() + 60*60*24*7; // 7 days

                $updateHashQuery = "UPDATE devices SET hash=:newhash, hash_time=:hashtime WHERE hash=:hash AND device_fingerprint=:fingerprint";

                $updateHashParams = array(
                    "newhash" => $newHash,
                    "hashtime" => $newHashTime,
                    "hash" => $hash,
                    "fingerprint" => $fingerprint
                );

                $db->fetchDb($updateHashQuery, $updateHashParams);

                if($returnUid == true){
                    $response = array(
                        "hash" => $newHash,
                        "DID" => $search['id_device']
                    );
                }
                else{
                    $response = array(
                        "hash" => $newHash,
                    );
                }
                
                
                $db->dbClose();
                return $response;

            }
            else {
                $db->dbClose();
                if($returnUid == true){
                    return array(
                        "DID" => $search['id_device']
                    );
                }
                else return true;
            }
                
        }
        else {
            $db->dbClose();
            return 0;
        }
            
    }