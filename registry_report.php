<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;

// $url = 'https://devops.aptrust.org/dpnode/api-v1/';
//        $url = 'https://rest.lib.utexas.edu/api-v1/';
//        $token = 'token 2eda2b215b753f4792e6afac45db2488ae08654e';

//        $url = 'https://rest.lib.utexas.edu/api-v1/';
//        $token = 'token 8347e1927cb92e3d5b4f2902064c52d1af311cf8';

//	  $url = 'https://devops.aptrust.org/dpnode/api-v1/';
//        $token = 'token 69b0bebab7be3d158b926e94368fec3d0fb8fd34';

        $url = 'http://dan.lib.utexas.edu:8080/api-v1/';
        $token = 'token 795f1c76fad8a5ffdfb2614eafa0bb125df06a0c';

$client = new Client([
    'base_url' => $url, 
    'defaults' =>[
        'verify' => false, 
	'headers' => [
	    'Authorization' => $token,
            'Accept'     => 'application/json'
         ]
      ] 
]);

$response = $client->get("bag/?page_size=999");
$json = $response->json();

$reg_array = $json['results'];

var_dump($reg_array);
exit();

echo "\nFrom host: $url\n";

foreach($reg_array as $ra) {
    echo "First Node: " . $ra['original_node'] . " Object Id: " . $ra['uuid'] . "\n"; 
    var_dump($ra['replicating_nodes']);
}
