<?php
$_pluginInfo=array(
	'name'=>'RR.com',
	'version'=>'1.4.9',
	'description'=>"Get the contacts from a RR.com account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'https://webmail.austin.rr.com/',
	'requirement'=>'email',
	'allowed_domains'=>false,
	);
/**
 * RR.com
 * 
 * Imports user's contacts from RR.com AddressBook
 * 
 * @author OpenInviter
 * @version 1.3.8
 */
class rr_com extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $ch="";
	public $domain="";
	public $internalError=false;
	protected $timeout=30;
	public $debug_array=array(
			  'login_post'=>'/do/logout',
			  'contacts_page'=>'First Name'
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
		$this->service='rr_com';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		preg_match('|\@(.*)\.rr\.com|',$user,$dom);
		$this->domain=$dom[1];
		

		$url="https://webmail.".$this->domain.".rr.com/do/dologin?l=en-US&v=standard";
		$cookie_path="/tmp";
		$header="true";
		$postfileds="variant=standard&client=html&directMessageView=&uid=&uidl=&folder=&remoteAccountUID=&domain=webmail.".$this->domain.".rr.com&login=1&account=".urlencode($user)."&password=".urlencode($pass)."&locale=en-US";
		$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";
		$this->ch = curl_init(); 
		curl_setopt($this->ch, CURLOPT_URL,$url);    
		curl_setopt($this->ch, CURLOPT_USERAGENT, $agent);    
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);     
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
	
		if($referrer!="")
		{		
			curl_setopt($this->ch, CURLOPT_REFERER, $referrer);  
		}  
		
		if($cookie_path!="")    
		{
			
			curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_path);
			curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookie_path);
		}    
	
		if($postfileds!="")    
		{    	
			curl_setopt($this->ch, CURLOPT_POST, 1.1);     
			curl_setopt($this->ch, CURLOPT_POSTFIELDS,$postfileds);     
		}    
	
		if($header!="")   
		{ 
			curl_setopt($this->ch, CURLOPT_HEADER, 1); 
		}   
	
		$res1 = curl_exec ($this->ch);    
		$curlstatus=curl_getinfo($this->ch);

	   	if ($this->checkResponse('login_post',$res1))
		{
			$this->updateDebugBuffer('login_post',"https://webmail.".$this->domain.".rr.com/do/dologin?l=en-US&v=standard",'GET');
		}
		else 
			{
			$this->updateDebugBuffer('login_post',"https://webmail.".$this->domain.".rr.com/do/dologin?l=en-US&v=standard",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}

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


		$contacts=array();
		
		$url="http://webmail.".$this->domain.".rr.com/do/addresses/export/submit";
		$cookie_path="/tmp";
		$header="";
		$postfileds="format=outlookExpress&locale=en_US";
		$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";

		curl_setopt($this->ch, CURLOPT_URL,$url);    
		curl_setopt($this->ch, CURLOPT_USERAGENT, $agent);    
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);     
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

		if($cookie_path!="")    
		{
			
			curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_path);
			curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookie_path);
		}    
	
		if($postfileds!="")    
		{    	
			curl_setopt($this->ch, CURLOPT_POST, 1.1);     
			curl_setopt($this->ch, CURLOPT_POSTFIELDS,$postfileds);     
		}    
	
		$res2 = curl_exec ($this->ch);    
		$curlstatus=curl_getinfo($this->ch);
		curl_close ($this->ch);
		
		if ($this->checkResponse("contacts_page",$res2))		
		{
			$this->updateDebugBuffer('contacts_page',"http://webmail.".$this->domain.".rr.com/do/addresses/export/submit",'POST',true,$post_elements);
		}
		else 
			{
			$this->updateDebugBuffer('contacts_page',"http://webmail.".$this->domain.".rr.com/do/addresses/export/submit",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;	
			}
			
		$res = eregi_replace('"',"",$res2);
		$csvrows = explode("\n", $res);
	
		foreach ($csvrows as $row)
		{
			$values = explode(",", $row);
			if (eregi("@", $values[5]))
			{
				if($values[2]<>"")
					$name = trim($values[0]." ".$values[2]." ".$values[1]);
				else
					$name = trim($values[0]." ".$values[1]);
				$email = $values[5];
				if($name=="")
					$name = trim($values[3]);
					
				if($name=="")
					$name = eregi_replace("@.*","",$email);
					
				$name = eregi_replace("@.*","",$name);
					
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