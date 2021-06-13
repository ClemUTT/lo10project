<?php

function dump($text){
    echo '<pre>';
    echo '<div style="background-color:black;color:white;padding:1rem;">';
    print_r($text);
    echo '</div>';
    echo '</pre>';
}

function mongoToArray($mongoObjs){
    // $allReservations = $reservations->find(['Accepted' => true]); <----- mongoObjs


    $newObjs = [];
    foreach($mongoObjs as $obj){
        $id = 0;
        foreach($obj as $key => $value){
            if($key == '_id'){
                $newObjs[$value] = [];
                $id = $value;
            } else {
                $newObjs[$id][$key] = $value;
            }
        }
    }

    return $newObjs;
}

function getUserInfo(){

    if(!isset($_SESSION['calendar_token'])){
        return;
    }

    $ch_userinfo = curl_init();
    $headers = [
        'Authorization: Bearer ' . $_SESSION['calendar_token']['access_token']
    ];
    curl_setopt($ch_userinfo, CURLOPT_URL, 'https://openidconnect.googleapis.com/v1/userinfo');
    curl_setopt($ch_userinfo, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch_userinfo, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch_userinfo, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch_userinfo, CURLOPT_RETURNTRANSFER, true);

    return json_decode(curl_exec($ch_userinfo), true);

}

function getOpenIDConfiguration(){

    $ch_get = curl_init();

    curl_setopt($ch_get, CURLOPT_URL, 'https://accounts.google.com/.well-known/openid-configuration');
    curl_setopt($ch_get, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch_get, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch_get, CURLOPT_RETURNTRANSFER, true);

    return json_decode(curl_exec($ch_get), true);

}


?>