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
	if($SubPage == "Subject Report"){
		$Page = "Examination Report";
	}elseif($SubPage == "Student Result"){
		$Page = "Examination Report";
	}elseif($SubPage == "Student Ranking"){
		$Page = "Examination Report";
	}else{
		$Page = "Examination";
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
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['Optsearch']))
	{	
		$Optsearch = $_POST['Optsearch'];
		if($Optsearch =="Class Wise"){
			$locksubj = "disabled='disabled'";
		}elseif($Optsearch =="Subject Wise"){
			$lockclass = "disabled='disabled'";
		}
	}
	if(isset($_GET['res']))
	{	
		$SMSStatus = $_GET['res'];
		if($SMSStatus == "error"){
			$errormsg = "<font color = red size = 1>SMS message could not be sent</font>";
		}else{
			$errormsg = "<font color = blue size = 1>SMS Sent successfully</font>";
		}
	}
	if(isset($_GET['totsent']))
	{	
		$totsent = $_GET['totsent'];
		$errormsg = "<font color = blue size = 1>".$totsent." SMS messages was sent successfully</font>";
	}
	if(isset($_POST['OptClass2']))
	{	
		$OptClass2 = $_POST['OptClass2'];
		$OptSubject2 = $_POST['OptSubject2'];
		$OptExam2 = $_POST['OptExam2'];
		$OptStudent2 = $_POST['OptStudent2'];
		
	}
	if(isset($_POST['OptStudent2']))
	{	
		$OptClass2 = $_POST['OptClass2'];
		$OptSubject2 = $_POST['OptSubject2'];
		$OptExam2 = $_POST['OptExam2'];
		$OptStudent2 = $_POST['OptStudent2'];
		
	}
	if(isset($_POST['OptSubject2']))
	{	
		$OptClass2 = $_POST['OptClass2'];
		$OptSubject2 = $_POST['OptSubject2'];
		$OptExam2 = $_POST['OptExam2'];
		$OptStudent2 = $_POST['OptStudent2'];
		
	}
	if(isset($_POST['OptExam2']))
	{	
		$OptClass2 = $_POST['OptClass2'];
		$OptSubject2 = $_POST['OptSubject2'];
		$OptExam2 = $_POST['OptExam2'];
		$OptStudent2 = $_POST['OptStudent2'];
		
	}
	if(isset($_POST['OptClass']))
	{	
		$OptClass = $_POST['OptClass'];
		$_POST['SubmitGo'] = "SubmitGo";
	}
	if(isset($_POST['OptSubject']))
	{	
		$OptSubject = $_POST['OptSubject'];
		$_POST['SubmitGo'] = "SubmitGo";
	}
