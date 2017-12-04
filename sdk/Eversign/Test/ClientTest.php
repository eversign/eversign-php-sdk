<?php

namespace Eversign\Test;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

function getFilesIntoArray($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    $responses = [];
    foreach ($files as $file) {
        $responseName = substr($file, 0, strpos($file, '.'));
        $responseBody = file_get_contents($dir . '/' . $file);
        $responses[$responseName] = json_decode($responseBody, true);
    }

    return $responses;
}


class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        $mocks = getFilesIntoArray(__DIR__ . '/mocks');

        return [
            'business' => [
                 'mocks' => [
                     $mocks['business'],
                 ],
                 'codeFilename' => 'business',
            ],
            'create_document_from_template' => [
                'mocks' => [
                    $mocks['business'],
                    $mocks['create_document_from_template'],
                ],
                'codeFilename' => 'create_document_from_template',
            ],
            'create_document' => [
                'mocks' => [
                    $mocks['business'],
                    $mocks['file'],
                    $mocks['create_document'],
                ],
                'codeFilename' => 'create_document',
            ],
            'oauth' => [
                'mocks' => [
                    $mocks['oauth'],
                    $mocks['business_with_oauth'],
                    $mocks['get_documents_with_oauth'],
                ],
                'codeFilename' => 'oauth',
            ],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCall($mocks, $codeFilename)
    {
        $reps = [];
        foreach ($mocks as $mock) {
            $reps[] = new \GuzzleHttp\Psr7\Response($mock['response']['statusCode'], [], json_encode($mock['response']['body']));
        }

        $container = [];
        $history = \GuzzleHttp\Middleware::history($container);
        $guzzleMock = new \GuzzleHttp\Handler\MockHandler($reps);
        $stack = \GuzzleHttp\HandlerStack::create($guzzleMock);
        $stack->push($history);

        require(__DIR__ . '/mockCode/' . $codeFilename . '.php');

        $i = 0;
        foreach ($mocks as $mock) {
            $currentRequest = $container[$i];

            // var_dump($mock['request']);
            // var_dump($mock['response']);
            // exit;

            $query = $currentRequest['request']->getUri()->getQuery();
            parse_str($query, $get_array);
            // $this->assertEquals($get_array['access_key'], 'test_access_key');

            $isUrl = ((string)($currentRequest['request']->getUri()));
            $shouldUrl = $mock['request']['url'];
            // echo $isUrl . ' == ' . $shouldUrl . PHP_EOL;
            $this->assertEquals($isUrl, $shouldUrl);

            if (array_key_exists('body', $mock['request'])) {
                $requestBody = (string)$currentRequest['request']->getBody();
                $requestData = json_decode($requestBody, true);
                $this->assertSame($requestData, $mock['request']['body']);
            }

            if (array_key_exists('headers', $mock['request'])) {
                $requestHeaders = $currentRequest['request']->getHeaders();
                $this->assertSame($requestHeaders, $mock['request']['headers']);
            }

            $i++;
        }
    }
}
