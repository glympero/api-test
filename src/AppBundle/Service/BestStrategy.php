<?php

namespace AppBundle\Service;


class BestStrategy
{
    /**
     * @param array $choices
     * @return array
     */
    public function findMoves(array $choices) : array
    {

        $moves = array();
        for($i = 0; $i < sizeof($choices); $i++) {
            $moves[$i][$i]['first'] = $choices[$i];
            $moves[$i][$i]['second'] = 0;
            $moves[$i][$i]['pick'] = $i;
            $moves[$i][$i]['direction'] = null;
        }

        for($l = 2; $l <= sizeof($choices); $l++) {
            for($i = 0; $i <= sizeof($choices) - $l; $i++) {
                $j = ($i + $l - 1);
                if($choices[$i] + $moves[$i+1][$j]['second'] > $moves[$i][$j-1]['second'] + $choices[$j]){
                    $moves[$i][$j]['first'] = $choices[$i] + $moves[$i+1][$j]['second'];
                    $moves[$i][$j]['second'] = $moves[$i+1][$j]['first'];
                    $moves[$i][$j]['pick'] = $i;
                    $moves[$i][$j]['direction'] = 'left';
                } else {
                    $moves[$i][$j]['first'] = $choices[$j] + $moves[$i][$j-1]['second'];
                    $moves[$i][$j]['second'] = $moves[$i][$j-1]['first'];
                    $moves[$i][$j]['pick'] =$j;
                    $moves[$i][$j]['direction'] = 'right';
                }
            }
        }
        return $moves;
    }

    /**
     * @param array $choices
     * @param array $moves
     * @return array
     */
    public function serializeSequence(array $choices, array $moves) : array
    {
        $i = 0;
        $j = sizeof($choices) -1;
        $step = null;
        $direction_choice = array();
        $values = array();
        $array_index = array();
        $total_score_p1 = 0;
        $total_score_p2 = 0;

        for($k = 0; $k < sizeof($choices); $k++) {
            $step = $moves[$i][$j]['pick'];
            $direction = $moves[$i][$j]['direction']!== null ? $moves[$i][$j]['direction'] : "Remaining Item";
            if($k % 2 === 0){
                $direction_choice[] = "P1: ".$direction;
                $values[] = "P1: ".$choices[$step];
                $total_score_p1 += $choices[$step];
                $array_index[]  = "P1: ".$step;
            } else {
                $direction_choice[] = "P1: ".$direction;
                $values[] = "P2: ".$choices[$step];
                $total_score_p2 += $choices[$step];
                $array_index[]  = "P2: ".$step;
            }
            if($step <= $i){
                $i = ($i + 1);
            }else {
                $j = ($j - 1);
            }
        }
        return $this->serializeResponse($direction_choice, $values, $array_index, $total_score_p1, $total_score_p2);
    }

    /**
     * @param array $direction_choice
     * @param array $values
     * @param array $array_index
     * @param float $total_score_p1
     * @param float $total_score_p2
     * @return array
     */
    private function serializeResponse(array $direction_choice, array $values, array $array_index, float $total_score_p1, float $total_score_p2) : array
    {
        return $data = [
            'strategy' => [
                'direction_choice' => $direction_choice,
                'value' => $values,
                'array_index' => $array_index,
                'p1_score' => $total_score_p1,
                'p2_score' => $total_score_p2
            ],
            'duration_ms' => 0
        ];
    }
}