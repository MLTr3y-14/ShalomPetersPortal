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
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
		//$audit=update_Monitory('Login','Teacher',$Page);
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
		//$audit=update_Monitory('Login','Administrators',$Page);
	}
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 20;
	}
	//Get School Report Header
	$query = "select ID,SchName,HeaderPic from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpCode  = $dbarray['ID'];
	$SchName  = $dbarray['SchName'];
	$HeaderPic  = $dbarray['HeaderPic'];
	
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	if(isset($_POST['SubmitPrint']))
	{		
		$OptType = $_POST['OptType'];
		$FrmDate = $_POST['FrmDate'];
		$ToDate = $_POST['ToDate'];
		$PageHasError=0;
		$Result = Date_Comparison($FrmDate,$ToDate);
		if($Result == "false"){
			$errormsg = "<font color = red size = 1>Invalid Date Entry</font>";
			$PageHasError = 1;
		}
		if($FrmDate==""){
			$errormsg = "<font color = red size = 1>From Date is empty</font>";
			$PageHasError = 1;
		}
		if($ToDate==""){
			$errormsg = "<font color = red size = 1>To Date is empty</font>";
			$PageHasError = 1;
		}
		if($OptType==""){
			$errormsg = "<font color = red size = 1>Field type not selected</font>";
			$PageHasError = 1;
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNET Management</TITLE>
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
<style type="text/css">

.ds_box {
	background-color: #FFF;
	border: 1px solid #000;
	position: absolute;
	z-index: 32767;
}

.ds_tbl {
	background-color: #FFF;
}

.ds_head {
	background-color: #333;
	color: #FFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	text-align: center;
	letter-spacing: 2px;
}

.ds_subhead {
	background-color: #CCC;
	color: #000;
	font-size: 12px;
	font-weight: bold;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	width: 32px;
}

.ds_cell {
	background-color: #EEE;
	color: #000;
	font-size: 13px;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	cursor: pointer;
}

.ds_cell:hover {
	background-color: #F3F3F3;
} /* This hover code won't work for IE */

</style>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<?PHP include 'datecalander.php'; ?>
<TABLE width="100%" bgcolor="#f4f4f4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  	<TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
<?PHP
		if ($Page == "Current Users") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="classreport.php?subpg=Class charges"></a> <strong> </strong><strong><a href="libreport.php?subpg=Books in Library"> </a> | <a href="Logout.php">Logout</a> </strong>| Current Login Users</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="monitor.php?pg=Current Users">
				<TABLE width="100%" align="center" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="54%"  align="left"><p>Status Type: 
						  <select name="OptType">
							<option value="">Select Type</option>
<?PHP
							if($OptType == "All"){
?>
								<option value="<?PHP echo $OptType; ?>" selected="selected"><?PHP echo $OptType; ?></option>
								<option value="Login">Login</option>
								<option value="Logout">Logout</option>
<?PHP
							}elseif($OptType == "Login"){
?>
								<option value="All">All</option>
								<option value="<?PHP echo $OptType; ?>" selected="selected"><?PHP echo $OptType; ?></option>
								<option value="Logout">Logout</option>
<?PHP
							}elseif($OptType == "Logout"){
?>
								<option value="All">All</option>
								<option value="Login">Login</option>
								<option value="<?PHP echo $OptType; ?>" selected="selected"><?PHP echo $OptType; ?></option>
<?PHP
							}else{
?>
								<option value="All">All</option>
								<option value="Login">Login</option>
								<option value="Logout">Logout</option>
<?PHP
							}
?>
						</select></p>
					  </TD>
					  <TD width="46%" valign="top"  align="left"></TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left"><p> Date Interval		
					    <input onClick="ds_sh(this);" name="FrmDate" readonly="readonly" style="cursor: text" value="<?PHP echo $FrmDate; ?>"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						And &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input onClick="ds_sh(this);" name="ToDate" readonly="readonly" style="cursor: text" value="<?PHP echo $ToDate; ?>"/>
					    <input type="submit" name="SubmitPrint" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Print List&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;">
					  </p> </TD>
					</TR>
					</TBODY>
				</TABLE>
				</form>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps">MONITORY SERVER </div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="95%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="181" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Name</strong></font></td>
					  <td width="107" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>User Name </strong></font></td>
					  <td width="87" bgcolor="#666666" align="center"><strong><font color="#FFFFFF">Status</font></strong></td>
					  <td width="103" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Date</strong></font></td>
					  <td width="119" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Platform</strong></font></td>
					  <td width="230" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Module</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					if(isset($_POST['SubmitPrint']))
					{
						if($PageHasError==0){
							
							$arrDateList = date_range(DB_date($FrmDate),DB_date($ToDate));
							$i = 0;
							$counter = 0;
							while(isset($arrDateList[$i])){
								if($OptType == "All"){
									$query2 = "select * from tbmonitory where sDate = '$arrDateList[$i]'";
								}elseif($OptType == "Login"){
									$query2 = "select * from tbmonitory where sDate = '$arrDateList[$i]' and Status = 'Login'";
								}elseif($OptType == "Logout"){
									$query2 = "select * from tbmonitory where sDate = '$arrDateList[$i]' and Status = 'Logout'";
								}else{
									$query2 = "select * from tbmonitory where sDate = '$arrDateList[$i]'";
								}
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$counter = $counter+1;
										$monID = $row2["ID"];
										$EmpID = $row2["EmpID"];
										$UserName = $row2["UserName"];
										$Status = $row2["Status"];
										$sDate = $row2["sDate"];
										$Platform = $row2["Platform"];
										$Module = $row2["Module"];
										if($Status=="Login"){
											$bgs="#FFFFFF";
										}else{
											$bgs="#F2F2F2";
										}
										$EmpName= GET_EMP_NAME($EmpID);
	?>
										  <tr bgcolor="<?PHP echo $bgs; ?>">
											<td width="181" align="center"><?PHP echo $EmpName; ?></td>
											<td width="107" align="center"><?PHP echo $UserName; ?></td>
											<td width="87" align="center"><?PHP echo $Status; ?></td>
											<td width="103" align="center"><?PHP echo $sDate; ?></td>
											<td width="119" align="center"><?PHP echo $Platform; ?></td>
											<td width="230" align="center"><?PHP echo $Module; ?></td>
										  </tr>
	<?PHP
									}
								}
								$i=$i+1;
							}
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}
?>
	 </TABLE></TD>
  </TR></TBODY></TABLE>
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
