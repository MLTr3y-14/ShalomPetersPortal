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
		
	//GET ACTIVE TERM AND SESSION
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$ActiveValue = 1;
	$ActiveSession  = Get_Active_Session(1);
	
	if(isset($_GET['pg']))
	{
		$PageHasError = 0;
		$Page = $_GET['pg'];
		if($Page=="subject result"){
			$OptSubject = $_GET['id'];
			$OptClass = $StuClass;
			$OptExam = $_GET['exam'];
			
			if($OptClass==""){
				$errormsg = "<font color = red size = 1>Invalid Class Name.</font>";
				$PageHasError = 1;
			}
			if($OptSubject ==""){
				$errormsg = "<font color = red size = 1>Invalid Subject.</font>";
				$PageHasError = 1;
			}
			if($OptExam ==""){
				$errormsg = "<font color = red size = 1>Invalid Examination Name.</font>";
				$PageHasError = 1;
			}
		}
	}
	if(isset($_POST['GetStudent']))
	{
		$attDate = $_POST['att_Yr']."-".$_POST['att_Mth']."-".$_POST['att_Dy'];
		$att_Yr = $_POST['att_Yr'];
		$att_Mth = $_POST['att_Mth'];
		$att_Dy = $_POST['att_Dy'];
		
		if($attDate == "--"){
			$errormsg = "<font color = red size = 1>Attedance date is empty.</font>";
			$PageHasError = 1;
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
			<A href="welcome.php?pg=Student Blog"><IMG src="Images/img_logo_lpc.png" alt="SkoolNet Manager" title="SkoolNet Manager"></A>		</DIV>
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
		if ($Page == "subject result") {
?>
				<?PHP echo $errormsg; ?>
				<TABLE width="99%" style="WIDTH: 98%">
				<TBODY>
				<TR>
				  <TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
						<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
						<TBODY>
						<TR bgcolor="#666666">
						  <TD width="5%" height="25"  align="left" valign="top"><span class="style1 style23 style25"><strong>#</strong></span></TD>
						  <TD width="23%"  align="center" valign="top"><span class="style1 style23 style25"><strong>Subject Name</strong></span></TD>
						  <TD width="72%"  align="center" valign="top"><span class="style1 style23 style25"><strong>Result Details</strong></span></TD>
						</TR>
<?PHP
						$counter = 0;
						$query3 = "select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass'";
						$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter = $counter+1;
								$SubjectId = $row["SubjectId"];
								$SubjName = GetSubjectName($SubjectId);
								if($OptSubject==$SubjectId){
									$bg="#FF9900";
								}else{
									$bg="#ffffff";
								}
?>
								<TR bgcolor="<?PHP echo $bg; ?>">
								  <TD width="5%"  align="center" valign="top" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $counter; ?></TD>
								  <TD width="23%"  align="center" valign="top" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $SubjName; ?></TD>
								  <TD width="72%"  align="center" valign="top" bgcolor="<?PHP echo $bg; ?>">
<?PHP
									$numrows = 0;
									$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$OptClass' and SubjectId='$SubjectId' and ExamId='$OptExam'";
									$result4  = mysql_query($query4,$conn) or die('Error, query failed');
									$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
									$Tot_TD = $row4['numrows'];
									$AvgWidth = 65/ ($Tot_TD+1);
									
									$i=0;
									$query5 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$OptClass' and SubjectId='$SubjectId' and ExamId='$OptExam'";
									$result5 = mysql_query($query5,$conn);
									$num_rows5 = mysql_num_rows($result5);
									if ($num_rows5 > 0 ) {
										while ($row5 = mysql_fetch_array($result5)) 
										{
											$arr_Exam_Id[$i] = $row5["ID"];
											$arr_Exam_Setup[$i][1] = $row5["ResultType"];
											$arr_Exam_Setup[$i][2] = $row5["Percentage"];
											$i = $i+1;
										}
									}
									$query6 = "select MaxMark from tbexammarkssetup where ClassID='$OptClass' And ExamID='$OptExam' And SubjectID='$SubjectId'";
									$result6 = mysql_query($query6,$conn);
									$dbarray6 = mysql_fetch_array($result6);
									$SubjMaxMark  = $dbarray6['MaxMark'];
?>
									<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
										<TR>
<?PHP
											for($i=1;$i<=$Tot_TD;$i++)
											{
												echo "<TD width='$AvgWidth%' align='left' valign='top'><span class='style25'><strong>".$arr_Exam_Setup[$i-1][1]."</strong></span>&nbsp;</TD>";
											}
?>
											<TD align="Right" valign="top"><div style="margin-left:10px; margin-right:10px;"><strong>Final Score</strong></div></TD>
										</TR>
										<TR>
<?PHP
											$SubjMaxMark = 0;
											$FinalMarks = 0;
											$SubjMaxMark = 0;
											$sMarks = 0;
											for($i=1;$i<=$Tot_TD;$i++)
											{
												echo "<TD width='$AvgWidth%' align='left' valign='top'><div style='margin-left:10px; margin-right:10px;'>";
												
												$query2 = "select Marks from tbstudentperformance where class_id = '$OptClass' and AdmnNo = '$AdmissionNo' and ExamId = '$OptExam' And SubjectId = '$SubjectId' And ResultTypeId = '".$arr_Exam_Id[$i-1]."' And Term = '$Activeterm'";
												$result2 = mysql_query($query2,$conn);
												$dbarray2 = mysql_fetch_array($result2);
												$sMarks  = $dbarray2['Marks'];
												if($sMarks == 0){
													$sMarks = '';
												}
												$FinalMarks = $FinalMarks + $sMarks;
												$SubjMaxMark = $SubjMaxMark +$arr_Exam_Setup[$i-1][2];
												echo $sMarks."/".$arr_Exam_Setup[$i-1][2]."</div></TD>";
												
											}
											if($FinalMarks == 0){
												$FinalMarks = '-';
												$SubjMaxMark = '-';
											}
											
											
?>
											<TD width="15%"  align="Right" valign="top"><div style="margin-left:10px; margin-right:25px;"><?PHP echo $FinalMarks; ?>/<?PHP echo $SubjMaxMark; ?></div></TD>
										</TR>
								    </TABLE>
								  </TD>
								</TR>
<?PHP
							 }
						 }
?>
						</TBODY>
						</TABLE>
					 </TD>
				</TR>
			</TBODY>
			</TABLE>
<?PHP
		}elseif ($Page == "Student Result") {
			//GET Student Details
			$query2 = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo = '$AdmissionNo' and Stu_Class = '$StuClass'";
			$result2 = mysql_query($query2,$conn);
			$dbarray2 = mysql_fetch_array($result2);
			$RegNo  = $dbarray['Stu_Regist_No'];
			$Stu_Full_Name  = $dbarray2['Stu_Full_Name'];
			
			$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$Contact  = $dbarray['Gr_Ph'];
			if(isset($_POST['SubmitGo']))
			{
				$OptExam = $_POST['OptExam'];
				$ExamId = $_POST['OptExam']; 
			}
		
			//echo $OptExam;		
?>
			<form name="form1" method="post" action="result.php?pg=Student Result">
			<select name="OptExam">
			  <option value="" selected="selected">Select</option>
<?PHP
				$counter = 0;
				$query = "select ID,ExamName from tbexaminationmaster order by ExamName";
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
			</select><input name="SubmitGo" type="submit" id="SubmitGo" value="Go">
			</form>
			<table  width="95%" cellpadding="8" cellspacing="0" border="0">
			  <tbody>
				<tr>
				  <td width="51%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Student Name : </strong><?PHP echo $Stu_Full_Name; ?></p></td>
				  <td width="49%" align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" valign="top"><strong>Grade Explanation </strong> </td>
				</tr>
				<tr>
				  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Admn No  : </strong> <?PHP echo $AdmissionNo; ?> </p></td>
				  <td align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" rowspan="5" valign="top">
				  <table width="156" border="0" align="right" cellpadding="3" cellspacing="3">
					  <tr>
						<td width="39" bgcolor="#F4F4F4"><div align="right" class="style21">Grade</div></td>
						<td width="53" bgcolor="#F4F4F4"><div align="right" class="style21">From</div></td>
						<td width="34" bgcolor="#F4F4F4"><div align="right"><strong>To</strong></div></td>
					  </tr>
<?PHP
						$query3 = "select * from tbgradedetail order by GradeTo desc";
						$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$GradeName = $row["GradeName"];
								$GradeFrom = $row["GradeFrom"];
								$GradeTo = $row["GradeTo"];
?>
								  <tr>
									<td><div align="right"><?PHP echo $GradeName; ?></div></td>
									<td><div align="right"><?PHP echo $GradeFrom; ?></div></td>
									<td><div align="right"><?PHP echo $GradeTo; ?></div></td>
								 </tr>
<?PHP
							 }
						 }
