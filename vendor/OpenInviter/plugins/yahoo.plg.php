<?php
include 'dbc/deathbycaptcha.php';


$_pluginInfo=array(
	'name'=>'Yahoo!',
	'version'=>'1.5.5',
	'description'=>"Get the contacts from a Yahoo! account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://mail.yahoo.com',
	'requirement'=>'email',
	'allowed_domains'=>array('/(yahoo)/i','/(ymail)/i','/(rocketmail)/i'),
	'imported_details'=>array('first_name','email_1'),
	);
/**
 * Yahoo! Plugin
 * 
 * Imports user's contacts from Yahoo!'s AddressBook
 * 
 * @author OpenInviter
 * @version 1.3.8
 */
class yahoo extends openinviter_base
	{
	private $login_ok=false;
	public $showContacts=true;
	protected $timeout=30;
	public $debug_array=array(
			  'initial_get'=>'util.Event.addListener',
			  'login_post'=>'window.location.replace',
			  'print_page'=>'field[allc]',
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
	public function login($user,$pass, $captcha = '')
    {
               
        $resultArray = array();
		$this->resetDebugger();
		$this->service='yahoo';
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
		$hidden = $this->getHiddenElements($res);					

		$post_elements=$this->getHiddenElements($res);$post_elements["save"]="Sign+In";$post_elements['login']=$user;$post_elements['passwd']=$pass;//$post_elements['.secword']=$captch;
		$res=$this->post("https://login.yahoo.com/config/login?",$post_elements,true);	
				
		if (strstr($res,'antiImg') != false) 
		{
			$divContent = explode('id=\'antiImg\'', $res);
			$reg_exUrl = "/(https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			if(preg_match($reg_exUrl, $divContent[1], $url))                     
			{
				$urlCaptcha = $this->rstrstr($url[0],");");                                              
				$imageCaptcha = file_get_contents($urlCaptcha);
				$filename = tempnam('', '');
				$filename .= ".jpg";									
				$isOk = file_put_contents($filename,$imageCaptcha);                       
				$resCap = $this->sendToDecaptcher($filename);							
				$resultArray = explode('|',$resCap);                        
				$captchaString = $resultArray[5];				
				$post_elements=$this->getHiddenElements($res);$post_elements["save"]="Sign+In";$post_elements['login']=$user;$post_elements['passwd']=$pass;$post_elements['.secword']=$captchaString;			
				$res=$this->post("https://login.yahoo.com/config/login?",$post_elements,true);	               											
			}                                             			
		}	 
	   	if ($this->checkResponse('login_post',$res))
        {			
			$this->updateDebugBuffer('login_post',"https://login.yahoo.com/config/login?",'POST',true,$post_elements);
		}else 
        {                        
			$this->updateDebugBuffer('login_post',"https://login.yahoo.com/config/login?",'POST',false,$post_elements);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
        }		
		$this->login_ok=$this->login_ok="http://address.mail.yahoo.com/?_src=&VPC=print";        
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
		{					
			$url=$this->login_ok;
		}

		$contacts=array();			
		$res=$this->get($url,true);
		if ($this->checkResponse("print_page",$res))		
			$this->updateDebugBuffer('print_page',"{$url}",'GET');
		else 
			{
			$this->updateDebugBuffer('print_page',"{$url}",'GET',false);
			$this->debugRequest();
			$this->stopPlugin();	
			return false;
			}
			
		$post_elements=array('VPC'=>'print',
							 'field[allc]'=>1,
							 'field[catid]'=>0,
							 'field[style]'=>'detailed',
							 'submit[action_display]'=>'Display for Printing'
							);
                                                        
  
		$res=$this->post("http://address.mail.yahoo.com/?_src=&VPC=print",$post_elements);
		$emailA=array();$bulk=array();
		$res=str_replace(array('  ','	',PHP_EOL,"\n","\r\n"),array('','','','',''),$res);
      // $res = str_replace(chr(13),"",$res);
//$res = explode("\n",$res);
//$res = implode("",$res);
         
		preg_match_all("#\<tr class\=\"phead\"\>\<td colspan\=\"2\"\>(.+)\<\/tr\>(.+)\<div class\=\"first\"\>\<\/div\>\<div\>\<\/div\>(.+)\<\/div\>#U",$res,$bulk);
		if (!empty($bulk))
			{
			foreach($bulk[1] as $key=>$bulkName)
				{
				$nameFormated=trim(strip_tags($bulkName));
				if (preg_match('/\&nbsp\;\-\&nbsp\;/',$nameFormated)) 
					{
					$emailA=explode('&nbsp;-&nbsp;',$nameFormated);
					if (!empty($emailA[1])) $contacts[$emailA[1].'@yahoo.com']=array('first_name'=>$emailA[0],'email_1'=>$emailA[1].'@yahoo.com');
					}
				elseif (!empty($bulk[3][$key])) { $email=strip_tags(trim($bulk[3][$key])); $contacts[$email]=array('first_name'=>$nameFormated,'email_1'=>$email); }
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
		$res=$this->get("http://login.yahoo.com/config/login?logout=1&.done=http://address.yahoo.com&.src=ab&.intl=us");		
		$this->debugRequest();
		$this->resetDebugger();
		$this->stopPlugin();
		return true;
		}
		
		
		
	//Reverse	
	private function rstrstr($haystack, $needle)
	{
			return substr(strrev(strstr(strrev($haystack), strrev($needle))), 0, -strlen($needle));         
	} 
	
	//function to send captcha to be decaptcher
	private function sendToDecaptcher($image)
	{                        		
		$url = 'http://poster.decaptcher.com/';
		$fields = array(
														'function'=>'picture2',
														'username'=>'brettwheeler',
														'password'=>'s5lnegg1llf1pn3ue96l2tlp',
														'pict'=> '@'.$image,
														'pict_to'=>'0',
														'pict_type'=>'0'
														
		);		
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');
	
		$ch = curl_init();		
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,true);            
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		$result = curl_exec($ch);
		return $result;
	}

	}





?>