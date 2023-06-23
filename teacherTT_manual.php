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
	if(isset($_POST['EditTT']))
	{
		$_POST['OptTeacherEdit'] = $_POST['OptTeacher'];
		$SubPage = "Edit Teacher Time Table";
	}
	if(isset($_POST['PrevieTT']))
	{
		$_POST['OptTeacher'] = $_POST['OptTeacherEdit'];
		$SubPage = "Teacher time table";
	}
	if(isset($_POST['OptTeacher']))
	{
		$OptTeacher = $_POST['OptTeacher'];
		$query = "select Max_Lect from tbteacherworkload where EmpID='$OptTeacher' order by Max_Lect desc";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['Max_Lect'];
		$AvgWidth = 90/ $MaxLecAll;
		
		$i=0;
		$query3 = "select SubjectID from tbclassteachersubj where EmpId = '$OptTeacher' And SecID ='$Activeterm'";
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
		$arr_Fri_Subj = "";
		$arr_Sat_Subj = "";
		
		$arr_Mon_LecNo = "";
		$arr_Tue_LecNo = "";
		$arr_Wed_LecNo = "";
		$arr_Thur_LecNo = "";
		$arr_Fri_LecNo = "";
		$arr_Sat_LecNo = "";
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacher' And Term = '$Activeterm' and WeekDay = 'Monday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Mon_LecNo[$i]= $row["LecNo"];
				$arr_Mon_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacher' And Term = '$Activeterm' and WeekDay = 'Tuesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Tue_LecNo[$i]= $row["LecNo"];
				$arr_Tue_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacher' And Term = '$Activeterm' and WeekDay = 'Wednesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Wed_LecNo[$i]= $row["LecNo"];
				$arr_Wed_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacher' And Term = '$Activeterm' and WeekDay = 'Thurday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Thur_LecNo[$i]= $row["LecNo"];
				$arr_Thur_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacher' And Term = '$Activeterm' and WeekDay = 'Friday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Fri_LecNo[$i]= $row["LecNo"];
				$arr_Fri_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacher' And Term = '$Activeterm' and WeekDay = 'Saturday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Sat_LecNo[$i]= $row["LecNo"];
				$arr_Sat_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
	}
	if(isset($_POST['SubmitSave']))
	{
		$OptTeacherEdit = $_POST['OptTeacherEdit'];
		$q = "Delete From tbteachertimetable where EmpID = '$OptTeacherEdit'";
		$result = mysql_query($q,$conn);
			
		//$Activeterm
		$query = "select Max_Lect from tbteacherworkload where EmpID='$OptTeacherEdit' order by Max_Lect desc";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['Max_Lect'];
		//Monday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Monday = "";
			$Monday = $_POST['Mon'.$i];
			if($Monday !=""){
				$q = "Insert into tbteachertimetable(EmpID,Term,WeekDay,SubjID,LecNo) Values ('$OptTeacherEdit','$Activeterm','Monday','$Monday','$i')";
				$result = mysql_query($q,$conn);
			}
		}
		//Tuesday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Tuesday = "";
			$Tuesday = $_POST['Tue'.$i];
			if($Tuesday !=""){
				$q = "Insert into tbteachertimetable(EmpID,Term,WeekDay,SubjID,LecNo) Values ('$OptTeacherEdit','$Activeterm','Tuesday','$Tuesday','$i')";
				$result = mysql_query($q,$conn);
			}
		}
		//Wednesday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Wednesday = "";
			$Wednesday = $_POST['Wed'.$i];
			if($Wednesday !=""){
				$q = "Insert into tbteachertimetable(EmpID,Term,WeekDay,SubjID,LecNo) Values ('$OptTeacherEdit','$Activeterm','Wednesday','$Wednesday','$i')";
				$result = mysql_query($q,$conn);
			}
		}
		//Thurday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Thurday = "";
			$Thurday = $_POST['Thur'.$i];
			if($Thurday !=""){
				$q = "Insert into tbteachertimetable(EmpID,Term,WeekDay,SubjID,LecNo) Values ('$OptTeacherEdit','$Activeterm','Thurday','$Thurday','$i')";
				$result = mysql_query($q,$conn);
			}
		}
		//Friday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Friday = "";
			$Friday = $_POST['Fri'.$i];
			if($Friday !=""){
				$q = "Insert into tbteachertimetable(EmpID,Term,WeekDay,SubjID,LecNo) Values ('$OptTeacherEdit','$Activeterm','Friday','$Friday','$i')";
				$result = mysql_query($q,$conn);
			}
		}
		//Saturday
		for ($i=1; $i<=$MaxLecAll; $i++){
			$Saturday = "";
			$Saturday = $_POST['Sat'.$i];
			if($Saturday !=""){
				$q = "Insert into tbteachertimetable(EmpID,Term,WeekDay,SubjID,LecNo) Values ('$OptTeacherEdit','$Activeterm','Saturday','$Saturday','$i')";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['OptTeacherEdit']))
	{
		$OptTeacherEdit = $_POST['OptTeacherEdit'];
		$query = "select Max_Lect from tbteacherworkload where EmpID='$OptTeacherEdit' order by Max_Lect desc";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['Max_Lect'];
		$AvgWidth = 90/ $MaxLecAll;
		
		$i=0;
		$query3 = "select SubjectID from tbclassteachersubj where EmpId = '$OptTeacherEdit' And SecID ='$Activeterm'";
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
		$arr_Fri_Subj = "";
		$arr_Sat_Subj = "";
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacherEdit' And Term = '$Activeterm' and WeekDay = 'Monday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Mon_LecNo[$i]= $row["LecNo"];
				$arr_Mon_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacherEdit' And Term = '$Activeterm' and WeekDay = 'Tuesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Tue_LecNo[$i]= $row["LecNo"];
				$arr_Tue_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacherEdit' And Term = '$Activeterm' and WeekDay = 'Wednesday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Wed_LecNo[$i]= $row["LecNo"];
				$arr_Wed_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacherEdit' And Term = '$Activeterm' and WeekDay = 'Thurday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Thur_LecNo[$i]= $row["LecNo"];
				$arr_Thur_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacherEdit' And Term = '$Activeterm' and WeekDay = 'Friday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Fri_LecNo[$i]= $row["LecNo"];
				$arr_Fri_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbteachertimetable where EmpID = '$OptTeacherEdit' And Term = '$Activeterm' and WeekDay = 'Saturday'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$arr_Sat_LecNo[$i]= $row["LecNo"];
				$arr_Sat_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
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
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<TABLE style="WIDTH: 100%" background="images/Top_pole.jpg">
<TBODY>
	<TR>
	  <TD height="55px" valign="top">
	  	<TABLE width="996" border="0" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
		  <TR>
			<TD width="38%" align="left"><img src="images/edmis_logo.jpg" width="130" height="39"></TD>
			<TD width="62%" align="right"><a href="welcome.php?pg=System Setup&mod=admin">Home</a> | About SkoolNet Manager | Contact us | <a href="Logout.php">Logout </a></TD>
		  </TR>
		</TABLE>
	  </TD>
	</TR>
</TBODY>
</TABLE>
<TABLE width="100%" bgcolor="#f4f4f4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	<DIV id=main>
		<DIV id=mainmenu>
			<?PHP include 'topmenu.php'; ?>
		</DIV>
	</DIV>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD>
		<TABLE width="100%" style="WIDTH: 100%">
        <TBODY>
			<TR>
			  <TD width="222" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="751" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: maroon; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" 
					  align=middle 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
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
							$query = "select ID from tbdepartments where DeptName='Teaching' or DeptName='Teacher' or DeptName='Lecturing'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$DeptID  = $dbarray['ID'];
							
							$counter = 0;
							$query = "select ID,EmpName from tbemployeemasters where EmpDept = '$DeptID' order by EmpName";
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
							  
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
								}

