<?php

namespace Mbrevda\MonologServer;

use \Mbrevda\MonologServer\Server;
use \Mbrevda\MonologServer\RequestHandler;
use Aura\Cli\Context;
use Aura\Cli\Stdio;

class RequestHandler
{
    public function __construct(
        Context $context,
        Stdio $stdio
    ) {
        $this->context  = $context;
        $this->stdio    = $stdio;
    }

    public function __invoke($request)
    {
        $body = stream_get_contents($request->content_stream);
        $msg  = $this->normalizeMessage($body);
        $msg  = $this->formatMessage($msg);
        $this->stdio->outln($msg);
    }

    /**
     * Normalize messages recevied as json, including error checking
     *
     * @param string $msg recevied message
     *
     * @return object the messaged, parsed
     */
    private function normalizeMessage($message)
    {
        $message = trim($message);
        $msg = json_decode($message);
        $err = json_last_error_msg();

        // return an error on error or blank message
        if (!$message || $err != 'No error') {
            if (!$message) {
                $err = '[blank message recevied]';
            }

            $msg                  = new \stdClass;
            $msg->datetime        = new \stdClass;
            $msg->datetime->date  = date('Y-m-d H:i:s');
            $msg->channel         = '';
            $msg->level_name      = '';
            $msg->location        = __FILE__ . ':' . __LINE__;
            $msg->message         = $err . ': "' . $message . '"';
            $msg->extra           = new \stdClass;
            $msg->extra->class    = null;
            $msg->extra->function = null;

            return $msg;
        }

        if (isset($msg->context)
            && is_object($msg->context)
            && isset($msg->context->file)
        ) {
            $msg->location = $msg->context->file
                . ':'
                . $msg->context->line;
        } else {
            $msg->location = '';
        }

        return $msg;
    }

    /**
     * Outputs a message
     *
     * @param string $msg the mreceived message
     */
    private function formatMessage($msg)
    {
        //print_r($msg);
        $out = PHP_EOL . PHP_EOL
            . '<<bold>>['
            . $msg->datetime->date
            . '] '
            . str_pad($msg->level_name, 8)
            . ' '
            . ($msg->channel ? $msg->channel . ' ' : '')
            . ($msg->extra->class ? $msg->extra->class . '::' : '')
            . $msg->extra->function
            . PHP_EOL
            . (is_string($msg->location) ? $msg->location : '');

        switch ($msg->level_name) {
            default:
            case 'DEBUG':
                break;
            case 'INFO':
                $out = '<<white>>' . $out;
                break;
            case 'NOTICE':
                $out = '<<green>>' . $out;
                break;
            case 'WARNING':
                $out = '<<yellow>>' . $out;
                break;
            case 'ERROR':
                $out = '<<red>>' . $out;
                break;
            case 'CRITICAL':
                $out = '<<bluebg>>' . $out;
                break;
            case 'ALERT':
                $out = '<<yellowbg>>' . $out;
                break;
            case 'EMERGENCY':
                $out = '<<redbg>>' . $out;
                break;
        }

        $out .= '<<reset>>' . PHP_EOL . PHP_EOL;

        // monolog insists on hardcoding the php error name in the error text
        // clean it out here
        if (is_string($msg->message)) {
            $out .= preg_replace('/^(E_.*?: )/', '', $msg->message);
        }

        return $out;
    }
}
