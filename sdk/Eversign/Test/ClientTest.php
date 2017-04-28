<?php

namespace Eversign\Test;

use Eversign\Client;

class ClientTest extends \PHPUnit_Framework_TestCase {

    public function testCreateClient() {
        $client = new Client("");
        $client->getBusinesses();
    }

}
