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
	if(isset($_GET['rid']))
	{
		$OptRooms = $_GET['rid'];
		$rollDate = $_GET['dt'];
		$ViewType = "Date";
	}
	if(isset($_GET['hid']))
	{
		$HostelNo = $_GET['hid'];
		$ViewType = "HostelNo";
	}
	if(isset($_GET['adm']))
	{
		$AdmissionNo = $_GET['adm'];
		$ViewType = "AdmissionNo";
	}
	if(isset($_GET['ty']))
	{
		$type = $_GET['ty'];
		$frmDate = $_GET['fr'];
		$toDate = $_GET['to'];
		if($type=="Request"){
			
		}elseif($type=="Exeat"){
			
		}elseif($type=="Return"){
			
		}
	}
	if(isset($_GET['ty2']))
	{
		$type = $_GET['ty2'];
		$frmDate = $_GET['fr'];
		$toDate = $_GET['to'];
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
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
<?PHP
		if ($Page == "Student Roll Call") {
?>
		  <TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="hostreport.php?subpg=Roll Call">Roll Call</a> &gt; Student Roll Details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Roll Call</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><div align="center">
				  <input type="submit" name="SubmitSAtt" value="P" style="background:#F2F2F2"> 
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="L" style=" background:#FFCC33">
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="SubmitSAtt" value="A" style=" background:#FF9C97">
				</div></div>
				<table width="124%" border="0" align="center" cellpadding="3" cellspacing="3">
				  <tr>
					<td width="31" bgcolor="#666666"><div align="center" class="style24 style1"><strong>Tick</strong></div></td>
					<td width="161" bgcolor="#666666"><div align="center" class="style24 style1"><strong>Hostel No </strong></div></td>
					<td width="196" bgcolor="#666666"><div align="center" class="style24 style1"><strong> Student Name </strong></div></td>
					<td width="161" bgcolor="#666666"><div align="center" class="style24 style1"><strong>Room No </strong></div></td>
					<td width="122" bgcolor="#666666"><div align="center" class="style24 style1"><strong> Roll Call Date </strong></div></td>
					<td width="63" bgcolor="#666666"><div align="center" class="style24 style1"><strong> Status </strong></div></td>
				  </tr>
<?PHP
					$counter_Stud = 0;
					if($ViewType =='Date'){
						$query3 = "select AdmNo,HostelNo,RoomID from tbstudentroom where RoomID ='$OptRooms' and Term = '$Activeterm'";
					}elseif($ViewType =='HostelNo'){
						$query3 = "select AdmNo,HostelNo,RoomID from tbstudentroom where HostelNo = '$HostelNo' and Term = '$Activeterm'";
					}
					
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$counter_Stud = $counter_Stud+1;
							$counter = $counter+1;
							$AdmissionNo = $row["AdmNo"];
							$HostelID = $row["HostelNo"];
							$RmID = $row["RoomID"];
							
							if($ViewType =='Date'){
								$query = "select Status from tbrollcall where AdmNo='$AdmissionNo' and HostelNo = '$HostelID' and RoomID = '$RmID' and Date = '$rollDate' and Term = '$Activeterm'";
							}elseif($ViewType =='HostelNo'){
								$query = "select Status from tbrollcall where AdmNo='$AdmissionNo' and HostelNo = '$HostelID' and RoomID = '$RmID' and Term = '$Activeterm'";
							}
					
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Status  = $dbarray['Status'];
							
							$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo='$AdmissionNo' and Stu_Sec = '$Activeterm'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Stu_Full_Name  = $dbarray['Stu_Full_Name'];
							
							$query = "select * from tbhostelroom where ID='$RmID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);	
							$StudentRoom  = $dbarray['RoomName'];

							$bg = "";
							if($Status == ""){
								$Status = "-";
							}elseif($Status =="P"){
								 $bg = "bgcolor='#F2F2F2'";
							}elseif($Status =="L"){
								 $bg = "bgcolor='#FFCC33'";
							}elseif($Status =="A"){
								 $bg = "bgcolor='#FF9C97'";
							}
?>								  
							  <tr <?PHP echo $bg; ?>>
								<td>
								   <div align="center">
								   <input type="hidden" name="Adm<?PHP echo $counter_Stud; ?>" value="<?PHP echo $AdmissionNo; ?>">
									 <input name="chkHostelID<?PHP echo $counter_Stud; ?>" type="checkbox" value="<?PHP echo $HostelID; ?>" <?PHP echo $chkStudent; ?>></div></td>
								<td><div align="center"><?PHP echo $HostelID; ?></div></td>
								<td><div align="center"><?PHP echo $Stu_Full_Name; ?></div></td>
								<td><div align="center"><?PHP echo $StudentRoom; ?></div></td>
								<td><div align="center"><?PHP echo Long_date($rollDate); ?></div></td>
								<td><div align="center"><?PHP echo $Status; ?></div></td>
							  </tr>
<?PHP
						}
					}
?>
				</table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Student Exeat") {
