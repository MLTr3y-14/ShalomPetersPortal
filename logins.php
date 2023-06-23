<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent,$LimitDate;
	include 'library/config.php';
	include 'library/opendb.php';
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['mker']))
	{
		$UserName = $_GET['mker'];
		$Ddpass = $_GET['acc'];
		$result = Monitor_confirmUser($UserName, $Ddpass);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNET Manager</TITLE>
<META content="Viodvla.com is the official website of the Directorate for Road Traffic Services in Nigeria." name="Short Title">
<META content="Nigeria Centre for Road Traffic" name=AGLS.Function>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<LINK media=all href="css/rock.css" type=text/css rel=stylesheet>
<style type="text/css">
td img {display: block;}.style21 {font-weight: bold}
.style22 {
	color: #FF0000;
	font-style: italic;
}
</style>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<TABLE style="WIDTH: 100%" background="images/Top_pole.jpg">
<TBODY>
	<TR>
	  <TD height="55px" valign="top">
	  	<TABLE width="70%" border="0" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
		  <TR>
			<TD width="38%" align="left"><img src="images/edmis_logo.jpg" width="130" height="39"></TD>
			<TD width="62%" align="right"><a href="index.php">Home</a> | About SkoolNET Manager | Contact us</TD>
		  </TR>
		</TABLE>
	  </TD>
	</TR>
</TBODY>
</TABLE>
<TABLE width="100%" bgcolor="#f4f4f4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br><br>
<?PHP
		if ($Page == "Monitory") {
?>
			<TABLE width="70%" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
			  <TR>
				<TD><div align="center"><BR><BR> 
					<TABLE width="753" border=0 align="center" cellPadding=1 cellSpacing=0 id=Login1 style="BORDER-RIGHT: #cccc99 1px solid; BORDER-TOP: #cccc99 1px solid; BORDER-LEFT: #cccc99 1px solid; BORDER-BOTTOM: #cccc99 1px solid; BORDER-COLLAPSE: collapse; BACKGROUND-COLOR: #ffffff">
					  <TBODY>
					  <TR>
							<TD width="419" valign="top"><p><img src="images/login_monitoryserver.gif" width="452" height="54"></p>
<?PHP

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
								  		return 3; //Invalid Employee
									}else{
										return 0; //Success! Username and password confirmed
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

							function Monitor_displayLogin()
							{
							   global $logged_in;
							   if($logged_in){
								  echo "<div align ='center'><span class='style3'>Welcome <b>$_SESSION[username]</b>, you are currently logged in. click here to <a href=\"logout.php\">Logout</a></span></div>";
							   }
							   else{
?>
								<form name="form1" method="post" action="login.php?pg=Monitory">
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
											  <input class=button name="MonitorLogin" type="submit" id="MonitorLogin" value="Login" style=" cursor:pointer"><br></div>
										</TD>
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
							   $_POST['UserName'] = stripslashes($_POST['UserName']);
							   $_SESSION['username'] = $_POST['UserName'];
							   $_SESSION['password'] = $Ddpass;
							   $_SESSION['module'] = "Administrator";
								
							   if ($PageHasError == 0) {
							   		$numrows = 0;
									if($LimitDate >0){
										$errorMsg = "<font color = red size = 1>Access Denied...</font>";
										$PageHasError = 1;
									}else{
										$usrname = $_SESSION['username'];
										$q = "select EmpID from tbusermaster where UserName = '$usrname' and UserPassword = '$Ddpass'";
									   	$result = mysql_query($q,$conn);
									   	$dbarray = mysql_fetch_array($result);
									   	$sEmpID  = $dbarray['EmpID'];
										
							   			$Status="Login";
										$dat = date('Y'.'-'.'m'.'-'.'d');
										$Platform = "Monitory Server";
							   			$sys_mod = "Current User";
										
										
										$q = "Insert into tbmonitory(EmpID,UserName,Status,sDate,Platform,Module) Values ('$sEmpID','$usrname','$Status','$dat','$Platform','$sys_mod')";
										$result = mysql_query($q,$conn);
										
										echo "<meta http-equiv=\"Refresh\" content=\"0;url=monitor.php?pg=Current Users\">";
										$logged_in = checkLogin();
										return;
									}
								}
							}
							Monitor_displayLogin();
?>
							<?PHP echo $errorMsg; ?>

							  </TD>
							<TD width="328" valign="top"><img src="images/Mon_functionality.gif" width="317" height="195"></TD>
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
		}elseif ($Page == "Admin") {
?>
	
	  		<TABLE width="70%" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
			  <TR>
				<TD><div align="center"><BR><BR> 
					<TABLE width="753" border=0 align="center" cellPadding=1 cellSpacing=0 id=Login1 style="BORDER-RIGHT: #cccc99 1px solid; BORDER-TOP: #cccc99 1px solid; BORDER-LEFT: #cccc99 1px solid; BORDER-BOTTOM: #cccc99 1px solid; BORDER-COLLAPSE: collapse; BACKGROUND-COLOR: #ffffff">
					  <TBODY>
					  <TR>
							<TD width="419" valign="top"><p><img src="images/admin_monitoryserver.gif" width="452" height="54"></p>
<?PHP

							function Admin_confirmUser($username, $password){
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
			
							function Admin_checkLogin(){
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

							function Admin_displayLogin()
							{
							   global $logged_in;
							   if($logged_in){
								  echo "<div align ='center'><span class='style3'>Welcome <b>$_SESSION[username]</b>, you are currently logged in. click here to <a href=\"logout.php\">Logout</a></span></div>";
							   }
							   else{
?>
								  <form name="form1" method="post" action="login.php?pg=Admin">
									<TABLE border=0 align="center" cellPadding=3 
										cellSpacing=5 >
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
											  <input class=button name="AdminLogin" type="submit" id="AdminLogin" value="Login" style=" cursor:pointer">
											</div></TD></TR>
									  </TBODY>
									</TABLE>
								</FORM>
<?PHP
							   }
							}
							if(isset($_POST['AdminLogin'])){
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
							   $result = Admin_confirmUser($_POST['UserName'], $Ddpass);
							   
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
									$errorMsg = "<font color = red size = 1>Incorrect Login Details.</font>";
									$PageHasError = 1;
									
								  //die('Incorrect password, please try again.');
							   }
							   
							   /* Username and password correct, register session variables */
							   $_POST['UserName'] = stripslashes($_POST['UserName']);
							   $_SESSION['username'] = $_POST['UserName'];
							   $_SESSION['password'] = $Ddpass;
							   $_SESSION['module'] = "Administrator";
								
							   if ($PageHasError == 0) {
							   		$numrows = 0;
									if($LimitDate >0){
										$errorMsg = "<font color = red size = 1>Access Denied...</font>";
										$PageHasError = 1;
									}else{
										$usrname = $_SESSION['username'];
										$q = "select EmpID from tbusermaster where UserName = '$usrname' and UserPassword = '$Ddpass'";
									   	$result = mysql_query($q,$conn);
									   	$dbarray = mysql_fetch_array($result);
									   	$sEmpID  = $dbarray['EmpID'];
										
							   			$Status="Login";
										$dat = date('Y'.'-'.'m'.'-'.'d');
										$Platform = "Administrators";
							   			$sys_mod = "System Setup";
										
										
										$q = "Insert into tbmonitory(EmpID,UserName,Status,sDate,Platform,Module) Values ('$sEmpID','$usrname','$Status','$dat','$Platform','$sys_mod')";
										$result = mysql_query($q,$conn);
										
										echo "<meta http-equiv=\"Refresh\" content=\"0;url=welcome.php?pg=System Setup&mod=admin\">";
										$logged_in = Admin_checkLogin();
										return;
									}
								}
							}
							Admin_displayLogin();
?>
							<?PHP echo $errorMsg; ?>
							<BR>
					    </TD>
							<TD width="328" valign="top"><img src="images/Admin_functionality.gif" width="317" height="250"></TD>
					  </TR>
					  </TBODY></TABLE>
				
				</div>
				  <div align="center">
					<p><BR>
						<span class="style22">Note:</span> Only registered administrator can access the system, </p>
					<p>contact <a href="mailto(system@ischoolmanager.net)">system administrator</a> for details </p>
				  </div></TD>
			  </TR>
			 </TABLE>
<?PHP
		}elseif ($Page == "Teacher") {
?> 
	 
	 		<TABLE width="70%" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
			  <TR>
				<TD><div align="center"><BR><BR> 
					<TABLE width="753" border=0 align="center" cellPadding=1 cellSpacing=0 id=Login1 style="BORDER-RIGHT: #cccc99 1px solid; BORDER-TOP: #cccc99 1px solid; BORDER-LEFT: #cccc99 1px solid; BORDER-BOTTOM: #cccc99 1px solid; BORDER-COLLAPSE: collapse; BACKGROUND-COLOR: #ffffff">
					  <TBODY>
					  <TR>
							<TD width="419" valign="top"><p><img src="images/teacher_monitoryserver.gif" width="452" height="54"></p>
<?PHP
							function Teacher_confirmUser($username, $password){
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
								  		return 0; //Success! Username and password confirmed
									}else{
										return 3; //Invalid Employee
									}
							   }else{
								  return 2; //Indicates password failure
							   }
							}
			
							function Teacher_checkLogin(){
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

							function Teacher_displayLogin()
							{
							   global $logged_in;
							   if($logged_in){
								  echo "<div align ='center'><span class='style3'>Welcome <b>$_SESSION[username]</b>, you are currently logged in. click here to <a href=\"logout.php\">Logout</a></span></div>";
							   }
							   else{
?>
								<form name="form1" method="post" action="login.php?pg=Teacher">
								<TABLE border=0 align="center" cellPadding=3 
									cellSpacing=5 >
									  <TBODY>
								  <TR>
									<TD colspan="2"><div align="center"></div></TD>
								  </TR>
								  <TR>
									<TD width=108>
									<DIV align=right><span class="style21">User Name</span>: </DIV></TD>
									<TD width="276"><INPUT size=30 name="UserName"></TD>
								  </TR>
								  <TR>
								  <TR>
									<TD width=108>
									<DIV align=right><strong>Password:</strong></DIV></TD>
									<TD><INPUT name="Password" type="password" size=30></TD></TR>
								  <TR>
									<TD colSpan=2>
									  <div align="center">
										  <input class=button name="TeacherLogin" type="submit" id="TeacherLogin" value="Login" style=" cursor:pointer">
										</div></TD></TR>
								  </TBODY></TABLE></FORM>
<?PHP
							   }
							}
							if(isset($_POST['TeacherLogin'])){
								$PageHasError = 0;
							   /* Check that all fields were typed in */
							   if(!$_POST['UserName'] || !$_POST['Password']){
									$errorMsg = "<font color = red size = 1>You didn't fill in a required field.</font>";
									$PageHasError = 1;
								  //die('You didn\'t fill in a required field.');
							   }
							   /* Spruce up username, check length */
							   $_POST['Password'] = trim($_POST['Password']);
							   if(strlen($_POST['UserName']) > 30){
									$errorMsg = "<font color = red size = 1>Sorry, the username is longer than 30 characters, please shorten it.</font>";
									$PageHasError = 1;
									
								  //die("Sorry, the username is longer than 30 characters, please shorten it.");
							   }
							
							   /* Checks that username is in database and password is correct */
							   $Ddpass = $_POST['Password'];
							   $result = Teacher_confirmUser($_POST['UserName'], $Ddpass);
							   
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
									$errorMsg = "<font color = red size = 1>Incorrect login Details.</font>";
									$PageHasError = 1;
							   }
							   /* Username and password correct, register session variables */
							   $_POST['UserName'] = stripslashes($_POST['UserName']);
							   $_SESSION['username'] = $_POST['UserName'];
							   $_SESSION['password'] = $Ddpass;
							   $_SESSION['module'] = "Teacher";
								
							   if ($PageHasError == 0) {
							   		$numrows = 0;
									if($LimitDate >0){
										$errorMsg = "<font color = red size = 1>Access Denied...</font>";
										$PageHasError = 1;
									}else{
										$usrname = $_SESSION['username'];
										$q = "select EmpID from tbusermaster where UserName = '$usrname' and UserPassword = '$Ddpass'";
									   	$result = mysql_query($q,$conn);
									   	$dbarray = mysql_fetch_array($result);
									   	$sEmpID  = $dbarray['EmpID'];
										
							   			$Status="Login";
										$dat = date('Y'.'-'.'m'.'-'.'d');
										$Platform = "Teacher";
							   			$sys_mod = "System Setup";
										
										
										$q = "Insert into tbmonitory(EmpID,UserName,Status,sDate,Platform,Module) Values ('$sEmpID','$usrname','$Status','$dat','$Platform','$sys_mod')";
										$result = mysql_query($q,$conn);
										
										echo "<meta http-equiv=\"Refresh\" content=\"0;url=welcome.php?pg=System Setup&mod=admin\">";
										$logged_in = checkLogin();
										return;
									}
								}
							}
							Teacher_displayLogin();
?>
							<?PHP echo $errorMsg; ?>
					    </TD>
							<TD width="328" valign="top"><img src="images/teacher_functionality.gif" width="317" height="250"></TD>
					  </TR>
					  </TBODY></TABLE>
				
				</div>
				  <div align="center">
					<p><BR>
						<span class="style22">Note:</span> Only registered teachers  can access the system, </p>
					<p>contact <a href="mailto(system@ischoolmanager.net)">system administrator</a> for details </p>
				  </div></TD>
			  </TR>
			 </TABLE>
<?PHP
		}
?> 
	 
	 
	 
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
