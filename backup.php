<?PHP
session_start();
global $conn;
global $dbname;
global $errorMsg, $PPContent;
include 'library/config.php';
include 'library/opendb.php';
include 'function.php';
if($_SESSION['module'] == "Teacher"){
	$Login = "Log in Teacher: ".$_SESSION['username']; 
	$bg="#420434";
	$usrnam = $_SESSION['username'];
	$query = "select EmpID from tbusermaster where UserName='$usrnam'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpID  = $dbarray['EmpID'];
	$audit=update_Monitory('Login','Teacher','System Setup');
}else{
	$Login = "Log in Administrator: ".$_SESSION['username']; 
	$bg="maroon";
	$userNames = $_SESSION['username'];
	$query = "select EmpID from tbusermaster where UserName='$userNames'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpID  = $dbarray['EmpID'];
	$audit=update_Monitory('Login','Administrators','System Setup');
}
if(isset($_GET['pg']))
{
	$Page = $_GET['pg'];
	$SysModule = $_GET['mod'];
}
// filename: photo.php 

	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload.db.processor.php';
	// set a max file size for the html upload form 
	$max_file_size = 100000; // size in bytes OR 30kb
if(isset($_GET['mker']))
{
	$UserName = $_GET['mker'];
	$Ddpass = $_GET['acc'];
	$dbpath = $_GET['pth'];
	$result2 = Monitor_confirmUser($UserName, $Ddpass);
	echo $Ddpass;
	 /* Check error codes */
   if($result2 == 1){
		$errorMsg = "<font color = red size = 1>That user doesn\'t exist in our database.</font>";
		$PageHasError = 1;
		$Page="error";
	  //die('That username doesn\'t exist in our database.');
   }else if($result2 == 2){
		$errorMsg = "<font color = red size = 1>Incorrect access details, please try again.</font>";
		$PageHasError = 1;
		$Page="error";
		
	  //die('Incorrect password, please try again.');
   }else if($result2 == 3){
		$errorMsg = "<font color = red size = 1>Incorrect access details. only administrators are allowed to use this platform. </font>";
		$PageHasError = 1;
		$Page="error";
	  //die('Incorrect password, please try again.');
   }
							   
   /* Username and password correct, register session variables */
   $_SESSION['backup_UN'] = $UserName;
   $_SESSION['backup_PW'] = $Ddpass;
   $_SESSION['backup'] = "YES";
								
   if ($PageHasError == 0) {
		$numrows = 0;
		$dbpath = str_replace("|","/",$dbpath);
		$Tablename=AllDatabaseName('1');
		$counter = 0;
		for($i=0; $i<=91; $i++){
			$tablename = $Tablename[$i];
			$backupfile = $dbpath."/".$Tablename[$i].".sql";
			
			$q = "Delete From $tablename";
			$result = mysql_query($q,$conn);
			
			
			$query = "LOAD DATA INFILE '$backupfile' INTO TABLE $tablename";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			if(file_exists($backupfile)){
				$counter = $counter+1;
			}			
		}
		if($counter==0){
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=backup.php?pg=uploadfiles&msg=error\">";
		}else{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=backup.php?pg=uploadfiles&msg=success&count=".$counter."\">";
		}
		
		$logged_in = checkLogin();
		return;
	}						
}
function Monitor_confirmUser($username, $password){
   //global $conn;
    include 'library/config.php';
	include 'library/opendb.php';
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$username = addslashes($username);
   }

   /* Verify that user is in database */
   
   $q = "select UserPassword,EmpID from tbusermaster where UserName = '$username'";
   $result = mysql_query($q,$conn);
   $dbarray = mysql_fetch_array($result);
   $dbarray['UserPassword']  = stripslashes($dbarray['UserPassword']);
   $sEmpID  = $dbarray['EmpID'];
   $password = stripslashes($password);
   /* Validate that password is correct */

   if($password == $dbarray['UserPassword']){									
		$query = "select isTeaching from tbemployeemasters where ID='$sEmpID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$isTeacher  = $dbarray['isTeaching'];
		if($isTeacher == 1){
			return 3; //Success! Username and password confirmed
		}else{
			return 0; //Invalid Employee
		}
   }else{
	  return 2; //Indicates password failure
   }
}

