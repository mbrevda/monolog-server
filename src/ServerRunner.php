<?php

namespace Mbrevda\LogServer;

use \Mbrevda\LogServer\Server;
use \Mbrevda\LogServer\RequestHandler;

class ServerRunner
{
    protected $server;

    public function __invoke()
    {
        $rHandler = new RequestHandler;

        $server = new Server($rHandler);
        $server->run_forever();
    }
}
