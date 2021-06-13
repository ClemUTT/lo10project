<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';
require __DIR__ . '/../functions.php';
session_start();
// session_destroy();

$client = getClient();
$service = new Google_Service_Calendar($client);

if(isset($_POST['terrain'])){

    /**********Insertion de la réservation en BDD***********/

    $clientDb = new MongoDB\Client('mongodb+srv://yvan_lo10:yvanlo10@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $clientDb->terraindb;
    $reservations = $terraindb->reservation;

    $month = $_POST["month"] + 1;
    $day = $_POST["day"];
    $t = json_decode($_POST["terrain"][0], true);
    $start = ($_POST["start"] > $_POST["end"] ? $_POST["end"] : $_POST["start"]);
    $end = ($_POST["start"] > $_POST["end"] ? $_POST["start"] : $_POST["end"]);

    // dump($_POST);
    // dump($reservations->find(['Accepted' => true]));
    // dump(mongoToArray($reservations->find(['Accepted' => true])));


    $insertReservation = $reservations->insertOne(
        [
        '_id' => (sizeof(mongoToArray($reservations->find(['Accepted' => true]))) + 1),
        'email_holder' => getUserInfo()['email'],
        'terrain_id' => (int) $t['id'],
        'start' => mktime($start, 0, 0, $month, $day, 2021),
        'end' => mktime($end, 0, 0, $month, $day, 2021),
        'Accepted' => true
        ]
    );



    /***********Insertion de la réservation dans l'Agenda Google***********/

    $event = new Google_Service_Calendar_Event(array(
        'summary' => 'Réservation',
        'location' => '800 Howard St., San Francisco, CA 94103',
        'description' => 'Réservation de match au terrain ' . $t["name"],
        'start' => array(
          'dateTime' => "2021-". $month ."-".$day."T".($start-1).":00:00-00:00",
          'timeZone' => 'Europe/Paris',
        ),
        'end' => array(
          'dateTime' => "2021-". $month ."-".$day."T".($end-1).":00:00-00:00",
          'timeZone' => 'Europe/Paris',
        ),
        'recurrence' => array(
          'RRULE:FREQ=DAILY;COUNT=1'
        ),
        'attendees' => array(
          array('email' => 'lpage@example.com'),
          array('email' => 'sbrin@example.com'),
        ),
        'reminders' => array(
          'useDefault' => FALSE,
          'overrides' => array(
            array('method' => 'email', 'minutes' => 24 * 60),
            array('method' => 'popup', 'minutes' => 10),
          ),
        ),
      ));

      $calendarId = 'primary';
      $event = $service->events->insert($calendarId, $event);


      header('Location: http://localhost/lo10project/index2.php?reservation-confirmed=true');


} else {
    header('Location: http://localhost/lo10project/index2.php?connected=true');
}





// dump(getOpenIDConfiguration());
// dump(getUserInfo());















function getClient(){

    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes([Google_Service_Calendar::CALENDAR, 'https://www.googleapis.com/auth/userinfo.email']);
    $client->setAuthConfig('client_secret.json');
    $client->setRedirectUri('http://localhost/lo10project/api/calendarHandler.php');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');




    if(isset($_GET['code'])){ //C'est bon il a autorisé

        $_SESSION['code'] = $_GET['code'];

        $client->authenticate($_GET['code']);
        $access_token = $client->getAccessToken();

        if($client->isAccessTokenExpired()){ // S'il refresh la page alors qu'il a autorisé, le token doit être regénéré

            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $auth_url = $client->createAuthUrl();
                goToAuthURL($auth_url);
            }

        } else {


            $client->setAccessToken($access_token); // Permet d'utiliser l'API

            if (array_key_exists('error', $access_token)) {// Check to see if there was an error.
                throw new Exception(join(', ', $access_token));
            }


            $_SESSION['calendar_token'] = $access_token;
            header('Location: http://localhost/lo10project/api/calendarHandler.php');


        }


    } else { // On lui demande l'autorisation s'il ne l'a pas déjà fait


        if(isset($_SESSION['calendar_token'])){
            $client->setAccessToken($_SESSION['calendar_token']); // Permet d'utiliser l'API

            if(array_key_exists('error', $_SESSION['calendar_token'])){
                $auth_url = $client->createAuthUrl();
                goToAuthURL($auth_url);
            }

        } else {
            $auth_url = $client->createAuthUrl();
            goToAuthURL($auth_url);
        }
    }

    return $client;
}

function goToAuthURL($auth_url){
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
}


function getUserInfo(){
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

// $ch_post = curl_init();
// $params = [
//     'code' => $_SESSION['code'],
//     'client_id' => GOOGLE_ID,
//     'client_secret' => GOOGLE_SECRET,
//     'redirect_uri' => 'http://localhost/test_oauth/api/quickstart.php',
//     'grant_type' => 'authorization_code'
// ];
// curl_setopt($ch_post, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch_post, CURLOPT_POSTFIELDS, $params);

// $response = curl_exec($ch_post);
// curl_close($ch_post);
// dump($response);

?>