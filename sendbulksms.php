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
	global $userNames,$Teacher_EmpID,$Activeterm;
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
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
		$Adm_Dy = date('d');
		$Adm_Mth = date('m');
		$Adm_Yr = date('Y');
	}
function SendSMS( $from, $to, $msg ){	
	$user = "edmins"; // this is your nuObjects Bulk SMS  usrename
	$pass = "edmins_pass"; // this is your nuObjects Bulk SMS  password
	$from = rawurlencode($from);  //LOOK MAX 11 CHARs. This is the senderID that will appear on the recipients Phone. 
	$msg = rawurlencode($msg); // It is important that you use urlencode() here in orde to manage special characters.
	$to = preg_replace("/[^0-9,]/", "", $to);
	
	
	// build HTTP URL and query
	$request =	'user='.$user.
				'&pass='.$pass.
				'&from='.$from.
				'&to='.$to.
				'&msg='.$msg; //initialize the request variable
				
	$url = 'http://www.nuobjects.com/nusms/'; //this is the url of the gateway's interface
	//$ch = curl_init(); //initialize curl handle 
	//curl_setopt($ch, CURLOPT_URL, $url); //set the url
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable 
	//curl_setopt($ch, CURLOPT_POST, 1); //set POST method 
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $request); //set the POST variables
	//$response = curl_exec($ch); // grab URL and pass it to the browser. Run the whole process and return the response
	//curl_close($ch); //close the curl handle
	
	return $response;
		
}
if(isset($_POST['Optsearch']))
{	
	$Optsearch = $_POST['Optsearch'];
	if($Optsearch =="Class"){
		$locktype = "disabled='disabled'";
		$lockname = "disabled='disabled'";
	}elseif($Optsearch =="Type"){
		$lockclass = "disabled='disabled'";
		$lockname = "disabled='disabled'";
	}elseif($Optsearch =="Name Wise"){
		$locktype = "disabled='disabled'";
		$lockclass = "disabled='disabled'";
	}
}
$Search_Key = "";
if(isset($_POST['SubmitSearch'])){
	$Optsearch = $_POST['Optsearch'];
	$toNo ="";
	if($Optsearch =="Class"){
		$OptClass = $_POST['OptClass'];
		$query3 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No IN (Select Stu_Regist_No from tbstudentmaster where Stu_Class = '$OptClass') order by Gr_Ph";
		$Search_Key = "";
		$_POST['Search_Key2'] = "";
	}elseif($Optsearch =="Type"){
		$OptType = $_POST['OptType'];
		$query3 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No IN (Select Stu_Regist_No from tbstudentmaster where Stu_Type = '$OptType') order by Gr_Ph";
		$Search_Key = "";
		$_POST['Search_Key2'] = "";
	}elseif($Optsearch =="Name Wise"){
		$Search_Key = $_POST['Search_Key'];
		$query3 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No = '0'";
	}
	
	$result3 = mysql_query($query3,$conn);
	$num_rows = mysql_num_rows($result3);
	if ($num_rows > 0 ) {
		while ($row = mysql_fetch_array($result3)) 
		{
			$counter_Adm = $counter_Adm+1;
			$counter = $counter+1;
			$Gr_Ph = $row["Gr_Ph"];
			$toNo = $toNo.$Gr_Ph.", ";
		}
	}
}
if(isset($_POST['OptStudent'])){
	$OptStudent = $_POST['OptStudent'];
	if($Search_Key == ""){
		$Search_Key =  $_POST['Search_Key2'];
	}
	
	if ($OptStudent ==""){
		$errormsg = "<font color = red size = 1>Select a student</font>";
		$PageHasError = 1;
	}
	if ($PageHasError == 0)
	{
		$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No IN (Select Stu_Regist_No from tbstudentmaster where AdmissionNo ='$OptStudent')";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$Gr_Ph  = $dbarray['Gr_Ph'];
		$toNo = $toNo.$Gr_Ph.", ";
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
.style23 {color: #990000}
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
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 18px;
}
.style2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FF0000;
}
.style4 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #0000FF;
}
.style5 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
<script type="text/javascript">
<!--
function clearDefault(el) {
if (el.defaultValue==el.value) el.value = ""
}
// -->
</script>
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
					  <TD height="55" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Bulk SMS") {
?>
			<table width="740" height="409" border="0" align="center">
			  <tr>
				<td width="740" valign="top">
