<?php

namespace Eversign\Test;

use Eversign\Client;
use Eversign\OAuthTokenRequest;



class OAuthTest extends \PHPUnit_Framework_TestCase
{

    public function testCall()
    {

        $client = new Client(null, null, getenv('SDK_TESTING_MOCK_URL') ? getenv('SDK_TESTING_MOCK_URL') : 'http://localhost:8888/api/');

        $client->setOAuthAccessToken('test_oauth_access_token');
        $client->setSelectedBusinessById(1337);

        $documents = $client->getAllDocuments();
        $this->assertSame(sizeof($documents), 1);

    }
}
