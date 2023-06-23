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
	$dat = date('Y'.'-'.'m'.'-'.'d');
	
	
    $start=time();
    $_SESSION['time_start']=$start;
    /*get the login info & set to session */ 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML 
xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<TITLE>Student Online Learning System @ SkoolNET</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">

<LINK media=screen href="css/css_uow_colour.css" type=text/css rel=stylesheet>
<LINK media=screen href="css/css_uow_base.css" type=text/css rel=stylesheet>


<!-- SS_END_SNIPPET(fragment1,stylesheets)--><!-- SS_BEGIN_SNIPPET(fragment5,javascript)-->
<SCRIPT src="css/js_jquery.js" 
type=text/javascript></SCRIPT>

<SCRIPT src="css/js_jquery_plugin_cycle.js" 
type=text/javascript></SCRIPT>

<SCRIPT src="css/js_jquery_plugin_clearinput.js" 
type=text/javascript></SCRIPT>

<SCRIPT src="css/js_jquery_plugin_colorbox.js" 
type=text/javascript></SCRIPT>

<SCRIPT src="css/js_jquery_plugin_sspng.js" 
type=text/javascript></SCRIPT>

<SCRIPT src="css/ga.js" type=text/javascript></SCRIPT>

<SCRIPT type=text/javascript>
// This code is only included if nodeId = 1.
// Gogo IE6 Hacks
$(document).ready(function(){
	$('body').supersleight(); 
});

// This does the search box input magic.
$(document).ready(function(){
	$('#searchbox').clearingInput(); 
});

// This does the gallery magic for the content pages.
$(document).ready(function(){
	$("a[rel='gallery']").colorbox({
		maxWidth:'800',
		maxHeight:'600'
	});
});

</SCRIPT>
<META content="MSHTML 6.00.2900.2096" name=GENERATOR></HEAD>
<BODY>
<DIV id=header>
	<DIV id=top-menus>
		<DIV id=uowlogo>
			<A href="welcome.php?pg=Student Blog"><IMG src="Images/img_logo_lpc.png" alt="SkoolNet Manager" title="SkoolNet Manager"></A>
		</DIV>
		<DIV id=search>
			<FORM style="FLOAT: right" action="" method=get>
				<DIV class=left><INPUT class=search_input id=searchbox value=Search... name=query> </DIV>
				<DIV class=left>
					<INPUT title=Search style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; BORDER-BOTTOM-STYLE: none" 
			type=image src="Images/img_button_search_go.gif" name=search_button> 
				</DIV>
				<INPUT type=hidden value=alluow name=collection> 
				<INPUT type=hidden value=10 name=num_ranks> 
			</FORM>
		</DIV>
		<DIV id=top-links>
			<UL> 
			  <LI><A href="http://www.lagospastoralcollege.org">SkoolNET HOME</A> 
			  <LI><A href="ad/index.php" target="_blank"><STRONG>Staff Web Kiosk</STRONG></A> 
			
			  <LI><A 
			  href="http://www.lagospastoralcollege.org/page.php?pg=Contact Us&subpg=none" target="_blank"><STRONG>Contacts</STRONG></A> 
			  </LI>
			</UL>
		</DIV>
		<DIV id=section-title><IMG title="Student Online Learning System" src="Images/uow066001.png"></DIV>
		<DIV id=breadcrumb>
			<A href="index.php">Home</A> &gt; Current Students		</DIV>
	</DIV>
