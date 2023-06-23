<?php
session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	//session_start();
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
	
	
$studentclass = $_GET['name1'];
//$studentclass = "Primary2A" ;
$query = "select ID from tbclassmaster where Class_Name = '$studentclass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$studentclassID = $row["ID"];
							
						}
	              }
$query = "select Stu_Full_Name, AdmissionNo from tbstudentmaster where Stu_Class = '$studentclassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
      $i = 0;
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$StudentName = $row["Stu_Full_Name"];
							$AdmissionNo = $row["AdmissionNo"];
							$Student_Name[$i] = $StudentName;
							$Student_Admission_No[$i] = $AdmissionNo;
	
							$i++;
							
							
						}
	              }
$json_row['studentname'] = $Student_Name;
//$json_row['studentname'] = $Student_Admission_No;
$json_row['admission_number'] = $Student_Admission_No;
$varstudent = json_encode($json_row);



//echo $studentclassID;
die($varstudent);

?>