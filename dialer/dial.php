<?php
require ('./CheckLicense.php');
require ('manager.php');
error_reporting(E_ALL);

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
/***END of Geting Parameters from ini File ***/


 /* Now we connect to the AMI interface */
$astManager = new AGI_AsteriskManager();

//$trunk_name="SIP/to-tci/";
//$outbound_prefix = '';
$trunk_clid = '43398000';

//$ast_host = '127.0.0.1';
//$ast_user = 'phpconfig';
//$ast_pass = 'php[onfig'; 

$out1 = $_REQUEST['out1'];
$in = strtolower($_REQUEST['in']);
$out2 = strtolower($_REQUEST['out2']);
//direction=op or customer
$direction = strtolower($_REQUEST['dir']);


$res = $astManager->connect($ast_host, $ast_user, $ast_pass);
		if(!$res) {
		echo "AMI NOT CONNECTED" ;
		die(100);
		}
		echo "*****AMI CONNECTED" ;

$parameters = "Out1:".$out1."-".
"direction:".$direction."-".
"Out2:".$out2."-".
"In:".$in."-".
"callerid:".$cid."-".
"trunkTechName:".$trunkTechName."-".
"pbxOutPrefix:".$pbxoutprefix;
insertLog("RequestParameters:".$parameters,"");

if(is_numeric($out1) && is_numeric($in) || is_numeric($out1) && is_numeric($out2)){

	if($direction == "inout"){
			$astManager->Originate("Local/".$in."@from-internal-additional",$pbxoutprefix.$out1,"from-internal-additional","1","","","",$out1,"","");
	}else if($direction == "outin"){
			$astManager->Originate($trunkTechName.$out1,$in,"from-internal-additional","1","","","",$cid,"","");
	}else if($direction == "outout"){
			$astManager->Originate($trunkTechName.$out1,$pbxoutprefix.$out2,"from-internal-additional","1","","","",$cid,"","");
	}

}


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


