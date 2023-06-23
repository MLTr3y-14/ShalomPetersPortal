<?php
session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	//include 'library/dsndatabase.php';
	include 'formatstring.php';
	include 'function.php';
	include 'sms/sms_processor.php';
	global $userNames;
	
	
	$query3 = "select * from tbstudentmaster";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$AdminNO = $row["AdmissionNo"];
				$entrypassword= 'abc123';
				$query = "insert into tbusermaster(UserName,UserPassword) Values ('$AdminNO','$entrypassword')";
				                           $result = mysql_query($query,$conn);
										   echo $AdminNO;
			}
		}
?>
