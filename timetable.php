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
	if(isset($_POST['SubmitRoom']))
	{
		$PageHasError = 0;
		$OptRoom = $_POST['OptRoom'];
		$RoomName = $_POST['RoomName'];
		$Description = $_POST['Description'];
		$RoomType = $_POST['RoomType'];
		$SeatingCap = $_POST['SeatingCap'];
		
		if(!$_POST['RoomName']){
			$errormsg = "<font color = red size = 1>Room No/ Name is empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($SeatingCap)){
			$errormsg = "<font color = red size = 1>Seating Capacity should be in numbers</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SubmitRoom'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbRoomMaster where RoomNo = '$RoomName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The room you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbRoomMaster(RoomNo,Description,type,Capacity) Values ('$RoomName','$Description','$RoomType','$SeatingCap')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$RoomNo = "";
					$Description = "";
					$RoomType = "";
					$SeatingCap = "";
				}
			}elseif ($_POST['SubmitRoom'] =="Update"){
				$q = "update tbRoomMaster set RoomNo='$RoomName',Description='$Description',type='$RoomType',Capacity='$SeatingCap' where ID = '$OptRoom'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$RoomNo = "";
				$Description = "";
				$RoomType = "";
				$SeatingCap = "";
			}elseif ($_POST['SubmitRoom'] =="Delete"){
				$q = "Delete From tbRoomMaster where ID = '$OptRoom'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
				
				$RoomName = "";
				$Description = "";
				$RoomType = "";
				$SeatingCap = "";
			}
		}
	}
	if(isset($_POST['OptRoom']))
	{	
		$OptRoom = $_POST['OptRoom'];
		$query = "select * from tbRoomMaster where ID='$OptRoom'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RoomName  = $dbarray['RoomNo'];
		$Description  = $dbarray['Description'];
		$RoomType  = $dbarray['type'];
		$SeatingCap  = $dbarray['Capacity'];
	}
	if(isset($_POST['OptTeacher']))
	{	
		$OptTeacher = $_POST['OptTeacher'];
		$query = "select * from tbTeacherWorkLoad where EmpID='$OptTeacher'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptWeekday  = $dbarray['Day'];
		$MinLectures  = $dbarray['Min_Lect'];
		$MaxLectures  = $dbarray['Max_Lect'];
	}
	if(isset($_GET['work_id']))
	{
		$workid = $_GET['work_id'];
		$query = "select * from tbTeacherWorkLoad where ID='$workid'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptTeacher = $dbarray['EmpID'];
		$OptWeekday  = $dbarray['Day'];
		$MinLectures  = $dbarray['Min_Lect'];
		$MaxLectures  = $dbarray['Max_Lect'];
	}
	if(isset($_POST['Submitworkload']))
	{
		$PageHasError = 0;
		$workid = $_POST['workloadID'];
		$OptTeacher = $_POST['OptTeacher'];
		$OptWeekday = $_POST['OptWeekday'];
		$MinLectures = $_POST['MinLectures'];
		$MaxLectures = $_POST['MaxLectures'];
		
		if(!$_POST['OptWeekday']){
			$errormsg = "<font color = red size = 1>Select Week Day.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($MinLectures)){
			$errormsg = "<font color = red size = 1>Invalid Minimum Lectures allowed.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($MaxLectures)){
			$errormsg = "<font color = red size = 1>Invalid Maximum Lectures allowed.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['Submitworkload'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbTeacherWorkLoad where EmpID = '$OptTeacher' and Day = '$OptWeekday'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					//Update
					$q = "update tbTeacherWorkLoad set EmpID = '$OptTeacher', Day = '$OptWeekday',Max_Lect = '$MaxLectures',Min_Lect = '$MinLectures' where ID = '$workid'";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				}else {
					$q = "Insert into tbTeacherWorkLoad(EmpID,Day,Max_Lect,Min_Lect) Values ('$OptTeacher','$OptWeekday','$MaxLectures','$MinLectures')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
					
					$OptWeekday= "";
					$MinLectures= "";
					$MaxLectures= "";
				}
			}elseif ($_POST['Submitworkload'] =="Delete"){
				$q = "Delete From tbTeacherWorkLoad where ID = '$workid'";
				$result = mysql_query($q,$conn);
				$OptWeekday= "";
				$MinLectures= "";
				$MaxLectures= "";
			}
		}
	}
	if(isset($_POST['SubmitAllToALL']))
	{
		$PageHasError = 0;
		$workid = $_POST['workloadID'];
		$OptTeacher = $_POST['OptTeacher'];
		$OptWeekday = $_POST['OptWeekday'];
		$MinLectures = $_POST['MinLectures'];
		$MaxLectures = $_POST['MaxLectures'];
		
		if(!$_POST['OptWeekday']){
			$errormsg = "<font color = red size = 1>Select Week Day.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($MinLectures)){
			$errormsg = "<font color = red size = 1>Invalid Minimum Lectures allowed.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($MaxLectures)){
			$errormsg = "<font color = red size = 1>Invalid Maximum Lectures allowed.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{			
			$query = "select ID from tbdepartments where DeptName='Teaching' or DeptName='Teacher' or DeptName='Lecturing'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$DeptID  = $dbarray['ID'];
			
			$counter = 0;
			$query1 = "select ID,EmpName from tbemployeemasters where EmpDept = '$DeptID'";
			$result1 = mysql_query($query1,$conn);
			$num_rows1 = mysql_num_rows($result1);
			if ($num_rows1 > 0 ) {
				while ($row1 = mysql_fetch_array($result1)) 
				{
					$EmpID = $row1["ID"];
					//echo $EmpID;
					$query2 = "select ID from tbTeacherWorkLoad where EmpID = '$EmpID' and Day = '$OptWeekday'";
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ){
						//Update
						$q = "update tbTeacherWorkLoad set Max_Lect = '$MaxLectures',Min_Lect = '$MinLectures' where EmpID = '$EmpID' and Day = '$OptWeekday'";
						$result = mysql_query($q,$conn);
					}else{
						$q = "Insert into tbTeacherWorkLoad(EmpID,Day,Max_Lect,Min_Lect) Values ('$EmpID','$OptWeekday','$MaxLectures','$MinLectures')";
						$result = mysql_query($q,$conn);
					}
					
				}
			}
		}
	}
	if(isset($_POST['EditTT']))
	{
		$_POST['OptClassEdit'] = $_POST['OptClass'];
		$SubPage = "Edit Class Time Table";
	}
	if(isset($_POST['PrevieTT']))
	{
		$_POST['OptClass'] = $_POST['OptClassEdit'];
		$SubPage = "Class time table";
	}
	if(isset($_POST['OptClass']))
	{
		$OptClass = $_POST['OptClass'];
		$query = "select * from tbclassmaster where ID='$OptClass'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['MaxLec'];
		$BreakLec  = $dbarray['BreakLec'];
		$AvgWidth = 90/ ($MaxLecAll+1);
		$i=0;
		$query3 = "select ID from tbsubjectmaster where ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$OptClassEdit')";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$SubjID = $row["ID"];
				$arr_Subj[$i]=$SubjID;
				$i = $i+1;
			}
		}
		$arr_Mon_Subj = "";
		$arr_Tue_Subj = "";
		$arr_Wed_Subj = "";
		$arr_Thur_Subj = "";
		$arr_Fri_Subj = "";
		$arr_Sat_Subj = "";
		
		$arr_Mon_LecNo = "";
		$arr_Tue_LecNo = "";
		$arr_Wed_LecNo = "";
		$arr_Thur_LecNo = "";
		$arr_Fri_LecNo = "";
		$arr_Sat_LecNo = "";
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' and WeekDay = 'Monday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Mon_LecNo[$i]= $row["LecNo"];
				$arr_Mon_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' and WeekDay = 'Tuesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Tue_LecNo[$i]= $row["LecNo"];
				$arr_Tue_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' And Term = '$Activeterm' and WeekDay = 'Wednesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Wed_LecNo[$i]= $row["LecNo"];
				$arr_Wed_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' And Term = '$Activeterm' and WeekDay = 'Thurday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Thur_LecNo[$i]= $row["LecNo"];
				$arr_Thur_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' And Term = '$Activeterm' and WeekDay = 'Friday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Fri_LecNo[$i]= $row["LecNo"];
				$arr_Fri_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' And Term = '$Activeterm' and WeekDay = 'Saturday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Sat_LecNo[$i]= $row["LecNo"];
				$arr_Sat_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
	}
	if(isset($_POST['SubmitSave']))
	{
		$OptClassEdit = $_POST['OptClassEdit'];
		$q = "Delete From tbclasstimetable where ClassID = '$OptClassEdit'";
		$result = mysql_query($q,$conn);
			
		//$Activeterm
		$query = "select MaxLec from tbclassmaster where ID='$OptClassEdit'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['MaxLec'];
		//Monday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Monday = "";
			$Monday = $_POST['Mon'.$i];
			$OptTime = $_POST['OptTime'.$i];
			if($Monday !=""){
				$q = "Insert into tbclasstimetable(ClassID,Term,WeekDay,SubjID,LecNo,TimeNo) Values ('$OptClassEdit','$Activeterm','Monday','$Monday','$i','$OptTime')";
				$result = mysql_query($q,$conn);
			}
		}
		//Tuesday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Tuesday = "";
			$Tuesday = $_POST['Tue'.$i];
			$OptTime = $_POST['OptTime'.$i];
			if($Tuesday !=""){
				$q = "Insert into tbclasstimetable(ClassID,Term,WeekDay,SubjID,LecNo,TimeNo) Values ('$OptClassEdit','$Activeterm','Tuesday','$Tuesday','$i','$OptTime')";
				$result = mysql_query($q,$conn);
			}
		}
		//Wednesday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Wednesday = "";
			$Wednesday = $_POST['Wed'.$i];
			$OptTime = $_POST['OptTime'.$i];
			if($Wednesday !=""){
				$q = "Insert into tbclasstimetable(ClassID,Term,WeekDay,SubjID,LecNo,TimeNo) Values ('$OptClassEdit','$Activeterm','Wednesday','$Wednesday','$i','$OptTime')";
				$result = mysql_query($q,$conn);
			}
		}
		//Thurday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Thurday = "";
			$Thurday = $_POST['Thur'.$i];
			$OptTime = $_POST['OptTime'.$i];
			if($Thurday !=""){
				$q = "Insert into tbclasstimetable(ClassID,Term,WeekDay,SubjID,LecNo,TimeNo) Values ('$OptClassEdit','$Activeterm','Thurday','$Thurday','$i','$OptTime')";
				$result = mysql_query($q,$conn);
			}
		}
		//Friday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Friday = "";
			$Friday = $_POST['Fri'.$i];
			$OptTime = $_POST['OptTime'.$i];
			if($Friday !=""){
				$q = "Insert into tbclasstimetable(ClassID,Term,WeekDay,SubjID,LecNo,TimeNo) Values ('$OptClassEdit','$Activeterm','Friday','$Friday','$i','$OptTime')";
				$result = mysql_query($q,$conn);
			}
		}
		//Saturday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Saturday = "";
			$Saturday = $_POST['Sat'.$i];
			$OptTime = $_POST['OptTime'.$i];
			if($Saturday !=""){
				$q = "Insert into tbclasstimetable(ClassID,Term,WeekDay,SubjID,LecNo,TimeNo) Values ('$OptClassEdit','$Activeterm','Saturday','$Saturday','$i','$OptTime')";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['OptClassEdit']))
	{
		$OptClassEdit = $_POST['OptClassEdit'];
		$query = "select * from tbclassmaster where ID='$OptClassEdit'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['MaxLec'];
		$BreakLec  = $dbarray['BreakLec'];
		$AvgWidth = 90/ ($MaxLecAll+1);
		$i=0;
		$query3 = "select ID from tbsubjectmaster where ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$OptClassEdit')";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$SubjID = $row["ID"];
				$arr_Subj[$i]=$SubjID;
				$i = $i+1;
			}
		}
		$arr_Mon_Subj = "";
		$arr_Tue_Subj = "";
		$arr_Wed_Subj = "";
		$arr_Thur_Subj = "";
		$arr_Fri_Subj = "";
		$arr_Sat_Subj = "";
		
		$arr_Mon_LecNo = "";
		$arr_Tue_LecNo = "";
		$arr_Wed_LecNo = "";
		$arr_Thur_LecNo = "";
		$arr_Fri_LecNo = "";
		$arr_Sat_LecNo = "";
		$i=1;
		$query3 = "select LecNo,SubjID,TimeNo from tbclasstimetable where ClassID = '$OptClassEdit' And Term = '$Activeterm' and WeekDay = 'Monday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Mon_LecNo[$i]= $row["LecNo"];
				$arr_Mon_TimeNo[$i]= $row["TimeNo"];
				$arr_Mon_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID,TimeNo from tbclasstimetable where ClassID = '$OptClassEdit' And Term = '$Activeterm' and WeekDay = 'Tuesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Tue_LecNo[$i]= $row["LecNo"];
				$arr_Mon_TimeNo[$i]= $row["TimeNo"];
				$arr_Tue_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID,TimeNo from tbclasstimetable where ClassID = '$OptClassEdit' And Term = '$Activeterm' and WeekDay = 'Wednesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Wed_LecNo[$i]= $row["LecNo"];
				$arr_Mon_TimeNo[$i]= $row["TimeNo"];
				$arr_Wed_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID,TimeNo from tbclasstimetable where ClassID = '$OptClassEdit' And Term = '$Activeterm' and WeekDay = 'Thurday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Thur_LecNo[$i]= $row["LecNo"];
				$arr_Mon_TimeNo[$i]= $row["TimeNo"];
				$arr_Thur_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID,TimeNo from tbclasstimetable where ClassID = '$OptClassEdit' And Term = '$Activeterm' and WeekDay = 'Friday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Fri_LecNo[$i]= $row["LecNo"];
				$arr_Mon_TimeNo[$i]= $row["TimeNo"];
				$arr_Fri_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID,TimeNo from tbclasstimetable where ClassID = '$OptClassEdit' And Term = '$Activeterm' and WeekDay = 'Saturday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Sat_LecNo[$i]= $row["LecNo"];
				$arr_Mon_TimeNo[$i]= $row["TimeNo"];
				$arr_Sat_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
	}
	if(isset($_POST['PrintTT']))
	{
		$OptClass = $_POST['OptClass'];
		if(!isset($_POST['OptClass']))
		{
			$errormsg = "<font color = red size = 1>Select Class to view.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=timetable_rpt.php?pg=Timetable&cls=$OptClass\">";
			exit;
		}
	}
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
<script language="JavaScript">
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->
</script>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
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
			  <TD width="221" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="857" align="center" valign="top">
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
		if ($SubPage == "Room Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="timetable.php?subpg=Room Master">
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
					  <TD width="34%" valign="top"  align="left">
							<select name="Select" size="1" multiple="multiple" style="width:250px;">
						      <option>
							  SrNo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Class Room</option>
						      </select>
							 <select name="OptRoom" size="20" multiple style="width:250px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptSubj\',\'\')', 0)">
<?PHP
								$counter = 0;
								if(isset($_POST['SubmitSearch']))
								{
									$searchroom = $_POST['searchroom'];
									$query = "select ID,RoomNo from tbRoomMaster where INSTR(RoomNo,'$searchroom') order by RoomNo";
								}else{
									$query = "select ID,RoomNo from tbRoomMaster order by RoomNo";
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
										$roomID = $row["ID"];
										$RoomNo = $row["RoomNo"];
										if($counter <= 9){
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}else{
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										
										if($OptRoom =="$roomID"){
?>
											<option value="<?PHP echo $roomID; ?>" selected="selected"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $RoomNo; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $roomID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $RoomNo; ?></option>
<?PHP
										}
									}
								}
?>
						      <option> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						      </select>
						  <p align="right">Search Subject 
						    <input name="searchroom" type="text" size="20" value="<?PHP echo $searchroom; ?>">
						    <input name="SubmitSearch" type="submit" id="Search" value="Go">
						  </p></TD>
					  <TD width="66%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <TABLE width="92%" align="center" cellpadding="7">
							<TBODY>
							<TR>
							  <TD width="31%"  align="left"><strong>Room  Information </strong></TD>
							  <TD width="69%"  align="left" valign="top">&nbsp;</TD>
							</TR>
							<TR>
							  <TD width="31%"  align="left">Room No /  Name  :</TD>
							  <TD width="69%"  align="left" valign="top">
									<input name="RoomName" type="text" size="35" value="<?PHP echo $RoomName; ?>">							   </TD>
							</TR>
							<TR>
							  <TD width="31%"  align="left">Description</TD>
							  <TD width="69%"  align="left" valign="top"><textarea name="Description" cols="45" rows="4"><?PHP echo $Description; ?></textarea></TD>
							</TR>
							<TR>		
							  <TD width="31%"  align="left">Room Type </TD>
							  <TD width="69%"  align="left" valign="top"><label>
							  <input name="RoomType" type="text" size="35" value="<?PHP echo $RoomType; ?>">
							  </label></TD>
							</TR>
							<TR>
							  <TD width="31%"  align="left">Seating Capacity </TD>
							  <TD width="69%"  align="left" valign="top"><label>
							  <input name="SeatingCap" type="text" size="5" value="<?PHP echo $SeatingCap; ?>">
							  </label></TD>
							</TR>
						</TBODY>
						</TABLE>
						<p>&nbsp;</p>
						<div align="center">
						  <input type="submit" name="SubmitRoom" value="Create New">
						  <input type="submit" name="SubmitRoom" value="Update">
						  <input type="submit" name="SubmitRoom" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						  <input type="reset" name="reset" value="Reset">
						  </div></TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">					  
						<p style="margin-left:150px;">&nbsp;</p>
					    </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Teacher Workload") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="timetable.php?subpg=Teacher Workload">
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
					  <TD width="99%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <TABLE width="97%" align="center" cellpadding="7">
							<TBODY>
							<TR>
							  <TD width="21%"  align="left"><strong>Select Teacher  </strong></TD>
							  <TD width="28%"  align="left" valign="top">
							  <select name="OptTeacher" style="background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptTeacher\',\'\')', 0)">
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
										$EmpID = $row["ID"];
										$EmpName = $row["EmpName"];
										
										if($OptTeacher =="$EmpID"){
?>
											<option value="<?PHP echo $EmpID; ?>" selected="selected"><?PHP echo $EmpName; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></option>
<?PHP
										}
									}
								}
