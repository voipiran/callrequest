<?php
header('Access-Control-Allow-Origin: *');

header('Content-type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");



//Get DB user and Pass
 $config = parse_ini_file('panel/inc/database.ini' , true) ;
$server_name = $config['server_name'] ;
$db_port = $config['db_port'] ;
$db_name = $config['db_name'] ;
$db_user = $config['db_user'] ;
$db_password = $config['db_password'] ;
$db_settings_table = $config['db_settings_table'] ;
$db_prompts_table = $config['db_prompts_table'] ;

// Create connection
$conn = new mysqli($server_name, $db_user, $db_password, $db_name);
$conn -> set_charset("utf8");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  echo "Errror";
}

$sql = "SELECT * FROM settings";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
      
    if($row['parameter']=='PbxURL')
        $pbxURL = $row['value'];
    
    if($row['parameter']=='CallButtonDisableTime')
        $callButtonDisableTime = $row['value'];
        
    if($row['parameter']=='PbxDestination')
        $pbxDestination = $row['value'] ;
        
    if($row['parameter']=='Direction')
        $direction = $row['value'];
        
    if($row['parameter']=='PbxOutboundPrefix')
        $pbxOutPrefix = $row['value'];
    
    if($row['parameter']=='TrunkTechName')
        $TrunkTechName = $row['value'];
  }
}

$sql = "SELECT * FROM prompts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
      
    if($row['prompt']=='PromptYourNumber')
        $promptYourNumber = $row['text'];
    
    if($row['prompt']=='PromptYourName')
        $promptYourName = $row['text'];
        
    if($row['prompt']=='Submit')
        $promptSubmit = $row['text'] ;
        
    if($row['prompt']=='Label2')
        $promptL2 = $row['text'];
        
    if($row['prompt']=='Label1')
        $promptL1 = $row['text'];
    
    if($row['prompt']=='Notice')
        $promptNotice = $row['text'];
        
    if($row['prompt']=='Calling')
        $promptCalling = $row['text'];
        
    if($row['prompt']=='InvalidNumber')
        $promptInvalidNumber = $row['text'];
  
  }
}



/*
$config = parse_ini_file('webcallback-params.ini' , true) ;
$config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/callrequest/webcallback-params.ini');
$pbxDestination = $config['pbxDestination'] ;
$direction = $config['direction'];
$pbxoutprefix = $config['pbxoutprefix'];
////$extensionTech = $config['extensionTech'];

//Translation
$promptYourNumber = $config['promptYourNumber'];
$promptYourName = $config['promptYourName'];
$promptSubmit = $config['promptSubmit'];
$promptL2 = $config['promptL2'];
$promptL1 = $config['promptL1'];
$promptNotice  = $config['promptNotice'];

$promptCalling = $config['promptCalling'];
$promptInvalidNumber = $config['promptInvalidNumber'];
$pbxURL = $config['pbxURL'];
$callButtonDisableTime = $config['callButtonDisableTime'];
*/



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>single page</title>
    <link href="https://www.voipiran.io/callrequest/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.voipiran.io/callrequest/css/default.css">
    <link rel="stylesheet" href="https://www.voipiran.io/callrequest/css/animate.min.css">
	<script async="" src="https://www.google-analytics.com/analytics.js"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Markazi+Text&family=Lalezar&display=swap');
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-font-smoothing: antialiased;
  -moz-font-smoothing: antialiased;
  -o-font-smoothing: antialiased;
  font-smoothing: antialiased;
  text-rendering: optimizeLegibility;
}

body {
  font-family: 'Lalezar', cursive;
  font-weight: 100;
  font-size: 16px;
  line-height: 30px;
  color: #777;
  direction:rtl;

}

.container {
  max-width: 400px;
  width: 100%;
  margin: 0 auto;
  position: relative;
}

#contact input[type="text"],
#contact input[type="email"],
#contact input[type="tel"],
#contact input[type="url"],
#contact textarea,
#contact button[type="submit"] {
  font: 500 16px/16px "Markazi Text", Helvetica, Arial, sans-serif;}



#contact h3 {
  display: block;
  font-size: 30px;
  font-weight: 300;
  margin-bottom: 10px;
  text-align: center;
}

#contact h4 {
  margin: 5px 0 15px;
  display: block;
  font-size: 16px;
  font-weight: 400;
  text-align: center;
  color: #fcb424;
  
}

fieldset {
  border: medium none !important;
  margin: 0 0 10px;
  min-width: 100%;
  padding: 0;
  width: 100%;
  
}

#contact input[type="text"],
#contact input[type="email"],
#contact input[type="tel"],
#contact input[type="url"],
#contact textarea {
  width: 75%;
  border: 1px solid #ccc;
  background: #FFF;
  margin: 0 0 5px;
  padding: 10px;
}

#contact input[type="text"]:hover,
#contact input[type="email"]:hover,
#contact input[type="tel"]:hover,
#contact input[type="url"]:hover,
#contact textarea:hover {
  -webkit-transition: border-color 0.3s ease-in-out;
  -moz-transition: border-color 0.3s ease-in-out;
  transition: border-color 0.3s ease-in-out;
  border: 1px solid #aaa;
}

#contact textarea {
  height: 100px;
  max-width: 100%;
  resize: none;
}



