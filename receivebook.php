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
	$bookname = $_GET['name'];
	 $issuedto = $_GET['name2'];
	$issueddate = $_GET['name3'];
	//$bookname = "System Analysis and Design";
	//$issuedto = "JENLE  OBANSANJO";
	//$issuedto = "STEPHEN BELLO";
	//$_GET['name3'];
	//$_GET['name4'];
	//$_GET['name5'];
	$query = "SELECT ID FROM tbbookmst where Title = '$bookname'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
			$bookid = $dbarray['ID'];
	$isStatus = '0';
	$query = "update tbbookmst set Status = '$isStatus' where ID = '$bookid'";
    $result = mysql_query($query,$conn);
	
	$query = "SELECT AdmissionNo FROM tbstudentmaster where Stu_Full_Name = '$issuedto'";
       $result = mysql_query($query,$conn);
           $num_rows = mysql_num_rows($result);
              if ($num_rows > 0 ) 
				{
					$dbarray = mysql_fetch_array($result);
					$studentid = $dbarray['AdmissionNo'];
					$query = "SELECT ID from tbbookissstd where bookID = '$bookid' and AdmnNo = '$studentid'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
			                 $issuedbookid = $dbarray['ID'];
							 $query = "Delete From tbbookissstd where ID = '$issuedbookid'";
								$result = mysql_query($query,$conn);
				   }
				    else{
						$query = "SELECT ID FROM tbemployeemasters where EmpName = '$issuedto'";
						$result = mysql_query($query,$conn);
	                         $dbarray = mysql_fetch_array($result);
			                    $employeeid = $dbarray['ID'];
								 $query = "SELECT ID From tbbookissemp where bookID = '$bookid' and EmpID = '$employeeid'";
					                $result = mysql_query($query,$conn);
									  $dbarray = mysql_fetch_array($result);
			                    $issuedbookid = $dbarray['ID'];
								$query = "Delete From tbbookissemp where ID = '$issuedbookid'";
								$result = mysql_query($query,$conn);
					}
	//print "{$bookid} successfully issued to {$studentid}, book {$issuedto} to {$employeeid} be returned {$issuedbookid} on {$_GET['name1']}/{$_GET['name2']}/{$_GET['name']}   \n";
					        
		
	?>