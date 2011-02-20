<?php
/**
 * CommonResourceEndToEndTest
 *
 * Holds the CommonResourceEndToEndTest class
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
 * Copyright (C) 2007-2010 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 * @link     http://www.meza.hu
 */

/**
 * The CommonResourceEndToEndTest class is responsible for ...
 *
 * PHP Version: PHP 5
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
abstract class CommonResourceEndToEndTest extends BrsEndToEndTest
{


    /**
     * Verifies that a contact that doesn't exist gives
     * 404 with HEAD, GET, DELETE
     *
     * @test
     *
     * @return void
     */
    public function verifyThatUnexistingResource404()
    {
        $randomName = md5(date('U'));
        $resource   = $this->hostname.$this->url.'/'.$randomName;

        $notFoundConstraint = new NotFoundConstraint();
        $this->assertThat($this->head($resource), $notFoundConstraint);
        $this->assertThat($this->get($resource), $notFoundConstraint);
        $this->assertThat($this->delete($resource), $notFoundConstraint);

    }//end verifyThatUnexistingResource404()


    /**
     * Verifies that OPTION gives OPTIONS, HEAD and PUT to an unexisting
     * resource
     *
     * @test
     *
     * @return void
     */
    public function verifyThatOptionsGivesTheCorrectValuesToUnexistingResoure()
    {
        $randomName = md5(date('U'));
        $resource   = $this->hostname.$this->url.'/'.$randomName;

        $expectedMethods = array(
                            'options',
                            'put',
                            'head',
                           );
        $header = new AllowHeaderConstraint($expectedMethods);
        $this->assertThat($this->options($resource), $header);

    }//end verifyThatOptionsGivesTheCorrectValuesToUnexistingResoure()


    /**
     * Verifies that a resource that exists gives 200 with HEAD, GET, OPTIONS
     *
     * @test
     *
     * @return string resource
     */
    public function verifyThatExistingResourceIs200()
    {
        $id       = $this->createResource();
        $resource = $this->hostname.$this->url.'/'.urlencode($id);

        $okConstraint = new HttpOKConstraint();
        $this->assertThat($this->head($resource), $okConstraint);
        $this->assertThat($this->get($resource), $okConstraint);
        $this->assertThat($this->options($resource), $okConstraint);
        $this->assertThat($this->delete($resource), $okConstraint);

        return $resource;

    }//end verifyThatExistingResourceIs200()


    /**
     * Create a given resource.
     *
     * @return void
     */
    abstract public function createResource();


    /**
     * Verifies that OPTION gives OPTIONS, HEAD and PUT to an existing
     * resource
     *
     * @test
     *
     * @return void
     */
    public function verifyThatOptionsGivesTheCorrectValuesToExistingResource()
    {
        $id       = $this->createResource();
        $resource = $this->hostname.$this->url.'/'.urlencode($id);

        $expectedMethods = array(
                            'options',
                            'put',
                            'post',
                            'head',
                            'get',
                            'delete',
                           );
        $header = new AllowHeaderConstraint($expectedMethods);
        $this->assertThat($this->options($resource), $header);
        $this->delete($resource);

    }//end verifyThatOptionsGivesTheCorrectValuesToExistingResource()


    /**
     * Verify delete for existing resource works
     *
     * @test
     *
     * @return void
     */
    public function testDeleteOnAnExistingResource()
    {
        $id       = $this->createResource();
        $resource = $this->hostname.$this->url.'/'.urlencode($id);

        $ok       = new HttpOKConstraint();
        $notFound = new NotFoundConstraint();

        $this->assertThat($this->delete($resource), $ok);
        $this->assertThat($this->delete($resource), $notFound);

    }//end testDeleteOnAnExistingResource()


}//end class

?>