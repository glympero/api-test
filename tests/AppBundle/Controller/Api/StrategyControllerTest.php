<?php

namespace Tests\AppBundle\Controller\Api;


use Tests\AppBundle\Controller\BaseController;

class StrategyControllerTest extends BaseController
{
    public function testBestStrategy()
    {
        $client = $this->client();

        $client->request(
            'POST',
            '/best_strategy',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"game_state":[3, 1, 2, 2, 9, 1]}'
        );

        $this->assertJsonResponse($client->getResponse(), 200, true);

        $finishedData = json_decode($client->getResponse()->getContent(), true);

        $strategy = [
            'direction_choice' => [
                "P1: left",
                "P1: right",
                "P1: right",
                "P1: right",
                "P1: right",
                "P1: Remaining Item"
            ],
            'value' => [
                "P1: 3",
                "P2: 1",
                "P1: 9",
                "P2: 2",
                "P1: 2",
                "P2: 1"
            ],
            'array_index' => [
                "P1: 0",
                "P2: 5",
                "P1: 4",
                "P2: 3",
                "P1: 2",
                "P2: 1"
            ],
            "p1_score" => 14,
            "p2_score" => 4
        ];

        $this->assertArrayHasKey('strategy', $finishedData);
        $this->assertEquals($strategy, $finishedData['strategy']);
        $this->assertArrayHasKey('direction_choice', $finishedData['strategy']);
        $this->assertEquals($strategy['direction_choice'], $finishedData['strategy']['direction_choice']);
        $this->assertArrayHasKey('value', $finishedData['strategy']);
        $this->assertEquals($strategy['value'], $finishedData['strategy']['value']);
        $this->assertArrayHasKey('array_index', $finishedData['strategy']);
        $this->assertEquals($strategy['array_index'], $finishedData['strategy']['array_index']);
        $this->assertArrayHasKey('duration_ms', $finishedData);
    }
    public function testJsonFormatErrors()
    {
        $client = $this->client();

        $client->request(
            'POST',
            '/best_strategy',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"game_stake":"Wrong"}'
        );

        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/best_strategy',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"game_state":"Wrong"}'
        );

        $this->assertJsonResponse($client->getResponse(), 422, true);
        $this->assertEquals('Unprocessable Entity', $this->getErrorMessage());
    }

    public function testJsonParamsErrors()
    {
        $client = $this->client();
        $client->request(
            'POST',
            '/best_strategy',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"game_stake":[]}'
        );

        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/best_strategy',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"game_stake":["Eggs", "Milk", "Sugar", "Wine"]}'
        );

        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/best_strategy',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"game_stake":[1, 2, 9, "Wine"]}'
        );

        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());

        $client->request(
            'POST',
            '/best_strategy',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"game_stake":[1, 2, 9}'
        );

        $this->assertJsonResponse($client->getResponse(), 400, true);
        $this->assertEquals('Invalid JSON body', $this->getErrorMessage());
    }
}