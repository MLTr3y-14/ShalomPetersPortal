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
	session_start();
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
		
 $employeename = $_GET['name'];
  //$employeename = "Johnson Abuchi";
  
$stmt = $pdo->prepare("SELECT * FROM tbemployeemasters where EmpName = '$employeename' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = $row;



$query = "select Photo,EmpDept,catCode,isTeaching from tbemployeemasters where EmpName = '$employeename' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$employeepic = $row["Photo"];
							$deptID = $row["EmpDept"];
							$catID = $row["catCode"];
							$teachingStatus = $row["isTeaching"];
							if($teachingStatus == 1){
								$teachingStatus = "Yes";
							}else{
							 $teachingStatus = "No";}
							
						}
	             }

$query = "select DeptName from tbdepartments where ID = '$deptID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $row = mysql_fetch_array($result);
	 $departName = $row["DeptName"];
	 
$query = "select cName from tbcategorymaster where ID = '$deptID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $row = mysql_fetch_array($result);
	 $catName = $row["cName"];


$json_row['employeepicture'] = $employeepic;
$json_row['employeedept'] =  $departName;
$json_row['employcategory'] = $catName;
$json_row['employeteachingstatus'] = $teachingStatus;
$varstudentinfo = json_encode($json_row);
die($varstudentinfo);


?>