<?php
$_pluginInfo=array(
	'name'=>'Bt',
	'version'=>'1.1.2',
	'description'=>"Get the contacts from a Bt account",
	'base_version'=>'1.8.4',
	'type'=>'social',
	'check_url'=>'http://bt.com',
	'requirement'=>'user',
	'allowed_domains'=>false,
	);
/**
 * Bt Plugin
 * 
 * Imports user's contacts from Bt
 * 
 * @author Kishore Metra
 * @version 1.0
 */
class bt extends OpenInviter_Base
	{
	private $login_ok=false;
	public $showContacts=true;
	public $requirement='user';
	public $internalError=false;
	public $allowed_domains=false;
	protected $timeout=30;
	protected $maxUsers=100;
	
	public $debug_array=array(
				'initial_get'=>'username',
				'login_post'=>'inbox',
				'friends_url'=>'list-tweet',
				'wall_message'=>'latest_text',
				'send_message'=>'inbox'
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
		$this->service='bt';
		$this->service_user=$user;
		$this->service_pass=$pass;
		if (!$this->init()) return false;
		$res=$this->get("http://bt.com",true);
		if ($this->checkResponse('initial_get',$res))
			$this->updateDebugBuffer('initial_get',"http://bt.com/",'GET');
		else 
			{
			$this->updateDebugBuffer('initial_get',"http://bt.com/",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
		$form_action="https://www.bt.com/siteminderagent/forms/login.fcc";
		$post_elements=array('target'=>$this->getElementString($res,'name="target" value="','"'),'smauthreason'=>$this->getElementString($res,'name="smauthreason" value="','"'),'siteArea'=>$this->getElementString($res,'name="siteArea" value="','"'),'USER'=>$user,'PASSWORD'=>$pass,'x'=>'53','y'=>'12');
		$res=$this->post($form_action,$post_elements,true);
		if ($this->checkResponse('login_post',$res))
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',true,$post_elements);
		else 
			{
			$this->updateDebugBuffer('login_post',"{$form_action}",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}				
		$this->login_ok="https://www.intouch.bt.com/BTCOM/app/viewContacts.do?pab_detail_1=surname&pab_detail_2=Home&pab_detail_3=Work&pab_detail_4=Email&page_num_contacts=25&sort_field=surname&email_detail_1=surname&email_detail_2=Email";
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
		else $url=$this->login_ok;
		$res=$this->get($url);
		if ($this->checkResponse('friends_url',$res))
			$this->updateDebugBuffer('friends_url',"{$url}",'GET');
		else 
			{
			$this->updateDebugBuffer('friends_url',"{$url}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}	
		$contacts=array();$countUsers=0;

		preg_match_all('/<tr[^>]*>[^<]*<td\sclass="t4Tint">(.*?)<\/tr>/si',$res,$tr_array,PREG_SET_ORDER);

		foreach($tr_array as $tr){
	
				preg_match_all('/<td>(.*?)<\/td>/si',$tr[1],$td_ar,PREG_SET_ORDER);
				$contacts[$countUsers]=trim(str_replace('&nbsp','',strip_tags($td_ar[3][1]))); $countUsers++;

		}
						
		/*do{			
			$nextPage=false;
			$doc=new DOMDocument();libxml_use_internal_errors(true);if (!empty($res)) $doc->loadHTML($res);libxml_use_internal_errors(false);
			$xpath=new DOMXPath($doc);
			$query="//a[@name]";$data=$xpath->query($query);
			foreach ($data as $node)
				{
				$user=(string)$node->getAttribute("name");
				if (!empty($user)) {$contacts[$countUsers]=$user; $countUsers++; }									
				}			
			$query="//div[@class='list-more']/a";$data=$xpath->query($query);
			foreach($data as $node) { $nextPage=$node->getAttribute("href");break; }					
			if ($countUsers>$this->maxUsers) break; 
			if (!empty($nextPage)) $res=$this->get('http://mobile.twitter.com'.$nextPage);			
			}
		while ($nextPage);	*/		
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
	 * 
	 */	
	public function logout()
		{
		if (!$this->checkSession()) return false;
		$this->get("https://www2.bt.com/logout_all?siteArea=con.mya&external_target=http://www.bt.com/youraccount");
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;	
		}
	}	

?>