if(isset($_POST['OptExam']))
	{	
		$OptExam = $_POST['OptExam'];
		$_POST['SubmitGo'] = "SubmitGo";
	}
	
	if(isset($_POST['SubmitGo']))
	{
		$PageHasError = 0;
		$OptClass = $_POST['OptClass'];
		$OptSubject = $_POST['OptSubject'];
		$OptExam = $_POST['OptExam'];
		
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Class Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptSubject']){
			$errormsg = "<font color = red size = 1>Select Subject Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptExam']){
			$errormsg = "<font color = red size = 1>Select Examination Name.</font>";
			$PageHasError = 1;
		}		
		if ($PageHasError == 0)
		{
			$numrows = 0;
			$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$OptClass' and SubjectId='$OptSubject' and ExamId='$OptExam' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result4  = mysql_query($query4,$conn) or die('Error, query failed');
			$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
			$Tot_TD = $row4['numrows'];
			$AvgWidth = 65/ ($Tot_TD+1);
			
			$i=0;
			$query3 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$OptClass' and SubjectId='$OptSubject' and ExamId='$OptExam' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result3 = mysql_query($query3,$conn);
			$num_rows = mysql_num_rows($result3);
			if ($num_rows > 0 ) {
				while ($row = mysql_fetch_array($result3)) 
				{
					$arr_Exam_Id[$i] = $row["ID"];
					$arr_Exam_Setup[$i][1] = $row["ResultType"];
					$arr_Exam_Setup[$i][2] = $row["Percentage"];
					$i = $i+1;
				}
			}
			$query = "select MaxMark from tbexammarkssetup where ClassID='$OptClass' And ExamID='$OptExam' And SubjectID='$OptSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$SubjMaxMark  = $dbarray['MaxMark'];
		}
	}
	if(isset($_POST['SubmitGenerate']))
	{
		$PageHasError = 0;
		$OptClass = $_POST['OptClass'];
		$OptSubject = $_POST['OptSubject'];
		$OptExam = $_POST['OptExam'];
		
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Class Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptSubject']){
			$errormsg = "<font color = red size = 1>Select Subject Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptExam']){
			$errormsg = "<font color = red size = 1>Select Examination Name.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$TotalStudent = $_POST['TotalStud'];
			$TotalTD = $_POST['TotalTD'];
			for($i=1;$i<=$TotalStudent;$i++){
				$AdmnNo = $_POST['admnNo'.$i];
				for($n=1;$n<=$TotalTD;$n++){
					$Exam_Id = $_POST['Exam_Id'.$i.$n];
					$TotMark = $_POST['mark'.$i.$n];
					$Score = $_POST['Score'.$i.$n];
					if($Score <= $TotMark){
						$num_rows = 0;
						$query = "select ID from tbstudentperformance where class_id = '$OptClass' and AdmnNo = '$AdmnNo' and ExamId = '$OptExam' And SubjectId = '$OptSubject' And ResultTypeId = '$Exam_Id' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) 
						{
							$q = "update tbstudentperformance set Marks='$Score' where class_id = '$OptClass' and AdmnNo = '$AdmnNo' and ExamId = '$OptExam' And SubjectId = '$OptSubject' And ResultTypeId = '$Exam_Id' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($q,$conn);
						}else{
							if($Score ==""){
								$q = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Term,Marks,Session_ID,Term_ID) Values ('$OptClass','$AdmnNo','$OptExam','$OptSubject','$Exam_Id','$Activeterm','0','$Session_ID','$Term_ID')";
								$result = mysql_query($q,$conn);
							}else{
								$q = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Term,Marks,Session_ID,Term_ID) Values ('$OptClass','$AdmnNo','$OptExam','$OptSubject','$Exam_Id','$Activeterm','$Score','$Session_ID','$Term_ID')";
								$result = mysql_query($q,$conn);
							}
						}
					}else{
						$errormsg = "<font color = red size = 1>Assesment Score should not be greater than total mark</font>";
					}
				}
			}
			$numrows = 0;
			$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$OptClass' and SubjectId='$OptSubject' and ExamId='$OptExam' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result4  = mysql_query($query4,$conn) or die('Error, query failed');
			$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
			$Tot_TD = $row4['numrows'];
			$AvgWidth = 65/ ($Tot_TD+1);
			
			$i=0;
			$query3 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$OptClass' and SubjectId='$OptSubject' and ExamId='$OptExam' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result3 = mysql_query($query3,$conn);
			$num_rows = mysql_num_rows($result3);
			if ($num_rows > 0 ) {
				while ($row = mysql_fetch_array($result3)) 
				{
					$arr_Exam_Id[$i] = $row["ID"];
					$arr_Exam_Setup[$i][1] = $row["ResultType"];
					$arr_Exam_Setup[$i][2] = $row["Percentage"];
					$i = $i+1;
				}
			}
			$query = "select MaxMark from tbexammarkssetup where ClassID='$OptClass' And ExamID='$OptExam' And SubjectID='$OptSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$SubjMaxMark  = $dbarray['MaxMark'];
		}
	}
	if(isset($_POST['OptShowResult']))
	{	
		if($_POST['OptShowResult']){
			$PageHasError = 0;
			$OptClass = $_POST['OptClass'];
			$OptSubject = $_POST['OptSubject'];
			$OptExam = $_POST['OptExam'];
			
			if(!$_POST['OptClass']){
				$errormsg = "<font color = red size = 1>Select Class Name.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$OptShowResult = $_POST['OptShowResult'];
				$numrows = 0;
				$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$OptClass' and SubjectId='$OptSubject' and ExamId='$OptExam' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result4  = mysql_query($query4,$conn) or die('Error, query failed');
				$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
				$Tot_TD = $row4['numrows'];
				$AvgWidth = 65/ ($Tot_TD+1);
				
				$i=0;
				$query3 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$OptClass' and SubjectId='$OptSubject' and ExamId='$OptExam' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$arr_Exam_Id[$i] = $row["ID"];
						$arr_Exam_Setup[$i][1] = $row["ResultType"];
						$arr_Exam_Setup[$i][2] = $row["Percentage"];
						$i = $i+1;
					}
				}
				$query = "select MaxMark from tbexammarkssetup where ClassID='$OptClass' And ExamID='$OptExam' And SubjectID='$OptSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$SubjMaxMark  = $dbarray['MaxMark'];
			}
		}
	}
	if(isset($_POST['SubmitShowResult']))
	{	
		$PageHasError = 0;
		$OptClass = $_POST['OptClass2'];
		$OptSubject = $_POST['OptSubject2'];
		$OptExam = $_POST['OptExam2'];
		$OptShowResult = "Subject Wise";
		//$OptStudent = $_POST['OptStudent2'];
		if(!$_POST['OptClass2']){
			$errormsg = "<font color = red size = 1>Select Class Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptSubject2']){
			$errormsg = "<font color = red size = 1>Select Subject Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptExam2']){
			$errormsg = "<font color = red size = 1>Select Examination Name.</font>";
			$PageHasError = 1;
		}
		
		//if(!$_POST['OptStudent2']){
		//	$errormsg = "<font color = red size = 1>Select Student to display</font>";
		//	$PageHasError = 1;
		//}
		//if($OptShowResult =="Student Result"){
			//if(!$_POST['OptStudent']){
			//	$errormsg = "<font color = red size = 1>Select Student to display</font>";
			//	$PageHasError = 1;
			//}
		//}	
		if ($PageHasError == 0)
		{
			if($_POST['SubmitShowResult'] !="Go")
			{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mod=report&mth=Display\">";
				exit;
			}else{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mth=Display\">";
				exit;
			}
		}
	}
