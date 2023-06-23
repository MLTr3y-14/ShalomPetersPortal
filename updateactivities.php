<?PHP

global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';


$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];

	
	
$studentname = $_POST['studentname'];
//$studentname = 'YOBO KEMI';
 $query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name ='$studentname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
						$result = mysql_query($query,$conn);
						$row = mysql_fetch_array($result);
						$AdmissionNo = $row["AdmissionNo"];
						
$activitydate = $_POST['classdate'];
//$activitydate = '2012-12-03';
//$status = '1';
$activityvalue2 = $_POST['activity21'];
if(isset($_POST['activity21']) &&
   $_POST['activity21'] == 'activity21')
{
    $activityvalue21= $_POST['activity21'];
	//$activityvalue21 = 'activity21';
	$query = "select ID from tbdailyactivities where DailyActivities ='$activityvalue21' and AdmissionNo ='$AdmissionNo' and Date ='$activitydate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbdailyactivities set Status='1' where DailyActivities ='$activityvalue21' and AdmissionNo ='$AdmissionNo' and Date ='$activitydate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											$query = "Insert into tbdailyactivities(DailyActivities,AdmissionNo,Status,Date,Session_ID,Term_ID) Values ('$activityvalue21','$AdmissionNo','1','$activitydate','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
				
			
					}
                        
}else{
	 $activityvalue21= 'activity21';
	$query = "select ID from tbdailyactivities where DailyActivities ='$activityvalue21' and AdmissionNo ='$AdmissionNo' and Date ='$activitydate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result = mysql_query($query,$conn);
	          $num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						$query = "Update tbdailyactivities set Status='0' where DailyActivities ='$activityvalue21' and AdmissionNo ='$AdmissionNo' and Date ='$activitydate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result = mysql_query($query,$conn);
									}
									else{
										 
										
											$query = "Insert into tbdailyactivities(DailyActivities,AdmissionNo,Status,Date,Session_ID,Term_ID) Values ('$activityvalue21','$AdmissionNo','0','$activitydate','$Session_ID','$Term_ID')";
										
										$result = mysql_query($query,$conn);
				
			
					}
	
							
			
}


if(isset($_POST['activity22']) &&
   $_POST['activity22'] == 'activity22')
{
    $activityvalue22= $_POST['activity22'];
}
else{
   
    $activityvalue22= 'activity22';
}

		//$query = "Update tb_status set date='$entrydate' where status = '2'";
                            //$result = mysql_query($query);
				 			  
	                                $json = array();
										$json['studentname'] = $studentname;
										$json['activitydate'] = $activityvalue2;
										$json['activitystatus'] = 'Update Successful';
										
							$encoded = json_encode($json);
						die($encoded);
?>