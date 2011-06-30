<?php
$_pluginInfo=array(
	'name'=>'Verizon.net',
	'version'=>'1.4.9',
	'description'=>"Get the contacts from a Verizon.net account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://wapp.verizon.net/PORTAL/WAPMAIL/LoginWap.aspx?ReturnUrl=%2fPORTAL%2fWAPMAIL%2ffolder.aspx%3ffolder%3dInbox',
	'requirement'=>'email',
	'allowed_domains'=>false,
	);
/**
 * Verizon.net Plugin
 * 
 * Imports user's contacts from Verizon.net AddressBook
 * 
 * @author OpenInviter
 * @version 1.3.8
 */
class verizon_net extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	protected $timeout=30;
	public $debug_array=array(
			  'login_post'=>'Sign Out',
			  'contacts_page'=>'AddressBook.aspx'
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
		$this->service='verizon_net';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("http://wapp.verizon.net/PORTAL/WAPMAIL/LoginWap.aspx?__ufps=505407&ReturnUrl=%2fPORTAL%2fWAPMAIL%2ffolder.aspx%3ffolder%3dInbox",true,true,false);
		preg_match('/<input type=\"hidden\" name=\"\_\_VIEWSTATE\" value=\"(.*?)\">/', $res, $vs);
		//print_r($vs);
		
		$post_elements=array("txtUserName"=>$user,
							 "txtPassword"=>$pass,
							 "__VIEWSTATE"=>$vs[1],
							 "__EVENTTARGET"=>"",
							 "__EVENTARGUMENT"=>"",
							 "cmdLogin.x"=>"26",
							 "cmdLogin.y"=>"7",			
							);

	    $res=$this->post("http://wapp.verizon.net/PORTAL/WAPMAIL/LoginWap.aspx?__ufps=505407&ReturnUrl=%2fPORTAL%2fWAPMAIL%2ffolder.aspx%3ffolder%3dInbox",$post_elements,true);
		//echo "<textarea rows=30 cols=120>".$res."</textarea>"; 
		
		if ($this->checkResponse("login_post",$res))		
		{
			$this->updateDebugBuffer('login_post',"http://wapp.verizon.net/PORTAL/WAPMAIL/LoginWap.aspx?__ufps=505407&ReturnUrl=%2fPORTAL%2fWAPMAIL%2ffolder.aspx%3ffolder%3dInbox",'POST',true,$post_elements);
		}
		else 
			{
			$this->updateDebugBuffer('login_post',"http://wapp.verizon.net/PORTAL/WAPMAIL/LoginWap.aspx?__ufps=505407&ReturnUrl=%2fPORTAL%2fWAPMAIL%2ffolder.aspx%3ffolder%3dInbox",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;	
			}
	
		$this->login_ok=$this->login_ok="http://m.verizon.net/addressBook.jsp";
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
		$res=$this->get("http://wapp.verizon.net/PORTAL/WAPMAIL/AddressBook/AddressBook.aspx",true,true,false);
		
		if ($this->checkResponse("contacts_page",$res))
			$this->updateDebugBuffer('contacts_page',"http://wapp.verizon.net/PORTAL/WAPMAIL/AddressBook/AddressBook.aspx",'GET');
		else
			{
			$this->updateDebugBuffer('contacts_page',"http://wapp.verizon.net/PORTAL/WAPMAIL/AddressBook/AddressBook.aspx",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
		
		preg_match_all('/<a href=\"ContactDetails\.aspx([^\"]*)\">([^\<]*)<\/a><\/b><br>\s+<font size=\"\-1\"><a href=\"\/PORTAL\/WAPMAIL\/SendMail\.aspx\?mail=([^\"]*)\">([^\<]*)<\/a>/', $res, $cont);
		
		for($i=0;$i<count($cont[1]);$i++)
		{
			if (eregi("@", $cont[4][$i]))
			{
				$email = trim($cont[4][$i]);
				$name = trim($cont[2][$i]);
					
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
		$res=$this->get("https://webmail.west.verizon.net/do/logout?rnd=7216744409362527028");		
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}

	}
?>