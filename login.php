<?PHP
       // session_start();

	global $conn;
	global $dbname;
	global $userNames;
	global $errorMsg, $PPContent,$LimitDate;
	include 'library/config.php';
	include 'library/opendb.php';
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
/*	if(isset($_GET['mker']))
	{
		$UserName = $_GET['mker'];
		$Ddpass = $_GET['acc'];
		$result = Monitor_confirmUser($UserName, $Ddpass);
	}
	if(isset($_POST['Go']))
	{	
		$Page = $_POST['OptPlatform'];
		if($Page == "Student"){
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=smp/login.php\">";
			exit;
		}
	}*/
		$Page = "Admin";
      
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>SkoolNet Manager :: School Management System</TITLE>
<META http-equiv=Content-Type content=text/html;charset=utf-8>
<META 
content="width=320; initial-scale=1.0; maximum-scale=8.0; user-scalable=1;" 
name=viewport>
<META 
content="SkoolNet Manager, School Management System Nexzon Investment://www.nexzoninvestment.com." 
name=description><LINK 
href="css/login.css" type=text/css 
rel=stylesheet><LINK href="images/skoolnet.tif" rel="SHORTCUT ICON">
<META content="MSHTML 6.00.2900.2096" name=GENERATOR></HEAD>
<BODY onload=onLoad();>
<TABLE style="HEIGHT: 100%" width="100%">
  <TBODY>
  <TR>
    <TD vAlign=center align=middle>
      <DIV id=ZloginPanel>
      <TABLE width="100%">
        <TBODY>
        <TR>
          <TD>
            <TABLE width="100%">
              <TBODY>
              <TR>
                <TD vAlign=center align=middle>
					<A id=bannerLink href="#" target=_new><DIV class=ImgLoginBanner style="DISPLAY: block; CURSOR: pointer">&nbsp;</DIV></A>
				</TD></TR>
              <TR>
                <TD>
                  <DIV id=ZLoginAppName><?PHP echo $Page; ?> Platform  </DIV></TD></TR></TBODY></TABLE></TD></TR>
        <TR>
          <TD id=ZloginBodyContainer>
            <DIV id=ZloginFormPanel>
<?PHP
		if ($Page == "Admin") {
				function Admin_confirmUser($username, $password){
				   //global $conn;
				   include 'library/config.php';
					include 'library/opendb.php';
				   /* Add slashes if necessary (for query) */
				   if(get_magic_quotes_gpc()) {
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

				//function Admin_checkLogin(){
				   /* Check if user has been remembered */
				 //  if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
				//	  $_SESSION['username'] = $_COOKIE['cookname'];
				//	  $_SESSION['password'] = $_COOKIE['cookpass'];
				//   }
				
				   /* Username and password have been set */
				//   if(isset($_SESSION['username']) && isset($_SESSION['password'])){
					  /* Confirm that username and password are valid */
				//	  if(Admin_confirmUser($_SESSION['username'], $_SESSION['password']) != 0){
						 /* Variables are incorrect, user not logged in */
				//		 unset($_SESSION['username']);
				//		 unset($_SESSION['password']);
				//		 return false;
				//	  }
				//	  return true;
				//   }
				   /* User not logged in */
				//   else{
				//	  return false;
				//   }
				//}

				function Admin_displayLogin()
				{
				  // global $logged_in;
				  // if($logged_in){
				//	  echo "<div align ='center'><span class='style3'>Welcome <b>$_SESSION[username]</b>, you are currently logged in. click here to <a //href=\"logout.php\">Logout</a></span></div>";
			//	   }
			//	   else{
?>
					<FORM name="form1" action="login.php?pg=Admin" method="post">
					<TABLE cellPadding=4 width="100%">
					  <TBODY>
					  <TR>
						<TD class=zLoginLabelContainer><LABEL for=username>Admin Username:</LABEL></TD>
						<TD class=zLoginFieldContainer colSpan=2><INPUT 
						  class="zLoginField" id="UserName" name="UserName"> </TD></TR>
					  <TR>
						<TD class=zLoginLabelContainer><LABEL 
						  for=password>Password:</LABEL></TD>
						<TD class=zLoginFieldContainer colSpan=2><INPUT 
						  class="zLoginField" id="Password" type="password" name="Password"> 
					  </TD></TR>
					  <TR>
						<TD class=zLoginLabelContainer></TD>
						<TD>
						  <TABLE width="100%">
							<TBODY>
							<TR>
							 <TD width="6%"><INPUT id="remember" type="checkbox" value="1" name="rememberme"></TD>
							  <TD width="94%" class="zLoginCheckboxLabelContainer"><LABEL for=remember>Remember me on this computer</LABEL></TD>
							 </TR>
							</TBODY>
						  </TABLE>
						 </TD>
						<TD><INPUT class="zLoginButton" type="submit" value="Log In" name="AdminLogin"></TD>
					</TR>
					</TBODY>
					</TABLE>
					</FORM>
<?PHP
				  // }
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
						$PageHasError = 0;
						
					  //die('Incorrect password, please try again.');
				   }
				   
				   /* Username and password correct, register session variables */
				   $_POST['UserName'] = stripslashes($_POST['UserName']);
				  // $_SESSION['username'] = $_POST['UserName'];
				   $userNames = $_POST['UserName'];
				  // $_SESSION['password'] = $Ddpass;
				  // $_SESSION['module'] = "Administrator";
					
				   if ($PageHasError == 0) {
						/*$numrows = 0;
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
							//$logged_in = Admin_checkLogin();
							return;
						}*/
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=welcome.php?pg=System Setup&mod=admin&UserName=$userNames\">";
					}
				}
				Admin_displayLogin();
?>
				<?PHP echo $errorMsg; ?>
				<div align="center">
					<p><BR>
						<span class="style22">Note:</span> Only registered administrator can access the system, </p>
					<p>contact <a href="mailto(info@dianetsolutions.com)">system administrator</a> for details </p>
					<p> For <b>demo acess</b> use the following criteria: </p>
					<p> <b>Admin User name:</b> admin, <b>Password:</b> tonico </p>
				  </div>
<?PHP
		}
		
		?>
		
					
				<FORM name="form1" action="login.php" method="post">
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
            <TABLE width="100%">
              <TBODY>
              <TR>
                <TD noWrap align=middle>
                  <DIV class=ZLoginSeparator style="MARGIN-TOP: 0px"></DIV>Which 
                  platform would you like to use?&nbsp; 
				  <select name="OptPlatform" onChange="javascript:setTimeout('__doPostBack(\'OptPlatform\',\'\')', 0)">
				  	<OPTION value="Admin" selected>Default</OPTION> 
					<OPTION value="Admin">Administrators</OPTION> 
					<OPTION value="Teacher">Teachers</OPTION>
					<OPTION value="Monitory">Monitory Server</OPTION> 
					<OPTION value="Student">Student</OPTION>
				  </SELECT>
				  <label>
				  <input type="submit" name="Go" value="...">
				  </label>                   </TD>
              </TR>
              <TR>
                <TD id=ZloginClientLevelContainer noWrap></TD></TR>
              <TR>
                <TD id=ZloginLicenseContainer noWrap>Copyright © 2016 
                  SkoolNet Manager All Rights Reserved.</TD>
			  </TR>
			 </TBODY>
		</TABLE></FORM>
		</DIV></TD></TR></TBODY></TABLE></DIV></TD></TR></TBODY></TABLE>
</BODY></HTML>