</DIV>
<DIV id=main>
	<DIV id=content-bg>
		<DIV id=leftcol>
			<DIV id=col-lefttop>
				<H4 style="CURSOR: pointer" onclick=$(this).next().toggle()>Student Central</H4>
				<DIV id=category1 style="DISPLAY: none"><NOSCRIPT></DIV>
				<DIV id=category1><NOSCRIPT></NOSCRIPT>
					<UL class=navLinks>
					  <LI><A href="../aboutsols.php" target=_self>About SOLS</A> 
					  <LI><A href="index.php" target=_self>Login to SOLS</A>
					  <LI><A href="refuse.php?pg=Find a Faculty Member" target=_self>Find a Faculty Member</A> 
					  <LI><A href="refuse.php?pg=Manage my SOLS Password" target=_self>Manage my SOLS Password</A> 
					  <LI><A href="refuse.php?pg=Fees and Assistance" target=_self>Fees and Assistance</A>
					  <LI><A href="registration.php" target=_self>Registration</A></LI>
					  <LI><A href="refuse.php?pg=eLearning" target=_self>eLearning</A></LI>
					</UL>
				</DIV>
				<H4 style="CURSOR: pointer" onclick=$(this).next().toggle()>Study Resources</H4>
				<DIV id=category2 style="DISPLAY: none"><NOSCRIPT></DIV>
				<DIV id=category2><NOSCRIPT></NOSCRIPT>
					<UL class=navLinks>
					  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Programs&subpg=none" target=_blank>Study Information</A>
					  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=HomePage&subpg=News @ SkoolNET" target=_blank>Session &amp; Key Dates</A> 
					  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Fees&subpg=none" target=_blank>Fees</A> </LI>
					</UL>
				</DIV>
				<H4 style="CURSOR: pointer" onclick=$(this).next().toggle()>Staff Resources</H4>
				<DIV id=category3 style="DISPLAY: none"><NOSCRIPT></DIV>
				<DIV id=category3><NOSCRIPT></NOSCRIPT>
					<UL class=navLinks>
					  <LI><A href="#" target=_self>Manage Staff Account (Email &amp; Internet)</A> 
					  <LI><A href="#" target=_self>Help for eLearning</A> 
					  <LI><A href="#" target=_self>Staff Intranet</A> 
					  <LI><A href="#" target=_self>Forgotten Password</A> 
					  <LI><A href="#" target=_self>Webmail</A> 
					  <LI><A href="#" target=_self>IT Support Help</A> 
					  <SCRIPT type=text/javascript>
							$(document).ready(function(){
								$("#category1").toggle();
							});
						</SCRIPT>
					  </LI>
					</UL>
				</DIV>
			</DIV>
			<DIV id=col-leftbtm><IMG title="Quick Links" alt="Quick Links" src="Images/title_quicklinks.gif"> 
			<UL id=quick-links>
			  <LI>&gt;&nbsp; <A href="http://www.lagospastoralcollege.org/page.php?pg=About SkoolNET&subpg=none" target=_blank>About SkoolNET</A> 
			  <LI>&gt;&nbsp; <A href="http://www.lagospastoralcollege.org/page.php?pg=Programs&subpg=none" target=_blank>Programs</A> 
			  <LI>&gt;&nbsp; <A href="http://www.lagospastoralcollege.org/page.php?pg=Curriculum&subpg=none" target=_blank>Curriculum</A> 
			  <LI>&gt;&nbsp; <A href="http://www.lagospastoralcollege.org/page.php?pg=Admissions&subpg=none" target=_blank>Admissions</A> 
			  <LI>&gt;&nbsp; <A href="http://www.lagospastoralcollege.org/page.php?pg=Fees&subpg=none" target=_blank>Fees</A> 
			  <LI>&gt;&nbsp; <A href="http://www.lagospastoralcollege.org/page.php?pg=Lectures&subpg=none" target=_blank>Lectures </A> </LI>
			  <LI>&gt;&nbsp; <A href="http://www.lagospastoralcollege.org/page.php?pg=Contact Us&subpg=none" target=_blank>Contact Us </A> </LI>
			</UL>
		</DIV>
	</DIV>
	<DIV id=maincontent>
		<DIV id=maincontent>
<?PHP
			if($Page =="Find a Faculty Member"){
?>
				<H1>Find a Faculty Member  </H1>
				<P>
				Use your username and password to access SOLS Central.</P>
				<p>Please note the following</p>
				<p><strong>Sorry access denied :  </strong>Only admitted student can use this function, if you've being admitted please complete the below form..</p>
<?PHP
			}elseif($Page =="Manage my SOLS Password"){
?>
				<H1>Manage my SOLS Password</H1>
				<P>Complete the below form.</P>
<?PHP
			}elseif($Page =="Examination"){
?>
				<H1>Examination</H1>
				<P>
				Use your username and password to access SOLS Central.</P>
				<p>Please note the following</p>
				<p><strong>Sorry access denied :  </strong>Only admitted student can use this function, if you've being admitted please complete the below form..</p>
<?PHP
			}elseif($Page =="eLearning"){
?>
				<H1>eLearning Platform</H1>
				<P>
				SOLs e-Learning platform is used by the college to communicate important information between student and lecturers. Students are advice to access the SOLS system at least once a day.</P>
				<p>Complete the below form..</p>
<?PHP
			}
