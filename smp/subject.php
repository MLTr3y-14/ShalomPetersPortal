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
	
	if(isset($_POST['OptSubject']))
	{	
		$OptSubject = $_POST['OptSubject'];
	}	
	if(isset($_GET['subjID']))
	{
		$OptSubject = $_GET['subjID'];
		$query = "select ID,Subj_name from tbsubjectmaster where ID='$OptSubject'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Subjectname  = $dbarray['Subj_name'];
		
		$query = "select ID,EmpId from tbclassteachersubj where ClassId='$StuClass' And SecID='$Activeterm' And SubjectId = '$OptSubject'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpId  = $dbarray['EmpId'];
		$EmpName = GET_EMP_NAME($EmpId);
	}
	$PostAction = "lastest";
	if(isset($_GET['Oth_post_id']))
	{
		$SelPostID = $_GET['Oth_post_id'];
		$PostAction = "Others";
	}									
	if($OptSubject==""){
		$OptSubject = $DefaultSubj;
	}
	if(isset($_POST['OptSelExams']))
	{
		$OptSelExams = $_POST['OptSelExams'];
	}
	if(isset($_POST['ViewResult']))
	{
		$OptSubject = $_GET['subjID'];
		$OptSelExams = $_POST['OptSelExams'];
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=result.php?pg=subject result&id=".$OptSubject."&exam=".$OptSelExams."\">";
		exit;
	}
	if(isset($_POST['NotifyTeacher']))
	{
		$OptSubject = $_GET['subjID'];
		$OptSelExams = $_POST['OptSelExams'];
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=notify.php?pg=subject&id=".$OptSubject."&exam=".$OptSelExams."\">";
		exit;
	}
	if(isset($_POST['TeacherDetails']))
	{
		$OptSubject = $_GET['subjID'];
		$OptSelExams = $_POST['OptSelExams'];
		$query = "select ID,EmpId from tbclassteachersubj where ClassId='$StuClass' And SecID='$Activeterm' And SubjectId = '$OptSubject'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpId  = $dbarray['EmpId'];
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=staffPDF.php?pg=Staff Details&eid=".$EmpId."\">";
		exit;
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
					  <?PHP echo $errormsg; ?>
					  <form name="form1" method="post" action="subject.php?subjID=<?PHP echo $OptSubject; ?>">
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
					  <TABLE class="contentpaneopen">
						<TBODY>
						<TR>
							<TD width="68%" valign="top" class="systemtdtext">
								<DIV class="header_box">
								  <H3><?PHP echo $Subjectname; ?></H3>
								  Subject Teacher:  <?PHP echo $EmpName; ?>
								</DIV>
									<TABLE width="100%" border="0" style="WIDTH: 100%">
									<TBODY>
									<TR>
									  <TD colspan="2" valign="top"  align="left" style="BORDER-RIGHT: #dbdbdb 1px solid; BORDER-TOP: #dbdbdb 1px solid; BORDER-LEFT: #dbdbdb 1px solid; WIDTH: 100%x; BORDER-BOTTOM: #dbdbdb 1px solid; HEIGHT: 100px">Select Section
										<select name="OptSelExams" onChange="javascript:setTimeout('__doPostBack(\'OptSelExams\',\'\')', 0)">
<?PHP
										$query = "select ID,ExamName from tbexaminationmaster order by ExamName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows > 0 ) {
											while ($row = mysql_fetch_array($result)) 
											{
												$ExamID = $row["ID"];
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
									<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
									  <tr>
										<td width="26" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
										<td width="450" bgcolor="#F4F4F4"><div align="center" class="style21">Result Details</div></td>
									  </tr>
									  <tr>
										<td width="26" bgcolor="#FFFFFF">&nbsp;</td>
										<td width="450" bgcolor="#FFFFFF">
											<table width="456" border="0" align="center" cellpadding="3" cellspacing="3">
											  <tr bgcolor="#ECE9D8">
												<td width="146" align="center"><strong>SUBJECT  NAME</strong></td>
												<td width="117" align="center"><strong>ASSESSMENT</strong></td>
												<td width="163" align="center"><strong>ALL. MARK </strong></td>
											  </tr>
									    </table></td>
									  </tr>
						  
<?PHP
										if(isset($_POST['OptSelExams']))
										{
											$OptSelExams = $_POST['OptSelExams'];
										}
										$ClassId = $StuClass;
										$SubjID = $OptSubject;
										$OptSelExams = $OptSelExams;
										$counter1 = 0;
										$query2 = "select Distinct SubjectId from tbclasssubjectrelation where SubjectId IN (Select SubjectId from tbclassexamsetup where ExamId = '$OptSelExams' And ClassId ='$ClassId' And SubjectId ='$SubjID') order by ID";
										
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
										<td>
										<table width="448" border="0" align="left" cellpadding="3" cellspacing="3">
<?PHP
											$TotalPer=0; 
											$countDisplay = 0;
											$query4 = "select ID,ExamId,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And SubjectId ='$SubjID' And ExamId = '$OptSelExams'";
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
													<td width="144" align="center">
													<?PHP
														$countDisplay = $countDisplay+1;
														if($countDisplay==1){
															echo GetSubjectName($OptSubject); 
														}
													?></td>
													<td width="146" align="center"><?PHP echo $ResultType; ?></td>
													<td width="128" align="center"><?PHP echo $Percentage; ?></td>
												  </tr>
<?PHP
												 }
											}
