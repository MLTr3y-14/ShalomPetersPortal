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
	$Page = "Examination";
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
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['ChkAllClass']))
	{	
		//$OptClass = "";
		$ChkAllClass = $_POST['ChkAllClass'];
		$_POST['OptClass'] = "";
		$lockClass = "disabled='disabled'";
		$isChecked = "checked='checked'";
	}
	if(isset($_POST['headmaster']))
	{
		$PageHasError = 0;
		$ProgHeadID = $_POST['SelHeadID'];
		$OptClass = $_POST['OptClass'];
		$HeadInfo = $_POST['HeadInfo'];
		
		if(!isset($_POST['ChkAllClass']))
		{
			//GET ALL SUBJECTS
			if(!$_POST['OptClass']){
				$errormsg = "<font color = red size = 1>Select Class.</font>";
				$PageHasError = 1;
			}
		}
		
		if(!$_POST['HeadInfo']){
			$errormsg = "<font color = red size = 1>Header Info is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['headmaster'] =="Create New"){
				$num_rows = 0;
				if(isset($_POST['ChkAllClass']))
				{
					$query = "select ID from tbclassmaster order by Class_Name";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$ClassID = $row["ID"];
							$query1 = "select ID from tbprogheader where Description = '$HeadInfo' And class_id = '$ClassID'";
							$result1 = mysql_query($query1,$conn);
							$num_rows1 = mysql_num_rows($result1);
							if ($num_rows1 <= 0 ) 
							{
								$q = "Insert into tbprogheader(Description,class_id) Values ('$HeadInfo','$ClassID')";
								$result3 = mysql_query($q,$conn);
							}
						}
					}
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					$HeadInfo = "";
					$OptClass = "";
				}else{
					$query = "select ID from tbprogheader where Description = '$HeadInfo' and class_id ='$OptClass'";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) 
					{
						$errormsg = "<font color = red size = 1>The progress header you are trying to add already exist.</font>";
						$PageHasError = 1;
					}else {
						$q = "Insert into tbprogheader(Description,class_id) Values ('$HeadInfo','$OptClass')";
						$result = mysql_query($q,$conn);
						$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
						
						$HeadInfo = "";
						$OptClass = "";
					}
				}
			}elseif ($_POST['headmaster'] =="Update"){
				$q = "update tbprogheader set Description = '$HeadInfo',class_id = '$OptClass' where ID = '$ProgHeadID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$ProgHeadID = "";
				$HeadInfo = "";
				$OptClass = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['head_id']))
	{
		$ProgHeadID = $_GET['head_id'];
		$query = "select * from tbprogheader where ID='$ProgHeadID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$HeadInfo  = $dbarray['Description'];
		$OptClass  = $dbarray['class_id'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['headmasterr_delete']))
	{
		$Total = $_POST['Totalhead'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkHeadID'.$i]))
			{
				$chkHeadID = $_POST['chkHeadID'.$i];
				$q = "Delete From tbprogheader where ID = '$chkHeadID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SubmitSearch']))
	{
		$Search_Key = $_POST['Search_Key'];
	}
	if(isset($_POST['sheadmaster']))
	{
		$PageHasError = 0;
		$ProgsHeadID = $_POST['SelsHeadID'];
		$OptClass = $_POST['OptClass'];
		$OptHeader = $_POST['OptHeader'];
		$sHeadInfo = $_POST['sHeadInfo'];
		
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Class.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptHeader']){
			$errormsg = "<font color = red size = 1>Select Header.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['sHeadInfo']){
			$errormsg = "<font color = red size = 1>Sub Header Info is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['sheadmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbsubheader where Decription = '$sHeadInfo' and Head_id ='$OptHeader' and Class_id ='$OptClass'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The progress sub header you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbsubheader(Head_id,Decription,Class_id) Values ('$OptHeader','$sHeadInfo','$OptClass')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$sHeadInfo = "";
				}
			}elseif ($_POST['sheadmaster'] =="Update"){
				$q = "update tbsubheader set Head_id = '$OptHeader',Decription = '$sHeadInfo',Class_id = '$OptClass' where ID = '$ProgsHeadID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$sHeadInfo = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['shead_id']))
	{
		$ProgsHeadID = $_GET['shead_id'];
		$OptClass = $_GET['cid'];
		$OptHeader = $_GET['hid'];
		$_POST['OptClass'] = $_GET['cid'];
		$_POST['OptHeader'] = $_GET['hid'];
		$query = "select * from tbsubheader where ID='$ProgsHeadID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptHeader  = $dbarray['Head_id'];
		$sHeadInfo  = $dbarray['Decription'];
		$OptClass  = $dbarray['Class_id'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['sheadmasterr_delete']))
	{
		$Total = $_POST['Totalshead'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkSubheadID'.$i]))
			{
				$chkSubheadID = $_POST['chkSubheadID'.$i];
				$q = "Delete From tbsubheader where ID = '$chkSubheadID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SkillSetup']))
	{
		$PageHasError = 0;
		$ProgskillID = $_POST['SelskillID'];
		$OptClass = $_POST['OptClass'];
		$OptHeader = $_POST['OptHeader'];
		$OptSubHeader = $_POST['OptSubHeader'];
		$SkillDescrip = $_POST['SkillDescrip'];
		
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select Class.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptHeader']){
			$errormsg = "<font color = red size = 1>Select Header.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptSubHeader']){
			$errormsg = "<font color = red size = 1>Select Sub Header.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['SkillDescrip']){
			$errormsg = "<font color = red size = 1>Description is empty</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SkillSetup'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbproskills where Description = '$SkillDescrip' and Head_id ='$OptHeader' and SubHead_id = '$OptSubHeader' and Class_id ='$OptClass'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The progress skill you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbproskills(Head_id,SubHead_id,Description,Class_id) Values ('$OptHeader','$OptSubHeader','$SkillDescrip','$OptClass')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$SkillDescrip = "";
				}
			}elseif ($_POST['SkillSetup'] =="Update"){
				$q = "update tbproskills set Head_id = '$OptHeader',SubHead_id='$OptSubHeader',Description = '$SkillDescrip',Class_id = '$OptClass' where ID = '$ProgskillID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$SkillDescrip = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['skill_id']))
	{
		$ProgskillID = $_GET['skill_id'];
		$_POST['OptClass'] = $_GET['cid'];
		$_POST['OptHeader'] = $_GET['hid'];
		$_POST['OptSubHeader'] = $_GET['shid'];
		$query = "select * from tbproskills where ID='$ProgskillID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptHeader  = $dbarray['Head_id'];
		$OptSubHeader  = $dbarray['SubHead_id'];
		$SkillDescrip  = $dbarray['Description'];
		$OptClass  = $dbarray['Class_id'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['SkillSetup_delete']))
	{
		$Total = $_POST['Totalskill'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkSkillheadID'.$i]))
			{
				$chkSkillheadID = $_POST['chkSkillheadID'.$i];
				$q = "Delete From tbproskills where ID = '$chkSkillheadID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_GET['shid']))
	{
		$_POST['OptClass'] = $_GET['cid'];
		$_POST['OptHeader'] = $_GET['hid'];
		$_POST['OptSubHeader'] = $_GET['shid'];
	}
	if(isset($_POST['OptClass']))
	{	
		$OptClass = $_POST['OptClass'];
	}
	if(isset($_POST['OptHeader']))
	{	
		$OptHeader = $_POST['OptHeader'];
	}
	if(isset($_POST['OptSubHeader']))
	{	
		$OptSubHeader = $_POST['OptSubHeader'];
	}
	if(isset($_POST['Optsearch']))
	{	
		$Optsearch = $_POST['Optsearch'];
		if($Optsearch !="Class" and $Optsearch !="Name"){
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($Optsearch =="Class"){
			$lockname = "disabled='disabled'";
		}elseif($Optsearch =="Name"){
			$lockclass = "disabled='disabled'";
		}
	}
	if(isset($_POST['OptStudent']))
	{	
		$OptStudent = $_POST['OptStudent'];
		// GET STUDENT CLASS ID
		$query = "select Stu_Class from tbstudentmaster where AdmissionNo='$OptStudent'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SelStuClass  = $dbarray['Stu_Class'];
	}
	if(isset($_POST['ProgressRpt']))
	{
		$rTotal = $_POST['TotalResult'];
		$AdmNo = $_POST['AdmNo'];
		$sClass = $_POST['sClass'];
		$dat = date('Y'.'-'.'m'.'-'.'d');
		for($i=1;$i<=$rTotal;$i++){
			if(isset( $_POST['Res'.$i]))
			{
				$Result = $_POST['Res'.$i];
				$Ski_ID = $_POST['Ski'.$i];
				$query = "select Head_id,SubHead_id from tbproskills where ID='$Ski_ID'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$Hea_id  = $dbarray['Head_id'];
				$Sub_id  = $dbarray['SubHead_id'];
				
				$query = "select ID from tbprogreport where AdmnNo = '$AdmNo' and Head_id ='$Hea_id' and SubHead_id = '$Sub_id' and ProSkill_id = '$Ski_ID' and Class_id ='$sClass'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$q = "update tbprogreport set Result = '$Result',Prog_Date='$dat' where AdmnNo = '$AdmNo' and Head_id ='$Hea_id' and SubHead_id = '$Sub_id' and ProSkill_id = '$Ski_ID' and Class_id ='$sClass'";
				$result = mysql_query($q,$conn);
				}else {
					$q = "Insert into tbprogreport(AdmnNo,Head_id,SubHead_id,ProSkill_id,Result,Prog_Date,Class_id) Values ('$AdmNo','$Hea_id','$Sub_id','$Ski_ID','$Result','$dat','$sClass')";
					$result = mysql_query($q,$conn);
				}
			}
		}
	}
	if(isset($_POST['PrintRpt']))
	{	
		$PageHasError = 0;
		$OptStudent = $_POST['OptStudent'];
		$sClass = $_POST['sClass'];
		if(!$_POST['OptStudent']){
			$errormsg = "<font color = red size = 1>Select Student Name.</font>";
			$PageHasError = 1;
		}	
		if ($PageHasError == 0)
		{
			$OptShowResult = "Student Progress Report";
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=result_rpt.php?page=$OptShowResult&adm=$OptStudent&cid=$sClass\">";
			exit;
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
.style21 {font-weight: bold}
.style22 {
	color: #FFFFFF;
	font-weight: bold;
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
		if ($SubPage == "Progress Header") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="progress.php?subpg=Progress Header">
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
					  <TD colspan="4" valign="top"  align="left">
					    <input type="checkbox" name="ChkAllClass" value="on" <?PHP echo $chkVis; ?> onClick="javascript:setTimeout('__doPostBack(\'ChkAllClass\',\'\')', 0)" <?PHP echo $isChecked; ?>>
					    For All Classes 
					  </p>
					   </TD>
					</TR>
					<TR>
					  <TD width="12%" valign="top"  align="left">Select Class </TD>
					  <TD width="17%" valign="top"  align="left">
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)" <?PHP echo $lockClass; ?>>
						<option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassId from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
						}
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$ClassID = $row["ID"];
								$Classname = $row["Class_Name"];
								
								if($OptClass =="$ClassID"){
?>
									<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
								}
							}
						}
?>
					  </select>
					  </TD>
					  <TD width="21%" valign="top"  align="left">Header Info </TD>
					  <TD width="50%" valign="top"  align="left"><input name="HeadInfo" type="text" size="55" value="<?PHP echo $HeadInfo; ?>"></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="SelHeadID" value="<?PHP echo $ProgHeadID; ?>">
							 <input name="headmaster" type="submit" id="headmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="headmaster" type="submit" id="headmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4"><p>&nbsp;</p><hr>
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="535" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="74" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="153" bgcolor="#F4F4F4"><div align="center" class="style21">Class.</div></td>
                            <td width="278" bgcolor="#F4F4F4"><div align="left"><strong>Progress header </strong></div></td>
                          </tr>
<?PHP
							$counter_header = 0;
							if(isset($_POST['SubmitSearch'])){
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tbprogheader where INSTR(Description,'$Search_Key') order by class_id ";
							}elseif(isset($_POST['OptClass'])){
								if($_POST['OptClass']==""){
									$query2 = "select * from tbprogheader order by class_id ";
								}else{
									$query2 = "select * from tbprogheader where class_id = '$OptClass' order by class_id";
								}
							}else{
								$query2 = "select * from tbprogheader order by class_id";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_header = $rstart;
							}else{
								$counter_header = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbprogheader where INSTR(Description,'$Search_Key') order by class_id LIMIT $rstart,$rend";
							}elseif(isset($_POST['OptClass'])){
								if($_POST['OptClass']==""){
									$query3 = "select * from tbprogheader order by class_id LIMIT $rstart,$rend";
								}else{
									$query3 = "select * from tbprogheader where class_id = '$OptClass' order by class_id LIMIT $rstart,$rend";
								}
							}else{
								$query3 = "select * from tbprogheader order by class_id LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_header = $counter_header+1;
									$counter = $counter+1;
									$HeadID = $row["ID"];
									$ProgDesc = $row["Description"];
									$classid = $row["class_id"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkHeadID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $HeadID; ?>"></div></td>
										<td><div align="center"><a href="progress.php?subpg=Progress Header&head_id=<?PHP echo $HeadID; ?>"><?PHP echo GetClassName($classid); ?></a></div></td>
										<td><div align="left"><a href="progress.php?subpg=Progress Header&head_id=<?PHP echo $HeadID; ?>"><?PHP echo $ProgDesc; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Header&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Header&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Header&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalhead" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelHeadID" value="<?PHP echo $ProgHeadID; ?>">
						     <input name="headmasterr_delete" type="submit" id="headmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Progress Sub Header") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="progress.php?subpg=Progress Sub Header">
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
					  <TD width="12%" valign="top"  align="left">Select Class </TD>
					  <TD width="19%" valign="top"  align="left">
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)">
						<option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
						}
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$ClassID = $row["ID"];
								$Classname = $row["Class_Name"];
								
								if($OptClass =="$ClassID"){
?>
									<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
								}
							}
						}
?>
					  </select>
					  </TD>
					  <TD width="19%" valign="top"  align="left">Select Progress Header  </TD>
					  <TD width="50%" valign="top"  align="left">
					  <select name="OptHeader" onChange="javascript:setTimeout('__doPostBack(\'OptHeader\',\'\')', 0)">
                        <option value="" selected="selected">Select</option>
                        <?PHP
						$counter = 0;
						$query = "select ID,Description from tbprogheader where class_id = '$OptClass' order by Description";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$HeaderID = $row["ID"];
								$HeadDesc = $row["Description"];
								
								if($OptHeader =="$HeaderID"){
?>
                        			<option value="<?PHP echo $HeaderID; ?>" selected="selected"><?PHP echo $HeadDesc; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $HeaderID; ?>"><?PHP echo $HeadDesc; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></TD>
					</TR>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><p>Progress Sub Header Info 
					    <input name="sHeadInfo" type="text" size="65" value="<?PHP echo $sHeadInfo; ?>"></p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalshead" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelsHeadID" value="<?PHP echo $ProgsHeadID; ?>">
							 <input name="sheadmaster" type="submit" id="sheadmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="sheadmaster" type="submit" id="sheadmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4"><p>&nbsp;</p><hr>
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="602" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="45" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="269" bgcolor="#F4F4F4"><div align="center" class="style21">Progress Header </div></td>
                            <td width="258" bgcolor="#F4F4F4"><div align="left"><strong>Progress Sub Header </strong></div></td>
                          </tr>
<?PHP
							$counter_subheader = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tbsubheader where INSTR(Decription,'$Search_Key') order by Head_id ";
							}elseif($_POST['OptHeader'] !=""){
								$query2 = "select * from tbsubheader where Class_id = '$OptClass' And Head_id = '$OptHeader' order by Head_id";
							}elseif($_POST['OptClass'] !=""){
								$query2 = "select * from tbsubheader where Class_id = '$OptClass' order by Head_id";
							}else{
								$query2 = "select * from tbsubheader order by Decription";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_subheader = $rstart;
							}else{
								$counter_subheader = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbsubheader where INSTR(Decription,'$Search_Key') order by Head_id LIMIT $rstart,$rend";
							}elseif($_POST['OptHeader'] !=""){
								$query3 = "select * from tbsubheader where Class_id = '$OptClass' And Head_id = '$OptHeader' order by Head_id LIMIT $rstart,$rend";
							}elseif($_POST['OptClass'] !=""){
								$query3 = "select * from tbsubheader where Class_id = '$OptClass' order by Head_id LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tbsubheader order by Head_id LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_subheader = $counter_subheader+1;
									$counter = $counter+1;
									$SubHeadID = $row["ID"];
									$ProgSubDesc = $row["Decription"];
									$Headid = $row["Head_id"];
									
									$query1 = "select * from tbprogheader where ID='$Headid'";
									$result1 = mysql_query($query1,$conn);
									$dbarray1 = mysql_fetch_array($result1);
									$HeaderDesc  = $dbarray1['Description'];
?>
									  <tr>
										<td><div align="center">
										<input name="chkSubheadID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SubHeadID; ?>"></div></td>
										<td><div align="center"><a href="progress.php?subpg=Progress Sub Header&shead_id=<?PHP echo $SubHeadID; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>"><?PHP echo $HeaderDesc; ?></a></div></td>
										<td><div align="left"><a href="progress.php?subpg=Progress Sub Header&shead_id=<?PHP echo $SubHeadID; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>"><?PHP echo $ProgSubDesc; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Sub Header&st=0&ed=<?PHP echo $rend; ?>&shead_id=<?PHP echo $SubHeadID; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Sub Header&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&shead_id=<?PHP echo $SubHeadID; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Sub Header&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&shead_id=<?PHP echo $SubHeadID; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalshead" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelsHeadID" value="<?PHP echo $ProgsHeadID; ?>">
						     <input name="sheadmasterr_delete" type="submit" id="sheadmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Progress Skills") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="progress.php?subpg=Progress Skills">
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
					  <TD width="16%" valign="top"  align="left">Select Class </TD>
					  <TD width="30%" valign="top"  align="left">
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)" style="width:180px">
						<option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
						}
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$ClassID = $row["ID"];
								$Classname = $row["Class_Name"];
								
								if($OptClass =="$ClassID"){
?>
									<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
								}
							}
						}
?>
					  </select>
					  </TD>
					  <TD width="19%" valign="top"  align="left">Select Progress Header  </TD>
					  <TD width="35%" valign="top"  align="left">
					  <select name="OptHeader"  onChange="javascript:setTimeout('__doPostBack(\'OptHeader\',\'\')', 0)" style="width:200px">
                        <option value="" selected="selected">Select</option>
                        <?PHP
						$counter = 0;
						$query = "select ID,Description from tbprogheader where class_id = '$OptClass' order by Description";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$HeaderID = $row["ID"];
								$HeadDesc = $row["Description"];
								
								if($OptHeader =="$HeaderID"){
?>
                        			<option value="<?PHP echo $HeaderID; ?>" selected="selected"><?PHP echo $HeadDesc; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $HeaderID; ?>"><?PHP echo $HeadDesc; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></TD>
					</TR>
					<TR>
					  <TD width="16%" valign="top"  align="left"><p>Select Sub Header </p></TD>
					  <TD width="30%" valign="top"  align="left"><p>
					  <select name="OptSubHeader" onChange="javascript:setTimeout('__doPostBack(\'OptSubHeader\',\'\')', 0)" style="width:200px">
                        <option value="" selected="selected">Select</option>
                        <?PHP
						$counter = 0;
						$query = "select ID,Decription from tbsubheader where Class_id = '$OptClass' And Head_id = '$OptHeader' order by Decription";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$SubHeaderID = $row["ID"];
								$SubHeadDesc = $row["Decription"];
								
								if($OptSubHeader =="$SubHeaderID"){
?>
                        			<option value="<?PHP echo $SubHeaderID; ?>" selected="selected"><?PHP echo $SubHeadDesc; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $SubHeaderID; ?>"><?PHP echo $SubHeadDesc; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></p>
					  </TD>
					  <TD width="19%" valign="top"  align="left"><p>Enter Description </p></TD>
					  <TD width="35%" valign="top"  align="left"><p><textarea name="SkillDescrip" cols="45" rows="3"><?PHP echo $SkillDescrip; ?></textarea></p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="SelskillID" value="<?PHP echo $ProgskillID; ?>">
							 <input name="SkillSetup" type="submit" id="SkillSetup" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="SkillSetup" type="submit" id="SkillSetup" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4"><p>&nbsp;</p><hr>
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="619" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="45" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="247" bgcolor="#F4F4F4"><div align="center" class="style21">Progress Sub  Header </div></td>
                            <td width="297" bgcolor="#F4F4F4"><div align="left"><strong>Progress Skills </strong></div></td>
                          </tr>
<?PHP
							$counter_skillheader = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tbproskills where INSTR(Description,'$Search_Key') order by SubHead_id ";
							}elseif($_POST['OptSubHeader']!=""){
								$query2 = "select * from tbproskills where Class_id = '$OptClass' And Head_id ='$OptHeader' And SubHead_id = '$OptSubHeader' order by SubHead_id";
							}elseif($_POST['OptHeader']!=""){
								$query2 = "select * from tbproskills where Class_id = '$OptClass' And Head_id ='$OptHeader' order by SubHead_id";
							}elseif($_POST['OptClass']!=""){
								$query2 = "select * from tbproskills where Class_id = '$OptClass' order by SubHead_id";
							}else{
								$query2 = "select * from tbproskills order by SubHead_id";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_skillheader = $rstart;
							}else{
								$counter_skillheader = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbproskills where INSTR(Description,'$Search_Key') order by SubHead_id  LIMIT $rstart,$rend";
							}elseif($_POST['OptSubHeader']!=""){
								$query3 = "select * from tbproskills where Class_id = '$OptClass' And Head_id ='$OptHeader' And SubHead_id = '$OptSubHeader' order by SubHead_id  LIMIT $rstart,$rend";
							}elseif($_POST['OptHeader']!=""){
								$query3 = "select * from tbproskills where Class_id = '$OptClass' And Head_id ='$OptHeader' order by SubHead_id  LIMIT $rstart,$rend";
							}elseif($_POST['OptClass']!=""){
								$query3 = "select * from tbproskills where Class_id = '$OptClass' order by SubHead_id  LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tbproskills order by SubHead_id  LIMIT $rstart,$rend";
							}
							
							
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_skillheader = $counter_skillheader+1;
									$counter = $counter+1;
									$SkillHeadID = $row["ID"];
									$ProgSkillDesc = $row["Description"];
									$Headid = $row["Head_id"];
									$SubHeadid = $row["SubHead_id"];
									
									$query1 = "select * from tbsubheader where ID='$SubHeadid'";
									$result1 = mysql_query($query1,$conn);
									$dbarray1 = mysql_fetch_array($result1);
									$sHeaderDesc  = $dbarray1['Decription'];
?>
									  <tr>
										<td><div align="center">
										<input name="chkSkillheadID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SkillHeadID; ?>"></div></td>
										<td><div align="center"><a href="progress.php?subpg=Progress Skills&skill_id=<?PHP echo $SkillHeadID; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>&shid=<?PHP echo $OptSubHeader; ?>"><?PHP echo $sHeaderDesc; ?></a></div></td>
										<td><div align="left"><a href="progress.php?subpg=Progress Skills&skill_id=<?PHP echo $SkillHeadID; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>&shid=<?PHP echo $OptSubHeader; ?>"><?PHP echo $ProgSkillDesc; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Skills&st=0&ed=<?PHP echo $rend; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>&shid=<?PHP echo $OptSubHeader; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Skills&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>&shid=<?PHP echo $OptSubHeader; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="progress.php?subpg=Progress Skills&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&&cid=<?PHP echo $OptClass; ?>&hid=<?PHP echo $OptHeader; ?>&shid=<?PHP echo $OptSubHeader; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalskill" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelskillID" value="<?PHP echo $ProgskillID; ?>">
						     <input name="SkillSetup_delete" type="submit" id="SkillSetup_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Student Progress Report") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="progress.php?subpg=Student Progress Report">
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
					  <TD width="14%" valign="top"  align="left">
<?PHP
						if($Optsearch == "All"){
?>
					    	<input type="radio" name="Optsearch" value="All" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
<?PHP
						}else{
?>
							<input type="radio" name="Optsearch" value="All" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
<?PHP
						}
?>
					    All Students </TD>
					  <TD width="39%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="47%" colspan="2" rowspan="3" valign="top">
					  
					  <select name="OptStudent" size="10" multiple style="width:320px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptStudent\',\'\')', 0)">
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									if($_POST['Optsearch']=="Class"){
										$OptStuClass = $_POST['OptStuClass'];
										$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$OptStuClass' order by Stu_Full_Name";
									}elseif($_POST['Optsearch']=="Name"){
										$Search_Name = $_POST['Search_Name'];
										$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Name') And Stu_Class IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Stu_Full_Name";
									}else{
										$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Stu_Full_Name";
									}
								}else{
									if($_POST['Optsearch']=="All"){
										$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster order by Stu_Full_Name";
									}elseif($_POST['Optsearch']=="Class"){
										$OptStuClass = $_POST['OptStuClass'];
										$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$OptStuClass' order by Stu_Full_Name";
									}elseif($_POST['Optsearch']=="Name"){
										$Search_Name = $_POST['Search_Name'];
										$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Name') order by Stu_Full_Name";
									}else{
										$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '2' order by Stu_Full_Name";
									}
								}
								
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$Stu_Full_Name = $row["Stu_Full_Name"];
										$AdmnNo = $row["AdmissionNo"];
										
										if($OptStudent =="$AdmnNo"){
?>
                       					 	<option value="<?PHP echo $AdmnNo; ?>" selected="selected">&nbsp;&nbsp;&nbsp;<?PHP echo $Stu_Full_Name; ?></option>
<?PHP
										}else{
?>
                        					<option value="<?PHP echo $AdmnNo; ?>">&nbsp;&nbsp;&nbsp;<?PHP echo $Stu_Full_Name; ?></option>
<?PHP
										}
									}
								}