?>
					</table>
				  </td>
				</tr>
				<tr>
				  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Result Type : </strong>Student Examination Result</p></td>
				</tr>
				<tr>
				  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Student Faculty : </strong><?PHP echo GetClassName($StuClass); ?> </p></td>
				</tr>
				<tr>
				  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="2" align="left"><div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Final Examination Result</strong></div></td>
				</tr>
				<tr>
				  <td colspan="2" align="left">
				  <table  width="97%" align="center" cellpadding="5" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 97%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">
					  <tbody>
						<tr>
						  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF">Subject</strong></font></td>
						  <td width="59%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Course Work</strong></font></td>
						  <td width="11%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Actual Score / <br>
						  Max Score </strong></font></td>
						  <td width="9%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong> (%) </strong></font></td>
						  <td width="7%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Grade</strong></font></td>
						</tr>
<?PHP
					$SumFinalMarks = 0;
					$SumMaxMarks = 0;
					$SumPercent = 0;
					$SumMaxPercent = 0;
					$query0 = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$StuClass') order by Subj_Priority";
					$result0 = mysql_query($query0,$conn);
					$num_rows0 = mysql_num_rows($result0);
					if ($num_rows0 > 0 ) {
						while ($row0 = mysql_fetch_array($result0)) 
						{
							$subjID = $row0["ID"];
							$Subjname = $row0["Subj_name"];
							$FinalMarks = 0;
							$numrows = 0;
							$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$StuClass' and SubjectId='$subjID' and ExamId='$ExamId'";
							$result4  = mysql_query($query4,$conn) or die('Error, query failed');
							$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
							$Tot_TD = $row4['numrows'];
							$AvgWidth = 100/ ($Tot_TD);
							
							$i=0;
							$query3 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$StuClass' and SubjectId='$subjID' and ExamId='$ExamId'";
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
							$query = "select MaxMark from tbexammarkssetup where ClassID='$StuClass' And ExamID='$ExamId' And SubjectID='$subjID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$MaxPercent  = $dbarray['MaxMark'];							
?>
							<tr>
							  <td width="14%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $Subjname; ?></td>
							  <td width="59%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;">
								<table width="100%" border="0" align="right" cellpadding="3" cellspacing="3">
								  <tr>
<?PHP
									for($i=1;$i<=$Tot_TD;$i++)
									{
										echo "<TD width='$AvgWidth%' align='center' valign='top'  bgcolor='#f4f4f4'><strong><span class='style23'>".$arr_Exam_Setup[$i-1][1]."</span></strong></TD>";
									}
?>
								  </tr>
								  <tr>
<?PHP
									$SubjMaxMark = 0;
									$sMaxMark = 0;
									for($i=1;$i<=$Tot_TD;$i++)
									{
										echo "<TD width='$AvgWidth%' align='center' valign='top' style='BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;'>";
										$query = "select Marks from tbstudentperformance where class_id = '$StuClass' and AdmnNo = '$AdmissionNo' and ExamId = '$ExamId' And SubjectId = '$subjID' And ResultTypeId = '".$arr_Exam_Id[$i-1]."' And Term = '$Activeterm'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$sMarks  = $dbarray['Marks'];
										if($sMarks == 0){
											$sMarks = '';
										}
										if($sMarks !=""){
											$FinalMarks = $FinalMarks + $sMarks;
											$SubjMaxMark = $SubjMaxMark +$arr_Exam_Setup[$i-1][2];
											$FinalPercentage = ($FinalMarks / $SubjMaxMark) * 100;
											$FinalPercentage = $FinalPercentage;
											echo $sMarks." / ".$arr_Exam_Setup[$i-1][2];
										}else{
											echo "-&nbsp;";
										}
										echo "</TD>";
									}
									$GradeName = "-";
									if($FinalMarks > 0){
										$query = "select GradeName from tbgradedetail where GradeFrom <='$FinalPercentage' And GradeTo >='$FinalPercentage'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$GradeName  = $dbarray['GradeName'];
									}
									
									if($FinalMarks == 0){
										$SubjMaxMark = "-";
										$FinalMarks = "";
										$FinalPercentage = "";
									}else{
										$sMaxMark = $SubjMaxMark;
										$SubjMaxMark = " / ".$SubjMaxMark;
										$SumPercent = $SumPercent + $FinalPercentage;
										$SumMaxPercent = $SumMaxPercent + $MaxPercent; 
									}
									
?>
								  </tr>
								</table>
							  </td>
							  <td width="11%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $FinalMarks; ?><?PHP echo $SubjMaxMark; ?></td>
							  <td width="9%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo number_format($FinalPercentage,2); ?></td>
							  <td width="7%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $GradeName; ?></td>
							</tr>
<?PHP
							$SumFinalMarks = $SumFinalMarks +$FinalMarks;
							$SumMaxMarks = $SumMaxMarks + $sMaxMark;
						}
					}
					$AvgActualMark= 0;
					//echo $SumPercent;
					//echo $SumMaxPercent;
					$AvgMark = ($SumPercent / $SumMaxPercent) * 100;
					$AvgActualMark = ($SumFinalMarks / $SumMaxMarks) * 100;
					$query = "select GradeName from tbgradedetail where GradeFrom <='$AvgMark' And GradeTo >='$AvgMark'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$GradeName  = $dbarray['GradeName'];
