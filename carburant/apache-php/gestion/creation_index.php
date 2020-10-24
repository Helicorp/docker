<?php

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0); //0=NOLIMIT

$date = date("Ymd");

$baseUrl =  "http://11.5.0.6:9200/carburant-$date";

$docIndex = array (
                "settings" => array (
                    "number_of_shards" => "1",
                    "number_of_replicas" => "0"
                ),
                "mappings" => array (
                    "properties" => array(
                        "Type" => array (
                            "type" => "keyword",
                        ),
                        "id" => array (
                            "type" => "text",
                        ),
                        "Service" => array (
                            "type" => "text"
                        ),
                        "Carburant" => array (
                            "type" => "keyword"
                        ),
                        "Relever" => array (
                            "type" => "date"
                        ),
                        "adresse" => array (
                            "type" => "text"
                        ),
                        "ville" => array (
                            "type" => "keyword"
                        ),
                        "latitude" => array (
                            "type" => "double"
                        ),
                        "longitude" => array (
                            "type" => "double"
                        ),
                        "prix" => array(
                            "type" => "double"
                        ),
                        "location" => array(
                            "type" => "geo_point"
                        )
                    )));

$jsonData = json_encode($docIndex);

$curlInit = curl_init();
curl_setopt($curlInit, CURLOPT_URL, $baseUrl);
curl_setopt($curlInit, CURLOPT_PORT, '9200');
curl_setopt($curlInit, CURLOPT_TIMEOUT, 200);
curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlInit, CURLOPT_FORBID_REUSE, 0);
curl_setopt($curlInit, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($curlInit, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt ($curlInit, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
$response = curl_exec($curlInit);
//echo $response;

if (!curl_errno($curlInit))
{
	switch ($http_code = curl_getinfo($curlInit, CURLINFO_HTTP_CODE))
    {
    case 200:
	echo "pas d'erreur";
	break;
    default:
        echo 'Unexpected HTTP code: ', $http_code, "\n";
    }
}

?>