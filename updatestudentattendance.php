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
	
	
	
	
	 $class = $_POST['classvalue'];
	 //$class = $_POST['selectclass2'];
	  $namelength = $_POST['studentnamelength'];
	  $attDate = $_POST['classdate'];
	  $query = "select ID from tbclassmaster where Class_Name = '$class' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                          $result = mysql_query($query,$conn);
						   $row = mysql_fetch_array($result);
						   $classid = $row["ID"];
		$i = 0;
		 $query2 = "select Stu_Full_Name from tbstudentmaster where Stu_Class = '$classid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		 $result2 = mysql_query($query2,$conn);
	       $num_rows2 = mysql_num_rows($result2);
	         if ($num_rows2 > 0 ) {
		       while ($row2 = mysql_fetch_array($result2))
				{
				     $i++;
				   $StudentName = $row2["Stu_Full_Name"];
				  /* $query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name = '$StudentName' ";
                                    $result = mysql_query($query,$conn);
						                 $row = mysql_fetch_array($result);
						                   $StudAdmNo = $row["AdmissionNo"];
						   
			                     $query = "select ID from tbattendancestudent where AdmnNo = '$StudAdmNo' and Att_date ='$attDate'";
												$result = mysql_query($query,$conn);
												$num_rows = mysql_num_rows($result);
												 if ($num_rows > 0 ) {
													      $attendanceStatus = 1;
												             }*/
	
	   //for( $i = 1; $i <= $namelength; $i++ ){
		   //if($StudentName == $studentname){
		             if(isset( $_POST['studentname'.$i]))
			          {        
					           
				                 $studentname = $_POST['studentname'.$i];
								   
				                    $AttStatus = 'P';
				                $query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name = '$StudentName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                                    $result = mysql_query($query,$conn);
						                 $row = mysql_fetch_array($result);
						                   $StudAdmNo = $row["AdmissionNo"];
						   
			                     $query = "select ID from tbattendancestudent where AdmnNo = '$StudAdmNo' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														$result = mysql_query($query,$conn);
	                                                       $num_rows = mysql_num_rows($result);
	                                                         if ($num_rows > 0 ) {
														  $query = "update tbattendancestudent set Status ='$AttStatus' where AdmnNo = '$StudAdmNo' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														  $result = mysql_query($query,$conn);
															        }
															     else {			   
				
				$q = "Insert into tbattendancestudent(AdmnNo,Att_date,Status,Session_ID,Term_ID) Values ('$StudAdmNo','$attDate','$AttStatus','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
			    }
			}
			 else{
				 $AttStatus = 'A';
				                $query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name = '$StudentName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
                                    $result = mysql_query($query,$conn);
						                 $row = mysql_fetch_array($result);
						                   $StudAdmNo = $row["AdmissionNo"];
						   
			                     $query = "select ID from tbattendancestudent where AdmnNo = '$StudAdmNo' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
														$result = mysql_query($query,$conn);
	                                                       $num_rows = mysql_num_rows($result);
	                                                         if ($num_rows > 0 ) {
														  $query = "update tbattendancestudent set Status ='$AttStatus' where AdmnNo = '$StudAdmNo' and Att_date ='$attDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
														  $result = mysql_query($query,$conn);
															        }
															     else {			   
				
				$q = "Insert into tbattendancestudent(AdmnNo,Att_date,Status,Session_ID,Term_ID) Values ('$StudAdmNo','$attDate','$AttStatus','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
			                    }
			                 }
					 		 
					  }
					 
	              }
	   
?>   