?>
										  <tr>
											<td width="144">&nbsp;</td>
											<td width="146" align="right"><hr><strong>Total</strong><hr></td>
											<td width="128"><hr><?PHP echo $TotalPer; ?>%<hr></td>
										  </tr>
										</table>										</td>
									  </tr>
									 </table>
									   <div align="center">
										 <input name="ViewResult" type="submit" id="ViewResult" value="View Result">
										 <input name="NotifyTeacher" type="submit" id="NotifyTeacher" value="Notify Teacher">
										 <input name="TeacherDetails" type="submit" id="TeacherDetails" value="View Teacher Details">
									   </div>
									  </TD>
									</TR>
								 </TBODY>
								 </TABLE>
					      		</TD>
<?PHP
									if($PostAction == "Others"){
										$query = "select * from tbclasspost where ID = '$SelPostID'";
									}elseif($OptSubject != ""){
										$query = "select * from tbclasspost where SubjID = '$OptSubject' And SessionID = '$ActiveSession' And Section = '$Activeterm' order by ID Desc";
									}else{
										$query = "select * from tbclasspost where SubjID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$StuClass') And SessionID = '$ActiveSession' And Section = '$Activeterm' order by ID Desc";
									}
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$PostID  = $dbarray['ID'];
									$OptSubject  = $dbarray['SubjID'];
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
										$query = "select EmpName,Photo from tbemployeemasters where ID='$SenderID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$SenderName  = formatDatabaseStr($dbarray['EmpName']);
										$Senderphoto  = $dbarray['Photo'];
									}else{
										$query = "select Stu_Full_Name,Stu_Photo from tbstudentmaster where AdmissionNo='$SenderID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$SenderName  = formatDatabaseStr($dbarray['Stu_Full_Name']);
										$Senderphoto  = $dbarray['Stu_Photo'];
									}
									if($Senderphoto==""){
										$Senderphoto = "NoImage.jpg";
									}
?>
							<TD width="32%" valign="top" class="systemtdtext">
								<TABLE style="BORDER-RIGHT: #dbdbdb 1px solid; BORDER-TOP: #dbdbdb 1px solid; BORDER-LEFT: #dbdbdb 1px solid; WIDTH: 100%x; BORDER-BOTTOM: #dbdbdb 1px solid; HEIGHT: 280px" cellSpacing=0 cellPadding=0 border=0>
									<TBODY>
									<TR bgcolor="#666666" class=body id=staff_actions_slot>
									  <TD width="319" height="24" style="PADDING-RIGHT: 0px; PADDING-LEFT: 6px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; TEXT-ALIGN: left"><span class="style25 style24 style1"><strong>Other Post </strong></span></TD>
									  <TD width="257"
									  style="PADDING-RIGHT: 2px; PADDING-LEFT: 2px; PADDING-BOTTOM: 0px;PADDING-TOP: 0px; TEXT-ALIGN: right"><span class="style24">&nbsp;</span></TD>
									</TR>
									<TR>
									  <TD style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 4px; PADDING-TOP: 4px; BACKGROUND-COLOR: #ffffff" valign="top" colspan="2">
										<TABLE width="100%" height="109" border=0 style="WIDTH: 100%;">
										  <TBODY>
<?PHP
											$counter_dept = 0;
											$query2 = "select * from tbclasspost where SubjID = '$OptSubject' And SessionID = '$ActiveSession' And Section = '$Activeterm'";
											$result2 = mysql_query($query2,$conn);
											$num_rows2 = mysql_num_rows($result2);
											
											if($rstart==0){
												$counter_dept = $rstart;
											}else{
												$counter_dept = $rstart-1;
											}
											$counter = 0;
											$query3 = "select * from tbclasspost where SubjID = '$OptSubject' And SessionID = '$ActiveSession' And Section = '$Activeterm' LIMIT $rstart,$rend";
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
														$query = "select Stu_Full_Name,Stu_Photo from tbstudentmaster where AdmissionNo='$o_SenderID'";
														$result = mysql_query($query,$conn);
														$dbarray = mysql_fetch_array($result);
														$SenderName  = formatDatabaseStr($dbarray['Stu_Full_Name']);
														$o_Senderphoto  = $dbarray['Stu_Photo'];
													}
													if($o_Senderphoto==""){
														$o_Senderphoto = "NoImage.jpg";
													}
													$numrows = 0;
													$query4   = "SELECT COUNT(ID) AS numrows FROM tbclasspost_reply where ClassPostID = '$o_Post_ID'";
													$result4  = mysql_query($query4,$conn) or die('Error, query failed');
													$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
													$PostTotal = $row4['numrows'];
													$PostTotal = "<br>Total of ".$PostTotal." Comments"
?>
													  <TR vAlign=top>
														<TD width="42" height="34"><IMG height=32 src="../ad/Images/uploads/<?PHP echo $o_Senderphoto; ?>" width=33 border=0></TD>
														<TD width="244" style="WIDTH: 100%x; BORDER-BOTTOM: #dbdbdb 1px solid;"><a href="welcome.php?pg=Student Blog&Oth_post_id=<?PHP echo $o_Post_ID; ?>"><?PHP echo $postcomment; ?>....&nbsp;&nbsp;&nbsp;&nbsp;<font size="-3"><em><?PHP echo $PostTotal; ?></em></font></a></TD>
													  </TR>
<?PHP
												 }
											 }
?>
											<TR>
												<TD colspan="2"> <p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Student Post&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Student Post&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="classwrk.php?subpg=Student Post&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
											</TR>
										  </TBODY></TABLE>
									  </TD>
								      </TR>
								    </TBODY>
								  </TABLE>
							</TD>
						</TR>
						</TBODY></TABLE>
						</form>
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