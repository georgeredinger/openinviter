<?php
$_pluginInfo=array(
	'name'=>'AIM',
	'version'=>'1.5.1',
	'description'=>"Get the contacts from an AIM account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://webmail.aol.com',
	'requirement'=>'email',
	'allowed_domains'=>array('/(aim.com)/i'),
	);
/**
 * AIM Plugin
 * 
 * Imports user's contacts from AIM's AddressBook
 * 
 * @author OpenInviter
 * @version 1.4.7
 */
class aim extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $internalError=false;
	protected $timeout=30;
	
	public $debug_array=array(
			 'initial_get'=>'logintabs',
	    	 'login_post'=>'gSuccessPath',
	    	 'inbox'=>'aol.wsl.afExternalRunAtLoad = []',
	    	 'print_contacts'=>'window\x27s'
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
		$this->service='aim';
		$this->service_user=$user;
		$this->service_password=$pass;
		if (!$this->init()) return false;
		
		$user=(strpos($user,'@aim')!==false?str_replace('@aim.com','',$user):$user);
		
		$res=$this->get("https://my.screenname.aol.com/_cqr/login/login.psp?mcState=initialized&uitype=mini&sitedomain=registration.aol.com&authLev=1&seamless=novl&lang=en&locale=us&siteState=OrigUrl%3dhttp%253a%252f%252fregistration%252eaol%252ecom%252fmail%253fs%255furl%253dhttp%25253a%25252f%25252fwebmail%25252eaol%25252ecom%25252f%25255fcqr%25252fLoginSuccess%25252easpx%25253fsitedomain%25253dsns%25252ewebmail%25252eaol%25252ecom%252526siteState%25253dver%2525253a1%252525252c0%25252526ld%2525253awebmail%25252eaol%25252ecom%25252526pv%2525253aAOL%25252526lc%2525253aen%25252dus%25252526ud%2525253aaol%25252ecom&loginId=&_sns_width_=174&_sns_height_=196&_sns_fg_color_=000000&_sns_err_color_=FF0000&_sns_link_color_=000000&_sns_bg_color_=b8d2e5",true);
		
		preg_match('|<input type=\"hidden\" name=\"usrd\" value=\"(.*)\">|',$res,$id);
		$sUSRD = $id[1];
			
		$post_elements[urldecode(urldecode('sitedomain=sns.webmail.aol.com&siteId=&lang=en&locale=us&authLev=0&siteState=ver%253A3%257Crt%253ASTANDARD%257Cac%253AWS%257Cat%253ASNS%257Cld%253Awebmail.aol.com%257Cuv%253AAOL%257Clc%253Aen-us%257Cmt%253AAIM%257Csnt%253AScreenName&isSiteStateEncoded=true&mcState=initialized&uitype=std&use_aam=0&_sns_fg_color_=&_sns_err_color_=&_sns_link_color_=&_sns_width_=&_sns_height_=&_sns_bg_color_=&offerId=webmail2-en-us&seamless=novl&regPromoCode=&idType='))]="SN";
		$post_elements['usrd']=$sUSRD;
		$post_elements['loginId']=$user;
		$post_elements['password']=$pass;
		
		$res=$this->post("https://my.screenname.aol.com/_cqr/login/login.psp",$post_elements,true);
		if ($this->checkResponse('login_post',$res))	
			$this->updateDebugBuffer('login_post',"https://my.screenname.aol.com/_cqr/login/login.psp",'POST',true,$post_elements);
		else
			{
			$this->updateDebugBuffer('login_post',"https://my.screenname.aol.com/_cqr/login/login.psp",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$url_redirect="http://webmail.aol.com".htmlspecialchars_decode($this->getElementString($res,'var gSuccessPath = "','"',$res));
		$url_redirect=str_replace("Suite.aspx","Lite/Today.aspx",$url_redirect);
		$res=$this->get($url_redirect,true);
		if ($this->checkResponse('inbox',$res))
			$this->updateDebugBuffer('inbox',"{$url_redirect}",'GET');
		else 
			{
			$this->updateDebugBuffer('inbox',"{$url_redirect}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
			}
			
		$url_contact=$this->getElementDOM($res,"//a[@id='contactsLnk']",'href');
		$this->login_ok=$this->login_ok=$url_contact[0];
		file_put_contents($this->getLogoutPath(),$url_contact[0]);
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
		//go to url inbox
		$res=$this->get($url,true);

		
		$url_temp=$this->getElementString($res,"command.','','","'");
		$version=$this->getElementString($url_temp,'http://webmail.aol.com/','/');
		$url_print=str_replace("');","",str_replace("PrintContacts.aspx","addresslist-print.aspx?command=all&sort=FirstLastNick&sortDir=Ascending&nameFormat=FirstLastNick&version={$version}:webmail.aol.com&user=",$url_temp));
		$url_print.=$this->getElementString($res,"addresslist-print.aspx','","'");
		

	 	$res=$this->get($url_print,true);
	
		$contacts=array();
		if ($this->checkResponse("print_contacts",$res))
			{
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$nodes=$doc->getElementsByTagName("span");$name=false;$flag_name=false;$flag_email=false;
			$temp=array();
			$descriptionArrayFlag=array('Screen Name:'=>'nickname','Email 1:'=>'email_1','Email 2:'=>'email_2','Mobile: '=>'phone_mobile','Home: '=>'phone_home','Work: '=>'phone_work','Pager: '=>'pager','Fax: '=>'fax_work','Family Names:'=>'last_name');
			$xpath=new DOMXPath($doc);$query="//span";$data=$xpath->query($query);
			foreach($data as $node)
				{
				if ($node->getAttribute("class")=="fullName") { $nameD=$node->nodeValue;$temp=array(); }
				if (end($temp)!==false)
					{
					$key=key($temp);
					if ($key=='Email 1:') $keyDescription=$node->nodeValue;
					if (!empty($keyDescription))
						{
						if (empty($contacts[$keyDescription]['first_name'])) $contacts[$keyDescription]['full_name']=!empty($nameD)?$nameD:false;
						$contacts[$keyDescription][$descriptionArrayFlag[$key]]=!empty($node->nodeValue)?$node->nodeValue:false; $temp[$key]=false;
						}
					}
				if (isset($descriptionArrayFlag[$node->nodeValue])) $temp[$node->nodeValue]=true;
				}
			$this->updateDebugBuffer('print_contacts',"{$url_print}",'GET');
			}
		else
			{ 
			$this->updateDebugBuffer('print_contacts',"{$url_print}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();
			return false;
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
		if (file_exists($this->getLogoutPath()))
			{
			$url=file_get_contents($this->getLogoutPath());
			$res=$this->get($url,true);
			$url_logout=$this->getElementDOM($res,"//a[@class='signOutLink']",'href');
			if (!empty($url_logout)) $res=$this->get($url_logout[0]);
			}
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
				
	}
?>