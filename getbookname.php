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
  
  $bookid = $_GET['name'];
 // $bookid = 4;
  //print "successfully isuued to {$_GET['name']}, book to be returned on    \n";
//echo $BookTitle;
/* $dsn = "mysql:host=localhost;dbname=skoolnet";
$username = "root";

$password = "tingate200";

try {

    $pdo = new PDO($dsn, $username, $password);

}

catch(PDOException $e) {

    die("Could not connect to the database\n");

}
$stmt = $pdo->prepare("SELECT AdmnNo FROM tbbookissstd where bookID = '$bookid'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varstudent = json_encode($json_row);
//print $varstudent;
die($varstudent);*/
$query = "SELECT Title FROM tbbookmst where ID = '$bookid'";
$result = mysql_query($query,$conn);
$dbarray = mysql_fetch_array($result);
		$booktitle = $dbarray['Title'];
 $query = "SELECT AdmnNo,IssDate FROM tbbookissstd where bookID = '$bookid'";
 $result = mysql_query($query,$conn);
$num_rows = mysql_num_rows($result);
if ($num_rows > 0 ) 
				{
					$dbarray = mysql_fetch_array($result);
					$admnNo = $dbarray['AdmnNo'];
					$issuedate = $dbarray['IssDate'];
					$query = "SELECT Stu_Full_Name FROM tbstudentmaster where AdmissionNo = '$admnNo'";
					$result = mysql_query($query,$conn);
                       $dbarray = mysql_fetch_array($result);
		                  $name = $dbarray['Stu_Full_Name'];
						  
						  }else {
							 $query = "SELECT EmpID,issDate FROM tbbookissemp where bookID = '$bookid'";
						 	 $result = mysql_query($query,$conn);
                                $dbarray = mysql_fetch_array($result);
		                          $empID = $dbarray['EmpID'];
								  $issuedate = $dbarray['issDate'];
								  $query = "SELECT EmpName FROM tbemployeemasters where ID = '$empID'";
					                  $result = mysql_query($query,$conn);
                                        $dbarray = mysql_fetch_array($result);
										$name = $dbarray['EmpName'];
										
						            }
	$json = array();
	$json['name'] = $name;
	$json['title'] = $booktitle;
	$json['issuedate'] = $issuedate;
	//encode array $json to JSON string
$encoded = json_encode($json);
//echo $booktitle.$bookid;
 
// send response back to index.html
// and end script execution
die($encoded);


?>