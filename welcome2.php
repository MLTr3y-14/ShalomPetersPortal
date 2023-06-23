<?PHP
	session_start();
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'function.php';
	if($_SESSION['module'] == "Teacher"){
		$Login = "Username: ".$_SESSION['username']; 
		$bg="#420434";
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpID  = $dbarray['EmpID'];
		$audit=update_Monitory('Login','Teacher','System Setup');
	}else{
		$Login = "Username: ".$_SESSION['username']; 
		$bg="maroon";
		$userNames = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$userNames'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpID  = $dbarray['EmpID'];
		
		$query1 = "select EmpName from tbemployeemasters where ID='$EmpID'";
		$result1 = mysql_query($query1,$conn);
		$dbarray = mysql_fetch_array($result1);
		$LoginEmpName  = $dbarray['EmpName'];
		
		$audit=update_Monitory('Login','Administrators','System Setup');
	}
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
		$SysModule = $_GET['mod'];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager</TITLE>
<META content="Educational Mangement System" name="Short Title">
<META content="Nigeria Centre for Road Traffic" name=AGLS.Function>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/menu.css" type=text/css rel=stylesheet>
<style type="text/css">
<!--
.style23 {color: #FFFFFF}
-->
</style>

<SCRIPT 
src="css/jquery-1.2.3.min.js" 
type=text/javascript></SCRIPT>

<SCRIPT 
src="css/menu.js" 
type=text/JavaScript></SCRIPT>

<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=350,height=200,scrollbars=yes');
return false;
}
//-->
</SCRIPT>
<!-- Calendar script -->
<script type="text/javascript" src="css/calendar.js"></script>
<!-- End of Calendar Script -->
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
			  <TD width="222" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="858" align="center">
			  	
				<BR>
				<TABLE style="HEIGHT: 123px">
					<TBODY>
					<TR>
					  <TD width="75%" style="VERTICAL-ALIGN: top; WIDTH: 60%; TEXT-ALIGN: right">
					<TABLE width="453" align="right">
					<TBODY>
					<TR>
					  <TD 
					  style="VERTICAL-ALIGN: top; WIDTH: 60%; HEIGHT: 110px; TEXT-ALIGN: right">SkoolNet Manager, built on the latest web technology provides the simplest 
						way for academic professional to interact with Student Records, Staff Records, Timetable and manage these records effectively.It facilitates real time management and statistical reports analysis.</TD>
					</TR>
					<TR>
					  <TD style="FONT-WEIGHT: bold; WIDTH: 60%; COLOR: Green; FONT-FAMILY: Tahoma; TEXT-ALIGN: right; FONT-VARIANT: small-caps">WebBase Performance Monitoring </TD>
					</TR>
					<TR>
					  <TD 
					  style="VERTICAL-ALIGN: top; WIDTH: 60%; HEIGHT: 130px; TEXT-ALIGN: right">Web 
						based centralize depository of performance which shows real 
						time information of every student and their academic performance. System administrators and school management can track these performances in realtime.</TD>
					</TR>
					<TR>
					  <TD height="24" style="FONT-WEIGHT: bold; WIDTH: 60%; COLOR: Green; FONT-FAMILY: Tahoma; TEXT-ALIGN: right; FONT-VARIANT: small-caps">Analysis 
						and Statistic Reporting </TD>
					</TR>
					<TR>
					  <TD style="VERTICAL-ALIGN: top; WIDTH: 60%;  TEXT-ALIGN: right"> 
						Web reporting empowers administrators and school management in analysing performances. 
						It provides helpful historical student and staff data presented in different format 
						that would help to improving service quality, time management as well 
						as forecasting and decision making. The reports are available 
						in daily, weekly, monthly, yearly and other periodic formats. 
					<BR></TD></TR></TBODY></TABLE>
					  </TD>
					  <TD width="25%">
					  	<TABLE style="WIDTH: 100%">
							<TBODY>
							<TR>
								<TD>
								<script type="text/javascript">calendar();</script></TD>
							</TR>
							<TR>
								<TD style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;"><img src="images/home-boy.gif" width="270" height="192">&nbsp;</TD>
							</TR>
							<TR>
								<TD style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
									<form name="form1" method="post" action="popup.php?pg=Send Message">
									
									<TABLE width="100%" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
									  <TBODY>
									  <TR>
										<TD colspan="2" align="center" valign="top"><strong><?PHP echo $errormsg; ?></strong></TD>
									  </TR>
									  <TR bgcolor="#666666">
										<TD colspan="2" align="center" valign="top"><div align="center" class="style21 style23"><strong>POP UP MESSAGE</strong></div></TD>
									  </TR>
									  <TR>
										<TD width="116" align="right" valign="top"><strong>Subject</strong></TD>
										<TD width="213" valign="top"><label>
										  <input type="text" name="Subject" value="<?PHP echo $Subject; ?>">
										</label></TD>
									  </TR>
									  <TR>
										<TD width="116" align="right" valign="top"><strong>Send To</strong></TD>
										<TD width="213" valign="top"><label>
										  <select name="OptEmp" style="width:200px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptEmp\',\'\')', 0)">
										  <option value="">&nbsp;</option>
<?PHP								
												$counter = 0;
												$query = "select ID,EmpName from tbemployeemasters where ID IN (Select EmpID from tbusermaster) order by EmpName";
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
														$EmpID = $row["ID"];
														$EmpName = $row["EmpName"];
														
														if($OptEmp =="$EmpID"){
?>
															<option value="<?PHP echo $EmpID; ?>" selected="selected"><?PHP echo $EmpName; ?></option>
<?PHP
														}else{
?>
															<option value="<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></option>
<?PHP
														}
													}
												}
?>
										</select><br>
<?PHP
											$n=0;
											$Counter = 0;
											while(isset($arrEmpList[$n])){
												if($arrEmpList[$n] !=""){
													$Counter = $Counter+1;
													$query = "select EmpName from tbemployeemasters where ID='$arrEmpList[$n]'";
													$result = mysql_query($query,$conn);
													$dbarray = mysql_fetch_array($result);
													$EmpName  = $dbarray['EmpName'];
											
													echo "<input type='checkbox' name='chk".$Counter."' value='".$arrEmpList[$n]."'>&nbsp;&nbsp;&nbsp;&nbsp;".$EmpName."<br>";
												}
												$n = $n+1;
											}
?>
										</label></TD>
									  </TR>
									  <TR>
										<TD width="116" align="right" valign="top"><strong>Message</strong></TD>
										<TD width="213" valign="top"><label>
										  <textarea name="Message" cols="30" rows="5"> <?PHP echo $Message; ?></textarea>
										</label></TD>
									  </TR>
									  <TR>
										<TD colspan="2" align="Center" valign="top"><label>
										  <input type="hidden" name="Total" value="<?PHP echo $Counter; ?>">
										  <input type="hidden" name="EmpList" value="<?PHP echo $EmpList; ?>">
										  <input type="submit" name="SubmitMessage" value="Send Message">
										</label></TD>
									  </TR>
									  </TBODY>
									</TABLE>
									</form>
									
									
							
								</TD>
							</TR>
							</TBODY>
						</TABLE>
					  </TD>
					</TR>
					</TBODY></TABLE>
			  </TD>
			</TR>
		</TBODY>
		</TABLE>
		<BR></TD>
	  </TR>
	 </TABLE>
      </TD></TR></TBODY></TABLE>


<!--<TABLE style="WIDTH: 100%" background="images/footer.jpg">-->
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