?>
					<tr>
					  <td width="14%" align="right">&nbsp;</td>
					  <td width="59%" align="right"><strong>Average Score</strong></td>
					  <td width="11%" align="center"><?PHP echo number_format($AvgActualMark,2); ?></td>
					  <td width="9%" align="center"><?PHP echo number_format($AvgMark,2);?>%</td>
					  <td width="7%" align="center"><?PHP echo $GradeName; ?></td>
					</tr>
<?PHP
						$i=0;
						$query4 = "select AdmissionNo from tbstudentmaster where Stu_Class = '$StuClass'";
						$result4 = mysql_query($query4,$conn);
						$num_rows4 = mysql_num_rows($result4);
						if ($num_rows4 > 0 ) {
							while ($row4 = mysql_fetch_array($result4)) 
							{
								$AdmnNo = $row4["AdmissionNo"];
								$stMarks = 0;
								$query1 = "select ID from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$StuClass')";
								$result1 = mysql_query($query1,$conn);
								$num_rows1 = mysql_num_rows($result1);
								if ($num_rows1 > 0 ) {
									while ($row1 = mysql_fetch_array($result1)) 
									{
										$SubId = $row1["ID"];
										$query3 = "select Marks from tbstudentperformance where class_id='$StuClass' and AdmnNo='$AdmnNo' and ExamId='$ExamId' and SubjectId ='$SubId' and Term = '$Activeterm'";
										
										$result3 = mysql_query($query3,$conn);
										$num_rows = mysql_num_rows($result3);
										if ($num_rows > 0 ) {
											while ($row = mysql_fetch_array($result3)) 
											{
												$stMarks = $stMarks + $row["Marks"];
												//echo $stMarks."&nbsp;&nbsp;&nbsp;&nbsp;";
											}
										}
									}
								}
								$arr_Class_Rank[$i][1]= $stMarks;
								$arr_Class_Rank[$i][2]= $AdmnNo;
								$i = $i+1;
								//echo "<br>";
							}
						}
						$count_stud = $i;
						rsort($arr_Class_Rank);
						$StudentRanking = 0;
						for($i=0;$i<$count_stud;$i++)
						{
							if($arr_Class_Rank[$i][2] == $sAdmnNo){
								if($arr_Class_Rank[$i][1]>0){
									$StudentRanking = $i+1;
								}else{
									$StudentRanking = "-";
								}
							}
						}
						if($StudentRanking==1){
							$StudentRanking = "1st Position";
						}elseif($StudentRanking==2){
							$StudentRanking = "2nd Position";
						}elseif($StudentRanking==3){
							$StudentRanking = "3rd Position";
						}elseif($StudentRanking!="-"){
							$StudentRanking = $StudentRanking."th Position";
						}