?>
		  <TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="hostreport.php?subpg=Exeat Details">Exeat</a> &gt; Student Exeat Details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Exeat Details</strong></div>
				</div></div>
				<table  width="100%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="72" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Status</strong></div></td>
					  <td width="67" bgcolor="#666666"><div align="center" class="style1 style25"><strong> Hostel No. </strong></div></td>
					  <td width="108" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Admn No</strong></div></td>
					  <td width="237" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Student Name</strong></div></td>
					  <td width="62" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Room</strong></div></td>
					  <td width="116" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Request Date</strong></div></td>
					  <td width="118" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Exeat Date</strong></div></td>
					  <td width="124" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Return Date</strong></div></td>
				    </tr>
<?PHP
						$frmDate = DB_date($frmDate);
						$toDate = DB_date($toDate);
						$arrDate=date_range($frmDate,$toDate);
						$n=0;
						while(isset($arrDate[$n])){
							//echo $arrDate[$n]."<br>";
							if($type=="Request"){
								$query3 = "select * from tbexeathostel where ReqDate='$arrDate[$n]'";
							}elseif($type=="Exeat"){
								$query3 = "select * from tbexeathostel where ExtDate='$arrDate[$n]'";
							}elseif($type=="Return"){
								$query3 = "select * from tbexeathostel where RetDate='$arrDate[$n]'";
							}
							
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$ExetID = $row["ID"];
									$DbStatus = $row["Status"];
									$DbHostelNo = $row["HostelNo"];
									$DbAdmNo = $row["AdmNo"];
									$DbRoomID = $row["RoomID"];
									$DbReqDate = $row["ReqDate"];
									$DbReqDate = User_date($DbReqDate);
									$DbExtDate = $row["ExtDate"];
									$DbExtDate = User_date($DbExtDate);
									$DbRetDate = $row["RetDate"];
									$DbRetDate = User_date($DbRetDate);
									
									
									$query = "select * from tbstudentmaster where AdmissionNo='$DbAdmNo'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$StudentName  = strtoupper($dbarray['Stu_Full_Name']);
									
									$query = "select * from tbhostelroom where ID='$DbRoomID'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$StudentRoom  = $dbarray['RoomName'];
									
									if($DbStatus =="Refuse"){
										$bg="#FFBF80";
										$fbg="#003399";
									}elseif($DbStatus =="Granted"){
										$bg="#F2F2F2";
										$fbg="#000000";
									}else{
										$bg="#FFFFFF";
										$fbg="#000000";
									}
?>								  
									  <tr bgcolor="<?PHP echo $bg; ?>">
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbStatus; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbHostelNo; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbAdmNo; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $StudentName; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $StudentRoom; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbReqDate; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbExtDate; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbRetDate; ?></font></div></td>
									  </tr>
<?PHP
								}
							}
							$n=$n+1;
						}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Student Clinic") {
?>
		   <TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="hostreport.php?subpg=Clinic Details">Clinic</a> &gt; Student Clinic Details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student Clinic Details</strong></div>
				</div></div>
				<table  width="100%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="68" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Status</strong></div></td>
					  <td width="97" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Admn No</strong></div></td>
					  <td width="167" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Student Name</strong></div></td>
					  <td width="87" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Class</strong></div></td>
					  <td width="91" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Date</strong></div></td>
					  <td width="87" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Amount</strong></div></td>
					  <td width="144" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Illness</strong></div></td>
					  <td width="163" bgcolor="#666666"><div align="center" class="style1 style25"><strong>Drugs prescription</strong></div></td>
				    </tr>
<?PHP
						$frmDate = DB_date($frmDate);
						$toDate = DB_date($toDate);
						$arrDate=date_range($frmDate,$toDate);
						$n=0;
						while(isset($arrDate[$n])){
							//echo $arrDate[$n]."<br>";
							if($type=="All"){
								$query3 = "select * from tbclinic where ReqsDate='$arrDate[$n]'";
							}elseif($type=="Admitted"){
								$query3 = "select * from tbclinic where ReqsDate='$arrDate[$n]' and Status = 'Admitted'";
							}elseif($type=="Visited"){
								$query3 = "select * from tbclinic where ReqsDate='$arrDate[$n]' and Status = 'Visited'";
							}
		
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$ClinicID = $row["ID"];
									$DbStatus = $row["Status"];
									$DbAdmNo = $row["AdmNo"];
									$DbRoomID = $row["RoomID"];
									$DbAmount = $row["ChgAmount"];
									$Dbillness = $row["illness"];
									$DbDrug = $row["Drug"];
									
									
									$query = "select * from tbstudentmaster where AdmissionNo='$DbAdmNo'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$StudentName  = strtoupper($dbarray['Stu_Full_Name']);
									$StuClass  = $dbarray['Stu_Class'];
									$ClssName = GetClassName($StuClass);
									
									$query = "select * from tbhostelroom where ID='$DbRoomID'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$StudentRoom  = $dbarray['RoomName'];
									
									if($DbStatus =="Admitted"){
										$bg="#FFBF80";
										$fbg="#003399";
									}elseif($DbStatus =="Visited"){
										$bg="#F2F2F2";
										$fbg="#000000";
									}else{
										$bg="#FFFFFF";
										$fbg="#000000";
									}
?>								  
									  <tr bgcolor="<?PHP echo $bg; ?>">
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbStatus; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbAdmNo; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $StudentName; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $ClssName; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $arrDate[$n]; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbAmount; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $Dbillness; ?></font></div></td>
										<td><div align="center"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbDrug; ?></font></div></td>
									  </tr>
<?PHP
								}
							}
							$n=$n+1;
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
