<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent,$LimitDate;
	include '../library/config.php';
	include '../library/opendb.php';
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['adm']))
	{
		$adm = $_GET['adm'];
		$pwd = $_SESSION['password'];
		$ck = $_GET['ck'];
		if($ck != ""){
			$errorMsg = "<font color = red size = 1>Incorrect password, please try again.</font>";
			$PageHasError = 1;
		}else{
			$result = confirmUser($adm, $pwd);
			$_POST['SubmitLogin'] = "Submit";
			$_POST['UserName'] = $adm;
			$_POST['Passwrd'] = $pwd;
		}
	}
	$dat = date('Y'.'-'.'m'.'-'.'d');
	if($Page==""){
		$Page = "Admin";
	}
	$start=time();
    $_SESSION['time_start']=$start;
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
					<A id=bannerLink href="#" target=_new><DIV class=ImgLoginBanner style="DISPLAY: block; CURSOR: pointer">&nbsp;</DIVD></A>
				</TD></TR>
              <TR>
                <TD>
                  <DIV id=ZLoginAppName>Student Login  </DIV></TD></TR></TBODY></TABLE></TD></TR>
        <TR>
          <TD id=ZloginBodyContainer>
<?PHP
		function confirmUser($AdmNo, $passwrd){
		   //global $conn;		   
		   include '../library/config.php';
		   include '../library/opendb.php';
		   /* Add slashes if necessary (for query) */
		   if(!get_magic_quotes_gpc()) {
			$username = addslashes($username);
		   }
		
		   /* Verify that user is in database */
		   
		   $q = "select password,Stu_Class from tbstudentmaster where AdmissionNo = '$AdmNo'";
		   $result = mysql_query($q,$conn);
		   $dbarray = mysql_fetch_array($result);
		   $dbarray['password']  = stripslashes($dbarray['password']);
		   $StuClass  = $dbarray['Stu_Class'];
		   $passwrd = stripslashes($passwrd);
		   /* Validate that password is correct */
	
		   if($passwrd == $dbarray['password']){
				return 0; //valid Student
		   }else{
			  return 2; //Indicates password failure
		   }
		   
		}

		function checkLogin(){
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
		   	include '../library/config.php';
		    include '../library/opendb.php';
?>
            <DIV id=ZloginFormPanel>
			<form name="form1" method="post" action="login.php">
            <TABLE cellPadding=4 width="100%">
              <TBODY>
              <TR>
                <TD class=zLoginLabelContainer><LABEL for=username>Please enter your Student Admn. No:</LABEL></TD>
                <TD class=zLoginFieldContainer colSpan=2><INPUT 
                  class="zLoginField" id="username" name="UserName"> </TD></TR>
              <TR>
                <TD class=zLoginLabelContainer><LABEL 
                  for=password>Password:</LABEL></TD>
                <TD class=zLoginFieldContainer colSpan=2><INPUT 
                  class="zLoginField" id="password" type="password" name="Passwrd"> 
              </TD></TR>
              <TR>
                <TD class=zLoginLabelContainer></TD>
                <TD>
                  <TABLE width="100%">
                    <TBODY>
                    <TR>
                      <TD><INPUT id=remember type=checkbox value=1 
                        name=zrememberme></TD>
                      <TD class=zLoginCheckboxLabelContainer><LABEL 
                        for=remember>Remember me on this computer</LABEL></TD></TR></TBODY></TABLE></TD>
                <TD><INPUT class="zLoginButton" type="submit" value="Log In" name="SubmitLogin"></TD></TR></TBODY></TABLE>
            <TABLE width="100%">
              <TBODY>
              <TR>
                <TD noWrap align=middle>
                  <DIV class=ZLoginSeparator style="MARGIN-TOP: 0px"></DIV>Select Session?&nbsp; 
				   <select name="OptSession" onChange="javascript:setTimeout('__doPostBack(\'OptSession\',\'\')', 0)">
						  <option value="" selected="selected">Select</option>
<?PHP
							$query = "select * from session order by SessionName";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "No Session Found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$SessID = $row["ID"];
									$SessionName = $row["SessionName"];
									if($OptSession =="$SessID"){
?>
										<option value="<?PHP echo $SessID; ?>" selected="selected"><?PHP echo $SessionName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $SessID; ?>"><?PHP echo $SessionName; ?></option>
<?PHP
									}
								}
							}
?>
						</select>
                   </TD></TR>
              <TR>
                <TD id=ZloginClientLevelContainer noWrap></TD></TR>
              <TR>
                <TD id=ZloginLicenseContainer noWrap>Copyright © 2010 
                  SkoolNet Manager All Rights Reserved.</TD>
			  </TR>
			 </TBODY>
		</TABLE></FORM>
<?PHP
		   }
		}
		if(isset($_POST['SubmitLogin'])){
			$PageHasError = 0;
		   /* Check that all fields were typed in */
		   if(!$_POST['UserName'] || !$_POST['Passwrd']){
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
		   $Ddpass = $_POST['Passwrd'];
		   $result = confirmUser($_POST['UserName'], $Ddpass);
		   
		   /* Check error codes*/ 
		   if($result == 1){
				$errorMsg = "<font color = red size = 1>That username doesn\'t exist in our database.</font>";
				$PageHasError = 1;
			  //die('That username doesn\'t exist in our database.');
		   }else if($result == 2){
				$errorMsg = "<font color = red size = 1>Incorrect password, please try again.</font>";
				$PageHasError = 1;
				
			  //die('Incorrect password, please try again.');
		   }
		   /* Username and password correct, register session variables */
		    $_POST['UserName'] = stripslashes($_POST['UserName']);
		    $_SESSION['AdmNo'] = $_POST['UserName'];
		    $_SESSION['password'] = $Ddpass;
		    $_SESSION['StuSession'] = $_POST['OptSession'];
			
		   if ($PageHasError == 0) {
				$numrows = 0;
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=welcome.php?pg=Student Blog\">";
				$logged_in = checkLogin();
				return;
			}
		}
		Admin_displayLogin();
?>
		<?PHP echo $errorMsg; ?>
		</DIV></TD></TR></TBODY></TABLE></DIV></TD></TR></TBODY></TABLE>
</BODY></HTML>
