<?php
require_once '../../vendor/autoload.php';

$config = require('../config.php');

use Eversign\Client;
use Eversign\OAuthTokenRequest;

$client = new Client();


// STEP 1: generate URL for the user to authorize the app
$authorizationUrl = $client->generateOAuthAuthorizationUrl(array(
    'client_id' => $config['oauth_client_id'],
    'state' => 'mystate',
));
echo '<a href="' . $authorizationUrl . '">' . htmlentities($authorizationUrl) . '</a>';