?>
                              </select></TD>
							  <TD width="23%"  align="left"><strong>Week Day   </strong></TD>
							  <TD width="28%"  align="left">
							  <SELECT name="OptWeekday">
                                <OPTION value="">Choose Day</OPTION>
<?PHP
								if($OptWeekday == "Monday"){
									echo "<option value='Monday' selected='selected'>Monday</option>";
									echo "<OPTION value='Tuesday'>Tuesday</OPTION>";
									echo "<OPTION value='Wednesday'>Wednesday</OPTION>";
									echo "<OPTION value='Thursday'>Thursday</OPTION>";
									echo "<OPTION value='Friday'>Friday</OPTION>";
									echo "<OPTION value='Saturday'>Saturday</OPTION>";
								}elseif($OptWeekday == "Tuesday"){
									echo "<option value='Monday'>Monday</option>";
									echo "<OPTION value='Tuesday' selected='selected'>Tuesday</OPTION>";
									echo "<OPTION value='Wednesday'>Wednesday</OPTION>";
									echo "<OPTION value='Thursday'>Thursday</OPTION>";
									echo "<OPTION value='Friday'>Friday</OPTION>";
									echo "<OPTION value='Saturday'>Saturday</OPTION>";
								}elseif($OptWeekday == "Wednesday"){
									echo "<option value='Monday'>Monday</option>";
									echo "<OPTION value='Tuesday'>Tuesday</OPTION>";
									echo "<OPTION value='Wednesday' selected='selected'>Wednesday</OPTION>";
									echo "<OPTION value='Thursday'>Thursday</OPTION>";
									echo "<OPTION value='Friday'>Friday</OPTION>";
									echo "<OPTION value='Saturday'>Saturday</OPTION>";
								}elseif($OptWeekday == "Thursday"){
									echo "<option value='Monday'>Monday</option>";
									echo "<OPTION value='Tuesday'>Tuesday</OPTION>";
									echo "<OPTION value='Wednesday'>Wednesday</OPTION>";
									echo "<OPTION value='Thursday' selected='selected'>Thursday</OPTION>";
									echo "<OPTION value='Friday'>Friday</OPTION>";
									echo "<OPTION value='Saturday'>Saturday</OPTION>";
								}elseif($OptWeekday == "Friday"){
									echo "<option value='Monday'>Monday</option>";
									echo "<OPTION value='Tuesday'>Tuesday</OPTION>";
									echo "<OPTION value='Wednesday'>Wednesday</OPTION>";
									echo "<OPTION value='Thursday'>Thursday</OPTION>";
									echo "<OPTION value='Friday' selected='selected'>Friday</OPTION>";
									echo "<OPTION value='Saturday'>Saturday</OPTION>";
								}elseif($OptWeekday == "Saturday"){
									echo "<option value='Monday'>Monday</option>";
									echo "<OPTION value='Tuesday'>Tuesday</OPTION>";
									echo "<OPTION value='Wednesday'>Wednesday</OPTION>";
									echo "<OPTION value='Thursday'>Thursday</OPTION>";
									echo "<OPTION value='Friday'>Friday</OPTION>";
									echo "<OPTION value='Saturday' selected='selected'>Saturday</OPTION>";
								}else{
									echo "<option value='Monday'>Monday</option>";
									echo "<OPTION value='Tuesday'>Tuesday</OPTION>";
									echo "<OPTION value='Wednesday'>Wednesday</OPTION>";
									echo "<OPTION value='Thursday'>Thursday</OPTION>";
									echo "<OPTION value='Friday'>Friday</OPTION>";
									echo "<OPTION value='Saturday'>Saturday</OPTION>";
								}
