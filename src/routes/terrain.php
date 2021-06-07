<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;



// sélectionner tous les terrains

$app->get('/api/terrains', function(Request $request, Response $response){
try {
    $client = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client->terraindb;
$terrains = $terraindb->terrains;



$document = $terrains->find(
);

foreach($document as $doc) 
{
    echo json_encode($doc);
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
	$data['error'] = 'terrain inexistant';
return $response->withJson($data, 404);

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

$app->post('/api/newterrain', function(Request $request, Response $response){
	$headers = $request->getHeader('Content-Type');
	if ($headers[0] == "application/xml") {

		
	  $data = $request->getBody();
      $terrainsss = new SimpleXMLElement($data);
	

       
		$id = $terrainsss[0]->id;
	     $id1 = (int)$id;
		
		
        
		
		$longitude = $terrainsss[0]->longitude;
		
		$long = (float)$longitude;

		

		$latitude = $terrainsss[0]->latitude;
		$lat = (float)$latitude;
		

		$type = $terrainsss[0]->Type;
		$ty = (string)$type;
		$adresse=$terrainsss[0]->Adresse;
		$addr = (string)$adresse;
		$pratique=$terrainsss[0]->Pratique;
		$prat=(string)$pratique;
		$libre=$terrainsss[0]->libre;
		
		if (strtolower($libre) == 'true') {
	        $lib = true;
		}
		if (strtolower($libre) == 'false') {
			$lib = false;
		}
		if (strtolower($libre) != 'false' && strtolower($libre) != 'true' ) {
			$lib = null;
		
		}
		
    
		
		
		
		if(empty($id1) == true || empty($long) ==true || empty($lat) ==true || empty($ty) ==true || empty($addr) == true || empty($prat) ==true || is_null($lib) ==true )	{
			$data1['error'] = 'Vous devez renseigner le body dans son ensemble';
            return $response->withJson($data1, 400);
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
	$data2['error'] = 'Un terrain avec cet id existe deja';
            return $response->withJson($data2, 400);
}

else {

	$insert = array (

		'_id' => $id1,
		'longitude' => $long,
		'latitude' => $lat,
		'Type' => $ty,
		'Adresse' => $addr,
		'Pratique' => $prat,
		'Libre d\'accès' => $lib
		
		);

		$terrains->insertOne($insert);

		$data3['success'] = 'Terrain bien ajoute';
		return $response->withJson($data3, 201);
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
	$data['error'] = 'Vous devez renseigner le body dans son ensemble';
            return $response->withJson($data, 400);
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
	$data1['error'] = 'Un terrain avec cet id existe deja';
            return $response->withJson($data1, 400);
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

$data2['success'] = 'Terrain bien ajoute';
return $response->withJson($data2, 201); }}

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


	$headers = $request->getHeader('Content-Type');
	$id = $request->getAttribute('id'); 
	$id1 = (int)$id;
	if ($headers[0] == "application/xml") {
        $dataxml = $request->getBody();
      $terraintype = new SimpleXMLElement($dataxml);
	
       
		$type = $terraintype[0]->type;
		
		
if(empty($type) == true)	{
	$data['error'] = 'Vous devez renseigner le nouveau type du terrain';
            return $response->withJson($data, 400);
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
		$data1['error'] = 'Terrain inexistant';
				return $response->withJson($data1, 400);
	}
	
	else {
	
	$update = $terrains->updateOne(
	   ['_id' => $id1],
	   ['$set' => ['Type' => $type]]
	
	);
	
	$data2['success'] = 'Type de terrain bien modifié';
        return $response->withJson($data2, 200); }}
	
	catch (MongoDB\Driver\Exception\AuthenticationException $e) {
	
		echo "Exception:", $e->getMessage(), "\n";
	} catch (MongoDB\Driver\Exception\ConnectionException $e) {
	
		echo "Exception:", $e->getMessage(), "\n";
	} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
	
		echo "Exception:", $e->getMessage(), "\n";
	}
}
	}

	if ($headers[0] == "application/json") {
	
	$Type = $request->getParam('Type'); 
	

if(empty($Type) ==true)	{
	$data['error'] = 'Vous devez renseigner le nouveau type du terrain';
            return $response->withJson($data, 400);
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
	$data1['error'] = 'Terrain inexistant';
            return $response->withJson($data1, 400);
}

else {

$update = $terrains->updateOne(
   ['_id' => $id1],
   ['$set' => ['Type' => $Type]]

);

$data2['success'] = 'Type de terrain bien modifié';
        return $response->withJson($data2, 200);  }}

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
	$data1['error'] = 'Terrain inexistant';
            return $response->withJson($data1, 400);
}
} else {
	$document = $terrains->deleteOne(
		['_id' => $id1]
	);
	$data2['success'] = 'Terrain bien supprime';
        return $response->withJson($data2, 200);
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