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
		$Month = $_GET['mth'];
		$Year = $_GET['yr'];
	}
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
		$audit=update_Monitory('Login','Teacher',$Page);
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
		$audit=update_Monitory('Login','Administrator',$Page);
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
	if ($HeaderPic == ""){
		$HeaderPic = "empty_r2_c2.jpg";
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
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<TABLE width="100%" bgcolor="#f4f4f4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
	  </TR>
<?PHP
		if ($Page == "SalaryDetails") {
?>
		  <TR>
			<TD>
				<table width="90%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 90%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="6%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Sr/No</strong></font></td>
					  <td width="11%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Emp ID</strong></font></td>
					  <td width="23%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Name</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Basic Pay</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Addition</strong></font></td>
					  <td width="16%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Deduction</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Net Salary</strong></font></td>
					</tr>
					<tr>
					  <td colspan="7" align="left"><strong>PERIOD:&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo Get_Month_Name($Month)." &nbsp;&nbsp;"; ?> <?PHP echo $Year; ?> </strong></td>
					</tr>
<?PHP
					if(isset($_GET['ty']))
					{
						$OptType = $_GET['ty'];
						$Counter = 0;
						$SumBasicPay = 0;
						$SumDeductions = 0;
						$SumAdditions = 0;
						$SumNetPay = 0;
						
						$counter_rpt = 0;		
						if($OptType=="All"){
							$query2 = "select * from salary where SMonth='$Month' and SYear='$Year'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_rpt = $rstart;
							}else{
								$counter_rpt = $rstart-1;
							}
							$query = "select * from salary where SMonth='$Month' and SYear='$Year' LIMIT $rstart,$rend";
						}elseif($OptType=="Employee"){
							$OptEmp = $_GET['optEmp'];
							$query2 = "select * from salary where EmpId = '$OptEmp' And SMonth='$Month' And SYear='$Year'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_rpt = $rstart;
							}else{
								$counter_rpt = $rstart-1;
							}
							$query = "select * from salary where EmpId = '$OptEmp' And SMonth='$Month' And SYear='$Year' LIMIT $rstart,$rend";
						}elseif($OptType=="Category"){
							$OptCat = $_GET['Optcat'];
							$query2 = "select * from salary where SMonth='$Month' And SYear='$Year'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_rpt = $rstart;
							}else{
								$counter_rpt = $rstart-1;
							}
							$query = "select * from salary where SMonth='$Month' And SYear='$Year' LIMIT $rstart,$rend";
						}
						
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$Counter = $Counter+1;
								$EmpId = $row["EmpId"];
								$BasicPay = $row["BasicPay"];
								$totalDeductions = $row["totalDeductions"];
								$totalAdditions = $row["totalAdditions"];
								$NetPay = $row["NetPay"];
								$EmpName = GET_EMP_NAME($EmpId);
								$SumBasicPay = $SumBasicPay + $BasicPay;
								$SumDeductions = $SumDeductions + $totalDeductions;
								$SumAdditions = $SumAdditions + $totalAdditions;
								$SumNetPay = $SumNetPay + $NetPay;
?>
								<tr>
								  <td width="6%"  align="center"><?PHP echo $Counter; ?></td>
								  <td width="11%" align="center"><?PHP echo $EmpId; ?></td>
								  <td width="23%" align="center"><?PHP echo $EmpName; ?></td>
								  <td width="13%" align="center"><?PHP echo number_format($BasicPay,2); ?></td>
								  <td width="13%" align="center"><?PHP echo number_format($totalAdditions,2); ?></td>
								  <td width="16%" align="center"><?PHP echo number_format("-".$totalDeductions,2); ?></td>
								  <td width="18%" align="center"><?PHP echo number_format($NetPay,2); ?></td>
								</tr>
<?PHP
							}
						}
					}
?>
					<tr>
					  <td width="6%"  align="center">&nbsp;</td>
					  <td width="11%" align="center">&nbsp;</td>
					  <td width="23%" align="right"><hr><strong>Total</strong><hr></td>
					  <td width="13%" align="center"><hr><?PHP echo number_format($SumBasicPay,2); ?><hr></td>
					  <td width="13%" align="center"><hr><?PHP echo number_format($SumAdditions,2); ?><hr></td>
					  <td width="16%" align="center"><hr><?PHP echo number_format("-".$SumDeductions,2); ?><hr></td>
					  <td width="18%" align="center"><hr><?PHP echo number_format($SumNetPay,2); ?><hr></td>
					</tr>
					<tr>
					  <td colspan="7" align="center">
					  	<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="payroll_rpt.php?pg=SalaryDetails&mth=<?PHP echo $Month; ?>&yr=<?PHP echo $Year; ?>&ty=<?PHP echo $OptType; ?>&st=0&ed=<?PHP echo $rend; ?>&Optcat=<?PHP echo $OptCat; ?>&optEmp=<?PHP echo $OptEmp; ?>">First&nbsp;</a> |&nbsp;&nbsp;
						
						<a href="payroll_rpt.php?pg=SalaryDetails&mth=<?PHP echo $Month; ?>&yr=<?PHP echo $Year; ?>&ty=<?PHP echo $OptType; ?>&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optcat=<?PHP echo $OptCat; ?>&optEmp=<?PHP echo $OptEmp; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;
						
						<a href="payroll_rpt.php?pg=SalaryDetails&mth=<?PHP echo $Month; ?>&yr=<?PHP echo $Year; ?>&ty=<?PHP echo $OptType; ?>&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optcat=<?PHP echo $OptCat; ?>&optEmp=<?PHP echo $OptEmp; ?>">Next</a> </p>
						
						
						
						
					  </td>
					</tr>
					
			      </tbody>
			  </table>
			</TD>
		  </TR>
		  <TR>
			   <TD><a href="salary.php?subpg=Generate%20Salary">Back to previous Page </a></TD>
		   </TR>