?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Monday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Mon_LecNo[$LNo] == $i	){
											echo GetSubjectName($arr_Mon_Subj[$LNo]);
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Tue_LecNo[$LNo] == $i	){
											echo GetSubjectName($arr_Tue_Subj[$LNo]);
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Wed_LecNo[$LNo] == $i	){
											echo GetSubjectName($arr_Wed_Subj[$LNo]);
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Thur_LecNo[$LNo] == $i	){
											echo GetSubjectName($arr_Thur_Subj[$LNo]);
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Fri_LecNo[$LNo] == $i	){
											echo GetSubjectName($arr_Fri_Subj[$LNo]);
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_SatLecNo[$LNo] == $i	){
											echo GetSubjectName($arr_Sat_Subj[$LNo]);
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
						  <input type="submit" name="EditTT" value="Edit TimeTable">
						</div>						</TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Edit Teacher Time Table") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="teacherTT.php?subpg=Edit Teacher Time Table">
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
					  	 <select name="OptTeacherEdit" onChange="javascript:setTimeout('__doPostBack(\'OptTeacherEdit\',\'\')', 0)">
							<option value="" selected="selected">Select</option>
<?PHP
							$query = "select ID from tbdepartments where DeptName='Teaching' or DeptName='Teacher' or DeptName='Lecturing'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$DeptID  = $dbarray['ID'];
							
							$counter = 0;
							$query = "select ID,EmpName from tbemployeemasters where EmpDept = '$DeptID' order by EmpName";
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
									
									if($OptTeacherEdit =="$TeacherID"){
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
					  <TD width="14%" valign="top"  align="left" ><strong>Current Section: </strong></TD>
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
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>BREAK</div></TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div></TD>";
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Mon$i' style='width:65px;'>";
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Tue$i' style='width:65px;'>";
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Wed$i' style='width:65px;'>";
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Thur$i' style='width:65px;'>";
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Fri$i' style='width:65px;'>";
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
									if($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top' bgcolor='#FFFFCC'>";
										echo "<select name='Sat$i' style='width:65px;'>";
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
						  <input name="SubmitSave" type="submit" id="SubmitSave" value="Save ">
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