if(isset($_POST['SubmitStudentResult']))
	{	
		$PageHasError = 0;
		$OptClass = $_POST['OptClass2'];
		//$OptSubject = $_POST['OptSubject2'];
		$OptExam = $_POST['OptExam2'];
		$OptStudent = $_POST['OptStudent2'];
		$OptShowResult = "Student Result";
		//$OptStudent = $_POST['OptStudent2'];
		if(!$_POST['OptClass2']){
			$errormsg = "<font color = red size = 1>Select Class Name.</font>";
			$PageHasError = 1;
		}
		//if(!$_POST['OptSubject2']){
		//	$errormsg = "<font color = red size = 1>Select Subject Name.</font>";
		//	$PageHasError = 1;
		//}
		if(!$_POST['OptExam2']){
			$errormsg = "<font color = red size = 1>Select Examination Name.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['OptStudent2']){
			$errormsg = "<font color = red size = 1>Select Student to display</font>";
		$PageHasError = 1;
		}
		//if($OptShowResult =="Student Result"){
			//if(!$_POST['OptStudent']){
			//	$errormsg = "<font color = red size = 1>Select Student to display</font>";
			//	$PageHasError = 1;
			//}
		//}	
		if ($PageHasError == 0)
		{
			if($_POST['SubmitShowResult'] !="Go")
			{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mod=report&mth=Display\">";
				exit;
			}else{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mth=Display\">";
				exit;
			}
		}
	}
