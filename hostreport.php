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
	$Page = "Hostel Report";
	$audit=update_Monitory('Login','Administrator',$Page);
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$PageHasError = 0;
	if(isset($_GET['filename']))
	{
		$EmpFilename = $_GET['filename'];
	}else{
		$EmpFilename = "empty_r2_c2.jpg";
	}
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['OptClass']))
	{	
		$OptClass = $_POST['OptClass'];
	}
	if(isset($_POST['GetStudent']))
	{	
		$OptRooms = $_POST['OptRooms'];
		$attDate = $_POST['att_Yr']."-".$_POST['att_Mth']."-".$_POST['att_Dy'];
		$att_Yr = $_POST['att_Yr'];
		$att_Mth = $_POST['att_Mth'];
		$att_Dy = $_POST['att_Dy'];
		
		if($_POST['att_Yr'] == "" or $_POST['att_Mth'] == "" or $_POST['att_Dy'] == ""){
			$errormsg = "<font color = red size = 1>Roll Call date is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptRooms']){
			$errormsg = "<font color = red size = 1>Select Room.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Roll Call&rid=$OptRooms&dt=$attDate\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrint']))
	{	
		$HostelNum = $_POST['HostelNum'];
		
		if(!$_POST['HostelNum']){
			$errormsg = "<font color = red size = 1>Hostel No. is empty</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Roll Call&hid=$HostelNum\">";
			exit;
		}
	}
	if(isset($_POST['isSearch']))
	{
		$isSearch = $_POST['isSearch'];
		if($isSearch =="Request"){
			$chkRequest = "checked='checked'";
			$lockExeat = "disabled='disabled'";
			$lockReturn = "disabled='disabled'";
		}elseif($isSearch =="Exeat"){
			$lockRequest = "disabled='disabled'";
			$chkExeat = "checked='checked'";
			$lockReturn = "disabled='disabled'";
		}elseif($isSearch =="Return"){
			$lockRequest = "disabled='disabled'";
			$lockExeat = "disabled='disabled'";
			$chkReturn = "checked='checked'";
		}
	}
	if(isset($_POST['SubmitPrintExeat']))
	{	
		$isSearch = $_POST['isSearch'];
		
		if($isSearch =="Request"){
			$frRequest = $_POST['frRequest'];
			$toRequest = $_POST['toRequest'];
			$chkRequest = "checked='checked'";
			$lockExeat = "disabled='disabled'";
			$lockReturn = "disabled='disabled'";
			if($frRequest !="" and $frRequest !="--" and $toRequest !="" and $toRequest !="--"){
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Exeat&fr=$frRequest&to=$toRequest&ty=Request\">";
				exit;
			}
		}elseif($isSearch =="Exeat"){
			$frExeat = $_POST['frExeat'];
			$toExeat = $_POST['toExeat'];
			$lockRequest = "disabled='disabled'";
			$chkExeat = "checked='checked'";
			$lockReturn = "disabled='disabled'";
			if($frExeat !="" and $frExeat !="--" and $toExeat !="" and $toExeat !="--"){
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Exeat&fr=$frExeat&to=$toExeat&ty=Exeat\">";
				exit;
			}
		}elseif($isSearch =="Return"){
			$frReturn = $_POST['frReturn'];
			$toReturn = $_POST['toReturn'];
			$lockRequest = "disabled='disabled'";
			$lockExeat = "disabled='disabled'";
			$chkReturn = "checked='checked'";
			if($frReturn !="" and $frReturn !="--" and $toReturn !="" and $toReturn !="--"){
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Exeat&fr=$frReturn&to=$toReturn&ty=Return\">";
				exit;
			}
		}
	}
	if(isset($_POST['isSearch2']))
	{
		$isSearch2 = $_POST['isSearch2'];
		if($isSearch2 =="All"){
			$chkRequest = "checked='checked'";
			$lockExeat = "disabled='disabled'";
			$lockReturn = "disabled='disabled'";
		}elseif($isSearch2 =="Admitted"){
			$lockRequest = "disabled='disabled'";
			$chkExeat = "checked='checked'";
			$lockReturn = "disabled='disabled'";
		}elseif($isSearch2 =="Visited"){
			$lockRequest = "disabled='disabled'";
			$lockExeat = "disabled='disabled'";
			$chkReturn = "checked='checked'";
		}
	}
	if(isset($_POST['SubmitPrintClinic']))
	{	
		$OptRooms = $_POST['OptRooms'];
		
		$isSearch2 = $_POST['isSearch2'];
		if($isSearch2 =="All"){
			$frAll = $_POST['frAll'];
			$toAll = $_POST['toAll'];
			$chkRequest = "checked='checked'";
			$lockExeat = "disabled='disabled'";
			$lockReturn = "disabled='disabled'";
			if($frAll !="" and $frAll !="--" and $toAll !="" and $toAll !="--"){
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Clinic&fr=$frAll&to=$toAll&ty2=All\">";
				exit;
			}
		}elseif($isSearch2 =="Admitted"){
			$frAdmitted = $_POST['frAdmitted'];
			$toAdmitted = $_POST['toAdmitted'];
			$lockRequest = "disabled='disabled'";
			$chkExeat = "checked='checked'";
			$lockReturn = "disabled='disabled'";
			if($frAdmitted !="" and $frAdmitted !="--" and $toAdmitted !="" and $toAdmitted !="--"){
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Clinic&fr=$frAdmitted&to=$toAdmitted&ty2=Admitted\">";
				exit;
			}
		}elseif($isSearch2 =="Visited"){
			$frVisited = $_POST['frVisited'];
			$toVisited = $_POST['toVisited'];
			$lockRequest = "disabled='disabled'";
			$lockExeat = "disabled='disabled'";
			$chkReturn = "checked='checked'";
			if($frVisited !="" and $frVisited !="--" and $toVisited !="" and $toVisited !="--"){
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=hostel_rpt.php?pg=Student Clinic&fr=$frVisited&to=$toVisited&ty2=Visited\">";
				exit;
			}
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
					  <TD height="55" align="center"style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><p>&nbsp;</p><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Roll Call") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="hostreport.php?subpg=Roll Call">
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
					  <TD width="44%" valign="top"  align="left">
					  		<table width="301" cellpadding="4">
								<tr>
									<td width="43"><div align="right">Room :</div></td>
									<td width="234">
									<select name="OptRooms">
										<option value="" selected="selected">Select</option>
<?PHP
										$counter = 0;
										$query = "select ID,RoomName from tbhostelroom order by RoomName";
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
												$RoomID = $row["ID"];
												$RoomName = $row["RoomName"];
												
												if($OptRooms =="$RoomID"){
?>
													<option value="<?PHP echo $RoomID; ?>" selected="selected"><?PHP echo $RoomName; ?></option>
<?PHP
												}else{
?>
													<option value="<?PHP echo $RoomID; ?>"><?PHP echo $RoomName; ?></option>
<?PHP
												}
											}
										}
?>
									  </select>
									</td>
								</tr>
								<tr>
									<td width="43"><div align="right">Date:</div></td>
									<td width="234">
									<select name="att_Dy">
								      <option value="" selected="selected">Day</option>
								      
<?PHP
										for($i=1; $i<=31; $i++){
											if($att_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								    </select>
								    <select name="att_Mth">
								       <option value="" selected="selected">Month</option>
<?PHP
											for($i=1; $i<=12; $i++){
												if($i == $att_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}else{
													echo "<option value=$i>$i</option>";
												}
											}
?>
					                </select>
								    <select name="att_Yr">
								      <option value="" selected="selected">Year</option>
 <?PHP
 										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($att_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
								    <label>
								    <input type="submit" name="GetStudent" value="Go">
								    </label></td>
								</tr>
							</table>
					        </TD>
					  <TD width="56%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">Search Student
					 	 <TABLE width="90%" align="center" style="WIDTH: 98%" cellpadding="5" cellspacing="4">
							<TBODY>
								<TR>
								  <TD width="29%"  align="left">
<?PHP
									if($OptType =='HostelNo')
									{
										echo "<input name='OptType' type='radio' value='HostelNo' checked='checked'>";
									}else{
										echo "<input name='OptType' type='radio' value='HostelNo'>";
									}
?>
								    Hostel No.: </TD>
								  <TD width="71%" valign="top"  align="left">
								    <input name="HostelNum" type="text"value="<?PHP echo $HostelNum; ?>" size="15"/>
								    <input name="SubmitPrint" type="submit" id="SubmitPrint" value="Print">
								  </TD>
								</TR>
								</TBODY>
						 </TABLE>
					  </TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">		
					    <p>&nbsp;</p>			  
						<label></label></TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Exeat Details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="hostreport.php?subpg=Exeat Details">
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
					  <TD width="100%" valign="top"  align="left">
					  		<table width="537" align="center" cellpadding="4">
								<tr>
									<td width="135">&nbsp;</td>
									<td width="503">&nbsp;</td>
								</tr>
								<tr>
									<td width="135"><div align="right">
									  <label>
									  <div align="left">
									    <input name="isSearch" type="radio" value="Request" onClick="javascript:setTimeout('__doPostBack(\'isSearch\',\'\')', 0)" <?PHP echo $chkRequest; ?>>
									    Request Date:</div>
									  </label>
									  <div align="left"></div>
									</div></td>
									<td width="503">From 
									  <input class="fil_ariane_passif" onClick="ds_sh(this);" name="frRequest" readonly="readonly" style="cursor: text" value="<?PHP echo $frRequest; ?>" <?PHP echo $lockRequest; ?>/>
								    <label> To 
								    <input class="fil_ariane_passif" onClick="ds_sh(this);" name="toRequest" readonly="readonly" style="cursor: text" value="<?PHP echo $toRequest; ?>" <?PHP echo $lockRequest; ?>/>
								    </label></td>
								</tr>
								<tr>
									<td width="135"><div align="right">
									  <label>
									  <div align="left">
									   <input name="isSearch" type="radio" value="Exeat" onClick="javascript:setTimeout('__doPostBack(\'isSearch\',\'\')', 0)" <?PHP echo $chkExeat; ?>>
									    Exeat Date:</div>
									  </label>
									</div></td>
									<td width="503">From 
									  <input class="fil_ariane_passif" onClick="ds_sh(this);" name="frExeat" readonly="readonly" style="cursor: text" value="<?PHP echo $frExeat; ?>" <?PHP echo $lockExeat; ?>/>
								    <label> To 
								    <input class="fil_ariane_passif" onClick="ds_sh(this);" name="toExeat" readonly="readonly" style="cursor: text" value="<?PHP echo $toExeat; ?>" <?PHP echo $lockExeat; ?>/>
								    </label></td>
								</tr>
								<tr>
									<td width="135"><div align="right">
									  <label>
									  <div align="left">
									    <input name="isSearch" type="radio" value="Return" onClick="javascript:setTimeout('__doPostBack(\'isSearch\',\'\')', 0)" <?PHP echo $chkReturn; ?>>
									    Return Date:</div>
									  </label>
									</div></td>
									<td width="503">From 
									  <input class="fil_ariane_passif" onClick="ds_sh(this);" name="frReturn" readonly="readonly" style="cursor: text" value="<?PHP echo $frReturn; ?>" <?PHP echo $lockReturn; ?>/>
								    <label> To 
								    <input class="fil_ariane_passif" onClick="ds_sh(this);" name="toReturn" readonly="readonly" style="cursor: text" value="<?PHP echo $toReturn; ?>" <?PHP echo $lockReturn; ?>/>
								    </label></td>
								</tr>
							</table>
					  </TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">		
					    <p align="center">
					      <input name="SubmitPrintExeat" type="submit" id="SubmitPrintExeat" value="Print">
					    </p>			  
						<label></label></TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Clinic Details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="hostreport.php?subpg=Clinic Details">
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
					  <TD width="100%" valign="top"  align="left">
					  		<table width="537" align="center" cellpadding="4">
								<tr>
									<td width="117">&nbsp;</td>
									<td width="396">&nbsp;</td>
								</tr>
								<tr>
									<td width="117"><div align="right">
									  <div align="left"><input name="isSearch2" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isSearch\',\'\')', 0)" <?PHP echo $chkRequest; ?>>
									    All :
									    </label>
									  </div>
									  <div align="left"></div>
									</div></td>
									<td width="396">From 
									  <input class="fil_ariane_passif" onClick="ds_sh(this);" name="frAll" readonly="readonly" style="cursor: text" value="<?PHP echo $frAll; ?>" <?PHP echo $lockRequest; ?>/>
								    <label> To 
								    <input class="fil_ariane_passif" onClick="ds_sh(this);" name="toAll" readonly="readonly" style="cursor: text" value="<?PHP echo $toAll; ?>" <?PHP echo $lockRequest; ?>/>
								    </label></td>
								</tr>
								<tr>
									<td width="117"><div align="right">
									  <div align="left"><input name="isSearch2" type="radio" value="Admitted" onClick="javascript:setTimeout('__doPostBack(\'isSearch\',\'\')', 0)" <?PHP echo $chkExeat; ?>>
									    Admitted
									    </label>
									</div>
									  </div></td>
									<td width="396"><label>From
									<input class="fil_ariane_passif" onClick="ds_sh(this);" name="frAdmitted" readonly="readonly" style="cursor: text" value="<?PHP echo $frAdmitted; ?>" <?PHP echo $lockExeat; ?>/></label>
								    <label> To 
								    <input class="fil_ariane_passif" onClick="ds_sh(this);" name="toAdmitted" readonly="readonly" style="cursor: text" value="<?PHP echo $toAdmitted; ?>" <?PHP echo $lockExeat; ?>/>
									
                                       
</label></td>
								</tr>
								<tr>
									<td width="117"><div align="right">
									  <div align="left"><input name="isSearch2" type="radio" value="Visited" onClick="javascript:setTimeout('__doPostBack(\'isSearch\',\'\')', 0)" <?PHP echo $chkReturn; ?>>
									    Visited:
									    </label>
									</div>
									  </div></td>
									<td width="396"><label>From
                                        <input onClick="ds_sh(this);" name="frVisited" readonly="readonly" style="cursor: text" value="<?PHP echo $frVisited; ?>" <?PHP echo $lockReturn; ?>/></label>
To<label>
										<input onClick="ds_sh(this);" name="toVisited" readonly="readonly" style="cursor: text" value="<?PHP echo $toVisited; ?>" <?PHP echo $lockReturn; ?>/>
</label></td>
								</tr>
							</table>
					  </TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">		
					    <p align="center">
					      <input name="SubmitPrintClinic" type="submit" id="SubmitPrintClinic" value="Print">
					    </p>			  
						<label></label></TD>
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
