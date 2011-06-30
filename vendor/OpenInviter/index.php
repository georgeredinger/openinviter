 <?php
	include('openinviter.php');
	$inviter=new OpenInviter();
	$oi_services=$inviter->getPlugins();
	$ers = $oks = array();
	$import_ok = $done = false;

	$strUsername = trim($_POST['username']);
	$strPassword = trim($_POST['password']);
	$strProvider = $_POST['domain'];

	if ($strProvider == 'msn') {
		// MSN is now served through the unified Live/Hotmail plugin
		$strProvider = 'hotmail';
	}
	
error_log("***********************************************");
	switch($strProvider){
		case 'gmail':
			(!strpos($strUsername,'gmail'))? $strUsername .= "@gmail.com":null;
			break;
		case 'cox_net':
			(!strpos($strUsername,'cox'))? $strUsername .= "@cox.net":null;
			break;
		case 'comcast_net':
			(!strpos($strUsername,'comcast'))? $strUsername .= "@comcast.net":null;
			break;
		case 'verizon_net':
			(!strpos($strUsername,'verizon'))? $strUsername .= "@verizon.net":null;
			break;
		case 'at_t_net':
			(!strpos($strUsername,'att.net'))? $strUsername .= "@att.net":null;
			break;
		case 'aim':
			break;
		case 'quest':
			break;
		case 'msn':
			break;
		case 'hotmail':
			break;
		//======================================================================
		//new added
		case 'fuse_net':
			(!strpos($strUsername,'fuse'))? $strUsername .= "@fuse.net":null;
			break;
		//======================================================================
		default:
			(!strpos($strUsername,$strProvider))? $strUsername .= "@{$strProvider}.com":null;
	}
	$inviter->startPlugin($strProvider);
	$internalError = $inviter->getInternalError();
	if(false != $internalError) {
		print $internalError;
        exit;
	}
	elseif(false == $inviter->login($strUsername,$strPassword)){
        print "Invalid Username or Password";
        exit;
	}
	elseif(false === $contacts = $inviter->getMyContacts()) {
        print "Something went wrong getting contacts.  Please call John at Buddy Referral System Tech Support: 208-651-7239.";
        exit;
    }
	

	$i = 0;
	$strReturn = "";
	asort($contacts);
	foreach($contacts as $strEmail=>$strName)
	{
		$strReturn .= "<input type='checkbox' name='$i' id='chkAccContact' value='$strEmail' onclick='fnAddtoVIPList(this, this.value)'> <label>$strEmail</label><br>\n";
		$i++;
	}
	print($strReturn);

?>
