<?php
$_pluginInfo=array(
	'name'=>'Comcast.net',
	'version'=>'1.4.9',
	'description'=>"Get the contacts from a Comcast.net account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://www.comcast.net/',
	'requirement'=>'email',
	'allowed_domains'=>false,
	);
/**
 * Comcast.net Plugin
 * 
 * Imports user's contacts from Comcast.net AddressBook
 * 
 * @author OpenInviter
 * @version 1.3.8
 */
class comcast_net extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	protected $timeout=30;
	public $debug_array=array(
			  'login_post'=>'Sign out',
			  'contacts_page'=>'Email <small><b>Contacts'
			  );
	
	/**
	 * Login function
	 * 
	 * Makes all the necessary requests to authenticate
	 * the current user to the server.
	 * 
	 * @param string $user The current user.
	 * @param string $pass The password for the current user.
	 * @return bool TRUE if the current user was authenticated successfully, FALSE otherwise.
	 */
	public function login($user,$pass)
		{
		error_log("FOO: login()");
		
		$this->resetDebugger();
		$this->service='comcast_net';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$post_elements=array("user"=>$user,
							 "passwd"=>$pass,
							 "Submit"=>"Submit",
							 "redirect"=>"emailList.jsp",			
							);

	    $res=$this->post("https://login.comcast.net/login?s=mobile",$post_elements,true);
		
		// if ($this->checkResponse("login_post",$res))		
		// {
		// 	error_log("FOOONE");
		// 	$this->updateDebugBuffer('login_post',"http://m.comcast.net/signIn.jsp",'POST',true,$post_elements);
		// }
		// else 
		// 	{
		// 		error_log("FOOTWO");
		// 	$this->updateDebugBuffer('login_post',"http://m.comcast.net/signIn.jsp",'POST',false,$post_elements);
		// 	$this->debugRequest();
		// 	$this->stopPlugin();	
		// 	return false;	
		// 	}
	
		$this->login_ok=$this->login_ok="http://m.comcast.net/addressBook.jsp";
		return true;
		}

	/**
	 * Get the current user's contacts
	 * 
	 * Makes all the necesarry requests to import
	 * the current user's contacts
	 * 
	 * @return mixed The array if contacts if importing was successful, FALSE otherwise.
	 */	
	public function getMyContacts()
		{
		if (!$this->login_ok)
			{
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		else
			$url=$this->login_ok;

		$contacts=array();
		http://sz0096.ev.mail.comcast.net/zimbra/m/zmain#message
		$res=$this->get("https://sz0096.ev.mail.comcast.net/zimbra/m/zmain?st=contact",true,true,false);

		// error_log($res);
		// 
		// if ($this->checkResponse("contacts_page",$res))
		// 	$this->updateDebugBuffer('contacts_page',"http://m.comcast.net/signIn.jsp",'GET');
		// else
		// 	{
		// 	$this->updateDebugBuffer('contacts_page',"http://m.comcast.net/signIn.jsp",'GET',false);
		// 	$this->debugRequest();
		// 	$this->stopPlugin();
		// 	return false;
		// 	}
		preg_match_all('/<a href=\"\?st=newmail\&;to=(.*)\">/', $res, $cont);
		//preg_match_all('/(?P<key>[a-zA-Z\s]+):\s(?P<val>.*)\n/', $res, $cont);
		
		// <a href="?st=newmail&amp;to=besheynaz@aol.com">besheynaz@aol.com</a>
		
		for($i=0;$i<count($cont[1]);$i++)
		{
			if (eregi("@", $cont[2][$i]))
			{
				$email = trim($cont[2][$i]);
				$name = trim($cont[1][$i]);
					
				if($name=="")
					$name = eregi_replace("@.*","",$email);
					
				$contacts[$email]=$name;
			}
		}
		return $contacts;
		}

	/**
	 * Terminate session
	 * 
	 * Terminates the current user's session,
	 * debugs the request and reset's the internal 
	 * debudder.
	 * 
	 * @return bool TRUE if the session was terminated successfully, FALSE otherwise.
	 */	
	public function logout()
		{
		if (!$this->checkSession()) return false;
		$res=$this->get("https://webmail.west.comcast.net/do/logout?rnd=7216744409362527028");		
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}

	}
?>