?>
                              </SELECT>
							  </TD>
							</TR>
							<TR>
							  <TD width="21%"  align="left"><strong>Min Lectures allowed   </strong></TD>
							  <TD width="28%"  align="left" valign="top"><input name="MinLectures" type="text" size="5" value="<?PHP echo $MinLectures; ?>"></TD>
							  <TD width="23%"  align="left"><strong>Max Lectures allowed </strong></TD>
							  <TD width="28%"  align="left"><input name="MaxLectures" type="text" size="5" value="<?PHP echo $MaxLectures; ?>"></TD>
							</TR>
							<TR>
							 <TD colspan="4"><div align="center"><br>
							       <br>
							   Existing workload settings for teacher name.
							   </div>
							   <table width="539" border="0" align="center" cellpadding="3" cellspacing="3">
									  <tr bgcolor="#ECE9D8">
										<td width="178"><div align="center"><strong>Sr. No</strong></div></td>
										<td width="178"><div align="center"><strong>Week Day</strong></div></td>
										<td width="154"><div align="center"><strong>Min Lectures</strong></div></td>
										<td width="154"><div align="center"><strong>Max lectures</strong></div></td>
									  </tr>
<?PHP
										$counter = 0;
										$query = "select * from tbTeacherWorkLoad where EmpID='$OptTeacher' order by ID";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows > 0 ) {
											while ($row = mysql_fetch_array($result)) 
											{
												$counter = $counter+1;
												$workloadID = $row["ID"];
												$wDay = $row["Day"];
												$Max_Lect = $row["Max_Lect"];
												$Min_Lect = $row["Min_Lect"];
?>
											  <tr>
												<td>
												   <div align="center"><a href="timetable.php?subpg=Teacher Workload&work_id=<?PHP echo $workloadID; ?>"><?PHP echo $counter; ?></a></div></td>
												<td><div align="center"><a href="timetable.php?subpg=Teacher Workload&work_id=<?PHP echo $workloadID; ?>"><?PHP echo $wDay; ?></a></div></td>
												<td><div align="center"><a href="timetable.php?subpg=Teacher Workload&work_id=<?PHP echo $workloadID; ?>"><?PHP echo $Min_Lect; ?></a></div></td>
												<td><div align="center"><a href="timetable.php?subpg=Teacher Workload&work_id=<?PHP echo $workloadID; ?>"><?PHP echo $Max_Lect; ?></a></div></td>
											  </tr>
<?PHP
										 }
									 }
