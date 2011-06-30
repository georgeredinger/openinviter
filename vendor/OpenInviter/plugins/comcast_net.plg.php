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
							 "contacts_page"=>"contacts.csv",			
							);

	    $res=$this->post("https://login.comcast.net/login?s=mobile",$post_elements,true);
		$this->login_ok="http://www.plaxo.com/po3/?module=tools&operation=recover_ab&t=sz";
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

		//$contacts=array();
		//$res=$this->get("http://sz0096.ev.mail.comcast.net/zimbra/m/zmain?st=contact",true,true,false);
		//preg_match_all('/<a href=\"\?st=newmail\&;to=(.*)\">/', $res, $cont);
		
		$contacts=array();
		$post_elements["format"]="outlook2000";$post_elements['locale']="en_US";
	    $res=$this->post("http://sz0096.ev.mail.comcast.net/zimbra/m/zmain?st=contact",$post_elements,true);
		
		if ($this->checkResponse("contacts_page",$res))		
		{
			$this->updateDebugBuffer('contacts_page',"http://sz0096.ev.mail.comcast.net/zimbra/m/zmain?st=contact",'POST',true,$post_elements);
		}
		else 
			{
			$this->updateDebugBuffer('contacts_page',"http://sz0096.ev.mail.comcast.net/zimbra/m/zmain?st=contact",'POST',false,$post_elements);
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