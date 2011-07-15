<?php
$_pluginInfo=array(
	'name'=>'Plus',
	'version'=>'1.0.0',
	'description'=>"Get the contacts from a Plus.net account",
	'base_version'=>'1.8.0',
	'type'=>'email',
	'check_url'=>'http://www.plus.net/',
	'requirement'=>'email',
	'allowed_domains'=>false,
);

class plus extends openinviter_base
{
	protected $loginUrl   = 'https://webmail.plus.net/src/redirect.php';
	protected $logoutUrl  = 'https://webmail.plus.net/src/signout.php';
	protected $contactUrl = 'https://webmail.plus.net/src/addressbook.php';
	protected $userAgent  = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30';
	private $isLoggedIn   = false;

	public function login($user, $pass)
	{
		if(empty($user) || empty($pass)) return false;
		if(!$this->init()) return false;
		$postfields = array(
			'login_username'        => $user,
			'secretkey'             => $pass,
			'js_autodetect_results' => 0,
			'just_logged_in'        => 1,
			'loginServer'           => 'PlusNet',
		);
		$data = $this->post($this->loginUrl, $postfields);
		if (strlen($data) == 0) {
			$this->isLoggedIn = true;
			return true;
		}
		return false;
	}
	
	public function getMyContacts()
	{
		if ($this->isLoggedIn)
		{
			$data = $this->get($this->contactUrl);
			$table_start  = stripos($data, '<table align="center" border="0" cellpadding="1" cellspacing="0" width="90%">');
			$table_end    = stripos($data, '</table>', $table_start);
			$table        = substr($data, $table_start, $table_end-$table_start);

			$rows = array();
			$result = array();

			preg_match_all('|<tr bgcolor="[^"]+">.*?</tr>|uis', $table, $rows);
			$rows = $rows[0];
			array_shift($rows);
			foreach($rows as $row){
				$cols = array();
				preg_match_all('#<(label|a)[^>]*>(.*?)</(label|a)>#uis', $row, $cols);
				if ($this->isEmail($cols[2][2])) {
					$result[$cols[2][2]] = $cols[2][1];
				}
			}
			return $this->returnContacts($result);
		}
	
	}
	
	public function logout()
	{
		$this->get($this->logoutUrl);
	}
}
