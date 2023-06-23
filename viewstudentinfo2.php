<?php
session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'library/dsndatabase.php';
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
  
 $studentname = $_GET['name'];
//$studentname = "Tochi Abuchi Philip";



  
$stmt = $pdo->prepare("SELECT * FROM tbstudentmaster where Stu_Full_Name = '$studentname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = $row;

$query = "select Stu_Regist_No from tbstudentmaster where Stu_Full_Name = '$studentname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$RegNo = $row["Stu_Regist_No"];
							
						}
	              }
$stmt = $pdo->prepare("SELECT * FROM tbstudentdetail where Stu_Regist_No = '$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$studentdetails= array() ;
$studentdetails = $row;

/*$query = "select StudentPhoto from tbregistration where ID = '$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$studentpic = $row["StudentPhoto"];
							
						}
	              }*/
$query = "select * from tbstudentmaster where Stu_Regist_No = '$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $row = mysql_fetch_array($result);
	 $classID = $row["Stu_Class"];
	 $studentpic = $row["Stu_Photo"];
$query = "select Class_Name from tbclassmaster where ID = '$classID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
     $result = mysql_query($query,$conn);
	 $row = mysql_fetch_array($result);
	 $className = $row["Class_Name"];


$json_row['studentpicture'] = $studentpic;
$json_row['studentotherdetails'] = $studentdetails;
$json_row['studentclass'] = $className;
$varstudentinfo = json_encode($json_row);
die($varstudentinfo);


?>