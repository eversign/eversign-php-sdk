<?php

/*
 * The MIT License
 *
 * Copyright 2017 Dominik Kukacka.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Eversign;

use Eversign\Config;
use GuzzleHttp\Client as GuzzleClient;
use JMS\Serializer\SerializerBuilder;


class OAuthTokenRequest {

    /**
     * The client id of your app
     *
     */
    protected $client_id = null;

    /*
     * The secret token of your app
     *
     * @var string
     */
    protected $client_secret = null;

    /**
     * The state parameter should be generated on the serverside and passed
     * through the oauth flow for security reasons.
     * You should check if the returned state in the Oauth callback matches your
     * generated one.
     *
     * @var string
     */
    protected $state = null;

    /**
     * The code parameters for url generation
     *
     * @var string
     */
    protected $code = null;

    public function __construct($obj) {
        if(array_key_exists('client_id', $obj)) {
            $this->client_id = $obj['client_id'];
        }

        if(array_key_exists('client_secret', $obj)) {
            $this->client_secret = $obj['client_secret'];
        }

        if(array_key_exists('state', $obj)) {
            $this->state = $obj['state'];
        }

        if(array_key_exists('code', $obj)) {
            $this->code = $obj['code'];
        }
    }

    public function toArray(): array
    {
        return array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'state' => $this->state,
            'code' => $this->code,
        );
    }

}
