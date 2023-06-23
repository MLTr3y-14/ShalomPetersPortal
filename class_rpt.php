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
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
	}
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
		$audit=update_Monitory('Login','Administrator',$Page);
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
	
	if(isset($_GET['ty']))
	{
		$ischecked = $_GET['ty'];
		$OptClass = $_GET['cls'];
		if($OptClass != "")
		{
			$query2 = "select * from tbclassmaster where ID ='$OptClass' order by Class_Name";
		}else{
			$query2 = "select * from tbclassmaster order by Class_Name";
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
    <TD height="503" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  	<TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
<?PHP
		if ($Page == "Class charges") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="classreport.php?subpg=Class charges">Class charges</a> &gt; Class Charges Report</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Class Charges</strong></div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="80%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 80%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="178" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Class Name.</strong></font></td>
					  <td width="227" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Class Teacher</strong></font></td>
					  <td width="161" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Term</strong></font></td>
					  <td width="190" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Seating Capacity</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter = $counter+1;
							$ClassNo = $row2["ID"];
							$ClassName = $row2["Class_Name"];
							
							$query4 = "select Incharge,Seat_capacity from tbclasssection where ClassID='$ClassNo'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$EmpID  = $dbarray4['Incharge'];
							$Seating  = $dbarray4['Seat_capacity'];
							if($counter % 2 == 1){
								$bgs="#FFFFFF";
							}else{
								$bgs="#F2F2F2";
							}
?>
							  <tr bgcolor="<?PHP echo $bgs; ?>">
								<td width="178" align="center"><?PHP echo $ClassName; ?></td>
								<td width="227" align="center"><?PHP echo GET_EMP_NAME($EmpID); ?></td>
								<td width="161" align="center"><?PHP echo $Activeterm; ?></td>
								<td width="190" align="center"><?PHP echo $Seating; ?></td>
							  </tr>
							  <tr bgcolor="<?PHP echo $bgs; ?>">
							  	<td colspan="4">
								 <table width="50%" border="0" align="right" cellpadding="3" cellspacing="3">
								  <tr>
									  <td width="26" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>#</strong></font></td>
									  <td width="161" bgcolor="#999999" align="left"><font color="#FFFFFF"><strong>Charge Name</strong></font></td>
									  <td width="97" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Amount</strong></font></td>
								 </tr>
<?PHP
									$Count_Charge = 0;
									$query = "select ID,Amount,ChargeName from tbclasscharges where ClassID = '$ClassNo' order by ChargeName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$Count_Charge = $Count_Charge+1;
											$ChargeName = $row["ChargeName"];
											$Amount = $row["Amount"];
?>
											  <tr>
												<td width="26" align="center"><?PHP echo $Count_Charge; ?></td>
												<td width="161" align="left"><?PHP echo $ChargeName; ?></td>
												<td width="97" align="center"><?PHP echo $Amount; ?></td>
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
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Class Subject") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="classreport.php?subpg=Class Subject">Class Subject</a> &gt; Class Subject Report</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Class Subject</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="80%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 80%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="178" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Class Name.</strong></font></td>
					  <td width="227" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Class Teacher</strong></font></td>
					  <td width="161" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Term</strong></font></td>
					  <td width="190" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Seating Capacity</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter = $counter+1;
							$ClassNo = $row2["ID"];
							$ClassName = $row2["Class_Name"];
							
							$n=0;
							$y=0;
							$arrComp = "";
							$arrOpt = "";
							$CountSubject = 0;
							$query4 = "select SubjectId from tbclasssubjectrelation where ClassId='$ClassNo'";
							$result4 = mysql_query($query4,$conn);
							$num_rows4 = mysql_num_rows($result4);
							if ($num_rows4 > 0 ) {
								while ($row4 = mysql_fetch_array($result4)) 
								{
									$SubjectId = $row4["SubjectId"];
									$CountSubject = $CountSubject+1;
									$query3 = "select Sub_Type from tbsubjectmaster where ID='$SubjectId'";
									$result3 = mysql_query($query3,$conn);
									$dbarray3 = mysql_fetch_array($result3);	
									$Sub_Type  = $dbarray3['Sub_Type'];
									
									if($Sub_Type == 0){
										$arrComp[$n]=$SubjectId;
										$n=$n+1;
									}elseif($Sub_Type == 1){
										$arrOpt[$y]=$SubjectId;
										$y=$y+1;
									}
								}
							}
							
							$query4 = "select Incharge,Seat_capacity from tbclasssection where ClassID='$ClassNo'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$EmpID  = $dbarray4['Incharge'];
							$Seating  = $dbarray4['Seat_capacity'];
							if($counter % 2 == 1){
								$bgs="#FFFFFF";
							}else{
								$bgs="#F2F2F2";
							}
?>
							  <tr bgcolor="<?PHP echo $bgs; ?>">
								<td width="178" align="center"><?PHP echo $ClassName; ?></td>
								<td width="227" align="center"><?PHP echo GET_EMP_NAME($EmpID); ?></td>
								<td width="161" align="center"><?PHP echo $Activeterm; ?></td>
								<td width="190" align="center"><?PHP echo $Seating; ?></td>
							  </tr>
							  <tr bgcolor="<?PHP echo $bgs; ?>">
							  	<td colspan="4">
								 <table width="50%" border="0" align="right" cellpadding="3" cellspacing="3">
								  <tr>
									  <td width="147" bgcolor="#999999" align="left"><div align="center"><font color="#FFFFFF"><strong>Compulsory Subjects</strong></font></div></td>
									  <td width="146" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Optional Subjects</strong></font></td>
								 </tr>
<?PHP
									for($i=0; $i<$CountSubject; $i++){
										//echo $arrComp[$i]."<br>";
?>
									  <tr>
										<td width="147" align="center">
										<?PHP
											if(isset($arrComp[$i])){
												echo GetSubjectName($arrComp[$i]);
											}
										?></td>
										<td width="146" align="center">
										<?PHP
											if(isset($arrOpt[$i])){
												echo GetSubjectName($arrOpt[$i]);
											}
										?></td>
									  </tr>
<?PHP
									}
?>
								 </table>
							 	</td>
							</tr>
<?PHP
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Class student") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="classreport.php?subpg=Class student">Class Student</a> &gt; Class Student Report</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Class Student</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="80%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 80%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
<?PHP
					$counter = 0;
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter = $counter+1;
							$ClassNo = $row2["ID"];
							$ClassName = $row2["Class_Name"];
							
							$query4 = "select Incharge,Seat_capacity from tbclasssection where ClassID='$ClassNo'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$EmpID  = $dbarray4['Incharge'];
							if($counter % 2 == 1){
								$bgs="#FFFFFF";
							}else{
								$bgs="#F2F2F2";
							}
?>
							  <tr bgcolor="<?PHP echo $bgs; ?>">
								<td width="178" align="left"><strong>Class Name :</strong> <?PHP echo $ClassName; ?></td>
								<td width="227" align="left"><strong>Class Teacher : </strong> <?PHP echo GET_EMP_NAME($EmpID); ?></td>
							  </tr>
							  <tr bgcolor="<?PHP echo $bgs; ?>">
							  	<td colspan="2">
								 <table width="100%" border="0" align="right" cellpadding="3" cellspacing="3">
								  <tr>
									  <td width="91" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Admn. No.</strong></font></td>
									  <td width="75" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Reg. No.</strong></font></td>
									  <td width="142" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Student Name</strong></font></td>
									  <td width="101" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Term / Section</strong></font></td>
									  <td width="65" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Gender</strong></font></td>
									  <td width="87" bgcolor="#999999" align="center"><font color="#FFFFFF"><strong>Type</strong></font></td>
								 </tr>
<?PHP
									$Count_Charge = 0;
									
									$query = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,Stu_Gender,Stu_Type,AdmissionNo from tbstudentmaster where Stu_Class = '$ClassNo' order by Stu_Full_Name";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$Count_Charge = $Count_Charge+1;
											$RegNo = $row["Stu_Regist_No"];
											$StuSec = $row["Stu_Sec"];
											$StuName = $row["Stu_Full_Name"];
											$Gender = $row["Stu_Gender"];
											$StuType = $row["Stu_Type"];
											if($StuType == "Scholar"){
												$StuType = "Day Scholar";
											}
											$AdmissionNo = $row["AdmissionNo"];
?>
											  <tr>
												<td width="91" align="center"><?PHP echo $AdmissionNo; ?></td>
												<td width="75" align="center"><?PHP echo $RegNo; ?></td>
												<td width="142" align="left"><?PHP echo $StuName; ?></td>
												<td width="101" align="center"><?PHP echo $StuSec; ?></td>
												<td width="65" align="center"><?PHP echo $Gender; ?></td>
												<td width="87" align="center"><?PHP echo $StuType; ?></td>
											  </tr>
<?PHP
										 }
									 }
?>
								 </table>
							 	</td>
							</tr>
							<tr>
								<td width="178" align="left"><strong>&nbsp;</td>
								<td width="227" align="left"><strong>&nbsp;</td>
							  </tr>
<?PHP
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
