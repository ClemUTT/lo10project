<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;



// get all customers

$app->get('/api/terrains', function(Request $request, Response $response){

    $client = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

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

try {
    $client = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;



$document = $terrains->findOne(
	['_id' => $id1]
); 

if(empty($document) == true) {
	echo "terrain inexistant";
} else {
	echo json_encode($document);
}

 }

catch (MongoDB\Driver\Exception\AuthenticationException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {

    echo "Exception:", $e->getMessage(), "\n";
}



});

//ajouter terrain - Tolerant Reader 

$app->post('/api/terrain/add', function(Request $request, Response $response){
	$headers = $request->getHeader('Content-Type');
	if ($headers[0] == "application/xml") {   //Traitement si body au format xml
		$data = $request->getBody();
        $terrainsss = new SimpleXMLElement($data);

		$id = $terrainsss->id;
		
		$id1 = (int)$id;   // Caster toutes les variables pour pouvoir les exploiter
		

		$longitude = $terrainsss->longitude;
		$long = (float)$longitude;
		

		$latitude = $terrainsss->latitude;
		$lat = (float)$latitude;

		$type = $terrainsss->Type;
		$ty = (string)$type;
		$adresse=$terrainsss->Adresse;
		$addr = (string)$adresse;
		$pratique=$terrainsss->Pratique;
		$prat=(string)$pratique;
		$libre=$terrainsss->libre;
		
		if (strtolower($libre) == 'true') {  //la variable libre contient un booléan, mais pas au sens de php. Il faut réaffecter la variable en fonction de la valeur envoyer.
	        $lib = true;
		}
		if (strtolower($libre) == 'false') {
			$lib = false;
		}
		if (strtolower($libre) != 'false' && strtolower($libre) != 'true' ) {
			$lib = null;
		}
		
		
		if(empty($id1) == true || empty($long) ==true || empty($lat) ==true || empty($ty) ==true || empty($addr) == true || empty($prat) ==true || is_null($lib) ==true )	{
			echo "<p>Vous devez renseigner le header dans son ensemble<br>
			       <id></id><br>
				   <longitude></longitude><br>
				   <latitude></latitude><br>
				   <Type></Type><br>
				   <Adresse></Adresse><br>
				   <Pratique></Pratique><br>
				   <libre></libre></p>";
		}

	else {	
		try {

		$client = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;

$document = $terrains->findOne(
	['_id' => $id1]
); 

if(empty($document)==false) {
	echo "ce terrain existe déjà";
}

else {

	$insert = array (

		'_id' => $id1,
		'longitude' => $long1,
		'latitude' => $latitude,
		'Type' => $type,
		'Adresse' => $addr,
		'Pratique' => $pratique,
		'Libre d\'accès' => $libre
		
		);

		$terrains->insertOne($insert);

echo"Terrain bien inséré"; 

}
	}

	catch (MongoDB\Driver\Exception\AuthenticationException $e) {

		echo "Exception:", $e->getMessage(), "\n";
	} catch (MongoDB\Driver\Exception\ConnectionException $e) {
	
		echo "Exception:", $e->getMessage(), "\n";
	} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
	
		echo "Exception:", $e->getMessage(), "\n";
	}


	
	}
	
}

if ($headers[0] == "application/json")

{	
	 

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
	$id1 = (int)$id;

if(empty($id) == true || empty($longitude) ==true || empty($latitude) ==true || empty($Type) ==true || empty($Adresse) == true || empty($Pratique) ==true || empty($Libre) ==true )	{
	echo "Vous devez renseigner le header dans son ensemble
	{'id' :
	 'longitude' :
     'latitude':
	 'Type':
	 'Adresse':
	 'Pratique':
	 'Libre d'accès':}";
}

else {	
	
try {
    $client = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;

$document = $terrains->findOne(
	['_id' => $id1]
); 

if(empty($document)==false) {
	echo "ce terrain existe déjà";
}

else {

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

echo"Terrain bien inséré";  }}

catch (MongoDB\Driver\Exception\AuthenticationException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {

    echo "Exception:", $e->getMessage(), "\n";
}
	
} 

}
});

//modifier un terrain

$app->put('/api/terrain/update/{id}', function(Request $request, Response $response){

	$id = $request->getAttribute('id'); 
	$id1 = (int)$id;
	$Type = $request->getParam('Type'); 
	

if(empty($Type) ==true)	{
	echo "Vous devez renseigner le nouveau type du terrain";
}

else {	
	
try {
    $client = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;

$document = $terrains->findOne(
	['_id' => $id1]
); 

if(empty($document)==true) {
	echo "ce terrain n'existe pas";
}

else {

$update = $terrains->updateOne(
   ['_id' => $id1],
   ['$set' => ['Type' => $Type]]

);

echo"Terrain bien modifié";  }}

catch (MongoDB\Driver\Exception\AuthenticationException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {

    echo "Exception:", $e->getMessage(), "\n";
}
	
} 
});

//supprimer un terrain




$app->delete('/api/terrain/delete/{id}', function(Request $request, Response $response){

	$id = $request->getAttribute('id'); 
	$id1 = (int)$id;

try {
    $client = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;



$document = $terrains->findOne(
	['_id' => $id1]
); 

if(empty($document) == true) {
	echo "terrain inexistant";
} else {
	$document = $terrains->deleteOne(
		['_id' => $id1]
	);
	echo "bien supprimé";
}

 }

catch (MongoDB\Driver\Exception\AuthenticationException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionException $e) {

    echo "Exception:", $e->getMessage(), "\n";
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {

    echo "Exception:", $e->getMessage(), "\n";
}



});



?>