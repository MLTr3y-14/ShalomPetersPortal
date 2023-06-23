<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 */

//echo check_credit()."<br/>";

//echo check_if_sufficient(15);

 function send_sms($sender, $msg, $recipient, $sms_type){
     $user = "tcc"; // this is your SMS username
	$pass = "ghrladmin"; // this is your Bulk SMS  password
	$from = rawurlencode($sender);  //LOOK MAX 11 CHARs. This is the senderID that will appear on the recipients Phone.
	$msg = rawurlencode($msg); // It is important that you use urlencode() here in order to manage special characters.
	$to = preg_replace("/[^0-9,]/", "", $recipient);


	// build HTTP URL and query
	$request =	'user='.$user.
				'&pass='.$pass.
				'&from='.$from.
				'&to='.$to.
				'&msg='.$msg; //initialize the request variable

	$url = 'http://api.budzeg.com/'; //this is the url of the gateway's interface
	//$ch = curl_init(); //initialize curl handle
	//curl_setopt($ch, CURLOPT_URL, $url); //set the url
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
	//curl_setopt($ch, CURLOPT_POST, 1); //set POST method
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $request); //set the POST variables
	//$response = curl_exec($ch); // grab URL and pass it to the browser. Run the whole process and return the response
	//curl_close($ch); //close the curl handle

        if($response == 'sent'){
            // log the sms sent.

            return 'SMS Sent successfully.';

        }else if($response == 'error_gw' ){
            return 'Server is not responding.';
        }else{
			$response = "Not Functional";
			return $response;
		}
	
 }

 function check_credit(){

     $user = "TCC"; // this is your SMS username
	$pass = "tcc_pass"; // this is your Bulk SMS  password
	
	// build HTTP URL and query
	$request =	'user='.$user.
				'&pass='.$pass; //initialize the request variable

	$url = 'http://api.budzeg.com/credit.php'; //this is the url of the gateway's interface
	//$ch = curl_init(); //initialize curl handle
	//curl_setopt($ch, CURLOPT_URL, $url); //set the url
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
	//curl_setopt($ch, CURLOPT_POST, 1); //set POST method
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $request); //set the POST variables
	//$response = curl_exec($ch); // grab URL and pass it to the browser. Run the whole process and return the response
	//curl_close($ch); //close the curl handle

	return $response;


 }

 function check_if_sufficient($num){
     $bal = check_credit();

     if($bal > $num)
        return 'true';
     else
        return 'false';
 }



?>
