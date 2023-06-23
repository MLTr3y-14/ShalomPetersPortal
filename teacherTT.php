<?PHP
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
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Time Table";
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
		$audit=update_Monitory('Login','Teacher',$Page);
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
		$audit=update_Monitory('Login','Administrators',$Page);
	}
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$PageHasError = 0;
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['OptTeacher']))
	{
		$OptTeacher = $_POST['OptTeacher'];
		//$query = "select Max_Lect from tbteacherworkload where EmpID='$OptTeacher' order by Max_Lect desc";
		//$result = mysql_query($query,$conn);
		//$dbarray = mysql_fetch_array($result);
		//$MaxLecAll  = $dbarray['Max_Lect'];
		//$MaxLecAll  = 7;
		//$AvgWidth = 90/ $MaxLecAll;
		
		$i=0;
		$query3 = "select Distinct SubjectID from tbclassteachersubj where EmpId = '$OptTeacher'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$SubjID = $row["SubjectID"];
				$arr_Subj[$i]=$SubjID;
				$i = $i+1;
				
			}
		}
		$arr_Mon_Subj = "";
		$arr_Tue_Subj = "";
		$arr_Wed_Subj = "";
		$arr_Thur_Subj = "";
		$arr_Fri_subj = "";
		$arr_Sat_Subj = "";					
		
		$MCount = 0;
		$TuCount = 0;
		$WCount = 0;
		$ThCount = 0;
		$FCount = 0;
		$SCount = 0;
		$query1 = "select Distinct ClassID from tbclassteachersubj where EmpId = '$OptTeacher'";
		$result1 = mysql_query($query1,$conn);
		$num_rows1 = mysql_num_rows($result1);
		if ($num_rows1 > 0 ) {
			while ($row1 = mysql_fetch_array($result1)) 
			{
				$ClassID = $row1["ClassID"];
				//echo $ClassID."<br>";
				$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' and WeekDay = 'Monday'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$n = 0;
						while(isset($arr_Subj[$n])){
							if($arr_Subj[$n] == $row["SubjID"]){
								$arr_Mon_Subj[$MCount][1]= $row["LecNo"];
								$arr_Mon_Subj[$MCount][2] = $row["SubjID"];
								$arr_Mon_Subj[$MCount][3] = $ClassID;
								$MCount = $MCount+1;
							}
							$n = $n+1;
						}
					}
				}
						
				$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Tuesday'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$n = 0;
						while(isset($arr_Subj[$n])){
							if($arr_Subj[$n] == $row["SubjID"]){
								$arr_Tue_Subj[$TuCount][1]= $row["LecNo"];
								$arr_Tue_Subj[$TuCount][2] = $row["SubjID"];
								$arr_Tue_Subj[$TuCount][3] = $ClassID;
								$TuCount = $TuCount+1;
							}
							$n = $n+1;
						}
					}
				}
				$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Wednesday'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$n = 0;
						while(isset($arr_Subj[$n])){
							if($arr_Subj[$n] == $row["SubjID"]){
								$arr_Wed_Subj[$WCount][1]= $row["LecNo"];
								$arr_Wed_Subj[$WCount][2] = $row["SubjID"];
								$arr_Wed_Subj[$WCount][3] = $ClassID;
								$WCount = $WCount+1;
							}
							$n = $n+1;
						}
					}
				}
				$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Thurday'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$n = 0;
						while(isset($arr_Subj[$n])){
							if($arr_Subj[$n] == $row["SubjID"]){
								$arr_Thur_Subj[$ThCount][1]= $row["LecNo"];
								$arr_Thur_Subj[$ThCount][2] = $row["SubjID"];
								$arr_Thur_Subj[$ThCount][3] = $ClassID;
								$ThCount = $ThCount+1;
							}
							$n = $n+1;
						}
					}
				}
				$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Friday'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$n = 0;
						while(isset($arr_Subj[$n])){
							if($arr_Subj[$n] == $row["SubjID"]){
								$arr_Fri_subj[$FCount][1] =$row["LecNo"];
								$arr_Fri_subj[$FCount][2] =$row["SubjID"];
								$arr_Fri_subj[$FCount][3] = $ClassID;
								$FCount = $FCount +1;
							}
							$n = $n+1;
						}
					}
				}
				$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Saturday'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$n = 0;
						while(isset($arr_Subj[$n])){
							if($arr_Subj[$n] == $row["SubjID"]){
								$arr_Sat_Subj[$SCount][1]= $row["LecNo"];
								$arr_Sat_Subj[$SCount][2] = $row["SubjID"]; 
								$arr_Sat_Subj[$SCount][3] = $ClassID;
								$SCount = $SCount +1;
							}
							$n = $n+1;
						}
					}
				}
				//sort($arr_Mon_Subj);
				//sort($arr_Tue_Subj);
				//sort($arr_Wed_Subj);
				//sort($arr_Thur_Subj);
				//sort($arr_Fri_subj);
				//sort($arr_Sat_Subj);
			}
		}
	}
	//$d_var=getdate(mktime(0,0,0,12,8,2009));
	//echo $d_var[weekday];
	//$weekday = date("l", mktime(0,0,0,12,9,2009));
	//print($weekday);
	//echo $arr_Tue_Subj[$TuCount][1]; 
	
	if(isset($_POST['LookupAdj']))
	{
		$OptLookupTeacher = $_POST['OptLookupTeacher'];
		$lookup_Dy = $_POST['lookup_Dy'];
		$lookup_Mth = $_POST['lookup_Mth'];
		$lookup_Yr = $_POST['lookup_Yr'];
		$weekday = date("l", mktime(0,0,0,$lookup_Mth,$lookup_Dy,$lookup_Yr));
		
		$query = "select Max_Lect from tbteacherworkload where EmpID='$OptLookupTeacher' order by Max_Lect desc";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['Max_Lect'];
		$AvgWidth = 90/ $MaxLecAll;
		
		$i=0;
		$query3 = "select Distinct SubjectID from tbclassteachersubj where EmpId = '$OptLookupTeacher' And SecID = '$Activeterm'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$SubjID = $row["SubjectID"];
				$arr_Subj[$i]=$SubjID;
				$i = $i+1;
				
			}
		}
		$arr_Mon_Subj = "";
		$arr_Tue_Subj = "";
		$arr_Wed_Subj = "";
		$arr_Thur_Subj = "";
		$arr_Fri_subj = "";
		$arr_Sat_Subj = "";					
		
		$query1 = "select Distinct ClassID from tbclassteachersubj where EmpId = '$OptLookupTeacher' And SecID = '$Activeterm'";
		$result1 = mysql_query($query1,$conn);
		$num_rows1 = mysql_num_rows($result1);
		if ($num_rows1 > 0 ) {
			while ($row1 = mysql_fetch_array($result1)) 
			{
				$ClassID = $row1["ClassID"];
				if($weekday == 'Monday'){
					$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Monday'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$n = 0;
							while(isset($arr_Subj[$n])){
								if($arr_Subj[$n] == $row["SubjID"]){
									$arr_lookup_Subj[$n][1]= $row["LecNo"];
									$arr_lookup_Subj[$n][2] = $row["SubjID"];
								}
								$n = $n+1;
							}
							sort($arr_lookup_Subj);
						}
					}
				}elseif($weekday == 'Tuesday'){
					$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Tuesday'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$n = 0;
							while(isset($arr_Subj[$n])){
								if($arr_Subj[$n] == $row["SubjID"]){
									$arr_lookup_Subj[$n][1]= $row["LecNo"];
									$arr_lookup_Subj[$n][2] = $row["SubjID"];
								}
								$n = $n+1;
							}
							sort($arr_lookup_Subj);
						}
					}
				}elseif($weekday == 'Wednesday'){
					$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Wednesday'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$n = 0;
							while(isset($arr_Subj[$n])){
								if($arr_Subj[$n] == $row["SubjID"]){
									$arr_lookup_Subj[$n][1]= $row["LecNo"];
									$arr_lookup_Subj[$n][2] = $row["SubjID"]; 
								}
								$n = $n+1;
							}
							sort($arr_lookup_Subj);
						}
					}
				}elseif($weekday == 'Thurday'){
					$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Thurday'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$n = 0;
							while(isset($arr_Subj[$n])){
								if($arr_Subj[$n] == $row["SubjID"]){
									$arr_lookup_Subj[$n][1]= $row["LecNo"];
									$arr_lookup_Subj[$n][2] = $row["SubjID"]; 
								}
								$n = $n+1;
							}
							sort($arr_lookup_Subj);
						}
					}
				}elseif($weekday == 'Friday'){
					$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Friday'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$n = 0;
							while(isset($arr_Subj[$n])){
								if($arr_Subj[$n] == $row["SubjID"]){
									$arr_lookup_Subj[$n][1] =$row["LecNo"];
									$arr_lookup_Subj[$n][2] =$row["SubjID"];
								}
								$n = $n+1;
							}
							sort($arr_lookup_Subj);
						}
					}
				}elseif($weekday == 'Saturday'){
					$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Saturday'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$n = 0;
							while(isset($arr_Subj[$n])){
								if($arr_Subj[$n] == $row["SubjID"]){
									$arr_lookup_Subj[$n][1]= $row["LecNo"];
									$arr_lookup_Subj[$n][2] = $row["SubjID"]; 
								}
								$n = $n+1;
							}
							sort($arr_lookup_Subj);
						}
					}
				}
			}
		}
	}
	if(isset($_POST['Dutymaster']))
	{
		$PageHasError = 0;
		$DutyID = $_POST['SelDutyID'];
		$Code = $_POST['SelDutyID'];
		$Duty = $_POST['Duty'];
		$Description = $_POST['Description'];
		
		if(!$_POST['Duty']){
			$errormsg = "<font color = red size = 1>Duty Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Dutymaster'] =="Save"){
				$num_rows = 0;
				$query = "select ID from tbTeachersDuty where TeacherDuty = '$Duty'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Duty you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbTeachersDuty(TeacherDuty,Remarks) Values ('$Duty','$Description')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$Code = "";
					$Duty = "";
					$Description = "";
				}
			}elseif ($_POST['Dutymaster'] =="Update"){
				$query = "select ID from tbTeachersDuty where ID = '$DutyID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$q = "update tbTeachersDuty set TeacherDuty = '$Duty',Remarks = '$Description' where ID = '$DutyID'";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
					
					$Code = "";
					$Duty = "";
					$Description = "";
				}else {
					$errormsg = "<font color = red size = 1>Teacher Duty not found.</font>";
					$PageHasError = 1;
				}
			}
		}
	}
	if(isset($_GET['duty_id']))
	{
		$Code = $_GET['duty_id'];
		$query = "select * from tbTeachersDuty where ID='$Code'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Duty  = $dbarray['TeacherDuty'];
		$Description  = $dbarray['Remarks'];
	}
	if(isset($_POST['Dutymaster_delete']))
	{
		$Total = $_POST['TotalDuty'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkdutyID'.$i]))
			{
				$dutyIDs = $_POST['chkdutyID'.$i];
				$q = "Delete From tbTeachersDuty where ID = '$dutyIDs'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['AddDuty']))
	{
		$PageHasError = 0;
		$optDuty = $_POST['optDuty'];
		$OptTr = $_POST['OptTr'];

		
		$from_Yr = $_POST['from_Yr'];
		$from_Mth = $_POST['from_Mth'];
		$from_Dy = $_POST['from_Dy'];
		if(strlen($from_Dy)==1){
			$_POST['from_Dy'] = '0'.$from_Dy;
		}
		if(strlen($from_Mth)==1){
			$_POST['from_Mth'] = '0'.$from_Mth;
		}
		$FromDate = $_POST['from_Yr']."-".$_POST['from_Mth']."-".$_POST['from_Dy'];
		
		if(!FromDate){
			$errormsg = "<font color = red size = 1>From Date is empty</font>";
			$PageHasError = 1;
		}
		
		$to_Yr = $_POST['to_Yr'];
		$to_Mth = $_POST['to_Mth'];
		$to_Dy = $_POST['to_Dy'];
		if(strlen($to_Dy)==1){
			$_POST['to_Dy'] = '0'.$to_Dy;
		}
		if(strlen($to_Mth)==1){
			$_POST['to_Mth'] = '0'.$to_Mth;
		}
		$ToDate = $_POST['to_Yr']."-".$_POST['to_Mth']."-".$_POST['to_Dy'];
		
		if(!ToDate){
			$errormsg = "<font color = red size = 1>Attedance date is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['optDuty']){
			$errormsg = "<font color = red size = 1>Duty Name not selected.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptTr']){
			$errormsg = "<font color = red size = 1>Teacher not selected.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			$num_rows = 0;
			$query = "select ID from tbassignduty where Dutyid = '$optDuty' And Empid = '$OptTr' And From_Date = '$FromDate' And To_Date = '$ToDate'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) 
			{
				$errormsg = "<font color = red size = 1>The Duty you are trying to assign already exist.</font>";
				$PageHasError = 1;
			}else {
				$q = "Insert into tbassignduty(Dutyid,Empid,From_Date,To_Date) Values ('$optDuty','$OptTr','$FromDate','$ToDate')";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Assign Successfully.</font>";
				
				$optDuty = "";
				$OptTr = "";
				$FromDate = "";
				$ToDate = "";
			}
		}
	}
	if(isset($_POST['DeleteDuty']))
	{
		$PageHasError = 0;
		$duty_id = $_POST['SeldutyID'];
		$optDuty = $_POST['optDuty'];
		$OptTr = $_POST['OptTr'];
		
		$FromDate = $_POST['from_Yr']."-".$_POST['from_Mth']."-".$_POST['from_Dy'];
		$from_Yr = $_POST['from_Yr'];
		$from_Mth = $_POST['from_Mth'];
		$from_Dy = $_POST['from_Dy'];
		if(!FromDate){
			$errormsg = "<font color = red size = 1>From Date is empty</font>";
			$PageHasError = 1;
		}
		
		$ToDate = $_POST['to_Yr']."-".$_POST['to_Mth']."-".$_POST['to_Dy'];
		$to_Yr = $_POST['to_Yr'];
		$to_Mth = $_POST['to_Mth'];
		$to_Dy = $_POST['to_Dy'];
		
		if(!ToDate){
			$errormsg = "<font color = red size = 1>Attedance date is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['optDuty']){
			$errormsg = "<font color = red size = 1>Duty Name not selected.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptTr']){
			$errormsg = "<font color = red size = 1>Teacher not selected.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			$num_rows = 0;
			$query = "select ID from tbassignduty where Dutyid = '$optDuty' And Empid = '$OptTr' And From_Date = '$FromDate' And To_Date = '$ToDate'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) 
			{
				$q = "Delete From tbassignduty where ID = '$duty_id'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Unassigned Successfully.</font>";
				
				$optDuty = "";
				$OptTr = "";
				$FromDate = "";
				$ToDate = "";
			}else {
				$errormsg = "<font color = red size = 1>Teacher duty not found.</font>";
				$PageHasError = 1;
			}
		}
	}
	if(isset($_POST['UpdateDuty']))
	{
		$PageHasError = 0;
		$duty_id = $_POST['SeldutyID'];
		$optDuty = $_POST['optDuty'];
		$OptTr = $_POST['OptTr'];
		
		$FromDate = $_POST['from_Yr']."-".$_POST['from_Mth']."-".$_POST['from_Dy'];
		$from_Yr = $_POST['from_Yr'];
		$from_Mth = $_POST['from_Mth'];
		$from_Dy = $_POST['from_Dy'];
		if($FromDate=="--"){
			$errormsg = "<font color = red size = 1>From Date is empty</font>";
			$PageHasError = 1;
		}
		
		$ToDate = $_POST['to_Yr']."-".$_POST['to_Mth']."-".$_POST['to_Dy'];
		$to_Yr = $_POST['to_Yr'];
		$to_Mth = $_POST['to_Mth'];
		$to_Dy = $_POST['to_Dy'];
		
		if($ToDate=="--"){
			$errormsg = "<font color = red size = 1>To date is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['optDuty']){
			$errormsg = "<font color = red size = 1>Duty Name not selected.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptTr']){
			$errormsg = "<font color = red size = 1>Teacher not selected.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			$num_rows = 0;
			$query = "select ID from tbassignduty where ID = '$duty_id'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) 
			{
				$q = "update tbassignduty set Dutyid='$optDuty',Empid='$OptTr',From_Date='$FromDate',To_Date='$ToDate' where ID = '$duty_id'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$optDuty = "";
				$OptTr = "";
				$FromDate = "";
				$ToDate = "";
			}else {
				$errormsg = "<font color = red size = 1>Teacher duty not found.</font>";
				$PageHasError = 1;
			}
		}
	}
	if(isset($_GET['duty_id']))
	{
		$duty_id = $_GET['duty_id'];
		$query = "select * from tbassignduty where ID='$duty_id'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$optDuty  = $dbarray['Dutyid'];
		$OptTr  = $dbarray['Empid'];
		
		$arrfDate=explode ('-', $dbarray['From_Date']);
		$from_Dy = $arrfDate[2];
		$from_Mth = $arrfDate[1];
		$from_Yr = $arrfDate[0];
		
		$arrtDate=explode ('-', $dbarray['To_Date']);
		$to_Dy = $arrtDate[2];
		$to_Mth = $arrtDate[1];
		$to_Yr = $arrtDate[0];
	}
	if(isset($_POST['PrintTT']))
	{
		$OptTeacher = $_POST['OptTeacher'];
		if(!isset($_POST['OptTeacher']))
		{
			$errormsg = "<font color = red size = 1>Select teacher to view.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=timetable_rpt.php?pg=Teachers Timetable&tch=$OptTeacher\">";
			exit;
		}
	}
	//$MCount = 0;
	//$arr_Mon_Subj[$MCount][1] = "kolade";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager</TITLE>
<META content="Viodvla.com is the official website of the Directorate for Road Traffic Services in Nigeria." name="Short Title">
<META content="Nigeria Centre for Road Traffic" name=AGLS.Function>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/menu.css" type=text/css rel=stylesheet>
<style type="text/css">td img {display: block;}</style>
<style type="text/css">
.a{
margin:0px auto;
position:relative;
width:250px;
}

.b{
overflow:auto;
width:auto;
height:200px;
}
.b2{
overflow:auto;
width:auto;
height:400px;
}
.a thead tr{
position:absolute;
top:0px;
}
.style3 {color: #FFFFFF; font-weight: bold; }
</style>
<script language="JavaScript">
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->
</script>
<SCRIPT 
src="css/jquery-1.2.3.min.js" 
type=text/javascript></SCRIPT>

<SCRIPT 
src="css/menu.js" 
type=text/JavaScript></SCRIPT>
<script language="JavaScript">
<!--
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<script type="text/javascript">
<!--
function clearDefault(el) {
if (el.defaultValue==el.value) el.value = ""
}
// -->
</script>
<style type="text/css">

.ds_box {
	background-color: #FFF;
	border: 1px solid #000;
	position: absolute;
	z-index: 32767;
}

.ds_tbl {
	background-color: #FFF;
}

.ds_head {
	background-color: #333;
	color: #FFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	text-align: center;
	letter-spacing: 2px;
}

.ds_subhead {
	background-color: #CCC;
	color: #000;
	font-size: 12px;
	font-weight: bold;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	width: 32px;
}

.ds_cell {
	background-color: #EEE;
	color: #000;
	font-size: 13px;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	cursor: pointer;
}

.ds_cell:hover {
	background-color: #F3F3F3;
} /* This hover code won't work for IE */

</style>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
<tr><td id="ds_calclass">
</td></tr>
</table>
<script type="text/javascript">
var ds_i_date = new Date();
ds_c_month = ds_i_date.getMonth() + 1;
ds_c_year = ds_i_date.getFullYear();

// Get Element By Id
function ds_getel(id) {
	return document.getElementById(id);
}

// Get the left and the top of the element.
function ds_getleft(el) {
	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}
function ds_gettop(el) {
	var tmp = el.offsetTop;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}

// Output Element
var ds_oe = ds_getel('ds_calclass');
// Container
var ds_ce = ds_getel('ds_conclass');

// Output Buffering
var ds_ob = ''; 
function ds_ob_clean() {
	ds_ob = '';
}
function ds_ob_flush() {
	ds_oe.innerHTML = ds_ob;
	ds_ob_clean();
}
function ds_echo(t) {
	ds_ob += t;
}

var ds_element; // Text Element...

var ds_monthnames = [
'January', 'February', 'March', 'April', 'May', 'June',
'July', 'August', 'September', 'October', 'November', 'December'
]; // You can translate it for your language.

var ds_daynames = [
'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
]; // You can translate it for your language.

// Calendar template
function ds_template_main_above(t) {
	return '<table cellpadding="3" cellspacing="1" class="ds_tbl">'
	     + '<tr>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_py();">&lt;&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_pm();">&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_hi();" colspan="3">[Close]</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_nm();">&gt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_ny();">&gt;&gt;</td>'
		 + '</tr>'
	     + '<tr>'
		 + '<td colspan="7" class="ds_head">' + t + '</td>'
		 + '</tr>'
		 + '<tr>';
}

function ds_template_day_row(t) {
	return '<td class="ds_subhead">' + t + '</td>';
	// Define width in CSS, XHTML 1.0 Strict doesn't have width property for it.
}

function ds_template_new_week() {
	return '</tr><tr>';
}

function ds_template_blank_cell(colspan) {
	return '<td colspan="' + colspan + '"></td>'
}

function ds_template_day(d, m, y) {
	return '<td class="ds_cell" onclick="ds_onclick(' + d + ',' + m + ',' + y + ')">' + d + '</td>';
	// Define width the day row.
}

function ds_template_main_below() {
	return '</tr>'
	     + '</table>';
}

// This one draws calendar...
function ds_draw_calendar(m, y) {
	// First clean the output buffer.
	ds_ob_clean();
	// Here we go, do the header
	ds_echo (ds_template_main_above(ds_monthnames[m - 1] + ' ' + y));
	for (i = 0; i < 7; i ++) {
		ds_echo (ds_template_day_row(ds_daynames[i]));
	}
	// Make a date object.
	var ds_dc_date = new Date();
	ds_dc_date.setMonth(m - 1);
	ds_dc_date.setFullYear(y);
	ds_dc_date.setDate(1);
	if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) {
		days = 31;
	} else if (m == 4 || m == 6 || m == 9 || m == 11) {
		days = 30;
	} else {
		days = (y % 4 == 0) ? 29 : 28;
	}
	var first_day = ds_dc_date.getDay();
	var first_loop = 1;
	// Start the first week
	ds_echo (ds_template_new_week());
	// If sunday is not the first day of the month, make a blank cell...
	if (first_day != 0) {
		ds_echo (ds_template_blank_cell(first_day));
	}
	var j = first_day;
	for (i = 0; i < days; i ++) {
		// Today is sunday, make a new week.
		// If this sunday is the first day of the month,
		// we've made a new row for you already.
		if (j == 0 && !first_loop) {
			// New week!!
			ds_echo (ds_template_new_week());
		}
		// Make a row of that day!
		ds_echo (ds_template_day(i + 1, m, y));
		// This is not first loop anymore...
		first_loop = 0;
		// What is the next day?
		j ++;
		j %= 7;
	}
	// Do the footer
	ds_echo (ds_template_main_below());
	// And let's display..
	ds_ob_flush();
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// A function to show the calendar.
// When user click on the date, it will set the content of t.
function ds_sh(t) {
	// Set the element to set...
	ds_element = t;
	// Make a new date, and set the current month and year.
	var ds_sh_date = new Date();
	ds_c_month = ds_sh_date.getMonth() + 1;
	ds_c_year = ds_sh_date.getFullYear();
	// Draw the calendar
	ds_draw_calendar(ds_c_month, ds_c_year);
	// To change the position properly, we must show it first.
	ds_ce.style.display = '';
	// Move the calendar container!
	the_left = ds_getleft(t);
	the_top = ds_gettop(t) + t.offsetHeight;
	ds_ce.style.left = the_left + 'px';
	ds_ce.style.top = the_top + 'px';
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// Hide the calendar.
function ds_hi() {
	ds_ce.style.display = 'none';
}

// Moves to the next month...
function ds_nm() {
	// Increase the current month.
	ds_c_month ++;
	if (ds_c_month > 12) {
		ds_c_month = 1; 
		ds_c_year++;
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous month...
function ds_pm() {
	ds_c_month = ds_c_month - 1; // Can't use dash-dash here, it will make the page invalid..
	if (ds_c_month < 1) {
		ds_c_month = 12; 
		ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the next year...
function ds_ny() {
	// Increase the current year.
	ds_c_year++;
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous year...
function ds_py() {
	// Decrease the current year.
	ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Format the date to output.
function ds_format_date(d, m, y) {
	// 2 digits month.
	m2 = '00' + m;
	m2 = m2.substr(m2.length - 2);
	// 2 digits day.
	d2 = '00' + d;
	d2 = d2.substr(d2.length - 2);
	// YYYY-MM-DD
	return y + '-' + m2 + '-' + d2;
}

function ds_onclick(d, m, y) {
	ds_hi();
	// Set the value of it, if we can.
	if (typeof(ds_element.value) != 'undefined') {
		ds_element.value = ds_format_date(d, m, y);
	// Maybe we want to set the HTML in it.
	} else if (typeof(ds_element.innerHTML) != 'undefined') {
		ds_element.innerHTML = ds_format_date(d, m, y);
	// I don't know how should we display it, just alert it to user.
	} else {
		alert (ds_format_date(d, m, y));
	}
}
</script>
<SCRIPT type=text/javascript>
<!--
var theForm = document.forms['aspnetForm'];
if (!theForm) {
    theForm = document.aspnetForm;
}
function __doPostBack(eventTarget, eventArgument) {
    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
        theForm.__EVENTTARGET.value = eventTarget;
        theForm.__EVENTARGUMENT.value = eventArgument;
        theForm.submit();
    }
}
// -->
</SCRIPT>
<TABLE style="WIDTH: 100%" background="images/Top_pole.jpg">
<TBODY>
	<TR>
	  <TD height="55px" valign="top">
	  	<TABLE width="1100" border="0" cellPadding=3 cellSpacing=0 bgcolor="#D7CFBE" align="center">
		  <TR>
			<TD width="23%" align="left"><img src="images/skoolnet_logo.gif" width="130" height="39" align="left"></TD>
			<TD width="77%" align="right"><a href="welcome.php?pg=System Setup&mod=admin"> Home</a> | <a href="download/userguide.pdf" target="_blank">Download Userguide</a> | <a href="backup.php?pg=login" target="_blank">Backup System</a> |<a href="Logout.php">Logout </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
		  </TR>
		</TABLE>
	  </TD>
	</TR>
</TBODY>
</TABLE>
<TABLE width="100%" bgcolor="#efe7d4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	 <DIV id=main>
		<DIV id=mainmenu>
			<?PHP include 'topmenu.php'; ?>
		</DIV>
	</DIV>
             </TD>
    </TR>
    <TR>
         <TD>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD>
		<TABLE width="100%" style="WIDTH: 100%">
        <TBODY>
			<TR>
			  <TD width="220" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="858" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Teacher time table") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="teacherTT.php?subpg=Teacher time table">
				<div>
					<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
					<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
					<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
					</div>
					<script type="text/javascript">
					<!--
					var theForm = document.forms['form1'];
					if (!theForm) {
						theForm = document.form1;
					}
					function __doPostBack(eventTarget, eventArgument) {
						if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
							theForm.__EVENTTARGET.value = eventTarget;
							theForm.__EVENTARGUMENT.value = eventArgument;
							theForm.submit();
						}
					}
					// -->
					</script>
					<script type="text/javascript">
					<!--
					function WebForm_OnSubmit() {
					if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
					return true;
					}
					// -->
					</script>
					<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="66%" valign="top"  align="left"><strong>Show Time Table For
					  	 <select name="OptTeacher" onChange="javascript:setTimeout('__doPostBack(\'OptTeacher\',\'\')', 0)">
							<option value="" selected="selected">Select</option>
<?PHP
							$counter = 0;
							if($_SESSION['module'] == "Teacher"){
								$query = "select ID,EmpName from tbemployeemasters where ID = '$Teacher_EmpID' order by EmpName";
							}else{
								$query = "select ID,EmpName from tbemployeemasters where isTeaching = '1' order by EmpName";
							}
							
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$counter = $counter+1;
									$TeacherID = $row["ID"];
									$TeacherName = $row["EmpName"];
									
									if($OptTeacher =="$TeacherID"){
?>
										<option value="<?PHP echo $TeacherID; ?>" selected="selected"><?PHP echo $TeacherName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $TeacherID; ?>"><?PHP echo $TeacherName; ?></option>
<?PHP
									}
								}
							}
?>
						 </select>
					  </strong></TD>
					  <TD width="34%" valign="top"  align="left" ><strong>Current Section: <?PHP echo $Activeterm; ?></strong></TD>
					</TR>
					<TR>
					  <TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
					  		<TABLE width="100%" style="WIDTH: 100%" cellpadding="10">
							<TBODY>
							<TR bgcolor="#00CCFF">
							  <TD width="10%"  align="left" valign="top">&nbsp;</TD>
<?PHP
								for($i=1;$i<=8;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>BREAK</div></TD>";
									}elseif($i<=8){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
									}
								}

?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Monday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=8;$i++)
								{
									if(isset($arr_Mon_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Mon_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&lecno=<?PHP echo $i; ?>&wd=Monday&subj=<?PHP echo $arr_Mon_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo][2])."/".GetClassName($arr_Mon_Subj[$LNo][3]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}else{
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>&nbsp;</TD>";
									}
								}
?>
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Tuesday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=8;$i++)
								{
									if(isset($arr_Tue_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Tue_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&lecno=<?PHP echo $i; ?>&wd=Tuesday&subj=<?PHP echo $arr_Tue_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Tue_Subj[$LNo][2])."/".GetClassName($arr_Tue_Subj[$LNo][3]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}else{
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>&nbsp;</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Wednesday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=8;$i++)
								{
									if(isset($arr_Wed_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Wed_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&lecno=<?PHP echo $i; ?>&wd=Wednesday&subj=<?PHP echo $arr_Wed_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo][2])."/".GetClassName($arr_Wed_Subj[$LNo][3]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}else{
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>&nbsp;</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Thursday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=8;$i++)
								{
									if(isset($arr_Thur_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Thur_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&lecno=<?PHP echo $i; ?>&wd=Thurday&subj=<?PHP echo $arr_Thur_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo][2])."/".GetClassName($arr_Thur_Subj[$LNo][3]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}else{
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>&nbsp;</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Friday</span></TD>
<?PHP
								$LNo = 0;
								for($i=1;$i<=8;$i++)
								{
									if(isset($arr_Fri_subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Fri_subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&lecno=<?PHP echo $i; ?>&wd=Friday&subj=<?PHP echo $$arr_Fri_subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Fri_subj[$LNo][2])."/".GetClassName($arr_Fri_subj[$LNo][3]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}else{
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>&nbsp;</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Saturday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=8;$i++)
								{
									if(isset($arr_Sat_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Sat_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&lecno=<?PHP echo $i; ?>&wd=Saturday&subj=<?PHP echo $arr_Sat_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo][2])."/".GetClassName($arr_Sat_Subj[$LNo][3]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}else{
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>&nbsp;</TD>";
									}
								}
?>
							 
							</TR>
							</TBODY>
							</TABLE>
							</TD>
					</TR>
					<TR>
						<TD colspan="2">
						<div align="center">
						  <input type="hidden" name="workloadID" value="<?PHP echo $workid; ?>">
						  <input type="submit" name="PrintTT" value="Print TimeTable">
						</div>						</TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Look up adjustment") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="teacherTT.php?subpg=Look up adjustment">
					<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="41%" valign="top"  align="left"><strong>Show Time Table For
					  	 <select name="OptLookupTeacher">
							<option value="" selected="selected">Select</option>
<?PHP
							$counter = 0;
							if($_SESSION['module'] == "Teacher"){
								$query = "select ID,EmpName from tbemployeemasters where ID = '$Teacher_EmpID' order by EmpName";
							}else{
								$query = "select ID,EmpName from tbemployeemasters where isTeaching = '1' order by EmpName";
							}
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$counter = $counter+1;
									$TeacherID = $row["ID"];
									$TeacherName = $row["EmpName"];
									
									if($OptLookupTeacher =="$TeacherID"){
?>
										<option value="<?PHP echo $TeacherID; ?>" selected="selected"><?PHP echo $TeacherName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $TeacherID; ?>"><?PHP echo $TeacherName; ?></option>
<?PHP
									}
								}
							}
?>
						 </select>
					  </strong></TD>
					  <TD width="59%" valign="top"  align="left" ><strong>On &nbsp;&nbsp;&nbsp;</strong>
					    <select name="lookup_Dy">
						  <option value="<?PHP echo date('d'); ?>" selected="selected"><?PHP echo date('d'); ?></option>
						  
<?PHP
							echo $Cur_Dy;
							for($i=1; $i<=31; $i++){
								if($lookup_Dy == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						<select name="lookup_Mth">
						   <option value="<?PHP echo date('m'); ?>" selected="selected"><?PHP echo Get_Month_Name(date('m')); ?></option>
<?PHP
								for($i=1; $i<=12; $i++){
									if($lookup_Mth == $i){
										echo "<option value=$i selected='selected'>".Get_Month_Name($i)."</option>";
									}else{
										echo "<option value=$i>".Get_Month_Name($i)."</option>";
									}
								}
?>
						</select>
						<select name="lookup_Yr">
						  <option value="<?PHP echo date('Y'); ?>" selected="selected"><?PHP echo date('Y'); ?></option>
<?PHP
							for($i=2009; $i<=$CurYear; $i++){
								if($lookup_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						</label>
						&nbsp;&nbsp;<?PHP echo $weekday; ?>&nbsp;
					    <input type="submit" name="LookupAdj" value="Look up adjustment">
						</TD>
					</TR>
					<TR>
					  <TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
					  		<TABLE width="100%" style="WIDTH: 100%" cellpadding="10">
							<TBODY>
							<TR bgcolor="#00CCFF">
							  <TD width="10%"  align="left" valign="top">&nbsp;</TD>
<?PHP
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
									}
								}

?>
							 
							</TR>
<?PHP
							if($weekday == "Monday"){
?>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Monday</span></TD>
<?PHP
									//echo $arr_lookup_Subj[0][2];
									$LNo = 0;
									for($i=1;$i<=$MaxLecAll;$i++)
									{
										if($i<=$MaxLecAll){
											echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
											if($arr_lookup_Subj[$LNo][1] == $i){
?>
												<a title="Time Table" 
		href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptLookupTeacher; ?>&wd=Monday&subj=<?PHP echo $arr_lookup_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_lookup_Subj[$LNo][2]); ?></a>
<?PHP
												$LNo = $LNo +1;
											}
											echo "</TD>";
										}
									}
?>
							</TR>
<?PHP
							}elseif($weekday == "Tuesday"){
?>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Tuesday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_lookup_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptLookupTeacher; ?>&wd=Tuesday&subj=<?PHP echo $arr_lookup_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_lookup_Subj[$LNo][2]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
<?PHP
							}elseif($weekday == "Wednesday"){
?>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Wednesday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_lookup_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptLookupTeacher; ?>&wd=Wednesday&subj=<?PHP echo $arr_lookup_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_lookup_Subj[$LNo][2]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
<?PHP
							}elseif($weekday == "Thursday"){
?>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Thursday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_lookup_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptLookupTeacher; ?>&wd=Thursday&subj=<?PHP echo $arr_lookup_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_lookup_Subj[$LNo][2]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
<?PHP
							}elseif($weekday == "Friday"){
?>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Friday</span></TD>
<?PHP
								$LNo = 0;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_lookup_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptLookupTeacher; ?>&wd=Friday&subj=<?PHP echo $$arr_lookup_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_lookup_Subj[$LNo][2]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
<?PHP
							}elseif($weekday == "Saturday"){
?>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Saturday</span></TD>
<?PHP
							  	$LNo = 0;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_lookup_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptLookupTeacher; ?>&wd=Saturday&subj=<?PHP echo $arr_lookup_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_lookup_Subj[$LNo][2]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
<?PHP
							}
?>
							</TBODY>
							</TABLE>
							</TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Teachers Duty Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="teacherTT.php?subpg=Teachers Duty Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182" align="center"><strong>DUTY</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter = 0;
								$query = "select * from tbTeachersDuty order by TeacherDuty";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Duty Found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelDutyID = $row["ID"];
										$TeacherDuty = $row["TeacherDuty"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkdutyID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelDutyID; ?>">
									           </div></td>
											<td><div align="center"><a href="teacherTT.php?subpg=Teachers Duty Master&duty_id=<?PHP echo $SelDutyID; ?>"><?PHP echo $TeacherDuty; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="66">Code :</td>
								<td width="314"><input name="Code" type="text" size="5" value="<?PHP echo $Code; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Duty :</td>
								<td><input name="Duty" type="text" size="55" value="<?PHP echo $Duty; ?>"></td>
							  </tr>
							  <tr>
								<td>Description :</td>
								<td><textarea name="Description" cols="55" rows="5"><?PHP echo $Description; ?></textarea></td>
							  </tr>
							</table>
					  
					  
					  
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalDuty" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelDutyID" value="<?PHP echo $Code; ?>">
						     <input name="Dutymaster" type="submit" id="Dutymaster" value="Save">
						     <input name="Dutymaster_delete" type="submit" id="Dutymaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Dutymaster" type="submit" id="Dutymaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Assign Teachers Duty") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="teacherTT.php?subpg=Assign Teachers Duty">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="30%" valign="top"  align="right">
					  <p>Duty Name  : </p>
					  </TD>
					  <TD width="70%" valign="top"  align="left">
							<select name="optDuty" class="psd2xhmtl-example">
								<option value="" selected="selected">Select</option>
<?PHP
							$query = "select ID,TeacherDuty from tbteachersduty order by TeacherDuty";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "No Duty Found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$DutyID = $row["ID"];
									$TeacherDuty = $row["TeacherDuty"];
									if($optDuty =="$DutyID"){
?>
										<option value="<?PHP echo $DutyID; ?>" selected="selected"><?PHP echo $TeacherDuty; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $DutyID; ?>"><?PHP echo $TeacherDuty; ?></option>
<?PHP
									}
								}
							}
?>
						  </select>
					   </TD>
					</TR>
					<TR>
					  <TD width="30%" valign="top"  align="right">
					  <p>Teacher Name  : </p></TD>
					  <TD width="70%" valign="top"  align="left">
                           <p><label>
                           <select name="OptTr">
							<option value="" selected="selected">Select</option>
<?PHP
							
							$counter = 0;
							if($_SESSION['module'] == "Teacher"){
								$query = "select ID,EmpName from tbemployeemasters where ID = '$Teacher_EmpID' order by EmpName";
							}else{
								$query = "select ID,EmpName from tbemployeemasters where isTeaching = '1' order by EmpName";
							}
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$counter = $counter+1;
									$TeacherID = $row["ID"];
									$TeacherName = $row["EmpName"];
									
									if($OptTr =="$TeacherID"){
?>
										<option value="<?PHP echo $TeacherID; ?>" selected="selected"><?PHP echo $TeacherName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $TeacherID; ?>"><?PHP echo $TeacherName; ?></option>
<?PHP
									}
								}
							}
?>
						 </select></label>
					  </p>
					   </TD>
					</TR>
					<TR>
					  <TD valign="top"  align="right"><p>Effective From </p></TD>
					  <TD width="70%" valign="top"  align="left">
					  <select name="from_Dy" style="width:45px;">
						  <option value="" selected="selected">Day</option>
						  
<?PHP
							for($i=1; $i<=31; $i++){
								if($from_Dy == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						<select name="from_Mth">
						   <option value="" selected="selected">Month</option>
<?PHP
								for($i=1; $i<=12; $i++){
									if($i == $from_Mth){
										echo "<option value=$i selected='selected'>".Get_Month_Name($i)."</option>";
									}else{
										echo "<option value=$i>".Get_Month_Name($i)."</option>";
									}
								}
?>
						</select>
						<select name="from_Yr">
						  <option value="" selected="selected">Year</option>
<?PHP
							$CurYear = date('Y');
							for($i=2009; $i<=$CurYear; $i++){
								if($from_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
					   &nbsp; &nbsp;To&nbsp;&nbsp;
					   <select name="to_Dy" style="width:45px;">
						  <option value="" selected="selected">Day</option>
						  
<?PHP
							for($i=1; $i<=31; $i++){
								if($to_Dy == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						<select name="to_Mth">
						   <option value="" selected="selected">Month</option>
<?PHP
								for($i=1; $i<=12; $i++){
									if($i == $to_Mth){
										echo "<option value=$i selected='selected'>".Get_Month_Name($i)."</option>";
									}else{
										echo "<option value=$i>".Get_Month_Name($i)."</option>";
									}
								}
?>
						</select>
						<select name="to_Yr">
						  <option value="" selected="selected">Year</option>
<?PHP
							$CurYear = date('Y');
							for($i=2009; $i<=$CurYear; $i++){
								if($to_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						<input type="hidden" name="SeldutyID" value="<?PHP echo $duty_id; ?>">
					   <input name="AddDuty" type="submit" id="SubmitSearch" value="  +  " title="Assign duty to teacher">
					   <input name="DeleteDuty" type="submit" id="SubmitSearch2" value="  -  " title="Unassign duty from teacher">
					  </p>
						</TD>
					</TR>
					<TR>
					   <TD align="left" colspan="2">
					    <p style="margin-left:150px;">&nbsp;</p>
					    <table width="561" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="155" bgcolor="#F4F4F4"><div align="center" class="style21">Duty Name</div></td>
                            <td width="153" bgcolor="#F4F4F4"><div align="center" class="style21">Teacher</div></td>
                            <td width="104" bgcolor="#F4F4F4"><div align="center"><strong>From Date</strong></div></td>
							<td width="110" bgcolor="#F4F4F4"><div align="center"><strong>To Date</strong></div></td>
                          </tr>
<?PHP
							$counter_duty = 0;
							$query2 = "select * from tbAssignDuty";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_duty = $rstart;
							}else{
								$counter_duty = $rstart-1;
							}
							$counter = 0;
							$query3 = "select * from tbassignduty LIMIT $rstart,$rend";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_desig = $counter_desig+1;
									$counter = $counter+1;
									$adutyid = $row["ID"];
									$Dutyid = $row["Dutyid"];
									$Empid = $row["Empid"];
									$From_Date = $row["From_Date"];
									$To_Date = $row["To_Date"];
									
									$query = "select TeacherDuty from tbteachersduty where ID='$Dutyid'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$TeacherDuty  = $dbarray['TeacherDuty'];
?>
									  <tr>
										<td><div align="center"><a href="teacherTT.php?subpg=Assign Teachers Duty&duty_id=<?PHP echo $adutyid; ?>"><?PHP echo $TeacherDuty; ?></a></div></td>
										<td><div align="center"><a href="teacherTT.php?subpg=Assign Teachers Duty&duty_id=<?PHP echo $adutyid; ?>"><?PHP echo GET_EMP_NAME($Empid); ?></a></div></td>
										<td><div align="center"><a href="teacherTT.php?subpg=Assign Teachers Duty&duty_id=<?PHP echo $adutyid; ?>"><?PHP echo Long_date($From_Date); ?></a></div></td>
										<td><div align="center"><a href="teacherTT.php?subpg=Assign Teachers Duty&duty_id=<?PHP echo $adutyid; ?>"><?PHP echo Long_date($To_Date); ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="teacherTT.php?subpg=Assign Teachers Duty&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="teacherTT.php?subpg=Assign Teachers Duty&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="teacherTT.php?subpg=Assign Teachers Duty&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						     <input type="hidden" name="SeldutyID" value="<?PHP echo $duty_id; ?>">
							 <input name="UpdateDuty" type="submit" id="UpdateDuty" value="Update">
						  </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}
?>

              </TD>
			</TR>
		</TBODY>
		</TABLE>
		<BR></TD>
	  </TR>
	 </TABLE>
      </TD></TR></TBODY></TABLE>
	  <TABLE style="WIDTH: 100%" background="images/footer.jpg">
<TBODY>
	<TR>
	  <TD height="101px" valign="middle">
	  	<TABLE width="70%" border="0" cellPadding=3 cellSpacing=0 align="center">
		  <TR>
			<TD align="center">Home | About SkoolNet Manager | Contact us | User Agreement | Privacy Policy | Copyright Policy</TD>
		  </TR>
		  <TR>
			<TD align="center"> Copyright © <?PHP echo date('Y'); ?> SkoolNet Manager. All right reserved.</TD>
		  </TR>
		</TABLE>	  
	  </TD>
	</TR>
</TBODY>
</TABLE> 	
</BODY></HTML>
