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

use Eversign\Config;
use GuzzleHttp\Client as GuzzleClient;
use JMS\Serializer\SerializerBuilder;


class ApiRequest {

    private $guzzleClient;

    private $accessKey;

    private $httpType;

    private $endPoint;

    private $serializeClass;

    private $parameters;

    private $payLoad;

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
    public function __construct($httpType = "GET", $accessKey, $endPoint, $serializeClass = "", $parameters = NULL, $payLoad = NULL) {
        $this->httpType = $httpType;
        $this->accessKey = $accessKey;
        $this->guzzleClient = new GuzzleClient(['base_uri' => Config::API_URL, 'headers' => ['User-Agent' => 'Eversign_PHP_SDK']]);
        $this->endPoint = $endPoint;
        $this->serializeClass = $serializeClass;
        $this->parameters = $parameters;
        $this->payLoad = $payLoad;
    }

    private function createQuery() {
        $query = [
            'access_key' => $this->accessKey
        ];

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
        if(Config::DEBUG_MODE) {
           echo "<hr>" . Config::API_URL . $this->endPoint ."<br />";
        }


        $response = $this->guzzleClient->request($this->httpType, $this->endPoint, [
            'query' => $this->createQuery(),
            'multipart' => [
                [
                    'name'     => 'upload',
                    'contents' => fopen($this->payLoad, 'r')
                ]
            ]
        ]);


        if(Config::SHOW_API_RESPONSE) {
           echo "<div style='background-color: #eee; padding: 20px; border: solid 1px #333; margin: 10px 0; word-break:break-all;'><h3 style='margin-top: 0;'>Response</h3>".$response->getBody()."</div>";

        }


        $responseJson = json_decode($response->getBody());
            if(isset($responseJson->success)) {
                throw new \Exception('Webservice Error No ' . $responseJson->code . ' - Type: ' . $responseJson->type);
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
        if(Config::DEBUG_MODE) {
           echo "<hr>" . Config::API_URL . $this->endPoint ."<br />";
        }

        if($this->payLoad && is_array($this->payLoad) && array_key_exists("sink", $this->payLoad)) {
            $response = $this->guzzleClient->request($this->httpType, $this->endPoint, [
                'query' => $this->createQuery(),
                'sink' => $this->payLoad["sink"]
            ]);
        }
        else {
            $response = $this->guzzleClient->request($this->httpType, $this->endPoint, [
                'query' => $this->createQuery(),
                'body' => $this->payLoad
            ]);
        }

        if(Config::SHOW_API_RESPONSE && $this->endPoint != Config::DOCUMENT_FINAL_URL && $this->endPoint != Config::DOCUMENT_RAW_URL) {
           echo "<div style='background-color: #eee; padding: 20px; border: solid 1px #333; margin: 10px 0; word-break:break-all;'><h3 style='margin-top: 0;'>Response</h3>".$response->getBody()."</div>";
        }

        $body = $response->getBody();

        if($this->payLoad && is_array($this->payLoad) &&  array_key_exists("sink", $this->payLoad)) {
            return file_exists($this->payLoad["sink"]);
        } else {
            $responseJson = json_decode($body, true);
            if($responseJson && array_key_exists('error', $responseJson)) {
                throw new \Exception('Webservice Error No ' . $responseJson['error']['code'] . ' - Type: ' . $responseJson['error']['type']);
            } else if($responseJson && $this->serializeClass) {
                $serializer = SerializerBuilder::create()->build();
                $serializeObject = $serializer->deserialize($body, $this->serializeClass, 'json');
                if(Config::DEBUG_MODE) {
                    highlight_string("<?php\n\$serializeObject =\n" . var_export($serializeObject, true) . ";\n?>");
                }

                return $serializeObject;
            } else {
                throw new \Exception('Webservice Error No 999 - Type: parsing_exception');
            }
        }


    }



}
