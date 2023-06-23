<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	session_start();
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
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Clinic Management";
	$audit=update_Monitory('Login','Administrator',$Page);
	$PageHasError = 0;
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_GET['Optsrc']))
	{
		$_POST['OptSearch'] = $_GET['Optsrc'];
		$OptSearch = $_GET['Optsrc'];
		$OptClass = $_GET['clss'];
		$StuName = $_GET['name'];
		$_POST['StuName'] = $_GET['name'];
	}
	if(isset($_POST['GetStudent']))
	{
		$OptSearch = $_POST['OptSearch'];
		$OptClass = $_POST['OptClass'];
		$StuName = $_POST['StuName'];
	}
	
	if(isset($_GET['adm_No']))
	{
		$adm_No = $_GET['adm_No'];
		$query = "select * from tbstudentmaster where AdmissionNo='$adm_No'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$AdmnNo = $_GET['adm_No'];
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Class  = strtoupper($dbarray['Stu_Class']);
		$ClssName = GetClassName($Stu_Class);
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);
		
		$DOB  = long_date($dbarray['Stu_DOB']);
		$Email  = strtoupper($dbarray['Stu_Email']);
		$Address  = strtoupper($dbarray['Stu_Address']);

		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatherName  = $dbarray['Gr_Name_Mr'];
		$Contact  = $dbarray['Gr_Ph'];
		
		$query = "select * from tbclinic where AdmNo='$adm_No' and Term = '$Activeterm'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$clinicID = $dbarray['ID'];	
		$OptStatus  = $dbarray['Status'];
		$ReqDate  = $dbarray['ReqsDate'];
		$ReqDate = User_date($ReqDate);
		
		$ChargeAmt  = $dbarray['ChgAmount'];
		if($ChargeAmt==""){
			$ChargeAmt = 0;
		}
		$Illness  = $dbarray['illness'];
		$Drugs  = $dbarray['Drug'];
		$Rmarks  = $dbarray['Remarks'];
		
		$_POST['OptSearch'] = $_GET['Optsrc'];
		$OptSearch = $_GET['Optsrc'];
		$OptClass = $_GET['clss'];
		$StuName = $_GET['name'];
		$_POST['StuName'] = $_GET['name'];
	}
	if(isset($_POST['SubmitClinic']))
	{	
		$AdmnNo = $_POST['StudAdmNo'];
		$clinicID = $_POST['clinicID'];
		if(!$_POST['StudAdmNo']){
			$errormsg = "<font color = red size = 1>Select Student.</font>";
			$PageHasError = 1;
		}
		
		$OptStatus = $_POST['OptStatus'];
		$ReqDate = $_POST['ReqDate'];
		$ReqDate = DB_date($ReqDate);
		$ChargeAmt = $_POST['ChargeAmt'];
		$Illness = $_POST['Illness'];
		$Drugs = $_POST['Drugs'];
		$Rmarks = $_POST['Rmarks'];
		
		
		
		if(!$_POST['ReqDate']){
			$errormsg = "<font color = red size = 1>Request Date is empry.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptStatus']){
			$errormsg = "<font color = red size = 1>Select Status.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['Illness']){
			$errormsg = "<font color = red size = 1>Illness is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SubmitClinic'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbclinic where AdmNo='$AdmnNo' and ReqsDate`='$ReqDate' and illness='$Illness' And Term ='$Activeterm'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The details you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbclinic(AdmNo,Status,ReqsDate,ChgAmount,illness,Drug,Remarks,Term) Values ('$AdmnNo','$OptStatus','$ReqDate','$ChargeAmt','$Illness','$Drugs','$Rmarks','$Activeterm')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					$OptStatus= "";
					$ReqDate= "";
					$ChargeAmt= "";
					$Illness= "";
					$Drugs= "";
					$Rmarks= "";
				}
			}elseif ($_POST['SubmitClinic'] =="Update"){
				$q = "update tbclinic set AdmNo = '$AdmnNo',Status = '$OptStatus',ReqsDate = '$ReqDate',ChgAmount = '$ChargeAmt',Drug = '$Drugs',Remarks = '$Rmarks', Term = '$Activeterm'  where ID = '$clinicID'";
				
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				$OptStatus= "";
				$ReqDate= "";
				$ChargeAmt= "";
				$Illness= "";
				$Drugs= "";
				$Rmarks= "";
			}elseif ($_POST['SubmitClinic'] =="Delete"){
				$q = "Delete From tbclinic where ID = '$clinicID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
				
				$OptStatus= "";
				$ReqDate= "";
				$ChargeAmt= "";
				$Illness= "";
				$Drugs= "";
				$Rmarks= "";
			}
		}
	}
	if(isset($_POST['DelSubmitClinic']))
	{
		$Total = $_POST['Total'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCliID'.$i]))
			{
				$chkCliID = $_POST['chkCliID'.$i];
				$q = "Delete From tbclinic where ID = '$chkCliID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['NotifyParent']))
	{	
		$CountSMS = 0;
		$TotalStudent = $_POST['TotalStudent'];
		for($i=1;$i<=$TotalStudent;$i++){
			if(isset($_POST['chkStud'.$i]))
			{
				$chkStud = $_POST['chkStud'.$i];				
				$query = "select * from tbclinic where AdmNo = '$chkStud' and Term = '$Activeterm'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);		
				$CliID  = $dbarray['ID'];
				$Status  = $dbarray['Status'];
				$ReqsDate  = $dbarray['ReqsDate'];
				if($Status=="Visited"){
					$Status = "Visited the clinic on ".long_date($ReqsDate);
				}elseif($Status=="Admitted"){
					$Status = "Admitted to clinic on ".long_date($ReqsDate);
				}
				$query = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo='$chkStud'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$RegNo  = $dbarray['Stu_Regist_No'];
				$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

				$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$Contact  = $dbarray['Gr_Ph'];
				
				$isSend_Status="False";
				$isSend_Status = sendClinic($chkStud,$StudentName,$Status,$Contact);
				if($isSend_Status == "False"){
					$CountSMS = $CountSMS;
				}elseif($isSend_Status == "True"){
					$CountSMS = $CountSMS +1;
				}
			}
		}
		$errormsg = "<font color = blue size = 1>".$CountSMS." SMS messages sent Successfully.</font>";
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
.style21 {font-weight: bold}
.style24 {color: #FFFFFF}
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
<script type="text/javascript">
<!--
function clearDefault(el) {
if (el.defaultValue==el.value) el.value = ""
}
// -->
</script>
<style type="text/css">

.ds_box {
	background-color: #FFF;
	border: 1px solid #000;
	position: absolute;
	z-index: 32767;
}

.ds_tbl {
	background-color: #FFF;
}

.ds_head {
	background-color: #333;
	color: #FFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	text-align: center;
	letter-spacing: 2px;
}

.ds_subhead {
	background-color: #CCC;
	color: #000;
	font-size: 12px;
	font-weight: bold;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	width: 32px;
}

.ds_cell {
	background-color: #EEE;
	color: #000;
	font-size: 13px;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	cursor: pointer;
}

.ds_cell:hover {
	background-color: #F3F3F3;
} /* This hover code won't work for IE */

</style>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<?PHP include 'datecalander.php'; ?>
<TABLE style="WIDTH: 100%" background="images/Top_pole.jpg">
<TBODY>
	<TR>
	  <TD height="55px" valign="top">
	  	<TABLE width="1100" border="0" cellPadding=3 cellSpacing=0 bgcolor="#D7CFBE" align="center">
		  <TR>
			<TD width="23%" align="left"><img src="images/skoolnet_logo.gif" width="130" height="39" align="left"></TD>
			<TD width="77%" align="right"><a href="welcome.php?pg=System Setup&mod=admin"> Home</a> | <a href="download/userguide.pdf" target="_blank">Download Userguide</a> | <a href="backup.php?pg=login" target="_blank">Backup System</a> |<a href="Logout.php">Logout </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
		  </TR>
		</TABLE>
	  </TD>
	</TR>
</TBODY>
</TABLE>
<TABLE width="100%" bgcolor="#efe7d4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	<DIV id=main>
		<DIV id=mainmenu>
			<?PHP include 'topmenu.php'; ?>
		</DIV>
	</DIV>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD>
		<TABLE width="100%" style="WIDTH: 100%">
        <TBODY>
			<TR>
			  <TD width="222" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="751" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>

<?PHP
		if ($SubPage == "Clinic Management") {
?>
			<?PHP echo $errormsg; ?>
			<form name="form1" method="post" action="clinic.php?subpg=Clinic Management">
			<TABLE width="94%" style="WIDTH: 100%">
				<TBODY>
				<TR>
				  <TD width="40%" valign="top"  align="left">
						<strong>Search Criteria</strong>
						<p>
<?PHP
						if($OptSearch =="All")
						{
							echo "<input type='radio' name='OptSearch' value='All' checked='checked'/>All Student";
						}else{
							echo "<input type='radio' name='OptSearch' value='All'/>All Student";
						}
?>
						</p>
						<p>
<?PHP
						if($OptSearch =="Class")
						{
							echo "<input type='radio' name='OptSearch' value='Class' checked='checked'/>Class";
						}else{
							echo "<input type='radio' name='OptSearch' value='Class'/>Class";
						}
?>
						<select name="OptClass">
						<option value="" selected="selected">&nbsp;</option>
<?PHP
						$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows <= 0 ) {
							echo "";
						}
						else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
								if($OptClass ==$ClassID){
?>
									<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $ClassName; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $ClassID; ?>"><?PHP echo $ClassName; ?></option>
<?PHP
								}
							}
						}
?>
						</select>
						</p>
						<p>
<?PHP
						if($OptSearch =="Name")
						{
							echo "<input type='radio' name='OptSearch' value='Name' checked='checked'/>Name";
						}else{
							echo "<input type='radio' name='OptSearch' value='Name'/>Name";
						}
?>					  		   
						<input type="text" name="StuName" size="10" value="<?PHP echo $StuName; ?>">
						<input name="GetStudent" type="submit" id="GetStudent" value="Go">
						</p>
						<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
						  <tr>
							<td width="17%" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
							<td width="52%" bgcolor="#F4F4F4"><div align="center" class="style21">Name</div></td>
							<td width="31%" bgcolor="#F4F4F4"><div align="center" class="style21">Admn  No.</div></td>
						  </tr>
<?PHP
							$counter_stud = 0;
							if(isset($_POST['OptSearch']))
							{
								if($_POST['OptSearch'] == "All"){
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Sec ='$Activeterm'";
								}elseif($_POST['OptSearch'] == "Class"){
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Class = '$OptClass' And Stu_Sec ='$Activeterm'";
								}elseif($_POST['OptSearch'] == "Name"){
									$Search_Key = $_POST['StuName'];
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where INSTR(Stu_Full_Name,'$Search_Key') And Stu_Sec ='$Activeterm' order by Stu_Full_Name";
								}else{
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Sec ='$Activeterm'";
								}
							}else{
								$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Sec ='$Activeterm'";
							}
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_stud = $rstart;
							}else{
								$counter_stud = $rstart-1;
							}
							$counter = 0;
							
							if(isset($_POST['OptSearch']))
							{
								if($_POST['OptSearch'] == "All"){
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}elseif($_POST['OptSearch'] == "Class"){
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Class = '$OptClass' And Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}elseif($_POST['OptSearch'] == "Name"){
									$Search_Key = $_POST['StuName'];
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where INSTR(Stu_Full_Name,'$Search_Key') And Stu_Sec ='$Activeterm' order by Stu_Full_Name";
								}else{
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}
							}else{
								$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_stud = $counter_stud+1;
									$counter = $counter+1;
									$RecID = $row["ID"];
									$AdmissionNo = $row["AdmissionNo"];
									$Stu_Full_Name = $row["Stu_Full_Name"];
									
?>
									  <tr bgcolor="#666666">
										<td><div align="center">
											<input type="hidden" name="AdmNo<?PHP echo $counter; ?>" value="<?PHP echo $AdmissionNo; ?>">
											<input name="chkStud<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $AdmissionNo; ?>"></div></td>
										<td><div align="center"><a href="clinic.php?subpg=Clinic Management&adm_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>"><font color="#FFFFFF"><?PHP echo $Stu_Full_Name; ?></font></a></div></td>
										<td>
										  <?PHP echo $HostelID; ?>									    </td>
									  </tr>
<?PHP
								 }
							 }
?>
						</table>
						<p><input type="hidden" name="TotalStudent" value="<?PHP echo $counter_Stud; ?>">
						  <input type="submit" name="NotifyParent" value="Notify Parent">
						</p>
						<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="clinic.php?subpg=Clinic Management&st=0&ed=<?PHP echo $rend; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>">First&nbsp;</a>| &nbsp;<a href="clinic.php?subpg=Clinic Management&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>">Next</a> </p>
						<p>Note: To send notification to parent, tick on the appropriate     check box and click on notify parent </p></TD>
					 <TD width="60%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					<TABLE width="99%">
						<TBODY>
						<TR>
						  <TD width="32%"  align="left">Admn No     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="AdmnNo" type="text" size="35" value="<?PHP echo $AdmnNo; ?>" disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Student Name     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="StudentName" type="text" size="35" value="<?PHP echo $StudentName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Father Name     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="FatherName" type="text" size="35" value="<?PHP echo $FatherName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Student Class     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="ClassName" type="text" size="35" value="<?PHP echo $ClssName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Contact No   :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="Contact" type="text" size="35" value="<?PHP echo $Contact; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Email Address   :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="Email" type="text" size="35" value="<?PHP echo $Email; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Status    :</TD>
						  <TD  align="left" valign="top"><label>
						  <select name="OptStatus">
                            <option value="">&nbsp;</option>
<?PHP
							if($OptStatus == "Visited"){
?>
                            <option value="<?PHP echo $OptStatus; ?>" selected="selected"><?PHP echo $OptStatus; ?></option>
                            <option value="Admitted">Admitted</option>
<?PHP
							}elseif($OptStatus == "Admitted"){
?>
                            <option value="Visited">Visited</option>
                            <option value="<?PHP echo $OptStatus; ?>" selected="selected"><?PHP echo $OptStatus; ?></option>
<?PHP
							}else{
?>
                            <option value="Visited">Visited</option>
                            <option value="Admitted">Admitted</option>
<?PHP
							}
?>
                          </select>
						  *</label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Date:</TD>
						  <TD  align="left" valign="top"><input class="fil_ariane_passif" onClick="ds_sh(this);" name="ReqDate" readonly="readonly" style="cursor: text" value="<?PHP echo $ReqDate; ?>"/>
						    *</TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Charge Amount :</TD>
						  <TD  align="left" valign="top"><input name="ChargeAmt" type="text" size="25" value="<?PHP echo $ChargeAmt; ?>"  style="background:#FFFFFF"></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Illness Description :</TD>
						  <TD  align="left" valign="top"><input name="Illness" type="text" size="45" value="<?PHP echo $Illness; ?>" style="background:#FFFFFF">
						    *</TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Drugs Prescription :</TD>
						  <TD  align="left" valign="top"><textarea name="Drugs" cols="45" rows="4" style="background:#FFFFFF"><?PHP echo $Drugs; ?></textarea></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Remarks:</TD>
						  <TD  align="left" valign="top"><label>
						    <textarea name="Rmarks" cols="45" rows="4"><?PHP echo $Rmarks; ?></textarea>
						  </label>						  </TD>
						</TR>
						<TR>
							<TD colspan="2"><div align="center"><p>All Field with * are compulsory</p>
							<input type="hidden" name="clinicID" value="<?PHP echo $clinicID; ?>">
							 <input type="hidden" name="StudAdmNo" value="<?PHP echo $adm_No; ?>">
							 <input name="SubmitClinic" type="submit" id="SubmitClinic" value="Create New">
							  <input name="SubmitClinic" type="submit" id="SubmitClinic" value="Update">
							</div></TD>
						</TR>
						<TR>
							<TD colspan="2"><div align="center">
							Admission No. :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="48" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="106" bgcolor="#F4F4F4"><div align="center" class="style21">Status</div></td>
                            <td width="244" bgcolor="#F4F4F4"><div align="left"><strong>Student Name</strong></div></td>
                          </tr>
<?PHP
							$counter_room = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tbclinic where INSTR(AdmNo,'$Search_Key')";
							}else{
								$query2 = "select * from tbclinic Status";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_room = $rstart;
							}else{
								$counter_room = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbclinic where INSTR(AdmNo,'$Search_Key') LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tbclinic order by Status LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_room = $counter_room+1;
									$counter = $counter+1;
									$ClnID = $row["ID"];
									$AdmissionNo = $row["AdmNo"];
									$dbStatus = $row["Status"];
									
									$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo='$AdmissionNo'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$StudentName  = strtoupper($dbarray['Stu_Full_Name']);
?>
									  <tr>
										<td><div align="center">
										<input name="chkCliID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $ClnID; ?>"></div></td>
										<td><div align="center"><a href="clinic.php?subpg=Clinic Management&adm_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>"><?PHP echo $dbStatus; ?></a></div></td>
										<td><div align="left"><a href="clinic.php?subpg=Clinic Management&adm_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>"><?PHP echo $StudentName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="clinic.php?subpg=Clinic Management&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="clinic.php?subpg=Clinic Management&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="clinic.php?subpg=Clinic Management&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
							</div></TD>
						</TR>
						<TR>
							<TD colspan="2"><div align="center"><p>All Field with * are compulsory</p>
							<input type="hidden" name="Total" value="<?PHP echo $counter; ?>">
							 <input name="DelSubmitClinic" type="submit" id="DelSubmitClinic" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
							</div></TD>
						</TR>
					</TBODY>
					</TABLE>					</TD>
				</TR>
			</TBODY>
			</TABLE>
			</form>
<?PHP
		}
?>
              </TD>
			</TR>
		</TBODY>
		</TABLE>
		<BR></TD>
	  </TR>
	 </TABLE>
      </TD></TR></TBODY></TABLE>
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
