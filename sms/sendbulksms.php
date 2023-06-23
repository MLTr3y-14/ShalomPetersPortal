<?php


function SendSMS( $from, $to, $msg ){
	
	$user = "edmins"; // this is your nuObjects Bulk SMS  usrename
	$pass = "edmins_pass"; // this is your nuObjects Bulk SMS  password
	$from = rawurlencode($from);  //LOOK MAX 11 CHARs. This is the senderID that will appear on the recipients Phone. 
	$msg = rawurlencode($msg); // It is important that you use urlencode() here in orde to manage special characters.
	$to = preg_replace("/[^0-9,]/", "", $to);
	
	
	// build HTTP URL and query
	$request =	'user='.$user.
				'&pass='.$pass.
				'&from='.$from.
				'&to='.$to.
				'&msg='.$msg; //initialize the request variable
				
	$url = 'http://www.nuobjects.com/nusms/'; //this is the url of the gateway's interface
	$ch = curl_init(); //initialize curl handle 
	curl_setopt($ch, CURLOPT_URL, $url); //set the url
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable 
	curl_setopt($ch, CURLOPT_POST, 1); //set POST method 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request); //set the POST variables
	$response = curl_exec($ch); // grab URL and pass it to the browser. Run the whole process and return the response
	curl_close($ch); //close the curl handle

	return $response;
		
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Send Bulk SMS</title>
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 18px;
}
.style2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FF0000;
}
.style4 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #0000FF;
}
.style5 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table style="border:solid 1px #000000" width="483" height="409" border="0" align="center">
  <tr>
    <td height="54"><div align="center"><span class="style1">SEND BULK SMS</span></div></td>
  </tr>
  <tr>
    <td valign="top">
    
    <br />
    
    <?php
	
	if (!$_POST){
	
	?>
    <form id="form1" name="form1" method="post" action="<?=$_SERVER['PHP_SELF'] ?>">
    <table width="100%" border="0" cellpadding="5" cellspacing="5">
      <tr>
        <td width="19%">From</td>
        <td width="81%">
          <input type="text" name="from" id="from" />
        </td>
      </tr>
      <tr>
        <td>To</td>
        <td><label>
          <input type="text" name="to" id="to" />
        </label></td>
      </tr>
      <tr>
        <td>Message</td>
        <td><label>
          <textarea name="msg" id="msg" cols="25" rows="6"></textarea>
        </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input type="submit" name="Send SMS" id="Send SMS" value="Send SMS" />
        </label></td>
      </tr>
    </table>
    
    </form>
    
    <?php
	
	} else { 
		
		$to = $_POST['to'];
	
		if (get_magic_quotes_gpc()) {
			$msg = stripslashes($_POST['msg']); //this is the message that we want to send
			$from = stripslashes($_POST['from']);//this is our sender 
		} else {
			$msg = $_POST['msg']; //this is the message that we want to send
			$from = $_POST['from'];//this is our sender 
		}
	
	$SendSMS =  SendSMS( $from, $to, $msg );
	
	?>
    <br />
    
    <?php
		if ($SendSMS == 'sent'){	
	?>
    
    <table width="100%" border="0" align="center">
      <tr>
        <td><div align="center" class="style4">Message Sent</div></td>
      </tr>
      <tr>
        <td><p>Your message(s) has been sent.</p>
          <p><a href="<?=$_SERVER['PHP_SELF'] ?>">Send SMS Now!</a></p>
          <p>&nbsp;</p></td>
      </tr>
    </table>
    
    <?php
		}else{	
	?>
    
    <table width="100%" border="0" align="center">
      <tr>
        <td><div align="center" class="style2 ">Message NOT Sent</div></td>
      </tr>
      <tr>
        <td><p>&nbsp;</p>
          <p>There was a problem sending your messages. Please try again later. </p>
          <p>The error messages from the server is <span class="style5">[ <?php echo $SendSMS ?> ]</span></p>
          <p><a href="<?=$_SERVER['PHP_SELF'] ?>">Send SMS Now! </a></p>
          <p>&nbsp;</p></td>
      </tr>
    </table>
    
    <?php
	
	}
	}
	
	?>
    </td>
  </tr>
</table>
</body>
</html>
