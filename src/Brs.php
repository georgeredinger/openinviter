<?php
/**
 * Brs application
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

/**
 * Main application class
 *
 * @category Class
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
class Brs
{

    /**
     * @var BrsConfig configuration
     */
    private $_config;

    /**
     * @var OpenInviterInterface the OI service
     */
    private $_service;


    /**
     * Create the object
     *
     * @param BrsConfig            $config  Configuration
     * @param OpenInviterInterface $service The openinviter to use
     *
     * @return Brs
     */
    public function __construct(
        BrsConfig $config,
        OpenInviterInterface $service
    ) {
        $this->_config  = $config;
        $this->_service = $service;

    }//end __construct()


    /**
     * Run the application
     *
     * @param Handlr                  $handlr Handlr instance
     * @param HttpClientRequestParser $http   Http instance
     * @param Restlr                  $restlr Restlr instance
     *
     * @return bool
     */
    public function run(
        Handlr $handlr,
        HttpClientRequestParser $http,
        Restlr $restlr
    ) {
        $request = $http->parse();
        if (false === file_exists(dirname(__FILE__).'/handlers')) {
            $restlr->notFound();
            return false;
        }

        $xx       = $handlr->collectHandlers(dirname(__FILE__).'/handlers');
        $routes   = $handlr->getStaticRouteTable($xx)->uris;
        $resource = $this->_getResource($routes, $request);

        if (false === $resource) {
            $restlr->notFound();
            return false;
        }

        $restSuccess = $restlr->run($resource, $request);

        return $restSuccess;

    }//end run()


    /**
     * Load a resource.
     *
     * @param Route[]           $routeTable The routes
     * @param HttpClientRequest $request    The request
     *
     * @return Resource
     */
    private function _getResource(array $routeTable, HttpClientRequest $request)
    {
        if (true === isset($routeTable[$request->uri])) {
            $result = new $routeTable[$request->uri](
                $this->_config,
                $request,
                $this->_service
            );
            return $result;
        }

        foreach ($routeTable as $uriPattern => $resourceName) {
            if (0 < preg_match('`^'.$uriPattern.'$`', $request->uri, $match)) {
                $keys   = array_filter(array_keys($match), 'is_string');
                $match  = array_intersect_key($match, array_flip($keys));
                $result = new $resourceName(
                    $this->_config,
                    $request,
                    $this->_service,
                    $match
                );
                return $result;
            }
        }

        return false;

    }//end _getResource()


}//end class

?>