?>
			<p><strong>Forgotten Password or username:  </strong> if you've forgotten your password, you will need to make a request in person at an authorised centre to have your password reset. You will need to bring the relevant student cards with you. It is not possible to do this via 
			telephone or email for security reasons.</P>
			<P>If you have any problems or questions please contact the system administrator or call telephone +2348 xxxx xxxx</P>
			<DIV class=boxBlue>
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
?>
			<form name="form1" method="post" action="index.php">
			<TABLE cellSpacing=0 width="80%" align=center border=0>
			  <TBODY>
			  <TR>
				<TD width="56%"><div align="right"><STRONG>Please enter your Student Admn. No </STRONG></div></TD>
				<TD width="44%"><INPUT size=25 name="UserName"></TD>
				</TR>
			  <TR>
				<TD width="56%"><div align="right"><STRONG>enter your password </STRONG></div></TD>
				<TD width="44%"><INPUT name="Passwrd" type="password" size=15></TD>
				</TR>
			  <TR>
				<TD  colspan="2" align="center"><div align="center">
				  <input name=SubmitLogin type=submit id="SubmitLogin" value=Login method="post">
				  </div></TD>
				</TR>
			  </TBODY>
			</TABLE>
			</FORM>
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
			</DIV>
			<DIV id=lastupdated>
			</DIV></DIV>
		</H1>
		<DIV style="MARGIN: -10px auto auto"></DIV>
		<DIV style="MARGIN: auto"></DIV>
	</DIV>
	<DIV id=rightcol>
		<DIV id=col-righttop>
			<DIV class=promoTitle>
				<H4>Tell us what you think</H4>
				<DIV class=promoContent>
					<P><A href="../feedback.php"><IMG alt=Feedback@UOW src="Images/uow069177.jpg"></A></P>
				</DIV>
			</DIV>
			<DIV class=promoTitle>
				<H4>SkoolNET Bulletin Board</H4>
				<DIV class=promoContent>
					<DIV style="PADDING-RIGHT: 1px; PADDING-LEFT: 1px; FONT-SIZE: 9px; PADDING-BOTTOM: 1px; PADDING-TOP: 1px" align=right target="_blank">&nbsp;<A href="#" target=_blank>SkoolNET Archive</A></DIV>
					<IFRAME id=solsFrame title="SOLS Bulletins" border=0 name=solsFrame marginWidth=0 marginHeight=0 src="../Images/webSkoolNET.htm" frameBorder=0 width=190 allowTransparency></IFRAME>
				</DIV>
			</DIV>
	  </DIV>
		<DIV id=col-rightbtm>
			<DIV class=featureRight><!-- SS_BEGIN_SNIPPET(region4_element3,featured_content_rgt)-->
				<DIV class=featureRight>
					<H4>Events Calendar</H4>
					<DIV id=currentStudent-right>
						<DIV class=iframe>
							<DIV class=smallLinks align=right><A href="#">View All Events</A></DIV>
								<IFRAME id=eventsFrame border=0 name=eventsFrame marginWidth=0 marginHeight=0 src="../Images/webCAL.htm" 
		frameBorder=0 width=185 height=125 allowTransparency></IFRAME>
							</DIV>
						</DIV>
					</DIV>
				</DIV>
			</DIV>
		</DIV>
	</DIV>
</DIV>
<DIV id=footer>
	<DIV class=footer>
		<DIV class=left>
			<UL>
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Programs&subpg=none" target="_blank">Programs</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=About SkoolNET&subpg=none" target="_blank">About SkoolNET</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Curriculum&subpg=none" target="_blank">Curriculum</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Lectures&subpg=none" target="_blank">Lectures</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Fees&subpg=none" target="_blank">Fees</A> </LI>
			</UL>
		</DIV>
		<DIV class=middle>
			<UL>
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=HomePage&subpg=Vision and Dreams Identification" target="_blank">Vision and Dreams Identification</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=HomePage&subpg=Excellent and Relevant Training" target="_blank">Excellent and Relevant Training</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=HomePage&subpg=Supernatural Equipping" target="_blank">Supernatural Equipping</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=HomePage&subpg=Ministry Mentoring Program" target="_blank">Ministry Mentoring Program</A></LI>
			</UL>
		</DIV>
		<DIV class=right>
			<UL>
			  <LI><A href="http://www.lagospastoralcollege.org/#">News &amp; Media</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/#">About the founder</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=download&subpg=none">Downloads</A> 
			  <LI><A href="http://www.lagospastoralcollege.org/page.php?pg=Contact Us&subpg=none">Contact Us</A>
			  </LI>
		  </UL>
		</DIV>
	</DIV>
	<DIV class=footer>
		<DIV class=left>
			<P>Communion Pastoral & Missions College<BR>2nd Avenue, P.M.B. 019 Festac Town, <BR>Lagos, Nigeria +234.1.2811657, 01-879333</P>
		</DIV>
		<DIV class=middle>© 2010 Communion Pastoral & Missions College<BR>Powered by <A href="http://www.nexzoninvestment.com" target="_blank">Nexzon Investment Limited</A></DIV>
		<DIV class=right>
			<P>Email: info@lagospastoralcollege.org<BR>
				<A href="#">Privacy</A>, 
				<A href="#">Disclaimer &amp; Copyright Info</A><BR>Feedback: 
				<A href="#">Site: www.lagospastoralcollege.org</A>
			</P>
		</DIV>
	</DIV>
</DIV>
</BODY></HTML>
