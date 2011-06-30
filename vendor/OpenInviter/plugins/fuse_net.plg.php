<?php
$_pluginInfo=array(
	'name'=>'Fuse.net',
	'version'=>'1.4.9',
	'description'=>"Get the contacts from a Fuse.net account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://webmail.fuse.net/webedge/',
	'requirement'=>'email',
	'allowed_domains'=>false,
	);
/**
 * Fuse.net Plugin
 * 
 * Imports user's contacts from Fuse.net AddressBook
 * 
 * @author OpenInviter
 * @version 1.3.8
 */
class fuse_net extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	protected $timeout=30;
	public $debug_array=array(
			  'login_post'=>'value="Logout"',
			  'contacts_page'=>'addresses.csv'
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
		$this->resetDebugger();
		$this->service='fuse_net';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$post_elements=array("account"=>$user,
							 "password"=>$pass,
							 "variant"=>"cbt",
							 "locale"=>"en-US",
							 "client"=>"html",
							 "login"=>"1",				 
							);

	    $res=$this->post("http://webmail.fuse.net/webedge/do/dologin",$post_elements,true);
		
	   	if ($this->checkResponse('login_post',$res))
		{
			$this->updateDebugBuffer('login_post',"http://webmail.fuse.net/webedge/do/home",'GET');
		}
		else 
			{
			$this->updateDebugBuffer('login_post',"http://webmail.fuse.net/webedge/do/home",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
		$this->login_ok=$this->login_ok="https://webmail.west.cox.net/do/addresses/export/submit";
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
		$post_elements["format"]="outlook2000";$post_elements['locale']="en_US";
	    $res=$this->post("http://webmail.fuse.net/webedge/do/addresses/export/submit",$post_elements,true);
		
		if ($this->checkResponse("contacts_page",$res))
		{
			$this->updateDebugBuffer('contacts_page',"http://webmail.fuse.net/webedge/do/addresses/export/submit",'POST',true,$post_elements);
		}
		else 
			{
			$this->updateDebugBuffer('contacts_page',"http://webmail.fuse.net/webedge/do/addresses/export/submit",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;	
			}
			
		$res = eregi_replace('"',"",$res);
		$csvrows = explode("\n", $res);
	
		foreach ($csvrows as $row)
		{
			$values = explode(",", $row);
			if (eregi("@", $values[26]))
			{
				if($values[1]<>"")
					$name = trim($values[0]." ".$values[1]." ".$values[2]);
				else
					$name = trim($values[0]." ".$values[2]);
				$email = $values[26];
				if($name=="")
					$name = trim($values[27]);
					
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
		$res=$this->get("http://webmail.fuse.net/webedge/do/logout?l=en-US&v=cbt");		
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}

	}
?>