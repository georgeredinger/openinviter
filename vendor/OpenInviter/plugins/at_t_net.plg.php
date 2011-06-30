<?php
$_pluginInfo=array(
	'name'=>'At&t.net',
	'version'=>'1.4.9',
	'description'=>"Get the contacts from a At&t.net account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://m.comcast.net/signIn.jsp',
	'requirement'=>'email',
	'allowed_domains'=>false,
	);
/**
 * At&t.net Plugin
 * 
 * Imports user's contacts from At&t.net AddressBook
 * 
 * @author OpenInviter
 * @version 1.3.8
 */
class at_t_net extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	protected $timeout=30;
	public $debug_array=array(
			  'initial_get'=>'form: login information',
			  'login_post'=>'window.location.replace',
			  'contacts_page'=>'.crumb',
			  'contacts_file'=>'"'
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
		$this->service='comcast_net';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$res=$this->get("https://login.yahoo.com/config/mail?.intl=us&rl=1");
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"https://login.yahoo.com/config/mail?.intl=us&rl=1",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"https://login.yahoo.com/config/mail?.intl=us&rl=1",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
		
		$post_elements=$this->getHiddenElements($res);$post_elements["save"]="Sign+In";$post_elements['login']=$user;$post_elements['passwd']=$pass;
	    $res=htmlentities($this->post("https://login.yahoo.com/config/login?",$post_elements,true));
	   	if ($this->checkResponse('login_post',$res))
			$this->updateDebugBuffer('login_post',"https://login.yahoo.com/config/login?",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('login_post',"https://login.yahoo.com/config/login?",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}		
		$this->login_ok=$this->login_ok="http://address.mail.yahoo.com/?_src=&VPC=tools_export";
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
		
		
		
		$post_elements=array('.done'=>'',
							'VPC'=>'print',
							urldecode(urldecode('field%5Ballc%5D'))=>'1',
							urldecode(urldecode('field%5Bcatid%5D'))=>'0',
							urldecode(urldecode('field%5Bstyle%5D'))=>'quick',
							urldecode(urldecode('submit%5Baction_display%5D'))=>urldecode(urldecode('Display+for+Printing')),
							);
		$res=$this->post("http://address.mail.yahoo.com/index.php",$post_elements);
		
		if ($this->checkResponse("contacts_file",$res))
			{
			
			$part=explode('<td valign="top" width="200">',$res);
		
		for($i=0;$i<count($part);$i++)
		{
			if(eregi("<table class=\"qprintable2\"",$part[$i]))
			{
				//echo $part[$i];
				preg_match("/<b> (.*) <\/b>/i",$part[$i],$u);
				preg_match("/<small>(.*)<\/small>/i",$part[$i],$u1);
				preg_match("/<div class=\"first\">(.*)<\/div>/i",$part[$i],$u2);
				preg_match("/<div>(.*)<\/div>/i",$part[$i],$u3);				
				preg_match("/<b><div class=\"last\">\s+(.*)\s+<\/div><\/b>/i",$part[$i],$u4);
				
				if($u[1]<>"")
					$name=$u[1];
		
				if($u3[1]<>"")
					$email=$u3[1];
				
				if($email=="" and $u1[1]<>"")
				{
					if(eregi("@",$u1[1]))
						$email=$u1[1];
					else
						$email=$u1[1]."@yahoo.com";
				}
					
				if($name=="" and $u1[1]<>"")
					$name=$u1[1];
					
				if($name=="" and $email<>"")
				{
					$nn=explode("@",$email);
					$name=$nn[0];
				}
				if($email<>"")
					$contacts[$email]=$name;
					//$contacts[] = array('name' => $name, 'email' => $email);
										
				$name="";
				$email="";
			}
		}

			$this->updateDebugBuffer('contacts_file',"http://address.mail.yahoo.com/index.php",'POST',true,$post_elements);
			}
		else 
			{
			$this->updateDebugBuffer('contacts_file',"http://address.mail.yahoo.com/index.php",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;	
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