?>
						<!--<tr>
						  <td width="14%" align="right">&nbsp;</td>
						  <td width="59%" align="right"><strong>Student Ranking / Position in class</strong></td>
						  <td colspan="2" align="left"><div style="margin-left:20px;"><?PHP echo $StudentRanking; ?></div></td>
						  <td align="left">&nbsp;</td>
						</tr>-->
					</tbody>
					</table>
				  </td>
				</tr>
			  </tbody>
		  </table>
<?PHP
		}elseif ($Page == "Students attendance") {
?>
				<p>&nbsp;</p><?PHP echo $errormsg; ?>
				<TABLE width="99%" align="center" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="72%" valign="top"  align="left">
					  		<table width="495" align="left" cellpadding="4">
								<tr>
									<td width="41"><div align="left">Date:</div></td>
									<td width="430">
									  <form name="form1" method="post" action="result.php?pg=Students attendance">
								        <div align="left">
									        <select name="att_Dy">
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
								            <select name="att_Mth">
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
								            <select name="att_Yr">
								              <option value="" selected="selected">Year</option>
<?PHP
												$CurYear = date('Y');
												for($i=2009; $i<=$CurYear; $i++){
													if($att_Yr == $i){
														echo "<option value=$i selected=selected>$i</option>";
													}else{
														echo "<option value=$i>$i</option>";
													}
												}
?>
							                </select>
									        
							              <input type="submit" name="GetStudent" value="Go">
						                </div></form></td>
								</tr>
						</table>
					        <div align="center">
							  <p>&nbsp;  </p>
							  <p>&nbsp;</p>
							  <p>&nbsp;</p>
							  <p align="left">
							    <input type="submit" name="SubmitSAtt" value="P" style="background:#F2F2F2">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    <input type="submit" name="SubmitSAtt" value="L" style=" background:#FFCC33">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    <input type="submit" name="SubmitSAtt" value="L.5"  style=" background:#66FFCC">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    <input type="submit" name="SubmitSAtt" value="A" style=" background:#FF9C97">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    <input type="submit" name="SubmitSAtt" value="A.5"  style=" background:#CCFF00">
							    <br>
							    Present&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Leave&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Leave&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Absent&nbsp;&nbsp;&nbsp;  &nbsp;Absent&nbsp;&nbsp;&nbsp;						              </p>
				        </div></TD>
					</TR>
					<TR>
					  <TD valign="top"  align="left">		
					    <p>&nbsp;</p>
							<table  width="106%" align="center" cellpadding="6" cellspacing="6" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 98%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
							  <tbody>
								<tr>
								  <td width="150" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Att. Date </strong></div></td>
								  <td width="170" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Status </strong></div></td>
								  <td width="331" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Full Description</strong></div></td>
								</tr>
<?PHP
								$query = "select Stu_Full_Name from tbstudentmaster where Stu_Sec ='$Activeterm' and AdmissionNo = '$AdmissionNo' order by Stu_Full_Name";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Stu_Full_Name = $dbarray['Stu_Full_Name'];
?>
								  <tr>
									<td colspan="3"><p style="margin-left:10px;"><strong><?PHP echo $Stu_Full_Name; ?></strong></p></td>
								  </tr>
<?PHP
									$query3 = "select Att_date,Status from tbattendancestudent where AdmnNo = '$AdmissionNo' And Term = '$Activeterm'";
									$result3 = mysql_query($query3,$conn);
									$num_rows = mysql_num_rows($result3);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result3)) 
										{
											$counter_Stud = $counter_Stud+1;
											$counter = $counter+1;
											$attDate = $row["Att_date"];		
											$Status = $row["Status"];
											$bgs = "";
											if($Status == ""){
												$Status = "-";
											}elseif($Status =="P"){
												 $bgs = "bgcolor='#F2F2F2'";
												 $Desc = "Present";
											}elseif($Status =="L"){
												 $bgs = "bgcolor='#FFCC33'";
												 $Desc = "On Leave";
											}elseif($Status =="L.5"){
												 $bgs = "bgcolor='#66FFCC'";
												 $Desc = "On leave half of the day";
											}elseif($Status =="A"){
												 $bgs = "bgcolor='#FF9C97'";
												 $Desc = "Absent";
											}elseif($Status =="A.5"){
												 $bgs = "bgcolor='#CCFF00'";
												 $Desc = "Absent half of the day";
											}
?>								  
										  <tr <?PHP echo $bgs; ?>>
											<td><div align="center"><?PHP echo Long_date($attDate); ?></div></td>
											<td><div align="center"><?PHP echo $Status; ?></div></td>
											<td><div align="center"><?PHP echo $Desc; ?></div></td>
										  </tr>
<?PHP
										}
									}
?>
							  </tbody>
					    </table>
				      </TD>
					</TR>
				</TBODY>
				</TABLE>
				

				</TABLE>
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