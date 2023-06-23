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
		$Adm_Dy = date('d');
		$Adm_Mth = date('m');
		$Adm_Yr = date('Y');
	}
	if(true){
		$tpl;
		$query = "SELECT tpl FROM templtable WHERE tpl_type = 'ATT'";
		$result = mysql_query($query,$conn);

		while($row = mysql_fetch_array($result)) {
			$tpl = $row['tpl'];
		} 

		$_SESSION['att_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templtable WHERE tpl_type = 'RST_SUBJ'";
		$result = mysql_query($query,$conn);
 
		while($row = mysql_fetch_array($result)) {
			$tpl = $row['tpl'];
		}

		$_SESSION['rst_subj_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templtable WHERE tpl_type = 'RST'";
		$result = mysql_query($query,$conn);

		while($row = mysql_fetch_array($result)) {
			$tpl = $row['tpl'];
		}

		$_SESSION['rst_tpl'] = $tpl; 
		$tpl ="";
		$query = "SELECT tpl FROM templtable WHERE tpl_type = 'FEES_DEFAULTER'";
		$result = mysql_query($query,$conn);

		while($row = mysql_fetch_array($result)) {
			$tpl = $row['tpl'];
		}

		$_SESSION['fees_default_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templtable WHERE tpl_type = 'FEE'";
		$result = mysql_query($query,$conn);
		while($row = mysql_fetch_array($result)) {
			$tpl = $row["tpl"];
			
		}

		$_SESSION['fee_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templtable WHERE tpl_type = 'ROLL'";
		$result = mysql_query($query,$conn);

		while($row = mysql_fetch_array($result)) {
			$tpl = $row['tpl'];
		}

		$_SESSION['roll_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templtable WHERE tpl_type = 'EXEAT'";
		$result = mysql_query($query,$conn);

		while($row = mysql_fetch_array($result)) {
			$tpl = $row['tpl'];
		}

		$_SESSION['exeat_tpl'] = $tpl; 
		
		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'CLINIC'";
		$result = mysql_query($query,$conn);

		while($row = mysql_fetch_array($result)) {
			$tpl = $row['tpl'];
		}

		$_SESSION['clinic_tpl'] = $tpl;
	 }
	if(isset($_POST['sender'])){

		//attendance
		$errormsg=saveTpl($_POST['att_tpl'], 'ATT', $_POST['sender']);
		$_SESSION['att_tpl'] = $_POST['att_tpl'];

		//attendance
		$errormsg=saveTpl($_POST['rst_subj_tpl'], 'RST_SUBJ', $_POST['sender']);
		$_SESSION['rst_subj_tpl'] = $_POST['rst_subj_tpl'];

		//attendance
		$errormsg=saveTpl($_POST['rst_tpl'], 'RST', $_POST['sender']);
		$_SESSION['rst_tpl'] = $_POST['rst_tpl'];

		//attendance
		$errormsg=saveTpl($_POST['fee_default_tpl'], 'FEES_DEFAULTER', $_POST['sender']);
		$_SESSION['fees_default_tpl'] = $_POST['fee_default_tpl'];

		//roll_tpl
		$errormsg=saveTpl($_POST['roll_tpl'], 'ROLL', $_POST['sender']);
		$_SESSION['roll_tpl'] = $_POST['roll_tpl'];

		//attendance
		$errormsg=saveTpl($_POST['fees_tpl'], 'FEE', $_POST['sender']);
		$_SESSION['fee_tpl'] = $_POST['fees_tpl'];

		//exeat
		$errormsg=saveTpl($_POST['exeat_tpl'], 'EXEAT', $_POST['sender']);
		$_SESSION['exeat_tpl'] = $_POST['exeat_tpl'];

		//clinic
		$errormsg=saveTpl($_POST['clinic_tpl'], 'CLINIC', $_POST['sender']);
		$_SESSION['clinic_tpl'] = $_POST['clinic_tpl'];
		
?>
		 <!-- <script>
			  alert("template saved!");
		  </script>-->
<?php
	}
	function saveTpl($tpl, $tpl_type, $sender) {
		include 'library/config.php';
		include 'library/opendb.php';
		
		$sql = "UPDATE templtable SET tpl = '$tpl' WHERE tpl_type = '$tpl_type'";
		$result2 = mysql_query($conn,$sql);
		$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
		return $errormsg;
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager</TITLE>

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
				<BR><?PHP echo $errormsg; ?>



<form action="" method="post" >
<table width="85%" border="0">
  <tr>
    <td width="29%"><table width="97%" border="0">
      <tr>
        <td><p>List of variables:</p>
          <p>&lt;date&gt; = date of request. <br/>&lt;admin_no&gt; = admission number. <br/>&lt;stud_name&gt; = student name. <br/>&lt;stud_class&gt; = student class.<br/> &lt;status&gt; = student status.</p>
          </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><label>Sender
            <input type="text" name="sender" id="sender" />
        </label></td>
      </tr>
    </table></td>
    <td width="29%">
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Attendance SMS Template</legend>
        <textarea name="att_tpl" id="att_tpl" cols="30" rows="5"><?php echo $_SESSION['att_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td width="42%">
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Performance overall Template</legend>
        <textarea name="rst_tpl" id="rst_tpl" cols="30" rows="5"><?php echo $_SESSION['rst_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Performance<br> 
        subject-based<br> 
        Template</legend>
        <textarea name="rst_subj_tpl" id="rst_subj_tpl" cols="30" rows="5"><?php echo $_SESSION['rst_subj_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Fees defaulter SMS Template</legend>
        <textarea name="fee_default_tpl" id="fees_default_tpl" cols="30" rows="5"><?php echo $_SESSION['fees_default_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Student FEES  Template</legend>
        <textarea name="fees_tpl" id="fees_tpl" cols="30" rows="5"><?php echo $_SESSION['fee_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Roll call SMS Template</legend>
        <textarea name="roll_tpl" id="roll_tpl" cols="30" rows="5"><?php echo $_SESSION['roll_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Exeat SMS Template</legend>
        <textarea name="exeat_tpl" id="exeat_tpl" cols="30" rows="5"><?php echo $_SESSION['exeat_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
    <td>
    <table width="41%" border="0">
      <tr>
        <td><fieldset>
        <legend>Clinic SMS Template</legend>
        <textarea name="clinic_tpl" id="clinic_tpl" cols="30" rows="5"><?php echo $_SESSION['clinic_tpl'] ?></textarea>
        </fieldset>
        </td>
      </tr>
      <tr>
        <td><em><span class="style2">120 characters / 2 sms</span></em></td>
      </tr>
    </table></td>
  </tr>
  <tr><td colspan="3"><div align="center">
    <input type="submit" name="button" id="button" value="Save" />
  </div></td></tr>
</table>
</form>







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
