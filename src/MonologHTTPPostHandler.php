<?php

namespace Mbrevda\MonologServer;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class MonologHTTPPostHandler extends AbstractProcessingHandler
{

    public function __construct($url, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->url = $url;
    }

    protected function write(array $record)
    {
        $ch = curl_init($this->url );

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $record['formatted']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


}
