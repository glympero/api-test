<?php

namespace AppBundle\Controller\Api\v1;


use AppBundle\Controller\BaseController;
use AppBundle\Service\BestStrategy;
use AppBundle\Service\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class StrategyController extends BaseController
{
    private $choices;
    /**
     * @Route("/best_strategy", name="best_strategy")
     * @Method("POST")
     */
    public function strategyAction(Request $request)
    {
        $newLogger = new Logger();
        $start = microtime(true);
        $this->getJsonInput($request->getContent());

        $bestStrategy = new BestStrategy();

        $moves = $bestStrategy->findMoves($this->choices);

        $data = $bestStrategy->serializeSequence($this->choices, $moves);
        $time_elapsed_secs = microtime(true) - $start;
        $data['duration_ms'] = $time_elapsed_secs;
        $newLogger->logAPI($this->choices, $data, 'best_strategy');

        $response = $this->createApiResponse($data, 200);

        return $response;
    }

    /**
     * @param $game_state
     */
    private function validateInputs($game_state)
    {
        if (!is_array($game_state)) {
            $this->throw422();
        } else {
            $this->choices = $game_state;
            if (count($this->choices) < 2 || !$this->arrayHasOnlyInts($game_state) || count($this->choices) % 2 !== 0) {
                $this->throw400();
            }
        }
    }

    /**
     * @param string $body
     */
    private function getJsonInput(string $body)
    {
        $input = json_decode($body, true);
        if(!isset($input['game_state'])) {
            $this->throw400();
        }
        $this->validateInputs($input['game_state']);
    }

    /**
     * @param array $array
     * @return bool
     */
    private function arrayHasOnlyInts(array $array) : bool {
        foreach($array as $element) {
            if(is_numeric($element)) {
                return true;
            }
            return false;
        }
    }
}