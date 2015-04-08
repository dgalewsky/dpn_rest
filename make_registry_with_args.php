d<?php
	
	// 
	// This utility accepts a uuid of a bag and creates a registry record for that bag.
	// It is assumed that the bag has a suffix of .tar - and is already in /dpn/outgoing - the staging area.
	//
	
	require 'vendor/autoload.php';

        use GuzzleHttp\Client;

//        $url = 'http://dan.lib.utexas.edu:8080/api-v1/';
//        $token = 'token 795f1c76fad8a5ffdfb2614eafa0bb125df06a0c';

        $url = 'https://rest.lib.utexas.edu/api-v1/';
        $token = 'token 2eda2b215b753f4792e6afac45db2488ae08654e';

        if (!isset($argv[1])) {
        	useage($argv);
        	exit();
        }
        
        $uuid = $argv[1];
               
        $bag_path = '/dpn/outgoing/' . $uuid . '.tar';
        
        if (!file_exists($bag_path)) {
            echo "File $bag_path does not exist\n";
            exit();
        }
        
        $bag_size = filesize ($bag_path);
        echo "Bag Size $bag_size\n";
        
        $client = new Client([
                'base_url' => $url,
                'defaults' =>[
                        'verify' => false,
                        'headers' => [
                                'Authorization' => $token,
                                'Accept'     => 'application/json',
                                'Content-Type' => 'application/json'
                        ]
                ]
        ]);

        //Registry

        $json = '{
		"uuid":"",
		"original_node":"",
		"admin_node":"",
		"fixities":[
                {
                 "algorithm":"sha256",                 
"digest":"3fc9ab32c37ea0855b316ae3acbd9ee941a53879dc5d2917f5152d0efbd730b5"
                }
              ],		
		"local_id": "",
		"size": 0,
		"first_version_uuid": "",
		"version": 1,
		"bag_type": "D",
		"created_at": "",
		"updated_at":""
        
        }';
        
        
	$var = json_decode($json);
	
	if ($var == null) {
	    echo "error in json\n";
	    echo $json;
	    
	    exit();
	}

	// Set values in the registry JSON

        $var->created_at = gmdate("Y-m-d\TH:i:s\Z");
	$var->updated_at = gmdate("Y-m-d\TH:i:s\Z");

	$var->uuid = $uuid;
	$var->first_version_uuid = $uuid;
	$var->original_node = "tdr";
	$var->admin_node = "tdr";

	
	$var->size = $bag_size;

	echo "DPN Object Id: " . $var ->uuid . "\n";

	echo "\n======= JSON =======\n";
	echo json_encode($var);
	
	echo "*** Before post ***\n";

	try {
            $response = $client->post('bag/', ['body' => json_encode($var)]);
            echo "Right after post\n";
        } catch (Exception $e) {
	    //echo "**EXCEPTION ** " . $e->getRequest() . "\n**End of Exception\n";

	    if ($e->hasResponse()) {
	    	echo "\nStatus Code: " . $e->getResponse()->getStatusCode() . "\n";
	    	    
		echo "Reason: " . $e->getResponse()->getReasonPhrase() . "\n";
		
		echo "Body: " . $e->getResponse()->getBody() . "\n";
		
	    }
	    exit();
        }

	echo "\n\n======= After Post =========\n";

	$json = $response->json();
        var_dump($json);

	echo "\nReturned Json: " . json_encode($json) . "\n";

function uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

                // 16 bits for "time_mid"
                mt_rand( 0, 0xffff ),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand( 0, 0x0fff ) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand( 0, 0x3fff ) | 0x8000,

                // 48 bits for "node"
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
}

function useage($argv) {
 echo "Useage: $argv[0] <dpn_object_id>\n";
	
}

