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
	
	$class = $_POST['OptClass'];
	 $SubjectId = $_POST['subjectid'];
	  $StudentNamelength = $_POST['studentnamelength'];
	  $AttendanceDate = $_POST['classdate'];
	  $LectureNo = $_POST['lectureno'];
	   //$TeacherName = $_POST['teachername2'];
	   if(isset( $_POST['teachername2']))
			          { 
					  if($_POST['teachername2'] == "Not Assigned Yet" ){
						  $teacherid =0000;
					  }
					  else{
					  $TeacherName = $_POST['teachername2'];
					   $query = "select ID from tbemployeemasters where EmpName = '$TeacherName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                          $result = mysql_query($query,$conn);
						   $row = mysql_fetch_array($result);
						   $teacherid = $row["ID"];
					             }
					  }
					 else{
					         $teacherid =000;   
							 }
	   
	  // $AttendanceDate= 12;
	   
	
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
						   
			                     $query = "select ID from tblectureattendance where AdmnNo = '$StudAdmNo' and AttendanceDate ='$AttendanceDate' and Lecture ='$LectureNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														$result = mysql_query($query,$conn);
	                                                       $num_rows = mysql_num_rows($result);
	                                                         if ($num_rows > 0 ) {
														  $query = "update tblectureattendance set Status ='$AttStatus' where AdmnNo = '$StudAdmNo' and AttendanceDate ='$AttendanceDate' and Lecture ='$LectureNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														  $result = mysql_query($query,$conn);
															        }
															     else {			   
				
				$q = "Insert into tblectureattendance(AttendanceDate,AdmnNo,SubjectId,Lecture,Status,teacherid,classid,,Session_ID,Term_ID) Values ('$AttendanceDate','$StudAdmNo','$SubjectId','$LectureNo','$AttStatus','$teacherid','$classid','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
			    }
			}
			 else{
				 $AttStatus = 'A';
				                $query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name = '$StudentName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                                    $result = mysql_query($query,$conn);
						                 $row = mysql_fetch_array($result);
						                   $StudAdmNo = $row["AdmissionNo"];
						   
			                     $query = "select ID from tblectureattendance where AdmnNo = '$StudAdmNo' and AttendanceDate ='$AttendanceDate' and Lecture ='$LectureNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														$result = mysql_query($query,$conn);
	                                                       $num_rows = mysql_num_rows($result);
	                                                         if ($num_rows > 0 ) {
														  $query = "update tblectureattendance set Status ='$AttStatus' where AdmnNo = '$StudAdmNo' and AttendanceDate ='$AttendanceDate' and Lecture ='$LectureNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														  $result = mysql_query($query,$conn);
															        }
															     else {			   
				
				$q = "Insert into tblectureattendance(AttendanceDate,AdmnNo,SubjectId,Lecture,Status,teacherid,classid,Session_ID,Term_ID) Values ('$AttendanceDate','$StudAdmNo','$SubjectId','$LectureNo','$AttStatus','$teacherid','$classid','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
			                    }
			                 }
					 		 
					  }
					 
	              }
	   //$StudAdmNo = 1234;
	   $json = array();
										$json['attendancedate'] = $AttendanceDate;
							            $json['admnNo'] = $studentname;
										//$json['subjectid'] = $TeacheerName;
										$json['teacherid'] = $TeacherName;
										$encoded = json_encode($json);
										die($encoded);
?>   
	  