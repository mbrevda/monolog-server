<?php

namespace Mbrevda\MonologServer;

use \Mbrevda\MonologServer\Server;
use \Mbrevda\MonologServer\RequestHandler;

class ServerRunner
{
    protected $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function __invoke()
    {
        $this->server->run_forever();
    }
}
