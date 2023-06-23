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

	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	
	$departmentname = $_POST['employeevalue'];
	$attDate = $_POST['classdate'];
	$query = "select ID from tbdepartments where DeptName = '$departmentname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                          $result = mysql_query($query,$conn);
						   $row = mysql_fetch_array($result);
						   $departmentid = $row["ID"];
		$i = 0;
		 $query2 = "select EmpName from tbemployeemasters where EmpDept = '$departmentid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		 $result2 = mysql_query($query2,$conn);
	       $num_rows2 = mysql_num_rows($result2);
	         if ($num_rows2 > 0 ) {
		       while ($row2 = mysql_fetch_array($result2))
				{
				     $i++;
				   $EmployeeName = $row2["EmpName"];
				   
				    if(isset( $_POST['employeename'.$i]))
			          {        
					           
				                 $employeename = $_POST['employeename'.$i];
								   
				                    $AttStatus = 'P';
				                $query = "select ID from tbemployeemasters where EmpName = '$EmployeeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                                    $result = mysql_query($query,$conn);
						                 $row = mysql_fetch_array($result);
						                   $EmployeeID = $row["ID"];
						   
			                     $query = "select ID from tbattendanceemployee where ID = '$EmployeeID' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														$result = mysql_query($query,$conn);
	                                                       $num_rows = mysql_num_rows($result);
	                                                         if ($num_rows > 0 ) {
														  $query = "update tbattendanceemployee set Status ='$AttStatus' where EmpId = '$EmployeeID' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														  $result = mysql_query($query,$conn);
															        }
															     else {			   
				
				$q = "Insert into tbattendanceemployee(EmpId,Att_date,Status,Session_ID,Term_ID) Values ('$EmployeeID','$attDate','$AttStatus','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
			    }
			}
			 else{
				 $AttStatus = 'A';
				                $query = "select ID from tbemployeemasters where EmpName = '$EmployeeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                                    $result = mysql_query($query,$conn);
						                 $row = mysql_fetch_array($result);
						                   $EmployeeID = $row["ID"];
						   
			                     $query = "select ID from tbattendanceemployee where ID = '$EmployeeID' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														$result = mysql_query($query,$conn);
	                                                       $num_rows = mysql_num_rows($result);
	                                                         if ($num_rows > 0 ) {
														  $query = "update tbattendanceemployee set Status ='$AttStatus' where EmpId = '$EmployeeID' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														  $result = mysql_query($query,$conn);
															        }
															     else {			   
				
				$q = "Insert into tbattendanceemployee(EmpId,Att_date,Status,Session_ID,Term_ID) Values ('$EmployeeID','$attDate','$AttStatus','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
			                    }
			                 }
					 		 
					  }
					 
	              }
	   
?>   