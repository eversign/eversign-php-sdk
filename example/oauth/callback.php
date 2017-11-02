<?php
require_once '../../vendor/autoload.php';

$config = require('../config.php');

use Eversign\Client;
use Eversign\OAuthTokenRequest;

$client = new Client();

// STEP 2: Get a token
$token_request = new OAuthTokenRequest(array(
    'client_id' => $config['oauth_client_id'],
    'client_secret' => $config['oauth_client_secret'],
    'code' => $_GET['code'],
    'state' => 'mystate',
));

$token = $client->requestOAuthToken($token_request);

$client->setOAuthAccessToken($token);
$client->setSelectedBusinessById(6);

$documents = $client->getAllDocuments();
echo 'found ' . sizeof($documents) . ' documents';
