<?PHP
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
	global $conn;
	global $dbname;
	global $errormsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'updateXML.php';
	include 'formatstring.php';
	include 'function.php';
	
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Utilities";
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
	if(isset($_POST['userprofile']))
	{
		$PageHasError = 0;
		$ProfileName = $_POST['ProfileName'];
		$Remark = $_POST['Remark'];
		$UserProID = $_POST['UserProID'];
		$UPID = $_POST['UserProID'];
		if(!$_POST['ProfileName']){
			$errormsg = "<font color = red size = 1>Profile Name is empty.</font>";
			$PageHasError = 1;
		}elseif(strlen($_POST['ProfileName'])>45){
			$errormsg = "<font color = red size = 1>Sorry, Profile Name is longer than 45 characters, please shorten it.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$query = "select * from tbuserprofile where Profile_Name = '$ProfileName'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) {
			  $errormsg = "<font color = red size = 1>The User profile you are trying to save already exist.</font>";
			}
			else 
			{
				if($_POST['userprofile']=="Create New"){
					$num_rows = 0;
					$q = "Insert into tbuserprofile(Profile_Name,Remarks) Values ('$ProfileName','$Remark')";
					$result = mysql_query($q,$conn);
					
					$errormsg = "<font color = blue size = 1>Record saved successfully.</font>";
					$ProfileName = "";
					$Remark = "";
					$UPID = "";
				}elseif($_POST['userprofile']=="Update"){
					$q = "update tbuserprofile set Profile_Name = '$ProfileName', Remarks = '$Remark' where ID = '$UserProID'";
					$result = mysql_query($q,$conn);
					
					$errormsg = "<font color = blue size = 1>Record updated successfully.</font>";
					$ProfileName = "";
					$Remark = "";
					$UPID = "";
				}
			}
		}
	}
	if(isset($_GET['id']))
	{
		$SelProfileID = $_GET['id'];
		$query = "select * from tbuserprofile where ID='$SelProfileID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$UPID  = $dbarray['ID'];
		$ProfileName  = $dbarray['Profile_Name'];
		$Remark  = $dbarray['Remarks'];
	}
	if(isset($_POST['userprofile_delete']))
	{
		$Total = $_POST['Total'];
		
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkProID'.$i]))
			{
				$ProID = $_POST['chkProID'.$i];
				$q = "Delete From tbuserprofile where ID = '$ProID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SubmitProfileRight']))
	{
		$SelProfileID = $_POST['Sel_Profile'];
		$q = "Delete From tbseluserprofile where UserProfileID = '$SelProfileID'";
		$result = mysql_query($q,$conn);
		//System Setup
		for($i=0;$i<=9;$i++){
			if(isset( $_POST['MS_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('MS_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Admission
		for($i=0;$i<=5;$i++){
			if(isset( $_POST['Ad_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('Ad_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Examination
		for($i=0;$i<=9;$i++){
			if(isset( $_POST['Ex_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('Ex_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Fee
		for($i=0;$i<=3;$i++){
			if(isset( $_POST['fee_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('fee_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Library
		for($i=0;$i<=4;$i++){
			if(isset( $_POST['Lib_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('Lib_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Time Table
		for($i=0;$i<=8;$i++){
			if(isset( $_POST['TT_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('TT_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Attendance
		for($i=0;$i<=3;$i++){
			if(isset( $_POST['Att_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('Att_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Pay Roll
		for($i=0;$i<=5;$i++){
			if(isset( $_POST['PR_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('PR_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Report Center
		for($i=0;$i<=7;$i++){
			if(isset( $_POST['Rpt_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('Rpt_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		//Utilities
		for($i=0;$i<=9;$i++){
			if(isset( $_POST['Uti_'.$i]))
			{
				$q = "Insert into tbseluserprofile(Profile_right,UserProfileID) Values ('Uti_$i','$SelProfileID')";
				$result = mysql_query($q,$conn);
			}
		}
		$SelProfileID = "";
	}
	include 'proright.php';
	if(isset($_POST['userMas']))
	{
		$PageHasError = 0;
		$UsrName = $_POST['UsrName'];
		$UsrPwd = $_POST['UsrPwd'];
		$Sel_UserProfile = $_POST['Sel_UserProfile'];
		$SelUsrID = $_POST['SelUsrID'];
		$OptEmpID = $_POST['OptEmpID'];
		
		if(!$_POST['UsrName']){
			$errormsg = "<font color = red size = 1>User Name is empty.</font>";
			$PageHasError = 1;
		}elseif(strlen($_POST['ProfileName'])>45){
			$errormsg = "<font color = red size = 1>Sorry, Profile Name is longer than 45 characters, please shorten it.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['UsrPwd']){
			$errormsg = "<font color = red size = 1>User Password is empty.</font>";
			$PageHasError = 1;
		}elseif(strlen($_POST['UsrPwd'])>45){
			$errormsg = "<font color = red size = 1>Sorry, Profile Name is longer than 45 characters, please shorten it.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptEmpID']){
			$errormsg = "<font color = red size = 1>Select Staff Profile.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$query = "select * from tbusermaster where UserName = '$UsrName'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if($_POST['userMas']=="Create New"){
				if ($num_rows > 0 ) {
					$errormsg = "<font color = red size = 1>Sorry, the user you are trying to add already exist. try a different username.</font>";
					$PageHasError = 1;
				}
				else 
				{
					$num_rows = 0;
					$q = "Insert into tbusermaster(UserName,UserPassword,UserprofileID,EmpID) Values ('$UsrName','$UsrPwd','$Sel_UserProfile','$OptEmpID')";
					$result = mysql_query($q,$conn);
					
					$errormsg = "<font color = blue size = 1>Record saved successfully.</font>";
					$UsrName = "";
					$UsrPwd = "";
					$Sel_UserProfile = "";
					$OptEmpID = "";
				}
			}elseif($_POST['userMas']=="Update"){
				$q = "update tbusermaster set UserName = '$UsrName', UserPassword = '$UsrPwd', UserprofileID = '$Sel_UserProfile',EmpID ='$OptEmpID'  where ID = '$SelUsrID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Record updated successfully.</font>";
				$UsrName = "";
				$UsrPwd = "";
				$Sel_UserProfile = "";
				$OptEmpID = "";
			}
		}
	}
	if(isset($_GET['uid']))
	{
		$SelUsrID = $_GET['uid'];
		$query = "select * from tbusermaster where ID='$SelUsrID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$UsrName  = $dbarray['UserName'];
		$UsrPwd  = $dbarray['UserPassword'];
		$Sel_UserProfile  = $dbarray['UserprofileID'];
		$OptEmpID  = $dbarray['EmpID'];
		
		$query = "select isTeaching from tbemployeemasters where ID='$OptEmpID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$isTeacher  = $dbarray['isTeaching'];
	}
	if(isset($_POST['userMas_delete']))
	{
		$Total = $_POST['TotalUser'];
		
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkUsrID'.$i]))
			{
				$UsrIDs = $_POST['chkUsrID'.$i];
				$q = "Delete From tbusermaster where ID = '$UsrIDs'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SubmitForm']))
	{
		$PageHasError = 0;
		$EventDate = DB_date($_POST['EventDate']);
		$EventItem = formatHTMLStr($_POST['EventItem']);
		$ItemLink = formatHTMLStr($_POST['ItemLink']);
		
		if(!$_POST['EventDate']){
			$errormsg = "<font color = red size = 1>Event Date is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['EventItem']){
			$errormsg = "<font color = red size = 1>Event Item is empty.</font>";
			$PageHasError = 1;
		}elseif(strlen($_POST['EventItem'])>60){
			$errormsg = "<font color = red size = 1>Sorry, Event Item is longer than 60 characters, please shorten it.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SubmitForm'] =="Submit"){
				$num_rows = 0;
				$query = "select ID from calendar where event_date = '$EventDate' And item = 'formatDatabaseStr($EventItem)'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Event you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into calendar(event_date,item,item_link) Values ('$EventDate','$EventItem','$ItemLink')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				}
			}elseif ($_POST['SubmitForm'] =="Update"){
				$SelEventID = $_POST['SelEvtID'];
				$q = "update calendar set event_date = '$EventDate', item = '$EventItem', item_link = '$ItemLink' where ID = '$SelEventID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
			}
			UpdateXML_File();
		}
	}
	if(isset($_GET['event_id']))
	{
		$SelEventID = $_GET['event_id'];
		$query = "select * from calendar where ID='$SelEventID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EventDate  = $dbarray['event_date'];
		$EventItem  = $dbarray['item'];
		$ItemLink  = $dbarray['item_link'];
	}
	if(isset($_POST['Event_delete']))
	{
		$Total = $_POST['TotalEvent'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCanID'.$i]))
			{
				$EvtIDs = $_POST['chkCanID'.$i];
				$q = "Delete From calendar where ID = '$EvtIDs'";
				$result = mysql_query($q,$conn);
				UpdateXML_File();
			}
		}
	}
	if(isset($_POST['isTeacher']))
	{	
		$isTeacher = $_POST['isTeacher'];
		$SelUsrID = $_POST['SelUsrID'];
		$UsrName = $_POST['UsrName'];
		$UsrPwd = $_POST['UsrPwd'];
		$Sel_UserProfile = $_POST['Sel_UserProfile'];
		$OptEmpID = $_POST['OptEmpID'];
	}
	if(isset($_POST['SubmitPwd']))
	{
		$oldPwd = $_POST['oldPwd'];
		$newPwd = $_POST['newPwd'];
		$conPwd = $_POST['conPwd'];
		if(!$_POST['oldPwd']){
			$errormsg = "<br><font color = red size = 1>Old Password is empty.</font>";
			$PageHasError = 1;
	   }
	   
	   if(!$_POST['newPwd']){
			$errormsg = "<br><font color = red size = 1>New Password is empty.</font>";
			$PageHasError = 1;
	   }
	   else {
		   $_POST['newPwd'] = trim($_POST['newPwd']);
		   if(strlen($_POST['newPwd']) > 10){
				$errormsg = "<br><font color = red size = 1>Sorry, the New Password is longer than 10 characters, please shorten it.</font>";
				$PageHasError = 1;
		   }
	   }
	   
	  if(!$_POST['conPwd']){
			$errormsg = "<br><font color = red size = 1>Confirm Password is empty.</font>";
			$PageHasError = 1;
	   }
	   else {
		   $_POST['conPwd'] = trim($_POST['conPwd']);
		   if(strlen($_POST['conPwd']) > 10){
				$errormsg = "<br><font color = red size = 1>Sorry, the Confirm Password is longer than 10 characters, please shorten it.</font>";
				$PageHasError = 1;
		   }
	   }
	   
	   if($newPwd != $conPwd){
			$errormsg = "<br><font color = red size = 1>Invalid New Password entry. </font>";
			$PageHasError = 1;

	   }
	   if ($PageHasError == 0)
	   {
			$num_rows = 0;
			$query = "select ID from tbusermaster where UserName='$userNames'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) 
			{
				while ($row = mysql_fetch_array($result)) 
				{
					$oldPwdID = $row["ID"];
				}
				$q = "update tbusermaster set UserPassword = '$newPwd' where ID = '$oldPwdID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<br><font color = blue size = 1>Password was successfully change.</font>";
				$oldPwd = "";
				$newPwd = "";
				$conPwd = "";
			}
			else {
				$DisplayMsg = "<br><font color = red size = 1>Invalid New Password entry. </font>";
				$PageHasError = 1;
	   		}
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
<style type="text/css">
td img {display: block;}
</style>

<SCRIPT 
src="css/jquery-1.2.3.min.js" 
type=text/javascript></SCRIPT>

<SCRIPT 
src="css/menu.js" 
type=text/JavaScript></SCRIPT>

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
			  <TD width="219" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="859" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" align="Center" style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?> - <?PHP echo $SelProName; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "User Profile") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="utilities.php?subpg=User Profile">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>PROFILE NAME </strong></td>
							  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbuserprofile order by Profile_Name";
								$result = mysql_query($query,$conn);
								//$dbarray = mysql_fetch_array($result);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Profile found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$profileID = $row["ID"];
										$Profile_Name = $row["Profile_Name"];
										$Remarks = $row["Remarks"];
			
?>
										  <tr>
											<td>
											   <input type="hidden" name="ProID<?PHP echo $counter; ?>" value="<?PHP echo $profileID; ?>">
					  						   <input name="chkProID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $profileID; ?>">
											</td>
											<td><div align="left"><a href="utilities.php?subpg=User%20Profile&id=<?PHP echo $profileID; ?>"><?PHP echo $Profile_Name; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  <tr>
								<td>&nbsp;</td>
								<td class="hlight">&nbsp;</td>
							  </tr>
							</table>					  
							</TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="86">ID :</td>
								<td width="294"><input name="UPID" type="text" size="15" disabled="disabled" value="<?PHP echo $UPID; ?>"></td>
							  </tr>
							  <tr>
								<td>Profile Name  :</td>
								<td><input name="ProfileName" type="text" size="50"  value="<?PHP echo $ProfileName; ?>"></td>
							  </tr>
							  <tr>
								<td>Remark :</td>
								<td><textarea name="Remark" cols="55" rows="5"><?PHP echo $Remark; ?></textarea></td>
							  </tr>
							</table>					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						   <div align="center">
						   	 <input type="hidden" name="Total" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="UserProID" value="<?PHP echo $UPID; ?>">
						     <input name="userprofile" type="submit" id="userprofile" value="Create New">
						     <input name="userprofile_delete" type="submit" id="userprofile_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="userprofile" type="submit" id="userprofile" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						       </div></TD>
					</TR>
					<TR>
						 <TD colspan="2" align="left">
						 	<p><span class="hlight"><strong>DELETE</strong>:</span> Tick on the respective check box and press delete button to remove profile name from the list</p>
							<p><span class="hlight"><strong>UPDATE:</strong></span> Click on the profile name, make necessary adjustment and once you're done, press the update button</p>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Profile Right") {
?>
				<form name="form1" method="post" action="utilities.php?subpg=Profile Right" onSubmit="javascript:return WebForm_OnSubmit();" id="form1">
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
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="27%" align="left" valign="top">
							<table width="182" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr bgcolor="#ECE9D8">
								<td width="182"><strong>PROFILE NAME </strong></td>
							  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbuserprofile order by Profile_Name";
								$result = mysql_query($query,$conn);
								//$dbarray = mysql_fetch_array($result);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Profile found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$profileID = $row["ID"];
										$Profile_Name = $row["Profile_Name"];
										$Remarks = $row["Remarks"];
			
?>
										  <tr>
											<td><div align="left"><a href="utilities.php?subpg=Profile Right&PR_id=<?PHP echo $profileID; ?>"><?PHP echo $Profile_Name; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  <tr>
								<td>&nbsp;</td>
								<td class="hlight">&nbsp;</td>
							  </tr>
							</table>
					  </TD>
					  <TD width="73%" valign="top"  align="left">
					  <input type="checkbox" name="SelTick" value="checkbox" onClick="javascript:setTimeout('__doPostBack(\'SelTick\',\'\')', 0)"> Tick All
							<input type="hidden" name="Sel_Profile" value="<?PHP echo $SelProfileID; ?>">
					  		<table width="496" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="453">
									<input name="MS_0" type="checkbox" value="on" <?PHP echo $MS_0; ?>/>&nbsp;&nbsp;<strong>System Setup</strong>
									<p style="margin-left:30PX;"><input name="MS_1" type="checkbox" value="on" <?PHP echo $MS_1; ?>/><SPAN>Application Setup</SPAN> <br>
									<input name="MS_2" type="checkbox" value="on" <?PHP echo $MS_3; ?>/>City Master<br>
									<input name="MS_3" type="checkbox" value="on" <?PHP echo $MS_4; ?>/>Document Master<br>
									<input name="MS_4" type="checkbox" value="on" <?PHP echo $MS_5; ?>/>Religion Master<br>
									<input name="MS_5" type="checkbox" value="on" <?PHP echo $MS_6; ?>/>School Charges<br>
									<input name="MS_6" type="checkbox" value="on" <?PHP echo $MS_7; ?>/>Class Master<br>
									<input name="MS_7" type="checkbox" value="on" <?PHP echo $MS_8; ?>/>Leave Master<br>
									<input name="MS_8" type="checkbox" value="on" <?PHP echo $MS_9; ?>/>Session<br>
									<input name="MS_9" type="checkbox" value="on" <?PHP echo $MS_9; ?>/>Active Term / Section<br>
									</P>
									<input name="Ad_0" type="checkbox" value="on" <?PHP echo $Ad_0; ?>/>&nbsp;&nbsp;<strong>Admission</strong>
									<p style="margin-left:30PX;"><input name="Ad_1" type="checkbox" value="on" <?PHP echo $Ad_1; ?>/>
									Enquiry<br>
									<input name="Ad_2" type="checkbox" value="on" <?PHP echo $Ad_2; ?>/>Registration<br>
									<input name="Ad_3" type="checkbox" value="on" <?PHP echo $Ad_3; ?>/>Admission<br>
									<input name="Ad_4" type="checkbox" value="on" <?PHP echo $Ad_4; ?>/>Edit Student Info<br>
									<input name="Ad_5" type="checkbox" value="on" <?PHP echo $Ad_5; ?>/>School Leaving certificate<br>
									</p>
									<input name="Ex_0" type="checkbox" value="on" <?PHP echo $Ex_0; ?>/><SPAN><strong>Examination</strong></SPAN>
									<p style="margin-left:30PX;"><input name="Ex_1" type="checkbox" value="on" <?PHP echo $Ex_1; ?>/>Examination Master<br>
									<input name="Ex_2" type="checkbox" value="on" <?PHP echo $Ex_2; ?>/>Manage Weighted Result<br>
									<input name="Ex_3" type="checkbox" value="on" <?PHP echo $Ex_3; ?>/>Grade master<br>
									<input name="Ex_4" type="checkbox" value="on" <?PHP echo $Ex_4; ?>/>Mark Setup<br>
									<input name="Ex_5" type="checkbox" value="on" <?PHP echo $Ex_5; ?>/>Student Performance<br>
									<input name="Ex_6" type="checkbox" value="on" <?PHP echo $Ex_6; ?>/>Progress Header<br>
									<input name="Ex_7" type="checkbox" value="on" <?PHP echo $Ex_7; ?> />Progress Sub Header<br>
									<input name="Ex_8" type="checkbox" value="on" <?PHP echo $Ex_8; ?>/>Progress Skill<br>
									<input name="Ex_9" type="checkbox" value="on" <?PHP echo $Ex_9; ?>/>Student Progress Report<br>
									</p>
									<input name="fee_0" type="checkbox" value="on" <?PHP echo $fee_0; ?>/><SPAN><strong>Fees</strong></SPAN>
									<p style="margin-left:30PX;"><input name="fee_1" type="checkbox" value="on" <?PHP echo $fee_1; ?>/>School Charges Master<br>
									<input name="fee_2" type="checkbox" value="on" <?PHP echo $fee_2; ?>/>Update Student Fee<br>
									<input name="fee_3" type="checkbox" value="on" <?PHP echo $fee_3; ?>/>Fee receipt<br>
									</p>
									<input name="Lib_0" type="checkbox" value="on" <?PHP echo $Lib_0; ?>/><SPAN><strong>Library</strong></SPAN>
									<p style="margin-left:30PX;"><input name="Lib_1" type="checkbox" value="on" <?PHP echo $Lib_1; ?>/>Library Setup<br>
									<input name="Lib_2" type="checkbox" value="on" <?PHP echo $Lib_2; ?>/>Book Master<br>
									<input name="Lib_3" type="checkbox" value="on" <?PHP echo $Lib_3; ?>/>Issue A Book<br>
									<input name="Lib_4" type="checkbox" value="on" <?PHP echo $Lib_4; ?>/>Receive A Book<br>
									</p>
								</td>
								<td width="453" valign="top">
									<input name="TT_0" type="checkbox" value="on" <?PHP echo $TT_0; ?>/>&nbsp;&nbsp;<strong>Time Table</strong>
									<p style="margin-left:30PX;"><input name="TT_1" type="checkbox" value="on" <?PHP echo $TT_1; ?>/>Subject Setup <br>
									<input name="TT_2" type="checkbox" value="on" <?PHP echo $TT_2; ?>/>Room Master <br>
									<input name="TT_3" type="checkbox" value="on" <?PHP echo $TT_3; ?>/>Teacher Workload<br>
									<input name="TT_4" type="checkbox" value="on" <?PHP echo $TT_4; ?>/>Edit time table<br>
									<input name="TT_5" type="checkbox" value="on" <?PHP echo $TT_5; ?>/>View time table<br>
									<input name="TT_6" type="checkbox" value="on" <?PHP echo $TT_6; ?>/>Look up adjustment<br>
									<input name="TT_7" type="checkbox" value="on" <?PHP echo $TT_7; ?>/>Teachers Duty Master<br>
									<input name="TT_8" type="checkbox" value="on" <?PHP echo $TT_8; ?>/>Assign Teachers Duty<br>
									
									</p>
									<input name="Att_0" type="checkbox" value="on" <?PHP echo $Att_0; ?>/><strong>Attendance</strong>
									<p style="margin-left:30PX;"><input name="Att_1" type="checkbox" value="on" <?PHP echo $Att_1; ?>/>Lecture attendance<br>
									<input name="Att_2" type="checkbox" value="on" <?PHP echo $Att_2; ?>/>Students<br>
									<input name="Att_3" type="checkbox" value="on" <?PHP echo $Att_3; ?>/>Employee<br>
									</p>
									<input name="PR_0" type="checkbox" value="on"  <?PHP echo $PR_0; ?>/><strong>Pay Roll</strong>
									<p style="margin-left:30PX;"><input name="PR_1" type="checkbox" value="on" <?PHP echo $PR_1; ?>/>Pay Roll Setup<br>
									<input name="PR_2" type="checkbox" value="on" <?PHP echo $PR_2; ?>/>Employee Master<br>
									<input name="PR_3" type="checkbox" value="on" <?PHP echo $PR_3; ?>/>Allowance Setup<br>
									<input name="PR_4" type="checkbox" value="on" <?PHP echo $PR_4; ?>/>Salary Detail<br>
									<input name="PR_5" type="checkbox" value="on" <?PHP echo $PR_5; ?>/>Generate Salary<br>
									</p>
									<input name="Rpt_0" type="checkbox" value="on" <?PHP echo $Rpt_0; ?>/><strong>Report Center</strong>
									<p style="margin-left:30PX;"><input name="Rpt_1" type="checkbox" value="on" <?PHP echo $Rpt_1; ?>/>Fees<br>
									<input name="Rpt_2" type="checkbox" value="on" <?PHP echo $Rpt_2; ?>/>Library<br>
									<input name="Rpt_3" type="checkbox" value="on" <?PHP echo $Rpt_3; ?>/>Examination<br>
									<input name="Rpt_4" type="checkbox" value="on" <?PHP echo $Rpt_4; ?>/>Attendance<br>
									<input name="Rpt_5" type="checkbox" value="on" <?PHP echo $Rpt_5; ?>/>Class<br>
									<input name="Rpt_6" type="checkbox" value="on" <?PHP echo $Rpt_6; ?>/>Student<br>
									<input name="Rpt_7" type="checkbox" value="on" <?PHP echo $Rpt_7; ?>/>Employee<br>
									</p>
									<input name="Uti_0" type="checkbox" value="on" <?PHP echo $Uti_0; ?>/><strong>Utilities</strong>
									<p style="margin-left:30PX;"><input name="Uti_1" type="checkbox" value="on"<?PHP echo $Uti_1; ?> />Pop Ups<br>
									<input name="Uti_2" type="checkbox" value="on" <?PHP echo $Uti_2; ?>/>Event Calendar<br>
									<input name="Uti_3" type="checkbox" value="on" <?PHP echo $Uti_3; ?>/>Remarks<br>
									<input name="Uti_4" type="checkbox" value="on" <?PHP echo $Uti_4; ?>/>User Profile<br>
									<input name="Uti_5" type="checkbox" value="on" <?PHP echo $Uti_5; ?>/>Profile Right<br>
									<input name="Uti_6" type="checkbox" value="on" <?PHP echo $Uti_6; ?>/>User master<br>
									<input name="Uti_7" type="checkbox" value="on" <?PHP echo $Uti_7; ?>/>Change Password<br>
									<input name="Uti_8" type="checkbox" value="on" <?PHP echo $Uti_8; ?>/>Hostel Management<br>
									<input name="Uti_9" type="checkbox" value="on" <?PHP echo $Uti_9; ?>/>Clinic Management<br>
									</p>
								</td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						   <div align="center">
						     <input name="SubmitProfileRight" type="submit" id="SubmitDoc" value="Create New">
						   </div></TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "User master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="utilities.php?subpg=User master">
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
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="263" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr bgcolor="#ECE9D8">
								<td width="30"><strong>TICK</strong></td>
								<td width="92"><strong>USER NAME </strong></td>
								<td width="111"><strong>PROFILE </strong></td>
							  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbusermaster order by UserName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No User found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$UserID = $row["ID"];
										$User_Name = $row["UserName"];
										$UserprofileID = $row["UserprofileID"];
										
										$query2 = "select Profile_Name from tbuserprofile where ID = '$UserprofileID'";
										$result2 = mysql_query($query2,$conn);
										$dbarray2 = mysql_fetch_array($result2);
										$User_Profile_Name = $dbarray2['Profile_Name'];
			
?>
										  <tr>
											<td>
											   <input type="hidden" name="UsrID<?PHP echo $counter; ?>" value="<?PHP echo $UserID; ?>">
					  						   <input name="chkUsrID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $UserID; ?>">											</td>
											<td><div align="left"><a href="utilities.php?subpg=User master&uid=<?PHP echo $UserID; ?>"><?PHP echo $User_Name; ?></a></div></td>
											<td><div align="left"><a href="utilities.php?subpg=User master&uid=<?PHP echo $UserID; ?>"><?PHP echo $User_Profile_Name; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  <tr>
								<td>&nbsp;</td>
								<td class="hlight">&nbsp;</td>
							  </tr>
							</table>							</TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							   <tr>
								<td width="86">ID :</td>
								<td width="294"><input name="SelUsrID" type="text" size="15" disabled="disabled" value="<?PHP echo $SelUsrID; ?>"></td>
							  </tr>
							  <tr>
								<td width="86">User Name  :</td>
								<td width="294"><input name="UsrName" type="text" size="50" value="<?PHP echo $UsrName; ?>"></td>
							  </tr>
							  <tr>
								<td>Password  :</td>
								<td><input name="UsrPwd" type="password" size="50"  value="<?PHP echo $UsrPwd; ?>"></td>
							  </tr>
							  <tr>
								<td>User Profile  :</td>
								<td>
								  <select name="Sel_UserProfile">
<?PHP
									$query = "select * from tbuserprofile order by Profile_Name";
									$result = mysql_query($query,$conn);
									//$dbarray = mysql_fetch_array($result);
									$num_rows = mysql_num_rows($result);
									if ($num_rows <= 0 ) {
										echo "<option value='0' selected>No Profile Found</option>";
									}
									else 
									{
										while ($row = mysql_fetch_array($result)) 
										{
											$profileID = $row["ID"];
											$Profile_Name = $row["Profile_Name"];
											if($Sel_UserProfile == $profileID){
?>
										  		<option value="<?PHP echo $profileID; ?>" selected><?PHP echo $Profile_Name; ?></option>
<?PHP
											}else{
?>
										  		<option value="<?PHP echo $profileID; ?>"><?PHP echo $Profile_Name; ?></option>
<?PHP
											}
									    }
								     }
?>
								    </select>								</td>
							  </tr>
							  <tr>
							  <TD width="41%" valign="top"  align="left">Teaching</TD>
							  <TD width="59%" valign="top"  align="left">
<?PHP
								if($isTeacher == 1){
?>
								  <input name="isTeacher" type="radio" value="1" checked="checked" onClick="javascript:setTimeout('__doPostBack(\'isTeacher\',\'\')', 0)">Yes 
								  <input name="isTeacher" type="radio" value="0" onClick="javascript:setTimeout('__doPostBack(\'isTeacher\',\'\')', 0)">No 
<?PHP
								}elseif($isTeacher == 0){
?>
								  <input name="isTeacher" type="radio" value="1" onClick="javascript:setTimeout('__doPostBack(\'isTeacher\',\'\')', 0)">Yes 
								  <input name="isTeacher" type="radio" value="0" checked="checked" onClick="javascript:setTimeout('__doPostBack(\'isTeacher\',\'\')', 0)">No 
<?PHP
								}
?>
							  </TD>
							  </tr>
							  <tr>
								  <TD width="41%" valign="top"  align="left"> Staff Profile</TD>
								  <TD width="59%" valign="top"  align="left">
							  		<select name="OptEmpID">
									<option value="" selected="selected">Select</option>
<?PHP
									if($isTeacher == 1){
										$query = "select ID,EmpName from tbemployeemasters where isTeaching = '1' order by EmpName";
									}else{
										$query = "select ID,EmpName from tbemployeemasters where isTeaching = '0' order by EmpName";
									}
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$EmpID = $row["ID"];
											$EmpName = $row["EmpName"];
											if($OptEmpID =="$EmpID"){
?>
												<option value="<?PHP echo $EmpID; ?>" selected="selected"><?PHP echo $EmpName; ?></option>
<?PHP
											}else{
?>
												<option value="<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></option>
<?PHP
											}
										}
									}
?>
						  		</select>&nbsp;</TD>
							</tr>
							</table>							
							</TD>
					</TR>
					<TR>
						 <TD colspan="2">&nbsp;						   </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						   <div align="center">
						   	 <input type="hidden" name="TotalUser" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelUsrID" value="<?PHP echo $SelUsrID; ?>">
						     <input name="userMas" type="submit" id="userMas" value="Create New">
						     <input name="userMas_delete" type="submit" id="userMas_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="userMas" type="submit" id="userMas" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>						 </TD>
					</TR>
					<TR>
						 <TD colspan="2" align="left">
						 	<p><span class="hlight"><strong>DELETE</strong>:</span> Tick on the respective check box and press delete button to remove profile name from the list</p>
							<p><span class="hlight"><strong>UPDATE:</strong></span> Click on the profile name, make necessary adjustment and once you're done, press the update button</p>						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif($SubPage=="Event Calendar"){
?>
				<form name="form1" method="post" action="utilities.php?subpg=Event Calendar">
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
					<TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
							<TBODY>
							<TR>
							<TD width=100% valign="top">
							  <IFRAME 
								name=portframe marginWidth=0 marginHeight=0
								src="activecalendar/data/cmcCalendar.php?css=css/ceramique_wide.css" frameBorder=0 width=100% 
								scrolling=no height=480></IFRAME>
								
								<TABLE cellSpacing=5 cellPadding=5 border=0 width="99%">
								<TBODY>
								<TR>
									<TD width=100% align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<select name="SelMonth" id="SelMonth"  onchange="javascript:setTimeout('__doPostBack(\'SelMonth\',\'\')', 0)">
											<option value="0" selected="selected">Select</option>
<?PHP
												if(isset($_POST['SelMonth']))
												{
?>
													<option value="<?PHP echo $_POST['SelMonth']; ?>" selected="selected"><?PHP echo Get_Month_Name($_POST['SelMonth']); ?></option>
<?PHP
												}
?>
											
											<option value="01">January</option>
											<option value="02">February</option>
											<option value="03">March</option>
											<option value="04">April</option>
											<option value="05">May</option>
											<option value="06">June</option>
											<option value="07">July</option>
											<option value="08">August</option>
											<option value="09">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
										<TABLE style="WIDTH: 98%">
										<TBODY>
										<TR>
										  <TD width="39%" height="236" align="left" valign="top">
												<table width="244" border="0" align="center" cellpadding="3" cellspacing="3">
												  <tr bgcolor="#ECE9D8">
													<td width="28"><strong>TICK</strong></td>
													<td width="195"><strong>Event Title </strong></td>
													<td width="195"><strong>Event Date </strong></td>
												  </tr>
<?PHP
												if(isset($_POST['SelMonth']))
												{
													$mDate = $_POST['SelMonth'];
												}else{
													$mDate = date('m');
												}
												$counter = 0;
												$query = "select * from calendar order by event_date";
												$result = mysql_query($query,$conn);
												//$dbarray = mysql_fetch_array($result);
												$num_rows = mysql_num_rows($result);
												if ($num_rows <= 0 ) {
													echo "No Calander found.";
												}
												else 
												{
													while ($row = mysql_fetch_array($result)) 
													{
														$CalanderID = $row["ID"];
														$event_date = User_date($row["event_date"]);
														$item = $row["item"];
														
														$arrDate=explode("-",$event_date);
														if($arrDate[1]==$mDate){
															$counter = $counter+1;
?>
														  <tr>
															<td>
															   <input type="hidden" name="CanID<?PHP echo $counter; ?>" value="<?PHP echo $CalanderID; ?>">
															   <input name="chkCanID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $CalanderID; ?>">
															</td>
															<td><div align="left"><a href="utilities.php?subpg=Event Calendar&event_id=<?PHP echo $CalanderID; ?>"><?PHP echo $item; ?></a></div></td>
															<td><div align="left"><a href="utilities.php?subpg=Event Calendar&event_id=<?PHP echo $CalanderID; ?>"><?PHP echo $event_date; ?></a></div></td>
														  </tr>
<?PHP
														}
													 }
												 }
?>
												</table>
										  </TD>
										  <TD width="61%" valign="top"  align="left">
												<TABLE cellSpacing=5 cellPadding=5 border=0 width="94%">
												<TBODY>
												<TR>
												<TD width=104>
												  <DIV align=right>Select Event Date</DIV></TD>
												<TD width="231"><input class="fil_ariane_passif" onClick="ds_sh(this);" name="EventDate" readonly="readonly" style="cursor: text" value="<?PHP echo $EventDate; ?>"/></TD></TR>
												<TR>
												<TD width=104><DIV align=right><span class="style1">* </span>Event Title</DIV></TD>
												<TD><textarea name="EventItem" class="fil_ariane_passif" cols="25" rows="3" id="EventItem"><?PHP echo $EventItem; ?></textarea></TD></TR>
												<TR>
												<TD width=104>
												  <div align="right">Item Link</div></TD>
												<TD>
<?PHP
												if($ItemLink == ""){
													$ItemLink = "http://#";
												}
?>
												<input name="ItemLink" type="text" class="fil_ariane_passif" id="ItemLink" size="40" value="<?PHP echo $ItemLink; ?>"></TD></TR>
												<TR>
												<TD colSpan=2 align="center">
												
												     <input type="hidden" name="TotalEvent" value="<?PHP echo $counter; ?>">
							 						<input type="hidden" name="SelEvtID" value="<?PHP echo $SelEventID; ?>">
													 <input type="submit" name="SubmitForm" id="SubmitForm" value="Submit">
													 <input name="Event_delete" type="submit" id="Event_delete" value="Delete"  onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						    						 <input name="SubmitForm" type="submit" id="SubmitForm" value="Update">
													 <input type="submit" name="SubmitNew" id="SubmitNew" value="Clear"></TD>
												</TR>
												</TBODY></TABLE>
										  </TD>
										  </TR>
									  </TBODY></TABLE>
									</TD>
								</TR>
								</TBODY>
								</TABLE>				
							</TD>
							</TR>
							</TBODY>
							</TABLE>
				</form>
<?PHP
		}elseif($SubPage=="Change Password"){
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="utilities.php?subpg=Change Password">
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
					<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
						 <TD colspan="2" align="center"><p>&nbsp;</p>
						 <table width="472" border="0" align="center" cellpadding="10" cellspacing="10">
                           <tr>
                             <td width="128"><strong>User Name  :</strong></td>
                             <td width="323"><?PHP echo $userNames; ?></td>
                           </tr>
                           <tr>
                             <td><strong>Old Password  :</strong></td>
                             <td><input name="oldPwd" type="password" size="20"  value="<?PHP echo $oldPwd; ?>"></td>
                           </tr>
                           <tr>
                             <td><strong>New Password   :</strong></td>
                             <td><input name="newPwd" type="password" size="20"  value="<?PHP echo $newPwd; ?>"></td>
                           </tr>
                           <tr>
                             <TD width="128" valign="top"  align="left"><strong>Confirm Password </strong></TD>
                             <TD width="323" valign="top"  align="left"><input name="conPwd" type="password" size="20"  value="<?PHP echo $conPwd; ?>"></TD>
                           </tr>
                         </table></TD>
					</TR>
					<TR>
						 <TD colspan="2">
						   <div align="center">
						     <input name="SubmitPwd" type="submit" id="SubmitPwd" value="Submit">
						     <input type="reset" name="Reset" value="Reset">
						   </div>						 </TD>
					</TR>
					<TR>
						 <TD colspan="2" align="left">
						 	<p>&nbsp;</p>
					  </TD>
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
