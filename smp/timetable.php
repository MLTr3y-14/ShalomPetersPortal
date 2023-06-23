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
	
	$OptClass = $StuClass;
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
.style2 {color: #000000}
-->
</style>
<script type="text/javascript">
<!--
function clearDefault(el) {
if (el.defaultValue==el.value) el.value = ""
}
// -->
</script>
<script language="JavaScript">
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->
</script>
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
				</SPAN>			</DIV>
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
<TABLE width="100%" cellpadding="10" cellspacing="5" style="WIDTH: 100%;">
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Monday&subj=<?PHP echo $arr_Mon_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Monday&subj=<?PHP echo $arr_Mon_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Tuesday&subj=<?PHP echo $arr_Tue_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Tue_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Tuesday&subj=<?PHP echo $arr_Tue_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Tue_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Wednesday&subj=<?PHP echo $arr_Wed_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Wednesday&subj=<?PHP echo $arr_Wed_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Thursday&subj=<?PHP echo $arr_Thur_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Thursday&subj=<?PHP echo $arr_Thur_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Friday&subj=<?PHP echo $arr_Fri_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Fri_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Friday&subj=<?PHP echo $arr_Fri_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Fri_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Saturday&subj=<?PHP echo $arr_Sat_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo]); ?></a>
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
	href="JavaScript: newWindow = openWin('TTPopUp.php?class=<?PHP echo $OptClass; ?>&wd=Saturday&subj=<?PHP echo $arr_Sat_Subj[$LNo]; ?>', '', 'width=450,height=160,toolbar=0,location=0,directories=0,status=1,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo]); ?></a>
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
					  <DIV class="clr"></DIV>
				  </DIV>
				</DIV>
			</DIV>
      </DIV>
	        <DIV id="contentDiv">				
				<SPAN class="article_separator">&nbsp;</SPAN>		  </DIV>
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