#contact button[type="submit"] {

  width: 75%;
  margin: 0 0 5px;
  /*padding: 10px;*/
  font-size: 16px;
  background: #FDD017;

  
  display: inline-block;
  border: 0.1em solid #2b2b2b;
  border-radius: 0.12em;
  box-sizing: border-box;
  text-decoration: none;
  color: #2b2b2b;
  text-align: center;
  transition: all 0.2s;
}
#contact button[type="submit"]:hover {
  color: #2b2b2b;
  background-color: #caa612;
  border: 0.1em solid #FDD017;
  border-radius: 0.12em;
  box-sizing: border-box;

}


.copyright {
  text-align: center;
}

#contact input:focus,
#contact textarea:focus {
  outline: 0;
  border: 1px solid #aaa;
}

::-webkit-input-placeholder {
  color: #888;
}

:-moz-placeholder {
  color: #888;
}

::-moz-placeholder {
  color: #888;
}

:-ms-input-placeholder {
  color: #888;
}


/*Counter Botton*/
.AfterChange{
  background-color: #4CAF50;
color: #ffffff;
border:3px solid #000000;
border-radius:3px;
}

</style>
<script>
var DOCUMENT_ROOT = "<?= getenv('DOCUMENT_ROOT') ?>";
</script>
<script type="text/javascript" src="jquery-1.11.3.js"></script>
<script type="text/javascript" src="callbackjs.js"></script>
</head>
<body>
<form id="contact" action="">
    <div class="container-fluid h-screen" id="index">
        <div class="top-container">
            <div class="top">
                <div class="row">
                    <a href="https://voipiran.io" target="_blank"><img src="https://www.voipiran.io/webphone/public/images/voipiran_logo.png" class="logo"></a>
                </div>
                <div class="row">
                    <img src="images/avatar.png" class="avatar">
                </div>
				<div class="row">
                    <h4><?php echo $promptL2; ?></h4>
                </div>
				<div class="row">
                    
                </div>
            </div>
        </div>
        <div class="bottom-container">
            <div class="bottom">
                <div class="row">
                    <span class="arrow-down"></span>
                </div>
        
                 <div id="note"><?php echo $promptNotice; ?></div>
                <div class="grid grid-cols-1 gap-4 justify-center mx-auto max-w-md" style="margin-top: 15px;">
                    <fieldset>
						<input placeholder="<?php echo $promptYourName; ?>" type="text" name="callerid" required>
					</fieldset>
                </div>  
				<div class="grid grid-cols-1 gap-4 justify-center mx-auto max-w-md">
                    <fieldset>
						<input id="customerNumber" placeholder="<?php echo $promptYourNumber; ?>" type="text" name="customerNumber" required>
					</fieldset>
                </div>

				<div class="grid grid-cols-1 gap-4 justify-center mx-auto max-w-md">
					<fieldset>
					  <button class="btn-call font-yekan" type="submit" name="submit" id="contact-submit"><?php echo $promptSubmit; ?></button>
					</fieldset>
				</div>
				
				<div class="grid grid-cols-1 gap-4 justify-center mx-auto max-w-md">
					<fieldset>
					<input type="hidden" id="pbxDestination" value=<?php echo $pbxDestination; ?> type="text" name="pbxDestination">
					  <input type="hidden" id="direction"  value=<?php echo $direction; ?> name="direction">
					  <input type="hidden" id="pbxOutPrefix"  value=<?php echo $pbxOutPrefix; ?> name="pbxOutPrefix">
					  <input type="hidden" id="trunkTechName"  value=<?php echo $trunkTechName; ?> name="trunkTechName">
					  <input type="hidden" id="promptCalling"  value="<?php echo $promptCalling; ?>" name="promptCalling">
					  <input type="hidden" id="promptInvalidNumber"  value="<?php echo $promptInvalidNumber; ?>" name="promptInvalidNumber">
					  <input type="hidden" id="pbxURL"  value="<?php echo $pbxURL; ?>" name="pbxURL">
					  <input type="hidden" id="callButtonDisableTime"  value="<?php echo $callButtonDisableTime; ?>" name="callButtonDisableTime">
					  <input type="hidden" id="promptSubmit"  value="<?php echo $promptSubmit; ?>" name="promptSubmit">
					  <input type="hidden" id="promptNotice"  value="<?php echo $promptNotice; ?>" name="promptNotice">

					</fieldset>
                </div>                
				
            
            </div>
        </div>
    </div>
</form>
<script>
$( document ).ready(function() {
	calcValues();
	var int = setInterval(calcValues, 1000);
	function calcValues() {
		$('.counter .to')
			.addClass('hide')
			.removeClass('to')
			.addClass('from')
			.removeClass('hide')
			.addClass('n')
			.find('span:not(.shadow)').each(function (i, el) {
			$(el).text(getSec(true));
		});
		$('.counter .from:not(.n)')
			.addClass('hide')
			.addClass('to')
			.removeClass('from')
			.removeClass('hide')
		.find('span:not(.shadow)').each(function (i, el) {
			$(el).text(getSec(false));
		});
		$('.counter .n').removeClass('n');
	}
	function getSec(next) {
		var d = new Date();
		var sec = 60-d.getSeconds();
		if (next) {
			sec--;
			if (sec < 0) {
				sec = 59;
			}
		} else if(sec == 60) {
			sec = 0;
		}
		return (sec < 10 ? '0' + sec : sec);
	}
});

</script>
</body>
</html>
