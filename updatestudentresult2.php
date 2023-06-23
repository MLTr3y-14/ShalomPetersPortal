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
	
	
	
    $marks1 = $_GET['name1'];
	$marks2 = $_GET['name2'];
	$marks3 = $_GET['name3'];
	$marks4 = $_GET['name4'];
	$marks5 = $_GET['name5'];
	$marks6 = $_GET['name6'];
	$marks7 = $_GET['name7'];
	$marks8 = $_GET['name8'];
	$marks9 = $_GET['name9'];
	$marks10 = $_GET['name10'];
	$resultype1 = $_GET['name11'];
	$resultype2 = $_GET['name12'];
	$resultype3 = $_GET['name13'];
	$resultype4 = $_GET['name14'];
	$resultype5 = $_GET['name15'];
	$resultype6 = $_GET['name16'];
	$resultype7 = $_GET['name17'];
	$resultype8 = $_GET['name18'];
	$resultype9 = $_GET['name19'];
	$resultype10 = $_GET['name20'];
	$ResultTypeId1 = $_GET['name21'];
	$ResultTypeId2 = $_GET['name22'];
	$ResultTypeId3 = $_GET['name23'];
	$ResultTypeId4 = $_GET['name24'];
	$ResultTypeId5 = $_GET['name25'];
	$ResultTypeId6 = $_GET['name26'];
	$ResultTypeId7 = $_GET['name27'];
	$ResultTypeId8 = $_GET['name28'];
	$ResultTypeId9 = $_GET['name29'];
	$ResultTypeId10 = $_GET['name30'];
    $SubjID = $_GET['name31'];
    $ClassId = $_GET['name32'];
    $ExamId = $_GET['name33'];
    $StuID = $_GET['name34'];
	
	//$ResultTypeId1= '121';
	 //$StuID = '56372-103';
	 if(isset($ResultTypeId1)) {
	$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId1' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks1' where ResultTypeId ='$ResultTypeId1' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										/* $query = "select ClassId,SubjectId,ExamId from tbclassexamsetup where ID = '$ResultTypeId1'";
										 $result = mysql_query($query,$conn);
	                                 $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
								
											$classid = $row["ClassId"];
											$subjectid = $row["SubjectId"];
											$examid = $row["ExamId"];*/
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId1','$marks1','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										
									            
									                 }
	 }
	 
  if(isset($ResultTypeId2)) {				
 $query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId2' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks2' where ResultTypeId ='$ResultTypeId2' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId2','$marks2','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
		
		             }
		 if(isset($ResultTypeId3)) {
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId3' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks3' where ResultTypeId ='$ResultTypeId3' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										 
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId3','$marks3','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
		
		if(isset($ResultTypeId4)) {
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId4' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks4' where ResultTypeId ='$ResultTypeId4' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId4','$marks4','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
		 if(isset($ResultTypeId5)) {											 
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId5' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks5' where ResultTypeId ='$ResultTypeId5' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId5','$marks5','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
		
		 if(isset($ResultTypeId6)) {
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId6' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks6' where ResultTypeId ='$ResultTypeId6' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId6','$marks6','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
	 if(isset($ResultTypeId7)) {
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId7' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks7' where ResultTypeId ='$ResultTypeId7' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId7','$marks7','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
													 
		 if(isset($ResultTypeId8)) {
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId8' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks8' where ResultTypeId ='$ResultTypeId8' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId8','$marks8','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
	    if(isset($ResultTypeId9)) {
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId9' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks9' where ResultTypeId ='$ResultTypeId9' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId9','$marks9','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
	 if(isset($ResultTypeId10)) {
		$query = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId10' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbstudentperformance set Marks='$marks10' where ResultTypeId ='$ResultTypeId10' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											
											$query = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId10','$marks10','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
										//}
									            }
									                 }
?>
										
										