<?PHP
		}elseif ($Page == "BankLetter") {
?>
		  <TR>
			<TD>
				<table width="90%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 90%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">
				  <tbody>
				  	<tr>
					  <td colspan="5" align="left"><strong>To : The Manager</strong></td>
					</tr>
					<tr>
					  <td colspan="5" align="Left" height="150px"><div><strong>Comment: </strong></div></td>
					</tr>
					<tr>
					  <td width="8%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Sr/No</strong></font></td>
					  <td width="31%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Bank A/c No</strong></font></td>
					  <td width="25%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Name</strong></font></td>
					  <td width="17%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Basic Pay</strong></font></td>
					  <td width="19%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Net Salary</strong></font></td>
					</tr>
					<tr>
					  <td colspan="5" align="left"><strong>PERIOD:&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo Get_Month_Name($Month)." &nbsp;&nbsp;"; ?> <?PHP echo $Year; ?> </strong></td>
					</tr>
<?PHP
					if(isset($_GET['ty']))
					{
						$OptType = $_GET['ty'];
						$Counter = 0;
						$SumBasicPay = 0;
						$SumDeductions = 0;
						$SumAdditions = 0;
						$SumNetPay = 0;
						
						$counter_rpt = 0;		
						if($OptType=="All"){
							$query2 = "select * from salary where SMonth='$Month' and SYear='$Year'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_rpt = $rstart;
							}else{
								$counter_rpt = $rstart-1;
							}
							$query = "select * from salary where SMonth='$Month' and SYear='$Year' LIMIT $rstart,$rend";
						}elseif($OptType=="Employee"){
							$OptEmp = $_GET['optEmp'];
							$query2 = "select * from salary where EmpId = '$OptEmp' And SMonth='$Month' And SYear='$Year'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_rpt = $rstart;
							}else{
								$counter_rpt = $rstart-1;
							}
							$query = "select * from salary where EmpId = '$OptEmp' And SMonth='$Month' And SYear='$Year' LIMIT $rstart,$rend";
						}elseif($OptType=="Category"){
							$OptCat = $_GET['Optcat'];
							$query2 = "select * from salary where SMonth='$Month' And SYear='$Year'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_rpt = $rstart;
							}else{
								$counter_rpt = $rstart-1;
							}
							$query = "select * from salary where SMonth='$Month' And SYear='$Year' LIMIT $rstart,$rend";
						}
						
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$Counter = $Counter+1;
								$EmpId = $row["EmpId"];
								$BasicPay = $row["BasicPay"];
								$NetPay = $row["NetPay"];
								
								$query2 = "select ID,EmpBankNo from tbemployeemasters where ID='$EmpId'";
								$result2 = mysql_query($query2,$conn);
								$dbarray2 = mysql_fetch_array($result2);
								$EmpCode  = $dbarray2['ID'];
								$EmpBankNo  = $dbarray2['EmpBankNo'];
								
								$EmpName = GET_EMP_NAME($EmpId);
								$SumBasicPay = $SumBasicPay + $BasicPay;
								$SumDeductions = $SumDeductions + $totalDeductions;
								$SumAdditions = $SumAdditions + $totalAdditions;
								$SumNetPay = $SumNetPay + $NetPay;
?>
								<tr>
								  <td width="8%"  align="center"><?PHP echo $Counter; ?></td>
								  <td width="31%" align="center"><?PHP echo $EmpBankNo; ?></td>
								  <td width="25%" align="center"><?PHP echo $EmpName; ?></td>
								  <td width="17%" align="center"><?PHP echo number_format($BasicPay,2); ?></td>
								  <td width="19%" align="center"><?PHP echo number_format($NetPay,2); ?></td>
								</tr>
<?PHP
							}
						}
					}
?>
					<tr>
					  <td width="8%"  align="center">&nbsp;</td>
					  <td width="31%" align="center">&nbsp;</td>
					  <td width="25%" align="right"><hr><strong>Total</strong><hr></td>
					  <td width="17%" align="center"><hr><?PHP echo number_format($SumBasicPay,2); ?><hr></td>
					  <td width="19%" align="center"><hr><?PHP echo number_format($SumNetPay,2); ?><hr></td>
					</tr>
					<tr>
					  <td colspan="5" align="center">
					  	<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="payroll_rpt.php?pg=SalaryDetails&mth=<?PHP echo $Month; ?>&yr=<?PHP echo $Year; ?>&ty=<?PHP echo $OptType; ?>&st=0&ed=<?PHP echo $rend; ?>&Optcat=<?PHP echo $OptCat; ?>&optEmp=<?PHP echo $OptEmp; ?>">First&nbsp;</a> |&nbsp;&nbsp;
						
						<a href="payroll_rpt.php?pg=SalaryDetails&mth=<?PHP echo $Month; ?>&yr=<?PHP echo $Year; ?>&ty=<?PHP echo $OptType; ?>&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optcat=<?PHP echo $OptCat; ?>&optEmp=<?PHP echo $OptEmp; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;
						
						<a href="payroll_rpt.php?pg=SalaryDetails&mth=<?PHP echo $Month; ?>&yr=<?PHP echo $Year; ?>&ty=<?PHP echo $OptType; ?>&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optcat=<?PHP echo $OptCat; ?>&optEmp=<?PHP echo $OptEmp; ?>">Next</a> </p>
					  </td>
					</tr>
					
			      </tbody>
			  </table>
			</TD>
		  </TR>
		  <TR>
			   <TD><a href="salary.php?subpg=Generate%20Salary">Back to previous Page </a></TD>
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
