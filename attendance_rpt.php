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
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
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
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
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
	
	if(!$_GET['cid'] == "" and !$_GET['frdt'] == "" )
	{
		$OptClass = $_GET['cid'];
		$fromDate = $_GET['frdt'];
		$toDate = $_GET['todt'];
		$ViewType = "viewA";
	}
	if(!$_GET['cid'] == "" and !$_GET['frdt'] == "" and !$_GET['studname'] == "")
	{
		$OptClass = $_GET['cid'];
		$fromDate = $_GET['frdt'];
		$toDate = $_GET['todt'];
		$ViewType = "viewB";
		$StudName = $_GET['studname'];
	}
	if(!$_GET['cid'] == "" and !$_GET['specificdt'] == "")
	{
		$OptClass = $_GET['cid'];
		//$fromDate = $_GET['frdt'];
		$specificDate = $_GET['specificdt'];
		$ViewType = "viewC";
	}
	if(!$_GET['cid'] == "" and !$_GET['specificdt'] == "" and !$_GET['studname'] == "")
	{
		$OptClass = $_GET['cid'];
		//$fromDate = $_GET['frdt'];
		$specificDate = $_GET['specificdt'];
		$ViewType = "viewD";
		$StudName = $_GET['studname'];
	}
	
   if(!$_GET['did'] == "" and !$_GET['frdt'] == "" )
	{
		$OptDepartment = $_GET['did'];
		$fromDate = $_GET['frdt'];
		$toDate = $_GET['todt'];
		$ViewType = "viewA";
	}
	if(!$_GET['did'] == "" and !$_GET['frdt'] == "" and !$_GET['employeename'] == "")
	{
		$OptDepartment = $_GET['did'];
		$fromDate = $_GET['frdt'];
		$toDate = $_GET['todt'];
		$ViewType = "viewB";
		$employeeName = $_GET['employeename'];
	}
	if(!$_GET['did'] == "" and !$_GET['specificdt'] == "")
	{
		$OptDepartment = $_GET['did'];
		//$fromDate = $_GET['frdt'];
		$specificDate = $_GET['specificdt'];
		$ViewType = "viewC";
	}
	if(!$_GET['did'] == "" and !$_GET['specificdt'] == "" and !$_GET['employeename'] == "")
	{
		$OptDepartment = $_GET['did'];
		//$fromDate = $_GET['frdt'];
		$specificDate = $_GET['specificdt'];
		$ViewType = "viewD";
		$employeeName = $_GET['employeename'];
	}
	if(isset($_GET['mth']))
	{
		$Method = $_GET['mth'];
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
.style1 {color: #FFFFFF}
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
<?PHP
		if ($Page == "Student attendance") {
			$CountSMS = 0;
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="attreport.php?subpg=Student attendance details">Attendance</a> &gt; Student Attendance Details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Attendance Details For <?PHP echo $OptClass; ?> </strong></div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><div align="center">
				  <input type="submit" name="SubmitSAtt" value="P" style="background:#F2F2F2"> 
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L" style=" background:#FFCC33">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L.5"  style=" background:#66FFCC">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A" style=" background:#FF9C97">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A.5"  style=" background:#CCFF00">
				</div></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					
<?PHP
						$counter_Stud = 0;
						
						if($ViewType =='viewA')
						{ ?>
							<tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Admn No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Student Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>From Att. Date </strong></div></td>
                        <td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>To Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Total Present </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Total Absent</strong></div></td>
				    </tr>
						<?PHP	$query = "select ID from tbclassmaster where Class_Name = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$OptClass2  = $dbarray['ID'];
							$query3 = "select Stu_Regist_No,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class = '$OptClass2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
					$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter_Stud = $counter_Stud+1;
								$counter = $counter+1;
								$RegNo  = $dbarray['Stu_Regist_No'];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
								
								$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Contact  = $dbarray['Gr_Ph'];
								
								
								$query = "select Status from tbattendancestudent where AdmnNo='$AdmissionNo' and Att_date = '$specificDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Status  = $dbarray['Status'];
								if($ViewType !='Date'){
								$attDate  = $dbarray['Att_date'];
								}
								
								$bg = "";
								if($Status == ""){
									$Status = "-";
								}elseif($Status =="P"){
									 $bg = "bgcolor='#F2F2F2'";
								}elseif($Status =="L"){
									 $bg = "bgcolor='#FFCC33'";
								}elseif($Status =="L.5"){
									 $bg = "bgcolor='#66FFCC'";
								}elseif($Status =="A"){
									 $bg = "bgcolor='#FF9C97'";
								}elseif($Status =="A.5"){
									 $bg = "bgcolor='#CCFF00'";
								}
								
								if($Method == "SMS"){
									$isSend_Status="False";
									//echo $AdmissionNo.",".User_date($attDate).",".GetClassName($OptClass).",".$Stu_Full_Name.",".$Status.",".GetTotalAtt($AdmissionNo).",".$Contact."<br>";
									//$isSend_Status = send_StudAtt($AdmissionNo,$attDate,$OptClass,$Stu_Full_Name,$Status,GetTotalAtt($AdmissionNo),$Contact);
									if($isSend_Status == "False"){
										$CountSMS = $CountSMS;
									}elseif($isSend_Status == "True"){
										$CountSMS = $CountSMS + 1;
									}	
								}
?>								  
								  <tr <?PHP echo $bg; ?>>
									<td>
									   <div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $counter_Stud; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $AdmissionNo; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo Long_date($fromDate); ?></a></div></td>
                                    <td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo Long_date($toDate); ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo GetTotalAttPresent($AdmissionNo,$fromDate,$toDate); ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo GetTotalAttAbsent($AdmissionNo,$fromDate,$toDate); ?></a></div></td>
								  </tr>
<?PHP
							}
						}
						}elseif($ViewType =='viewB'){
							?>
                            <tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Admn No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Student Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>From Att. Date </strong></div></td>
                        <td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>To Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Total Present </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Total Absent</strong></div></td>
				    </tr>
                    <?PHP
							
							$query3 = "select Stu_Regist_No,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Full_Name ='$StudName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter_Stud = $counter_Stud+1;
								$counter = $counter+1;
								$RegNo  = $dbarray['Stu_Regist_No'];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
								
								$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Contact  = $dbarray['Gr_Ph'];
								
								
								$query = "select Status from tbattendancestudent where AdmnNo='$AdmissionNo' and Att_date = '$specificDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Status  = $dbarray['Status'];
								//if($ViewType !='Date'){
								//$attDate  = $dbarray['Att_date'];
								//}
								
								$bg = "";
								if($Status == ""){
									$Status = "-";
								}elseif($Status =="P"){
									 $bg = "bgcolor='#F2F2F2'";
								}elseif($Status =="L"){
									 $bg = "bgcolor='#FFCC33'";
								}elseif($Status =="L.5"){
									 $bg = "bgcolor='#66FFCC'";
								}elseif($Status =="A"){
									 $bg = "bgcolor='#FF9C97'";
								}elseif($Status =="A.5"){
									 $bg = "bgcolor='#CCFF00'";
								}
								
								if($Method == "SMS"){
									$isSend_Status="False";
									//echo $AdmissionNo.",".User_date($attDate).",".GetClassName($OptClass).",".$Stu_Full_Name.",".$Status.",".GetTotalAtt($AdmissionNo).",".$Contact."<br>";
									//$isSend_Status = send_StudAtt($AdmissionNo,$attDate,$OptClass,$Stu_Full_Name,$Status,GetTotalAtt($AdmissionNo),$Contact);
									if($isSend_Status == "False"){
										$CountSMS = $CountSMS;
									}elseif($isSend_Status == "True"){
										$CountSMS = $CountSMS + 1;
									}
								}
							?>								  
								  <tr <?PHP echo $bg; ?>>
									<td>
									   <div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $counter_Stud; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $AdmissionNo; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo Long_date($fromDate); ?></a></div></td>
                                    <td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo Long_date($toDate); ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo GetTotalAttPresent($AdmissionNo,$fromDate,$toDate); ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo GetTotalAttAbsent($AdmissionNo,$fromDate,$toDate); ?></a></div></td>
								  </tr>
<?PHP
							}
						  }
						//}
		
							
						}elseif($ViewType =='viewC'){
							?>
                            <tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Admn No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Student Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Status </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Term Total Present</strong></div></td>
				    </tr>
                      <?PHP 
							$query = "select ID from tbclassmaster where Class_Name = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$OptClass2  = $dbarray["ID"];
							$query3 = "select Stu_Regist_No,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class = '$OptClass2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
							$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter_Stud = $counter_Stud+1;
								$counter = $counter+1;
								$RegNo  = $dbarray['Stu_Regist_No'];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
								
								$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Contact  = $dbarray['Gr_Ph'];
								
								
								$query = "select Status from tbattendancestudent where AdmnNo='$AdmissionNo' and Att_date = '$specificDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Status  = $dbarray['Status'];
								if($ViewType !='Date'){
								$attDate  = $dbarray['Att_date'];
								}
								
								$bg = "";
								if($Status == ""){
									$Status = "-";
								}elseif($Status =="P"){
									 $bg = "bgcolor='#F2F2F2'";
								}elseif($Status =="L"){
									 $bg = "bgcolor='#FFCC33'";
								}elseif($Status =="L.5"){
									 $bg = "bgcolor='#66FFCC'";
								}elseif($Status =="A"){
									 $bg = "bgcolor='#FF9C97'";
								}elseif($Status =="A.5"){
									 $bg = "bgcolor='#CCFF00'";
								}
								
								if($Method == "SMS"){
									$isSend_Status="False";
									//echo $AdmissionNo.",".User_date($attDate).",".GetClassName($OptClass).",".$Stu_Full_Name.",".$Status.",".GetTotalAtt($AdmissionNo).",".$Contact."<br>";
									//$isSend_Status = send_StudAtt($AdmissionNo,$attDate,$OptClass,$Stu_Full_Name,$Status,GetTotalAtt($AdmissionNo),$Contact);
									if($isSend_Status == "False"){
										$CountSMS = $CountSMS;
									}elseif($isSend_Status == "True"){
										$CountSMS = $CountSMS + 1;
									}	
								}
?>								  
								  <tr <?PHP echo $bg; ?>>
									<td>
									   <div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $counter_Stud; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $AdmissionNo; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo Long_date($specificDate); ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Status; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo GetTotalAtt($AdmissionNo); ?></a></div></td>
								  </tr>
<?PHP
							}
						}
						}elseif($ViewType =='viewD'){
							?>
                            <tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Admn No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Student Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Status </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Term Total Present</strong></div></td>
				    </tr>
						<?PHP	$query3 = "select Stu_Regist_No,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Full_Name ='$StudName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						
						
						$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter_Stud = $counter_Stud+1;
								$counter = $counter+1;
								$RegNo  = $dbarray['Stu_Regist_No'];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
								
								$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Contact  = $dbarray['Gr_Ph'];
								
								
								$query = "select Status from tbattendancestudent where AdmnNo='$AdmissionNo' and Att_date = '$specificDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Status  = $dbarray['Status'];
								//if($ViewType !='Date'){
								//$attDate  = $dbarray['Att_date'];
								//}
								
								$bg = "";
								if($Status == ""){
									$Status = "-";
								}elseif($Status =="P"){
									 $bg = "bgcolor='#F2F2F2'";
								}elseif($Status =="L"){
									 $bg = "bgcolor='#FFCC33'";
								}elseif($Status =="L.5"){
									 $bg = "bgcolor='#66FFCC'";
								}elseif($Status =="A"){
									 $bg = "bgcolor='#FF9C97'";
								}elseif($Status =="A.5"){
									 $bg = "bgcolor='#CCFF00'";
								}
								
								if($Method == "SMS"){
									$isSend_Status="False";
									//echo $AdmissionNo.",".User_date($attDate).",".GetClassName($OptClass).",".$Stu_Full_Name.",".$Status.",".GetTotalAtt($AdmissionNo).",".$Contact."<br>";
									//$isSend_Status = send_StudAtt($AdmissionNo,$attDate,$OptClass,$Stu_Full_Name,$Status,GetTotalAtt($AdmissionNo),$Contact);
									if($isSend_Status == "False"){
										$CountSMS = $CountSMS;
									}elseif($isSend_Status == "True"){
										$CountSMS = $CountSMS + 1;
									}	
								}
?>								  
								  <tr <?PHP echo $bg; ?>>
									<td>
									   <div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $counter_Stud; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $AdmissionNo; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo Long_date($specificDate); ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Status; ?></a></div></td>
									<td><div align="center"><a href="attendance_rpt.php?pg=Attendance Details&adm=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo GetTotalAtt($AdmissionNo); ?></a></div></td>
								  </tr>
<?PHP
							}
						}
						}
						if($Method == "SMS"){
							//echo "<meta http-equiv=\"Refresh\" content=\"0;url=attreport.php?subpg=Student attendance details&totsent=$CountSMS\">";
							//exit;
						}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Attendance Details") {
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="attreport.php?subpg=Student attendance details">Attendance</a> &gt; Student Attendance Details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Attendance Details</strong></div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><div align="center">
				  <input type="submit" name="SubmitSAtt" value="P" style="background:#F2F2F2"> 
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L" style=" background:#FFCC33">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L.5"  style=" background:#66FFCC">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A" style=" background:#FF9C97">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A.5"  style=" background:#CCFF00">
				</div></div>
				<table  width="60%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 60%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="107" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Att. Date </strong></div></td>
						<td width="66" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Status </strong></div></td>
					  <td width="150" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Full Description</strong></div></td>
				    </tr>
