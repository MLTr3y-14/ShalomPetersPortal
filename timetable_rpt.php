<?PHP
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
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
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
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 20;
	}
	//Get School Report Header
	$query = "select ID,SchName,HeaderPic from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpCode  = $dbarray['ID'];
	$SchName  = $dbarray['SchName'];
	$HeaderPic  = $dbarray['HeaderPic'];
	
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	//Get School Report Header
	$query = "select * from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$sName  = $dbarray['SchName'];
	$sAddress  = $dbarray['SchAddress'];
	$sState  = $dbarray['SchState'];
	$sCountry  = $dbarray['SchCountry'];
	$sPhone  = $dbarray['SchPhone'];
	$SchEmail1  = $dbarray['SchEmail1'];
	$sEmail2  = $dbarray['SchEmail2'];
	
	if(isset($_GET['cls']))
	{
		$OptClass = $_GET['cls'];
		
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
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' And Term = '$Activeterm' and WeekDay = 'Monday'";
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
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$OptClass' And Term = '$Activeterm' and WeekDay = 'Tuesday'";
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
	if(isset($_GET['tch']))
	{
		$OptTeacher = $_GET['tch'];
		$query = "select Max_Lect from tbteacherworkload where EmpID='$OptTeacher' order by Max_Lect desc";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['Max_Lect'];
		$AvgWidth = 90/ $MaxLecAll;
		
		$i=0;
		$query3 = "select Distinct SubjectID from tbclassteachersubj where EmpId = '$OptTeacher' And SecID = '$Activeterm'";
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
		$query1 = "select Distinct ClassID from tbclassteachersubj where EmpId = '$OptTeacher' And SecID = '$Activeterm'";
		$result1 = mysql_query($query1,$conn);
		$num_rows1 = mysql_num_rows($result1);
		if ($num_rows1 > 0 ) {
			while ($row1 = mysql_fetch_array($result1)) 
			{
				$ClassID = $row1["ClassID"];
				//echo $ClassID."<br>";
				$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' And Term = '$Activeterm' and WeekDay = 'Monday'";
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
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<TABLE width="100%" bgcolor="#f4f4f4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
<?PHP
		if ($Page == "Timetable") {
?>
		  <TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=System%20Setup&mod=admin">Home</a> &gt; <a href="timetable.php?subpg=Class time table">Timetable</a> &gt; Class Timetable</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Class Timetable</strong>s</div>
				</div>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="66%" valign="top"  align="left"><strong>Select Class <?PHP echo GetClassName($OptClass); ?></strong></TD>
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
									$query = "select TimeNo from tbclasstimetable where LecNo='$i' And Term = '$Activeterm' And ClassID = '$OptClass'";
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
				</TBODY>
				</TABLE>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Teachers Timetable") {
?>
		  <TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=System%20Setup&mod=admin">Home</a> &gt; <a href="teacherTT.php?subpg=Teacher time table">Timetable</a> &gt; Teachers Timetable</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Teachers Timetable</strong>s</div>
				</div>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="66%" valign="top"  align="left"><strong>Show Time Table For <?PHP echo GET_EMP_NAME($OptTeacher); ?></strong></TD>
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
							  	$LNo = 0;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if(isset($arr_Mon_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Mon_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&wd=Monday&subj=<?PHP echo $arr_Mon_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo][2]); ?></a>
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
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if(isset($arr_Tue_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Tue_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&wd=Tuesday&subj=<?PHP echo $arr_Tue_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Tue_Subj[$LNo][2]); ?></a>
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
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if(isset($arr_Wed_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Wed_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&wd=Wednesday&subj=<?PHP echo $arr_Wed_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo][2]); ?></a>
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
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if(isset($arr_Thur_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Thur_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&wd=Thursday&subj=<?PHP echo $arr_Thur_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo][2]); ?></a>
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
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if(isset($arr_Fri_subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Fri_subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&wd=Friday&subj=<?PHP echo $$arr_Fri_subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Fri_subj[$LNo][2]); ?></a>
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
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if(isset($arr_Sat_Subj[$LNo][1])){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Sat_Subj[$LNo][1] == $i){
?>
											<a title="Time Table" 
	href="JavaScript: newWindow = openWin('TTPopUp.php?EmpId=<?PHP echo $OptTeacher; ?>&wd=Saturday&subj=<?PHP echo $arr_Sat_Subj[$LNo][2]; ?>', '', 'width=300,height=150,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo][2]); ?></a>
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
						<div align="center"></div>						</TD>
					</TR>
				</TBODY>
				</TABLE>
			  <br><br></TD>
		  </TR>
<?PHP
		}
?>
	 </TABLE></TD>
  </TR></TBODY></TABLE>
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
