<?php
session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
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
  
  $SubjID = $_GET['name'];
  $ClassId = $_GET['name2'];
  $ExamId = $_GET['name3'];
  $StuID = $_GET['name4'];
  
 // $SubjID = '22';
  //$ClassId = '1';
 // $ExamId = '1' ;
 // $StuID ='56372-154' ;
  
   
        $subjectName = GetSubjectName($SubjID); 
   
  
										   
					
	

$query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId = '$ExamId' And              SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						 $result = mysql_query($query,$conn);
						 $StudentMark = array();
						   $counter1 = 0;
						   $counter = 0;
						   $Result = array();
						   $EntryID = array();
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
								
											$SetupId = $row["ID"];
											$StudentResultType = $row["ResultType"];
											
											$Result[$counter1] = $StudentResultType;
											$EntryID[$counter1] = $SetupId;
											$counter1 = $counter1 +1;
											 $query2 = "select ID,Marks from tbstudentperformance where class_id ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID' And AdmnNo = '$StuID' And ResultTypeId = '$SetupId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result2 = mysql_query($query2,$conn);
						  
						  
									//$num_rows = mysql_num_rows($result);
									//if ($num_rows > 0 ) {
										//while (
											 $row2 = mysql_fetch_array($result2); 
										//{
											$marks = $row2["Marks"];
											
											$StudentMark[$counter] = $marks;
											$counter = $counter +1;
											
										        //}
									//}
											
										        }
									}
										   
											
											
											
											
											
											$json = array();
											$json['NewStudentMark'] = $StudentMark;
											$json['StudentResultType'] = $Result;
											//$json = array('NewStudentMark'=>$StudentMark);
											//$json = array('StudentResultType'=>$Result);
											 $json['subject'] = $subjectName;
											  $json['subjectid'] = $SubjID;
											   $json['classid'] = $ClassId;
											    $json['examid'] = $ExamId;
												 $json['studentid'] = $StuID;
	                                              $json['entryid'] = $EntryID;
									//encode array $json to JSON string
//echo $json['NewStudentMark'][1];
$encoded = json_encode($json);

//echo $counter;
// echo $StudentMark[1];
//echo $booktitle.$bookid;
 
// send response back to index.html
// and end script execution
die($encoded);

						    