if(isset($_POST['SubmitShowRanking']))
	{	
		$PageHasError = 0;
		$OptClass = $_POST['OptClass2'];
		//$OptSubject = $_POST['OptSubject2'];
		$OptExam = $_POST['OptExam2'];
		$OptShowResult = "Student Ranking";
		//$OptStudent = $_POST['OptStudent2'];
		if(!$_POST['OptClass2']){
			$errormsg = "<font color = red size = 1>Select Class Name.</font>";
			$PageHasError = 1;
		}
		//if(!$_POST['OptSubject2']){
			//$errormsg = "<font color = red size = 1>Select Subject Name.</font>";
			//$PageHasError = 1;
		//}
		if(!$_POST['OptExam2']){
			$errormsg = "<font color = red size = 1>Select Examination Name.</font>";
			$PageHasError = 1;
		}
		
		//if(!$_POST['OptStudent2']){
		//	$errormsg = "<font color = red size = 1>Select Student to display</font>";
		//	$PageHasError = 1;
		//}
		//if($OptShowResult =="Student Result"){
			//if(!$_POST['OptStudent']){
			//	$errormsg = "<font color = red size = 1>Select Student to display</font>";
			//	$PageHasError = 1;
			//}
		//}	
		if ($PageHasError == 0)
		{
			if($_POST['SubmitShowResult'] !="Go")
			{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mod=report&mth=Display\">";
				exit;
			}else{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mth=Display\">";
				exit;
			}
		}
	}
	if(isset($_POST['NotifyParent']))
	{	
		$PageHasError = 0;
		$OptClass = $_POST['OptClass'];
		$OptSubject = $_POST['OptSubject'];
		$OptExam = $_POST['OptExam'];
		$OptShowResult = $_POST['OptShowResult'];
		$OptStudent = $_POST['OptStudent'];
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Class Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptSubject']){
			$errormsg = "<font color = red size = 1>Select Subject Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptExam']){
			$errormsg = "<font color = red size = 1>Select Examination Name.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptShowResult']){
			$errormsg = "<font color = red size = 1>Select result type to display</font>";
			$PageHasError = 1;
		}
		if($OptShowResult =="Student Result"){
			if(!$_POST['OptStudent']){
				$errormsg = "<font color = red size = 1>Select Student to display</font>";
				$PageHasError = 1;
			}
		}	
		if ($PageHasError == 0)
		{
			if($_POST['SubmitShowResult'] !="Go")
			{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mod=report&mth=SMS\">";
				exit;
			}else{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?pg=$OptShowResult&cid=$OptClass&sid=$OptSubject&eid=$OptExam&adm=$OptStudent&mth=SMS\">";
				exit;
			}
		}
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNET Manager</TITLE>
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
.style23 {
	color: #FFFFFF;
	font-weight: bold;
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
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center" >
	  <TR>
	  	<TD valign="top">
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
		if ($SubPage == "Student Performance") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="performance.php?subpg=Student Performance">
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
					  <TD width="51%" valign="top"  align="left"><strong>Class Name</strong> 
					        <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)"> 
					                  <option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					      </TD>
					    <TD width="49%" valign="top"  align="left" ><strong>Subject Name : </strong>
					      <select name="OptSubject" onChange="javascript:setTimeout('__doPostBack(\'OptSubject\',\'\')', 0)">
                            <option value="" selected="selected">Select</option>
                            <?PHP
						
								$query = "select ID,Subj_name from tbsubjectmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							
						
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$subjID = $row["ID"];
								$Subjname = $row["Subj_name"];
								
								if($OptSubject =="$subjID"){
?>
                            <option value="<?PHP echo $subjID; ?>" selected="selected"><?PHP echo $Subjname; ?></option>
                            <?PHP
								}else{
?>
                            <option value="<?PHP echo $subjID; ?>"><?PHP echo $Subjname; ?></option>
                            <?PHP
								}
							}
						}
?>
                          </select></TD>
					</TR>
					<TR>
					  <TD width="51%" valign="top"  align="left"><p><strong>Examination Name
					        <select name="OptExam" onChange="javascript:setTimeout('__doPostBack(\'OptExam\',\'\')', 0)">
                              <option value="" selected="selected">Select</option>
                              <?PHP
							
								$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$ExamID = $row["ExamID"];
										$ExamName = $row["ExamName"];
										
										if($OptExam =="$ExamID"){
?>
                              <option value="<?PHP echo $ExamID; ?>" selected="selected"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}else{
?>
                              <option value="<?PHP echo $ExamID; ?>"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}
									}
								}
?>
                            </select>
					      </strong>
				
					  </p>					    </TD>
					    <TD width="49%" valign="top"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
					  <TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
					  		<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
							<TBODY>
							<TR bgcolor="#666666">
							  <TD width="15%"  align="left" valign="top"><span class="style23">Admn No.</span></TD>
							  <TD width="20%"  align="center" valign="top"><span class="style23">Student Name</span></TD>
<?PHP
								for($i=1;$i<=$Tot_TD;$i++)
								{
									echo "<TD width='$AvgWidth%' align='left' valign='top'><span class='style23'>".$arr_Exam_Setup[$i-1][1]."</span></TD>";
								}
?>
								<TD width="<?PHP echo $AvgWidth; ?>%"  align="center" valign="top"><span class="style23">Total Score</span></TD>
							</TR>
<?PHP
							$counter = 0;
							$query3 = "select AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$AdmnNo = $row["AdmissionNo"];
									$StudName = $row["Stu_Full_Name"];
									$FinalMarks = 0;
									if($counter % 2 == 1){
										$bg="#F2F2F2";
									}else{
										$bg="#FFFFFF";
									}
