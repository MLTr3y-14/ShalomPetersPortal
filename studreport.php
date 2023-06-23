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
	$Page = "Student Report";
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
	if(isset($_POST['TickBox']))
	{
		$TickBox = $_POST['TickBox'];
		if($TickBox =="Tick"){
			$chkTick = "checked='checked'";
			$StudBox = "checked='checked'";
		}elseif($TickBox =="Untick"){
			$chkUntick = "checked='checked'";
			$StudBox = "";
		}
		$OptClass = $_POST['OptClass'];
		$isChkStudent = $_POST['isChkStudent'];
		if($isChkStudent =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}elseif($isChkStudent =="Class"){
			$chkClass = "checked='checked'";
		}
	}
	if(isset($_POST['OptClass']))
	{
		$OptClass = $_POST['OptClass'];
	}
	if(isset($_POST['OptDept']))
	{
		$OptDept = $_POST['OptDept'];
	}
	if(isset($_POST['isChkStudent']))
	{	
		$isChkStudent = $_POST['isChkStudent'];
		if($isChkStudent =="All"){
			$chkAll = "checked='checked'";
			$lockClass = "disabled='disabled'";
		}elseif($isChkStudent =="Class"){
			$chkClass = "checked='checked'";
		}
	}
	if(isset($_POST['PrintStudent']))
	{
		$PageHasError = 0;
		if(!isset($_POST['isChkStudent']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria.</font>";
			$PageHasError = 1;
		}
		if($PageHasError==0){
			$TickBox = $_POST['TickBox'];
			if($TickBox =="Tick"){
				$chkTick = "checked='checked'";
				$StudBox = "checked='checked'";
			}elseif($TickBox =="Untick"){
				$chkUntick = "checked='checked'";
				$StudBox = "";
			}
			$OptClass = $_POST['OptClass'];
			$isChkStudent = $_POST['isChkStudent'];
			if($isChkStudent =="All"){
				$chkAll = "checked='checked'";
				$lockclass = "disabled='disabled'";
			}elseif($isChkStudent =="Class"){
				$chkClass = "checked='checked'";
			}
			
			if($isChkStudent =="Class"){
				if($_POST['OptClass'] =="0")
				{
					$errormsg = "<font color = red size = 1>Select Class</font>";
					$PageHasError = 1;
				}
				if ($PageHasError == 0)
				{
					$Total = $_POST['Totalbox'];
					$AdmissionNo = "";
					for($i=1;$i<=$Total;$i++){
						if(isset( $_POST['stud'.$i]))
						{
							$AdmNo = $_POST['stud'.$i];
							$AdmissionNo = $AdmissionNo.$AdmNo.",";
						}
					}
					if($AdmissionNo==""){
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent&isClass=$OptClass\">";
						exit;
					}else{
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent&isClass=$OptClass&isAdm=$AdmissionNo\">";
						exit;
					}
				}
			}else{
				$Total = $_POST['Totalbox'];
				$AdmissionNo = "";
				for($i=1;$i<=$Total;$i++){
					if(isset( $_POST['stud'.$i]))
					{
						$AdmNo = $_POST['stud'.$i];
						$AdmissionNo = $AdmissionNo.$AdmNo.",";
					}
				}
				if($AdmissionNo==""){
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent\">";
					exit;
				}else{
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent&isAdm=$AdmissionNo\">";
					exit;
				}
			}
		}		
	}
	if(isset($_POST['PrintNewStudent']))
	{
		$PageHasError = 0;
		if(!isset($_POST['isChkStudent']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria.</font>";
			$PageHasError = 1;
		}
		if($PageHasError==0){
			$TickBox = $_POST['TickBox'];
			if($TickBox =="Tick"){
				$chkTick = "checked='checked'";
				$StudBox = "checked='checked'";
			}elseif($TickBox =="Untick"){
				$chkUntick = "checked='checked'";
				$StudBox = "";
			}
			$OptClass = $_POST['OptClass'];
			$isChkStudent = $_POST['isChkStudent'];
			if($isChkStudent =="All"){
				$chkAll = "checked='checked'";
				$lockclass = "disabled='disabled'";
			}elseif($isChkStudent =="Class"){
				$chkClass = "checked='checked'";
			}
			
			if($isChkStudent =="Class"){
				if($_POST['OptClass'] =="0")
				{
					$errormsg = "<font color = red size = 1>Select Class</font>";
					$PageHasError = 1;
				}
				if ($PageHasError == 0)
				{
					$Total = $_POST['Totalbox'];
					$AdmissionNo = "";
					for($i=1;$i<=$Total;$i++){
						if(isset( $_POST['stud'.$i]))
						{
							$AdmNo = $_POST['stud'.$i];
							$AdmissionNo = $AdmissionNo.$AdmNo.",";
						}
					}
					if($AdmissionNo==""){
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent&isClass=$OptClass&vw=new\">";
						exit;
					}else{
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent&isClass=$OptClass&isAdm=$AdmissionNo&vw=new\">";
						exit;
					}
				}
			}else{
				$Total = $_POST['Totalbox'];
				$AdmissionNo = "";
				for($i=1;$i<=$Total;$i++){
					if(isset( $_POST['stud'.$i]))
					{
						$AdmNo = $_POST['stud'.$i];
						$AdmissionNo = $AdmissionNo.$AdmNo.",";
					}
				}
				if($AdmissionNo==""){
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent&vw=new\">";
					exit;
				}else{
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Student Details&src_stud=$isChkStudent&isAdm=$AdmissionNo&vw=new\">";
					exit;
				}
			}
		}		
	}
	if(isset($_POST['PrintRegStudent']))
	{
		$PageHasError = 0;
		if(!isset($_POST['isChkStudent']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria.</font>";
			$PageHasError = 1;
		}
		if($PageHasError==0){
			$TickBox = $_POST['TickBox'];
			if($TickBox =="Tick"){
				$chkTick = "checked='checked'";
				$StudBox = "checked='checked'";
			}elseif($TickBox =="Untick"){
				$chkUntick = "checked='checked'";
				$StudBox = "";
			}
			$OptClass = $_POST['OptClass'];
			$isChkStudent = $_POST['isChkStudent'];
			if($isChkStudent =="All"){
				$chkAll = "checked='checked'";
				$lockclass = "disabled='disabled'";
			}elseif($isChkStudent =="Class"){
				$chkClass = "checked='checked'";
			}
			
			if($isChkStudent =="Class"){
				if($_POST['OptClass'] =="0")
				{
					$errormsg = "<font color = red size = 1>Select Class</font>";
					$PageHasError = 1;
				}
				if ($PageHasError == 0)
				{
					$Total = $_POST['Totalbox'];
					$AdmissionNo = "";
					for($i=1;$i<=$Total;$i++){
						if(isset( $_POST['stud'.$i]))
						{
							$AdmNo = $_POST['stud'.$i];
							$AdmissionNo = $AdmissionNo.$AdmNo.",";
						}
					}
					if($AdmissionNo==""){
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Registered Student&src_stud=$isChkStudent&isClass=$OptClass\">";
						exit;
					}else{
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Registered Student&src_stud=$isChkStudent&isClass=$OptClass&isAdm=$AdmissionNo\">";
						exit;
					}
				}
			}else{
				$Total = $_POST['Totalbox'];
				$AdmissionNo = "";
				for($i=1;$i<=$Total;$i++){
					if(isset( $_POST['stud'.$i]))
					{
						$AdmNo = $_POST['stud'.$i];
						$AdmissionNo = $AdmissionNo.$AdmNo.",";
					}
				}
				if($AdmissionNo==""){
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Registered Student&src_stud=$isChkStudent\">";
					exit;
				}else{
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Registered Student&src_stud=$isChkStudent&isAdm=$AdmissionNo\">";
					exit;
				}
			}
		}		
	}
	if(isset($_POST['isChkParent']))
	{	
		$isChkParent = $_POST['isChkParent'];
		if($isChkParent =="All"){
			$chkAll = "checked='checked'";
			$lockAdmn = "disabled='disabled'";
		}elseif($isChkParent =="Admission"){
			$chkAdmn = "checked='checked'";
		}
	}
	if(isset($_POST['SubmitParentPrint']))
	{
		$PageHasError = 0;
		if(!isset($_POST['isChkParent']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria.</font>";
			$PageHasError = 1;
		}
		if($PageHasError==0){
			$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
			$isChkParent = $_POST['isChkParent'];
			if($isChkParent =="All"){
				$chkAll = "checked='checked'";
				$lockAdmn = "disabled='disabled'";
			}elseif($isChkParent =="Admission"){
				$chkAdmn = "checked='checked'";
			}
			
			if($isChkParent =="Admission"){
				if($AdmissionNo =="-")
				{
					$errormsg = "<font color = red size = 1>Admissiion No is empty</font>";
					$PageHasError = 1;
				}
				if ($PageHasError == 0)
				{
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Parent Details&src_par=$isChkParent&isAdm=$AdmissionNo\">";
					exit;
				}
			}else{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=student_rpt.php?pg=Parent Details&src_par=$isChkParent\">";
				exit;
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
<style type="text/css">td img {display: block;}</style>
<style type="text/css">
.a{
margin:0px auto;
position:relative;
width:450px;
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
.style1 {
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
			  <TD width="220" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="858" align="center" valign="top">
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
		if ($SubPage == "Student Details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="studreport.php?subpg=Student Details">
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
				<p>&nbsp;</p>
				<TABLE width="85%" style="WIDTH: 85%" cellpadding="5" cellspacing="5">
				<TBODY>
					<TR>
					  <TD valign="top"  align="left" ><strong>Filter Criteria</strong></TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
							<input name="isChkStudent" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isChkStudent\',\'\')', 0)" <?PHP echo $chkAll; ?>/>
					  All Student
<?PHP
						}
?>
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" height="51"  align="left" valign="top"><label>
					 <input name="isChkStudent" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'isChkStudent\',\'\')', 0)" <?PHP echo $chkClass; ?>> Students Class
					 </label>
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)" style="width:100px;" <?PHP echo $lockClass; ?>>
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
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
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
					  <div class="a">
					  <div class="b">
						<table width="390" border="0" align="center" cellpadding="3" cellspacing="3">
						<tr>
                          <td width="65">
                            <label> 
                            <input name="TickBox" type="radio" value="Tick" onChange="javascript:setTimeout('__doPostBack(\'TickBox\',\'\')', 0)" <?PHP echo $chkTick; ?>>Tick All
                            </label>
                            </td>
                          <td width="304"><label><input name="TickBox" type="radio" value="Untick" onChange="javascript:setTimeout('__doPostBack(\'TickBox\',\'\')', 0)" <?PHP echo $chkUntick; ?>>UnTick All </label></td>
                        </tr>
                        <tr bgcolor="#666666">
                          <td width="65"><span class="style1">Tick</span> </td>
                          <td width="304"><span class="style1">Student Name</span></td>
                        </tr>
<?PHP
						$counter = 0;
						if($_POST['isChkStudent'] =="Class")
						{
							$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class='$OptClass order by Stu_Full_Name'";
							$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter = $counter+1;
								$AdmID = $row["ID"];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
?>
								<tr>
								  <td><label>
									<input type="checkbox" name="stud<?PHP echo $counter; ?>" value="<?PHP echo $AdmissionNo; ?>" <?PHP echo $StudBox; ?>>
								  </label></td>
								  <td><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></td>
								</tr>
<?PHP
							}
						}
						}elseif($_POST['isChkStudent'] == 'All'){
							$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster order by Stu_Full_Name";
						
						$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter = $counter+1;
								$AdmID = $row["ID"];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
?>
								<tr>
								  <td><label>
									<input type="checkbox" name="stud<?PHP echo $counter; ?>" value="<?PHP echo $AdmissionNo; ?>" <?PHP echo $StudBox; ?>>
								  </label></td>
								  <td><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></td>
								</tr>
<?PHP
							}
						}
						}
?>
                      </table>
					  </div></div>
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left"><label></label></TD>
					 </TR>
					  <TR>
							<TD>
							<div align="center">
							<input type="hidden" name="Totalbox" value="<?PHP echo $counter; ?>">
							  <input type="submit" name="PrintStudent" value="Print">
							</div>							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "New Admission") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="studreport.php?subpg=New Admission">
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
				<p>&nbsp;</p>
				<TABLE width="85%" style="WIDTH: 85%" cellpadding="5" cellspacing="5">
				<TBODY>
					<TR>
					  <TD valign="top"  align="left" ><strong>Filter Criteria [New Admission]</strong></TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
						 <input name="isChkStudent" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isChkStudent\',\'\')', 0)" <?PHP echo $chkAll; ?>/>
					  All Student
