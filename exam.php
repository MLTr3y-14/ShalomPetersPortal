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
	global $userNames,$Teacher_EmpID,$Activeterm;
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
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
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
	// filename: photo.php 

	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseEmp.processor.php'; 
	// set a max file size for the html upload form 
	$max_file_size = 100000; // size in bytes OR 30kb
	
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Examination";
	$audit=update_Monitory('Login','Administrator',$Page);
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 3;
	}
	if(isset($_POST['exammaster']))
	{
		$PageHasError = 0;
		$ExaminationID = $_POST['SelExamID'];
		$ExamName = $_POST['ExamName'];
		
		if(!$_POST['ExamName']){
			$errormsg = "<font color = red size = 1>Examination Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['exammaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbexaminationmaster where ExamName = '$ExamName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Examination you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbexaminationmaster(ExamName,Session_ID,Term_ID) Values ('$ExamName','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$ExamName = "";
				}
			}elseif ($_POST['exammaster'] =="Update"){
				$q = "update tbexaminationmaster set ExamName = '$ExamName' where ID = '$ExaminationID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$ExamID = "";
				$ExamName = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['exam_id']))
	{
		$ExaminationID = $_GET['exam_id'];
		$query = "select * from tbexaminationmaster where ID='$ExaminationID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$ExamName  = $dbarray['ExamName'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['exammaster_delete']))
	{
		$Total = $_POST['Totalexam'];
		for($i=1;$i<=$Total;$i++){
			if(isset($_POST['chkExamID'.$i]))
			{
				$chkExamID = $_POST['chkExamID'.$i];
				$q = "Delete From tbexaminationmaster where ID = '$chkExamID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['OptClass']))
	{	
		$OptFunction = "New";
		$OptClass = $_POST['OptClass'];
	}
	if(isset($_POST['SubmitAdd']))
	{
		$PageHasError = 0;
		$OptFunction = "New";
		$examsetupID = $_POST['SelsetupID'];
		$ResultType = $_POST['ResultType'];
		$Percentage = $_POST['Percentage'];
		$OptExams = $_POST['OptExams'];
		$OptClass = $_POST['OptClass'];
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Class.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['ResultType']){
			$errormsg = "<font color = red size = 1>Result Type is empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($Percentage)){
			$errormsg = "<font color = red size = 1>Percentage should be in numbers.</font>";
			$PageHasError = 1;
		}
		if($Percentage > 100){
			$errormsg = "<font color = red size = 1>Percentage should not be greater than 100.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptExams']){
			$errormsg = "<font color = red size = 1>Select Examination.</font>";
			$PageHasError = 1;
		}
		$OptSubject = $_POST['OptSubject'];
		if(!$_POST['OptSubject']){
			$errormsg = "<font color = red size = 1>Select Subject.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$num_rows = 0;
			$query = "select ID from tbclassexamsetup where ClassId = '$OptClass' And SubjectId = '$OptSubject' And ExamId = '$OptExams' And ResultType = '$ResultType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) 
			{
				$errormsg = "<font color = red size = 1>The result details you are trying to add already exist.</font>";
				$PageHasError = 1;
			}else {
				$q = "Insert into tbclassexamsetup(ClassId,SubjectId,ExamId,ResultType,Percentage,Session_ID,Term_ID) Values ('$OptClass','$OptSubject','$OptExams','$ResultType','$Percentage','$Session_ID','$Term_ID')";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				
				$ResultType = "";
				$Percentage = "";
			}
		}
	}
	if(isset($_POST['SubmitUpdate']))
	{
		$PageHasError = 0;
		$OptFunction = $_POST['OptFunction'];
		$examsetupID = $_POST['SelsetupID'];
		$ResultType = $_POST['ResultType'];
		$Percentage = $_POST['Percentage'];
		$OptExams = $_POST['OptExams'];
		$OptClass = $_POST['OptClass'];
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Class.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['ResultType']){
			$errormsg = "<font color = red size = 1>Result Type is empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($Percentage)){
			$errormsg = "<font color = red size = 1>Percentage should be in numbers.</font>";
			$PageHasError = 1;
		}
		if($Percentage > 100){
			$errormsg = "<font color = red size = 1>Percentage should not be greater than 100.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptExams']){
			$errormsg = "<font color = red size = 1>Select Examination.</font>";
			$PageHasError = 1;
		}
		$OptSubject = $_POST['OptSubject'];
		if(!$_POST['OptSubject']){
			$errormsg = "<font color = red size = 1>Select Subject.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$q = "update tbclassexamsetup set ClassId = '$OptClass', SubjectId = '$OptSubject', ExamId = '$OptExams', ResultType = '$ResultType', Percentage = '$Percentage' where ID = '$examsetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			//echo $q;
			$result = mysql_query($q,$conn);
			$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";

			$ResultType = "";
			$Percentage = "";
		}
		if($OptFunction == "Old"){
			$_POST['SubmitSearch'] = "Go";
			$_POST['Optsearch'] = $_POST['Optsearch'];
			$_POST['OptClassWise'] = $_POST['OptClass'];
			$_POST['OptSubjectWise'] = $_POST['OptSubject'];
			$_POST['exid'] = $_POST['OptExams'];
			$rstart = $_POST['st'];
			$rend = $_POST['ed'];
		}elseif($OptFunction == "New"){
			$_POST['SubmitAdd'] = "+";
		}
		
	}
	if(isset($_POST['SubmitReset']))
	{
		$disabled = "";
		$OptFunction = "New";
		$ResultType = $_POST['ResultType'];
		$Percentage = $_POST['Percentage'];
		$OptExams = $_POST['OptExams'];
		$OptClass = $_POST['OptClass'];
		$OptSubject = $_POST['OptSubject'];
		$_POST['SubmitAdd'] = "+";
	}
	
	if(isset($_POST['SubmitSearch']))
	{
		$OptFunction = "Old";
		$OptSelExams = $_POST['OptSelExams'];
		$OptClassWise = $_POST['OptClassWise'];
		$OptSubjectWise = $_POST['OptSubjectWise'];
		if(!isset($_POST['OptSelExams']))
		{	
			$errormsg = "<font color = red size = 1>Select Examnination.</font>";
			$PageHasError = 1;
		}
	}
	if(isset($_GET['setup_id']))
	{
		$OptFunction = "New";
		$examsetupID = $_GET['setup_id'];
		$query = "select * from tbclassexamsetup where ID='$examsetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptClass  = $dbarray['ClassId'];
		$OptSubject  = $dbarray['SubjectId'];
		$OptExams  = $dbarray['ExamId'];
		$ResultType  = $dbarray['ResultType'];
		$Percentage  = $dbarray['Percentage'];
		$_POST['SubmitAdd'] = "+";
	}
	if(isset($_GET['setup_id2']))
	{
		$OptFunction = "Old";
		$examsetupID = $_GET['setup_id2'];
		$query = "select * from tbclassexamsetup where ID='$examsetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptClass  = $dbarray['ClassId'];
		$OptSubject  = $dbarray['SubjectId'];
		$OptExams  = $dbarray['ExamId'];
		$ResultType  = $dbarray['ResultType'];
		$Percentage  = $dbarray['Percentage'];
		$_POST['SubmitSearch'] = "Go";
		
		$OptSelExams = $_GET['exid'];
		$OptClassWise = $_GET['cid'];
		$OptSubjectWise = $_GET['sid'];
		$Optsearch = $_GET['src'];
		$_POST['Optsearch'] = $_GET['src'];
		$_POST['OptClassWise'] = $_GET['cid'];
		$_POST['OptSubjectWise'] = $_GET['sid'];
		
	}
	if(isset($_GET['nExid']))
	{
		$OptFunction = "Old";
		// When clicked on Next, previous, or first navigation link, this function is executed
		$_POST['SubmitSearch'] = "Go";
		
		$OptSelExams = $_GET['nExid'];
		$OptClassWise = $_GET['cid'];
		$OptSubjectWise = $_GET['sid'];
		$Optsearch = $_GET['src'];
		$_POST['Optsearch'] = $_GET['src'];
		$_POST['OptClassWise'] = $_GET['cid'];
		$_POST['OptSubjectWise'] = $_GET['sid'];
	}
	if(isset($_POST['DeleteExamSetup_delete']))
	{
		$OptFunction = $_POST['OptFunction'];
		$Total = $_POST['TotalExamSetup'];
		for($i=1;$i<=$Total;$i++){
			if(isset($_POST['chkExamsetupID'.$i]))
			{
				$chkExamsetupID = $_POST['chkExamsetupID'.$i];
				$q = "Delete From tbclassexamsetup where ID = '$chkExamsetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
		if($OptFunction == "Old"){
			$_POST['SubmitSearch'] = "Go";
			$_POST['Optsearch'] = $_POST['Optsearch'];
			$OptSelExams = $_POST['OptSelExams'];
			$OptClassWise = $_POST['OptClassWise'];
			$OptSubjectWise = $_POST['OptSubjectWise'];
		}elseif($OptFunction == "New"){
			$disabled = "";
			$ResultType = $_POST['ResultType'];
			$Percentage = $_POST['Percentage'];
			$OptExams = $_POST['OptExams'];
			$OptClass = $_POST['OptClass'];
			$OptSubject = $_POST['OptSubject'];
			$_POST['SubmitAdd'] = "+";
		}
		
	}
	if(isset($_POST['Optsearch']))
	{
		$Optsearch = $_POST['Optsearch'];
		if($Optsearch =="Class Wise"){
			$locksubjwise = "disabled='disabled'";
		}elseif($Optsearch =="Subject Wise"){
			$lockclassWise = "disabled='disabled'";
		}
	}
	if(isset($_POST['SubmitGrade']))
	{
		$PageHasError = 0;
		$OptGrade = $_POST['OptGrade'];
		$GradeName = $_POST['GradeName'];
		$GradeFr = $_POST['GradeFr'];
		$GradeTo = $_POST['GradeTo'];
		
		if(!$_POST['GradeName']){
			$errormsg = "<font color = red size = 1>Grade name is empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($GradeFr)){
			$errormsg = "<font color = red size = 1>Grade Percentage should be in numbers</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($GradeTo)){
			$errormsg = "<font color = red size = 1>Grade Percentage should be in numbers.</font>";
			$PageHasError = 1;
		}
		if($GradeFr > $GradeTo or $GradeFr > 100){
			$errormsg = "<font color = red size = 1>Invalid Grade range.</font>";
			$PageHasError = 1;
		}
		if($GradeTo > 100){
			$errormsg = "<font color = red size = 1>Invalid Grade range.</font>";
			$PageHasError = 1;
		}
				
		if ($PageHasError == 0)
		{
			if ($_POST['SubmitGrade'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbGradeDetail where GradeName = '$GradeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The grade you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$numrows = 0;
					$query4   = "SELECT COUNT(*) AS numrows FROM tbGradeDetail Where GradeFrom = '$GradeFr' or GradeTo = '$GradeTo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result4  = mysql_query($query4,$conn) or die('Error, query failed');
					$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
					$numrows = $row4['numrows'];
					if($numrows > 0){
						$errormsg = "<font color = red size = 1>The percentage range you are trying to add already exist.</font>";
						$PageHasError = 1;
					}else{
						$q = "Insert into tbGradeDetail(GradeName,GradeFrom,GradeTo,Session_ID,Term_ID) Values ('$GradeName','$GradeFr','$GradeTo','$Session_ID','$Term_ID')";
						$result = mysql_query($q,$conn);
						$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
						
						$GradeName = "";
						$GradeFr = "";
						$GradeTo = "";
					}
				}
			}elseif ($_POST['SubmitGrade'] =="Update"){
				$q = "update tbGradeDetail set GradeName='$GradeName',GradeFrom='$GradeFr',GradeTo='$GradeTo' where ID = '$OptGrade' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$GradeName = "";
				$GradeFr = "";
				$GradeTo = "";
			}elseif ($_POST['SubmitGrade'] =="Delete"){
				$q = "Delete From tbGradeDetail where ID = '$OptGrade' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
				
				$GradeName = "";
				$GradeFr = "";
				$GradeTo = "";
			}
		}
	}
	if(isset($_POST['OptGrade']))
	{	
		$OptGrade = $_POST['OptGrade'];
		$query = "select * from tbGradeDetail where ID='$OptGrade' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$GradeName  = $dbarray['GradeName'];
		$GradeFr  = $dbarray['GradeFrom'];
		$GradeTo  = $dbarray['GradeTo'];
	}
	if(isset($_POST['SubmitMarksetup']))
	{	
		$OptClassName = $_POST['OptClassName'];
		$OptExamName = $_POST['OptExamName'];
	}
	if(isset($_POST['SubmitSave']))
	{	
		$OptClassName = $_POST['OptClassName'];
		$OptExamName = $_POST['OptExamName'];
		$Total = $_POST['TotalSubj'];
		for($i=1;$i<=$Total;$i++){
			$SubjID = $_POST['SubjID'.$i];
			$MarkID = $_POST['MarkID'.$i];
			$MaxMk = $_POST['Max'.$i];
			$PassMk = $_POST['Pass'.$i];
			if(!is_numeric($_POST['Max'.$i])){
				$PageHasError = 1;
			}
			if(!is_numeric($_POST['Pass'.$i])){
				$PageHasError = 1;
			}
			if($MaxMk >100){
				$errormsg = "<font color = red size = 1>Max Mark Should not be greater than 100%</font>";
				$PageHasError = 1;
			}
			if($PassMk >100){
				$errormsg = "<font color = red size = 1>Pass Mark Should not be greater than 100%</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$num_rows = 0;
				$query = "select ID from tbexammarkssetup where ClassID = '$OptClassName' and ExamID = '$OptExamName' and SubjectID = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$q = "update tbexammarkssetup set MaxMark='$MaxMk',PassMark='$PassMk' where ClassID = '$OptClassName' and ExamID = '$OptExamName' and SubjectID = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				}else {
					$q = "Insert into tbexammarkssetup(ClassID,ExamID,SubjectID,MaxMark,PassMark,Session_ID,Term_ID) Values ('$OptClassName','$OptExamName','$SubjID','$MaxMk','$PassMk','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				}
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
.style21 {font-weight: bold}
.style22 {
	color: #FFFFFF;
	font-weight: bold;
}
.style23 {color: #990000}
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
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD>
		<TABLE width="100%" style="WIDTH: 100%">
        <TBODY>
			<TR>
			  <TD width="222" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="856" align="center" valign="top">
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
		if ($SubPage == "Examination Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="exam.php?subpg=Examination Master">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="left"><p style="margin-left:150px;">Exam Name :&nbsp;&nbsp;&nbsp;
                            <input name="ExamName" type="text" size="55" value="<?PHP echo $ExamName; ?>">
                      </p>
					   </TD>
					</TR>
					<TR>
					   <TD align="left">
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="420" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="62" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="83" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="245" bgcolor="#F4F4F4"><div align="left"><strong>Exam Name</strong></div></td>
                          </tr>
<?PHP
							$counter_exam = 0;
							$query2 = "select * from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_exam = $rstart;
							}else{
								$counter_exam = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbexaminationmaster where INSTR(ExamName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
							}else{
								$query3 = "select * from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_exam = $counter_exam+1;
									$counter = $counter+1;
									$ExamID = $row["ID"];
									$ExamName = $row["ExamName"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkExamID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $ExamID; ?>"></div></td>
										<td><div align="center"><a href="exam.php?subpg=Examination Master&exam_id=<?PHP echo $ExamID; ?>"><?PHP echo $counter_exam; ?></a></div></td>
										<td><div align="left"><a href="exam.php?subpg=Examination Master&exam_id=<?PHP echo $ExamID; ?>"><?PHP echo $ExamName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="exam.php?subpg=Examination Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="exam.php?subpg=Examination Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="exam.php?subpg=Examination Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center">
							 <input type="hidden" name="Totalexam" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelExamID" value="<?PHP echo $ExaminationID; ?>">
							 <input name="exammaster" type="submit" id="exammaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="exammaster_delete" type="submit" id="exammaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="exammaster" type="submit" id="exammaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Manage Weighted Result") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="exam.php?subpg=Manage Weighted Result">
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
					  <TD width="12%" valign="top"  align="left">Class Name </TD>
					  <TD width="21%" valign="top"  align="left">
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)">
						<option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
					  </select></TD>
					  <TD width="8%" valign="top"  align="left">Subject</TD>
					  <TD width="23%" valign="top"  align="left">
					  <select name="OptSubject" <?PHP echo $lockSubj; ?>>
						<option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							if($OptClass !="")
							{
								$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And ClassID = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}else{
								$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}
						}else{
							if($OptClass !="")
							{
								$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}else{
								$query = "select ID,Subj_name from tbsubjectmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
							}
						}
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
					  </select>
					  </TD>
					  <TD width="11%" valign="top"  align="left">Term  Name </TD>
					  <TD width="25%" valign="top"  align="left">
					  <select name="OptExams" style="background-color:#FFFF99">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
									$ExamID = $row["ExamID"];
									$ExamName = $row["ExamName"];
									if($OptExams ==$ExamID){
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
						</TD>
					</TR>
					<TR>
					  <TD width="12%" valign="top"  align="left">Assessment  </TD>
					  <TD width="21%" valign="top"  align="left"><label>
					    <input type="text" name="ResultType" value="<?PHP echo $ResultType; ?>">
					  </label></TD>
					  <TD colspan="2" valign="top"  align="left">Allocated Mark: <input name="Percentage" type="text" size="10" value="<?PHP echo $Percentage; ?>"></TD>
					  <TD width="11%" valign="top"  align="left"><label>
					    <div align="right">
					      <input type="submit" name="SubmitAdd" value="+" <?PHP echo $disabled; ?>>
					      <!--<input type="submit" name="SubmitDel" value="  - " onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">-->
					        </div>
					  </label></TD>
					  <TD width="25%" valign="top"  align="left">
					  <input type="hidden" name="OptFunction" value="<?PHP echo $OptFunction; ?>">
					  <input type="hidden" name="SelsetupID" value="<?PHP echo $examsetupID; ?>">
					  <input type="hidden" name="st" value="<?PHP echo $rstart; ?>">
					  <input type="hidden" name="ed" value="<?PHP echo $rend; ?>">
					  <input name="SubmitUpdate" type="submit" id="SubmitUpdate" value="Update">
					  <input name="SubmitReset" type="submit" id="SubmitReset" value="Clear"></TD>
					</TR>
					<TR>
					   <TD align="left"  colspan="6">
					    <p style="margin-left:150px;">&nbsp;</p>
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
						  <tr>
							  <td colspan="3" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Search  records</strong></div></TD>
							</tr>
							<tr>
								<td width="34%"  align="left" valign="top">
								<?PHP
									if($Optsearch == "Subject Wise"){
?>
					    				<input name="Optsearch" type="radio" value="Subject Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
<?PHP
									}else{
?>
										<input name="Optsearch" type="radio" value="Subject Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
<?PHP
									}
?>
								Subject  
								<select name="OptSubjectWise" <?PHP echo $locksubjwise; ?>>
									<option value="" selected="selected">Select</option>
<?PHP
									$counter = 0;
									if($_SESSION['module'] == "Teacher"){
										$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
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
										
											if($OptSubjectWise =="$subjID"){
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
					  			</select></td>
					  			<td width="33%"  align="left" valign="top">
<?PHP
									if($Optsearch == "Class Wise"){
?>
					    			<input name="Optsearch" type="radio" value="Class Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
<?PHP
									}else{
?>
										<input name="Optsearch" type="radio" value="Class Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
<?PHP
									}
?>
									Class 					    
									<select name="OptClassWise" <?PHP echo $lockclassWise; ?>>
										<option value="" selected="selected">&nbsp;</option>
<?PHP
										if($_SESSION['module'] == "Teacher"){
											$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
												$ClassID = $row["ID"];
												$ClassName = $row["Class_Name"];
												if($OptClassWise ==$ClassID){
?>
												<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $ClassName; ?></option>
<?PHP
											}else{
?>
												<option value="<?PHP echo $ClassID; ?>"><?PHP echo $ClassName; ?></option>
<?PHP
											}
										}
									}
?>
					    			</select>
								</td>
					  			<td width="33%"  align="left" valign="top">
								Exam 
								<select name="OptSelExams" style="background-color:#FFFF99">
<?PHP
									$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$ExamID = $row["ExamID"];
											$ExamName = $row["ExamName"];
											if($OptSelExams ==$ExamID){
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
								<input name="SubmitSearch" type="submit" id="Search" value="Go"></TD>
						 </tr>
						 </table>
						 <table width="672" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="38" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="101" bgcolor="#F4F4F4"><div align="center" class="style21">Class Name</div></td>
							<td width="103" bgcolor="#F4F4F4"><div align="center" class="style21">Subject</div></td>
							<td width="121" bgcolor="#F4F4F4"><div align="center" class="style21">Result Details</div></td>
                          </tr>
						  <tr>
                            <td width="38" bgcolor="#FFFFFF">&nbsp;</td>
                            <td width="101" bgcolor="#FFFFFF">&nbsp;</td>
                            <td width="103" bgcolor="#FFFFFF">&nbsp;</td>
							<td width="121" bgcolor="#FFFFFF">
								<table width="360" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="177"><strong>ASSESSMENT</strong></td>
									<td width="177"><strong>EXAM NAME</strong></td>
									<td width="85"><strong>ALL. MARK </strong></td>
								  </tr>
								 </table></td>
                          </tr>
						  
<?PHP
						if($OptFunction == "New")
						{
							$ClassId = $OptClass;
							$SubjID = $OptSubject;
							$OptSelExams = $OptExams;
							$counter1 = 0;
							if($_SESSION['module'] == "Teacher"){
								//$Teacher_EmpID
								$query2 = "select Distinct SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' And ClassId ='$ClassId' And SubjectId ='$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							}else{
								$query2 = "select Distinct SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' And ClassId ='$ClassId' And SubjectId ='$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_Subj = $rstart;
							}else{
								$counter_Subj = $rstart;
							}
?>
							  <tr>
								<td valign="top"><div align="center">1</div></td>
								<td valign="top"><div align="center"><?PHP echo GetClassName($ClassId); ?></div></td>
								<td valign="top"><div align="center"><?PHP echo GetSubjectName($SubjID); ?></div></td>
								<td>
								<table width="360" border="0" align="center" cellpadding="3" cellspacing="3">
<?PHP
									$TotalPer=0; 
									$query4 = "select ID,ExamId,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And SubjectId ='$SubjID' And ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result4 = mysql_query($query4,$conn);
									$num_rows4 = mysql_num_rows($result4);
									if ($num_rows4 > 0 ) {
										while ($row4 = mysql_fetch_array($result4)) 
										{
											$counter1 = $counter1 +1;
											$SetupId = $row4["ID"];
											$ExamId = $row4["ExamId"];
											$ResultType = $row4["ResultType"];
											$Percentage = $row4["Percentage"];
											$TotalPer = $TotalPer +$Percentage;
?>
											  <tr>
												<td width="177"><input name="chkExamsetupID<?PHP echo $counter1; ?>" type="checkbox" value="<?PHP echo $SetupId; ?>">
												<a href="exam.php?subpg=Manage Weighted Result&setup_id=<?PHP echo $SetupId; ?>"><?PHP echo $ResultType; ?></a></td>
												<td width="177">
												<a href="exam.php?subpg=Manage Weighted Result&setup_id=<?PHP echo $SetupId; ?>"><?PHP echo GetExamName($ExamId); ?></a></td>
												<td width="85"><a href="exam.php?subpg=Manage Weighted Result&setup_id=<?PHP echo $SetupId; ?>"><?PHP echo $Percentage; ?></a></td>
											  </tr>
<?PHP
										 }
									}
?>
								  <tr>
									<td width="177">&nbsp;</td>
									<td width="177" align="right"><hr><strong>Total</strong><hr></td>
									<td width="85"><hr><?PHP echo $TotalPer; ?>%<hr></td>
								  </tr>
								</table>
								</td>
							  </tr>
<?PHP
						}elseif($OptFunction == "Old"){
							if($_SESSION['module'] == "Teacher"){
								//$Teacher_EmpID
								if(isset($_POST['SubmitSearch']))
								{
									$Optsearch = $_POST['Optsearch'];
									if($Optsearch == "Class Wise"){
										$OptClassWise = $_POST['OptClassWise'];
										$query2 = "select Distinct SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SubjectID IN (Select SubjectId from tbclassexamsetup where ClassId = '$OptClassWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
									}elseif($Optsearch == "Subject Wise"){
										$OptSubjectWise = $_POST['OptSubjectWise'];
										$query2 = "select ClassID,SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And ClassID IN (Select ClassId from tbclassexamsetup where SubjectId='$OptSubjectWise' and ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And SubjectId = '$OptSubjectWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									}else{
										$query2 = "select Distinct SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SubjectID IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
									}
								}else{
									$query2 = "select Distinct SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID'  And SubjectID IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
								}
							}else{
								if(isset($_POST['SubmitSearch']))
								{
									$Optsearch = $_POST['Optsearch'];
									if($Optsearch == "Class Wise"){
										$OptClassWise = $_POST['OptClassWise'];
										$query2 = "select Distinct SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ClassId = '$OptClassWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
									}elseif($Optsearch == "Subject Wise"){
										$OptSubjectWise = $_POST['OptSubjectWise'];
										$query2 = "select ClassId,SubjectId from tbclasssubjectrelation where ClassId IN (Select ClassId from tbclassexamsetup where SubjectId='$OptSubjectWise' and ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And SubjectId = '$OptSubjectWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									}else{
										$query2 = "select Distinct SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
									}
								}else{
									$query2 = "select Distinct SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
								}
							}
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_Subj = $rstart;
							}else{
								$counter_Subj = $rstart;
							}
							
							$counter1 = 0;
							if($_SESSION['module'] == "Teacher"){
								if(isset($_POST['SubmitSearch']))
								{
									$Optsearch = $_POST['Optsearch'];
									if($Optsearch == "Class Wise"){
										$OptClassWise = $_POST['OptClassWise'];
										$query3 = "select ClassId,SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SubjectID IN (Select SubjectId from tbclassexamsetup where ClassId='$OptClassWise' and ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And ClassID = '$OptClassWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
									}elseif($Optsearch == "Subject Wise"){
										$OptSubjectWise = $_POST['OptSubjectWise'];
										$query3 = "select ClassId,SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And ClassId IN (Select ClassId from tbclassexamsetup where SubjectId='$OptSubjectWise' and ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And SubjectId = '$OptSubjectWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
									}else{
										$query3 = "select ClassId,SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
									}
								}else{
									$query3 = "select ClassId,SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
								}
							}else{
								if(isset($_POST['SubmitSearch']))
								{
									$Optsearch = $_POST['Optsearch'];
									if($Optsearch == "Class Wise"){
										$OptClassWise = $_POST['OptClassWise'];
										$query3 = "select ClassId,SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ClassId='$OptClassWise' and ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And ClassId = '$OptClassWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
										
									}elseif($Optsearch == "Subject Wise"){
										$OptSubjectWise = $_POST['OptSubjectWise'];
										$query3 = "select ClassId,SubjectId from tbclasssubjectrelation where ClassId IN (Select ClassId from tbclassexamsetup where SubjectId='$OptSubjectWise' and ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And SubjectId = '$OptSubjectWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
									}else{
										$query3 = "select ClassId,SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
									}
								}else{
									$query3 = "select ClassId,SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID LIMIT $rstart,$rend";
								}
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_Subj = $counter_Subj+1;
									$counter = $counter+1;
									$ClassId = $row["ClassId"];
									$SubjID = $row["SubjectId"];
?>
									  <tr>
										<td valign="top"><div align="center"><?PHP echo $counter_Subj; ?></div></td>
										<td valign="top"><div align="center"><?PHP echo GetClassName($ClassId); ?></div></td>
										<td valign="top"><div align="center"><?PHP echo GetSubjectName($SubjID); ?></div></td>
										<td>
										<table width="360" border="0" align="center" cellpadding="3" cellspacing="3">
<?PHP
										$TotalPer=0; 
										$query4 = "select ID,ExamId,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And SubjectId ='$SubjID' And ExamId = '$OptSelExams' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result4 = mysql_query($query4,$conn);
										$num_rows4 = mysql_num_rows($result4);
										if ($num_rows4 > 0 ) {
											while ($row4 = mysql_fetch_array($result4)) 
											{
												$counter1 = $counter1+1;
												$SetupId = $row4["ID"];
												$ExamId = $row4["ExamId"];
												$ResultType = $row4["ResultType"];
												$Percentage = $row4["Percentage"];
												$TotalPer = $TotalPer +$Percentage;
?>
												  <tr>
													<td width="177"><input name="chkExamsetupID<?PHP echo $counter1; ?>" type="checkbox" value="<?PHP echo $SetupId; ?>">
													<a href="exam.php?subpg=Manage Weighted Result&setup_id2=<?PHP echo $SetupId; ?>&exid=<?PHP echo $OptSelExams; ?>&cid=<?PHP echo $OptClassWise; ?>&sid=<?PHP echo $OptSubjectWise; ?>&st=<?PHP echo $rstart; ?>&ed=<?PHP echo $rend; ?>&src=<?PHP echo $Optsearch; ?>"><?PHP echo $ResultType; ?></a></td>
													<td width="177">
													<a href="exam.php?subpg=Manage Weighted Result&setup_id2=<?PHP echo $SetupId; ?> &exid=<?PHP echo $OptSelExams; ?>&cid=<?PHP echo $OptClassWise; ?>&sid=<?PHP echo $OptSubjectWise; ?>&st=<?PHP echo $rstart; ?>&ed=<?PHP echo $rend; ?>&src=<?PHP echo $Optsearch; ?>"><?PHP echo GetExamName($ExamId); ?></a></td>
													<td width="85">
													<a href="exam.php?subpg=Manage Weighted Result&setup_id2=<?PHP echo $SetupId; ?> &exid=<?PHP echo $OptSelExams; ?>&cid=<?PHP echo $OptClassWise; ?>&sid=<?PHP echo $OptSubjectWise; ?>&st=<?PHP echo $rstart; ?>&ed=<?PHP echo $rend; ?>&src=<?PHP echo $Optsearch; ?>"><?PHP echo $Percentage; ?></a></td>
												  </tr>
<?PHP
											 }
										}
?>
										  <tr>
											<td width="177">&nbsp;</td>
											<td width="177" align="right"><hr><strong>Total</strong><hr></td>
											<td width="85"><hr><?PHP echo $TotalPer; ?>%<hr></td>
										  </tr>
										</table>
										</td>
									  </tr>
<?PHP
								 }
							 }
						}
?>
                        </table>
<?PHP
						if(!isset($_POST['SubmitAdd']))
						{
?>
							<p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="exam.php?subpg=Manage Weighted Result&st=0&ed=<?PHP echo $rend; ?>&nExid=<?PHP echo $OptSelExams; ?>&cid=<?PHP echo $OptClassWise; ?>&sid=<?PHP echo $OptSubjectWise; ?>&src=<?PHP echo $Optsearch; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="exam.php?subpg=Manage Weighted Result&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&nExid=<?PHP echo $OptSelExams; ?>&cid=<?PHP echo $OptClassWise; ?>&sid=<?PHP echo $OptSubjectWise; ?>&src=<?PHP echo $Optsearch; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="exam.php?subpg=Manage Weighted Result&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&nExid=<?PHP echo $OptSelExams; ?>&cid=<?PHP echo $OptClassWise; ?>&sid=<?PHP echo $OptSubjectWise; ?>&src=<?PHP echo $Optsearch; ?>">Next</a> </p>
<?PHP
						}
?>
							</TD>
					</TR>
					<TR>
						 <TD colspan="6">
						 <div align="center">
						   	 <input type="hidden" name="TotalExamSetup" value="<?PHP echo $counter1; ?>">
							 <input name="DeleteExamSetup_delete" type="submit" id="DeleteExamSetup_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						 </div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Grade master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="exam.php?subpg=Grade master">
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
							<select name="Selectddd" size="1" multiple="multiple" style="width:250px;">
						      <option>
							  Grade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Grade Range</option>
						      </select>
							 <select name="OptGrade" size="20" multiple style="width:250px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptGrade\',\'\')', 0)">
<?PHP
								$counter = 0;
								$query = "select * from tbGradeDetail where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by GradeTo desc";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$gradeID = $row["ID"];
										$GrdName = $row["GradeName"];
										$Grdfrom = $row["GradeFrom"];
										$Grdto = $row["GradeTo"];
										
										if(strlen($GrdName) >= 2){
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}else{
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										
										if($OptGrade =="$gradeID"){
?>
											<option value="<?PHP echo $gradeID; ?>" selected="selected"><?PHP echo $GrdName; ?><?PHP echo $spacer; ?><?PHP echo $Grdfrom; ?>&nbsp;&nbsp;T0&nbsp;&nbsp;<?PHP echo $Grdto; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $gradeID; ?>"><?PHP echo $GrdName; ?><?PHP echo $spacer; ?><?PHP echo $Grdfrom; ?>&nbsp;&nbsp;T0&nbsp;&nbsp;<?PHP echo $Grdto; ?></option>
<?PHP
										}
									}
								}
?>
						      </select>
						  </TD>
					  <TD width="66%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <TABLE width="92%" align="center" cellpadding="7">
							<TBODY>
							<TR>
							  <TD width="36%"  align="left"><strong>Grade Information </strong></TD>
							  <TD width="64%"  align="left" valign="top">&nbsp;</TD>
							</TR>
							<TR>
							  <TD width="36%"  align="left">Grade ID :</TD>
							  <TD width="64%"  align="left" valign="top">
									<input name="GradeID" type="text" size="5" value="<?PHP echo $OptGrade; ?>" disabled="disabled">
							  </TD>
							</TR>
							<TR>
							  <TD width="36%"  align="left">Grade Name  :</TD>
							  <TD width="64%"  align="left" valign="top">
									<input name="GradeName" type="text" size="10" value="<?PHP echo $GradeName; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="36%"  align="left">Percentage Between </TD>
							  <TD width="64%"  align="left" valign="top"><input name="GradeFr" type="text" size="5" value="<?PHP echo $GradeFr; ?>">
							  To 
							     <input name="GradeTo" type="text" size="5" value="<?PHP echo $GradeTo; ?>">
							  </TD>
							</TR>
						</TBODY>
						</TABLE>
						<p>&nbsp;</p>
						<div align="center">
						  <input type="submit" name="SubmitGrade" value="Create New">
						  <input type="submit" name="SubmitGrade" value="Update">
						  <input type="submit" name="SubmitGrade" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
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
		}elseif ($SubPage == "Class Subject Mark Setup") {
?>
				<?PHP $kol = 1;
				echo $errormsg.$kol; ?>
				<form name="form1" method="post" action="exam.php?subpg=Class Subject Mark Setup">
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
					  <TD width="38%" valign="top"  align="left"><strong>Select Class Name
					    <select name="OptClassName">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
										
										if($OptClassName =="$ClassID"){
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
					  <TD width="62%" valign="top"  align="left" ><strong>Exam Name: </strong>
					    <select name="OptExamName">
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
										$ExamID = $row["ID"];
										$ExamName = $row["ExamName"];
										
										if($OptExamName =="$ExamID"){
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
					    <input name="SubmitMarksetup" type="submit" id="SubmitSearch" value="Go">					  </TD>
					</TR>
					<TR>
					  <TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
					  		<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
							<TBODY>
							<TR bgcolor="#00CCFF">
							  <TD width="28%"  align="left" valign="top">&nbsp;</TD>
							  <TD width="20%"  align="center" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Max Mark (%) </span></TD>
							  <TD width="21%"  align="center" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Pass Mark (%) </span></TD>
							  <TD width="31%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Exam Name </span></TD>
							</TR>
<?PHP
							$counter = 0;
							if($_SESSION['module'] == "Teacher"){
								$query3 = "select SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And ClassId = '$OptClassName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							}else{
								$query3 = "select SubjectId from tbclasssubjectrelation where ClassId = '$OptClassName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							}
							
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$SubjectId = $row["SubjectId"];
									

									$query = "select ID,MaxMark,PassMark from tbexammarkssetup where ClassID = '$OptClassName' And ExamID = '$OptExamName' And SubjectID = '$SubjectId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$MarkID  = $dbarray['ID'];
									$MaxMark  = $dbarray['MaxMark'];
									$PassMark  = $dbarray['PassMark'];
									
?>
									<TR>
									  <TD width="28%" bgcolor="#00CCFF" align="left" valign="top"><span class="style3 style22"><?PHP echo GetSubjectName($SubjectId); ?></span></TD>
									  <TD width="20%" align="left" valign="top" bgcolor="#FFFFCC"><span class="style3">&nbsp;
									    <input type="hidden" name="SubjID<?PHP echo $counter; ?>" value="<?PHP echo $SubjectId; ?>">
										<input type="hidden" name="MarkID<?PHP echo $counter; ?>" value="<?PHP echo $MarkID; ?>">
									    <input name="Max<?PHP echo $counter; ?>" type="text" size="15" value="<?PHP echo $MaxMark; ?>">
									  </span></TD>
									  <TD width="21%" align="left" valign="top" bgcolor="#FFFFCC"><span class="style3">&nbsp;
									    <input name="Pass<?PHP echo $counter; ?>" type="text" size="15" value="<?PHP echo $PassMark; ?>">
									  </span></TD>
									  <TD width="31%" align="left" valign="top" bgcolor="#FFFFCC"><span class="style3"><?PHP echo GetExamName($OptExamName); ?></span></TD>
									</TR>
<?PHP
								 }
							 }
?>
							</TBODY>
							</TABLE>
						</TD>
					</TR>
					<TR>
						<TD colspan="2">
						<div align="center">
						  <input type="hidden" name="TotalSubj" value="<?PHP echo $counter; ?>">
						  <input name="SubmitSave" type="submit" id="SubmitMark" value="Update ">
						</div>
						<p align="center"><span class="style23">Please Note:</span> The Max Mark and the pass mark should be in percentage (%) </p></TD>
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
			<TD align="center"> Copyright © <?PHP echo date_default_timezone_set('Y'); ?> SkoolNet Manager. All right reserved.</TD>
		  </TR>
		</TABLE>	  
	  </TD>
	</TR>
</TBODY>
</TABLE> 	
</BODY></HTML>