?>
									<TR>
									  <TD width="15%"  align="left" valign="top" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $AdmnNo; ?>
									  <input type="hidden" name="admnNo<?PHP echo $counter; ?>" value="<?PHP echo $AdmnNo; ?>"></TD>
									  <TD width="20%"  align="center" valign="top" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $StudName; ?></TD>
<?PHP
										$SubjMaxMark = 0;
										for($i=1;$i<=$Tot_TD;$i++)
										{
											echo "<TD width='$AvgWidth%' align='left' valign='top' bgcolor='".$bg."'>";
											echo "<input type='hidden' name='Exam_Id$counter$i' value='".$arr_Exam_Id[$i-1]."'>";
											
											$query = "select Marks from tbstudentperformance where class_id = '$OptClass' and AdmnNo = '$AdmnNo' and ExamId = '$OptExam' And SubjectId = '$OptSubject' And ResultTypeId = '".$arr_Exam_Id[$i-1]."' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$sMarks  = $dbarray['Marks'];
											if($sMarks == 0){
												$sMarks = '';
											}
											$FinalMarks = $FinalMarks + $sMarks;
											$SubjMaxMark = $SubjMaxMark +$arr_Exam_Setup[$i-1][2];
											echo "<input type='hidden' name='mark$counter$i' value='".$arr_Exam_Setup[$i-1][2]."'>";
											echo $sMarks;
											//echo "/".$arr_Exam_Setup[$i-1][2]."</TD>";
											
										}
										if($FinalMarks == 0){
											$FinalMarks = '-';
										}
?>
										<TD width="<?PHP echo $AvgWidth; ?>%"  align="center" valign="top" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $FinalMarks; ?>/<?PHP echo $SubjMaxMark; ?></TD>
									</TR>
<?PHP
								 }
							 }
?>
							</TBODY>
							</TABLE>						</TD>
					</TR>
					<TR>
						<TD colspan="2">
						<div align="center">
						  <input type="hidden" name="TotalStud" value="<?PHP echo $counter; ?>">
						  <input type="hidden" name="TotalTD" value="<?PHP echo $Tot_TD; ?>">
						  <!-- <input name="SubmitGenerate" type="submit" id="SubmitMark" value="Generate Result">
						 <strong>Show Result</strong>
						  <label>
						 <select name="OptShowResult" onChange="javascript:setTimeout('__doPostBack(\'OptShowResult\',\'\')', 0)">
						  	<option value="" selected="selected"></option>
<?PHP
							$OptStudVis = "style='visibility:hidden'";
							if($_SESSION['module'] == "Teacher"){
								if($OptShowResult =="Subject Wise"){
								  echo "<option value='Subject Wise' selected='selected'>Subject Wise</option>";
								}else{
								  echo "<option value='Subject Wise'>Subject Wise</option>";
								}
							}else{
								if($OptShowResult =="Subject Wise"){
								  echo "<option value='Subject Wise' selected='selected'>Subject Wise</option>";
								}else{
								  echo "<option value='Subject Wise'>Subject Wise</option>";
								}
								if($OptShowResult =="Student Result"){
									$OptStudVis = "style=''";
								  echo "<option value='Student Result' selected='selected'>Student Result</option>";
								}else{
								  echo "<option value='Student Result'>Student Result</option>";
								}
								if($OptShowResult =="Student Ranking"){
								  echo "<option value='Student Ranking' selected='selected'>Student Ranking</option>";
								}else{
								  echo "<option value='Student Ranking'>Student Ranking</option>";
								}
							}
?>
						    </select>
						  </label>
						  <select name="OptStudent" <?PHP echo $OptStudVis; ?>>
							<option value="" selected="selected">Select</option>
<?PHP
							$counter = 0;
							if($OptShowResult =="Student Result"){
								$query = "select AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class ='$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$AdmnNo = $row["AdmissionNo"];
										$Full_Name= $row["Stu_Full_Name"];
									
										if($OptStudent =="$AdmnNo"){
?>
											<option value="<?PHP echo $AdmnNo; ?>" selected="selected"><?PHP echo $Full_Name; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $AdmnNo; ?>"><?PHP echo $Full_Name; ?></option>
<?PHP
										}
									}
								}
							}