<?PHP
						}
?>
					 </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left"><label>
					 <input name="isChkStudent" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'isChkStudent\',\'\')', 0)" <?PHP echo $chkClass; ?>> Students Class
					 </label>
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)" style="width:100px;" <?PHP echo $lockClass; ?>>
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
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
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
					  <div class="a">
					  <div class="b">
						<table width="390" border="0" align="center" cellpadding="3" cellspacing="3">
						<tr>
                          <td width="65">
                            <label> 
                            <input name="TickBox" type="radio" value="Tick" onChange="javascript:setTimeout('__doPostBack(\'TickBox\',\'\')', 0)" <?PHP echo $chkTick; ?>>Tick All
                            </label>
                            </td>
                          <td width="304"><label><input name="TickBox" type="radio" value="Untick" onChange="javascript:setTimeout('__doPostBack(\'TickBox\',\'\')', 0)" <?PHP echo $chkUntick; ?>>Un Tick All </label></td>
                        </tr>
                        <tr bgcolor="#666666">
                          <td width="65"><span class="style1">Tick</span> </td>
                          <td width="304"><span class="style1">Student Name</span></td>
                        </tr>
<?PHP
						$counter = 0;
						if($_POST['isChkStudent'] =="Class")
						{
							$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class = '$OptClass' and OldStudent = '0' order by Stu_Full_Name";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter = $counter+1;
								$AdmID = $row["ID"];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
?>
								<tr>
								  <td><label>
									<input type="checkbox" name="stud<?PHP echo $counter; ?>" value="<?PHP echo $AdmissionNo; ?>" <?PHP echo $StudBox; ?>>
								  </label></td>
								  <td><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></td>
								</tr>
<?PHP
							}
						}
						}elseif($_POST['isChkStudent'] == 'All'){
							$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where OldStudent = '0' order by Stu_Full_Name";
						
						$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter = $counter+1;
								$AdmID = $row["ID"];
								$AdmissionNo = $row["AdmissionNo"];
								$Stu_Full_Name = $row["Stu_Full_Name"];
?>
								<tr>
								  <td><label>
									<input type="checkbox" name="stud<?PHP echo $counter; ?>" value="<?PHP echo $AdmissionNo; ?>" <?PHP echo $StudBox; ?>>
								  </label></td>
								  <td><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></td>
								</tr>
<?PHP
							}
						}
						}
