<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Cli_Project
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
$path = dirname(__DIR__);
require "{$path}/vendor/autoload.php";

/*josegonzalez\Dotenv\Loader::load([
    'raiseExceptions' => false,
    'filepath'        => $path . '/.env',
    'putenv'          => false,
    'skipExisting'    => ['putenv']
]);*/

$kernel = (new \Aura\Project_Kernel\Factory)->newKernel(
    $path,
    'Aura\Cli_Kernel\CliKernel'
);
$status = $kernel();
exit($status);
