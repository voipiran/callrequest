<?php
require ('./CheckLicense.php');
 require ('manager.php');
 error_reporting(E_ALL);


$config = parse_ini_file('callrequest-params.ini' , true);
$ast_host = $config['asterisk_host'];
$ast_user = $config['ami_user'];
$ast_pass = $config['ami_password'];
//logging
$logging = $config['eventsLogging'];
$postURL = $config['postURL'];
$cid = $config['callerid'];
$DBServer = $config['DBServer'];
$DBUser = $config['DBUser'];
$DBPassword = $config['DBPassword'];



/*Coonect Asterisk and Make Call */
/* Now we connect to the AMI interface */
$astManager = new AGI_AsteriskManager();
	$res = $astManager->connect($ast_host, $ast_user, $ast_pass);
		if(!$res) {
		//echo $promptAMINotConnected ;
		insertLog("Error:".$promptAMINotConnected,$clientDomain);
		exit();
		}
		
//add OrginateResponse Event handler
$astManager->add_event_handler("OriginateResponse", "origResponse");
$astManager->add_event_handler("Hangup", "hangupResponse");

//waite until get response
		echo "**********START LISTENING***********";
$response = $astManager->wait_response(TRUE);


while (!$response) {
    //usleep("50000");
	$res = $astManager->connect($ast_host, $ast_user, $ast_pass);
    while (!$res) {
        //usleep("50000");
		$res = $astManager->connect($ast_host, $ast_user, $ast_pass);
    }
    $response = $astManager->wait_response(TRUE);
}

//$astManager->disconnect();
//exit();


/**************END*************/


//callback function
function origResponse($event,$data)
{
	echo "**********START ORIG***********";
/*
0 = no such extension or number. Also bad dial tech ie. name of a sip trunk that doesn’t exist
1 = no answer
4 = answered
5 = busy
8 = congested or not available (Disconnected Number)
*/

		$status = $data["Reason"];
		$channel = $data["Channel"] ;
		//$channel = substr($channel,-11);
		$uniqueid = $data["Uniqueid"] ;
		$ActionID = explode( '*', $data["ActionID"] );
		$action1 = $ActionID[0];
		$action2 = $ActionID[1];
		$accountCode = $data["AccountCode"] ;


	switch($status){
		case '0':
		$call_result = "bad originate command";
		case '1':
		$call_result = "no answer or unreachable";
		break;
		case '3':
		$call_result = "originate command timeout";
		break;
		case '4':
		$call_result = "answered";
		break;
		case '5':
		$call_result = "busy";
		break;
		case '8':
		$call_result = "Congestion";
		break;
		default:
		$call_result = "no-handel-status";
		
	}

	if(($status != '4')){
			sendPostRequest("OriginateResponse",$uniqueid,$channel,$call_result,$action1,$action2,$accountCode,"0",$temp2);	
	}
			
}

function hangupResponse($event,$data)
{
	global $cid;
	global $DBServer;
	global $DBUser;
	global $DBPassword;
		
		$status = $data["Reason"];
		$channel = $data["Channel"] ;
		$ChannelState = $data["ChannelState"] ;
		$ChannelStateDesc = $data["ChannelStateDesc"] ;
		//$channel = substr($channel,-11);
		$uniqueid = $data["Uniqueid"] ;
		//$accountCode = $data["AccountCode"] ;
		$accountCode = explode( '*', $data["AccountCode"] );
		$customerNumber = $accountCode[0];
		$responseCode = $accountCode[1];
		
		$connectedLineNum = $data["ConnectedLineNum"] ;
		$linkedid = $data["Linkedid"] ;
		$callerid = $data["callerid"] ;

		
		//if((($accountCode == $connectedLineNum) || ($connectedLineNum != $cid))&&($ChannelState == 6)){
		if((($customerNumber == $connectedLineNum) || ($customerNumber != $cid))&&($ChannelState == 6)){

			
/*GET DURATION FROM CDR*/
  	$con = mysql_connect($DBServer,$DBUser,$DBPassword);
		if (!$con)
		{
		echo "*******DB NOT CONNECTED *****";
		die('Could not connect: ' . mysql_error());

		}
		echo "*******DB CONNECTED *****";
		$query="select * from cdr where uniqueid = '$uniqueid'";
		$name = mysql_db_query("asteriskcdrdb",$query,$con);
		$row = mysql_fetch_array($name);
		
		$duration= $row[billsec];
		$recordingfile= $row[recordingfile];	
			
			
			if(!($duration == NULL)){
				sendPostRequest("HangupResponse",$uniqueid,$channel,$ChannelStateDesc,$customerNumber,$responseCode,$accountCode,$duration,$recordingfile);	
			}	
		}
}

function sendPostRequest($event,$uniqueid,$channel,$status,$action1,$action2,$accountCode,$temp,$temp2)
{
	/*INSERT LOG*/
	$customerNumber=$action1;
	$responseCode=$action2;
	$duration = $temp;
	$recordingfile = $temp2;
	
	//insertLog("Event:".$event."-uniqueid:".$uniqueid."-channel:".$channel."-call_result:".$status."-action1:".$action1."-action2:".$action2."-accountCode:".$accountCode."-Temp:".$temp);
	insertLog("Event:".$event." -status:".$status." -customerNumber:".$customerNumber." -responseCode:".$responseCode." -duration:".$duration." -uniqueid:".$uniqueid." -recordingfile:".$recordingfile);


//$status = vaziate tamas
//$customerNumber = shomare moshtari
//$responseCode = shore ersali as samte CRM
//$duration = toole mokaleme
//$recordingfile = esme file zabt shode mokaleme
	
	
	/*SEND POST URL*/
	global $postURL;

	$data = '
	{
	"event": $event,
	"customerNumber": $customerNumber,
	"status": $status,
	"responseCode": $responseCode,
	"duration": $duration,
	"recordingfile": $recordingfile,
	}
	';

	$additional_headers = array(                                                                          
	'Accept: application/json',
	'X-WorkWave-Key: YOUR API KEY',
	'Host: wwrm.workwave.com',
	'Content-Type: application/json'
	);

	$ch = curl_init($postURL);                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, $additional_headers); 

	$server_output = curl_exec ($ch);

	//echo  $server_output;
}






function insertLog($message) {
	
	//if(strtolower($logging) == "true"){
		$log  = date("F j, Y, g:i a").
		"log: ".$message.PHP_EOL.
		"-------------------------".PHP_EOL;
		file_put_contents('eventLogs.txt', $log, FILE_APPEND);
	//}

}



?>