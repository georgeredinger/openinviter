<?php
/**
 * RequestParsing.php
 *
 * Holds the RequestParsing class
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


require_once dirname(__FILE__).'/../HttpClientRequest.php';
require_once dirname(__FILE__).'/../CompositeItem.php';
require_once dirname(__FILE__).'/../Composite.php';
require_once dirname(__FILE__).'/../HttpHeaderComposite.php';
require_once dirname(__FILE__).'/../HttpHeader.php';
require_once dirname(__FILE__).'/../HttpClientRequestParser.php';
$parser  = new HttpClientRequestParser();
$request = $parser->parse();
if ($request->method === 'HEAD') {
    exit();
}
echo serialize($request);

?>