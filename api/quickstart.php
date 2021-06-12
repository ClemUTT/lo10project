<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/../vendor/autoload.php';


/*
if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}
*/

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig( __DIR__ . '/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.

    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
$calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);
$events = $results->getItems();

if (empty($events)) {
  print "No upcoming events found.\n";
} else {
  print "Upcoming events:\n";
  foreach ($events as $event) {
      $start = $event->start->dateTime;
      if (empty($start)) {
          $start = $event->start->date;
      }
      printf("%s (%s)\n", $event->getSummary(), $start);
  }
}



/**********Insertion de la réservation en BDD***********/

$client = new MongoDB\Client('mongodb+srv://yvan_lo10:yvanlo10@cluster0.qyzeh.mongodb.net/testdb');

$terraindb = $client->terraindb;
$reservations = $terraindb->reservation;


$month = $_POST["month"] + 1;
$day = $_POST["day"];
$t = json_decode($_POST["terrain"][0], true);
$start = $_POST["start"] - 1;
$end = $_POST["end"] - 1;


$insertReservation = $reservations->insertOne(
  [
    '_id' => rand(1, 10000),
    'terrain_id' => (int) $t['id'],
    'start' => mktime($start, 0, 0, $month, $day, 2021),
    'end' => mktime($end, 0, 0, $month, $day, 2021),
    'Accepted' => true
  ]
);







/***********Insertion de la réservation dans son Agenda Google***********/


$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Réservation',
  'location' => '800 Howard St., San Francisco, CA 94103',
  'description' => 'Réservation de match au terrain ' . $terrain . "coucou",
  'start' => array(
    'dateTime' => "2021-". $month ."-".$day."T".$start.":00:00-00:00",
    'timeZone' => 'Europe/Paris',
  ),
  'end' => array(
    'dateTime' => "2021-". $month ."-".$day."T".$end.":00:00-00:00",
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
// printf('Event created: %s<br/>', $event->htmlLink);




/*********** Retour à la page d'accueil ***********/

header('Location: http://localhost/lo10project/index2.php');

?>