<?PHP
						$AdmissionNo = $_GET['adm'];
						$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$Stu_Full_Name = $dbarray['Stu_Full_Name'];
?>
						  <tr>
							<td colspan="3"><p style="margin-left:10px;"><strong><?PHP echo $Stu_Full_Name; ?></strong></p></td>
						  </tr>
<?PHP
						$query3 = "select Att_date,Status from tbattendancestudent where AdmnNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Employee Attendance") {
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="attreport.php?subpg=Employee Attendance details">Attendance</a> &gt; Employee Attendance</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Employee Attendance details</strong></div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><div align="center">
				  <input type="submit" name="SubmitSAtt" value="P" style="background:#F2F2F2"> 
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L" style=" background:#FFCC33">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L.5"  style=" background:#66FFCC">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A" style=" background:#FF9C97">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A.5"  style=" background:#CCFF00">
				</div></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
			<?PHP
						$counter_Stud = 0;
						
						if($ViewType =='viewA')
						{ ?>
							<tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Emp No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Employee Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>From Att. Date </strong></div></td>
                        <td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>To Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Total Present </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Total Absent</strong></div></td>
				    </tr>
						<?PHP	
					$query2 = "select ID,EmpName from tbemployeemasters where EmpDept ='$OptDepartment' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName";
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0){
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter_Emp = $counter_Emp+1;
							$EmpNo = $row2["ID"];
							$EmpName = $row2["EmpName"];
							
							//$query = "select Status from tbattendanceemployee where EmpId='$EmpNo' and Term = '$Activeterm' and Att_date='$attDate'";
							//$result = mysql_query($query,$conn);
							//$dbarray = mysql_fetch_array($result);
							//$Status  = $dbarray['Status'];
							$bg = "";
							if($Status == ""){
								$Status = "-";
							}elseif($Status =="P"){
								 $bgs = "bgcolor='#F2F2F2'";
							}elseif($Status =="L"){
								 $bgs = "bgcolor='#FFCC33'";
							}elseif($Status =="L.5"){
								 $bgs = "bgcolor='#66FFCC'";
							}elseif($Status =="A"){
								 $bgs = "bgcolor='#FF9C97'";
							}elseif($Status =="A.5"){
								 $bgs = "bgcolor='#CCFF00'";
							}
?>								  
						  <tr <?PHP echo $bgs; ?>>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $counter_Emp; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpNo; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpName; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo Long_date($fromDate); ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo Long_date($toDate); ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo GetTotalAttPresent2($EmpNo,$fromDate,$toDate); ?></a></div></td>
                            <td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo GetTotalAttAbsent2($EmpNo,$fromDate,$toDate); ?></a></div></td>
						  </tr>
<?PHP
						}
					}

						}elseif($ViewType =='viewB'){
							?>
                           <tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Emp No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Employee Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>From Att. Date </strong></div></td>
                        <td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong>To Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Total Present </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Total Absent</strong></div></td>
				    </tr>
						<?PHP	
					$query2 = "select ID,EmpName from tbemployeemasters where EmpName = '$employeeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0){
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter_Emp = $counter_Emp+1;
							$EmpNo = $row2["ID"];
							$EmpName = $row2["EmpName"];
							
							//$query = "select Status from tbattendanceemployee where EmpId='$EmpNo' and Term = '$Activeterm' and Att_date='$attDate'";
							//$result = mysql_query($query,$conn);
							//$dbarray = mysql_fetch_array($result);
							//$Status  = $dbarray['Status'];
							$bg = "";
							if($Status == ""){
								$Status = "-";
							}elseif($Status =="P"){
								 $bgs = "bgcolor='#F2F2F2'";
							}elseif($Status =="L"){
								 $bgs = "bgcolor='#FFCC33'";
							}elseif($Status =="L.5"){
								 $bgs = "bgcolor='#66FFCC'";
							}elseif($Status =="A"){
								 $bgs = "bgcolor='#FF9C97'";
							}elseif($Status =="A.5"){
								 $bgs = "bgcolor='#CCFF00'";
							}
?>								  
						  <tr <?PHP echo $bgs; ?>>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $counter_Emp; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpNo; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpName; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo Long_date($fromDate); ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo Long_date($toDate); ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo GetTotalAttPresent2($EmpNo,$fromDate,$toDate); ?></a></div></td>
                            <td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo GetTotalAttAbsent2($EmpNo,$fromDate,$toDate); ?></a></div></td>
						  </tr>
<?PHP
						}
					}
							
						}elseif($ViewType =='viewC'){
							?>
                            <tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Emp No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Employee Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Status </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Term Total Present</strong></div></td>
				    </tr>
                      <?PHP 
							$query2 = "select ID,EmpName from tbemployeemasters where EmpDept ='$OptDepartment' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName";
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0){
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter_Emp = $counter_Emp+1;
							$EmpNo = $row2["ID"];
							$EmpName = $row2["EmpName"];
							
							$query = "select Status from tbattendanceemployee where EmpId='$EmpNo' and Att_date='$specificDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Status  = $dbarray['Status'];
							$bg = "";
							if($Status == ""){
								$Status = "-";
							}elseif($Status =="P"){
								 $bgs = "bgcolor='#F2F2F2'";
							}elseif($Status =="L"){
								 $bgs = "bgcolor='#FFCC33'";
							}elseif($Status =="L.5"){
								 $bgs = "bgcolor='#66FFCC'";
							}elseif($Status =="A"){
								 $bgs = "bgcolor='#FF9C97'";
							}elseif($Status =="A.5"){
								 $bgs = "bgcolor='#CCFF00'";
							}
?>								  
						  <tr <?PHP echo $bgs; ?>>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $counter_Emp; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpNo; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpName; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo Long_date($specificDate); ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $Status; ?></a></div></td>
                            <td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo GetTotalEmpAtt($EmpNo); ?></a></div></td>
						  </tr>
<?PHP
						}
					}

						}elseif($ViewType =='viewD'){
							?>
                            <tr>
						<td width="31" bgcolor="#666666"><div align="center" class="style25 style1">#</div></td>
						<td width="161" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Emp No </strong></div></td>
						<td width="196" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Employee Name </strong></div></td>
						<td width="122" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Att. Date </strong></div></td>
						<td width="63" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Status </strong></div></td>
						<td width="65" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Term Total Present</strong></div></td>
				    </tr>
						<?PHP	
					$query2 = "select ID,EmpName from tbemployeemasters where EmpName = '$employeeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0){
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter_Emp = $counter_Emp+1;
							$EmpNo = $row2["ID"];
							$EmpName = $row2["EmpName"];
							
							$query = "select Status from tbattendanceemployee where EmpId='$EmpNo' and Att_date='$specificDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Status  = $dbarray['Status'];
							$bg = "";
							if($Status == ""){
								$Status = "-";
							}elseif($Status =="P"){
								 $bgs = "bgcolor='#F2F2F2'";
							}elseif($Status =="L"){
								 $bgs = "bgcolor='#FFCC33'";
							}elseif($Status =="L.5"){
								 $bgs = "bgcolor='#66FFCC'";
							}elseif($Status =="A"){
								 $bgs = "bgcolor='#FF9C97'";
							}elseif($Status =="A.5"){
								 $bgs = "bgcolor='#CCFF00'";
							}
?>								  
						  <tr <?PHP echo $bgs; ?>>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $counter_Emp; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpNo; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $EmpName; ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo Long_date($specificDate); ?></a></div></td>
							<td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo $Status; ?></a></div></td>
                            <td><div align="center"><a href="attendance_rpt.php?pg=Employee Details&empID=<?PHP echo $EmpNo; ?>" target="_blank"><?PHP echo GetTotalEmpAtt($EmpNo); ?></a></div></td>
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
		}elseif ($Page == "Employee Details") {
?>
		  <TR>
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=System%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="attreport.php?subpg=Employee Attendance details">Attendance</a> &gt; Employee Attendance</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Employee Attendance details</strong></div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><div align="center">
				  <input type="submit" name="SubmitSAtt" value="P" style="background:#F2F2F2"> 
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L" style=" background:#FFCC33">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L.5"  style=" background:#66FFCC">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A" style=" background:#FF9C97">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A.5"  style=" background:#CCFF00">
				</div></div>
				<table  width="60%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 60%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="107" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Att. Date </strong></div></td>
						<td width="66" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Status </strong></div></td>
					  <td width="150" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Full Description</strong></div></td>
				    </tr>
<?PHP
						$EmpNo = $_GET['empID'];
						$query = "select EmpName from tbemployeemasters where ID ='$EmpNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$EmpName = $dbarray['EmpName'];
?>
						  <tr>
							<td colspan="3"><p style="margin-left:10px;"><strong><?PHP echo $EmpName; ?></strong></p></td>
						  </tr>
<?PHP
						$query3 = "select Att_date,Status from tbattendanceemployee where EmpId='$EmpNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
