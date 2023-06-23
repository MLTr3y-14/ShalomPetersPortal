<?php
session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
 
$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];

 
  $classname = $_GET['name'];
  //$teachername = 'Adeboa Tinu';
   $query = "select AdmissionNo, Stu_Full_Name, ID from tbstudentmaster where Stu_Class IN (select ID from tbclassmaster where Class_Name = '$classname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						$counter = 0;
					         if ($num_rows > 0 ) {
								 
								 while ($row = mysql_fetch_array($result)) 
						{
							     $ID[$counter] = $row["ID"];
								 $AdmissionNo[$counter] = $row["AdmissionNo"];
								 $StudentName[$counter] = $row["Stu_Full_Name"];
								 
									  $counter = $counter+1;
									  }
							 
							 
						$classname1[0] = $classname;
					                    $json = array();
										//$json['classname'] = $classname;
							            $json['admissionno'] =  $AdmissionNo;
										$json['studentname'] = $StudentName;
										$json['name'] = $classname1;
								 $encoded = json_encode($json);
								 }
								 else{
							  
							   $json = array();
							        $classname1[0] = $classname;
									$AdmissionNo[0] = "N";
									$StudentName[0] =  "No Student";
										$json['admissionno'] =  $AdmissionNo;
										$json['studentname'] = $StudentName;
										$json['name'] = $classname1;
									
					       $encoded = json_encode($json);
			
						   }
			die($encoded);			
	?>