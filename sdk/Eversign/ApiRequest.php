<?php

/*
 * The MIT License
 *
 * Copyright 2017 Patrick Leeb.
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

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\TransferStats;
use JMS\Serializer\SerializerBuilder;

class ApiRequest {

    private $apiBaseUrl;

    private $accessKey;

    private $httpType;

    private $endPoint;

    private $serializeClass;

    private $parameters;

    private $payLoad;

    private $guzzleClient;

    private $guzzleRequestTimeout;

    /**
     * Creating a blank Request with the Access Key and specific Endpoint
     *
     * @param string $httpType
     * @param string $accessKey
     * @param string $endPoint
     * @param string $serializeClass
     * @param [] $parameters
     * @param [] $payLoad
     */

    public function __construct(
        $httpType,
        $accessKey,
        $endPoint,
        $serializeClass = "",
        $parameters = null,
        $payLoad = null,
        $apiBaseUrl = null,
        $apiRequestTimeout = Config::GUZZLE_TIMEOUT
    ) {
        $headers = ['User-Agent' => 'Eversign_PHP_SDK'];

        if(substr($accessKey, 0, strlen('Bearer ')) === 'Bearer ') {
            $headers['Authorization'] = $accessKey;
            if(Config::DEBUG_MODE) {
               echo 'authorization via oauth header: Authorization: ' . $accessKey . '<br />';
            }
        } else {
            $this->accessKey = $accessKey;
        }

        if($apiBaseUrl === null) {
            $this->apiBaseUrl = Config::API_URL;
        } else {
            $this->apiBaseUrl = $apiBaseUrl;
        }

        $this->httpType = $httpType;
        $this->guzzleClient = new GuzzleClient(['base_uri' => $this->apiBaseUrl, 'headers' => $headers]);
        $this->endPoint = $endPoint;
        $this->serializeClass = $serializeClass;
        $this->parameters = $parameters;
        $this->payLoad = $payLoad;
        $this->apiB = $apiBaseUrl;

        $this->guzzleRequestTimeout = $apiRequestTimeout;
    }

    public function requestOAuthToken($token_request) {
        $effectiveUrl = null;

        $guzzleClient = new GuzzleClient([
            'base_uri' => Config::OAUTH_URL,
            'headers' => ['User-Agent' => 'Eversign_PHP_SDK'],
            'on_stats' => function (TransferStats $stats) use (&$effectiveUrl) {
                $effectiveUrl = $stats->getEffectiveUri();
            },
        ]);

        $response = $guzzleClient->request('POST', 'token', [
            'timeout' => $this->guzzleRequestTimeout,
            'form_params' => $token_request->toArray(),
        ]);

        if(Config::DEBUG_MODE) {
            echo "<div style='background-color: #eee; padding: 20px; border: solid 1px #333; margin: 10px 0; word-break:break-all;'>";
            echo "[" . $this->httpType . "] " . $effectiveUrl ."<hr /><br />";
            echo "<h3 style='margin-top: 0;'>Response</h3><pre>".json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)."</pre></div>";
        }

        $body = (string)$response->getBody();

        try {
            $data = json_decode($body, true);
            if($data['success'] === true) {
                return $data['access_token'];
            } else {
                throw new \Exception('no success');
            }
        } catch(\Exception $e) {
            throw new \Exception('Could not generate token: ' . $body);
        }
    }

    private function createQuery() {
        $query = [];
        if($this->accessKey) {
            $query['access_key'] = $this->accessKey;
        }

        if($this->parameters != NULL) {
            $query = array_merge($query, $this->parameters);
        }

        return $query;
    }

    /**
     * Starts a MultiPart Upload Request to the API
     * @return []
     * @throws \Exception
     */
    public function startMultipartUpload() {
        $effectiveUrl = null;
        $response = $this->guzzleClient->request($this->httpType, $this->endPoint, [
            'timeout' => $this->guzzleRequestTimeout,
            'query' => $this->createQuery(),
            'multipart' => [
                [
                    'name'     => 'upload',
                    'contents' => fopen($this->payLoad, 'r')
                ]
            ],
            'on_stats' => function (TransferStats $stats) use (&$effectiveUrl) {
                $effectiveUrl = $stats->getEffectiveUri();
            }
        ]);

        if(Config::DEBUG_MODE) {
            echo "<div style='background-color: #eee; padding: 20px; border: solid 1px #333; margin: 10px 0; word-break:break-all;'>";
            echo "[" . $this->httpType . "] " . $effectiveUrl ."<hr /><br />";
            echo "<h3 style='margin-top: 0;'>Response</h3><pre>".json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)."</pre></div>";
        }

        $responseJson = json_decode($response->getBody());
        if(isset($responseJson->success) && $responseJson->success === false) {
            throw new \Exception('Webservice Error No ' . $responseJson->error->code . ' - Type: ' . $responseJson->error->type);
        }
        return $responseJson;

    }

    /**
     * Starts the configured API Request of the ApiRequest instance.
     * Returns different objects based on the request sent. Consult the Eversign API
     * documentation for more information.
     * @return stdClass
     * @throws \Exception
     */
    public function startRequest() {
        $effectiveUrl = null;
        if($this->payLoad && is_array($this->payLoad) && array_key_exists("sink", $this->payLoad)) {
            $response = $this->guzzleClient->request($this->httpType, $this->endPoint, [
                'timeout' => $this->guzzleRequestTimeout,
                'query' => $this->createQuery(),
                'sink' => $this->payLoad["sink"],
                'on_stats' => function (TransferStats $stats) use (&$effectiveUrl) {
                    $effectiveUrl = $stats->getEffectiveUri();
                }
            ]);
        }
        else if($this->payLoad && is_array($this->payLoad) && array_key_exists("stream", $this->payLoad)) {
            $response = $this->guzzleClient->request($this->httpType, $this->endPoint, [
                'timeout' => $this->guzzleRequestTimeout,
                'query' => $this->createQuery(),
                'stream' => $this->payLoad["stream"],
                'on_stats' => function (TransferStats $stats) use (&$effectiveUrl) {
                    $effectiveUrl = $stats->getEffectiveUri();
                }
            ]);
        }
        else {
            $requestOptions = [
                'timeout' => $this->guzzleRequestTimeout,
                'query' => $this->createQuery(),
                'on_stats' => function (TransferStats $stats) use (&$effectiveUrl) {
                    $effectiveUrl = $stats->getEffectiveUri();
                }
            ];

            try {
                $requestOptions['json'] = json_decode($this->payLoad);
            } catch (\Exception $e) {
                $requestOptions['body'] = $this->payLoad;
            }

            $response = $this->guzzleClient->request($this->httpType, $this->endPoint, $requestOptions);
        }

        if(Config::DEBUG_MODE && $this->endPoint != Config::DOCUMENT_FINAL_URL && $this->endPoint != Config::DOCUMENT_RAW_URL) {
            echo "<div style='background-color: #eee; padding: 20px; border: solid 1px #333; margin: 10px 0; word-break:break-all;'>";
            echo "[" . $this->httpType . "] " . $effectiveUrl ."<hr /><br />";

            if(!$this->payLoad || !is_array($this->payLoad) || !array_key_exists("sink", $this->payLoad)) {
               echo "<h3 style='margin-top: 0;'>Request</h3><pre>".json_encode(json_decode($this->payLoad), JSON_PRETTY_PRINT)."</pre>";
            }

            if($this->endPoint != Config::DOCUMENT_FINAL_URL && $this->endPoint != Config::DOCUMENT_RAW_URL) {
               echo "<h3 style='margin-top: 0;'>Response</h3><pre>".json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)."</pre></div>";
           }
       }

        $body = $response->getBody();

        if($this->payLoad && is_array($this->payLoad) &&  array_key_exists("sink", $this->payLoad)) {
           return file_exists($this->payLoad["sink"]);

        } else if($this->payLoad && is_array($this->payLoad) &&  array_key_exists("stream", $this->payLoad)) {
            return $body;
        } else {
            $responseJson = json_decode($body, true);
            if(json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Webservice Error No 999 - Type: parsing_exception: ' . $body);
            } else if($responseJson && array_key_exists('error', $responseJson)) {
                throw new \Exception('Webservice Error No ' . $responseJson['error']['code'] . ' - Type: ' . $responseJson['error']['type']);
            } else if($responseJson && $this->serializeClass) {
                $serializer = SerializerBuilder::create()->build();
                $serializeObject = $serializer->deserialize($body, $this->serializeClass, 'json');
                if(Config::DEBUG_MODE) {
                    highlight_string("<?php\n\$serializeObject =\n" . var_export($serializeObject, true) . ";\n?>");
                }

                return $serializeObject;
            } else {
                throw new \Exception('Webservice Error No 999 - Type: parsing_exception: ' . $body);
            }
        }
    }
}
