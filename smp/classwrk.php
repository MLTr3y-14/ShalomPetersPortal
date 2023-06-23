<?PHP
	session_start();
	$end=time();
	global $userNames;
	global $TimerOut;
	$TimerOut="False";
	$file = "welcome.php?pg=";
/*    session variables like user login info ..    */ 
	
	if (isset($_SESSION['AdmNo']))
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
	$dat = date('Y'.'-'.'m'.'-'.'d');
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include '../library/config.php';
	include '../library/opendb.php';
	include '../formatstring.php';
	include '../function.php';
    include("start_timer.php");
    include("end_timer.php");
	
	$AdmissionNo = $_SESSION['AdmNo'];
	$query = "select Stu_Class,Stu_Full_Name,Stu_Photo,Stu_Sec from tbstudentmaster where AdmissionNo='$AdmissionNo'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$StuClass  = $dbarray['Stu_Class'];
	$FullName  = $dbarray['Stu_Full_Name'];
	$StudFileName  = $dbarray['Stu_Photo'];
	$Stu_Section  = $dbarray['Stu_Sec'];
	$Login = "Log in Student: ".$FullName; 
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['PostReply'])){
		?> <script>document.location = 'welcome.php?open_post=true';</script> <?php
	}
		
	//GET ACTIVE TERM AND SESSION
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$ActiveValue = 1;
	$ActiveSession  = Get_Active_Session(1);
	
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
				$query1 = "select Stu_Email,Stu_Full_Name from tbstudentmaster where Stu_Class IN(Select ClassId from tbclasssubjectrelation where SubjectId = '$OptSubject') order by Stu_Full_Name";
				$result1 = mysql_query($query1,$conn);
				$num_rows1 = mysql_num_rows($result1);
				if ($num_rows1 > 0 ) {
					while ($row1 = mysql_fetch_array($result1)) 
					{
						$StuEmail = $row1["Stu_Email"];
						$FullName = $row1["Stu_Full_Name"];		
						
						$mail->AddAddress($StuEmail, $FullName);
						$mail->From = "info@lagospastoralcollege.org";
						$mail->FromName = "SkoolNET Administrator";
						$mail->AddReplyTo("info@lagospastoralcollege.org", "SkoolNET Administrator");
						
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
						   echo "<meta http-equiv=\"Refresh\" content=\"0;url=classwrk.php?pg=Email Class&msg=error\">";
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
					$query1 = "select Stu_Email,Stu_Full_Name from tbstudentmaster where Stu_Email = '$arrEmail[$i]'";
					$result1 = mysql_query($query1,$conn);
					$num_rows1 = mysql_num_rows($result1);
					if ($num_rows1 > 0 ) {
						while ($row1 = mysql_fetch_array($result1)) 
						{
							$StuEmail = $row1["Stu_Email"];
							$FullName = $row1["Stu_Full_Name"];	
						
							$mail->AddAddress($StuEmail, $FullName);
							$mail->From = "info@lagospastoralcollege.org";
							$mail->FromName = "SkoolNET Administrator";
							$mail->AddReplyTo("info@lagospastoralcollege.org", "SkoolNET Administrator");
							
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
							   echo "<meta http-equiv=\"Refresh\" content=\"0;url=classwrk.php?pg=Email Class&msg=error\">";
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
	
?>
<HTML xmlns="http://ww.w3org.org/1999/xhtml" xml:lang=" en-gb" lang="en-gb" dir="ltr"><HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8"><TITLE>Student Online Learning System @ SkoolNET</TITLE>
<BASE href=".">
<BASE href=".">
<BASE href=".">
<TITLE>Student Online Learning System @ SkoolNET</TITLE>
<LINK href="#" rel="shortcut icon" type="image/x-icon">
<LINK rel="stylesheet" type="text/css" href="./css2/style.css">
<LINK rel="stylesheet" type="text/css" href="./css2/menu.css">
<LINK rel="stylesheet" href="./css2/inner_style.css" type="text/css">
<LINK rel="stylesheet" href="./css2/joomla_classes.css" type="text/css">

<SCRIPT type="text/javascript" src="./css2/textsizer.js"></SCRIPT>
<SCRIPT type="text/javascript" src="./css2/initpage.js"></SCRIPT>
<script language="JavaScript">
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->
</script>
<script src="../jquery/jquery-1.2.6.min.js" type="text/javascript"></script>
<script src="../jquery/jquery.validate.js" type="text/javascript"></script>
<script src="../jquery/jquery-ui.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $("#form").validate();
        $('#ddate').datepicker({
            altField: '#ddate',
            altFormat: 'dd/mm/yy'
        });
        $("select#state").change(function(){
            $.getJSON("state_select.php", {
                id: $(this).val(),
                ajax: 'true'
            }, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("select#lga").html(options);
            })
        });
		
		 $('#fmr_lic_div').hide();

		$('#note_upload').click(function(){
			$('#fmr_lic_div').show('slow');
		});

		$('#find_stud').click(function(){
			$('#fmr_lic_div').show('slow');
		});
		
		$('#Close_Find').click(function(){
			$('#fmr_lic_div').hide('slow');
		});
		
		$('#open_post').click(function(){
			$('#fmr_lic_div').show('slow');
		});
		
		$('#learn_new').click(function(){
			$('#fmr_lic_div').hide('slow');
		})
    });
    
</script>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</HEAD>
<BODY onLoad="pageMaskLoad()">
<?PHP include 'pagemask.php'; ?>
<DIV id=header>
	<DIV id=top-menus>
		<DIV id=uowlogo>
			<A href="welcome.php?pg=Student Blog"><IMG src="Images/img_logo_lpc.png" alt="SkoolNet Manager" title="SkoolNet Manager"></A>		</DIV>
		<DIV id=search>
			<FORM style="FLOAT: right" action="" method=get>
			</FORM>
		</DIV>
		<DIV id=top-links>			
			<UL>
			  <LI><A href="welcome.php?pg=Student Blog" target="_blank">HOME</A> 
			  <LI><A href="http://www.nexzoninvestment.com/page.php?pg=Contact&subpg=none" target="_blank"><STRONG>Contact us </STRONG></A> 
			
			  <LI><A 
			  href="Logout.php"><STRONG>Logout</STRONG></A>			  </LI>
			</UL>
		</DIV>
		<DIV id=section-title><IMG title="Student Online Learning System" src="Images/uow066001.png"></DIV>
	</DIV>
</DIV>
<DIV id="containerDiv">
  <DIV id="navcontainer">
	<?PHP include 'topmenu.php'; ?>
  </DIV>
   <DIV id="body">
    <DIV id="cpanelStrip">
      <DIV id="cpanelStripLeft"></DIV>
      <DIV id="cpanelStripMiddle">
        <DIV id="breadcrumbsDiv">		
			<DIV class="moduletable">
				<SPAN class="breadcrumbs pathway">
					<UL>
						<LI class="firstCrumb">Contact Us </LI>
						<LI>&nbsp;</LI>
						<LI><A href="" class="pathway" title="Products">Fee Receipt  </A></LI>
						<LI class="sep"> <IMG src="./Images/arrow_rtl.png" alt=""></LI> 
						<LI></LI>
						<LI class="lastCrumb">Student Blog </LI>
					</UL>
				</SPAN>
			</DIV>
		</DIV>
        <DIV id="innerCPanel">
			<UL class="imageList">
			   <LI class="first"><A href=""><IMG src="./Images/inner-textsizer.gif" width="18" height="11" border="0" usemap="#Map"></A></LI>
		  </UL>
			 <UL class="textList">
			   <LI class="first">Text Sizer</LI>
			   <LI class="second"><A href="" title="Student Online Learning System" target="_blank"></A></LI>
			   <LI class="third"></LI>
			</UL>
        </DIV>
      </DIV>
      <DIV id="cpanelStripRight"></DIV>
    </DIV>
    <DIV id="leftColumn">
      <DIV id="relatedPages">
        <DIV id="relatedPagesTop"></DIV>
        <DIV id="relatedPagesMiddle">
          <H3>RELATED PAGES</H3>
          <DIV class="moduletable">
			<DIV class="moduletable">
				<?PHP include 'sidemenu.php'; ?>		
			</DIV>
          </DIV>
        </DIV>
        <DIV id="relatedPagesBottom"></DIV>
      </DIV>
      <DIV id="lowerBanner">
        <DIV id="lowerBannerTop"></DIV>
        <DIV id="lowerBannerMiddle"> 		
			<DIV class="moduletable">
				<DIV class="bannergroup">
					<DIV class="banneritem" style="background:#333333; height:260px">
						&nbsp;
						<DIV class="clr"></DIV>
					</DIV>
				</DIV>
			</DIV>
	 	</DIV>
        <DIV id="lowerBannerBottom"></DIV>
      </DIV>
    </DIV>
    <DIV id="rightColumn">
		<DIV id="bannerDiv">
			<DIV class="moduletable">
				<DIV class="bannergroup">
					<DIV class="banneritem">
						<TABLE width="100%" border="0" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD align="left">
					  <!-- PAGE CONTENT BEGIN HERE-->
						<BR>
<?PHP
		if ($Page == "Submit Class Note") {
?>
						<?PHP echo $errormsg; ?>
						<form name="form1" method="post" action="classwrk.php?pg=Submit Class Note">
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
								<p style="margin-left:50px;">Select Subject 
								<select name="OptSelSubject" onChange="javascript:setTimeout('__doPostBack(\'OptSelSubject\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								$query = "select SubjectId from tbclasssubjectrelation where ClassId = '$StuClass'";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$subjID = $row["SubjectId"];
										$SubjectName = GetSubjectName($row["SubjectId"]);
					
										if($OptSelSubject =="$subjID"){
?>

											<option value="<?PHP echo $subjID; ?>" selected="selected"><?PHP echo $SubjectName; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $subjID; ?>"><?PHP echo $SubjectName; ?></option>
<?PHP
										}
									}
								}
