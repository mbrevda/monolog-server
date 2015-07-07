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
        return http_post_fields($this->$url, $record);
    }


}
