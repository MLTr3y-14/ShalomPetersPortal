<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	include 'sms/sms_processor.php';
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
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
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	
	
	if ($HeaderPic == ""){
		$HeaderPic = "empty_r2_c2.jpg";
	}
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
		$ClassId = $_GET['cid'];
		$SubjectId = $_GET['sid'];
		$ExamId = $_GET['eid'];
		$sAdmnNo = $_GET['adm'];
		$OptStudent = $_GET['adm'];
		$SelStuClass = $_GET['cid'];
		
		$query = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo='$OptStudent' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Name  = $dbarray['Stu_Full_Name'];
		
		$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Contact  = $dbarray['Gr_Ph'];
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
	//GET CLASS TEACHER
	$query2 = "select Incharge from tbclasssection where ClassID = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Incharge  = $dbarray2['Incharge'];
	//echo $query2;
	
	
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 20;
	}
	if(isset($_GET['page']))
	{
		//$Page = $_GET['page'];
		
	}	
	if(isset($_GET['mth']))
	{
		$Method = $_GET['mth'];
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
	  <TR>
	  	<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
	  </TR>
<?PHP
		if ($Page == "Subject Wise") {
			$CountSMS = 0;
?>
		  <TR>
			<TD>
<?PHP
			if(isset($_GET['mod']))
			{
				$mod = $_GET['mod'];
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="#">Examination</a> &gt; <a href="performance.php?subpg=Subject Report">Examination Report<hr>
</a> &gt; Subject Wise Result</strong></div>
<?PHP
			}else{
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="#">Examination</a> &gt; <a href="performance.php?subpg=Subject Report">Examination Report</a> &gt; Subject Wise Result</strong></div>
<?PHP
			}
?>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong><?PHP echo GetExamName($ExamId); ?></strong></div>
				<table  width="90%" border="0" align="center" cellpadding="8" cellspacing="0">
				  <tbody>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Class Name : <?PHP echo GetClassName($ClassId); ?> </strong></td>
					  <td align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Class Teacher : <?PHP echo GET_EMP_NAME($Incharge); ?></strong> </td>
					</tr>		
					<tr>
					  <td colspan="2" align="left">&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2" align="left">
<?PHP
						
						if($_SESSION['module'] == "Teacher"){
							$query0 = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' And ClassId = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And ID = '$SubjectId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
						}else{
							$query0 = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And ID = '$SubjectId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
						}
						
						$result0 = mysql_query($query0,$conn);
						$num_rows0 = mysql_num_rows($result0);
						if ($num_rows0 > 0 ) {
							while ($row0 = mysql_fetch_array($result0)) 
							{
								$subjID = $row0["ID"];
								$Subjname = $row0["Subj_name"];
								echo "<br><br>";
								echo "<strong>".$Subjname."</strong>";
?>
								<table  width="100%" cellpadding="5" cellspacing="3" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
								  <tbody>
									<tr>
									  <td width="15%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Admn No.</strong></font></td>
									  <td width="21%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Name</strong></font></td>
									  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Actual Score / <br>
								      Total Score</strong></font></td>
									  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Pass Mark (%) </strong></font></td>
									  <td width="10%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Mark (%) </strong></font></td>
									  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Max Marks (%) </strong></font></td>
									  <td width="7%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Ranking</strong></font></td>
									  <td width="10%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Grade</strong></font></td>
									</tr>
<?PHP
									$counter1=1;
									$counter = 0;
									if($sAdmnNo !=""){
										$query4 = "select AdmissionNo from tbstudentmaster where Stu_Class = '$ClassId' And AdmissionNo = '$sAdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									}else{
										$query4 = "select AdmissionNo from tbstudentmaster where Stu_Class = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									}
									
									$result4 = mysql_query($query4,$conn);
									$num_rows4 = mysql_num_rows($result4);
									if ($num_rows4 > 0 ) {
										while ($row4 = mysql_fetch_array($result4)) 
										{
											$AdmnNo = $row4["AdmissionNo"];
											$FinalMarks = 0;
											
											$query1 = "select Marks from tbstudentperformance where class_id = '$ClassId' and AdmnNo = '$AdmnNo' and ExamId = '$ExamId' And SubjectId = '$subjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result1 = mysql_query($query1,$conn);
											$num_rows1 = mysql_num_rows($result1);
											if ($num_rows1 > 0 ) {
												while ($row1 = mysql_fetch_array($result1)) 
												{
													$sMarks = $row1["Marks"];
													$FinalMarks = $FinalMarks + $sMarks;
												}
											}
											 //if($FinalMarks == 0){
											   //  $FinalMarks = 0.1;
										        //}
											$arr_RankingFinalMarks[$AdmnNo]= $FinalMarks;
											//$arr_RankingFinalMarks[$counter1][2]= $AdmnNo;
											//$arr_AdminNo[$i]= $AdmnNo;
										 // $counter1 = $counter1+1;
										}
									}
									$FinalMarks_array[$counter1-1] = 0;
									arsort($arr_RankingFinalMarks);
									foreach ($arr_RankingFinalMarks as $key => $val) {
										$AdmnNo = $key;
										 $FinalMarks = $val;
										 $FinalMarks_array[$counter1] = $FinalMarks;
										 if($FinalMarks==$FinalMarks_array[$counter1-1])
										 {
											 $StudentRanking = $StudentRanking1;
										 }else{
                                       $StudentRanking = $counter1;
									     $StudentRanking1 = $StudentRanking;
										 }
										$counter1 = $counter1+1;
										
										if($StudentRanking==1){
												$StudentRanking = "1st";
											}elseif($StudentRanking==2){
												$StudentRanking = "2nd";
											}elseif($StudentRanking==3){
												$StudentRanking = "3rd";
											}elseif($StudentRanking!="-"){
												$StudentRanking = $StudentRanking."th";
											}
										$query3 = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where Stu_Class = '$ClassId' And AdmissionNo = '$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									   $result3 = mysql_query($query3,$conn);
									   $row = mysql_fetch_array($result3); 
									     $counter = $counter+1;
											$RegNo  = $dbarray['Stu_Regist_No'];
											$StudName = $row["Stu_Full_Name"];
											
											$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$Contact  = $dbarray['Gr_Ph'];
											$TotalPer = 0;
											$query5 = "select Percentage from tbclassexamsetup where ClassId ='$ClassId' And SubjectId ='$subjID' And ExamId = '$ExamId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result5 = mysql_query($query5,$conn);
											$num_rows5 = mysql_num_rows($result5);
											if ($num_rows5 > 0 ) {
												while ($row5 = mysql_fetch_array($result5)) 
												{
													$Percentage = $row5["Percentage"];
													$TotalPer = $TotalPer +$Percentage;
													//$FinalMarks = $TotalPer;
												}
											}
											if($FinalMarks == 0){
												$FinalMarks = 0;
												$TotalPer = " / ".$TotalPer;
												$FinalPercentage = 0;
											}else{
												$FinalPercentage = ($FinalMarks / $TotalPer) * 100;
												$TotalPer = " / ".$TotalPer;
											}
											if($counter % 2 == 1){
												$bg="#F2F2F2";
											}else{
												$bg="#FFFFFF";
											}
											
											$query = "select MaxMark,PassMark from tbexammarkssetup where ClassID='$ClassId' And ExamID='$ExamId' And SubjectID='$subjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$SubjMaxMark  = $dbarray['MaxMark'];
											$PassMark  = $dbarray['PassMark'];
											
											$query = "select GradeName from tbgradedetail where GradeFrom <='$FinalPercentage' And GradeTo >='$FinalPercentage'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$GradeName  = $dbarray['GradeName'];
				
?>	
	<tr>
											  <td width="15%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $AdmnNo; ?></td>
											  <td width="21%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $StudName; ?></td>
											  <td width="13%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $FinalMarks; ?><?PHP echo $TotalPer; ?></td>
											  <td width="12%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $PassMark; ?></td>
											  <td width="10%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo  number_format($FinalPercentage,2); ?></td>
											  <td width="12%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $SubjMaxMark; ?></td>
											  <td width="7%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $StudentRanking; ?></td>
											  <td width="10%" align="center" bgcolor="<?PHP echo $bg; ?>"><?PHP echo $GradeName; ?></td>
											</tr>										
<?php                                               
										}
                                        
								//	}
									
							
?>
										
<?PHP 
											if($Method == "SMS"){
												$isSend_Status="False";
												//echo $AdmnNo.",".$StudName.",".GetExamName($ExamId).",".$FinalPercentage.",".$Subjname.",".$Contact;
												//$isSend_Status = sendPerformanceBySubject($AdmnNo,$StudName,GetExamName($ExamId),$FinalPercentage,$Subjname,$Contact);
												if($isSend_Status == "False"){
													$CountSMS = $CountSMS;
												}elseif($isSend_Status == "True"){
													$CountSMS = $CountSMS + 1;
												}	
											}
?>
											  
<?PHP
										}
									}
									
									$FinalPercentage = "";
									
?>
						  		</tbody>
						  		</table>
<?PHP
							//}
						//}
						if($Method == "SMS"){
							echo "<meta http-equiv=\"Refresh\" content=\"0;url=performance.php?subpg=Student Result&totsent=$CountSMS\">";
							exit;
						}
?>
					  </td>
					</tr>
			      </tbody>
			  </table>
			</TD>
		  </TR>
<?PHP
		}elseif ($Page == "Student Result") {
			//GET Student Details
			$query2 = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo = '$sAdmnNo' and Stu_Class = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result2 = mysql_query($query2,$conn);
			$dbarray2 = mysql_fetch_array($result2);
			$RegNo  = $dbarray['Stu_Regist_No'];
			$Stu_Full_Name  = $dbarray2['Stu_Full_Name'];
			
			$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$Contact  = $dbarray['Gr_Ph'];				
?>
		  <TR>
			<TD>
<?PHP
			if(isset($_GET['mod']))
			{
				$mod = $_GET['mod'];
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="exam.php?subpg=Examination%20Master">Examination</a> &gt; <a href="performance.php?subpg=Student Result">Student Performance</a> &gt; Student Result</strong></div>
<?PHP
			}else{
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="exam.php?subpg=Examination%20Master">Examination</a> &gt; <a href="performance.php?subpg=Student%20Performance">Student Performance</a> &gt; Student Result</strong></div>
<?PHP
			}
?>

				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				<table  width="95%" cellpadding="8" cellspacing="0" border="0">
				  <tbody>
					<tr>
					  <td width="51%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Student Name : </strong><?PHP echo $Stu_Full_Name; ?></p></td>
					  <td width="49%" align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" valign="top"><strong>Grade Explanation </strong> </td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Admn No  : </strong> <?PHP echo $sAdmnNo; ?> </p></td>
					  <td align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" rowspan="5" valign="top">
					  <table width="156" border="0" align="right" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="39" bgcolor="#F4F4F4"><div align="right" class="style21"><strong>Grade</strong></div></td>
                            <td width="53" bgcolor="#F4F4F4"><div align="right" class="style21"><strong>From</strong></div></td>
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
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Student Class : </strong><?PHP echo GetClassName($ClassId); ?> </p></td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><p style="margin-left:25px;"><strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($Incharge); ?> </p></td>
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
							  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Subject</strong></font></td>
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
						$query0 = "select ID,Subj_name from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_Priority";
						$result0 = mysql_query($query0,$conn);
						$num_rows0 = mysql_num_rows($result0);
						if ($num_rows0 > 0 ) {
							while ($row0 = mysql_fetch_array($result0)) 
							{
								$subjID = $row0["ID"];
								$Subjname = $row0["Subj_name"];
								$FinalMarks = 0;
								$numrows = 0;
								$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$ClassId' and SubjectId='$subjID' and ExamId='$ExamId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result4  = mysql_query($query4,$conn) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$Tot_TD = $row4['numrows'];
								$AvgWidth = 100/ ($Tot_TD+1);
								
								$i=0;
								$query3 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$ClassId' and SubjectId='$subjID' and ExamId='$ExamId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
								$query = "select MaxMark from tbexammarkssetup where ClassID='$ClassId' And ExamID='$ExamId' And SubjectID='$subjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
											$query = "select Marks from tbstudentperformance where class_id = '$ClassId' and AdmnNo = '$sAdmnNo' and ExamId = '$ExamId' And SubjectId = '$subjID' And ResultTypeId = '".$arr_Exam_Id[$i-1]."' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$sMarks  = $dbarray['Marks'];
											$SubjMaxMark = $SubjMaxMark +$arr_Exam_Setup[$i-1][2];
											if($sMarks == 0){
												$sMarks = '';
											}
											if($sMarks !=""){
												$FinalMarks = $FinalMarks + $sMarks;
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
											$sMaxMark = $SubjMaxMark;
											$SubjMaxMark =  " / ".$SubjMaxMark;
											$FinalMarks = "";
											$FinalPercentage = "";
										}else{
											$sMaxMark = $SubjMaxMark;
											$SubjMaxMark = " / ".$SubjMaxMark;
											$SumPercent = $SumPercent + $FinalPercentage;
											$SumMaxPercent = $SumMaxPercent +$MaxPercent; 
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
						//$AvgMark = ($SumPercent / $SumMaxPercent) * 100;
						if( $SumFinalMarks == 0){
							$AvgActualMark= 0;
						}else{
						$AvgActualMark = ($SumFinalMarks / $SumMaxMarks) * 100;
						}
						$SumMaxMarks  = " / ".$SumMaxMarks ;
						$query = "select GradeName from tbgradedetail where GradeFrom <='$AvgActualMark' And GradeTo >='$AvgActualMark'";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$GradeName  = $dbarray['GradeName'];
?>
						<tr>
						  <td width="14%" align="right">&nbsp;</td>
						  <td width="59%" align="right"><strong>Average Score</strong></td>
						  <td width="11%" align="center"><?PHP echo $SumFinalMarks.$SumMaxMarks; ?></td>
						  <td width="9%" align="center">
<?PHP 
						echo number_format($AvgActualMark,2);
						if($Method == "SMS"){
							$isSend_Status="False";
							//echo "/".$sAdmnNo.",".$Stu_Full_Name.",".GetExamName($ExamId).",".number_format($AvgMark,1).",".$Contact;
							//$isSend_Status = sendPerformanceOverall($sAdmnNo,$Stu_Full_Name,GetExamName($ExamId),number_format($AvgMark,1)."%".,$Contact);
							if($isSend_Status == "False"){
								echo "<meta http-equiv=\"Refresh\" content=\"0;url=performance.php?subpg=Student Result&res=error\">";
								exit;
							}elseif($isSend_Status == "True"){
								echo "<meta http-equiv=\"Refresh\" content=\"0;url=performance.php?subpg=Student Result&res=sent\">";
								exit;
							}	
						}
?>						  
						  %</td>
						  <td width="7%" align="center"><?PHP echo $GradeName; ?></td>
						</tr>
<?PHP
							$i=0;
							$query4 = "select AdmissionNo from tbstudentmaster where Stu_Class = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result4 = mysql_query($query4,$conn);
							$num_rows4 = mysql_num_rows($result4);
							if ($num_rows4 > 0 ) {
								while ($row4 = mysql_fetch_array($result4)) 
								{
									$AdmnNo = $row4["AdmissionNo"];
									$stMarks = 0;
									$query1 = "select ID from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result1 = mysql_query($query1,$conn);
									$num_rows1 = mysql_num_rows($result1);
									if ($num_rows1 > 0 ) {
										while ($row1 = mysql_fetch_array($result1)) 
										{
											$SubId = $row1["ID"];
											$query3 = "select Marks from tbstudentperformance where class_id='$ClassId' and AdmnNo='$AdmnNo' and ExamId='$ExamId' and SubjectId ='$SubId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											
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
									$arr_Class_Rank[$AdmnNo]= $stMarks;
									//$arr_Class_Rank[$i][2]= $AdmnNo;
									//$i = $i+1;
									//echo "<br>";
								}
							}
							$counter1 = 1;
							$FinalMarks_array[$counter1-1] = 0;
							arsort($arr_Class_Rank);
									foreach ($arr_Class_Rank as $key => $val) {
										$AdmnNo = $key;
										 $FinalMarks = $val;
										 $FinalMarks_array[$counter1] = $FinalMarks;
										 if($FinalMarks==$FinalMarks_array[$counter1-1])
										 {
											 $StudentRanking = $StudentRanking1;
										 }else{
                                       $StudentRanking = $counter1;
									     $StudentRanking1 = $StudentRanking;
										 }
										 $counter1 = $counter1+1;
										//$StudentRanking = $counter1;
										
										
										if($StudentRanking==1){
												$StudentRanking = "1st";
											}elseif($StudentRanking==2){
												$StudentRanking = "2nd";
											}elseif($StudentRanking==3){
												$StudentRanking = "3rd";
											}elseif($StudentRanking!="-"){
												$StudentRanking = $StudentRanking."th";
											}
									$arr_Class_Rank_Value[$AdmnNo]= $StudentRanking;
									}
?>
							<tr>
							  <td width="14%" align="right">&nbsp;</td>
							  <td width="59%" align="right"><strong>Student Ranking / Position in class</strong></td>
							  <td colspan="2" align="left"><div style="margin-left:20px;"><?PHP echo $arr_Class_Rank_Value[$sAdmnNo]; ?></div></td>
							  <td align="left">&nbsp;</td>
							</tr>
						</tbody>
						</table>
					  </td>
					</tr>
					<tr>
					  <td align="left" colspan="2">
					    <TABLE width="97%" align="center" cellpadding="7">
							<TBODY>
							<TR>
							  <TD width="11%"  align="left"><strong>Comment </strong></TD>
							  <TD width="89%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;"><p>&nbsp;</p>
						      <p>&nbsp;</p></TD>
							</TR>
							<TR>
							  <TD width="11%"  align="left">&nbsp;</TD>
							  <TD width="89%"  align="right" valign="top" ><p>________________________________</p>
							  <p style="margin-right:50px;">Authorised Signatory</p></TD>
							</TR>
							</TBODY>
						</TABLE></td>
					</tr>
			      </tbody>
			  </table>
			</TD>
		  </TR>
<?PHP
		}elseif ($Page == "Student Ranking") {	
			$SubjMaxMark = 0;
			$query = "select MaxMark from tbexammarkssetup where ClassID='$ClassId' And ExamID='$ExamId' And SubjectID IN (Select SubjectId from tbstudentperformance where class_id='$ClassId' And ExamId='$ExamId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) {
				while ($row = mysql_fetch_array($result)) 
				{
					$MaxMark = $row["MaxMark"];
					$SubjMaxMark = $SubjMaxMark +$MaxMark;
				}
			}
			
							$i=0;
							
							$query4 = "select AdmissionNo from tbstudentmaster where Stu_Class = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result4 = mysql_query($query4,$conn);
							$num_rows4 = mysql_num_rows($result4);
							if ($num_rows4 > 0 ) {
								while ($row4 = mysql_fetch_array($result4)) 
								{
									$TotalMarks = 0;
							          //$stMarks = 0;
									$AdmnNo = $row4["AdmissionNo"];
									$stMarks = 0;
									$query1 = "select ID from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result1 = mysql_query($query1,$conn);
									$num_rows1 = mysql_num_rows($result1);
									if ($num_rows1 > 0 ) {
										while ($row1 = mysql_fetch_array($result1)) 
										{
											$SubId = $row1["ID"];
											$query3 = "select Marks from tbstudentperformance where class_id='$ClassId' and AdmnNo='$AdmnNo' and ExamId='$ExamId' and SubjectId ='$SubId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											
											$result3 = mysql_query($query3,$conn);
											$num_rows = mysql_num_rows($result3);
											if ($num_rows > 0 ) {
												while ($row = mysql_fetch_array($result3)) 
												{
													$stMarks = $stMarks + $row["Marks"];
													//echo $stMarks."&nbsp;&nbsp;&nbsp;&nbsp;";
												}
											}
											$query6 = "select Percentage from tbclassexamsetup where ClassId='$ClassId' and SubjectId='$SubId' and ExamId='$ExamId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result6 = mysql_query($query6,$conn);
								$num_rows6 = mysql_num_rows($result6);
								if ($num_rows6 > 0 ) {
									while ($row6 = mysql_fetch_array($result6)) 
									{
										$TotalMarks = $TotalMarks + $row6["Percentage"];
									
									}
								}
											
								$query5 = "select MaxMark from tbexammarkssetup where ClassID='$ClassId' And ExamID='$ExamId' And SubjectID='$SubId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								         $result5 = mysql_query($query5,$conn);
								         $dbarray5 = mysql_fetch_array($result5);
								         $MaxPercent  = $dbarray5["MaxMark"];
										 $MaxPercent = 100;
								
									$arr_Class_Rank[$AdmnNo]= $stMarks;
									$arr_Total_Marks[$AdmnNo]= $TotalMarks;
									if( $stMarks == 0 ){
										     $stMarks = 0;
											 $AvgPercentage = 0;
										//$TotalMarks = 0;
									}else{
									$AvgPercentage = ($stMarks/$TotalMarks) * $MaxPercent;
									}
									$arr_Avg_Percentage[$AdmnNo]=  $AvgPercentage;
										
										}
									}
									
									
								}
							}
							
							
?>
		  <TR>
			<TD>
<?PHP
			if(isset($_GET['mod']))
			{
				$mod = $_GET['mod'];
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="exam.php?subpg=Examination%20Master">Examination</a> &gt; <a href="performance.php?subpg=Student Ranking">Student Performance</a> &gt; Student Ranking</strong></div>
<?PHP
			}else{
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="exam.php?subpg=Examination%20Master">Examination</a> &gt; <a href="performance.php?subpg=Student%20Performance">Student Performance</a> &gt; Student Ranking</strong></div>
<?PHP
			}
?>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				<table  width="90%" cellpadding="8" cellspacing="0" border="0">
				  <tbody>
				    <tr>
					  <td colspan="2" align="left"><div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Class Ranking</strong></div></td>
					</tr>
					<tr>
					  <td width="51%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><strong>Class Name : </strong><?PHP echo GetClassName($ClassId); ?> </td>
					  <td width="49%" align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" valign="top"><strong>Class Teacher </strong> <?PHP echo GET_EMP_NAME($Incharge); ?></td>
					</tr>
					<tr>
					  <td colspan="2" align="left">
					  <table  width="95%" align="center" cellpadding="5" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">
						  <tbody>
							<tr>
							  <td width="17%" bgcolor="#666666" align="center"><font color="#FFFFFF">Admn. No.</strong></font></td>
							  <td width="29%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student Name</strong></font></td>
							  <td width="17%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Average Score</strong></font></td>
							  <td width="24%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Ranking</strong></font></td>
							  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Grade</strong></font></td>
							</tr>
<?PHP							
							//Get Records	
							$counter1 = 1;
							$FinalMarks_array[$counter1-1] = 0;
							arsort($arr_Class_Rank);
									foreach ($arr_Class_Rank as $key => $val) {
										$AdmnNo = $key;
										 $FinalMarks = $val;
										 $FinalMarks_array[$counter1] = $FinalMarks;
										 if($FinalMarks==$FinalMarks_array[$counter1-1])
										 {
											 $StudentRanking = $StudentRanking1;
										 }else{
                                       $StudentRanking = $counter1;
									     $StudentRanking1 = $StudentRanking;
										 }
										 $counter1 = $counter1+1;
										//$StudentRanking = $counter1;
										
										
										if($StudentRanking==1){
												$StudentRanking = "1st";
											}elseif($StudentRanking==2){
												$StudentRanking = "2nd";
											}elseif($StudentRanking==3){
												$StudentRanking = "3rd";
											}elseif($StudentRanking!="-"){
												$StudentRanking = $StudentRanking."th";
											}
									$TotalMarks = $arr_Total_Marks[$AdmnNo];
									$AvgPercentage = $arr_Avg_Percentage[$AdmnNo];
									//$arr_Class_Rank_Value[$AdmnNo]= $StudentRanking;
									$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo = '$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								         $result = mysql_query($query,$conn);
								         $dbarray = mysql_fetch_array($result);
								         $StudName  = $dbarray["Stu_Full_Name"];
								

							
									
?>
								<tr>
								  <td width="17%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $AdmnNo; ?></td>
								  <td width="29%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $StudName; ?></td>
								  <td width="17%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo number_format($AvgPercentage,2).'%';  ?></td>
								  <td width="24%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $StudentRanking; ?></td>
								  <td width="13%" bgcolor="#ffffff" align="center" style="BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid;"><?PHP echo $GradeName; ?></td>
								</tr>
<?PHP
								}
							//}
?>
						</tbody>
						</table>
					  </td>
					</tr>
					<tr>
					  <td align="left" colspan="2">
					    <TABLE width="92%" align="center" cellpadding="7">
							<TBODY>
							<TR>
							  <TD width="11%"  align="left"><strong>Comment </strong></TD>
							  <TD width="89%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;"><p>&nbsp;</p>
						      <p>&nbsp;</p></TD>
							</TR>
							<TR>
							  <TD width="11%"  align="left">&nbsp;</TD>
							  <TD width="89%"  align="right" valign="top" ><p>________________________________</p>
							  <p style="margin-right:50px;">Authorised Signatory</p></TD>
							</TR>
							</TBODY>
						</TABLE></td>
					</tr>
			      </tbody>
			  </table>
			</TD>
		  </TR>
<?PHP
		}elseif ($Page == "Student Progress Report") {
			
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="exam.php?subpg=Examination%20Master">Examination</a> &gt; <a href="progress.php?subpg=Student%20Progress%20Report">Student Progress Report</a></strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Progress Report</strong></div>
				<table  width="90%" cellpadding="8" cellspacing="0" border="0">
				  <tbody>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Student Name : <?PHP echo $Stu_Name; ?> </strong></td>
					  <td align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Class Name : <?PHP echo GetClassName($SelStuClass); ?></strong> </td>
					</tr>		
					<tr>
					  <td colspan="2" align="left">&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2" align="left">
								<table  width="100%" align="center" cellpadding="5" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">
								  <tbody>
									<tr>
									  <td width="21%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Category</strong></strong></font></td>
									  <td width="30%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Sub category</strong></font></td>
									  <td width="30%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Skills</strong></font></td>
									  <td width="19%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Result</strong></font></td>
									</tr>
<?PHP
									$counter = 0;
									$CountBox = 0;
									$query = "select * from tbprogheader where class_id = '$SelStuClass' order by Description";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$counter = $counter+1;
											$Header_id = $row["ID"];
											$Description = $row["Description"];
											if($counter % 2 == 1){
												$bg="#F2F2F2";
											}else{
												$bg="#FFFFFF";
											}
?>
									 		<tr bgcolor="<?PHP echo $bg; ?>">
											<td valign="top"><div align="left"><?PHP echo $Description; ?></div></td>
											<td colspan="3">
												<table width="100%" border="0" cellpadding="3" cellspacing="3">
<?PHP					
												$query1 = "select * from tbsubheader where Class_id = '$SelStuClass' And Head_id = '$Header_id' order by Decription";
												$result1 = mysql_query($query1,$conn);
												$num_rows1 = mysql_num_rows($result1);
												if ($num_rows1 > 0 ) {
													while ($row1 = mysql_fetch_array($result1)) 
													{
														$Sub_id = $row1["ID"];
														$SubDesc = $row1["Decription"];
?>
														<tr>
														  <td width="38%" valign="top"><div align="left"><?PHP echo $SubDesc; ?></div></td>
														  <td width="62%" colspan="2" valign="top">
														  	<table width="100%" border="0" cellpadding="3" cellspacing="3">
<?PHP					
																$query2 = "select * from tbproskills where Head_id = '$Header_id' And SubHead_id = '$Sub_id' And Class_id = '$SelStuClass' order by Description";
																$result2 = mysql_query($query2,$conn);
																$num_rows2 = mysql_num_rows($result2);
																if ($num_rows2 > 0 ) {
																	while ($row2 = mysql_fetch_array($result2)) 
																	{
																		$CountBox = $CountBox+1;
																		$Skill_id = $row2["ID"];
																		$SkillDesc = $row2["Description"];
																		
																		$Result = "";
																		$query4 = "select Result from tbprogreport where AdmnNo = '$OptStudent' and Head_id ='$Header_id' and SubHead_id = '$Sub_id' and ProSkill_id = '$Skill_id' and Class_id ='$SelStuClass'";
																		$result4 = mysql_query($query4,$conn);
																		$dbarray4 = mysql_fetch_array($result4);
																		$Result  = $dbarray4['Result'];
				
?>
																		 <tr>
																	  	   <td width="62%" valign="top"><div align="left"><?PHP echo $SkillDesc; ?></div></td>
																		   <td width="38%" valign="top" align="center"><?PHP echo $Result; ?></td>
														                 </tr>
<?PHP
																	 }
																 }
?>
															</table>
														  </td>
														</tr>
<?PHP
													 }
												 }
?>
											</table>
										</td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					  </td>
					</tr>
			      </tbody>
			  </table>
			</TD>
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
