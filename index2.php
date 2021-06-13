<?php 
require 'vendor/autoload.php';




$client = new GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));

$client->getAsync('http://api.openweathermap.org/data/2.5/weather?q=Troyes&appid=f53f9bfb3f09f4560e8f8720d4a6685d&units=metric&lang=fr')->then(function ($response) {
        
     $response->getBody(); 
    $data = json_decode($response->getBody(), true);

    $temps = $data['weather'][0]['main'];
    $temp2 = $data['main']['temp'];

    $client2 = new MongoDB\Client('mongodb+srv://anas-admin:anas1998@cluster0.qyzeh.mongodb.net/testdb');

    $terraindb = $client2->terraindb;
    $terrains = $terraindb->terrains;
    $allTerrains = $terrains->find(['Libre d\'accès' => true]);

    $document = $terrains->findOne(
        ['_id' => 1]
    );
    
    $document2 = $terrains->findOne(
        ['_id' => 2]
    );
    
    $document3 = $terrains->findOne(
        ['_id' => 3]
    );

    /********** Récupération des réservations **********/

$reservations = $terraindb->reservation;
$allReservations = $reservations->find(['Accepted' => true]);


$newReservations = [];
foreach($allReservations as $rr){
    $id = 0;
    foreach($rr as $key => $value){
        if($key == '_id'){
            $newReservations[$value] = [];
            $id = $value;
        } else {
            $newReservations[$id][$key] = $value;
        }
    }
}


$newTerrains = [];
foreach($allTerrains as $tt){
    $id = 0;
    foreach($tt as $key => $value){
        if($key == '_id'){
            $newTerrains[$value] = [];
            $id = $value;
        } else {
            $newTerrains[$id][$key] = $value;
        }
    }
}


function dump($txt){
    echo '<pre><br><br><br><br><br><br><br><br><br><br><br>';
    print_r($txt);
    echo '</pre>';
}

   


   













?>







<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Freelancer - Start Bootstrap Theme</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />

        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/calendar_app.css"/>
        <link rel="stylesheet" href="css/external.css"/>

        <style type="text/css">
            #map{ /* la carte DOIT avoir une hauteur sinon elle n'apparaît pas */
                height:400px;
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">Pick UTT GAME</a>
                <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#carte">Carte</a></li>
              
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#about">Réservation</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#contact">Vidéo</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        
        <!-- Masthead-->
        <header class="masthead bg-primary text-white text-center">
            <div class="container d-flex align-items-center flex-column">
                <!-- Masthead Avatar Image-->
                <img class="masthead-avatar mb-5" src="assets/img/avataaars.svg" alt="" />
                <!-- Masthead Heading-->
                <h1 class="masthead-heading text-uppercase mb-0">Pick UTT GAME</h1>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Masthead Subheading-->
                <p class="masthead-subheading font-weight-light mb-0">Oragnisez des matchs proche de chez vous</p>
            </div>
        </header>
        <!-- Portfolio Section-->

        <section class="page-section carte" id="carte">
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Carte & Météo</h2>
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <h4><?php echo 'Il fait ' . $temp2 . '°C. Voici les terrains que nous vous proposons en fonction de la météo:'; ?></h4>

                <div id="map">
                    <!-- Ici s'affichera la carte -->
                </div>
                   <!-- Fichiers Javascript -->
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
        <script>
        let map;
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat:<?php echo $document['latitude']; ?>, lng: <?php echo $document['longitude']; ?> },
                zoom: 12,
            });

            function addMarker(porperty) {

                const marker = new google.maps.Marker({
                position:porperty.location,
                map:map,
                });

                const detailwindow = new google.maps.InfoWindow({
                content : porperty.content
                });
                
                marker.addListener("click", ()=>{
                detailwindow.open(map, marker);
                })
            }

            <?php 
            $nombre_document = $terrains->count();
            $liste_document = array();
        
            
            ; // ceci est à completer dès que la donnée sera disponible

            // je crée un tableau qui stocke tous nos documents
            for ($i=0; $i < $nombre_document ; $i++ ) {        
                $liste_document[] =  $terrains->findOne(['_id' => ($i+1)]);
            }

            // j'affiche les marqueurs de terrain sur la carte en fonction de la météo
            if($temp2 == "pluie"){
                for ($i=0; $i < $nombre_document; $i++) { 
                    if($liste_document[$i]['Type'] == "Synthetique"){  ?>
                        addMarker({location:{lat: <?php echo $liste_document[$i]['latitude']; ?>, lng: <?php echo $liste_document[$i]['longitude']; ?> }, content: "Type : <?php echo $liste_document[$i]['Type']; ?>" + "<br>Pratique : <?php echo $liste_document[$i]['Pratique']; ?>" });                   
                        <?php  }
                }
            }else{
                for ($i=0; $i < $nombre_document; $i++) { ?>
                        addMarker({location:{lat: <?php echo $liste_document[$i]['latitude']; ?>, lng: <?php echo $liste_document[$i]['longitude']; ?> }, content: "Type : <?php echo $liste_document[$i]['Type']; ?>" + "<br>Pratique : <?php echo $liste_document[$i]['Pratique']; ?>" });                   
                    <?php }
            }?>
            
            //je mets en commentaire la version preccedente des marqueurs.
            /*addMarker({location:{lat: <?php echo $document['latitude']; ?>, lng: <?php echo $document['longitude']; ?> }, content: "Type : <?php echo $document['Type']; ?>" + "<br>Pratique : <?php echo $document['Pratique']; ?>" });
            addMarker({location:{lat: <?php echo $document2['latitude']; ?>, lng: <?php echo $document2['longitude']; ?> }, content: "Type : <?php echo $document2['Type']; ?>" + "<br>Pratique : <?php echo $document2['Pratique']; ?>" });
            addMarker({location:{lat: <?php echo $document3['latitude']; ?>, lng: <?php echo $document3['longitude']; ?> }, content: "Type : <?php echo $document3['Type']; ?>" + "<br>Pratique : <?php echo $document3['Pratique']; ?>" });
            */
        }

       </script>
        <script
       src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCamyOsko-YreKmCaQe4YAg1Swcy1XGnmA&callback=initMap&libraries=places&v=weekly"
       async
       ></script> 

       
                
      </section>

    

        <!-- About Section-->
        <section class="page-section bg-primary text-white mb-0" id="about" style="height: 1100px;">
            <div class="container">
                <!-- About Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-white">Réservation</h2>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- About Section Content-->


                <div class="calendar-container">
                    <h1 class="year">&mdash; 2021 &mdash;</h1>
                    <h2 class="description">Réservez un terrain ici *</h2>
                    <ul>
                        <?php
                            $months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
                            $days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
                            foreach($months as $number => $month){
                                ?>

                                <li>
                                    <article tabindex="0">
                                        <div class="outline"></div>
                                        <div class="dismiss"></div>
                                        <div class="binding"></div>

                                        <?php
                                        echo "<h1 id=\"$number\">$month</h1>";
                                        ?>
                                        <table>
                                            <thead>
                                                <tr>
                                                <th>Lun</th>
                                                <th>Mar</th>
                                                <th>Mer</th>
                                                <th>Jeu</th>
                                                <th>Ven</th>
                                                <th>Sam</th>
                                                <th>Dim</th>
                                                </tr>
                                            </thead>
                                        <?php
                                            $firstline = true;
                                            $tr = 1;
                                            for($i=1; $i <=31; $i++){
                                                $isReserved = false;
                                                $nom = "";
                                                $hours = "";
                                                foreach($newReservations as $r_id => $r_value){
                                                    $monthRes = date('m', $r_value["start"]);
                                                    $dayRes = date('d', $r_value["start"]);



                                                    if($number == $monthRes-1 && $i == $dayRes){
                                                        foreach($newTerrains as $t_id => $t_value){
                                                            if($t_id == $r_value["terrain_id"]){
                                                                $nom = '[' . $t_value["Type"] . '] ' . $t_value["Adresse"];
                                                            }
                                                        }

                                                        $startHour = date('h', $r_value["start"]) + 1;
                                                        $endHour = date('h', $r_value["end"]) + 1;
                                                        $hours = $startHour . ':00h - ' . $endHour . 'h:00';
                                                        $isReserved = true;
                                                    }

                                                }

                                                echo ($tr == 1 ? "<tr>" : "") . "<td class=\"". ($isReserved ? "is-reserved" : "") ."\"><div class=\"day\">$i</div>" . ($isReserved ? "<div class=\"holiday\">Réservation (". $hours .")</div>" : "") . "</td>" . ($tr == 7 ? "</tr>" : "");
                                                $tr = $tr == 7 ? 1 : $tr+1;

                                            }
                                        ?>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </table>
                                    </article>
                                    </li>

                                <?php
                            }
                        ?>
                    </ul>
                    <div class="notes">
                        * Autorisez le service Google Calendar lors de votre réservation.
                    </div>

                    <div id="reservations">
                            <h4 id="reservation-title">Réservation pour le </h4>
                            <hr/>
                            <form action="api/quickstart.php" method="post">
                                <label for="terrain">Choisissez un terrain</label>
                                <select name="terrain[]" id="terrain">
                                    <?php
                                        foreach($newTerrains as $id => $value){
                                            $nom = '[' . $value['Type'] . '] ' . $value['Adresse'];

                                            $value = '{"id":"'. $id .'","name":"'. $nom .'"}';
                                            echo "<option value='". '{"id":"'. $id .'","name":"'. $nom .'"}' ."'>". $nom ."</option>";
                                        }
                                    ?>
                                </select>

                                <br/>
                                <br/>
                                <br/>

                                <div id="hoursDiv1">
                                    <label for="start">Début</label>
                                    <select name="start" id="start">
                                        <option value="8">08:00</option>
                                        <option value="9">09:00</option>
                                        <option value="10">10:00</option>
                                        <option value="11">11:00</option>
                                        <option value="12">12:00</option>
                                        <option value="13">13:00</option>
                                        <option value="14">14:00</option>
                                        <option value="15">15:00</option>
                                        <option value="16">16:00</option>
                                        <option value="17">17:00</option>
                                        <option value="18">18:00</option>
                                        <option value="19">19:00</option>
                                    </select>

                                    </div>
                                    <br/>
                                    <div id="hoursDiv2">


                                    <label for="end">Fin</label>
                                    <select name="end" id="end">
                                        <option value="8">08:00</option>
                                        <option value="9">09:00</option>
                                        <option value="10">10:00</option>
                                        <option value="11">11:00</option>
                                        <option value="12">12:00</option>
                                        <option value="13">13:00</option>
                                        <option value="14">14:00</option>
                                        <option value="15">15:00</option>
                                        <option value="16">16:00</option>
                                        <option value="17">17:00</option>
                                        <option value="18">18:00</option>
                                        <option value="19">19:00</option>
                                    </select>

                                </div>

                                <input hidden type="text" id="reservationMonth" name="month" value="0">
                                <input hidden type="text" id="reservationDay" name="day" value="0">


                                <br/>
                                <br/>

                                <button id="reserved" class="nav-link py-2 px-0 px-lg-3 rounded" type="submit"> Réserver !</button>
                            </form>
                    </div>

                </div>






            </div>
        </section>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container">
                <!-- Contact Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Vidéo</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
        <!-- Contact Section Form-->
        <div class = "container">
                <div id="player"></div>

    <script>
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '360',
          width: '640',
          videoId: 'x9jYSoWclPY',
            
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
          
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
          
          done = true;
        }
      }
      function stopVideo() {
        player.stopVideo();
      }
    </script>
    </div>

    <div class="divider-custom">
                    
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Entrainement</h2>
                    
                </div>
    
    
            </div>
        </section>


        <section class="page-section" id="contact">
            <div class="container">
                <!-- Contact Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Types de terrain</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
        <!-- Contact Section Form-->
        <div class = "container">
