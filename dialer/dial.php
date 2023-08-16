<?php
require ('./CheckLicense.php');
 require ('manager.php');
 error_reporting(E_ALL);
$clientPreviuosTime = 1;

echo "VOIPIRAN CallRequest App"."<br/>";

/***********************************/
/***Get Parameters from ini File ***/
$config = parse_ini_file('callrequest-params.ini' , true) ;
$RetryTime = $config['RetryTime'] ;
$ast_host = $config['asterisk_host'];
$ast_user = $config['ami_user'];
$ast_pass = $config['ami_password'];
$MaxNumberLen = $config['MaxNumberLen'];
$MinNumberLen = $config['MinNumberLen'];
$BlockInternationalCalling = $config['BlockInternationalCalling'];
$CustomerNumberLenCheck = $config['CustomerNumberLenCheck'];
$allowedDomainsEnabled = $config['allowedDomainsEnabled'];
$allowedDomains1 = $config['allowedDomains1'];
$allowedDomains2 = $config['allowedDomains2'];
$allowedDomains3 = $config['allowedDomains3'];
$allowedDomains4 = $config['allowedDomains4'];
$allowedDomains5 = $config['allowedDomains5'];
$async = $config['async'];
$cid = $config['callerId'];
$trunkTechName = $config['trunkTechName'];
$pbxoutprefix = $config['pbxOutPrefix'];
//$trunkname = $config['trunkName'];
//request Limits
$requesrSecsLimits = $config['requesrSecsLimits'];
//logging
$logging = $config['dialLogging'];
//Translaions
$promptNotAllowed = $config['promptNotAllowed'];
$promptLimitReached = $config['promptLimitReached'];
$promptAMINotConnected = $config['promptAMINotConnected'];
$prompNumberNotRange = $config['prompNumberNotRange'];
$prompNumberIsInternational = $config['prompNumberIsInternational'];
$prompOriginateDone = $config['prompOriginateDone'];
/*Check if from Domain is in Whitelist */
$allowedDomains = array($allowedDomains1,
$allowedDomains2,
$allowedDomains3,
$allowedDomains4,
$allowedDomains5,);


/***START***/
session_start();
//$_SESSION['LAST_ACTIVITY'] = null;
$referer = $_SERVER['HTTP_REFERER'];
$clientParameters = parse_url($referer); //If yes, parse referrer
$clientDomain=$clientParameters['host'];
$clientTime = $_SERVER['REQUEST_TIME'];

/**************************/
/***Get POST Parameters ***/
//$extensionTech = $_REQUEST['extensionTech'];
$pbxDestination = $_REQUEST['pbxdestination'];
$direction = $_REQUEST['direction'];
$customerNumber = $_REQUEST['customernumber'];
$sessionId = $_REQUEST['sessionid'];
/*SET ACTION ID*/
if (empty($_REQUEST[$sessionId]))
$actionId = $customerNumber . "*" . $sessionId;
if (empty($_REQUEST[$callerid]))
$cid = $_REQUEST['callerid'];
if (empty($_REQUEST[$trunkTechName]))
$trunkTechName = $_REQUEST['trunktechname'];
if (empty($_REQUEST[$pbxOutPrefix]))
$pbxoutprefix = $_REQUEST['pbxoutprefix'];
if (empty($_REQUEST[$async]))
$async = $_REQUEST['async'];


/*First LOG, Insert Parameters */
$parameters = "pbxDestination:".$_REQUEST['pbxdestination']."-".
"direction:".$_REQUEST['direction']."-".
"customerNumber:".$customerNumber."-".
"callerid:".$cid."-".
"trunkTechName:".$trunkTechName."-".
"pbxOutPrefix:".$pbxoutprefix;
insertLog("RequestParameters:".$parameters,$clientDomain);


/*Check Number Validation*/
if((strtolower($CustomerNumberLenCheck) == 'yes') && !(($MinNumberLen <= strlen((string)$customerNumber)) && (strlen((string)$customerNumber) <= $MaxNumberLen)) ){
	echo $prompNumberNotRange."<br/>";
	insertLog("Error:".$prompNumberNotRange,$clientDomain);
	exit;
}

/*Check International Number*/
if((strtolower($BlockInternationalCalling) == 'yes') && preg_match("~^00[1-9]\d{8,}$~D", $customerNumber) ){
	echo $$prompNumberIsInternational."<br/>";
	insertLog("Error:".$$prompNumberIsInternational,$clientDomain);
	exit;
}

//Check Domain Whitelist
if(strtolower($allowedDomainsEnabled) == 'yes'){
	if(!in_array( $clientDomain, $allowedDomains)) {
	//echo $promptNotAllowed;
	insertLog("Error:".$promptNotAllowed,$clientDomain);
	exit(); //Stop running the script
	}
}

/*Coonect Asterisk and Make Call */
/* Now we connect to the AMI interface */
$astManager = new AGI_AsteriskManager();
	$res = $astManager->connect($ast_host, $ast_user, $ast_pass);
		if(!$res) {
			insertLog("Error:".$promptAMINotConnected,$clientDomain);
			exit();
		}

		if(strtolower($direction) == "firstcallpbx"){
			//$astManager->Originate('LOCAL/'.$pbxDestination.'@from-internal',$pbxoutprefix.$customerNumber,"from-internal-additional","1","",$cid,"","","","",$async);
			$astManager->Originate('LOCAL/'.$pbxDestination.'@from-internal',$pbxoutprefix.$customerNumber,"from-internal-additional","1","","","",$cid,"",$accountCode,$async,$actionId);
			echo $prompOriginateDone."<br/>";
			//insertLog("Originate:".'LOCAL/'.$pbxDestination.'-'.$pbxoutprefix.$customerNumber,$clientDomain);
		}
		elseif (strtolower($direction) == "firstcallcustomer"){
			//$astManager->Originate($trunkTech.'/'.$trunkname.'/'.$customerNumber,$pbxDestination,"from-internal-additional","1","",$cid,"","","","",$async);
			$astManager->Originate($trunkTechName.'/'.$customerNumber,$pbxDestination,"from-internal","1","","","",$cid,"",$accountCode,$async,$actionId);
				echo $prompOriginateDone."<br/>";
			//insertLog("Originate:".$trunkTechName.'-'.$cid,$clientDomain);
		}

//}//End RequestPerSec
$res = $astManager->disconnect();
exit();


function insertLog($message,$clientDomain) 
{

	global $logging;
	date_default_timezone_set('Asia/Tehran');
	if(strtolower($logging) == "1"){
		$log  = "FROM:".$clientDomain.'-DATE:'.date("Fj,Y,g:ia").
		"-LOG:".$message.PHP_EOL.
		"-------------------------".PHP_EOL;
		file_put_contents('dialLogs.txt', $log, FILE_APPEND);
	}
}



?>