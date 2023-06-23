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
	
	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'uploademployeepic.processor.php'; 
	// set a max file size for the html upload form 
	$max_file_size = 100000; // size in bytes OR 30kb
	$Page = "Employee";
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
 if(isset($_GET['emp_id']))
	{ 
	   $EmpID = $_GET['emp_id'];
	   $_SESSION['EmpID'] = $EmpID;
	   $query = "select EmpName from tbemployeemasters where ID = $EmpID";
	   $result = mysql_query($query,$conn);
	   $row = mysql_fetch_array($result);
	   $employeeName = $row["EmpName"];
	   $_SESSION['FullName1'] = $employeeName;
	
	
	  }
 if (isset($_SESSION['picname1']))
    {
      $FullFileName = $_SESSION['picname1'];
	  $_SESSION['picname1']= "";
	  $EmpID = $_SESSION['EmpID'];
		$_SESSION['EmpID']= "";
      }
	  if($_SESSION['status1'] == 1)
	  {
	   $employeeName = $_SESSION['FullName1'];
	  $_SESSION['FullName1'] = "";
	  }
	  else{
		   $employeeName= $_SESSION['FullName1'];
	  }
	$_SESSION['status1'] = 0;
 
 if(isset($_POST['uploadpic']))	
 {
	 $EmpID = $_POST['EmpID'];
	 $FullFileName = $_POST['FullFileName'];
	 $query = "update tbemployeemasters set Photo='$FullFileName' where ID = '$EmpID'";
	  $result = mysql_query($query,$conn);
	  
	  //$query2 = "update tbstudentmaster set Stu_Photo='$FullFileName2' where Stu_Regist_No = '$RegNo2'";
	  //$result2 = mysql_query($query2,$conn);
	   //$errormsg = $RegNo2;
	  $errormsg = "<font color = blue size = 1>Picture Uploaded Successfully.</font>";
	  //$SubPage = "Select Student To Upload Picture";
 }
 if(isset($_POST['Optsearch']))
	{	
		$Optsearch = $_POST['Optsearch'];
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
                <?PHP echo $errormsg; ?>
 <TABLE style="WIDTH: 98%">
					<TBODY>
                     <TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong> Upload picture for <?PHP echo $employeeName ?></strong></div></TD>
					</TR>
					<TR>
					  <TD width="75%" align="left" valign="top">
					        <form id="Upload2" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post"> 
							  <TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
								<TBODY>
								<TR bgcolor="#CCCCCC">
									<TD colspan="2" align="left"><input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"></TD>
								</TR>
								<TR>
									<TD width=19%><label for="file">
									  <div align="right">PICTURE FILE</div>
									  </label>  </TD>
									<TD width=81% align="left"><input id="file" type="file" name="file">
                                     <input type="hidden" name="EmpID" value="<?PHP echo $EmpID; ?>">
									  <input id="submit" type="submit" name="submit" value="Upload Picture To Page"></TD>
                                     <TD><table border="0" cellpadding="0" cellspacing="0" width="221">
						  <tr>
						   <td><img src="images/spacer.gif" width="21" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="178" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="22" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
						  </tr>
						
						  <tr>
						   <td colspan="3"><img name="empty_r1_c1" src="images/empty_r1_c1.jpg" width="221" height="20" border="0" id="empty_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="empty_r2_c1" src="images/empty_r2_c1.jpg" width="21" height="197" border="0" id="empty_r2_c1" alt="" /></td>
						   <td><img src="images/<?PHP echo $FullFileName ?>" width="178" height="175"></td>
						   <td rowspan="2"><img name="empty_r2_c3" src="images/empty_r2_c3.jpg" width="22" height="197" border="0" id="empty_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="175" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="empty_r3_c2" src="images/empty_r3_c2.jpg" width="178" height="22" border="0" id="empty_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="22" border="0" alt="" /></td></form>
						  </tr>
						</table>	 </TD>
								</TR>
                                <TR><TD colspan="2" align="center"><form id="Upload" action="uploademployeepic.php"  method="post"> <input type="hidden" name="EmpID" value="<?PHP echo $EmpID; ?>">
                          <input type="hidden" name="FullFileName" value="<?PHP echo $FullFileName; ?>">
                                 <input id="uploadpic" type="submit" name="uploadpic" value="Click Here To Confirm Final Upload Of Pic To Employee Record" width="30"></TD></TR>
							  </TBODY></TABLE>	</form>
							 
                    

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
