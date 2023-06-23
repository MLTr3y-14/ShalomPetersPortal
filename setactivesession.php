<?php
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	session_start();
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	
	
	  $OptSession = $_POST['OptSession'];
	  
	         $query = "select ID from session where Status = '1'";
											    $result = mysql_query($query,$conn);
												$row = mysql_fetch_array($result);
												$SessionID = $row["ID"];
												
				$query = "update session set Status = '0' where ID = '$SessionID'";
				                    $result = mysql_query($query,$conn);
												
	  
	        $query = "update session set Status = '1' where ID = '$OptSession'";
				                    $result = mysql_query($query,$conn);
									
									
									

?>