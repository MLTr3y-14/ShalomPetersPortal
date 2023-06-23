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
  
 $teachername = $_GET['name2'];
  //$teachername = 'Demola';
 $class2 = $_GET['name'];
  //$class = 'Primary2B';
  $subject2 = $_GET['name1'];
  //$subject = 'English';
  
  $query = "select ID from tbclassmaster where Class_Name ='$class2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						   $classid2= $dbarray['ID'];
  $query = "select ID from tbsubjectmaster where Subj_name ='$subject2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
        $result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						   $subjectid2= $dbarray['ID'];
   $query = "select ID from tbemployeemasters where EmpName ='$teachername' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
           $result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						   $teacherid2= $dbarray['ID'];
  
  $query = "Insert into tbclassteachersubj(EmpId,ClassId,SubjectId,Session_ID,Term_ID) Values ('$teacherid2','$classid2','$subjectid2','$Session_ID','$Term_ID')";
  $result = mysql_query($query,$conn);
  
   
   $query = "select ClassId, SubjectId from tbclassteachersubj where EmpId IN (select ID from tbemployeemasters where EmpName = '$teachername') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						$counter = 0;
					         if ($num_rows > 0 ) {
								 
								 while ($row = mysql_fetch_array($result)) 
						{
							     $teacherid2[$counter] = $row["ID"];
								 $classid[$counter] = $row["ClassId"];
								 $subjectid[$counter] = $row["SubjectId"];
								 	 $counter = $counter+1;
									  }
											 //echo $classname[0];
					                  for($i=0; $i<$counter; $i++)
									  {   
									      //$classid = $classname[$i];
										  $query = "select Class_Name from tbclassmaster where ID ='$classid[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										  $result = mysql_query($query,$conn);
										  $dbarray = mysql_fetch_array($result);
										  $classname[$i] = $dbarray['Class_Name'];
										  $query = "select Subj_name from tbsubjectmaster where ID ='$subjectid[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										  $result = mysql_query($query,$conn);
										  $dbarray = mysql_fetch_array($result);
										  $subjectname[$i] = $dbarray['Subj_name'];
										   
									  }
										
							            $Teachername[0] = $teachername;
					                    $json = array();
										$json['classname'] = $classname;
							            $json['subjectname'] = $subjectname;
										$json['teachername'] = $Teachername;
										$json['teacherid2'] = $teacherid2;
									
					       $encoded = json_encode($json);
					      
						   }
						   else{
							   $Teachername[0] =  $teachername;
							    $teacherid2[0] = '0';
							   $json = array();
							        $classname[0] = "Not Available (Click here to add Class and Subject)";
										$json['classname'] = $classname;
							            $json['subjectname'] = $classname;
										$json['teachername'] = $Teachername;
										$json['teacherid2'] = $teacherid2;
									
					       $encoded = json_encode($json);
			
						   }
		die($encoded);
	                                 
	
?>