<?PHP

    global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	
	$departmentid = $_GET['name1'];
	
	//$query = "select ID from tbdepartments where DeptName = '$departmentid'";
        //$result = mysql_query($query,$conn);
		//$row = mysql_fetch_array($result);
		//$departmentid = $row["ID"];
		
$query = "select EmpName from tbemployeemasters where EmpDept = '$departmentid'";
      $i = 0;
	 $result = mysql_query($query,$conn);
	 $num_rows = mysql_num_rows($result);
	 if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							
							$EmployeeName = $row["EmpName"];
							$Employee_Name[$i] = $EmployeeName;
	
							$i++;
							
							
						}
	              }
$json_row['employeename'] = $Employee_Name;
$varemployee = json_encode($json_row);



//echo $studentclassID;
die($varemployee);


?>