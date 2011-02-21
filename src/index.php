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

$openInviterConfig = array(
                      'username'        => 'myixora',
                      'private_key'     => 'b2426a7c739dafa725d7fc50f9b164c3',
                      'cookie_path'     => '/tmp',
                      'message_body'    => 'You are invited to http://myixora.com',
                      'message_subject' => ' is inviting you to http://myixora.com',
                      'stats'           => false,
                     );

$openInviter   = new BrsOpeninviter($openInviterConfig);
$handlr        = new Handlr();
$requestParser = new HttpClientRequestParser();
$restlr        = new Restlr();
$appConfig     = new BrsConfig();
$application   = new Brs($appConfig, $openInviter);
$success       = $application->run(
    $handlr,
    $requestParser,
    $restlr
);


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