?>
					  		</select>
						   <input name="SubmitShowResult" type="submit" id="SubmitShowResult" value="Go">
						   <input name="Submitnotify" type="submit" id="notify" value="Notify Parent">
						--></div>						</TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Subject Report") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="performance.php?subpg=Subject Report">
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
					  <TD width="51%" valign="top"  align="left"><strong>Class Name</strong> 
					        <select name="OptClass2" onChange="javascript:setTimeout('__doPostBack(\'OptClass2\',\'\')', 0)"> 
					                  <option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
										
										if($OptClass2 =="$ClassID"){
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
					      </TD>
					    <TD width="49%" valign="top"  align="left" ><strong>Subject Name : </strong>
					      <select name="OptSubject2" <?PHP echo $lockSubj; ?> onChange="javascript:setTimeout('__doPostBack(\'OptSubject2\',\'\')', 0)">
                            <option value="" selected="selected">Select</option>
                            <?PHP
						$counter = 0;
						
//$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And ClassID = '$OptClass' And SecID = '$Activeterm') order by Subj_Priority";
//$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Subj_Priority";
					if($OptClass2 !="")
							{
								$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}else{
								$query = "select ID,Subj_name from tbsubjectmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}
						
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$subjID = $row["ID"];
								$Subjname = $row["Subj_name"];
								
								if($OptSubject2 =="$subjID"){
?>
                            <option value="<?PHP echo $subjID; ?>" selected="selected"><?PHP echo $Subjname; ?></option>
                            <?PHP
								}else{
?>
                            <option value="<?PHP echo $subjID; ?>"><?PHP echo $Subjname; ?></option>
                            <?PHP
								}
							}
						}
?>
                          </select>
					    </TD>
					</TR>
					<TR>
					  <TD width="51%" valign="top"  align="left"><p><strong>Examination Name
					        <select name="OptExam2" onChange="javascript:setTimeout('__doPostBack(\'OptExam2\',\'\')', 0)">
                              <option value="" selected="selected">Select</option>
                              <?PHP
								$counter = 0;
								$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
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
										$ExamID = $row["ExamID"];
										$ExamName = $row["ExamName"];
										
										if($OptExam2 =="$ExamID"){
?>
                              <option value="<?PHP echo $ExamID; ?>" selected="selected"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}else{
?>
                              <option value="<?PHP echo $ExamID; ?>"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}
									}
								}
?>
                            </select>
					      </strong></p>					    </TD>
					    <!--<TD width="49%" valign="top"  align="left" ><p><strong>Student Name
						
						<select name="OptStudent2" onChange="javascript:setTimeout('__doPostBack(\'OptStudent2\',\'\')', 0)">
                          <option value="" selected="selected">Select</option>
<?PHP
							/*$counter = 0;
							//$Stu_Sec = "--Left School--";
							$query = "select AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class ='$OptClass2' order by Stu_Full_Name";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
									$counter = $counter+1;
									$AdmnNo = "";
									$Full_Name = "";
									$AdmnNo = $row["AdmissionNo"];
									$Full_Name= $row["Stu_Full_Name"];
								
									if($OptStudent2 =="$AdmnNo"){
?>
										 <option value="<?PHP echo $AdmnNo; ?>" selected="selected"><?PHP echo $Full_Name; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $AdmnNo; ?>"><?PHP echo $Full_Name; ?></option>
<?PHP
									}
								}
							}*/
?>
                        </select>
						</p></TD>-->
					</TR>
					<TR>
						<TD colspan="2">
						<div align="center">
						  <label></label>
						   <input name="SubmitShowResult" type="submit" id="SubmitShowResult" value="Show Result">
						   <input type="submit" name="NotifyParent" value="Notify Parent">
						</div>						</TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Student Result") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="performance.php?subpg=Student Result">
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
					  <TD width="51%" valign="top"  align="left"><strong>Class Name</strong> 
					        <select name="OptClass2" onChange="javascript:setTimeout('__doPostBack(\'OptClass2\',\'\')', 0)"> 
					                  <option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
										
										if($OptClass2 =="$ClassID"){
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
					      </TD>
					    <!--<TD width="49%" valign="top"  align="left" ><strong>Subject Name : </strong>
					      <select name="OptSubject2" <?PHP echo $lockSubj; ?> onChange="javascript:setTimeout('__doPostBack(\'OptSubject2\',\'\')', 0)">
                            <option value="" selected="selected">Select</option>
                            <?PHP
						$counter = 0;
						
//$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And ClassID = '$OptClass' And SecID = '$Activeterm') order by Subj_Priority";
//$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Subj_Priority";
					if($OptClass2 !="")
							{
								$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}else{
								$query = "select ID,Subj_name from tbsubjectmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}
						
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$subjID = $row["ID"];
								$Subjname = $row["Subj_name"];
								
								if($OptSubject2 =="$subjID"){
?>
                            <option value="<?PHP echo $subjID; ?>" selected="selected"><?PHP echo $Subjname; ?></option>
                            <?PHP
								}else{
?>
                            <option value="<?PHP echo $subjID; ?>"><?PHP echo $Subjname; ?></option>
                            <?PHP
								}
							}
						}
