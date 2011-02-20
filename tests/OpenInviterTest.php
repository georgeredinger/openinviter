<?php
/**
 * OpenInviterTest.php
 *
 * Holds the OpenInviterTest class
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

/**
 * The OpenInviterTest class is the test for the case types resource
 *
 * PHP Version: PHP 5
 *
 * @group Acceptance
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
class OpenInviterTest extends BrsEndToEndTest
{

    /**
     * @var string A test e-mail account to use.
     */
    private $_testEmailAccount = 'clubleads@gmail.com';

    /**
     * @var string The password for the test account.
     */
    private $_testEmailPassword = 'baseball';

    /**
     * @var array of email addresses we want from the server.
     */
    private $_testEmailContacts = array(
                                   array('email' => 'sue@test.com'),
                                   array('email' => 'eric@test.com'),
                                  );

    /**
     * @var the url the resource is at.
     */
    protected $url = '/get_contacts';


    /**
     * Set up the fixtures.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

    }//end setUp()


    /**
     * Abstract call to the service assuring the response is OK
     *
     * @param array                $data       The POST data to send
     * @param HttpStatusConstraint $constraint A http status constraint
     *
     * @return HttpResponse
     */
    private function _useWith(array $data, HttpStatusConstraint $constraint)
    {
        $response = $this->post(
            $this->hostname.$this->url,
            $data
        );
        $this->assertThat($response, $constraint);

        return $response;

    }//end _useWith()


    /**
     * Scenario: Successfully fetch the contact list from each provider
     * When I POST to
     * /get_contacts?service=gmail&username=clubleads@gmail.com&password=baseball
     * Then I should receive a SUCCESS response with the JSON array
     * [{"email":"sue@test.com"},{"email":"eric@test.com"}]
     *
     * @return void
     */
    public function testSuccess()
    {
        $constraint = new HttpOKConstraint();
        $response   = $this->_useWith(
            array(
             'service'  => 'gmail',
             'username' => $this->_testEmailAccount,
             'password' => $this->_testEmailPassword,
            ),
            $constraint
        );

        $expectedJSON = json_encode($this->_testEmailContacts);
        $this->assertEquals($expectedJSON, $response->data);

    }//end testSuccess()


    /**
     * Scenario: Bad Username
     * When I POST to
     * /get_contacts?service=gmail&username=bademail_address&password=baseball
     * Then I should receive a FAIL response with the JSON
     * {message:"Bad Username or Password"}
     *
     * @return void
     */
    public function testBadUsername()
    {
        $constraint   = new HttpUnauthorizedConstraint();
        $response     = $this->_useWith(
            array(
             'service'  => 'gmail',
             'username' => 'bad_'.$this->_testEmailAccount,
             'password' => $this->_testEmailPassword,
            ),
            $constraint
        );
        $expectedJSON = '{"message":"Bad Username or Password"}';
        $this->assertEquals($expectedJSON, $response->data);

    }//end testBadUsername()


    /**
     * Scenario: Bad Password
     * When I POST to
     * /get_contacts?service=gmail&username=joe@gmail.com&password=badpassword
     * Then I should receive a FAIL response with the JSON
     * {message:"Bad Username or Password"}
     *
     * @return void
     */
    public function testBadPassword()
    {
        $constraint   = new HttpUnauthorizedConstraint();
        $response     = $this->_useWith(
            array(
             'service'  => 'gmail',
             'username' => $this->_testEmailAccount,
             'password' => 'bad_'.$this->_testEmailPassword,
            ),
            $constraint
        );
        $expectedJSON = '{"message":"Bad Username or Password"}';
        $this->assertEquals($expectedJSON, $response->data);

    }//end testBadPassword()


    /**
     * Scenario: Successful Login But No Contacts Exist
     * When I POST to
     * /get_contacts?service=gmail&username=clubleads.nocontacts@gmail.com&password=badpassword
     * Then I should receive a SUCCESS response with the JSON array []
     *
     * @return void
     */
    public function testEmpty()
    {
        $constraint = new HttpOKConstraint();
        $response   = $this->_useWith(
            array(
             'service'  => 'empty',
             'username' => $this->_testEmailAccount,
             'password' => $this->_testEmailPassword,
            ),
            $constraint
        );

        $expectedJSON = '[]';
        $this->assertEquals($expectedJSON, $response->data);

    }//end testEmpty()


}//end class

?>