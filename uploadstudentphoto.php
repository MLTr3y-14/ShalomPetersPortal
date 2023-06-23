<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
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
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
		
	if(isset($_GET['regis_id']))
	{ 
	   $RegNo = $_GET['regis_id'];
	   $_SESSION['RegNo'] = $RegNo;
	   $query = "select * from tbregistration where ID = $RegNo";
	   $result = mysql_query($query,$conn);
	   $row = mysql_fetch_array($result);
	   $Stu_FirstName = $row["Stu_FirstName"];
	   $Stu_MidName = $row["Stu_MidName"];
	   $Stu_LastName = $row["Stu_LastName"];
	   $FullName = $Stu_LastName." ".$Stu_MidName." ".$Stu_FirstName;
	   $_SESSION['FullName'] = $FullName;
	
	
	  }
	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'uploadstudentphoto.processor.php'; 
	// set a max file size for the html upload form 
	$max_file_size = 100000; // size in bytes OR 30kb
	$Page = "Students";
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
 if (isset($_SESSION['picname']))
    {
      $FullFileName = $_SESSION['picname'];
	  $_SESSION['picname']= "";
	  $RegNo = $_SESSION['RegNo'];
		$_SESSION['RegNo']= "";
      }
	  if($_SESSION['status'] == 1)
	  {
	  $FullName = $_SESSION['FullName'];
	  $_SESSION['FullName'] = "";
	  }
	  else{
		  $FullName = $_SESSION['FullName'];
	  }
	$_SESSION['status'] = 0;
 
 if(isset($_POST['uploadpic']))	
 {
	 $RegNo2 = $_POST['RegNo'];
	 $FullFileName2 = $_POST['FullFileName'];
	 $query = "update tbregistration set StudentPhoto='$FullFileName2' where ID = '$RegNo2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	  $result = mysql_query($query,$conn);
	  
	  $query2 = "update tbstudentmaster set Stu_Photo='$FullFileName2' where Stu_Regist_No = '$RegNo2' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	  $result2 = mysql_query($query2,$conn);
	   //$errormsg = $RegNo2;
	  $errormsg = "<font color = blue size = 1>Picture Uploaded Successfully.</font>";
	  $SubPage = "Select Student To Upload Picture";
 }
// if(isset($_POST['Optsearch']))
//	{	
//		$Optsearch = $_POST['Optsearch'];
//	} 
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
		if ($SubPage == "Select Student To Upload Picture") {
?>
				<?PHP echo $errormsg.$status2; ?>
				<form name="form1" method="post" action="uploadstudentphoto.php?subpg=Select Student To Upload Picture">
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
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Search Registration records/Click on a Student to upload picture</strong></div></TD>
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
                            
                            
                           
							<td width="104" bgcolor="#F4F4F4"><div align="center"><strong>Class</strong></div></td>
							<td width="171" bgcolor="#F4F4F4"><div align="center"><strong>Name</strong></div></td>
							
							
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
								//if($bgColor == "#FFBF80"){
									 
									
?>
									  <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
                                        </tr>
                                <?PHP         
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>">Next</a> </p> <?PHP 
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
								//if($bgColor == "#FFBF80"){
									 
									
?>
									<tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
                                        </tr>
                                <?PHP         
								 
							 
                                   } 
								 }
							// }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&OptClassWise=<?PHP echo $OptClassWise; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&OptClassWise=<?PHP echo $OptClassWise; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&OptClassWise=<?PHP echo $OptClassWise; ?>">Next</a> </p>   <?PHP 						
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
								//if($bgColor == "#FFBF80"){
									 
									
?>
									  <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
                                        </tr>
                                <?PHP         
								
                                   } 
								 }
							 //}
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Optsearch=<?PHP echo $Search_Key; ?>&Search_Key=<?PHP echo $Optsearch; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>  <?PHP		
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
								//if($bgColor == "#FFBF80"){
									 
									
?>
									 <tr>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo GetClassName($Stu_ClassID); ?></a></div></td>
										<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="uploadstudentphoto.php?subpg=upload picture&regis_id=<?PHP echo $regisID; ?>"><?PHP echo $FullName; ?></a></div></td>
                                        </tr>
                                <?PHP         
								 }
							    }
							// }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="uploadstudentphoto.php?subpg=Select Student To Upload Picture&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p> <?PHP } ?> </TD>
					</TR>
					
				</TBODY>
				</TABLE>
				</form>
                    
                    
<?PHP
		}elseif ($SubPage == "upload picture") {
	
	
?>                   
 <TABLE style="WIDTH: 98%">
					<TBODY>
                     <TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong> Upload picture for <?PHP echo $FullName ?></strong></div></TD>
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
									  <div align="right">LOGO FILE</div>
									  </label>  </TD>
									<TD width=81% align="left"><input id="file" type="file" name="file">
                                     <input type="hidden" name="RegNo" value="<?PHP echo $RegNo; ?>">
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
                                <TR><TD colspan="2" align="center"><form id="Upload" action="uploadstudentphoto.php?subpg=Select Student To Upload Picture"  method="post"> <input type="hidden" name="RegNo" value="<?PHP echo $RegNo; ?>">
                          <input type="hidden" name="FullFileName" value="<?PHP echo $FullFileName; ?>">
                                 <input id="uploadpic" type="submit" name="uploadpic" value="Click Here To Confirm Final Upload Of Pic To Student Record" width="30"></TD></TR>
							  </TBODY></TABLE>	</form>
							 
                    
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