?>
                      </table>
					  </div></div>
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left"><label></label></TD>
					 </TR>
					  <TR>
							<TD>
							<div align="center">
							<input type="hidden" name="Totalbox" value="<?PHP echo $counter; ?>">
							  <input type="submit" name="PrintNewStudent" value="Print">
							</div>							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Registered Student") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="studreport.php?subpg=Registered Student">
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
				<p>&nbsp;</p>
				<TABLE width="85%" style="WIDTH: 85%" cellpadding="5" cellspacing="5">
				<TBODY>
					<TR>
					  <TD valign="top"  align="left" ><strong>Filter Criteria [Registered Student]</strong></TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
						 <input name="isChkStudent" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isChkStudent\',\'\')', 0)" <?PHP echo $chkAll; ?>/>
					  	All Student
<?PHP
						}
?>
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left"><label>
					 <input name="isChkStudent" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'isChkStudent\',\'\')', 0)" <?PHP echo $chkClass; ?>> Students Class
					 </label>
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)" style="width:100px;" <?PHP echo $lockClass; ?>>
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						if($_SESSION['module'] == "Teacher"){
							$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
						}else{
							$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
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
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
					  <div class="a">
					  <div class="b">
						<table width="390" border="0" align="center" cellpadding="3" cellspacing="3">
						<tr>
                          <td width="65">
                            <label> 
                            <input name="TickBox" type="radio" value="Tick" onChange="javascript:setTimeout('__doPostBack(\'TickBox\',\'\')', 0)" <?PHP echo $chkTick; ?>>Tick All
                            </label>
                            </td>
                          <td width="304"><label><input name="TickBox" type="radio" value="Untick" onChange="javascript:setTimeout('__doPostBack(\'TickBox\',\'\')', 0)" <?PHP echo $chkUntick; ?>>Un Tick All </label></td>
                        </tr>
                        <tr bgcolor="#666666">
                          <td width="65"><span class="style1">Tick</span> </td>
                          <td width="304"><span class="style1">Student Name</span></td>
                        </tr>
<?PHP
						$counter = 0;
						if($_POST['isChkStudent'] =="Class")
						{
						$query3 = "select ID,Stu_LastName,Stu_MidName,Stu_FirstName from tbregistration where Stu_ClassID = '$OptClass'";
							//$query3 = "select ID,Stu_LastName,Stu_MidName,Stu_FirstName from tbregistration order by Stu_LastName";
							$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter = $counter+1;
								$RegID = $row["ID"];
								$Stu_Full_Name = $row["Stu_LastName"]." ".$row["Stu_MidName"]." ".$row["Stu_FirstName"];
								
								$query4 = "select AdmissionNo from tbstudentmaster where Stu_Regist_No='$RegID'";
								$result4 = mysql_query($query4,$conn);
								$dbarray4 = mysql_fetch_array($result4);
								$AdmissionNo  = $dbarray4['AdmissionNo'];
									
?>
								<tr>
								  <td><label>
									<input type="checkbox" name="stud<?PHP echo $counter; ?>" value="<?PHP echo $RegID; ?>" <?PHP echo $StudBox; ?>>
								  </label></td>
								  <td><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></td>
								</tr>
<?PHP
							}
						}
						}elseif($_POST['isChkStudent'] == 'All'){
							$query3 = "select ID,Stu_LastName,Stu_MidName,Stu_FirstName from tbregistration order by Stu_LastName";					
						
						$result3 = mysql_query($query3,$conn);
						$num_rows = mysql_num_rows($result3);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result3)) 
							{
								$counter = $counter+1;
								$RegID = $row["ID"];
								$Stu_Full_Name = $row["Stu_LastName"]." ".$row["Stu_MidName"]." ".$row["Stu_FirstName"];
								
								$query4 = "select AdmissionNo from tbstudentmaster where Stu_Regist_No='$RegID'";
								$result4 = mysql_query($query4,$conn);
								$dbarray4 = mysql_fetch_array($result4);
								$AdmissionNo  = $dbarray4['AdmissionNo'];
									
?>
								<tr>
								  <td><label>
									<input type="checkbox" name="stud<?PHP echo $counter; ?>" value="<?PHP echo $RegID; ?>" <?PHP echo $StudBox; ?>>
								  </label></td>
								  <td><a href="student_rpt.php?pg=View Student Details&Admn=<?PHP echo $AdmissionNo; ?>" target="_blank"><?PHP echo $Stu_Full_Name; ?></a></td>
								</tr>
<?PHP
							}
						}
						}