?>
								  </table>							 
							</TD>
						</TR>
						</TBODY>
						</TABLE>
						<p>&nbsp;</p>
						<div align="center">
						  <input type="hidden" name="workloadID" value="<?PHP echo $workid; ?>">
						  <input type="submit" name="Submitworkload" value="Create New">
						  <input type="submit" name="Submitworkload" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						  <input type="submit" name="SubmitAllToALL" value="Apply to All Teachers in School">
						</div></TD>
					</TR>
					<TR>
					  <TD valign="top"  align="left">					  
						<p style="margin-left:150px;">&nbsp;</p>					    </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Class time table") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="timetable.php?subpg=Class time table">
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
					  <TD width="66%" valign="top"  align="left"><strong>Select Class 
					    <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
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
										$ClassID = $row["ID"];
										$Classname = $row["Class_Name"];
										
										if($OptClass =="$ClassID"){
?>
											<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
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
					  		<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
							<TBODY>
							<TR bgcolor="#00CCFF">
							  <TD width="10%" align="left" valign="top">&nbsp;</TD>
<?PHP
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									
									$query = "select TimeNo from tbclasstimetable where LecNo='$i' And ClassID = '$OptClass'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$TimeNo  = $dbarray['TimeNo'];
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div><br>$TimeNo</TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>BREAK</div></TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div><br>$TimeNo</TD>";
									}
								}

