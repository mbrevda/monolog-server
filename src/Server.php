<?php

namespace Mbrevda\MonologServer;

use \HTTPServer;
use \Mbrevda\MonologServer\RequestHandler;

class Server extends HTTPServer
{

    public function __construct(RequestHandler $requestHandler)
    {
        parent::__construct(['port' => 33000]);
        $this->requestHandler = $requestHandler;
    }

    public function route_request($request)
    {
        $this->requestHandler->__invoke($request);

        if (!$this->isPost($request)) {
            return $this->notFound();
        }


        return $this->ok();
    }

    public function request_done($request)
    {

    }

    public function listening()
    {

    }

    private function isPost($request)
    {
        return $request->method == 'POST';
    }

    private function ok()
    {
        return $this->text_response(200, 'OK');
    }

    private function notFound()
    {
        return $this->text_response(404, 'Not Found');
    }
}
