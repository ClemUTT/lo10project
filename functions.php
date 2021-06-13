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


?>