<?php
$_pluginInfo=array(
	'name'=>'Shaw',
	'version'=>'1.0',
	'description'=>"Get the contacts from a shaw.ca account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'https://webmail.shaw.ca/',
	'requirement'=>'email',
	'allowed_domains'=>array('/(shaw)/i'),
	'imported_details'=>array('first_name','email_1'),
	);

/**
 * Shaw.ca Plugin
 * 
 * Imports user's contacts from shaw.ca address book
 * 
 * @author Dan Martin
 * @version 1.0
 */
class shaw_ca extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	protected $timeout=30;
	public $debug_array=array(
			  'initial_get'=>'Shaw Webmail',
			  'login_post'=>'getLocalizedLabel',
			  'address_book'=>'Personal Address Book',
			  'printable_page'=>'abprnlist.xml'
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
        error_log("I got to this point");
		$this->resetDebugger();
		$this->service='shaw_ca';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
				
		$res=$this->get("https://webmail.shaw.ca/uwc/auth");
		
		
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"https://webmail.shaw.ca/uwc/auth",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"https://webmail.shaw.ca/uwc/auth",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
		
		$post_elements=$this->getHiddenElements($res);$post_elements["fromlogin"]="true";$post_elements['username']=$user;$post_elements['password']=$pass;		
		
		$res=$this->post("https://webmail.shaw.ca/uwc/auth",$post_elements,true);

	   	if ($this->checkResponse('login_post',$res))
			$this->updateDebugBuffer('login_post',"https://webmail.shaw.ca/uwc/auth",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('login_post',"https://webmail.shaw.ca/uwc/auth",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}		
		$this->login_ok="https://webmail.shaw.ca/uwc/abs/search.xml?stopsearch=1";
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
			$res=$this->get($url,true);
		
		if ($this->checkResponse("address_book",$res))		
			$this->updateDebugBuffer('address_book',"{$url}",'GET');
		else 
			{
			$this->updateDebugBuffer('address_book',"{$url}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}

		$bulk=array();$bookidA=array();$bookid='';
		preg_match("/actionbookid.value\s*\=\s*\'(.*?)\'/", $res,$bookidA);

		if(!empty($bookidA)) $bookid = $bookidA[1];
		$printableUrl='https://webmail.shaw.ca/uwc/abs/abprnlist.xml?bookid=' . $bookid;
		$res=$this->get($printableUrl);

		if ($this->checkResponse("printable_page",$res))
		$this->updateDebugBuffer('printable_page',"{$printableUrl}",'GET');
		else
		{
			$this->updateDebugBuffer('printable_page',"{$printableUrl}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
		}
		
		$res=str_replace(array('  ','	',PHP_EOL,"\n","\r\n"),array('','','','',''),$res);
		preg_match_all('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i', $res, $bulk);

		$contacts=array();
					
			if (!empty($bulk))
			{
				foreach($bulk[0] as $key=>$emailAddress)
				{
					if (!empty($emailAddress) && $emailAddress != 'e.g.john@siroe.com') $contacts[$bulk[0][$key]]=array('email_1'=>$emailAddress,'first_name'=>'');
				}
			}
		foreach ($contacts as $email=>$name) if (!$this->isEmail($email)) unset($contacts[$email]);
		return $this->returnContacts($contacts);
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
		$res=$this->get("https://webmail.shaw.ca/cmd.msc?sid=&mbox=&cmd=logout&laurel=on&security=false&lang=en&popupLevel=undefined&cal=0");		
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}

	}
?>