?>
								</select>
											
								Search Note:&nbsp;&nbsp;&nbsp;
									<input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
									
									<input name="SubmitSearch" type="submit" value="Go">
								</p>
<?php
								$counter_Note = 0;
								$query2 = "select * from tbstudentnote where subjectid = '$OptSelSubject'";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Note = $rstart;
								}else{
									$counter_Note = $rstart-1;
								}
?>
								<table width="735" border="0" align="center" cellpadding="5" cellspacing="5">
								  <tr>
									<td bgcolor="#F2F2F2" colspan="2" align="left"><?PHP echo $num_rows2; ?> Files Found </td>
									<td bgcolor="#F2F2F2" align="right"><?PHP echo GetSubjectName($OptSelSubject); ?></td>
								  </tr>
								  <tr>
									<td width="65" bgcolor="#666666"><div align="center" class="style21 style25 style1"><strong>#</strong></div></td>
									<td width="347" bgcolor="#666666"><div align="left" class="style26 style1"><strong>Note Description </strong></div></td>
									<td width="293" bgcolor="#666666"><div align="left" class="style26 style1">Date Uploaded</div></td>
								  </tr>
<?PHP
									$counter = 0;
									if(isset($_POST['SubmitSearch']))
									{
										$Search_Key = $_POST['Search_Key'];
										$query3 = "select * from tbstudentnote where INSTR(Description,'$Search_Key') order by Description";
									}else{
										$query3 = "select * from tbstudentnote where subjectid = '$OptSelSubject' LIMIT $rstart,$rend";
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
												 <?PHP echo $counter; ?>
												</div></td>
												<td><div align="left"><a href="../ad/classnote/<?PHP echo $Filename; ?>" target="_blank"><img src="Images/pdf.gif" width="15" height="15">&nbsp;&nbsp;<?PHP echo $Description; ?></a></div></td>
												<td><div align="left"><a href="../ad/classnote/<?PHP echo $Filename; ?>" target="_blank"><?PHP echo Long_date($uploadDate); ?></a></div></td>
											  </tr>
<?PHP
										 }
									 }
