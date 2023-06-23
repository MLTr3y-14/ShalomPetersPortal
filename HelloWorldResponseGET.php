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
/*
* HelloWorldResponseGET.php
* --------
*
* Print the name that is passed in the
* 'name' $_GET parameter in a sentence
*/



//print "Hello {$_GET['name']}, welcome to the world {$_GET['name2']} of Dojo!\n";
//header('Content-type: text/plain');
$IssueDay = $_GET['name3'];
$IssueMonth = $_GET['name4'];
$IssueYear = $_GET['name5'];
$ReturnDay = $_GET['name6'];
$ReturnMonth = $_GET['name7'];
$ReturnYear = $_GET['name8'];
$BookTitle = $_GET['name2'];
$StudentName = $_GET['name'];
$EmployeeName = $_GET['name'];
$IssDate = $IssueMonth."/".$IssueDay."/".$IssueYear;
$DueDate = $ReturnMonth."/".$ReturnDay."/".$ReturnYear;

$query = "select ID from tbbookmst where Title = '$BookTitle'";
$result = mysql_query($query,$conn);
$dbarray = mysql_fetch_array($result);
$BookId = $dbarray['ID'];

$query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name = '$StudentName'";
$result = mysql_query($query,$conn);
$num_rows = mysql_num_rows($result);
if ($num_rows > 0 ) 
				{
					$dbarray = mysql_fetch_array($result);
                     $StuID = $dbarray['AdmissionNo'];
					 $query = "insert into tbbookissstd(bookID,AdmnNo,IssDate,DueDate) Values ('$BookId','$StuID','$IssDate','$DueDate')";
					 $result = mysql_query($query,$conn);
					 $realname = $StudentName; 
				}else {

$query = "select ID from tbemployeemasters where EmpName = '$EmployeeName'";
$result = mysql_query($query,$conn);
$dbarray = mysql_fetch_array($result);
$EmpID = $dbarray['ID'];
$query = "insert into tbbookissemp(bookID,EmpID,IssDate,DueDate) Values ('$BookId','$EmpID','$IssDate','$DueDate')";
         $result = mysql_query($query,$conn);
		 $realname = $EmployeeName;
				}

$isStatus = '1';
$q = "update tbbookmst set Status = '$isStatus' where Title = '$BookTitle'";
$result = mysql_query($q,$conn);
print "{$BookTitle} successfully issued to {$realname}, book to be returned on {$_GET['name6']}/{$_GET['name7']}/{$_GET['name8']}   \n";
//echo $BookTitle;

?>