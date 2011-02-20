<?php
/**
 * Handlr.php
 *
 * Holds the Handlr class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Handlr
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

/**
 * The Handlr class is responsible for routing
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Handlr
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
class Handlr
{

    const DIGIT = '\d+';
    const ANY   = '.*';
    const CHARS = '[a-zA-Z0-9_]+';


    /**
     * Collects handler classes for incoming URI-s
     *
     * @param string $baseDir The dir to start looking handlers for
     *
     * @return array of handlers, where the key is the class, tha value
     *               is the handler uri regex
     */
    public function collectHandlers($baseDir='.')
    {
        $files   = $this->getFileset($baseDir);
        $classes = array();
        $result  = array();

        foreach ($files as $file) {
            $definedClasses = $this->getDefinedClasses($file);
            $classes        = array_merge($classes, $definedClasses);
            include_once $file;
        }

        foreach ($classes as $key => $class) {
            $result[$class] = $this->getHandlerAnnotationsFromClass($class);
        }

        ksort($result);
        return $this->_getRouteCollection($result);

    }//end collectHandlers()


    /**
     * Get a collection of routes out of the class array
     *
     * @param array $routes The parsed routes
     *
     * @return RouteCollection
     */
    private function _getRouteCollection(array $routes)
    {
        $router = new RouteCollection();

        foreach ($routes as $className => $handle) {
            foreach ($handle as $pattern => $name) {
                if (true === empty($name)) {
                    $name = $className;
                }

                $route = new Route($name, $pattern);
                $route->addHandler($className);
                $router->addItem($route);
            }
        }

        return $router;

    }//end _getRouteCollection()


    /**
     * Transform the uri to regexp
     *
     * @param string $uri The canonical uri
     * 
     * @return string
     */
    public function transformUri($uri)
    {
        $pattern  = '/\{(?P<variable>[^:]*):(?P<value>[^\}]*)\}/';
        $searches = array(
                     'digit',
                     'chars',
                     'any',
                    );
        $replaces = array(
                     self::DIGIT,
                     self::CHARS,
                     self::ANY,
                    );

        $uri = str_replace('*', 'any', $uri);
        $uri = preg_replace('/\{([^:]*):([^\}]*)\}/', '(?P<\\1>\\2)', $uri);
        $uri = str_replace($searches, $replaces, $uri);

        return $uri;

    }//end transformUri()


    /**
     * Returns handler uris from a class
     *
     * @param string $classname To check
     *
     * @return array of uris
     */
    public function getHandlerAnnotationsFromClass($classname)
    {
        $rc         = new ReflectionClass($classname);
        $docComment = $rc->getDocComment();
        $pattern    = '/@handles\s+(?P<handler>.*)/m';
        preg_match_all($pattern, $docComment, $matchesarray);
        $result = array();

        foreach ($matchesarray['handler'] as $handler) {
            $handler = preg_replace('/\s{2,}/', ' ', $handler);
            $parts   = explode(' ', $handler);
            if (true === isset($parts[1])) {
                $result[$this->transformUri($parts[0])] = $parts[1];
            } else {
                $result[$this->transformUri($parts[0])] = '';
            }
        }

        return $result;

    }//end getHandlerAnnotationsFromClass()


    /**
     * Collect the defined classes in the file
     *
     * @param string $file The file to parse
     *
     * @return array of classnames
     */
    public function getDefinedClasses($file)
    {
        return $this->_getDefinedClasses($file);

    }//end getDefinedClasses()


    /**
     * Returns the classes defined in a file
     *
     * @param string $file The file to examine
     *
     * @return array of classnames
     */
    private function _getDefinedClasses($file)
    {
        $pattern  = '/^class\s+(?P<classname>[A-Za-z0-9_]+)/m';
        $contents = file_get_contents($file);
        $matches  = preg_match_all(
            $pattern, $contents, $matchesarray,
            PREG_PATTERN_ORDER
        );

        $result = array();
        foreach ($matchesarray['classname'] as $match) {
            $result[] = $match;
        }

        return $result;

    }//end _getDefinedClasses()


    /**
     * Returns the list of files in a directory
     *
     * @param string $baseDir The path of the directory to list
     *
     * @return array of filenames
     */
    public function getFileset($baseDir='.')
    {
        $baseDir = rtrim($baseDir, '/\\');
        $dir     = opendir($baseDir);

        $result = array();

        while ($file = readdir($dir)) {
            if (false === $this->_isSpecial($file)) {
                $file = $baseDir.DIRECTORY_SEPARATOR.$file;
                if (true === is_dir($file)) {
                    $files  = $this->getFileset($file);
                    $result = array_merge($result, $files);
                }

                if (true === is_file($file)) {
                    if (true === $this->_isPhp($file)) {
                        $result[] = $file;
                    }
                }
            }
        }

        return $result;

    }//end getFileset()


    /**
     * Determine if a file is php or not
     *
     * @param string $filename The file to check
     *
     * @return bool
     */
    private function _isPhp($filename)
    {
        $info = pathinfo($filename);
        if ('php' === strtolower($info['extension'])) {
            return true;
        }

        return false;

    }//end _isPhp()


    /**
     * Checks if the filename is special or not
     *
     * @param string $filename The filename to parse
     *
     * @return bool
     */
    private function _isSpecial($filename)
    {
        $specials = array(
                     '.',
                     '..',
                    );
        return in_array($filename, $specials);

    }//end _isSpecial()


    /**
     * Fetches a cachable representation of the route table
     *
     * @param RouteCollection $router The router to use
     *
     * @return StaticRouteTable
     */
    public function getStaticRouteTable(RouteCollection $router)
    {
        $nameFormatter = new IndexByNameRouteFormatter();
        $uriFormatter  = new IndexByUriRouteFormatter();
        $result        = new StaticRouteTable();
        $result->names = $nameFormatter->format($router);
        $result->uris  = $uriFormatter->format($router);

        return $result;

    }//end getStaticRouteTable()


}//end class

?>