?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Monday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Mon_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Monday&subj=<?PHP echo $arr_Mon_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Mon_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Monday&subj=<?PHP echo $arr_Mon_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Tuesday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Tue_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Tuesday&subj=<?PHP echo $arr_Tue_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Tue_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Tue_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Tuesday&subj=<?PHP echo $arr_Tue_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Tue_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Wednesday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Wed_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Wednesday&subj=<?PHP echo $arr_Wed_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Wed_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Wednesday&subj=<?PHP echo $arr_Wed_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Thursday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Thur_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Thursday&subj=<?PHP echo $arr_Thur_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Thur_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Thursday&subj=<?PHP echo $arr_Thur_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Friday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Fri_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Friday&subj=<?PHP echo $arr_Fri_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Fri_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Fri_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Friday&subj=<?PHP echo $arr_Fri_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Fri_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Saturday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Sat_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Saturday&subj=<?PHP echo $arr_Sat_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Sat_LecNo[$LNo] == $i	){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Saturday&subj=<?PHP echo $arr_Sat_Subj[$LNo]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo]); ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
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
		}elseif ($SubPage == "Edit Class Time Table") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="timetable.php?subpg=Edit Class Time Table">
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
					  <TD width="71%" valign="top"  align="left"><strong>Select Class 
					    <select name="OptClassEdit" onChange="javascript:setTimeout('__doPostBack(\'OptClassEdit\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
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
										$ClassID = $row["ID"];
										$Classname = $row["Class_Name"];
										
										if($OptClassEdit =="$ClassID"){
?>
											<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
										}
									}
								}
