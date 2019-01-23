<?php 
    function userAuth($hash, $fingerprint) {
        require_once("_database.php");

        $db = new DB;
        
        $searchQuery = "SELECT hash, hash_time FROM devices WHERE hash=:hash AND device_fingerprint=:fingerprint";
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

                $response = array(
                    "hash" => $newHash
                );
                
                $db->dbClose();
                return $response;

            }
            else {
                $db->dbClose();
                return true;
            }
                
        }
        else {
            $db->dbClose();
            return 0;
        }
            
    }