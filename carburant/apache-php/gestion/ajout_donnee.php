<?php

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0); //0=NOLIMIT

/*$file = fopen("C:\\wamp64\\www\\xml-Json\\PrixCarburants_quotidien_20200926.xml", 'r+');

flock($file, LOCK_EX);

// Load the data
$data = fread($file, filesize("C:\\wamp64\\www\\xml-Json\\PrixCarburants_quotidien_20200926.xml"));

$xml = simplexml_load_string($data);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

file_put_contents('test.json', $json);*/

// Initialize a file URL to the variable
$url = 'https://donnees.roulez-eco.fr/opendata/instantane';

// Use basename() function to return the base name of file
$file_name = basename($url);

// Use file_get_contents() function to get the file
// from url and use file_put_contents() function to
// save the file by using base name
if(file_put_contents( $file_name,file_get_contents($url)))
{
    echo "File downloaded successfully <br>";

    // assuming file.zip is in the same directory as the executing script.
    $file = 'instantane';

// get the absolute path to $file
    $path = pathinfo(realpath($file), PATHINFO_DIRNAME);

    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE)
    {
        // extract it to the path we determined above
        $zip->extractTo($path);
        $zip->close();
        echo "WOOT! $file extracted to $path <br>";
        unlink('instantane');

        $date = date("Ymd");

        $fichier = 'PrixCarburants_instantane.xml';

        $contenu = simplexml_load_file($fichier);

        $baseUrl =  "http://11.5.0.6:9200/carburant-$date";

//echo $index;
        foreach($contenu as $carburant)
        {
            //print_r($carburant);
            foreach($carburant->prix as $prix)
            {
                //echo 'type : prix | ' . 'ville : '. $carburant->ville .' | id : ' .$carburant['id'] . ' | adresse : '.$carburant->adresse . ' | carburant : ' . $prix['nom'] . ' | prix : ' . $prix['valeur']/1000 .  ' | latitude : ' . $carburant['latitude'] /100000 . ' | longitude : ' . $carburant['longitude']/100000 . ' | relever : ' . $prix['maj'] . ' | liste de service : ';
                //foreach($carburant->services->service as $service)

                //{
                //echo $service . ', ';
				
				$Relever = DateTime::createFromFormat("Y-m-d H:i:s", (string)$prix['maj']);
				
				//echo $Relever->format('Y-m-dTH:i:s');
				
                $cart = array('Type' => 'prix', 'ville' => (string)$carburant->ville, 'id' => (string)$carburant['id'], 'adresse' => (string)$carburant->adresse, 'prix' => (double)$prix['valeur'], 'Carburant' => (string)$prix['nom'], 'Service' => (array)$carburant->services->service, 'Relever' => $Relever->format('Y-m-d') . 'T' . $Relever->format('H:i:s'), 'longitude' => (string)$carburant['longitude']/100000, 'latitude' => (string)$carburant['latitude']/100000, 'location' => array("lat" => (string)$carburant['latitude']/100000, "lon" => (string)$carburant['longitude']/100000));
                //}
                /*echo '<br>';
                print_r($cart);
                echo '<br>';*/
                $baseUrl = "http://11.5.0.6:9200/carburant-$date/_doc/";

                $jsonData = json_encode($cart);

                $curlInit = curl_init();
                curl_setopt($curlInit, CURLOPT_URL, $baseUrl);
                curl_setopt($curlInit, CURLOPT_PORT, '9200');
                curl_setopt($curlInit, CURLOPT_TIMEOUT, 2000);
                curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curlInit, CURLOPT_FORBID_REUSE, 0);
                curl_setopt($curlInit, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($curlInit, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt ($curlInit, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
                $response = curl_exec($curlInit);
                curl_close($curlInit);
				echo '<br>';
                echo $response ;
				echo '<br>';

            }
            foreach($carburant->rupture as $rupture)
            {
                //echo 'type : rupture | ' . 'ville : '. $carburant->ville . ' | id : ' .$carburant['id'] . ' | adresse : '.$carburant->adresse . ' | carburant : ' . $rupture['nom'] .  ' | latitude : ' . $carburant['latitude'] /100000 . ' | longitude : ' . $carburant['longitude']/100000 . ' | relever : ' . $prix['maj'] . ' | liste de service : ';
                /*foreach($carburant->services->service as $service)
                {
                    echo $service . ', ';

                }*/
				
				$Relever = DateTime::createFromFormat("Y-m-d H:i:s", (string)$prix['maj']);
				
                $cart = array('Type' => 'rupture', 'ville' => (string)$carburant->ville, 'id' => (string)$carburant['id'], 'adresse' => (string)$carburant->adresse, 'Carburant' => (string)$prix['nom'], 'Service' => (array)$carburant->services->service, 'Relever' => $Relever->format('Y-m-d') . 'T' . $Relever->format('H:i:s'), 'longitude' => (string)$carburant['longitude']/100000, 'latitude' => (string)$carburant['latitude']/100000, 'location' => array("lat" => (string)$carburant['latitude']/100000, "lon" => (string)$carburant['longitude']/100000));
                /*echo '<br>';
                print_r($cart);
                echo '<br>';*/

                $baseUrl = "http://11.5.0.6:9200/carburant-$date/_doc/";

                $jsonData = json_encode($cart);

                $curlInit = curl_init();
                curl_setopt($curlInit, CURLOPT_URL, $baseUrl);
                curl_setopt($curlInit, CURLOPT_PORT, '9200');
                curl_setopt($curlInit, CURLOPT_TIMEOUT, 2000);
                curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curlInit, CURLOPT_FORBID_REUSE, 0);
                curl_setopt($curlInit, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($curlInit, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt ($curlInit, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
                $response = curl_exec($curlInit);
                curl_close($curlInit);
                //echo $response ;

            }
        }
		unlink("PrixCarburants_instantane.xml");
    }
    else
    {
        echo "Doh! I couldn't open $file <br>";
    }
}


?>
