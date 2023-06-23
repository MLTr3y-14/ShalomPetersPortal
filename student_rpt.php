<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
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
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 20;
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
	
	//Get School Report Header
	$query = "select * from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$sName  = $dbarray['SchName'];
	$sAddress  = $dbarray['SchAddress'];
	$sState  = $dbarray['SchState'];
	$sCountry  = $dbarray['SchCountry'];
	$sPhone  = $dbarray['SchPhone'];
	$SchEmail1  = $dbarray['SchEmail1'];
	$sEmail2  = $dbarray['SchEmail2'];
	
	if(isset($_GET['src_stud']))
	{
		$isChkStudent = $_GET['src_stud'];
		if(isset($_GET['isAdm']))
		{
			$AdmisNo = $_GET['isAdm'];
		}
		if(isset($_GET['vw']))
		{
			$Optview = $_GET['vw'];
		}
	}
	if(isset($_GET['src_emp']))
	{
		$isChkEmp = $_GET['src_emp'];
		if(isset($_GET['isID']))
		{
			$EmpID = $_GET['isID'];
		}
	}
	if(isset($_GET['src_par']))
	{
		$isChkParent = $_GET['src_par'];
		if(isset($_GET['isAdm']))
		{
			$AdmisNo = $_GET['isAdm'];
		}
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

<?PHP
		if ($Page == "Student Details") {
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
<?PHP
				if($Optview=="new"){
?>
					<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="studreport.php?subpg=Student Details">Report</a> &gt; <a href="studreport.php?subpg=New Admission">New Admission</a> &gt; New Admission Details</strong></div>
					<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
					
					
					<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>New Admission</strong>s</div>
					</div>
<?PHP
				}else{
?>
					<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="studreport.php?subpg=Student Details">Student Details</a> &gt; Student Details</strong></div>
					<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
					
					
					<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Details</strong></div>
					</div>
<?PHP
				}
?>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="96" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Reg. No</strong></font></td>
					  <td width="219" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Admn. No</strong></font></td>
					  <td width="128" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student Name</strong></font></td>
					  <td width="145" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Term / Section</strong></font></td>
					  <td width="147" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Gender</strong></font></td>
					  <td width="81" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Type</strong></font></td>
					  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Contact</strong></font></td>
					</tr>
<?PHP
					if($isChkStudent=="Class"){
						if($AdmisNo==""){
							//By Selected Class Only
							$OptClass = $_GET['isClass'];
							
							$query4 = "select Incharge from tbclasssection where ClassID='$OptClass'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$EmpID  = $dbarray4['Incharge'];
							//GET_EMP_NAME($EmpID)
							
							$Display = "False";
							if($Optview=="new"){
								$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and OldStudent = '0' order by Stu_Full_Name";
							}else{
								$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' order by Stu_Full_Name";
							}
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							if ($num_rows2 > 0 ) {
								while ($row2 = mysql_fetch_array($result2)) 
								{
									$RegistNo = $row2["Stu_Regist_No"];
									$StuSec = $row2["Stu_Sec"];
									$AdmissionNo = $row2["AdmissionNo"];
									$StuFull_Name = $row2["Stu_Full_Name"];
									$StuGender = $row2["Stu_Gender"];
									$StuType = $row2["Stu_Type"];
									$StuPhone = $row2["Stu_Phone"];
									
									if($Display=="False"){
										$Display ="True";
?>
									  <tr>
										<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
									  </tr>
<?PHP
									}
?>
								  <tr>
									<td width="96" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegistNo; ?></a></td>
									<td width="219" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $AdmissionNo; ?></a></td>
									<td width="128" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
									<td width="145" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuSec; ?></a></td>
									<td width="147" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuGender; ?></a></td>
									<td width="81" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuType; ?></a></td>
									<td width="98" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuPhone; ?></a></td>
								  </tr>
<?PHP
								}
							}
						}else{
							//By Selected Admission No.
							$Display = "False";
							$ArrAdmnNo = explode (',',$AdmisNo);
							
							$OptClass = $_GET['isClass'];
							$Display = "False";
							$query4 = "select Incharge from tbclasssection where ClassID='$OptClass'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$EmpID  = $dbarray4['Incharge'];
							//GET_EMP_NAME($EmpID)
							$i=0;
							while(isset($ArrAdmnNo[$i])){
								if($Optview=="new"){
									$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and AdmissionNo = '$ArrAdmnNo[$i]' and OldStudent = '0' order by Stu_Full_Name";
								}else{
									$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and AdmissionNo = '$ArrAdmnNo[$i]' order by Stu_Full_Name";
								}
							
								
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$RegistNo = $row2["Stu_Regist_No"];
										$StuSec = $row2["Stu_Sec"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuFull_Name = $row2["Stu_Full_Name"];
										$StuGender = $row2["Stu_Gender"];
										$StuType = $row2["Stu_Type"];
										$StuPhone = $row2["Stu_Phone"];
										
										if($Display=="False"){
											$Display ="True";
?>
										  <tr>
											<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
										  </tr>
<?PHP
										}
?>
									  <tr>
										<td width="96" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegistNo; ?></a></td>
										<td width="219" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $AdmissionNo; ?></a></td>
										<td width="128" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
										<td width="145" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuSec; ?></a></td>
										<td width="147" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuGender; ?></a></td>
										<td width="81" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuType; ?></a></td>
										<td width="98" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuPhone; ?></a></td>
									  </tr>
<?PHP
									}
								}		
								$i=$i+1;
							}
						}
					}else{
						if($AdmisNo==""){
							//By Selected Class Only
							$query1 = "select * from tbclassmaster order by Class_Name";
							$result1 = mysql_query($query1,$conn);
							$num_rows1 = mysql_num_rows($result1);
							if ($num_rows1 > 0 ) {
								while ($row1 = mysql_fetch_array($result1)) 
								{
									$OptClass = $row1["ID"];
									
									$query4 = "select Incharge from tbclasssection where ClassID='$OptClass'";
									$result4 = mysql_query($query4,$conn);
									$dbarray4 = mysql_fetch_array($result4);
									$EmpID  = $dbarray4['Incharge'];
									//GET_EMP_NAME($EmpID)
							
									$Display = "False";
									if($Optview=="new"){
										$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and OldStudent = '0' order by Stu_Full_Name";
									}else{
										$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' order by Stu_Full_Name";
									}
									
									$result2 = mysql_query($query2,$conn);
									$num_rows2 = mysql_num_rows($result2);
									if ($num_rows2 > 0 ) {
										while ($row2 = mysql_fetch_array($result2)) 
										{
											$RegistNo = $row2["Stu_Regist_No"];
											$StuSec = $row2["Stu_Sec"];
											$AdmissionNo = $row2["AdmissionNo"];
											$StuFull_Name = $row2["Stu_Full_Name"];
											$StuGender = $row2["Stu_Gender"];
											$StuType = $row2["Stu_Type"];
											$StuPhone = $row2["Stu_Phone"];
											
											if($Display=="False"){
												$Display ="True";
?>
												  <tr>
													<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
												  </tr>
<?PHP
											}
?>
											  <tr>
												<td width="96" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegistNo; ?></a></td>
												<td width="219" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $AdmissionNo; ?></a></td>
												<td width="128" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
												<td width="145" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuSec; ?></a></td>
												<td width="147" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuGender; ?></a></td>
												<td width="81" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuType; ?></a></td>
												<td width="98" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuPhone; ?></a></td>
											  </tr>
<?PHP
										}
									}
								}
							}
						}else{
							//By Selected Admission No.
							$Display = "False";
							$ArrAdmnNo = explode (',',$AdmisNo);
							$query1 = "select * from tbclassmaster order by Class_Name";
							$result1 = mysql_query($query1,$conn);
							$num_rows1 = mysql_num_rows($result1);
							if ($num_rows1 > 0 ) {
								while ($row1 = mysql_fetch_array($result1)) 
								{
									$OptClass = $row1["ID"];
									$Display = "False";
									$query4 = "select Incharge from tbclasssection where ClassID='$OptClass' and ClassTerm = '$Activeterm'";
									$result4 = mysql_query($query4,$conn);
									$dbarray4 = mysql_fetch_array($result4);
									$EmpID  = $dbarray4['Incharge'];
									//GET_EMP_NAME($EmpID)
									$i=0;
									while(isset($ArrAdmnNo[$i])){
										if($Optview=="new"){
											$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and AdmissionNo = '$ArrAdmnNo[$i]' and OldStudent = '0' order by Stu_Full_Name";
										}else{
											$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and AdmissionNo = '$ArrAdmnNo[$i]' order by Stu_Full_Name";
										}
										$result2 = mysql_query($query2,$conn);
										$num_rows2 = mysql_num_rows($result2);
										if ($num_rows2 > 0 ) {
											while ($row2 = mysql_fetch_array($result2)) 
											{
												$RegistNo = $row2["Stu_Regist_No"];
												$StuSec = $row2["Stu_Sec"];
												$AdmissionNo = $row2["AdmissionNo"];
												$StuFull_Name = $row2["Stu_Full_Name"];
												$StuGender = $row2["Stu_Gender"];
												$StuType = $row2["Stu_Type"];
												$StuPhone = $row2["Stu_Phone"];
												
												if($Display=="False"){
													$Display ="True";
?>
													  <tr>
														<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
													  </tr>
<?PHP
												}
?>
											  <tr>
												<td width="96" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegistNo; ?></a></td>
												<td width="219" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $AdmissionNo; ?></a></td>
												<td width="128" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
												<td width="145" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuSec; ?></a></td>
												<td width="147" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuGender; ?></a></td>
												<td width="81" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuType; ?></a></td>
												<td width="98" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuPhone; ?></a></td>
											  </tr>
<?PHP
											}
										}		
										$i=$i+1;
									}
								}
							}
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "View Student Details") {
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Full Student Details</strong></div>
				</div>
				
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="90%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 90%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
<?PHP
					$AdmnNo = $_GET['Admn'];
					$query = "select * from tbstudentmaster where AdmissionNo='$AdmnNo'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$RegNo= $dbarray['Stu_Regist_No'];
					$RegDate= $dbarray['Stu_Date_of_Admis'];
					$StuClass  = $dbarray['Stu_Class'];
					$OptSection  = $dbarray['Stu_Sec'];
					$StudentName  = $dbarray['Stu_Full_Name'];
					$DOB = $dbarray['Stu_DOB'];
					$OptGender  = $dbarray['Stu_Gender'];
					$Tel_No  = $dbarray['Stu_Phone'];
					$StuEmail  = $dbarray['Stu_Email'];
					$Stutype  = $dbarray['Stu_Type'];
					$StuFilename  = $dbarray['Stu_Photo'];
					if($StuFilename ==""){
						$StuFilename  = "empty_r2_c2.jpg";
					}
					$AdmissionNo = $dbarray['AdmissionNo'];
					$LastSchool  = $dbarray['SchoolLeft'];
					$ReasonDOB  = $dbarray['ReasonDOB'];
					$TransportMode  = $dbarray['Mode_of_coming'];
					
					
					$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);		
					$Weight  = $dbarray['Stu_Wt'];
					$Height  = $dbarray['Stu_Ht'];
					$Age  = $dbarray['Stu_Age'];
					$CertNo  = $dbarray['Cert_No'];
					$SchoolLeavingDate = $dbarray['ScLv_Date'];
					$BloodGroup  = $dbarray['BloodGroup'];
					$ReligionName  = $dbarray['Religion'];
					$NickName  = $dbarray['NickName'];
					$PlaceofBirth  = $dbarray['PlaceofBirth'];
					$BedWelter  = $dbarray['BedWetter'];
					$Diseases  = $dbarray['CommunicableDisease'];
					$ExtraCurricular  = $dbarray['ExtraCurricular'];
					
					$query4 = "select Incharge from tbclasssection where ClassID='$StuClass' and ClassTerm = '$Activeterm'";
					$result4 = mysql_query($query4,$conn);
					$dbarray4 = mysql_fetch_array($result4);
					$EmpID  = $dbarray4['Incharge'];
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
						  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Place of Birth</strong></div></TD>
						  <TD width="22%" valign="top"  align="left"><?PHP echo $PlaceofBirth; ?></TD>
						  <TD width="23%" valign="top"  align="left"><strong>Nick Name : </strong></TD>
						  <TD width="31%" valign="top"  align="left"><?PHP echo $NickName; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Admitted Class</strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo GetClassName($StuClass); ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Class Teacher  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo GET_EMP_NAME($EmpID); ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Age</strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $Age; ?>
						Gender :  <?PHP echo $OptGender; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Date of Birth  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $DOB; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" height="29"  align="left" valign="top"><div align="right"><strong>Height : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $Height; ?>
						Tel No : <?PHP echo $Tel_No; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Reason if DOB not Known </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $ReasonDOB; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Weight : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $Weight; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Last School Attended  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $LastSchool; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Religion : </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $ReligionName; ?>						</TD>
					  <TD width="23%" valign="top"  align="left"><strong>School Leaving Date  : </strong></TD>
					  <TD width="31%" valign="top"  align="left"><?PHP echo $SchoolLeavingDate; ?>
					    Cert No. : <?PHP echo $CertNo; ?></TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Blood Group </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $BloodGroup; ?></TD>
					  <TD width="23%" valign="top"  align="left"><strong>Section: 
					      <?PHP echo $OptSection; ?></strong></TD>
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
						   <td><img src="images/uploads/<?PHP echo $StuFilename; ?>" width="178" height="175"></td>
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
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Email Address: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $StuEmail; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Student Type: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $Stutype; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Transport Mode: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $TransportMode; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Bed Welter: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $BedWelter; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Communicable Diseases: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $Diseases; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%" valign="top"  align="left"><div align="right"><strong>Extra Curricular Activities: </strong></div></TD>
					  <TD width="22%" valign="top"  align="left"><?PHP echo $ExtraCurricular; ?></TD>
					  <TD width="23%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					</TBODY>
					</TABLE>
					</td>
					</tr>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Registered Student") {
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="studreport.php?subpg=Student Details">Report</a> &gt; <a href="studreport.php?subpg=Registered Student">Registered Student</a> &gt; Registered Student Details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Registered Student</strong>s</div>
				</div>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="192" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student Name</strong></font></td>
					  <td width="125" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Date of Reg.</strong></font></td>
					  <td width="108" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Reg. No</strong></font></td>
					  <td width="49" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Gender</strong></font></td>
					  <td width="126" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Contact</strong></font></td>
					  <td width="78" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Status</strong></font></td>
					  <td width="112" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Father Name</strong></font></td>
					  <td width="114" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Monther Name</strong></font></td>
					</tr>
<?PHP
					if($isChkStudent=="Class"){
						if($AdmisNo==""){
							//By Selected Class Only
							$OptClass = $_GET['isClass'];
							
							$query4 = "select Incharge from tbclasssection where ClassID='$OptClass'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$EmpID  = $dbarray4['Incharge'];
							
							$Display = "False";
							$query2 = "select * from tbregistration where Stu_ClassID = '$OptClass' order by Stu_LastName";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							if ($num_rows2 > 0 ) {
								while ($row2 = mysql_fetch_array($result2)) 
								{
									$RegID = $row2["ID"];
									$StuFull_Name = $row2["Stu_LastName"]." ".$row2["Stu_MidName"]." ".$row2["Stu_FirstName"];
									$RegDate = $row2["Stu_DateRegis"];
									$Stu_Gender = $row2["Stu_Gender"];
									$Stu_Phone = $row2["Stu_Phone"];
									$Status = $row2["Status"];
									$Stu_Father = $row2["Stu_Father"];
									$Stu_Mother = $row2["Stu_Mother"];
									if($Status==0){
										$Status = "Un-Admitted";
										$bgColor="#FFBF80";
									}else{
										$Status = "Admitted";
										$bgColor="#cccccc";
									}
									
									$query4 = "select AdmissionNo from tbstudentmaster where Stu_Regist_No='$RegID'";
									$result4 = mysql_query($query4,$conn);
									$dbarray4 = mysql_fetch_array($result4);
									$AdmissionNo  = $dbarray4['AdmissionNo'];
							
							
									if($Display=="False"){
										$Display ="True";
?>
									  <tr>
										<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
									  </tr>
<?PHP
									}
?>
								  <tr <?PHP echo $bgColor; ?>>
									<td width="192" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
									<td width="125" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $RegDate; ?></a></td>
									<td width="108" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegID; ?></a></td>
									<td width="49" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Gender; ?></a></td>
									<td width="126" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Phone; ?></a></td>
									<td width="78" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Status; ?></a></td>
									<td width="112" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Father; ?></a></td>
									<td width="114" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Mother; ?></a></td>
								  </tr>
<?PHP
								}
							}
						}else{
							//By Selected Admission No.
							$Display = "False";
							$ArrAdmnNo = explode (',',$AdmisNo);
							
							$OptClass = $_GET['isClass'];
							$Display = "False";
							$query4 = "select Incharge from tbclasssection where ClassID='$OptClass' and ClassTerm = '$Activeterm'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$EmpID  = $dbarray4['Incharge'];
							//GET_EMP_NAME($EmpID)
							$i=0;
							while(isset($ArrAdmnNo[$i])){
								$query2 = "select * from tbregistration where Stu_ClassID = '$OptClass' and ID='$ArrAdmnNo[$i]' order by Stu_LastName";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$RegID = $row2["ID"];
										$StuFull_Name = $row2["Stu_LastName"]." ".$row2["Stu_MidName"]." ".$row2["Stu_FirstName"];
										$RegDate = $row2["Stu_DateRegis"];
										$Stu_Gender = $row2["Stu_Gender"];
										$Stu_Phone = $row2["Stu_Phone"];
										$Status = $row2["Status"];
										$Stu_Father = $row2["Stu_Father"];
										$Stu_Mother = $row2["Stu_Mother"];
										if($Status==0){
											$Status = "Un-Admitted";
											$bgColor="#FFBF80";
										}else{
											$Status = "Admitted";
											$bgColor="#cccccc";
										}
										
										$query4 = "select AdmissionNo from tbstudentmaster where Stu_Regist_No='$RegID'";
										$result4 = mysql_query($query4,$conn);
										$dbarray4 = mysql_fetch_array($result4);
										$AdmissionNo  = $dbarray4['AdmissionNo'];
								
								
										if($Display=="False"){
											$Display ="True";
?>
										  <tr>
											<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
										  </tr>
<?PHP
										}
?>
									  <tr <?PHP echo $bgColor; ?>>
										<td width="192" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
										<td width="125" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $RegDate; ?></a></td>
										<td width="108" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegID; ?></a></td>
										<td width="49" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Gender; ?></a></td>
										<td width="126" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Phone; ?></a></td>
										<td width="78" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Status; ?></a></td>
										<td width="112" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Father; ?></a></td>
										<td width="114" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Mother; ?></a></td>
									  </tr>
<?PHP
									}
								}	
								$i=$i+1;
							}
						}
					}else{
						if($AdmisNo==""){
							//By Selected Class Only
							$query1 = "select * from tbclassmaster order by Class_Name";
							$result1 = mysql_query($query1,$conn);
							$num_rows1 = mysql_num_rows($result1);
							if ($num_rows1 > 0 ) {
								while ($row1 = mysql_fetch_array($result1)) 
								{
									$OptClass = $row1["ID"];
									
									$query4 = "select Incharge from tbclasssection where ClassID='$OptClass'";
									$result4 = mysql_query($query4,$conn);
									$dbarray4 = mysql_fetch_array($result4);
									$EmpID  = $dbarray4['Incharge'];
									
									$Display = "False";
									$query2 = "select * from tbregistration where Stu_ClassID = '$OptClass' order by Stu_LastName";
									$result2 = mysql_query($query2,$conn);
									$num_rows2 = mysql_num_rows($result2);
									if ($num_rows2 > 0 ) {
										while ($row2 = mysql_fetch_array($result2)) 
										{
											$RegID = $row2["ID"];
											$StuFull_Name = $row2["Stu_LastName"]." ".$row2["Stu_MidName"]." ".$row2["Stu_FirstName"];
											$RegDate = $row2["Stu_DateRegis"];
											$Stu_Gender = $row2["Stu_Gender"];
											$Stu_Phone = $row2["Stu_Phone"];
											$Status = $row2["Status"];
											$Stu_Father = $row2["Stu_Father"];
											$Stu_Mother = $row2["Stu_Mother"];
											if($Status==0){
												$Status = "Un-Admitted";
												$bgColor="#FFBF80";
											}else{
												$Status = "Admitted";
												$bgColor="#cccccc";
											}
											
											$query4 = "select AdmissionNo from tbstudentmaster where Stu_Regist_No='$RegID'";
											$result4 = mysql_query($query4,$conn);
											$dbarray4 = mysql_fetch_array($result4);
											$AdmissionNo  = $dbarray4['AdmissionNo'];
									
									
											if($Display=="False"){
												$Display ="True";
?>
												  <tr>
													<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
												  </tr>
<?PHP
											}
?>
										  <tr <?PHP echo $bgColor; ?>>
											<td width="192" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
											<td width="125" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $RegDate; ?></a></td>
											<td width="108" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegID; ?></a></td>
											<td width="49" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Gender; ?></a></td>
											<td width="126" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Phone; ?></a></td>
											<td width="78" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Status; ?></a></td>
											<td width="112" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Father; ?></a></td>
											<td width="114" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Mother; ?></a></td>
										  </tr>
<?PHP
										}
									}
								}
							}
						}else{
							//By Selected Admission No.
							$Display = "False";
							$ArrAdmnNo = explode (',',$AdmisNo);
							$query1 = "select * from tbclassmaster order by Class_Name";
							$result1 = mysql_query($query1,$conn);
							$num_rows1 = mysql_num_rows($result1);
							if ($num_rows1 > 0 ) {
								while ($row1 = mysql_fetch_array($result1)) 
								{
									$OptClass = $row1["ID"];
									$Display = "False";
									$query4 = "select Incharge from tbclasssection where ClassID='$OptClass' and ClassTerm = '$Activeterm'";
									$result4 = mysql_query($query4,$conn);
									$dbarray4 = mysql_fetch_array($result4);
									$EmpID  = $dbarray4['Incharge'];
									//GET_EMP_NAME($EmpID)
									$i=0;
									while(isset($ArrAdmnNo[$i])){
										if($Optview=="new"){
											$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and AdmissionNo = '$ArrAdmnNo[$i]' and OldStudent = '0' order by Stu_Full_Name";
										}else{
											$query2 = "select * from tbstudentmaster where Stu_Class = '$OptClass' and AdmissionNo = '$ArrAdmnNo[$i]' order by Stu_Full_Name";
										}
										$result2 = mysql_query($query2,$conn);
										$num_rows2 = mysql_num_rows($result2);
										if ($num_rows2 > 0 ) {
											while ($row2 = mysql_fetch_array($result2)) 
											{
												$RegistNo = $row2["Stu_Regist_No"];
												$StuSec = $row2["Stu_Sec"];
												$AdmissionNo = $row2["AdmissionNo"];
												$StuFull_Name = $row2["Stu_Full_Name"];
												$StuGender = $row2["Stu_Gender"];
												$StuType = $row2["Stu_Type"];
												$StuPhone = $row2["Stu_Phone"];
												
												if($Display=="False"){
													$Display ="True";
?>
													  <tr>
														<td colspan="7"><p style="margin-left:10px;"><strong>Class : </strong> <?PHP echo GetClassName($OptClass); ?>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class Teacher: </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></p></td>
													  </tr>
<?PHP
												}
?>
											  <tr>
												<td width="192" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $RegistNo; ?></a></td>
												<td width="125" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $AdmissionNo; ?></a></td>
												<td width="108" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuFull_Name; ?></a></td>
												<td width="49" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuSec; ?></a></td>
												<td width="126" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuGender; ?></a></td>
												<td width="78" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuType; ?></a></td>
												<td width="112" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $StuPhone; ?></a></td>
											  </tr>
<?PHP
											}
										}		
										$i=$i+1;
									}
								}
							}
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Parent Details") {
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="studreport.php?subpg=Student Details">Report</a> &gt; <a href="studreport.php?subpg=Parent Details">Parent Details</a> &gt; Parent Full Details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Parent Details</strong></div>
				</div>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="192" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Parent Name</strong></font></td>
					  <td width="125" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student Name</strong></font></td>
					  <td width="108" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Contact No.</strong></font></td>
					  <td width="49" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Email</strong></font></td>
					  <td width="126" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Occupation</strong></font></td>
					  <td width="78" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Emergency No.</strong></font></td>
					  <td width="112" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Emergency Name</strong></font></td>
					</tr>
<?PHP
					if($isChkParent=="Admission"){
						//By Selected Admission No.
						
						$query4 = "select Stu_Regist_No from tbstudentmaster where AdmissionNo='$AdmisNo'";
						$result4 = mysql_query($query4,$conn);
						$dbarray4 = mysql_fetch_array($result4);
						$RegID  = $dbarray4['Stu_Regist_No'];
								
						$query2 = "select * from tbstudentdetail where Stu_Regist_No = '$RegID'";
						$result2 = mysql_query($query2,$conn);
						$num_rows2 = mysql_num_rows($result2);
						if ($num_rows2 > 0 ) {
							while ($row2 = mysql_fetch_array($result2)) 
							{
								$Gr_Name_Mr = $row2["Gr_Name_Mr"];
								$Gr_Name_Ms = $row2["Gr_Name_Ms"];
								$Gr_Ph = $row2["Gr_Ph"];
								$Per_Occp = $row2["Per_Occp"];
								$Religion = $row2["Religion"];
								$EmergName = $row2["EmergName"];
								$EmergNO = $row2["EmergNO"];
		
								$query = "select * from tbstudentmaster where Stu_Regist_No='$RegID'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);	
								$StudentName  = $dbarray['Stu_Full_Name'];
								$StuEmail  = $dbarray['Stu_Email'];
								$AdmissionNo  = $dbarray['AdmissionNo'];
?>
							  <tr>
								<td width="192" align="center">Father : <?PHP echo $Gr_Name_Mr; ?><br>Mother : <?PHP echo $Gr_Name_Ms; ?></td>
								<td width="125" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $StudentName; ?></a></td>
								<td width="108" align="center"><?PHP echo $Gr_Ph; ?></td>
								<td width="49" align="center"><?PHP echo $StuEmail; ?></td>
								<td width="126" align="center"><?PHP echo $Per_Occp; ?></td>
								<td width="112" align="center"><?PHP echo $EmergName; ?></td>
								<td width="114" align="center"><?PHP echo $EmergNO; ?></td>
							  </tr>
<?PHP
							}
						}	
					}else{
						//By Selected Admission No.
						$query2 = "select * from tbstudentdetail";
						$result2 = mysql_query($query2,$conn);
						$num_rows2 = mysql_num_rows($result2);
						if ($num_rows2 > 0 ) {
							while ($row2 = mysql_fetch_array($result2)) 
							{
								$RegID = $row2["Stu_Regist_No"];
								$Gr_Name_Mr = $row2["Gr_Name_Mr"];
								$Gr_Name_Ms = $row2["Gr_Name_Ms"];
								$Gr_Ph = $row2["Gr_Ph"];
								$Per_Occp = $row2["Per_Occp"];
								$Religion = $row2["Religion"];
								$EmergName = $row2["EmergName"];
								$EmergNO = $row2["EmergNO"];
		
								$query = "select * from tbstudentmaster where Stu_Regist_No='$RegID'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);	
								$StudentName  = $dbarray['Stu_Full_Name'];
								$StuEmail  = $dbarray['Stu_Email'];
								$AdmissionNo  = $dbarray['AdmissionNo'];
?>
							  <tr>
								<td width="192" align="center">Father : <?PHP echo $Gr_Name_Mr; ?><br>Mother : <?PHP echo $Gr_Name_Ms; ?></td>
								<td width="125" align="center"><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>"target="_blank"><?PHP echo $StudentName; ?></a></td>
								<td width="108" align="center"><?PHP echo $Gr_Ph; ?></td>
								<td width="49" align="center"><?PHP echo $StuEmail; ?></td>
								<td width="126" align="center"><?PHP echo $Per_Occp; ?></td>
								<td width="112" align="center"><?PHP echo $EmergName; ?></td>
								<td width="114" align="center"><?PHP echo $EmergNO; ?></td>
							  </tr>
<?PHP
							}
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
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
