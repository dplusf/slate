<?php 

// IP to check
$domain = 'mailinator.com';
$authToken = '4b737a5da554f5ef92cd03abf753c94b86767e5d';

// Init cURL
$curl = curl_init();

// Set URL & some options
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.monapi.io/api/v1/checkdomain/' . $domain
));

// Set the authorization header
if ($authToken) {
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    	'Authorization: Token ' . $authToken
	));
}

// Send the request
$response = curl_exec($curl);

// Check for cURL & HTTP errors or return response content 
if (curl_errno($curl)) {
  echo 'Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl) . "\n";
} else {
  switch ($httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
    case 200:  # OK
      echo 'OK' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;
    case 404:
      echo 'Resource not found' . "\n";
      $responseObject = json_decode($response);
      var_dump($responseObject);
      break;  
    default:
      echo 'Unexpected HTTP-Code: ' . $httpCode . "\n";
  }
} 
// Close request to clear up some resources
curl_close($curl);
