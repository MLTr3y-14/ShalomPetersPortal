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
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
	}
	//GET ACTIVE TERM
	$query = "select Section from section where Active = '1'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$Activeterm  = $dbarray['Section'];
	if(isset($_GET['class']))
	{
		$ClassID = $_GET['class'];
		$WeekDay = $_GET['wd'];
		$SubjID = $_GET['subj'];
		//GET Subject Teacher
		$query2 = "select EmpId from tbclassteachersubj where ClassID = '$ClassID' And SecID = '$Activeterm' And SubjectID = '$SubjID'";
		$result2 = mysql_query($query2,$conn);
		$dbarray2 = mysql_fetch_array($result2);
		$EmpId  = $dbarray2['EmpId'];
	}
	if(isset($_GET['EmpId']))
	{
		$EmpId = $_GET['EmpId'];
		$WeekDay = $_GET['wd'];
		$SubjID = $_GET['subj'];
		$lecno = $_GET['lecno'];
		//GET Subject Teacher
		$query2 = "select ClassID,TimeNo from tbclasstimetable where Term = '$Activeterm' And WeekDay = '$WeekDay' And SubjID = '$SubjID' And LecNo = '$lecno' And ClassID IN (Select ClassID from tbclassteachersubj where EmpId = '$EmpId')";
		$result2 = mysql_query($query2,$conn);
		$dbarray2 = mysql_fetch_array($result2);
		$ClassID  = $dbarray2['ClassID'];
		$TimeNo  = $dbarray2['TimeNo'];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<!-- saved from url=(0039)http://localhost/myQ/General/Login.aspx -->
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager:: School Management System</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/menu.css" type=text/css rel=stylesheet>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<style type="text/css">
<!--
.style2 {font-size: 12px}
-->
</style>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<TABLE width="100%" bgcolor="#f4f4f4" height="200">
  <TBODY>
  <TR>
    <TD height="300" align=middle style="BACKGROUND-COLOR: transparent" valign="top">
	  <TABLE  border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" style="VERTICAL-ALIGN: middle; TEXT-ALIGN: left">
	  <TR>
	  	<TD>
		<TABLE style="FONT-SIZE: 10pt; FONT-FAMILY: Verdana; HEIGHT: 125px" cellPadding=0 border=0>
			<TBODY>
			<TR>
			  <TD 
			  style="FONT-WEIGHT: bold; COLOR: white; BACKGROUND-COLOR: #6b696b" 
			  align=middle colSpan=2>Time Table Details </TD>
			</TR>
			<TR>
			  <TD width="125" align=right><LABEL for=Login1_UserName>Class Name :</LABEL></TD>
			  <TD width="297"><?PHP echo GetClassName($ClassID); ?></TD>
			</TR>
			<TR>
			  <TD align=right><LABEL 
				for=Login1_Password>Week Day  :</LABEL></TD>
			  <TD><?PHP echo $WeekDay; ?></TD>
			</TR>
			<TR>
			  <TD align=right><LABEL 
				for=Login1_Password>Subject  :</LABEL></TD>
			  <TD><?PHP echo GetSubjectName($SubjID); ?></TD>
			</TR>
			<TR>
			  <TD align=right><LABEL 
				for=Login1_Password>Time Slot  :</LABEL></TD>
			  <TD><?PHP echo $TimeNo; ?></TD>
			</TR>
			<TR>
			  <TD align=right><LABEL 
				for=Login1_Password>Teacher :</LABEL></TD>
			  <TD><?PHP echo GET_EMP_NAME($EmpId); ?></TD>
			</TR>
			<TR>
			  <TD align=center colSpan=2>&nbsp;</TD>
			</TR></TBODY></TABLE>
		
		</TD>
	  </TR>
	 </TABLE>
      </TD></TR></TBODY></TABLE>

</BODY></HTML>