?>
                          </select>
					    </TD>-->
					</TR>
					<TR>
					  <TD width="51%" valign="top"  align="left"><p><strong>Examination Name
					        <select name="OptExam2" onChange="javascript:setTimeout('__doPostBack(\'OptExam2\',\'\')', 0)">
                              <option value="" selected="selected">Select</option>
                              <?PHP
								$counter = 0;
								$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
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
										$ExamID = $row["ExamID"];
										$ExamName = $row["ExamName"];
										
										if($OptExam2 =="$ExamID"){
?>
                              <option value="<?PHP echo $ExamID; ?>" selected="selected"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}else{
?>
                              <option value="<?PHP echo $ExamID; ?>"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}
									}
								}
?>
                            </select>
					      </strong></p>					    </TD>
					    <TD width="49%" valign="top"  align="left" ><p><strong>Student Name
						
						<select name="OptStudent2" onChange="javascript:setTimeout('__doPostBack(\'OptStudent2\',\'\')', 0)">
                          <option value="" selected="selected">Select</option>
<?PHP
							$counter = 0;
							//$Stu_Sec = "--Left School--";
							$query = "select AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class ='$OptClass2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
									$counter = $counter+1;
									$AdmnNo = "";
									$Full_Name = "";
									$AdmnNo = $row["AdmissionNo"];
									$Full_Name= $row["Stu_Full_Name"];
								
									if($OptStudent2 =="$AdmnNo"){
?>
										 <option value="<?PHP echo $AdmnNo; ?>" selected="selected"><?PHP echo $Full_Name; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $AdmnNo; ?>"><?PHP echo $Full_Name; ?></option>
<?PHP
									}
								}
							}
?>
                        </select>
						</p></TD>
					</TR>
					<TR>
						<TD colspan="2">
						<div align="center">
						  <label></label>
						   <input name="SubmitStudentResult" type="submit" id="SubmitStudentResult" value="Show Result">
						   <input type="submit" name="NotifyParent" value="Notify Parent">
						</div>						</TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Student Ranking") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="performance.php?subpg=Student Ranking">
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
					  <TD width="51%" valign="top"  align="left"><strong>Class Name</strong> 
					        <select name="OptClass2" onChange="javascript:setTimeout('__doPostBack(\'OptClass2\',\'\')', 0)"> 
					                  <option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
										
										if($OptClass2 =="$ClassID"){
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
					      </TD>
					    <TD width="49%" valign="top"  align="left" ><p><strong>Examination Name
					        <select name="OptExam2" onChange="javascript:setTimeout('__doPostBack(\'OptExam2\',\'\')', 0)">
                              <option value="" selected="selected">Select</option>
                              <?PHP
								$counter = 0;
								$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
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
										$ExamID = $row["ExamID"];
										$ExamName = $row["ExamName"];
										
										if($OptExam2 =="$ExamID"){
?>
                              <option value="<?PHP echo $ExamID; ?>" selected="selected"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}else{
?>
                              <option value="<?PHP echo $ExamID; ?>"><?PHP echo $ExamName; ?></option>
                              <?PHP
										}
									}
								}
?>
                            </select>
					      </strong></p>					   </TD>
					</TR>
					
					<TR>
						<TD colspan="2">
						<div align="center">
						  <label></label>
						   <input name="SubmitShowRanking" type="submit" id="SubmitShowRanking" value="Show Result">
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
			<TD align="center">Home | About SkoolNET Manager | Contact us | User Agreement | Privacy Policy | Copyright Policy</TD>
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
