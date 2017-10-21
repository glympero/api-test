<?php

namespace Tests\AppBundle\Controller\Api;

use Tests\AppBundle\Controller\BaseController;

class WordsControllerTest extends BaseController
{
    public function testWords()
    {
        $client = $this->client();

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"Mir geht’s gut","output_language":"EN","max_characters":5}'
        );

        $this->assertJsonResponse($client->getResponse(), 200, true);

        $finishedData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('original_sentence', $finishedData);
        $this->assertEquals('Mir geht’s gut', $finishedData['original_sentence']);
        $this->assertArrayHasKey('final_string', $finishedData);
        $this->assertEquals('I\'m', $finishedData['final_string']);
        $this->assertArrayHasKey('final_string_translated', $finishedData);
        $this->assertEquals('Ich bin', $finishedData['final_string_translated']);
        $this->assertArrayHasKey('duration_ms', $finishedData);


    }

    public function testJsonFormatErrors()
    {
        $client = $this->client();

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"output_language":"EN","max_characters":5}'
        );

        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"Mir geht’s gut","max_characters":5}'
        );
        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"Mir geht’s gut",{"output_language":"EN"}'
        );
        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{}'
        );
        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"","output_language":"EN","max_characters":5}'

        );

        $this->assertJsonResponse($client->getResponse(), 422, true);
        $this->assertEquals('Unprocessable Entity', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":55,"output_language":"EN","max_characters":5}'

        );

        $this->assertJsonResponse($client->getResponse(), 422, true);
        $this->assertEquals('Unprocessable Entity', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"Mir geht’s gut","output_language":23,"max_characters":5}'
        );

        $this->assertJsonResponse($client->getResponse(), 422, true);
        $this->assertEquals('Unprocessable Entity', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"Mir geht’s gut","output_language":"EN","max_characters":-2}'
        );

        $this->assertJsonResponse($client->getResponse(), 422, true);
        $this->assertEquals('Unprocessable Entity', $this->getErrorMessage());

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"Mir geht’s gut","output_language":"EN","max_characters":0}'
        );

        $this->assertJsonResponse($client->getResponse(), 422, true);
        $this->assertEquals('Unprocessable Entity', $this->getErrorMessage());
    }

    public function testJsonParamsErrors()
    {
        $client = $this->client();

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"How are you?","output_language":"EN","max_characters":5}'
        );
        $this->assertJsonResponse($client->getResponse(), 400, true);

        $client->request(
            'POST',
            '/most_words',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"sentence":"How are you?","output_language":"GFGFGG","max_characters":5}'
        );
        $this->assertJsonResponse($client->getResponse(), 400, true);
    }
}