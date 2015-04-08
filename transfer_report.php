<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

if (!isset($argv[1])) {
    echo "Useage: $argv[0] <server_token>\n";
    exit();
}


$url = 'https://chronopolis01.umiacs.umd.edu/api-v1/';
//$$token = 'token 69b0bebab7be3d158b926e94368fec3d0fb8fd34';
$token = 'token 51ac675f0e7790674d37a20a90ac3db6413100b5';

if ($argv[1] == "chron") {
    $url = 'https://chronopolis01.umiacs.umd.edu/api-v1/';
    //$$token = 'token 69b0bebab7be3d158b926e94368fec3d0fb8fd34';
    $token = 'token 51ac675f0e7790674d37a20a90ac3db6413100b5';
} else if ($argv[1] == "apt") {
    $url = 'https://devops.aptrust.org/dpnode/api-v1/';
    $token = 'token 69b0bebab7be3d158b926e94368fec3d0fb8fd34';
} else if ($argv[1] == "tdr") {
    $url = 'https://rest.lib.utexas.edu/api-v1/';
    $token = 'token  e370be231a197d237a851b85100f7ae890d13473';
    //$token = 'token 2eda2b215b753f4792e6afac45db2488ae08654e';
} else {
    echo "Useage: $argv[0] <server_token>\n";
    exit();
}

echo "Token " . $token . "\n";

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
echo "Base URL: " . $url . "\n";

$response = $client->get('replicate/');

//$response = $client->get('transfer/?status=C');

$json = $response->json();
//var_dump($json);

$xfers = $json['results'];

foreach($xfers as $xfer) {
    $link = $xfer['link'];
    echo "Replication Id: " . $xfer['replication_id'] . " Status: " . $xfer['status'] . " Node: " . $xfer['from_node'] . "\nLink: $link \n\n";

}