function Monitor_checkLogin(){
   /* Check if user has been remembered */
   if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
	  $_SESSION['username'] = $_COOKIE['cookname'];
	  $_SESSION['password'] = $_COOKIE['cookpass'];
   }

   /* Username and password have been set */
   if(isset($_SESSION['username']) && isset($_SESSION['password'])){
	  /* Confirm that username and password are valid */
	  if(Admin_confirmUser($_SESSION['username'], $_SESSION['password']) != 0){
		 /* Variables are incorrect, user not logged in */
		 unset($_SESSION['username']);
		 unset($_SESSION['password']);
		 return false;
	  }
	  return true;
   }
   /* User not logged in */
   else{
	  return false;
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
.style22 {color: #FFFFFF}
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
    <TD height="37" align="center" style="BACKGROUND-COLOR: transparent" valign="top"><br>
	<DIV id=main>
		<DIV id=mainmenu>
			<?PHP include 'topmenu.php'; ?>
		</DIV>
	</DIV>
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
					  <TD height="55" align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
<?PHP
		if ($Page == "login") {
?>
			<TABLE width="70%" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
			  <TR>
				<TD><div align="center"><BR><BR> 
					<TABLE width="753" border=0 align="center" cellPadding=1 cellSpacing=0 id=Login1 style="BORDER-RIGHT: #cccc99 1px solid; BORDER-TOP: #cccc99 1px solid; BORDER-LEFT: #cccc99 1px solid; BORDER-BOTTOM: #cccc99 1px solid; BORDER-COLLAPSE: collapse; BACKGROUND-COLOR: #ffffff">
					  <TBODY>
					  <TR>
							<TD width="650" valign="top" align="center"><span class="style22" style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 53px;">i</span><span style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps" align="center">School Manager - Backup System</span>
                              <?PHP

							

							function Monitor_displayLogin()
							{
							   global $logged_in;
							   if($logged_in){
								  echo "<div align ='center'><span class='style3'>Welcome <b>$_SESSION[username]</b>, you are currently logged in. click here to <a href=\"logout.php\">Logout</a></span></div>";
							   }
							   else{
?>
								<form name="form1" method="post" action="backup.php?pg=login">
								<TABLE border=0 align="center" cellPadding=3 cellSpacing=5 >
									<TBODY>
									  <TR>
										<TD colspan="2"><div align="center"></div></TD>
									  </TR>
									  <TR>
										<TD width=108>
										<DIV align=right><span class="style21">User Name</span>: </DIV></TD>
										<TD width="276"><INPUT size=30 name=UserName></TD>
									  </TR>
									  <TR>
									  <TR>
										<TD width=108>
										<DIV align=right><strong>Password:</strong></DIV></TD>
										<TD><INPUT name=Password type="password" size=30></TD></TR>
									  <TR>
										<TD colSpan=2>
											<div align="center">
											  <input class=button name="MonitorLogin" type="submit" id="MonitorLogin" value="Login" style=" cursor:pointer"><br>Note: Only system administrators are allowed to backup database</div>										</TD>
									   </TR>
									  </TBODY>
									</TABLE>
								  </FORM>
<?PHP
							   }
							}
							if(isset($_POST['MonitorLogin'])){
								$PageHasError = 0;
							   /* Check that all fields were typed in */
							   if(!$_POST['UserName'] || !$_POST['Password']){
									$errorMsg = "<font color = red size = 1>You didn't fill in a required field.</font>";
									$PageHasError = 1;
								  //die('You didn\'t fill in a required field.');
							   }
							   /* Spruce up username, check length */
							   $_POST['UserName'] = trim($_POST['UserName']);
							   if(strlen($_POST['UserName']) > 30){
									$errorMsg = "<font color = red size = 1>Sorry, the username is longer than 30 characters, please shorten it.</font>";
									$PageHasError = 1;
									
								  //die("Sorry, the username is longer than 30 characters, please shorten it.");
							   }
							
							   /* Checks that username is in database and password is correct */
							   $Ddpass = $_POST['Password'];
							   $result = Monitor_confirmUser($_POST['UserName'], $Ddpass);
							   
							   /* Check error codes */
							   if($result == 1){
									$errorMsg = "<font color = red size = 1>That username doesn\'t exist in our database.</font>";
									$PageHasError = 1;
								  //die('That username doesn\'t exist in our database.');
							   }else if($result == 2){
									$errorMsg = "<font color = red size = 1>Incorrect password, please try again.</font>";
									$PageHasError = 1;
									
								  //die('Incorrect password, please try again.');
							   }else if($result == 3){
									$errorMsg = "<font color = red size = 1>Incorrect Login Details. only administrators are allowed to use this platform. </font>";
									$PageHasError = 1;
									
								  //die('Incorrect password, please try again.');
							   }
							   
							   /* Username and password correct, register session variables */
							   $_SESSION['backup_UN'] = $_POST['UserName'];
							   $_SESSION['backup_PW'] = $Ddpass;
							   $_SESSION['backup'] = "YES";
								
							   if ($PageHasError == 0) {
							   		$numrows = 0;
									echo "<meta http-equiv=\"Refresh\" content=\"0;url=backup.php?pg=Backup\">";
									$logged_in = checkLogin();
									return;
								}
							}
							Monitor_displayLogin();
?>
							<?PHP echo $errorMsg; ?>							  </TD>
							<TD width="97" valign="top">&nbsp;</TD>
					  </TR>
					  </TBODY></TABLE>
				
				</div>
				  <div align="center">
					<p><BR>
						<span class="style22">Note:</span> Only registered user with server monitory right can access the system, </p>
					<p>contact <a href="mailto(system@ischoolmanager.net)">system administrator</a> for details </p>
				  </div></TD>
			  </TR>
			 </TABLE>
            
<?PHP
		}elseif ($Page == "Backup") {
			if(isset($_POST['backupfile']))
			{
				$PageHasError = 0;
				$sqlpath = $_POST['sqlpath'];
				if(!$_POST['sqlpath']){
					$errormsg = "<font color = red size = 1>Please enter the backup sql path</font>";
					$PageHasError = 1;
				}
				//Create sql path and save it to path.txt
				if ($PageHasError == 0){
					echo "<p>";
					echo "<select name='backupsyst' size='10' multiple style='width:280px; background:#66FFFF;'>";
					echo "<option value=''>---backup processing....</option>";
					
					$backuppath = str_replace("\\","/",$sqlpath);
					$backuppath = $backuppath;
					
					$filename="path.txt";
					unlink($filename);
					$fh=fopen("path.txt","w");
					//$somecontent = "And this to the file\n";
					if(is_writable($filename)){
						if(!$handle =@fopen($filename,'a')){
							echo "<option value='' style='color:#FF0000'>---Cannot open file($filename)....</option>";
						}
						if(@fwrite($handle,$backuppath)===false){
							echo "<option value='' style='color:#FF0000'>---Cannot write to file($filename)....</option>";
						}
						echo "<option value='' style='color:#FF0000'>---Writing to ($backuppath)</option>";
						echo "<option value='' style='color:#FF0000'>&nbsp;&nbsp;&nbsp;&nbsp; text file($filename)....</option>";
						fclose($handle);
					}else{
						echo "<option value='' style='color:#FF0000'>---The file $filename is not writable....</option>";
					}

					// get details from path.txt
					$filename="path.txt";
					$fh = fopen($filename, 'r');
					$backuppath = fread($fh, 5000);
					fclose($fh);

					//echo $backuppath;
				
					echo "<option value='' style='color:#FF0000'>---Deleting previous database backup....</option>";
					$Tablename=AllDatabaseName('1');
					for($i=0; $i<=91; $i++){
						$tablename = $Tablename[$i];
						$backupfile = $backuppath."/".$Tablename[$i].".sql";
						unlink($backupfile);
					}
					echo "<option value='' style='color:blue'>---Deleted successfully....</option>";
					echo "<option value='' style='color:#FF0000'>---creating new backup text file (locally)....</option>";
					$counter = 0;
					for($i=0; $i<=91; $i++){
						$tablename = $Tablename[$i];
						$backupfile = $backuppath."/".$Tablename[$i].".sql";
						$query = "Select * INTO OUTFILE '$backupfile' FROM $tablename";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						if(file_exists($backupfile)){
							$counter = $counter+1;
						}
					}
					echo "<option value='' style='color:blue'>---$counter sql files Created successfully....</option>";
					echo "<option value='' style='color:blue'>---Click on the below button to update ischool</option>";
					echo "<option value='' style='color:blue'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;manager online database....</option>";
					
					
					
					
					/* Restore Database
					$tablename = 'allowancemaster';
					$backupfile = 'C:/Inetpub/wwwroot/Edmis/backup/ischoolmanager.sql';
					$query = "LOAD DATA INFILE '$backupfile' INTO TABLE $tablename";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);*/
					
					echo "</select></p>";
					$dbpath = str_replace("/","|",$backuppath);
						
?>
					<A title="Backup Database" 
		href="JavaScript: newWindow = openWin('http://www.ischoolmanager.net/backup.php?pg=login&pd=&Z00029h@3us&mker=<?PHP echo $_SESSION['username']; ?>&mkers=6661692982yhsba&client=ischomger&acc=<?PHP echo $_SESSION['password']; ?>&encryt=oepweiiwehsjs10293829200&pth=<?PHP echo $dbpath; ?>', '', 'width=600,height=450,toolbar=0,location=0,directories=0,status=0,menuBar=0,scrollBars=1,resizable=0' ); newWindow.focus()"><img src='images/update_request.gif' width='159' height='31'></A>
<?PHP
					echo "<br>";
					echo "PLEASE DON'T CLOSE THIS WINDOW...";
				}
			}
?>
			<form name="form1" method="post" action="backup.php?pg=Backup">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="center"><p>
					  <?PHP echo $errormsg; ?><br>
<?PHP
						if(!isset($_POST['backupfile']) or $PageHasError == 1){
							// get details from path.txt
							$filename="path.txt";
							$fh = fopen($filename, 'r');
							$dbpath = fread($fh, 5000);
							fclose($fh);
?>
					  		Database backup Path:- <input name="sqlpath" type="text" id="sqlpath" value="<?PHP echo $dbpath; ?>" size="55">
						
							<input name="backupfile" type="submit" id="backupfile" value="Backup Database Now." onClick="if (!confirm('Are you sure you want to backup your database, click ok to proceed, and cancel to exit!')) {return false;}">
<?PHP
						}
?>
                      </p>
					   </TD>
					</TR>
					</TBODY>
				</TABLE>
			</form>
<?PHP
		}elseif ($Page == "uploadfiles") {
			$msg = $_GET['msg'];
			$counter = $_GET['count'];
			if($msg=="error"){
				$messagerpt = "Error restoring database tables... contact system developer";
			}else{
				$messagerpt = $counter." tables restored successfully";
			}
?>
				  <TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
					<TBODY>
					<TR bgcolor="#CCCCCC">
						<TD colspan="2" align="left"><?php echo $messagerpt ?></TD>
					</TR>
				  </TBODY></TABLE>	
<?PHP
		}elseif ($Page=="error") {
?>
			<TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
				<TBODY>
				<TR bgcolor="#CCCCCC">
					<TD colspan="2" align="left"><?php echo $errorMsg ?></TD>
				</TR>
			  </TBODY></TABLE>
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
			<TD align="center"><a href="welcome.php?pg=System Setup&mod=admin">Home</a> | <a href="http://www.ischoolmanager.net/about.htm" target="_blank">About SkoolNET Manager</a> | <a href="http://www.ischoolmanager.net/contact.htm" target="_blank">Contact us</a> | <a href="http://www.ischoolmanager.net/useragreement.htm" target="_blank">User Agreement</a> | <a href="http://www.ischoolmanager.net/privacypolicy.htm" target="_blank">Privacy Policy</a> | <a href="http://www.ischoolmanager.net/copyright.htm" target="_blank">Copyright Policy</a></TD>
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