<?php
				
				if (!isset($_POST['Send_SMS'])){
				
?>
					<form name="form1" method="post" action="sendbulksms.php?subpg=Bulk SMS">
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
					<fieldset>
       				 <legend>Search Phone Numbers</legend>
						<TABLE width="100%" style="WIDTH: 100%">
						<TBODY>
						<TR>
						  <TD width="22%" valign="top"  align="left"> <label>
							
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
						  </label>
						  <select name="OptClass" style="width:120px;" <?PHP echo $lockclass; ?>> 
						  <option value="" selected="selected">Select Class</option>
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
						  <TD width="24%" valign="top"  align="left">
	<?PHP
							if($Optsearch == "Type"){
	?>
								<input name="Optsearch" type="radio" value="Type" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
	<?PHP
							}else{
	?>
								<input name="Optsearch" type="radio" value="Type" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
	<?PHP
							}
	?>					    
							<select name="OptType" <?PHP echo $locktype; ?>>
								<option value="">Select Student Type</option>
								<option value="Scholar">Day Scholar</option>
								<option value="Hosteller">Hosteller</option>
							</select></TD>
						  <TD colspan="2"  align="left" valign="top">
	<?PHP
							if($Optsearch == "Name Wise"){
	?>
								<input name="Optsearch" type="radio" value="Name Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked">
	<?PHP
							}else{
	?>
								<input name="Optsearch" type="radio" value="Name Wise" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)">
	<?PHP
							}
							if($Search_Key ==""){
								$Search_Key = "Last Name...";
							}
	?>
							<input name="Search_Key" type="text" size="20" value="<?PHP echo $Search_Key; ?>" onFocus="this.value='';"  <?PHP echo $lockname; ?>/>
							
							<input name="SubmitSearch" type="submit" id="Search" value="Go"></TD>
							<td width="26%">
							<select name="OptStudent" size="10" multiple style="width:180px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptStudent\',\'\')', 0)">
<?PHP								
								
							$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Key')";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
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
                      		</select>
							<input type="hidden" name="Search_Key2" value="<?PHP echo $Search_Key; ?>">
						</TD>
						</TR>
						
						</TBODY></TABLE>
						</fieldset>
					</form>

				<form id="form1" name="form1" method="post" action="sendbulksms.php?subpg=Bulk SMS">
				<table width="99%" border="0" cellpadding="5" cellspacing="5">
				  <tr>
					<td width="16%"><div align="right">From</div></td>
					<td width="44%">
					  <input type="text" name="from" id="from" />
					</td>
				  </tr>
				  <tr>
					<td><div align="right">To</div></td>
					<td><label>
					  <textarea name="to" cols="45" rows="5" id="to"><?PHP echo $toNo; ?></textarea>
					</label></td>
				  </tr>
				  <tr>
					<td><div align="right">Message</div></td>
					<td><label>
					  <textarea name="msg" id="msg" cols="45" rows="6"></textarea>
					</label></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td><label>
					  <input type="submit" name="Send_SMS" id="Send SMS" value="Send SMS" />
					</label></td>
				  </tr>
				</table>
				
				
				</form>
				
<?php
				
				} else {
					$to = $_POST['to'];
				
					if (get_magic_quotes_gpc()) {
						$msg = stripslashes($_POST['msg']); //this is the message that we want to send
						$from = stripslashes($_POST['from']);//this is our sender 
					} else {
						$msg = $_POST['msg']; //this is the message that we want to send
						$from = $_POST['from'];//this is our sender 
					}
					
					$SendSMS =  SendSMS($from, $to, $msg);
				
?>
				<br />
				
<?php
					if ($SendSMS == 'sent'){	
?>
				
				<table width="100%" border="0" align="center">
				  <tr>
					<td><div align="center" class="style4">Message Sent</div></td>
				  </tr>
				  <tr>
					<td><p>Your message(s) has been sent.</p>
					  <p><a href="<?php echo $_SERVER['PHP_SELF'] ?>">Send SMS Now!</a></p>
					  <p>&nbsp;</p></td>
				  </tr>
				</table>
				
<?php
					}else{
?>
				
				<table width="100%" border="0" align="center">
				  <tr>
					<td><div align="center" class="style2 ">Message NOT Sent</div></td>
				  </tr>
				  <tr>
					<td><p>&nbsp;</p>
					  <p>There was a problem sending your messages. Please try again later. </p>
					  <p>The error messages from the server is <span class="style5">[ <?php echo $SendSMS ?> ]</span></p>
					  <p>&nbsp;</p></td>
				  </tr>
				</table>
				
<?php
				
				}
			}
				
?>
				</td>
			  </tr>
			</table>
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
