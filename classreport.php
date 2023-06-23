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
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Class Report";
	$audit=update_Monitory('Login','Administrator',$Page);
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$PageHasError = 0;
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['ischecked']))
	{	
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
		}
	}
	if(isset($_POST['SubmitPrint']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
		}
		if($ischecked == "Class"){
			$OptClass = $_POST['OptClass'];
			if($_POST['OptClass'] =="0")
			{
				$errormsg = "<font color = red size = 1>Select Class</font>";
				$PageHasError = 1;
			}
		}				
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=class_rpt.php?pg=Class charges&ty=$ischecked&cls=$OptClass\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrintSubject']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
		}
		if($ischecked == "Class"){
			$OptClass = $_POST['OptClass'];
			if($_POST['OptClass'] =="0")
			{
				$errormsg = "<font color = red size = 1>Select Class</font>";
				$PageHasError = 1;
			}
		}				
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=class_rpt.php?pg=Class Subject&ty=$ischecked&cls=$OptClass\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrintStudent']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
		}
		if($ischecked == "Class"){
			$OptClass = $_POST['OptClass'];
			if($_POST['OptClass'] =="0")
			{
				$errormsg = "<font color = red size = 1>Select Class</font>";
				$PageHasError = 1;
			}
		}				
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=class_rpt.php?pg=Class student&ty=$ischecked&cls=$OptClass\">";
			exit;
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
width:auto;
}

.b{
overflow:auto;
width:auto;
height:400px;
}
.b2{
overflow:auto;
width:auto;
height:300px;
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

.style24 {
	color: #FFFFFF;
	font-weight: bold;
}
.style25 {color: #FFFFFF}
</style>
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
			  <TD width="856" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" align="center"style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><p>&nbsp;</p><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Class charges") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="classreport.php?subpg=Class charges">
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
					  <TD width="54%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="503" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
								if($_SESSION['module'] != "Teacher"){
?>
						 			<label> <input name="ischecked" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAll; ?>></label>
                              All Classes
<?PHP
								}
?>
                             </td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID')and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name ";
								}
								
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
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
							  
							  </td>
                          </tr>
						  <tr>
                            <td width="324">&nbsp;</td>
							</tr>
                        </table>
					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrint" value="Print">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Class Subject") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="classreport.php?subpg=Class Subject">
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
					  <TD width="54%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="503" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
								if($_SESSION['module'] != "Teacher"){
?>
						 			<label>
									  <input name="ischecked" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAll; ?>>
									</label>
									  All Classes 
<?PHP
								}
?>							
							</td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}
								
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
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
							  
							  </td>
                          </tr>
						  <tr>
                            <td width="324">&nbsp;</td>
							</tr>
                        </table>
					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrintSubject" value="Print">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Class student") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="classreport.php?subpg=Class student">
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
					  <TD width="54%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="503" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
								if($_SESSION['module'] != "Teacher"){
?>
						 			<label>
									  <input name="ischecked" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAll; ?>>
									</label>
									  All Classes 
<?PHP
								}
?>							
							</td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}
								
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
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
							  
							  </td>
                          </tr>
						  <tr>
                            <td width="324">&nbsp;</td>
							</tr>
                        </table>
					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrintStudent" value="Print">
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
