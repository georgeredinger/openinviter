<?php
/**
 * HttpClientRequestParser.php
 *
 * Holds the HttpClientRequestParser class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Http
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
 * The HttpClientRequestParser class is responsible for parsing incoming
 * requests
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Http
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
class HttpClientRequestParser
{


    /**
     * Parses the request
     *
     * @return HttpClientRequest
     */
    public function parse($scope=null) {
        if (null === $scope) {
            $scope = $GLOBALS;
        }

        $uri = $scope['_SERVER']['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        $req          = new HttpClientRequest();
        $req->method  = $scope['_SERVER']['REQUEST_METHOD'];
        $req->uri     = $path;
        $req->headers = new HttpHeaderComposite();
        
        foreach($this->getHeaders() as $name=>$value) {
            $header = new HttpHeader($name, $value);
            $req->headers->addItem($header);
        }
        $req->data = $this->getData($req->method, $scope);
        return $req;

    }//end parse()


    private function getData($method, $scope)
    {
        switch(strtolower($method)) {
            case 'delete':  return $this->getDeleteData($scope);
            case 'get':     return $this->getGetData($scope);
            case 'head':    return $this->getHeadData($scope);
            case 'options': return $this->getOptionsData($scope);
            case 'post':    return $this->getPostData($scope);
            case 'put':     return $this->getPutData($scope);
        }

    }

    private function getGetData(array $scope)
    {
        return $scope['_GET'];
    }

    private function getPostData(array $scope)
    {
        return $scope['_POST'];
    }

    private function getPutData()
    {
        $data = file_get_contents('php://input');
        parse_str($data, $result);
        return $result;

    }

    private function getDeleteData(array $scope)
    {
        return $this->getGetData($scope);

    }

    private function getHeadData(array $scope)
    {
        return array();

    }

    private function getOptionsData(array $scope)
    {
        return $this->getPutData($scope);

    }

    private function getHeaders()
    {
        return getallheaders();
    }


}//end class

?>