<?php

     }); 

$promises = [
    $client->getAsync('http://api.openweathermap.org/data/2.5/weather?q=Troyes&appid=f53f9bfb3f09f4560e8f8720d4a6685d&units=metric&lang=fr')->then(function ($response) {
         $data = json_decode($response->getBody(), true);
        }),

        $client->getAsync('https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=1d7e6113ac773874aaf2758983f681eb&user_id=14253409@N00&tags=eos,pitch,hockey,6D,365,astroturf,synthetic,goals,night,adobe,canon,challenge,dark,editing&tag_mode=all&format=json&nojsoncallback=1')->then(function ($response) {

             $data = json_decode($response->getBody(), true);
             $photos= $data['photos']['photo'];
foreach($photos as $photo) {
    $url= 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'.jpg';
    ?>
<div class="container">
  <div class="row">
    <div class="col-sm">
    <img src="<?php echo $url; ?>">
    </div>
    <div class="col-sm">
    <h1> Terrain Synthétique</h1>
     <h4>Pratiquable même par temps de pluie</h4>
     <h4>Privilégier des crampons AG</h4>
    </div>
    </div>
</div>
    

<?php
  


    
}
            }),

            $client->getAsync('https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=1d7e6113ac773874aaf2758983f681eb&user_id=14469553@N05&tags=soccer,field&tag_mode=all&format=json&nojsoncallback=1')->then(function ($response) {

                $data = json_decode($response->getBody(), true);
                $photos= $data['photos']['photo'];
   foreach($photos as $photo) {
       $url1= 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'.jpg';
       ?>


<div class="container">
  <div class="row">
<div class="col-sm">
<img src="<?php echo $url1; ?>">
    </div>
    <div class="col-sm">
    <h1> Terrain en herbe</h1>
    <h4>Privilégier des crampons FG par temps sec</h4>
    <h4>Privilégier des crampons SG par temps de pluie</h4>
    
    </div>
    
  </div>
</div>

<?php
       
   }
               }),   

               $client->getAsync('https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=1d7e6113ac773874aaf2758983f681eb&user_id=146944136@N03&text=Urbansoccer&per_page=1&format=json&nojsoncallback=1')->then(function ($response) {

                $data = json_decode($response->getBody(), true);
                $photos= $data['photos']['photo'];
   foreach($photos as $photo) {
       $url1= 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'.jpg';
       ?>


<div class="container">
  <div class="row">
<div class="col-sm">
<img src="<?php echo $url1; ?>">
    </div>
    <div class="col-sm">
    <h1> Terrain synthétique 5vs5</h1>
    <h4>Privilégier des crampons TURF</h4>
    
    </div>
    
  </div>
</div>

<?php
       
   }
               }),            
               
               
  
];

