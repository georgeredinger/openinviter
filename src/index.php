<?php
/**
 * Entry point for BRS system
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Brs
 * @author   meza <meza@meza.hu>
 * @license  GPL3.0
 *                    GNU GENERAL PUBLIC LICENSE
 *                       Version 3, 29 June 2007
 *
 * Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 * @link     http://www.meza.hu
 */

require_once 'Brs.php';

$appConfig     = new BrsConfig();
$openInviter   = createService($appConfig->getOpenInviterConfigArray());
$handlr        = new Handlr();
$requestParser = new HttpClientRequestParser();
$restlr        = new Restlr();
$application   = new Brs($appConfig, $openInviter);
$success       = $application->run(
    $handlr,
    $requestParser,
    $restlr
);


/**
 * Create the service
 *
 * @param array $openInviterConfig The OI config
 *
 * @return OpenInviterInterface
 */
function createService($openInviterConfig)
{
    $service = new BrsStubOpeninviter($openInviterConfig);
//    $service = new BrsOpeninviter($openInviterConfig);
    return $service;

}//end createService()


/**
 * Autoloader method
 *
 * @param string $className Class to load
 *
 * @staticvar array $classes   Class cache
 *
 * @return bool
 */
function __autoload($className)
{
    static $classes;
    if (true === empty($classes)) {
        $classes = include getcwd().'/classes.php';
    }

    if (false === array_key_exists($className, $classes)) {
        return false;
    }

    // Namespace hack.
    $pos = strrpos($className, '\\');

    $trimmedClassName = $className;
    if (false !== $pos) {
        $trimmedClassName = substr($trimmedClassName, ($pos + 1));
    }

    $file = getcwd().$classes[$className].$trimmedClassName.'.php';
    if (false === file_exists($file)) {
        $file = getcwd().$classes[$className].$trimmedClassName.'.php';
    }

    return include_once $file;

}//end __autoload()


exit((int) !$success);
?>