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
	
	
	  $OptTerm = $_POST['Classterm'];
	  
	         $query = "select ID from section where Active = '1'";
											    $result = mysql_query($query,$conn);
												$row = mysql_fetch_array($result);
												$termID = $row["ID"];
												
				$query = "update section set Active = '0' where ID = '$termID'";
				                    $result = mysql_query($query,$conn);
												
	  
	        $query = "update section set Active = '1' where Section = '$OptTerm'";
				                    $result = mysql_query($query,$conn);
									
									
									

?>