?>
						      </select>
					  </strong></TD>
					  <TD width="29%" valign="top"  align="left" ><strong>Current Section: </strong></TD>
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
									$query = "select TimeNo from tbclasstimetable where LecNo='$i' And Term = '$Activeterm' And ClassID = '$OptClassEdit'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$TimeNo  = $dbarray['TimeNo'];
									if($i==$BreakLec){
										//echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
										
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div>";
										//Get time structure from time.txt file
										echo "<select name='OptTime".$i."' style='width:55px;'>";
										echo "<option value='".$TimeNo."' selected='selected'>".$TimeNo."</option>";
											$filename="time.txt";
											$fh = fopen($filename, 'r');
											$Timetable_time = fread($fh, 5000);
											fclose($fh);
											echo $Timetable_time;
									    echo "</select>";
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>BREAK</div></TD>";
										
									}elseif($i<=$MaxLecAll){
										//echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div>";
										//Get time structure from time.txt file
										echo "<select name='OptTime".$i."' style='width:55px;'>";
										echo "<option value='".$TimeNo."' selected='selected'>".$TimeNo."</option>";
											$filename="time.txt";
											$fh = fopen($filename, 'r');
											$Timetable_time = fread($fh, 5000);
											fclose($fh);
											echo $Timetable_time;
									    echo "</select>";
										echo "</TD>";
									}
								}

