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
	
       $SessionAvailable = 'false';
		$fromdate = $_POST['fromdate'];
		$todate = $_POST['todate'];
		$SessionName = $_POST['NewSessionName'];
		
		 $query = "select SessionName,FromDate from session where SessionName = '$SessionName' or FromDate = '$fromdate'";
						   $result = mysql_query($query,$conn);
	                                      $num_rows = mysql_num_rows($result);
	                                                if ($num_rows > 0 ) {
														$row = mysql_fetch_array($result);
						                                     $Name = $row["SessionName"];
															 $SessionAvailable = 'true';
													}
													else{
								$query = "insert into session(SessionName,FromDate,ToDate) Values ('$SessionName','$fromdate','$todate')";
											    $result = mysql_query($query,$conn);
													}
		
$json['SessionAvailable'] = $SessionAvailable;		
$encoded = json_encode($json);
die($encoded);
?>