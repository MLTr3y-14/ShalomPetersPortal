<?php
session_start();
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
$employeedept = $_GET['name1'];


$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];

$query = "select ID from tbdepartments where DeptName = '$employeedept' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
      $result = mysql_query($query,$conn);
	  $row = mysql_fetch_array($result);
	  $deptID = $row["ID"];
      $i = 0;
	  $query = "select EmpName from tbemployeemasters where EmpDept = '$deptID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$employeeName = $row["EmpName"];
							$Emp_Name[$i] = $employeeName;
	
							$i++;
							
							
						}
	              }
$json_row['employeename'] = $Emp_Name;
$varstudent = json_encode($json_row);



//echo $studentclassID;
die($varstudent);

?>