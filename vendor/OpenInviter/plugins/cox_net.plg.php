<?php
$_pluginInfo=array(
	'name'=>'Cox.net',
	'version'=>'1.4.9',
	'description'=>"Get the contacts from a Cox.net account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://webmail.cox.net',
	'requirement'=>'email',
	'allowed_domains'=>false,
	);
/**
 * Cox.net Plugin
 * 
 * Imports user's contacts from Cox.net AddressBook
 * 
 * @author OpenInviter
 * @version 1.3.8
 */
class cox_net extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	protected $timeout=30;
	public $debug_array=array(
			  'login_post'=>'do/home',
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
		$this->service='cox_net';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$post_elements=array("username"=>$user,
							 "password"=>$pass,
							 "match"=>"classic",
							 "target"=>urldecode(urldecode("https%3A%2F%2Fidm.west.cox.net%2Floginwmw%2Fget%3FreturnURL%3D%2Fcoxlogin%2Fredirect.jsp%3Ftargeturl%3Dhttps%253A%252F%252Fwebmail.west.cox.net%26coxretry")),
							 "x"=>"44",
							 "y"=>"4",						 
							);

	    $res=$this->post("https://idm.west.cox.net/auth/login.fcc",$post_elements,true);
		$res1=$this->get("https://webmail.west.cox.net/do/home",false,true,false);
		preg_match("|Location\: (.*)|",$res1,$ur);
		$res=$this->get($ur[1],false,true,false);
		
	   	if ($this->checkResponse('login_post',$res1))
		{
			$this->updateDebugBuffer('login_post',"https://webmail.west.cox.net/do/home",'GET');
		}
		else 
			{
			$this->updateDebugBuffer('login_post',"https://webmail.west.cox.net/do/home",'GET',false);
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
	    $res=$this->post("https://webmail.west.cox.net/do/addresses/export/submit",$post_elements,true);
		
		if ($this->checkResponse("contacts_page",$res))		
		{
			$this->updateDebugBuffer('contacts_page',"https://webmail.west.cox.net/do/addresses/export/submit",'POST',true,$post_elements);
		}
		else 
			{
			$this->updateDebugBuffer('contacts_page',"https://webmail.west.cox.net/do/addresses/export/submit",'POST',false,$post_elements);
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
		$res=$this->get("https://webmail.west.cox.net/do/logout?rnd=7216744409362527028");		
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}

	}
?>