?>
                      </table>
					  </div></div>
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left"><label></label></TD>
					 </TR>
					  <TR>
							<TD>
							<div align="center">
							<input type="hidden" name="Totalbox" value="<?PHP echo $counter; ?>">
							  <input type="submit" name="PrintRegStudent" value="Print">
							</div>							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Parent Details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="studreport.php?subpg=Parent Details">
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
				<p>&nbsp;</p>
				<TABLE width="88%" align="center" cellpadding="5" cellspacing="4" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;WIDTH: 80%">
				<TBODY>
					<TR>
					  <TD width="21%"  align="left">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
						 <input name="isChkParent" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isChkParent\',\'\')', 0)" <?PHP echo $chkAll; ?>/> 						
						All Parent:
<?PHP
						}
?>
 </TD>
					  <TD width="79%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="21%"  align="left">
<input name="isChkParent" type="radio" value="Admission" onClick="javascript:setTimeout('__doPostBack(\'isChkParent\',\'\')', 0)" <?PHP echo $chkAdmn; ?>/> 						
Admn No.: </TD>
					  <TD width="79%" valign="top"  align="left">
						<input name="AdmNo" type="text"value="<?PHP echo $AdmNo; ?>" size="10" <?PHP echo $lockAdmn; ?>/>
						<input name="AdmNo2" type="text" value="<?PHP echo $AdmNo2; ?>" size="5" <?PHP echo $lockAdmn; ?>/>
						<input name="SubmitParentPrint" type="submit" id="SubmitParentPrint" value="Print">
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
