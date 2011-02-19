<?php
/**
 * Entry point for BRS system
 *
 * @author meza <meza@meza.hu>
 */

require_once('Brs.php');

$application = new Brs();
$success     = $application->run(
    new Handlr(),
    new HttpClientRequestParser(),
    new Restlr()
);

exit((int)!$success);


/**
 * Autoloader method
 *
 * @staticvar array $classes   Class cache
 * @param string    $className Class to load
 *
 * @return bool
 *
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

        $pos = strrpos($className, '\\'); //namespace hack
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

?>