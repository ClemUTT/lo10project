<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// get all customers

$app->get('/api/terrains', function(Request $request, Response $response){

    $client = new MongoDB\Client('mongodb+srv://[username]:[password]@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;



$document = $terrains->find(
);

foreach($document as $doc) 
{
    echo json_encode($doc);
    echo "</br>";
}



});

//sélectionner un terrain
$app->get('/api/terrain/{id}', function(Request $request, Response $response){

	$id = $request->getAttribute('id'); 
	$id1 = (int)$id;


    $client = new MongoDB\Client('mongodb+srv://[username]:[password]@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;



$document = $terrains->findOne(
	['_id' => $id1]
);

echo json_encode($document); 



});

//ajouter terrain

$app->post('/api/terrain/add', function(Request $request, Response $response){

	$id = $request->getParam('_id'); 
	//var_dump($id);
	$longitude = $request->getParam('longitude'); 
	//var_dump($longitude);
	$latitude = $request->getParam('latitude'); 
	//var_dump($latitude);
	$Type = $request->getParam('Type'); 
	//var_dump($Type);
	$Adresse = $request->getParam('Adresse'); 
	//var_dump($Adresse);
	$Pratique = $request->getParam('Pratique'); 
	//var_dump($Pratique);
	$Libre = $request->getParam('Libre d\'accès'); 
	//var_dump($Libre);

	

    $client = new MongoDB\Client('mongodb+srv://[username]:[password]@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;


$insert = array (

'_id' => $id,
'longitude' => $longitude,
'latitude' => $latitude,
'Type' => $Type,
'Adresse' => $Adresse,
'Pratique' => $Pratique,
'Libre d\'accès' => $Libre

);

$terrains->insertOne($insert);

echo"Terrain bien inséré";


});

//supprimer un terrain
$app->delete('/api/terrain/delete/{id}', function(Request $request, Response $response){

	$id = $request->getAttribute('id'); 
	$id1 = (int)$id;


    $client = new MongoDB\Client('mongodb+srv://[username]:[password]@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;




$document = $terrains->deleteOne(
	['_id' => $id1]
);




});

?>