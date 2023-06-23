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
	
	if(isset($_POST['SubmitPwd']))
	{
		$oldPwd = $_POST['oldPwd'];
		$newPwd = $_POST['newPwd'];
		$conPwd = $_POST['conPwd'];
		if(!$_POST['oldPwd']){
			$errormsg = "<br><font color = red size = 1>Old Password is empty.</font>";
			$PageHasError = 1;
	   }
	   
	   if(!$_POST['newPwd']){
			$errormsg = "<br><font color = red size = 1>New Password is empty.</font>";
			$PageHasError = 1;
	   }
	   else {
		   $_POST['newPwd'] = trim($_POST['newPwd']);
		   if(strlen($_POST['newPwd']) > 10){
				$errormsg = "<br><font color = red size = 1>Sorry, the New Password is longer than 10 characters, please shorten it.</font>";
				$PageHasError = 1;
		   }
	   }
	   
	  if(!$_POST['conPwd']){
			$errormsg = "<br><font color = red size = 1>Confirm Password is empty.</font>";
			$PageHasError = 1;
	   }
	   else {
		   $_POST['conPwd'] = trim($_POST['conPwd']);
		   if(strlen($_POST['conPwd']) > 10){
				$errormsg = "<br><font color = red size = 1>Sorry, the Confirm Password is longer than 10 characters, please shorten it.</font>";
				$PageHasError = 1;
		   }
	   }
	   
	   if($newPwd != $conPwd){
			$errormsg = "<br><font color = red size = 1>Invalid New Password entry. </font>";
			$PageHasError = 1;

	   }
	   if ($PageHasError == 0)
	   {
			$num_rows = 0;
			$query = "select password from tbstudentmaster where AdmissionNo='$AdmissionNo'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) 
			{
				while ($row = mysql_fetch_array($result)) 
				{
					$OldPasswrd = $row["password"];
				}
				if($OldPasswrd == $oldPwd){
					$q = "update tbstudentmaster set password = '$newPwd' where AdmissionNo = '$AdmissionNo'";
					$result = mysql_query($q,$conn);
					$errormsg = "<br><font color = blue size = 1>Password was successfully change.</font>";
					$oldPwd = "";
					$newPwd = "";
					$conPwd = "";
				}else{
					$errormsg = "<br><font color = red size = 1>Invalid Old Password Entry. </font>";
					$PageHasError = 1;
				}
			}
			else {
				$errormsg = "<br><font color = red size = 1>Invalid New Password Entry. </font>";
				$PageHasError = 1;
	   		}
	   }
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
<script src="jquery/jquery-1.2.6.min.js" type="text/javascript"></script>
<script src="jquery/jquery.validate.js" type="text/javascript"></script>
<script src="jquery/jquery-ui.min.js" type="text/javascript"></script>

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
			<A href="welcome.php?pg=Student Blog"><IMG src="Images/img_logo_lpc.png" alt="SkoolNet Manager" title="SkoolNet Manager"></A>
		</DIV>
		<DIV id=search>
			<FORM style="FLOAT: right" action="" method=get>
			</FORM>
		</DIV>
		<DIV id=top-links>			
			<UL> 
			  <LI><A href="http://www.lagospastoralcollege.org" target="_blank">SkoolNET HOME</A> 
			  <LI><A href="../index.php" target="_blank">SOLS HOME</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Contact Us&subpg=none" target="_blank"><STRONG>Contact us </STRONG></A> 
			
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
					 
					 
					 
<?PHP
		if($Page == "My Details") {
?>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Full Student Details</strong>
				</div>
				<table  width="90%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 90%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
<?PHP
					$query = "select * from tbstudentmaster where AdmissionNo='$AdmissionNo'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$RegNo= $dbarray['Stu_Regist_No'];
					$RegDate= $dbarray['Stu_Date_of_Admis'];
					$StuClass  = $dbarray['Stu_Class'];
					$OptSection  = $dbarray['Stu_Sec'];
					$StudentName  = $dbarray['Stu_Full_Name'];
					$DOB  = $dbarray['Stu_DOB'];
					$OptGender  = $dbarray['Stu_Gender'];
					$HomeAddress  = $dbarray['Stu_Address'];
					$OptCity  = $dbarray['Stu_City'];
					$optOrigin  = $dbarray['Stu_State'];
					
					$Tel_No  = $dbarray['Stu_Phone'];
					$StuEmail  = $dbarray['Stu_Email'];
					$RegFee  = $dbarray['Stu_Reg_Fee'];
					
					$OptMarital  = $dbarray['MaritalStatus'];
					$StuFilename  = $dbarray['Stu_Photo'];
					if($StuFilename ==""){
						$StuFilename  = "empty_r2_c2.jpg";
					}
					$ReasonDOB  = $dbarray['ReasonDOB'];
					$LastSchool  = $dbarray['SchoolLeft'];
					$OldStudent  = $dbarray['OldStudent'];
					$AdmissionNo  = $dbarray['AdmissionNo'];
					$Country  = $dbarray['Country'];
					$Nationality  = $dbarray['Nationality'];		
					$chkIden  = $dbarray['IdenType'];
					$IdenNo  = $dbarray['IdenNo'];
					$IssueDate  = $dbarray['IssueDate'];
					$OptIssuePlace  = $dbarray['IssuePlace'];
					$OthersSpecified  = $dbarray['Others'];
					$IssueAuth  = $dbarray['IssueAuth'];
					$emergency  = $dbarray['Emergency'];
					
					$query2 = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
					$result2 = mysql_query($query2,$conn);
					$dbarray2 = mysql_fetch_array($result2);
					$OptProgramme  = $dbarray2['Programme'];
					$OptLevel  = $dbarray2['Level'];
					$OptPayment  = $dbarray2['PayMode'];
					$OptpayTerms  = $dbarray2['PayTerms'];
					$occ  = $dbarray2['Occupation'];
					$EduLevel  = $dbarray2['EduLevel'];
					$isEmployed  = $dbarray2['isEmploy'];
					$Employer  = $dbarray2['Employer'];
					$BibleSch  = $dbarray2['BibleSchool'];
					$WhenGivelife  = User_date($dbarray2['WhenGive_Life']);
					$WhereGivelife  = $dbarray2['WhereGive_Life'];
					
					$Whenbaptized  = User_date($dbarray2['WhenBaptized']);
					$Wherebaptized  = $dbarray2['WhereBaptized'];
					$How_long_mem  = $dbarray2['HowLong_mem'];
					$Hobbies  = $dbarray2['Hobbies'];
					$Languages  = $dbarray2['Languages'];
					$AreasInterest  = $dbarray2['Interest'];
					$Talents  = $dbarray2['Talents'];
					$RefereeName  = $dbarray2['RefName'];
					$RefereeProf  = $dbarray2['RefProf'];
					$Refereeacq  = $dbarray2['RefeAcq'];
					$RefereeTel  = $dbarray2['RefTelNo'];
					$RefereeAdd  = $dbarray2['RefAddress'];
?>
					<tr>
						<td>
						<TABLE width="99%" style="WIDTH: 98%" cellpadding="5" cellspacing="3">
						<TBODY>
						<TR>
						  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Admission No </strong></div></TD>
						  <TD width="22%" valign="top"  align="left"><?PHP echo $AdmissionNo; ?></TD>
						  <TD width="23%" valign="top"  align="left"><strong>Student Name : </strong></TD>
						  <TD width="31%" valign="top"  align="left"><?PHP echo $StudentName; ?></TD>
						</TR>
						<TR>
						  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Program</strong></div></TD>
						  <TD width="22%" valign="top"  align="left"><?PHP echo $OptProgramme; ?></TD>
						  <TD width="23%" valign="top"  align="left"><strong>Date of Birth : </strong></TD>
						  <TD width="31%" valign="top"  align="left"><?PHP echo $DOB; ?></TD>
						</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Faculty</strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo GetClassName($StuClass); ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Admission Date  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $RegDate; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Section</strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $OptSection ; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Home Address  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $HomeAddress; ?></TD>
					</TR>
					
					
					<TR>
					  <TD width="24%" height="29"  align="left" valign="top"><div align="right"><strong>Gender : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $OptGender; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>City. </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $OptCity; ?></TD>
					</TR>
					
					
					<TR>
					  <TD width="24%" height="29"  align="left" valign="top"><div align="right"><strong>State of Origin : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $optOrigin; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Contact No. </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $Tel_No; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Email : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $StuEmail; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Marital Status  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $OptMarital; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Reason if date of birth no known : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $ReasonDOB; ?>						</TD>
					  <TD width="23%" valign="top"  align="left"><strong>Last school attended  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $LastSchool; ?>
					    </TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Country : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $Country; ?>						</TD>
					  <TD width="23%" valign="top"  align="left"><strong>Nationality  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $Nationality; ?>
					    </TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Identification Type </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $chkIden; ?></TD>
					  <TD width="23%" valign="top"  align="left"></TD>
					  <TD width="31%" valign="top"  align="left" rowspan="7" >
						<table width="221" border="0" align="center" cellpadding="0" cellspacing="0">
						  <tr>
						   <td><img src="images/spacer.gif" width="21" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="178" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="22" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td colspan="3"><img name="empty_r1_c1" src="images/empty_r1_c1.jpg" width="221" height="20" border="0" id="empty_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="empty_r2_c1" src="images/empty_r2_c1.jpg" width="21" height="197" border="0" id="empty_r2_c1" alt="" /></td>
						   <td><img src="../ad/Images/uploads/<?PHP echo $StuFilename; ?>" width="178" height="175"></td>
						   <td rowspan="2"><img name="empty_r2_c3" src="images/empty_r2_c3.jpg" width="22" height="197" border="0" id="empty_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="175" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="empty_r3_c2" src="images/empty_r3_c2.jpg" width="178" height="22" border="0" id="empty_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="22" border="0" alt="" /></td>
						  </tr>
						</table>					    </TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Identification No: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $IdenNo; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Issue date: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $IssueDate; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Place of Issue: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $OptIssuePlace; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Others: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $OthersSpecified; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Issuing Authority: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $IssueAuth; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Emergency : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $emergency; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					</TBODY>
					</TABLE>
					</td>
					</tr>
			      </tbody>
			  </table>
<?PHP
		}elseif($Page=="Change Password"){
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="myaccount.php?pg=Change Password">
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
					<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
						 <TD colspan="2" align="center"><p>&nbsp;</p>
						 <table width="472" border="0" align="center" cellpadding="10" cellspacing="10">
                           <tr>
                             <td width="128"><strong>Admission No  :</strong></td>
                             <td width="323"><?PHP echo $AdmissionNo; ?></td>
                           </tr>
                           <tr>
                             <td><strong>Old Password  :</strong></td>
                             <td><input name="oldPwd" type="password" size="20"  value="<?PHP echo $oldPwd; ?>"></td>
                           </tr>
                           <tr>
                             <td><strong>New Password   :</strong></td>
                             <td><input name="newPwd" type="password" size="20"  value="<?PHP echo $newPwd; ?>"></td>
                           </tr>
                           <tr>
                             <TD width="128" valign="top"  align="left"><strong>Confirm Password </strong></TD>
                             <TD width="323" valign="top"  align="left"><input name="conPwd" type="password" size="20"  value="<?PHP echo $conPwd; ?>"></TD>
                           </tr>
                         </table></TD>
					</TR>
					<TR>
						 <TD colspan="2">
						   <div align="center">
						     <input name="SubmitPwd" type="submit" id="SubmitPwd" value="Submit">
						     <input type="reset" name="Reset" value="Reset">
						   </div>						 </TD>
					</TR>
					<TR>
						 <TD colspan="2" align="left">
						 	<p>&nbsp;</p>
					  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}
?>					 
					 
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