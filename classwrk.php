<?PHP
	session_start();
	$end=time();
	global $userNames;
	global $TimerOut;
	$TimerOut="False";
	$file = "welcome.php?pg=";
/*    session variables like user login info ..    */ 
	
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
		$SysModule = $_GET['mod'];
	}
	$dat = date('Y'.'-'.'m'.'-'.'d');
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
    include("start_timer.php");
    include("end_timer.php");
	
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
		$EmpID  = $dbarray['EmpID'];
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
		$userNames = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$userNames'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpID  = $dbarray['EmpID'];
	}
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
		
	//GET STAFF DEFAULT SUBJECT
	$query = "select SubjectId from tbclassteachersubj where EmpId='$EmpID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$DefaultSubj  = $dbarray['SubjectId'];
		
	//GET ACTIVE TERM AND SESSION
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$ActiveValue = 1;
	$ActiveSession  = Get_Active_Session(1);
	// filename: photo.php 

	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseNote.processor.php'; 
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
	$Page = "Class Work";
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_GET['subjID']))
	{
		$OptSelSubject = $_GET['subjID'];
	}
	if(isset($_POST['OptSelSubject']))
	{	
		$OptSelSubject = $_POST['OptSelSubject'];
	}
	if(isset($_POST['OptSubject']))
	{	
		$OptSubject = $_POST['OptSubject'];
	}
	if(isset($_POST['uploadnote'])){
		$btt = "disabled=disabled";
		?> <script>document.location = 'classwrk.php?note_upload=true';</script> <?php
	}
	if(isset($_POST['submitClear'])){
		$btt = "disabled=disabled";
		$OptSubject = "" ;
		$NoteDecription = "";
		$file="";
	}
	if(isset($_POST['Note_delete']))
	{
		$Total = $_POST['Totalnote'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkNoteID'.$i]))
			{
				$chkNoteID = $_POST['chkNoteID'.$i];
				$filename = $_POST['file'.$i];
				$q = "Delete From tbstudentnote where ID = '$chkNoteID'";
				$result = mysql_query($q,$conn);
				unlink("classnote/".$filename."");
			}
		}
	}
	if(isset($_POST['FindStudent'])){
		$btt = "disabled=disabled";
		?> <script>document.location = 'classwrk.php?find_stud=true';</script> <?php
	}
	if(isset($_POST['PostReply'])){
		?> <script>document.location = 'classwrk.php?open_post=true';</script> <?php
	}
	if(isset($_POST['SubmitFindStudent']))
	{	
		$SearchOpt = $_POST['SearchOpt'];
		if($SearchOpt == ""){
			$errormsg = "<font color = red size = 1>Select search option</font>";
		}else{
			if($SearchOpt =="email"){
				$Email = $_POST['Email'];
			}elseif($SearchOpt =="names"){
				$StudentName = $_POST['StudentName'];
			}elseif($SearchOpt =="State"){
				$optState = $_POST['optState'];
			}elseif($SearchOpt =="Admno"){
				$AdmissionNo = $_POST['AdmissionNo'];
			}elseif($SearchOpt =="DOB"){
				$find_Dy = $_POST['find_Dy'];
				$find_Mth = $_POST['find_Mth'];
				$find_Yr = $_POST['find_Yr'];
			}
		}
	}
	if(isset($_POST['CloseFind'])){
		$btt = "disabled=disabled";
		?> <script>document.location = 'classwrk.php?Close_Find=true';</script> <?php
	}
	if(isset($_POST['SendEmail']))
	{
		$PageHasError = 0;
		$OptSubject = $_POST['OptSubject'];
		$ToEmails = $_POST['ToEmails'];
		$EmailSubject = $_POST['EmailSubject'];
		$Message = $_POST['Message'];
			
		if(!$_POST['OptSubject'] and $_POST['ToEmails'] == ""){
			$errormsg = "<font color = red size = 1>Destination email not selected or entered</font>";
			$PageHasError = 1;
		}
		if(!$_POST['EmailSubject']){
			$errormsg = "<font color = red size = 1>Subject is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['Message']){
			$errormsg = "<font color = red size = 1>Message is empty.</font>";
			$PageHasError = 1;
		}
		
		
		if ($PageHasError == 0)
		{
			//Sending Email
			require("../phpmailer/class.phpmailer.php");
			$mail = new PHPMailer();
			
			$mail->IsSMTP();                                      // set mailer to use SMTP
			$mail->Host = "smtp.lagospastoralcollege.org";  // specify main and backup server
			$mail->SMTPAuth = true;     // turn on SMTP authentication
			$mail->Username = "info@lagospastoralcollege.org";  // SMTP username
			$mail->Password = "abc123"; // SMTP password
			
			if(isset($_POST['OptSubject']))
			{
				$query1 = "select Stu_Email,Stu_Full_Name from tbstudentmaster where Stu_Class IN(Select ClassId from tbclasssubjectrelation where SubjectId = '$OptSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
				$result1 = mysql_query($query1,$conn);
				$num_rows1 = mysql_num_rows($result1);
				if ($num_rows1 > 0 ) {
					while ($row1 = mysql_fetch_array($result1)) 
					{
						$StuEmail = $row1["Stu_Email"];
						$FullName = $row1["Stu_Full_Name"];		
						
						$mail->AddAddress($StuEmail, $FullName);
						$mail->From = "info@lagospastoralcollege.org";
						$mail->FromName = "LPC Administrator";
						$mail->AddReplyTo("info@lagospastoralcollege.org", "LPC Administrator");
						
						$mail->WordWrap = 50;                                 // set word wrap to 50 characters
						$mail->IsHTML(true);                                  // set email format to HTML
						
						$mail->Subject = $EmailSubject;
						$text_body  = $Message;
						$mail->Body    = $text_body;
						$mail->AltBody = "";
						if(!$mail->Send())
						{
						   echo "Message could not be sent. <p>";
						   echo "Mailer Error: " . $mail->ErrorInfo;
						   echo "<meta http-equiv=\"Refresh\" content=\"0;url=classwrk.php?subpg=Email Class&msg=error\">";
						   exit;
						}
						$mail->ClearAddresses();
						$mail->ClearAttachments();
					}
				}
			}
			if($_POST['ToEmails'] !="")
			{
				$arrEmail=explode (',', $ToEmails);
				$i=0;
				while(isset($arrEmail[$i])){
					$query1 = "select Stu_Email,Stu_Full_Name from tbstudentmaster where Stu_Email = '$arrEmail[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result1 = mysql_query($query1,$conn);
					$num_rows1 = mysql_num_rows($result1);
					if ($num_rows1 > 0 ) {
						while ($row1 = mysql_fetch_array($result1)) 
						{
							$StuEmail = $row1["Stu_Email"];
							$FullName = $row1["Stu_Full_Name"];	
						
							$mail->AddAddress($StuEmail, $FullName);
							$mail->From = "info@lagospastoralcollege.org";
							$mail->FromName = "LPC Administrator";
							$mail->AddReplyTo("info@lagospastoralcollege.org", "LPC Administrator");
							
							$mail->WordWrap = 50;                                 // set word wrap to 50 characters
							$mail->IsHTML(true);                                  // set email format to HTML
							
							$mail->Subject = $EmailSubject;
							// Plain text body (for mail clients that cannot read HTML)
							$text_body  = $Message;
							$mail->Body    = $text_body;
							$mail->AltBody = "";
							
							if(!$mail->Send())
							{
							   echo "Message could not be sent. <p>";
							   echo "Mailer Error: " . $mail->ErrorInfo;
							   echo "<meta http-equiv=\"Refresh\" content=\"0;url=classwrk.php?subpg=Email Class&msg=error\">";
							   exit;
							}
							$mail->ClearAddresses();
							$mail->ClearAttachments();
						}
					}
					$i=$i+1;
				}
			}		
			$errormsg = "<font color = blue size = 1>Email Sent successfully</font>";				
			$OptSubject = "";
			$ToEmails = "";
			$EmailSubject = "";
			$Message = "";
		}
	}
	if(isset($_GET['msg']))
	{
		$errormsg = "<font color = red size = 1>Message could not be sent.</font>";	
	}
	//Get Current Employee Details
	$query = "select * from tbemployeemasters where ID='$EmpID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpName  = formatDatabaseStr($dbarray['EmpName']);
	$empDept  = $dbarray['EmpDept'];
	$empDesign  = $dbarray['EmpDesig'];
	$OptCat  = $dbarray['catCode'];
	$EmpFilename  = $dbarray['Photo'];
	if($EmpFilename==""){
		$EmpFilename = "NoImage.jpg";
	}
	$isTeacher = $dbarray['isTeaching'];
	$arrDate = $dbarray['EmpDOJ'];
	if($isTeacher == 1){
		$isTeacher = "True";
	}elseif($isTeacher == 0){
		$isTeacher = "False";
	}
	if(isset($_POST['SubmitShare']))
	{
		$PageHasError = 0;
		$OptSubject = $_POST['OptSubject'];
		$postissue = $_POST['postissue'];
		if($postissue == ""){
			$errormsg = "<font color = red size = 1>Post is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptSubject']){
			$errormsg = "<font color = red size = 1>Select Class</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{	
			$num_rows = 0;
			$q = "Insert into tbclasspost(SubjID,SessionID,PostDate,PostMsg,SenderID,Section,SenderType,Session_ID,Term_ID) Values ('$OptSubject','$ActiveSession','$dat','$postissue','$EmpID','$Activeterm','Staff','$Session_ID','$Term_ID')";
			$result = mysql_query($q,$conn);
			$postissue = "";
		}
	}
	if(isset($_POST['SendReply']))
	{
		$PageHasError = 0;
		$PostID = $_POST['PostID'];
		$fSubComment = $_POST['fSubComment'];
		
		if(!$_POST['fSubComment']){
			$errormsg = "<font color = red size = 1>Post is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['PostID']){
			$errormsg = "<font color = red size = 1>Invalid Post ID</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$num_rows = 0;

			$q = "Insert into tbclasspost_reply(ClassPostID,Respondant,PostDate,PostMsg,SenderType,Session_ID,Term_ID) Values ('$PostID','$EmpID','$dat','$fSubComment','Staff','$Session_ID','$Term_ID')";
			$result = mysql_query($q,$conn);
			$fSubComment = "";
		}
	}
	if(isset($_GET['post_id']))
	{
		$postid = $_GET['post_id'];
		$query = "select SubjID from tbclasspost where ID='$postid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptSubject  = $dbarray['SubjID'];
		
		$q = "Delete From tbclasspost where ID = '$postid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);

		$q = "Delete From tbclasspost_reply where ClassPostID = '$postid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);
	}
	$PostAction = "lastest";
	if(isset($_GET['Oth_post_id']))
	{
		$SelPostID = $_GET['Oth_post_id'];
		$PostAction = "Others";
		$query = "select SubjID from tbclasspost where ID='$SelPostID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptSubject  = $dbarray['SubjID'];
	}									
	if($OptSubject==""){
		$OptSubject = $DefaultSubj;
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
.style23 {color: #FFFFFF}
.style24 {
	font-weight: bold;
	color: #FFFFFF;
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
		if ($SubPage == "Submit Class Note") {
?>
						<?PHP echo $errormsg; ?>
						
						<TABLE width="89%" style="WIDTH: 98%">
							<TBODY>
							<TR>
							  <TD width="64%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid; background:#FFFFCC">
							  		<form id="Upload2" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post">
									<div id="fmr_lic_div">
							  		<TABLE width="80%" align="center" cellpadding="7">
										<TBODY>
										<TR>
										  <TD width="24%"  align="left"><div align="right">Select Subject</div></TD>
										  <TD width="50%"  align="left" valign="top">
											<select name="OptSubject">
                            					<option value="" selected="selected">Select</option>
<?PHP
											$counter = 0;
											if($_SESSION['module'] == "Teacher"){
												if($OptClass !="")
												{
													$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And ClassID = '$Optfaculty' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
												}else{
													$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
												}
											}else{
												if($OptClass !="")
												{
													$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$Optfaculty' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
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
										  <TD width="26%"  align="left" rowspan="4" valign="top"><input id="submit" type="submit" name="submit" value="Upload"></TD>
										</TR>
										<TR>
										  <TD width="24%"  align="left"><div align="right">Note Description </div></TD>
										  <TD width="50%"  align="left" valign="top"><textarea name="NoteDecription" cols="40" rows="3"><?PHP echo $NoteDecription; ?></textarea></TD>
										</TR>
										<TR>
										  <TD width="24%"  align="left"><div align="right">Browse File</div></TD>
										  <TD width="50%"  align="left" valign="top"><input id="file" type="file" name="file"></TD>
										</TR>
										<TR>
										  <TD width="24%"  align="left">&nbsp;</TD>
										  <TD width="50%"  align="left" valign="top">&nbsp;</TD>
										</TR>
									</TBODY>
									</TABLE></div>
							  		</form>
						      </TD>
							</TR>
							</TBODY></TABLE>
							
						<form name="form1" method="post" action="classwrk.php?subpg=Submit Class Note">
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
						<TABLE width="89%" style="WIDTH: 98%">
							<TBODY>
							<TR>
							   <TD align="left">
								<p style="margin-left:150px;">Select Subject
								<select name="OptSelSubject" onChange="javascript:setTimeout('__doPostBack(\'OptSelSubject\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
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
					
										if($OptSelSubject =="$subjID"){
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
											
								Search Note:&nbsp;&nbsp;&nbsp;
									<input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
									
									<input name="SubmitSearch" type="submit" id="Search" value="Go">
								</p>
<?php
								$counter_Note = 0;
								$query2 = "select * from tbstudentnote where subjectid = '$OptSelSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Note = $rstart;
								}else{
									$counter_Note = $rstart-1;
								}
?>
								<table width="735" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr>
									<td bgcolor="#F2F2F2" colspan="2" align="left"><?PHP echo $num_rows2; ?> Files uploaded</td>
									<td bgcolor="#F2F2F2" align="right"><?PHP echo GetSubjectName($OptSelSubject); ?></td>
								  </tr>
								  <tr>
									<td width="65" bgcolor="#666666"><div align="center" class="style23 style25"><strong><strong>Tick</strong></strong></div></td>
									<td width="347" bgcolor="#666666"><div align="left" class="style26 style23"><strong>Note Description </strong></div></td>
									<td width="293" bgcolor="#666666"><div align="left" class="style23 style26"><strong>Date Uploaded</strong></div></td>
								  </tr>
<?PHP
									$counter = 0;
									if(isset($_POST['SubmitSearch']))
									{
										$Search_Key = $_POST['Search_Key'];
										$query3 = "select * from tbstudentnote where INSTR(Description,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Description";
									}else{
										$query3 = "select * from tbstudentnote where subjectid = '$OptSelSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}
									$result3 = mysql_query($query3,$conn);
									$num_rows = mysql_num_rows($result3);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result3)) 
										{
											$counter_Note = $counter_Note+1;
											$counter = $counter+1;
											$NoteID = $row["ID"];
											$subjectid = $row["subjectid"];
											$Description = $row["Description"];
											$Filename = $row["Filename"];
											$uploadDate = $row["uploadDate"];
?>
											  <tr>
												<td><div align="center">
												<input name="chkNoteID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $NoteID; ?>">
												 <input type="hidden" name="file<?PHP echo $counter; ?>" value="<?PHP echo $Filename; ?>">
												</div></td>
												<td><div align="left"><a href="classnote/<?PHP echo $Filename; ?>" target="_blank"><img src="Images/pdf.gif" width="15" height="15">&nbsp;&nbsp;<?PHP echo $Description; ?></a></div></td>
												<td><div align="left"><a href="classnote/<?PHP echo $Filename; ?>" target="_blank"><?PHP echo $uploadDate; ?></a></div></td>
											  </tr>
<?PHP
										 }
									 }
?>
								</table>
							  <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Submit Class Note&st=0&ed=<?PHP echo $rend; ?>&subjID=<?PHP echo $OptSelSubject; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Submit Class Note&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&subjID=<?PHP echo $OptSelSubject; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Submit Class Note&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&subjID=<?PHP echo $OptSelSubject; ?>">Next</a> </p></TD>
							</TR>
							<TR>
								 <TD>
								  <div align="center">
									 <input type="hidden" name="Totalnote" value="<?PHP echo $counter; ?>">
									 <input name="Note_delete" type="submit" id="Note_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
							       </div>
							    </TD>
							</TR>
						</TBODY>
						</TABLE>
						</form>
<?PHP
		}elseif ($SubPage == "Find Student") {
?>
						
					<?PHP echo $errormsg; ?>
					<form id="form" action="classwrk.php?subpg=Find Student" method="post" name="form">
					<div id="fmr_lic_div">
					<table width="704" border="0" align="center" cellpadding="5" cellspacing="5" >
						<tr>
							<td width="265"><input type="radio" name="SearchOpt" id="SearchOpt" value="email" />&nbsp;&nbsp;&nbsp;Find student by email</td>
							<td width="404"><div align="left">
							  <input name="Email" type="text" size="35" value="<?PHP echo $Email; ?>">
							  </div></td>
					  	</tr>
						<tr>
							<td><input type="radio" name="SearchOpt" id="SearchOpt" value="names" />&nbsp;&nbsp;&nbsp;Find student by names</td>
							<td><div align="left">
							  <input name="StudentName" type="text" size="35" value="<?PHP echo $StudentName; ?>">
							  </div></td>
					  	</tr>
						<tr>
							<td><input type="radio" name="SearchOpt" id="SearchOpt" value="State" />&nbsp;&nbsp;&nbsp;Find student from state</td>
							<td><div align="left">
							  <SELECT name="optState">
							    <OPTION value="">State</OPTION>
							    <?PHP
								if($optState != ""){
?>
							    <option value="<?PHP echo $optState; ?>" selected="selected"><?PHP echo $optState; ?></option>
							    <?PHP
								}
?>
							    <OPTION value="Abia">Abia</OPTION>
							    <OPTION value="Adamawa">Adamawa</OPTION>
							    <OPTION value="Akwa Ibom">Akwa Ibom</OPTION>
							    <OPTION value="Anambra">Anambra</OPTION>
							    <OPTION value="Bauchi">Bauchi</OPTION>
							    <OPTION value="Bayelsa">Bayelsa</OPTION>
							    <OPTION value="Benue">Benue</OPTION>
							    <OPTION value="Borno">Borno</OPTION>
							    <OPTION value="Cross River">Cross River</OPTION>
							    <OPTION value="Delta">Delta</OPTION>
							    <OPTION value="Ebonyi">Ebonyi</OPTION>
							    <OPTION value="Edo">Edo</OPTION>
							    <OPTION value="Ekiti">Ekiti</OPTION>
							    <OPTION value="Enugu">Enugu</OPTION>
							    <OPTION value="Federal Capital Territory">Federal Capital Territory</OPTION>
							    <OPTION value="Gombe">Gombe</OPTION>
							    <OPTION value="Imo">Imo</OPTION>
							    <OPTION value="Jigawa">Jigawa</OPTION>
							    <OPTION value="Kaduna">Kaduna</OPTION>
							    <OPTION value="Kano">Kano</OPTION>
							    <OPTION value="Katsina">Katsina</OPTION>
							    <OPTION value="Kebbi">Kebbi</OPTION>
							    <OPTION value="Kogi">Kogi</OPTION>
							    <OPTION value="Kwara">Kwara</OPTION>
							    <OPTION value="Lagos">Lagos</OPTION>
							    <OPTION value="Nassarawa">Nassarawa</OPTION>
							    <OPTION value="Niger">Niger</OPTION>
							    <OPTION value="Ogun">Ogun</OPTION>
							    <OPTION value="Ondo">Ondo</OPTION>
							    <OPTION value="Osun">Osun</OPTION>
							    <OPTION value="Oyo">Oyo</OPTION>
							    <OPTION value="Plateau">Plateau</OPTION>
							    <OPTION value="Rivers">Rivers</OPTION>
							    <OPTION value="Sokoto">Sokoto</OPTION>
							    <OPTION value="Taraba">Taraba</OPTION>
							    <OPTION value="Yobe">Yobe</OPTION>
							    <OPTION value="Zamfara">Zamfara</OPTION>
							    </SELECT>
							  </div></td>
					  	</tr>
						<tr>
							<td><input type="radio" name="SearchOpt" id="SearchOpt" value="Admno" />&nbsp;&nbsp;&nbsp;Find student using admission no.</td>
							<td><div align="left">
							  <input name="AdmissionNo" type="text" size="35" value="<?PHP echo $AdmissionNo; ?>">
							  </div></td>
					  	</tr>
						<tr>
							<td><input type="radio" name="SearchOpt" id="SearchOpt" value="DOB" />&nbsp;&nbsp;&nbsp;Find student using date of birth</td>
							<td><div align="left">
							  <select name="find_Dy">
							    <option value="" selected="selected">Day</option>
							    
<?PHP
								for($i=1; $i<=31; $i++){
									if($att_Dy == $i){
										echo "<option value=$i selected=selected>$i</option>";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
							    </select>
							  <select name="find_Mth">
							    <option value="" selected="selected">Month</option>
<?PHP
									for($i=1; $i<=12; $i++){
										if($i == $att_Mth){
											echo "<option value=$i selected='selected'>".Get_Month_Name($i)."</option>";
										}else{
											echo "<option value=$i>".Get_Month_Name($i)."</option>";
										}
									}
?>
							    </select>
                              <input type="submit" name="SubmitFindStudent" value="Find Student">
							</div></td>
					  	</tr>
					</table></div>
					</form>	
					<input name="FindStudent" id="find_stud" value="Open Search Criteria" type="button">
					<input name="CloseFind" id="Close_Find" value="Close  Search Criteria" type="button">
					<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="96" bgcolor="#666666"><div align="center" class="style25 style23"><strong>Adm. No </strong></div></td>
							<td width="147" bgcolor="#666666"><div align="center" class="style25 style23"><strong>Student Name</strong></div></td>
							<td width="120" bgcolor="#666666"><div align="center" class="style25 style23"><strong>Faculty </strong></div></td>
							<td width="109" bgcolor="#666666"><div align="center" class="style25 style23"><strong>Contact No. </strong></div></td>
							<td width="134" bgcolor="#666666"><div align="center" class="style25 style23"><strong>Email </strong></div></td>
							<td width="125" bgcolor="#666666"><div align="center" class="style25 style23"><strong>Date of Birth</strong></div></td>
                          </tr>
<?PHP
								$SearchOpt = $_POST['SearchOpt'];
								if($SearchOpt =="email"){
									$Email = $_POST['Email'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_Email = '$Email' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where Stu_Email = '$Email' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}elseif($SearchOpt =="names"){
									$StudentName = $_POST['StudentName'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where INSTR(Stu_Full_Name,'$StudentName') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where INSTR(Stu_Full_Name,'$StudentName') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}elseif($SearchOpt =="State"){
									$optState = $_POST['optState'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_State = '$optState' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where Stu_State = '$optState' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}elseif($SearchOpt =="Admno"){
									$AdmissionNo = $_POST['AdmissionNo'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}elseif($SearchOpt =="DOB"){
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}else{
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}
								$result4  = mysql_query($query4,$conn) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$num_rows2 = $row4['numrows'];
							
								if($rstart==0){
									$counter_Adm = $rstart;
								}else{
									$counter_Adm = $rstart-1;
								}
							
							
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{							
										if($SearchOpt =="DOB"){
											$dbDOB = "";
											$arrDate=explode ('-', $row["Stu_DOB"]);
											$Dy = $arrDate[2];
											$Mth = $arrDate[1];
											$dbDOB = $Mth."-".$Dy;
											$srDOB = "";
											$find_Dy = $_POST['find_Dy'];
											$find_Mth = $_POST['find_Mth'];
											$srDOB = $find_Mth."-".$find_Dy;
											if($dbDOB == $srDOB){
												$counter_Adm = $counter_Adm+1;
												$counter = $counter+1;
												$AdmID = $row["ID"];
												$AdmissionNo = $row["AdmissionNo"];
												$Stu_Full_Name = $row["Stu_Full_Name"];
												$Stu_Class = $row["Stu_Class"];
												$Stu_Phone = $row["Stu_Phone"];
												$Stu_Email = $row["Stu_Email"];
												$Stu_DOB = $row["Stu_DOB"];
?>
												<tr bgcolor="#F2F2F2">
												<td><div align="center"><?PHP echo $AdmissionNo; ?></div></td>
												<td><div align="center"><?PHP echo $Stu_Full_Name; ?></div></td>
												<td><div align="center"><?PHP echo GetClassName($Stu_Class); ?></div></td>
												<td><div align="center"><?PHP echo $Stu_Phone; ?></div></td>
												<td><div align="center"><?PHP echo $Stu_Email; ?></div></td>
												<td><div align="center"><?PHP echo Long_date($Stu_DOB); ?></div></td>
											   </tr>
<?PHP
											}
										}else{
											$counter_Adm = $counter_Adm+1;
											$counter = $counter+1;
											$AdmID = $row["ID"];
											$AdmissionNo = $row["AdmissionNo"];
											$Stu_Full_Name = $row["Stu_Full_Name"];
											$Stu_Class = $row["Stu_Class"];
											$Stu_Phone = $row["Stu_Phone"];
											$Stu_Email = $row["Stu_Email"];
											$Stu_DOB = $row["Stu_DOB"];
?>
											<tr bgcolor="#F2F2F2">
											<td><div align="center"><?PHP echo $AdmissionNo; ?></div></td>
											<td><div align="center"><?PHP echo $Stu_Full_Name; ?></div></td>
											<td><div align="center"><?PHP echo GetClassName($Stu_Class); ?></div></td>
											<td><div align="center"><?PHP echo $Stu_Phone; ?></div></td>
											<td><div align="center"><?PHP echo $Stu_Email; ?></div></td>
											<td><div align="center"><?PHP echo $Stu_DOB; ?></div></td>
										   </tr>
<?PHP
										}
								 	}
							 	}
								if($SearchOpt =="DOB"){
									$num_rows2 = $counter;
								}
								if(!isset($_POST['SearchOpt'])){
?>
								 <tr>
									<td colspan="7"><p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Find Studento&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Find Student&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Find Student&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></td>
								 </tr>
<?PHP
								}
?>
					</table>
<?PHP
		}elseif ($SubPage == "Email Class") {
?>
						
					<?PHP echo $errormsg; ?>
					<form id="form" action="classwrk.php?subpg=Email Class" method="post" name="form">
					<table width="704" border="0" align="center" cellpadding="5" cellspacing="5" >
						<tr>
							<td width="123" valign="top"><div align="right">To</div></td>
							<td width="540"><div align="left">
							  <select name="OptSubject" onChange="javascript:setTimeout('__doPostBack(\'OptSelSubject\',\'\')', 0)">
                                <option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
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
							  or 
							  <input name="ToEmails" type="text" size="35" value="<?PHP echo $ToEmails; ?>">
							 <span style="margin-left:300px;">-&gt; <em>Seperate emails by comma</em></span></div></td>
					  	</tr>
						<tr>
							<td><div align="right">Subject</div></td>
							<td><div align="left">
							  <input name="EmailSubject" type="text" size="55" value="<?PHP echo $EmailSubject; ?>">
							</div></td>
					  	</tr>
						<tr>
							<td><div align="right">Messagea</div></td>
							<td><div align="left">
							  <textarea name="Message" cols="60" rows="5"><?PHP echo $Message; ?></textarea>
							</div></td>
					  	</tr>
					</table>
					  <div align="center">
						 <input name="SendEmail" type="submit" id="SendEmail" value="Send Email">
						 <input type="reset" name="Reset" value="Reset">
					   </div>
					</form>	
<?PHP
		}elseif ($SubPage == "Student Post") {
?>
						
					<?PHP echo $errormsg; ?>
					<form name="form1" method="post" action="classwrk.php?subpg=Student Post">
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
					<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
						<TR>
							<TD width="67%" valign="top">
								<TABLE width="98%" border="0">
								<TBODY>
								<TR>
									 <TD align="left"><TABLE width="100%" border="0" style="WIDTH: 98%">
								<TBODY>
								<TR>
								  <TD colspan="2" valign="top"  align="left" style="BORDER-RIGHT: #dbdbdb 1px solid; BORDER-TOP: #dbdbdb 1px solid; BORDER-LEFT: #dbdbdb 1px solid; WIDTH: 100%x; BORDER-BOTTOM: #dbdbdb 1px solid; HEIGHT: 100px"><div align="right">
								    <textarea name="postissue" cols="45" rows="3" style=" width:98%; background:#FFFFFF"><?PHP echo $postissue; ?></textarea>
								    <span style="vertical-align:bottom">Select  Class
									<select name="OptSubject" onChange="javascript:setTimeout('__doPostBack(\'OptSubject\',\'\')', 0)">
                                	<option value="" selected="selected">Select</option>
<?PHP
									$counter = 0;
									if($_SESSION['module'] == "Teacher"){
										$query = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
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
					
											if($OptSubject =="$subjID"){
?>
                               				 <option value="<?PHP echo $subjID; ?>" selected="selected"><?PHP echo $Subjname; ?> Class</option>
<?PHP
											}else{
?>
                                			<option value="<?PHP echo $subjID; ?>"><?PHP echo $Subjname; ?> Class</option>
<?PHP
											}
										}
									}
?>
                              		</select>
							        <input name="SubmitShare" type="submit" id="SubmitShare" value="Share Post">
						            </span></div></TD>
								 </TR>
<?PHP
									if($PostAction == "Others"){
										$query = "select * from tbclasspost where ID = '$SelPostID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									}else{
										$query = "select * from tbclasspost where SubjID = '$OptSubject' And SessionID = '$ActiveSession' And Section = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID Desc";
									}
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$PostID  = $dbarray['ID'];
									$SenderID  = $dbarray['SenderID'];
									$SenderType  = $dbarray['SenderType'];
									$PostMsg  = $dbarray['PostMsg'];
									if($PostMsg==""){
										$PostMsg = "<em>No Post Yet...</em>";
									}
									if($dbarray['PostDate'] !=""){
										$PostDate  = "Dated:[".long_date($dbarray['PostDate'])."]";
									}
									
									if($SenderType =="Staff"){
										$query = "select EmpName,Photo from tbemployeemasters where ID='$SenderID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$SenderName  = formatDatabaseStr($dbarray['EmpName']);
										$Senderphoto  = $dbarray['Photo'];
									}else{
										$query = "select Stu_Full_Name,Stu_Photo from tbstudentmaster where AdmissionNo='$SenderID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$SenderName  = formatDatabaseStr($dbarray['Stu_Full_Name']);
										$Senderphoto  = $dbarray['Stu_Photo'];
									}
									if($Senderphoto==""){
										$Senderphoto = "NoImage.jpg";
									}
?>
								<TR>
								  <TD width="8%" align="right" valign="top"><span style=" background:#f2f2f2"><img src="Images/uploads/<?PHP echo $Senderphoto; ?>" width="40" height="39" align="left"></span></TD>
								  <TD width="92%" valign="top"  align="left">
									<div align="right">
									  <p align="left"><strong><?PHP echo strtoupper($SenderName); ?> </strong></p>
									  <p align="left"><?PHP echo $PostMsg; ?>&nbsp;&nbsp;<em><strong><?PHP echo $PostDate; ?></strong></em>&nbsp;&nbsp;&nbsp;
<?PHP
									 if($SenderID==$EmpID){
?>&nbsp;<a href="classwrk.php?subpg=Student Post&post_id=<?PHP echo $PostID; ?>">Delete post </a>
<?PHP
									 }
?>
									  </p>						
									  <p>&nbsp;</p>			  
								    </div>
<?PHP
									if($PostMsg !="<em>No Post Yet...</em>"){
?>
										<TABLE width="99%" style="WIDTH: 98%" bgcolor="#f2f2f2">
										<TBODY>
<?PHP
										$counter = 0;
										$query3 = "select * from tbclasspost_reply where ClassPostID = '$PostID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$num_rows = mysql_num_rows($result3);
										if ($num_rows > 0 ) {
											while ($row = mysql_fetch_array($result3)) 
											{
												$counter = $counter+1;
												$rPostID = $row["ID"];
												$Respondant = $row["Respondant"];
												$rPostID = $row["ClassPostID"];
												$rPostDate = long_date($row["PostDate"]);
												$rPostMsg = $row["PostMsg"];
												$SenderType = $row["SenderType"];
													
												if($SenderType=="Staff"){
													$query = "select EmpName,Photo from tbemployeemasters where ID='$Respondant' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
													$result = mysql_query($query,$conn);
													$dbarray = mysql_fetch_array($result);
													$rSenderName  = formatDatabaseStr($dbarray['EmpName']);
													$rSenderphoto  = $dbarray['Photo'];
												}else{
													$query = "select Stu_Full_Name,Stu_Photo from tbstudentmaster where AdmissionNo='$Respondant' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
													$result = mysql_query($query,$conn);
													$dbarray = mysql_fetch_array($result);
													$rSenderName  = formatDatabaseStr($dbarray['Stu_Full_Name']);
													$rSenderphoto  = $dbarray['Stu_Photo'];
												}
												if($rSenderphoto==""){
													$rSenderphoto = "NoImage.jpg";
												}
?>
												<TR>
													<TD width="10%" valign="top"  align="left"><img src="Images/uploads/<?PHP echo $rSenderphoto; ?>" width="40" height="39" align="right"></TD>
													<TD width="90%" valign="top"  align="left"><?PHP echo $rSenderName; ?><br><?PHP echo $rPostMsg; ?>&nbsp;&nbsp;&nbsp;
													<div style=" height:1px; width:100%; background:url(Images/linestyle.jpg)">&nbsp;</div><br></TD>
												</TR>
<?PHP
											 }
										 }
?>
										<TR>
											<TD width="10%" valign="top" align="right">
										      <div align="center"><img src="Images/uploads/<?PHP echo $EmpFilename; ?>" width="40" height="39" align="right"><a href="#" name="PostReply" class="style27" id="open_post" style="margin-left:20px">Post Comment</a>
										      </div></TD>
											<TD width="90%" valign="top"  align="left">
											<div id="fmr_lic_div">
											  <textarea name="fSubComment" cols="45" rows="3" style=" width:99%"><?PHP echo $fSubComment; ?></textarea>
											  <span style="vertical-align:bottom">
											  <input type="hidden" name="PostID" value="<?PHP echo $PostID; ?>"><input name="SendReply" type="submit" id="SendReply" value="Comment">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											 </span>
										   </div>
										    											 
											</TD>
										</TR>
										</TBODY>
										</TABLE>
<?PHP
									}
?>

									</TD>
								</TR>
							 </TBODY>
							 </TABLE>								 
									 
									 
									 </TD>
								</TR>
								</TBODY>
								</TABLE>
							</TD>
							<TD width="33%" valign="top">
								<TABLE style="BORDER-RIGHT: #dbdbdb 1px solid; BORDER-TOP: #dbdbdb 1px solid; BORDER-LEFT: #dbdbdb 1px solid; WIDTH: 100%x; BORDER-BOTTOM: #dbdbdb 1px solid; HEIGHT: 280px" cellSpacing=0 cellPadding=0 border=0>
									<TBODY>
									<TR bgcolor="#666666" class=body id=staff_actions_slot>
									  <TD width="319" height="24" style="PADDING-RIGHT: 0px; PADDING-LEFT: 6px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; TEXT-ALIGN: left"><span class="style23 style25"><strong><strong>Other Post </strong></strong></span></TD>
									  <TD width="257"
									  style="PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 0px;PADDING-TOP: 0px; TEXT-ALIGN: right"><span class="style24">&nbsp;</span></TD>
									</TR>
									<TR>
									  <TD style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #ffffff" valign="top" colspan="2">
										<TABLE width="100%" height="109" border=0 style="WIDTH: 100%;">
										  <TBODY>
				<?PHP
											$counter_dept = 0;
											$query2 = "select * from tbclasspost where SubjID = '$OptSubject' And SessionID = '$ActiveSession' And Section = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result2 = mysql_query($query2,$conn);
											$num_rows2 = mysql_num_rows($result2);
											
											if($rstart==0){
												$counter_dept = $rstart;
											}else{
												$counter_dept = $rstart-1;
											}
											$counter = 0;
											$query3 = "select * from tbclasspost where SubjID = '$OptSubject' And SessionID = '$ActiveSession' And Section = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
											$result3 = mysql_query($query3,$conn);
											$num_rows = mysql_num_rows($result3);
											if ($num_rows > 0 ) {
												while ($row = mysql_fetch_array($result3)) 
												{
													$counter_dept = $counter_dept+1;
													$counter = $counter+1;
													$o_Post_ID = $row["ID"];
													$o_PostDate = $row["PostDate"];
													$o_PostMsg = $row["PostMsg"];
													$o_SenderID = $row["SenderID"];
													$o_SenderType = $row["SenderType"];
													$arrPost = explode (' ', $o_PostMsg);
													$n=0;
													$postcomment = "";
													while(isset($arrPost[$n])){
														if($n <= 10){
															$postcomment = $postcomment." ".$arrPost[$n];
														}
														$n=$n+1;
													}
													if($o_SenderType =="Staff"){
														$query = "select EmpName,Photo from tbemployeemasters where ID='$o_SenderID'";
														$result = mysql_query($query,$conn);
														$dbarray = mysql_fetch_array($result);
														$o_SenderName  = formatDatabaseStr($dbarray['EmpName']);
														$o_Senderphoto  = $dbarray['Photo'];
													}else{
														$query = "select Stu_Full_Name,Stu_Photo from tbstudentmaster where AdmissionNo='$o_SenderID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														$result = mysql_query($query,$conn);
														$dbarray = mysql_fetch_array($result);
														$SenderName  = formatDatabaseStr($dbarray['Stu_Full_Name']);
														$o_Senderphoto  = $dbarray['Stu_Photo'];
													}
													if($o_Senderphoto==""){
														$o_Senderphoto = "NoImage.jpg";
													}
													
													$numrows = 0;
													$query4   = "SELECT COUNT(ID) AS numrows FROM tbclasspost_reply where ClassPostID = '$o_Post_ID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
													$result4  = mysql_query($query4,$conn) or die('Error, query failed');
													$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
													$PostTotal = $row4['numrows'];
													$PostTotal = "Total of ".$PostTotal." Comments"
				?>
													  <TR vAlign=top>
														<TD width="42" height="34"><IMG height=32 src="Images/uploads/<?PHP echo $o_Senderphoto; ?>" width=33 border=0></TD>
														<TD width="244"><a href="classwrk.php?subpg=Student Post&Oth_post_id=<?PHP echo $o_Post_ID; ?>"><?PHP echo $postcomment; ?>....&nbsp;&nbsp;&nbsp;&nbsp;<font size="-3"><em><?PHP echo $PostTotal; ?></em></font></a></TD>
													  </TR>
				<?PHP
												 }
											 }
				?>
											<TR>
												<TD colspan="2"> <p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Student Post&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Student Post&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Student Post&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
											</TR>
										  </TBODY></TABLE>									  </TD>
								      </TR>
								    </TBODY>
							    </TABLE>
							</TD>
						</TR>
					</TBODY></TABLE>
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
