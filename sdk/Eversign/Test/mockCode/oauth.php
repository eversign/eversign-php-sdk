<?php

use Eversign\Client;
use Eversign\OAuthTokenRequest;
use Eversign\Exception;

$client = new Client(null, null, $stack);

// STEP 2: Get a token
$token_request = new OAuthTokenRequest(array(
    'client_id' => 'oauth_client_id',
    'client_secret' => 'oauth_client_secret',
    'code' => 'code',
    'state' => 'mystate',
));

$token = $client->requestOAuthToken($token_request);

$client->setOAuthAccessToken($token);
$client->setSelectedBusinessById(1337);

$documents = $client->getAllDocuments();
