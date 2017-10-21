<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

class BaseController extends WebTestCase
{
    private $client;

    /**
     * @return Client
     */
    protected function client()
    {
        if ($this->client === null){
            $this->client =static::createClient();
        }
        return $this->client;
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    protected function getErrorMessage()
    {
        $finishedData = json_decode($this->client()->getResponse()->getContent(), true);
        return $finishedData['error']['exception'][0]['message'];
    }

}