<?php

namespace AppBundle\Service;

/**
 * Class Logger
 * @package AppBundle\Service
 */
class Logger
{
    /**
     * @param array $inputs
     * @param array $data
     * @param string $dest
     */
    public function logAPI(array $inputs, array $data, string $dest)
    {
        $remote_addr = isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR']:'127.0.0.1';
        $log = [
            "IP:" => $remote_addr,
            "Date:" => date("F j, Y, g:i a"),
            "Input Parameters" => $inputs,
            "Response Parameters" => $data
        ];

        $copy_to_file = @file_put_contents('../api_logs/'.$dest.'_'.date("j.n.Y").'.txt', json_encode($log). PHP_EOL, FILE_APPEND);
        if($copy_to_file === true){
            file_put_contents('../api_logs/'.$dest.'_'.date("j.n.Y").'.txt', json_encode($log). PHP_EOL, FILE_APPEND);
        }
    }
}