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
  
  
  
  $classid = $_GET['name1'];
  $subjectid = $_GET['name2'];
  $lectureNo = $_GET['name3'];
  $lectureday = $_GET['name4'];
  
  $query = "select Class_Name from tbclassmaster where ID ='$classid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						 $result = mysql_query($query,$conn);
                           $row = mysql_fetch_array($result);
						   $ClassName = $row["Class_Name"];
    $query = "select Subj_name from tbsubjectmaster where ID ='$subjectid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						 $result = mysql_query($query,$conn);
                           $row = mysql_fetch_array($result);
						   $SubjectName = $row["Subj_name"];
						   
		$counter = 0;				   
	$query = "select Stu_Full_Name from tbstudentmaster where Stu_Class = '$classid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                                    $result = mysql_query($query,$conn);
						                 $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
								
											$studentname = $row["Stu_Full_Name"];
											$StudentName[$counter] = $studentname;
											  $counter = $counter + 1;
										            }
									                         }
  $query = "select EmpId from tbclassteachersubj where ClassId ='$classid' and SubjectId ='$subjectid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						 $result = mysql_query($query,$conn);
                           $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
						           $empid = $row["EmpId"];
								    $query = "select EmpName from tbemployeemasters where ID ='$empid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						 $result = mysql_query($query,$conn);
                           $row = mysql_fetch_array($result);
						   $empname = $row["EmpName"];
										      }
									               }
											else{
												$empname = "Not Assigned Yet";
											}
						   

                                   $json = array();
								    $json['ClassName'] = $ClassName;
									$json['SubjectName'] = $SubjectName;
									$json['lectureNo'] = $lectureNo;
									$json['StudentName'] = $StudentName;
									$json['EmployeeName'] = $empname;
									$json['LectureDay'] = $lectureday;
									$json['empid'] = $empid;
				
				
		$encoded = json_encode($json);
		die($encoded);
  
  
  
  
  
  ?>