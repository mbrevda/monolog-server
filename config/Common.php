<?php

namespace Mbrevda\MonologServer\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        date_default_timezone_set('UTC');
        ini_set('display_errors', false);
        error_reporting(-1);
    }

    public function modify(Container $di)
    {
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject(
            'logger',
            $di->newInstance('Mbrevda\MonologServer\ServerRunner')
        );

        $help_service = $di->get('aura/cli-kernel:help_service');
        $help = $di->newInstance('Aura\Cli\Help');
        $help_service->set('logger', function () use ($help) {
            $help->setSummary('Runs the Streaming Logger Server');
            $help->setOptions([
                'addr:' => 'The address that the server will listen on'
            ]);
            return $help;

        });
    }
}