?>
								</table>
							  <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="classwrk.php?pg=Submit Class Note&st=0&ed=<?PHP echo $rend; ?>&subjID=<?PHP echo $OptSelSubject; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?pg=Submit Class Note&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&subjID=<?PHP echo $OptSelSubject; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?pg=Submit Class Note&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&subjID=<?PHP echo $OptSelSubject; ?>">Next</a> </p></TD>
							</TR>
						</TBODY>
						</TABLE>
						</form>
<?PHP
		}elseif ($Page == "Find Student") {
?>
						
					<?PHP echo $errormsg; ?>
					<form id="form" action="classwrk.php?pg=Find Student" method="post" name="form">
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
					<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="96" bgcolor="#666666"><div align="center" class="style25 style1"><strong>Adm. No </strong></div></td>
							<td width="147" bgcolor="#666666"><div align="center" class="style25 style1"><strong>Student Name</strong></div></td>
							<td width="120" bgcolor="#666666"><div align="center" class="style25 style1"><strong>Faculty </strong></div></td>
							<td width="109" bgcolor="#666666"><div align="center" class="style25 style1"><strong>Contact No. </strong></div></td>
							<td width="134" bgcolor="#666666"><div align="center" class="style25 style1"><strong>Email </strong></div></td>
							<td width="125" bgcolor="#666666"><div align="center" class="style25 style1"><strong>Date of Birth</strong></div></td>
                          </tr>
<?PHP
								$SearchOpt = $_POST['SearchOpt'];
								if($SearchOpt =="email"){
									$Email = $_POST['Email'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_Email = '$Email'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where Stu_Email = '$Email'";
								}elseif($SearchOpt =="names"){
									$StudentName = $_POST['StudentName'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where INSTR(Stu_Full_Name,'$StudentName')";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where INSTR(Stu_Full_Name,'$StudentName')";
								}elseif($SearchOpt =="State"){
									$optState = $_POST['optState'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_State = '$optState'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where Stu_State = '$optState'";
								}elseif($SearchOpt =="Admno"){
									$AdmissionNo = $_POST['AdmissionNo'];
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where AdmissionNo = '$AdmissionNo'";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster where AdmissionNo = '$AdmissionNo'";
								}elseif($SearchOpt =="DOB"){
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster";
								}else{
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster";
									$query3 = "select ID,AdmissionNo,Stu_Full_Name,Stu_Class,Stu_Phone,Stu_Email,Stu_DOB from tbstudentmaster LIMIT $rstart,$rend";
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
									<td colspan="7"><p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="classwrk.php?pg=Find Studento&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?pg=Find Student&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?pg=Find Student&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></td>
								 </tr>
<?PHP
								}
?>
					</table>
<?PHP
		}elseif ($Page == "Email Class") {
?>
						
					<?PHP echo $errormsg; ?>
					<form id="form" action="classwrk.php?pg=Email Class" method="post" name="form">
					<table width="704" border="0" align="center" cellpadding="5" cellspacing="5" >
						<tr>
							<td width="123" valign="top"><div align="right">To</div></td>
							<td width="540"><div align="left">
							  <select name="OptSubject" onChange="javascript:setTimeout('__doPostBack(\'OptSelSubject\',\'\')', 0)">
                                <option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								$query = "select SubjectId from tbclasssubjectrelation where ClassId = '$StuClass'";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$subjID = $row["SubjectId"];
										$Subjname = GetSubjectName($row["SubjectId"]);
					
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
		}
?>
                      </TD>
					</TR>
					</TBODY>
				  </TABLE>
					
					  	<DIV class="clr"></DIV>
				  </DIV>
				</DIV>
			</DIV>
            </DIV>
	        <DIV id="contentDiv">				
				<SPAN class="article_separator">&nbsp;</SPAN>
		  </DIV>
		</DIV>
	</DIV>
	<DIV id="categoryMenu"></DIV>  
	<DIV id="footer">
		Powered by Nexzon Investment Limited   </DIV>
</DIV>
<MAP name="Map" id="Map">
  <AREA shape="rect" coords="-5,0,11,17" href="javascript:ts('body', 1);" alt="Increase Font">
  <AREA shape="rect" coords="-5,0,18,26" href="javascript:ts('body', -1);" alt="Decrease Font">
</MAP>

<SCRIPT type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</SCRIPT><SCRIPT src="./css2/ga.js" type="text/javascript"></SCRIPT>
<SCRIPT type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10296102-1");
pageTracker._trackPageview();
} catch(err) {}</SCRIPT>


</BODY></HTML>