?>
                      </select></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">
<?PHP
						if($Optsearch == "Class"){
?>
					    	<input type="radio" name="Optsearch" value="Class" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
<?PHP
						}else{
?>
							<input type="radio" name="Optsearch" value="Class" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
<?PHP
						}
?>
					    Class Wise  </TD>
					  <TD width="39%" valign="top"  align="left">
					  <select name="OptStuClass" onChange="javascript:setTimeout('__doPostBack(\'OptStuClass\',\'\')', 0)" style="width:180px" <?PHP echo $lockclass; ?>>
                        <option value="" selected="selected">Select</option>
                        <?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
						}
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$ClassID = $row["ID"];
								$Classname = $row["Class_Name"];
								
								if($OptStuClass =="$ClassID"){
?>
                        <option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
								}else{
?>
                        <option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">
<?PHP
						if($Optsearch == "Name"){
?>
					    	<input type="radio" name="Optsearch" value="Name" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
<?PHP
						}else{
?>
							<input type="radio" name="Optsearch" value="Name" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
<?PHP
						}
?>
					    Name Wise </TD>
					  <TD width="39%" valign="top"  align="left">
					    <input name="Search_Name" type="text" size="40" value="<?PHP echo $Search_Name; ?>" <?PHP echo $lockname; ?>>
					    <input name="SubmitSearch2" type="submit" id="SubmitSearch" value="Go">					 </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4"><hr>
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="129" bgcolor="#cccccc"><div align="center" class="style21">Category</div></td>
                            <td width="161" bgcolor="#cccccc"><div align="center" class="style21">Sub category</div></td>
                            <td width="207" bgcolor="#cccccc"><div align="left"><strong>Skills</strong></div></td>
							<td width="173" bgcolor="#cccccc"><div align="left"><strong>Result</strong></div></td>
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
														  <td width="30%" valign="top"><div align="left"><?PHP echo $SubDesc; ?></div></td>
														  <td width="70%" colspan="2" valign="top">
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
																	  	   <td width="56%" valign="top"><div align="left"><?PHP echo $SkillDesc; ?></div></td>
																			<td width="44%" valign="top">
																			<input type="hidden" name="Ski<?PHP echo $CountBox; ?>" value="<?PHP echo $Skill_id; ?>">
																			<input name="Res<?PHP echo $CountBox; ?>" type="text" size="20" value="<?PHP echo $Result; ?>"></td>
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
					    </TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="TotalResult" value="<?PHP echo $CountBox; ?>">
							 <input type="hidden" name="AdmNo" value="<?PHP echo $OptStudent; ?>">
							 <input type="hidden" name="sClass" value="<?PHP echo $SelStuClass; ?>">
						     <input name="PrintRpt" type="submit" id="ProgressRpt" value="Print Details">
							 <input name="ProgressRpt" type="submit" id="ProgressRpt" value="Create New">
						   </div>
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
