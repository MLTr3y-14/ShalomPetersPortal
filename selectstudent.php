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
	
	
	

$studentclass = $_GET['name'];
//$studentclass = "Primary2A" ;
//$studentname = $_GET['name2'];
//$studentname = "ALAO  JIDE";
/*$query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name = '$studentname' ";
                          $result = mysql_query($query,$conn);
						   $row = mysql_fetch_array($result);
						   $AdmnNo = $row["AdmissionNo"];*/

$query = "select ID from tbclassmaster where Class_Name = '$studentclass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$studentclassID = $row["ID"];
							
						}
	              }
$query = "select ChargeName,Amount from tbclasscharges where ClassID = '$studentclassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ChargeName";
      $i = 0;
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$ChargeName = $row["ChargeName"];
							$Amount = $row["Amount"];
							$Charge_array[$i] = $ChargeName;
							$Amount_array[$i] = $Amount;
							
							/*$query2 = "select ID from tbchargemaster where ChargeName = '$ChargeName'";
							$result2 = mysql_query($query2,$conn);
							$row2 = mysql_fetch_array($result2);
							$ChargeID = $row2["ID"];
							$query3 = "select Balance,PaidAmount from tbfeepayment where ChargeID = '$ChargeID' and ReceiptNo = '$AdmnNo '";
							$result3 = mysql_query($query3,$conn);
							$row3 = mysql_fetch_array($result3);
							$balance = $row3["Balance"];
							$paidamount = $row3["PaidAmount"];*/
							$SBalance_array[$i] = 0;
							$SPayment_array[$i] = 0;
							$i++;
						}
	              }

$json_row['studentclass'] = $studentclass;
$json_row['ChargeName'] = $Charge_array;
$json_row['Amount'] = $Amount_array;
$json_row['StudentPayment'] = $SPayment_array;
$json_row['StudentBalance'] = $SBalance_array;
$varstudent = json_encode($json_row);



//echo $row;
die($varstudent);

?>