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
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Admission";
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
		$rend = 10;
	}
	$status2 = 1;
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
		
	if(isset($_POST['SubmitRelation']))
	{
		$backpg = "<a href=welcome.php?pg=Master%20Setup&mod=admin>Home</a> &gt; <a href=registration.php?subpg=Admission>Admission</a> &gt; <a href=registration.php?subpg=Registration>Registration</a> &gt; Relation";
		$SubPage = "Relation";
	}
	if(isset($_POST['Relmaster']))
	{
		$PageHasError = 0;
		$RelID = $_POST['SelRelID'];
		$RelName = $_POST['RelName'];
		
		if(!$_POST['RelName']){
			$errormsg = "<font color = red size = 1>Relation Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Relmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbrelation where relation = '$RelName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Relation you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbrelation(relation) Values ('$RelName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$RelName = "";
				}
			}elseif ($_POST['Relmaster'] =="Update"){
				$q = "update tbrelation set relation = '$RelName' where ID = '$RelID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$RelID = "";
				$RelName = "";
			}
		}
	}
	if(isset($_GET['rel_id']))
	{
		$RelID = $_GET['rel_id'];
		$query = "select * from tbrelation where ID='$RelID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RelName  = $dbarray['relation'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['Relmaster_delete']))
	{
		$Total = $_POST['TotalRel'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkrelID'.$i]))
			{
				$relIDs = $_POST['chkrelID'.$i];
				$q = "Delete From tbrelation where ID = '$relIDs'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	$regisFee = "0.00";
	 $SubmitSearch = 'false';
	if(isset($_POST['SubmitSearch']))
		{   
		   $SubmitSearch = 'true';      
		}
	if(isset($_GET['Optsearch']))
	{
		$Optsearch = $_GET['Optsearch'];
		 $SubmitSearch = 'true';
		 if($Optsearch == "Class Wise"){
			$OptClassWise = $_GET['OptClassWise'];
		}elseif($Optsearch == "Name Wise"){
			$Search_Key = $_GET['Search_Key'];
		}
	}
	else {if(isset($_POST['Optsearch']))
	{	
		$Optsearch = $_POST['Optsearch'];
		$Search_Key = $_POST['Search_Key'];
		$OptClassWise = $_POST['OptClassWise'];
		/*if($Optsearch !="Class Wise" and $Optsearch !="Name Wise"){
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($Optsearch =="Class Wise"){
			$lockname = "disabled='disabled'";
		}elseif($Optsearch =="Name Wise"){
			$lockclass = "disabled='disabled'";
		}*/
	        }
	}
	if(isset($_POST['regmaster']))
	{
		$PageHasError = 0;
		$regisNo = $_POST['SelRegisID'];
		$reg_Dy = $_POST['reg_Dy'];
		$reg_Mth = $_POST['reg_Mth'];
		$reg_Yr = $_POST['reg_Yr'];
		$RegDate = $reg_Yr."-".$reg_Mth."-".$reg_Dy;
		if(!is_numeric($reg_Dy)){
			$errormsg = "<font color = red size = 1>Invalid Date (Day).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($reg_Mth)){
			$errormsg = "<font color = red size = 1>Invalid Date (Month).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($reg_Yr)){
			$errormsg = "<font color = red size = 1>Invalid Date (Year).</font>";
			$PageHasError = 1;
		}
		$OptClass = $_POST['OptClass'];
		$regisFee = $_POST['regisFee'];
		if(!is_numeric($regisFee)){
			$errormsg = "<font color = red size = 1>Invalid Registration Fee.</font>";
			$PageHasError = 1;
		}
		$DOB_Dy = $_POST['DOB_Dy'];
		$DOB_Mth = $_POST['DOB_Mth'];
		$DOB_Yr = $_POST['DOB_Yr'];
		$DOBDate = $DOB_Yr."-".$DOB_Mth."-".$DOB_Dy;
		if(!is_numeric($DOB_Dy)){
			$errormsg = "<font color = red size = 1>Invalid Date (Day).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($DOB_Mth)){
			$errormsg = "<font color = red size = 1>Invalid Date (Month).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($DOB_Yr)){
			$errormsg = "<font color = red size = 1>Invalid Date (Year).</font>";
			$PageHasError = 1;
		}
		$FirstName = $_POST['FirstName'];
		$MidName = $_POST['MidName'];
		$LastName = $_POST['LastName'];
		$OptGender = $_POST['OptGender'];
		if(!$_POST['OptRelation'] ){
			$OptRelation = '1';
			}else{
		$OptRelation = $_POST['OptRelation'];
			}
		$RegAddress = $_POST['RegAddress'];
		$PaMr = $_POST['PaMr'];
		$OptCity = $_POST['OptCity'];
		$PaMrs = $_POST['PaMrs'];
		$Optstate = $_POST['Optstate'];
		$PhoneNo = $_POST['PhoneNo'];
		$Email = $_POST['Email'];
		
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Student Class.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['FirstName'] ){
			$errormsg = "<font color = red size = 1>Student first name is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['MidName'] ){
			$errormsg = "<font color = red size = 1>Student middle name is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['LastName'] ){
			$errormsg = "<font color = red size = 1>Student last name is empty.</font>";
			$PageHasError = 1;
		}
		//if(!$_POST['OptRelation'] ){
		//	$errormsg = "<font color = red size = 1>Please select relation.</font>";
		//	$PageHasError = 1;
		//}
		
		
		if ($PageHasError == 0)
		{
			if ($_POST['regmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbregistration where Stu_FirstName = '$FirstName' and Stu_MidName = '$MidName' and Stu_LastName = '$LastName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The registration you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
			$q = "Insert into tbregistration(Stu_DateRegis,Stu_ClassID,Stu_FirstName,Stu_MidName,Stu_LastName,Stu_DOB,Stu_Gender,RelationID,Stu_Address,CityID,Stu_State,Stu_Phone,Stu_Email,Status,RegFee,Stu_Father,Stu_Mother,Session_ID,Term_ID) Values ('$RegDate','$OptClass','$FirstName','$MidName','$LastName','$DOBDate','$OptGender','$OptRelation','$RegAddress','$OptCity','$Optstate','$PhoneNo','$Email','0','$regisFee','$PaMr','$PaMrs','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$regisNo = "";
					$OptClass = "";
					$regisFee = "";
					$FirstName = "";
					$MidName = "";
					$LastName = "";
					$OptGender = "";
					$OptRelation = "";
					$RegAddress = "";
					$PaMr = "";
					$OptCity = "";
					$PaMrs = "";
					$Optstate = "";
					$PhoneNo = "";
					$Email = "";
				}
			}elseif ($_POST['regmaster'] =="Update"){
				$q = "update tbregistration set Stu_DateRegis='$RegDate',Stu_ClassID='$OptClass',Stu_FirstName='$FirstName',Stu_MidName='$MidName',Stu_LastName='$LastName',Stu_DOB='$DOBDate',Stu_Gender='$OptGender',RelationID='$OptRelation',Stu_Address='$RegAddress',CityID='$OptCity',Stu_State='$Optstate',Stu_Phone='$PhoneNo',Stu_Email='$Email',RegFee='$regisFee',Stu_Father='$PaMr',Stu_Mother='$PaMrs' where ID = '$regisNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$regisNo = "";
				$OptClass = "";
				$regisFee = "";
				$FirstName = "";
				$MidName = "";
				$LastName = "";
				$OptGender = "";
				$OptRelation = "";
				$RegAddress = "";
				$PaMr = "";
				$OptCity = "";
				$PaMrs = "";
				$Optstate = "";
				$PhoneNo = "";
				$Email = "";
			}
		}
	}
	if(isset($_GET['regis_id']))
	{
		$regisNo = $_GET['regis_id'];
		$query = "select * from tbregistration where ID='$regisNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		
		$arrDate=explode ('-', $dbarray['Stu_DateRegis']);
		$reg_Dy = $arrDate[2];
		$reg_Mth = $arrDate[1];
		$reg_Yr = $arrDate[0];
		
		$OptClass  = $dbarray['Stu_ClassID'];
		$regisFee  = $dbarray['RegFee'];
		$FirstName  = $dbarray['Stu_FirstName'];

		$MidName  = $dbarray['Stu_MidName'];
		$LastName  = $dbarray['Stu_LastName'];
		$arrDate2=explode ('-', $dbarray['Stu_DOB']);
		$DOB_Dy = $arrDate2[2];
		$DOB_Mth = $arrDate2[1];
		$DOB_Yr = $arrDate2[0];
		
		$OptGender  = $dbarray['Stu_Gender'];
		$OptRelation  = $dbarray['RelationID'];
		$RegAddress  = $dbarray['Stu_Address'];
		$PaMr  = $dbarray['Stu_Father'];
		$OptCity  = $dbarray['CityID'];
		$PaMrs  = $dbarray['Stu_Mother'];
		$Optstate  = $dbarray['Stu_State'];
		$PhoneNo  = $dbarray['Stu_Phone'];
		$Email  = $dbarray['Stu_Email'];
		$status2  = $dbarray['Status'];
						
		
	}
	if(isset($_POST['regmaster_delete']))
	{
		$Total = $_POST['Totalregis'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkRegisID'.$i]))
			{
				$chkRegisID = $_POST['chkRegisID'.$i];
				$q = "Delete From tbregistration where ID = '$chkRegisID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SubmitSearch']) or isset($_POST['ChkAllStud']))
	{
		$Optsearch = $_POST['Optsearch'];
		if($Optsearch == "Class Wise"){
			$OptClassWise = $_POST['OptClassWise'];
		}elseif($Optsearch == "Name Wise"){
			$Search_Key = $_POST['Search_Key'];
		}
		
		if(isset($_POST['ChkAllStud']))
		{
			$chkStudent = "checked='checked'";
		}else{
			$chkStudent = "";
		}
	}
	if(isset($_POST['regmaster_Print']))
	{
		$regisNo = $_POST['SelRegisID'];
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=admission_rpt.php?pg=Registration Certificate&rid=$regisNo\">";
		exit;
	}
  
  if(isset($_POST['admit']))
   {
	  if($_POST && !empty($_POST['regisNo2'])) {$_SESSION['regisNo'] = $_POST['regisNo2']; }else{ $Title =$_SESSION['Title']; $_SESSION['regisNo'] = "";      }
	 if($_POST && !empty($_POST['FirstName'])) {$_SESSION['FirstName'] = $_POST['FirstName']." ".$_POST['MidName']." ".$_POST['LastName']; }else{ $Title =$_SESSION['Title']; $_SESSION['FirstName'] = "";      }
	  //$_SESSION['regisNo']= 4;
	echo "<meta http-equiv=\"Refresh\" content=\"0;url=admission.php?subpg=Admission\">";
		exit;
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
.style21 {font-weight: bold}
.style22 {
	font-size: 9px;
	font-style: italic;
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
<script type="text/javascript">
<!--
function clearDefault(el) {
if (el.defaultValue==el.value) el.value = ""
}
// -->
</script>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
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
             </TD>
    </TR>
    <TR>
         <TD>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD>
		<TABLE width="100%" style="WIDTH: 100%">
        <TBODY>
			<TR>
			  <TD width="221" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="857" align="center" valign="top">
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
		if ($SubPage == "Registration") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="registration.php?subpg=Registration">
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
				  <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="14%" valign="top"  align="left">Registration No</TD>
					  <TD width="32%" valign="top"  align="left"><input type="text" name="regisNo" value="<?PHP echo $regisNo; ?>" disabled="disabled"/>
					  *
                      <input type="hidden" name="regisNo2" id="regisNo2" value="<?PHP echo $regisNo; ?>" /></TD>
					  <TD width="9%" valign="top"  align="left">Reg.  Date</TD>
					  <TD width="45%" valign="top"  align="left">
<?PHP
						if($reg_Dy==""){$reg_Dy = date('d');}
						if($reg_Mth==""){$reg_Mth = date('m');}
						if($reg_Yr==""){$reg_Yr = date('Y');}
?>
					  <input name="reg_Dy" type="text" size="2"  value="<?PHP echo $reg_Dy; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="reg_Mth" type="text" size="2"  value="<?PHP echo $reg_Mth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="reg_Yr" type="text" size="2"  value="<?PHP echo $reg_Yr; ?>" ONFOCUS="clearDefault(this)">* <span class="style22">Format= DD / MM / YYYY</span>					  </TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Reg Fee</TD>
					  <TD width="32%" valign="top"  align="left"><input type="text" name="regisFee" value="<?PHP echo $regisFee; ?>" ONFOCUS="clearDefault(this)"/>
					  
					  *
					  </TD>
					  <TD width="9%" valign="top"  align="left">Class</TD>
					  <TD width="45%" valign="top"  align="left">
					  <select name="OptClass">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					    </select>*</TD>
					</TR>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Personal Information</strong></div></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">First Name</TD>
					  <TD width="32%" valign="top"  align="left"><input type="text" name="FirstName" value="<?PHP echo $FirstName; ?>"/>
					  *</TD>
					  <TD width="9%" valign="top"  align="left">Date of Birth&nbsp;</TD>
					  <TD width="45%" valign="top"  align="left">
					  <select name="DOB_Dy">
						  <option value="0" selected="selected">Day</option>
						  
<?PHP
							$Cur_Dy = date('d');
							for($i=1; $i<=31; $i++){
								if($DOB_Dy == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}elseif($i == $Cur_Dy){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						<select name="DOB_Mth">
						   <option value="0" selected="selected">Month</option>
<?PHP
								$Cur_Mth = date('m');
								for($i=1; $i<=12; $i++){
									if($i == $DOB_Mth){
										echo "<option value=$i selected='selected'>$i</option>";
										
									}elseif($i == $Cur_Mth){
										echo "<option value=$i selected='selected'>$i</option>";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
						</select>
						<select name="DOB_Yr">
						  <option value="0" selected="selected">Year</option>
<?PHP
							$CurYear = date('Y');
							for($i=1985; $i<=$CurYear; $i++){
								if($DOB_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}elseif($i == $CurYear){
										echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						*
          			 </TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Middle Name&nbsp;</TD>
					  <TD width="32%" valign="top"  align="left"><input type="text" name="MidName" value="<?PHP echo $MidName; ?>"/> *</TD>
					  <TD width="9%" valign="top"  align="left">Gender</TD>
					  <TD width="45%" valign="top"  align="left">
						<select name="OptGender">
<?PHP
						if($OptGender == "M"){
							echo "<option value='M' selected='selected'>Male</option>";
						}else{
							echo "<option value='M'>Male</option>";
						}
						if($OptGender == "F"){
							echo "<option value='F' selected='selected'>Female</option>";
						}else{
							echo "<option value='F'>Female</option>";
						}
?>
					    </select> 			  
						</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Last Name&nbsp;</TD>
					  <TD width="32%" valign="top"  align="left"><input type="text" name="LastName" value="<?PHP echo $LastName; ?>"/> *</TD>
					  <TD width="9%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="45%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Relation</TD>
					  <TD width="32%" valign="top"  align="left">
					  <select name="OptRelation">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,relation from tbrelation order by relation";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$RelID = $row["ID"];
									$RelationName = $row["relation"];
									if($OptRelation ==$RelID){
?>
										<option value="<?PHP echo $RelID; ?>" selected="selected"><?PHP echo $RelationName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $RelID; ?>"><?PHP echo $RelationName; ?></option>
<?PHP
									}
								}
							}
?>
					    </select>
					    <input name="SubmitRelation" type="submit" id="SubmitSearch" value="..."></TD>
					  <TD width="9%" valign="top"  align="left">Address</TD>
					  <TD width="45%" valign="top"  align="left"><textarea name="RegAddress" cols="35" rows="3"><?PHP echo $RegAddress; ?></textarea></TD>
					</TR>
					</TBODY>
					</TABLE>
					<fieldset>
        				<legend>Parent / Guradian's Information</legend>
					<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="14%" valign="top"  align="left">Mr.</TD>
					  <TD width="32%" valign="top"  align="left"><input type="text" name="PaMr" value="<?PHP echo $PaMr; ?>"/></TD>
					  <TD width="9%" valign="top"  align="left">City&nbsp;&nbsp;</TD>
					  <TD width="45%" valign="top"  align="left">
						<select name="OptCity">
						<option value="0" selected="selected">&nbsp;</option>
<?PHP
								$query = "select ID,CityName from mcity order by CityName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$CityID = $row["ID"];
										$CityName = $row["CityName"];
										if($OptCity =="$CityID"){
?>
											<option value="<?PHP echo $CityID; ?>" selected="selected"><?PHP echo $CityName; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $CityID; ?>"><?PHP echo $CityName; ?></option>
<?PHP
										}
									}
								}
?>

					      </select>
					  </TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Mrs. </TD>
					  <TD width="32%" valign="top"  align="left"><input type="text" name="PaMrs" value="<?PHP echo $PaMrs; ?>"/></TD>
					  <TD width="9%" valign="top"  align="left">State&nbsp;</TD>
					  <TD width="45%" valign="top"  align="left">
							<SELECT name="Optstate">
								<OPTION value="">Choose State</OPTION>
<?PHP
								if($Optstate != ""){
?>
									<option value="<?PHP echo $Optstate; ?>" selected="selected"><?PHP echo $Optstate; ?></option>
<?PHP
								}
?>
								<OPTION value="Abia">Abia</OPTION>
								<OPTION value="Adamawa">Adamawa</OPTION>
								<OPTION value="Akwa Ibom">Akwa Ibom</OPTION>
								<OPTION value="Anambra">Anambra</OPTION>
								<OPTION value="Bauchi">Bauchi</OPTION>
								<OPTION value="Bayelsa">Bayelsa</OPTION>
								<OPTION value="Benue">Benue</OPTION>
								<OPTION value="Borno">Borno</OPTION>
								<OPTION value="Cross River">Cross River</OPTION>
								<OPTION value="Delta">Delta</OPTION>
								<OPTION value="Ebonyi">Ebonyi</OPTION>
								<OPTION value="Edo">Edo</OPTION>
								<OPTION value="Ekiti">Ekiti</OPTION>
								<OPTION value="Enugu">Enugu</OPTION>
								<OPTION value="Federal Capital Territory">Federal Capital Territory</OPTION>
								<OPTION value="Gombe">Gombe</OPTION>
								<OPTION value="Imo">Imo</OPTION>
								<OPTION value="Jigawa">Jigawa</OPTION>
								<OPTION value="Kaduna">Kaduna</OPTION>
								<OPTION value="Kano">Kano</OPTION>
								<OPTION value="Katsina">Katsina</OPTION>
								<OPTION value="Kebbi">Kebbi</OPTION>
								<OPTION value="Kogi">Kogi</OPTION>
								<OPTION value="Kwara">Kwara</OPTION>
								<OPTION value="Lagos">Lagos</OPTION>
								<OPTION value="Nassarawa">Nassarawa</OPTION>
								<OPTION value="Niger">Niger</OPTION>
								<OPTION value="Ogun">Ogun</OPTION>
								<OPTION value="Ondo">Ondo</OPTION>
								<OPTION value="Osun">Osun</OPTION>
								<OPTION value="Oyo">Oyo</OPTION>
								<OPTION value="Plateau">Plateau</OPTION>
								<OPTION value="Rivers">Rivers</OPTION>
								<OPTION value="Sokoto">Sokoto</OPTION>
								<OPTION value="Taraba">Taraba</OPTION>
								<OPTION value="Yobe">Yobe</OPTION>
								<OPTION value="Zamfara">Zamfara</OPTION>
							</SELECT>
					  </TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Phone No&nbsp;</TD>
					  <TD width="32%" valign="top"  align="left">
					    <input type="text" name="PhoneNo" value="<?PHP echo $PhoneNo; ?>"/>					    </TD>
					  <TD width="9%" valign="top"  align="left">E-Mail&nbsp;</TD>
					  <TD width="45%" valign="top"  align="left"><input type="text" name="Email" value="<?PHP echo $Email; ?>"/></TD>
					</TR>
					</TBODY>
					</TABLE>
					</fieldset>
					<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
						 <TD colspan="4">
						  <div align="center"><br>
							 <input type="hidden" name="SelRegisID" value="<?PHP echo $regisNo; ?>">
							 <input name="regmaster" type="submit" id="regmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="regmaster" type="submit" id="regmaster" value="Update">
                             <?PHP 
							 if($status2 == '0')
							 {?>
                             <input name="admit" type="submit" id="admit" value="Admit" >
							 <?PHP } else {?>
							 <input name="admit" type="submit" id="admit" value="Admit" disabled >
							 <?PHP }?>
                             
						     <input type="reset" name="Reset" value="Reset">
						   </div>						  </TD>
					</TR>
					</TBODY>
					</TABLE>
					
					<div style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps; text-align:left"><br>* Required Fields </div>
					<br>
					<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Search Registration records/Click on an Unadmitted Student to Admit/Click on an Entry to Edit</strong></div></TD>
					</TR>
					<TR>
					  <TD width="19%" valign="top"  align="left"> 
					  	
<?PHP
						if($Optsearch == "Un-Admitted"){
?>
					    	<input type="radio" name="Optsearch" value="Un-Admitted" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">Un-Admitted
          <input name="SubmitSearch" type="submit" id="Search" value="Go">                  
<?PHP
						}else{
?>
							<input type="radio" name="Optsearch" value="Un-Admitted" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
          
                        Un-Admitted
                        <input name="SubmitSearch" type="submit" id="Search" value="Go" disabled="disabled">
                        
                        <?PHP
						}
?>
                        </TD>
					  <TD width="33%" valign="top"  align="left">
<?PHP
						if($Optsearch == "Class Wise"){
?>
					    	<input name="Optsearch" type="radio" value="Class Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
                        Class Wise					    
					    <select name="OptClassWise" >
							<option value="" selected="selected" >&nbsp;</option>
                            <?PHP
							$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
									if($OptClassWise ==$ClassID){
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
                        <input name="SubmitSearch" type="submit" id="Search" value="Go">
<?PHP
						}else{
?>
							<input name="Optsearch" type="radio" value="Class Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">

					    Class Wise					    
					    <select name="OptClassWise" disabled="disabled">
							<option value="Class Name" selected="selected" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>


					    </select><input name="SubmitSearch" type="submit" id="Search" value="Go" disabled="disabled">
                        <?PHP
						}
?>                          </TD>
					  <TD width="48%" colspan="2"  align="left" valign="top">
<?PHP
						if($Optsearch == "Name Wise"){
?>
					    	<input name="Optsearch" type="radio" value="Name Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
                       Name Wise 
					    <input name="Search_Key" type="text" size="30" value="<?PHP echo $Search_Key; ?>"/>
						<input name="SubmitSearch" type="submit" id="Search" value="Go">      
<?PHP
						}else{
?>
							<input name="Optsearch" type="radio" value="Name Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">

					    Name Wise 
					    <input name="Search_Key" type="text" size="30" value="<?PHP echo $Search_Key; ?>" disabled= "disabled"/>
						<input name="SubmitSearch" type="submit" id="Search" value="Go" disabled="disabled">
 <?PHP
						}
?>
                        </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4">
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="48" bgcolor="#F4F4F4"><div align="center" class="style21">
                              <label>
                              <input type="checkbox" name="ChkAllStud" value="checkbox" onClick="javascript:setTimeout('__doPostBack(\'ChkAllStud\',\'\')', 0)" <?PHP echo $chkStudent; ?>>
                              </label>
                              All</div></td>
                            <td width="53" bgcolor="#F4F4F4"><div align="center" class="style21">Reg. No.</div></td>
                            <td width="89" bgcolor="#F4F4F4"><div align="center"><strong>Status</strong></div></td>
							<td width="104" bgcolor="#F4F4F4"><div align="center"><strong>Class</strong></div></td>
							<td width="171" bgcolor="#F4F4F4"><div align="center"><strong>Name</strong></div></td>
							<td width="101" bgcolor="#F4F4F4"><div align="center"><strong>Phone</strong></div></td>
							<td width="77" bgcolor="#F4F4F4"><div align="center"><strong>Reg. Fee</strong></div></td>
                          </tr>
                          
<?PHP
							$counter_reg = 0;
							$query2 = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_reg = $rstart;
							}else{
								$counter_reg = $rstart-1;
							}
							$counter = 0;
							if($SubmitSearch == 'true')
							{
								//$Optsearch = $_POST['Optsearch'];
								if($Optsearch == "Un-Admitted"){
									$query3 = "select * from tbregistration where Status = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
									$num_rows = mysql_num_rows($result3);
									$query4 = "select * from tbregistration where Status = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
									$result4 = mysql_query($query4,$conn);
									$num_rows2 = mysql_num_rows($result4);
							
							if ($num_rows <= 0 ) { ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$regisID = $row["ID"];
									$Stu_ClassID = $row["Stu_ClassID"];
									$Stu_FirstName = $row["Stu_FirstName"];
									$Stu_MidName = $row["Stu_MidName"];
									$Stu_LastName = $row["Stu_LastName"];
									$FullName = $Stu_FirstName." ".$Stu_MidName." ".$Stu_LastName;
									$Stu_Phone = $row["Stu_Phone"];
									$RegFee = $row["RegFee"];
									$Status = $row["Status"];
									if($Status==0){
										$Status = "Un-Admitted";
										$bgColor="#FFBF80";
										$Status1 = 0;
									}else{
										$Status = "Admitted";
										$bgColor="#cccccc";
									}
									//if($regisNo == ""){
								if($bgColor == "#FFBF80"){
									 
									
?>
									  <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										<input name="chkRegisID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $regisID; ?>" <?PHP echo $chkStudent; ?>></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $regisID; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Status; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Stu_Phone; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo number_format($RegFee,2); ?></a></div></td>
									  </tr>
<?PHP
								}else{
?>
 <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										</td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $regisID; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo GetClassName($Stu_ClassID); ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $FullName; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Stu_Phone; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo number_format($RegFee,2); ?></div></td>
									  </tr>
<?php                              
                                   } 
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>">Next</a> </p> <?PHP 
								}elseif($Optsearch == "Class Wise"){
									
									$query3 = "select * from tbregistration where Stu_ClassID = '$OptClassWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
			    $result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select * from tbregistration where Stu_ClassID = '$OptClassWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
			    $result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows <= 0 ) { ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$regisID = $row["ID"];
									$Stu_ClassID = $row["Stu_ClassID"];
									$Stu_FirstName = $row["Stu_FirstName"];
									$Stu_MidName = $row["Stu_MidName"];
									$Stu_LastName = $row["Stu_LastName"];
									$FullName = $Stu_FirstName." ".$Stu_MidName." ".$Stu_LastName;
									$Stu_Phone = $row["Stu_Phone"];
									$RegFee = $row["RegFee"];
									$Status = $row["Status"];
									if($Status==0){
										$Status = "Un-Admitted";
										$bgColor="#FFBF80";
										$Status1 = 0;
									}else{
										$Status = "Admitted";
										$bgColor="#cccccc";
									}
									//if($regisNo == ""){
								if($bgColor == "#FFBF80"){
									 
									
?>
									  <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										<input name="chkRegisID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $regisID; ?>" <?PHP echo $chkStudent; ?>></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $regisID; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Status; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Stu_Phone; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo number_format($RegFee,2); ?></a></div></td>
									  </tr>
<?PHP
								}else{
?>
 <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										</td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $regisID; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo GetClassName($Stu_ClassID); ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $FullName; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Stu_Phone; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo number_format($RegFee,2); ?></div></td>
									  </tr>
<?php                              
                                   } 
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&OptClassWise=<?PHP echo $OptClassWise; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&OptClassWise=<?PHP echo $OptClassWise; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&OptClassWise=<?PHP echo $OptClassWise; ?>">Next</a> </p>   <?PHP 						
								}elseif($Optsearch == "Name Wise"){
									
									$query3 = "select * from tbregistration where INSTR(Stu_FirstName,'$Search_Key') or INSTR(Stu_MidName,'$Search_Key') or INSTR(Stu_LastName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select * from tbregistration where INSTR(Stu_FirstName,'$Search_Key') or INSTR(Stu_MidName,'$Search_Key') or INSTR(Stu_LastName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows <= 0 ) { ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$regisID = $row["ID"];
									$Stu_ClassID = $row["Stu_ClassID"];
									$Stu_FirstName = $row["Stu_FirstName"];
									$Stu_MidName = $row["Stu_MidName"];
									$Stu_LastName = $row["Stu_LastName"];
									$FullName = $Stu_FirstName." ".$Stu_MidName." ".$Stu_LastName;
									$Stu_Phone = $row["Stu_Phone"];
									$RegFee = $row["RegFee"];
									$Status = $row["Status"];
									if($Status==0){
										$Status = "Un-Admitted";
										$bgColor="#FFBF80";
										$Status1 = 0;
									}else{
										$Status = "Admitted";
										$bgColor="#cccccc";
									}
									//if($regisNo == ""){
								if($bgColor == "#FFBF80"){
									 
									
?>
									  <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										<input name="chkRegisID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $regisID; ?>" <?PHP echo $chkStudent; ?>></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $regisID; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Status; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Stu_Phone; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo number_format($RegFee,2); ?></a></div></td>
									  </tr>
<?PHP
								}else{
?>
 <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										</td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $regisID; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo GetClassName($Stu_ClassID); ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $FullName; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Stu_Phone; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo number_format($RegFee,2); ?></div></td>
									  </tr>
<?php                              
                                   } 
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Optsearch=<?PHP echo $Search_Key; ?>&Search_Key=<?PHP echo $Optsearch; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>  <?PHP		
								}else{
									$query3 = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
								}
							}else{
								$query3 = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
							                                                                         //}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							                                                                         //}
							$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows <= 0 ) { ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$regisID = $row["ID"];
									$Stu_ClassID = $row["Stu_ClassID"];
									$Stu_FirstName = $row["Stu_FirstName"];
									$Stu_MidName = $row["Stu_MidName"];
									$Stu_LastName = $row["Stu_LastName"];
									$FullName = $Stu_FirstName." ".$Stu_MidName." ".$Stu_LastName;
									$Stu_Phone = $row["Stu_Phone"];
									$RegFee = $row["RegFee"];
									$Status = $row["Status"];
									if($Status==0){
										$Status = "Un-Admitted";
										$bgColor="#FFBF80";
										$Status1 = 0;
									}else{
										$Status = "Admitted";
										$bgColor="#cccccc";
									}
									//if($regisNo == ""){
								if($bgColor == "#FFBF80"){
									 
									
?>
									  <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										<input name="chkRegisID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $regisID; ?>" <?PHP echo $chkStudent; ?>></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $regisID; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Status; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Stu_Phone; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo number_format($RegFee,2); ?></a></div></td>
									  </tr>
<?PHP
								}else{
?>
 <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										</td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $regisID; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo GetClassName($Stu_ClassID); ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $FullName; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Stu_Phone; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo number_format($RegFee,2); ?></div></td>
									  </tr>
<?php                              
                                   } 
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Registration&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p> <?PHP } ?> </TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalregis" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelRegisID" value="<?PHP echo $regisNo; ?>">
<?PHP
							if($regisNo !=""){
?>
							 	<input name="regmaster_Print" type="submit" id="regmaster_Print" value="Print Registration">
<?PHP
							}
?>
						     <input name="regmaster_delete" type="submit" id="regmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						   </div>						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Relation") {
?>
				<?PHP echo $errormsg; ?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong>
<?PHP
				echo $backpg;
?>
				</strong></div>
				<form name="form1" method="post" action="registration.php?subpg=Relation">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" align="left"><div align="right">Relation ID : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="RelID" type="text" size="55" value="<?PHP echo $RelID; ?>" disabled="disabled"></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">Relation : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="RelName" type="text" size="55" value="<?PHP echo $RelName; ?>"></TD>
					</TR>
					<TR>
						 <TD colspan="2">
							 <table width="539" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="178"><strong>TICK</strong></td>
									<td width="178"><strong>RELATION</strong></td>
									<td width="154"><strong>ID</strong></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbrelation order by relation";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Relation found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelRelID = $row["ID"];
										$RelationName = $row["relation"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkrelID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelRelID; ?>">
									           </div></td>
											<td><div align="center"><a href="registration.php?subpg=Relation&rel_id=<?PHP echo $SelRelID; ?>"><?PHP echo $RelationName; ?></a></div></td>
											<td><div align="center"><a href="registration.php?subpg=Relation&rel_id=<?PHP echo $SelRelID; ?>"><?PHP echo $counter; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  </table>
						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						 <div align="center">
						   	 <input type="hidden" name="TotalRel" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelRelID" value="<?PHP echo $RelID; ?>">
						     <input name="Relmaster" type="submit" id="Relmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="Relmaster_delete" type="submit" id="Relmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Relmaster" type="submit" id="Relmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Admit Registered Student") {
?>
<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Search Registration records/Click on an Unadmitted Student to Admit</strong></div></TD>
					</TR>
					<TR>
					  <TD width="19%" valign="top"  align="left"> 
					  	
<?PHP
						if($Optsearch == "Un-Admitted"){
?>
					    	<input type="radio" name="Optsearch" value="Un-Admitted" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">Un-Admitted
          <input name="SubmitSearch" type="submit" id="Search" value="Go">                  
<?PHP
						}else{
?>
							<input type="radio" name="Optsearch" value="Un-Admitted" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
          
                        Un-Admitted
                        <input name="SubmitSearch" type="submit" id="Search" value="Go" disabled="disabled">
                        
                        <?PHP
						}
?>
                        </TD>
					  <TD width="33%" valign="top"  align="left">
<?PHP
						if($Optsearch == "Class Wise"){
?>
					    	<input name="Optsearch" type="radio" value="Class Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
                        Class Wise					    
					    <select name="OptClassWise" >
							<option value="" selected="selected" >&nbsp;</option>
                            <?PHP
							$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
									if($OptClassWise ==$ClassID){
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
                        <input name="SubmitSearch" type="submit" id="Search" value="Go">
<?PHP
						}else{
?>
							<input name="Optsearch" type="radio" value="Class Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">

					    Class Wise					    
					    <select name="OptClassWise" disabled="disabled">
							<option value="Class Name" selected="selected" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>


					    </select><input name="SubmitSearch" type="submit" id="Search" value="Go" disabled="disabled">
                        <?PHP
						}
?>                          </TD>
					  <TD width="48%" colspan="2"  align="left" valign="top">
<?PHP
						if($Optsearch == "Name Wise"){
?>
					    	<input name="Optsearch" type="radio" value="Name Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
                       Name Wise 
					    <input name="Search_Key" type="text" size="30" value="<?PHP echo $Search_Key; ?>"/>
						<input name="SubmitSearch" type="submit" id="Search" value="Go">      
<?PHP
						}else{
?>
							<input name="Optsearch" type="radio" value="Name Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">

					    Name Wise 
					    <input name="Search_Key" type="text" size="30" value="<?PHP echo $Search_Key; ?>" disabled= "disabled"/>
						<input name="SubmitSearch" type="submit" id="Search" value="Go" disabled="disabled">
 <?PHP
						}
?>
                        </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4">
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="48" bgcolor="#F4F4F4"><div align="center" class="style21">
                              <label>
                              <input type="checkbox" name="ChkAllStud" value="checkbox" onClick="javascript:setTimeout('__doPostBack(\'ChkAllStud\',\'\')', 0)" <?PHP echo $chkStudent; ?>>
                              </label>
                              All</div></td>
                            <td width="53" bgcolor="#F4F4F4"><div align="center" class="style21">Reg. No.</div></td>
                            <td width="89" bgcolor="#F4F4F4"><div align="center"><strong>Status</strong></div></td>
							<td width="104" bgcolor="#F4F4F4"><div align="center"><strong>Class</strong></div></td>
							<td width="171" bgcolor="#F4F4F4"><div align="center"><strong>Name</strong></div></td>
							<td width="101" bgcolor="#F4F4F4"><div align="center"><strong>Phone</strong></div></td>
							<td width="77" bgcolor="#F4F4F4"><div align="center"><strong>Reg. Fee</strong></div></td>
                          </tr>
<?PHP
							$counter_reg = 0;
							$query2 = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_reg = $rstart;
							}else{
								$counter_reg = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Optsearch = $_POST['Optsearch'];
								if($Optsearch == "Un-Admitted"){
									$query3 = "select * from tbregistration where Status = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
								}elseif($Optsearch == "Class Wise"){
									$OptClassWise = $_POST['OptClassWise'];
									$query3 = "select * from tbregistration where Stu_ClassID = '$OptClassWise' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
								}elseif($Optsearch == "Name Wise"){
									$Search_Key = $_POST['Search_Key'];
									$query3 = "select * from tbregistration where INSTR(Stu_FirstName,'$Search_Key') or INSTR(Stu_MidName,'$Search_Key') or INSTR(Stu_LastName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
								}else{
									$query3 = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
								}
							}else{
								$query3 = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID desc LIMIT $rstart,$rend";
							                                                         }
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "No registration records found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$regisID = $row["ID"];
									$Stu_ClassID = $row["Stu_ClassID"];
									$Stu_FirstName = $row["Stu_FirstName"];
									$Stu_MidName = $row["Stu_MidName"];
									$Stu_LastName = $row["Stu_LastName"];
									$FullName = $Stu_FirstName." ".$Stu_MidName." ".$Stu_LastName;
									$Stu_Phone = $row["Stu_Phone"];
									$RegFee = $row["RegFee"];
									$Status = $row["Status"];
									if($Status==0){
										$Status = "Un-Admitted";
										$bgColor="#FFBF80";
										$Status1 = 0;
									}else{
										$Status = "Admitted";
										$bgColor="#cccccc";
									}
									//if($regisNo == ""){
					if($bgColor == "#FFBF80"){
									 
									
?>
              <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										<input name="chkRegisID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $regisID; ?>" <?PHP echo $chkStudent; ?>></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $regisID; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Status; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $Stu_Phone; ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="registration.php?subpg=Registration&regis_id=<?PHP echo $regisID; ?>"><?PHP echo number_format($RegFee,2); ?></a></div></td>
									  </tr>
<?PHP
								}else{
?>
									 
 <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center">
										</td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $regisID; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo GetClassName($Stu_ClassID); ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $FullName; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Stu_Phone; ?></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo number_format($RegFee,2); ?></div></td>
									  </tr>
<?php                              
                                   } 
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Admit Registered Student&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Admit Registered Student&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="registration.php?subpg=Admit Registered Student&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalregis" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelRegisID" value="<?PHP echo $regisNo; ?>">
						  </TD>
					</TR>
				</TBODY>
				</TABLE>

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
