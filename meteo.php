<?php
//7.2.14
/* echo php_ini_loaded_file();
$curl = curl_init('http://api.openweathermap.org/data/2.5/weather?q=Troyes&appid={APIKEY}&units=metric&lang=fr');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($curl);
echo $data;

if($data === false) {
    var_dump(curl_error($curl));

} else {
//p
    //var_dump(curl_getinfo($curl, CURLINFO_HTTP_CODE));  
   $data = json_decode($data, true);
   echo 'Il fait ' . $data['main']['temp'] . '°C';
}
curl_close($curl); */





// Patron Asynchronous Response Handler avec la bibliothèque guzzle


require_once(__DIR__ . '/vendor/autoload.php');
$client = new GuzzleHttp\Client();

$promises = [
    $client->getAsync('http://api.openweathermap.org/data/2.5/weather?q=Troyes&appid={APIKEY}&units=metric&lang=fr')->then(function ($response) {
        
         $data = json_decode($response->getBody(), true);

         ?>

<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">

   <style>
   
    </style>




  </head>
<body>
<div class="container-fluid">
<h6>Petit coucou</h6>
    <div class="row justify-content-center">
        <div class="col-12 col-md-4 col-md-12 col-md-12">
            <div class="card">
                <div class="d-flex">
                    <h6 class="flex-grow-1">London</h6>
                    <h6>16:08</h6>
                </div>
                <div class="d-flex flex-column temp mt-5 mb-3">
                    <h1 class="mb-0 font-weight-bold" id="heading"> <?php echo 'Il fait ' . $data['main']['temp'] . '°C'; ?> </h1> <span class="small grey">Stormy</span>
                </div>
                <div class="d-flex">
                    <div class="temp-details flex-grow-1">
                        <p class="my-1"> <img src="https://i.imgur.com/B9kqOzp.png" height="17px"> <span> 40 km/h </span> </p>
                        <p class="my-1"> <i class="fa fa-tint mr-2" aria-hidden="true"></i> <span> <?php echo $data['main']['humidity'] . '%'; ?> </span> </p>
                        <p class="my-1"> <img src="https://i.imgur.com/wGSJ8C5.png" height="17px"> <span> 0.2h </span> </p>
                    </div>
                    <div> <img src="https://i.imgur.com/Qw7npIg.png" width="100px"> </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>




         <?php
         
        
        }),
  
];

$results = GuzzleHttp\Promise\unwrap($promises);

// Wait for the requests to complete, even if some of them fail
$results = GuzzleHttp\Promise\settle($promises)->wait();



?>






 