$results = GuzzleHttp\Promise\unwrap($promises);

// Wait for the requests to complete, even if some of them fail
$results = GuzzleHttp\Promise\settle($promises)->wait();



?>
    
    </div>
    
    
            </div>
        </section>
        <!-- Footer-->
        <footer class="footer text-center">
            <div class="container">
                <div class="row">
                    <!-- Footer Location-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">Location</h4>
                        <p class="lead mb-0">
                            2215 John Daniel Drive
                            <br />
                            Clark, MO 65243
                        </p>
                    </div>
                    <!-- Footer Social Icons-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">Around the Web</h4>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-linkedin-in"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-dribbble"></i></a>
                    </div>
                    <!-- Footer About Text-->
                    <div class="col-lg-4">
                        <h4 class="text-uppercase mb-4">About Freelancer</h4>
                        <p class="lead mb-0">
                            Freelance is a free to use, MIT licensed Bootstrap theme created by
                            <a href="http://startbootstrap.com">Start Bootstrap</a>
                            .
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Copyright Section-->
        <div class="copyright py-4 text-center text-white">
            <div class="container"><small>Copyright © Your Website 2020</small></div>
        </div>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed">
            <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
        </div>
        <!-- Portfolio Modals-->
        <!-- Portfolio Modal 1-->
        <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-labelledby="portfolioModal1Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal1Label">Log Cabin</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="assets/img/portfolio/cabin.png" alt="" />
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia neque assumenda ipsam nihil, molestias magnam, recusandae quos quis inventore quisquam velit asperiores, vitae? Reprehenderit soluta, eos quod consequuntur itaque. Nam.</p>
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio Modal 2-->
        <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-labelledby="portfolioModal2Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal2Label">Tasty Cake</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="assets/img/portfolio/cake.png" alt="" />
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia neque assumenda ipsam nihil, molestias magnam, recusandae quos quis inventore quisquam velit asperiores, vitae? Reprehenderit soluta, eos quod consequuntur itaque. Nam.</p>
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio Modal 3-->
        <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-labelledby="portfolioModal3Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal3Label">Circus Tent</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="assets/img/portfolio/circus.png" alt="" />
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia neque assumenda ipsam nihil, molestias magnam, recusandae quos quis inventore quisquam velit asperiores, vitae? Reprehenderit soluta, eos quod consequuntur itaque. Nam.</p>
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio Modal 4-->
        <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-labelledby="portfolioModal4Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal4Label">Controller</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="assets/img/portfolio/game.png" alt="" />
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia neque assumenda ipsam nihil, molestias magnam, recusandae quos quis inventore quisquam velit asperiores, vitae? Reprehenderit soluta, eos quod consequuntur itaque. Nam.</p>
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio Modal 5-->
        <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-labelledby="portfolioModal5Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal5Label">Locked Safe</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="assets/img/portfolio/safe.png" alt="" />
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia neque assumenda ipsam nihil, molestias magnam, recusandae quos quis inventore quisquam velit asperiores, vitae? Reprehenderit soluta, eos quod consequuntur itaque. Nam.</p>
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio Modal 6-->
        <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-labelledby="portfolioModal6Label" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal6Label">Submarine</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="assets/img/portfolio/submarine.png" alt="" />
                                    <!-- Portfolio Modal - Text-->
                                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia neque assumenda ipsam nihil, molestias magnam, recusandae quos quis inventore quisquam velit asperiores, vitae? Reprehenderit soluta, eos quod consequuntur itaque. Nam.</p>
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="assets/mail/jqBootstrapValidation.js"></script>
        <script src="assets/mail/contact_me.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <script src="js/jquery.min.js"></script>
        <script src="js/calendar_app.js"></script>
    </body>
</html>