?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Monday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Mon$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Mon_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Mon_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Mon$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Mon_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Mon_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Tuesday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Tue$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Tue_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Tue_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Tue$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Tue_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Tue_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Wednesday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Wed$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Wed_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Wed_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Wed$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Wed_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Wed_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Thursday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Thur$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Thur_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Thur_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Thur$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Thur_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Thur_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Friday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Fri$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Fri_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Fri_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Fri$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Fri_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Fri_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Saturday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Sat$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Sat_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Sat_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Sat$i' style='width:55px;'>";
										echo "<option value='' selected='selected'></option>";
										if($arr_Sat_LecNo[$LNo] == $i	){
											$n = 0;
											while(isset($arr_Subj[$n])){
												if($arr_Sat_Subj[$LNo] == $arr_Subj[$n]){
													echo "<option value='$arr_Subj[$n]' selected='selected'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}else{
													echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												}
												$n=$n+1;
											}
											$LNo = $LNo +1;
										}else{
											$n = 0;
											while(isset($arr_Subj[$n])){
												echo "<option value='$arr_Subj[$n]'>".GetSubject_SName($arr_Subj[$n])."</option>";
												$n=$n+1;
											}
										}
										echo "</select></TD>";
									}
								}
?>
							 
							</TR>
							</TBODY>
							</TABLE>					  </TD>
					</TR>
					<TR>
						<TD colspan="2">
						<div align="center">
						  <input type="hidden" name="workloadID" value="<?PHP echo $workid; ?>">
						  <input name="SubmitSave" type="submit" id="SubmitSave" value="Create New ">
						  <input type="submit" name="PrevieTT" value="Preview TimeTable">
						</div>						</TD>
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
