<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'sms/sms_processor.php';
	include 'function.php';
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
	}
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
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
	
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	
	//Get School Report Header
	$query = "select ID,SchName,HeaderPic from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpCode  = $dbarray['ID'];
	$SchName  = $dbarray['SchName'];
	$HeaderPic  = $dbarray['HeaderPic'];
	
	//GET ACTIVE TERM
	/*$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];*/
	
	//Get School Report Header
	$query = "select * from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$sName  = $dbarray['SchName'];
	$sAddress  = $dbarray['SchAddress'];
	$sState  = $dbarray['SchState'];
	$sCountry  = $dbarray['SchCountry'];
	$sPhone  = $dbarray['SchPhone'];
	$SchEmail1  = $dbarray['SchEmail1'];
	$sEmail2  = $dbarray['SchEmail2'];
	$schLogo  = $dbarray['Logo'];
	if(isset($_GET['rid']))
	{
		$ReceiptNo = $_GET['rid'];
		$query3 = "select * from tbreceipt where ID='$ReceiptNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$dbarray3 = mysql_fetch_array($result3);
		$receiptDate  = $dbarray3['FeeDate'];	
		$AdmnNo  = $dbarray3['AdmnNo'];
		$PayMode  = $dbarray3['PayMode'];
		$FinedPayable =  $dbarray3['FinePayable'];
		$FinedPaid =  $dbarray3['FinePaid'];
		
		$query = "select Stu_Regist_No,Stu_Class,Stu_Full_Name,Stu_Type,Stu_Sec from tbstudentmaster where AdmissionNo='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$RegNo  = $dbarray['Stu_Regist_No'];
		$StudClass  = $dbarray['Stu_Class'];
		$StudName  = $dbarray['Stu_Full_Name'];
		$StudType  = $dbarray['Stu_Type'];
		$sTerm  = $dbarray['Stu_Sec'];
	}
	if(isset($_GET['src']))
	{
		$SrchMethod = $_GET['src'];
		$OptClass = $_GET['cls'];
		//$Term = $_GET['t1'];
		//$Term = $_GET['t2'];
		//$Term = $_GET['t3'];
	}
	if(isset($_GET['adm']))
	{
		$Stu_AdmnNo = $_GET['adm'];
		$Term = $_GET['t1'];
		$Term = $_GET['t2'];
		$Term = $_GET['t3'];
	}
	if(isset($_GET['ty']))
	{
		$SrchMethod = $_GET['ty'];
		$OptClass = $_GET['cls'];
	}
	if(isset($_GET['mth']))
	{
		$Method = $_GET['mth'];
	}
	if(isset($_GET['sms']))
	{
		$Sendsms = $_GET['sms'];
	}
if(isset($_GET['checked']))
{
		$ischecked = $_GET['checked'];
		//$Page = 'Student fee';
		if($ischecked == 'All'){
			$Page = 'Student fee';
			$studentmethod ='all';
		}
		elseif($ischecked == 'Class'){
			$Page = 'Student fee';
			$studentmethod ='class';
			$classname = $_GET['class'];
		}
		elseif($ischecked == 'Name'){
			$Page = 'Student fee';
			$studentmethod ='name';
			$studentname = $_GET['adm'];
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
<?PHP
		if ($Page == "Fees Receipt") {
?>
		  <TR>
			<TD>
				<table  width="95%" cellpadding="0" cellspacing="5" border="0">
				  <tbody>
					<tr>
					  <td width="458" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" valign="top">
					  	<table width="100%" cellpadding="0" cellspacing="0">
						  <tbody>
							<tr>
							  <td rowspan="4" align="center" valign="bottom"><img src="images/uploads/<?PHP echo $schLogo; ?>" width="82" height="73"><hr></td>
							  <td align="center" valign="bottom"><strong><?PHP echo strtoupper($sName); ?></strong></td>							  
							</tr>
							<tr>
							  <td align="center" valign="bottom"><strong><?PHP echo strtoupper($sAddress); ?>,&nbsp;<?PHP echo strtoupper($sCountry); ?></strong></td>
							</tr>
							<tr>
							  <td align="center" valign="bottom"><strong><?PHP echo strtoupper($sPhone); ?>,&nbsp;<?PHP echo strtoupper(SchEmail1); ?>&nbsp;</strong></td>
							</tr>
							<tr>
							  <td align="center" valign="bottom"><strong>PARENT'S COPY</strong>
							    <hr></td>
							</tr>
							</tbody>
						 </table>
						<table width="100%" cellpadding="5" cellspacing="5">
						  <tbody>
							<tr>
							  <td width="28%" align="center">&nbsp;</td>
							  <td width="72%" align="center">&nbsp;</td>
							</tr>
							<tr>
							  <td width="28%" align="Left">Dated : </td>
							  <td width="72%" align="Left"><?PHP echo Long_date($receiptDate); ?></td>
							</tr>
							<tr>
							  <td colspan="2" align="left"><p>Please credit our account as follow </p>
						      </td>
							</tr>
							<tr>
							  <td width="28%" align="left">Account Name:</td>
							  <td width="72%" align="left"><?PHP echo strtoupper($sName); ?></td>
							</tr>
							<tr>
							  <td width="28%" align="left">Payment Mode:</td>
							  <td width="72%" align="left"><?PHP echo strtoupper($PayMode); ?></td>
							</tr>
							<tr>
							  <td width="28%" align="left">Student Name:</td>
							  <td width="72%" align="left"><?PHP echo strtoupper($StudName); ?></td>
							</tr>
							<tr>
							  <td width="28%" align="left">Registration No:</td>
							  <td width="72%" align="left"><?PHP echo strtoupper($RegNo); ?></td>
							</tr>
							<tr>
							  <td width="28%" align="left">Admission No:</td>
							  <td width="72%" align="left"><?PHP echo strtoupper($AdmnNo); ?></td>
							</tr>
							<tr>
							  <td width="28%" align="left">Student Class:</td>
							  <td width="72%" align="left"><?PHP echo GetClassName($StudClass); ?></td>
							</tr>
							<tr>
							  <td colspan="2"align="center">
							  	<table width="98%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 98%; TEXT-ALIGN: left;" >
								  <tbody>
									<tr>
									  <td width="42%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
									  <td width="27%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Payable</strong></font></td>
									  <td width="31%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount</strong></font></td>
								    </tr>
<?PHP
									$counter = 0;
									$TotalAmt = 0;
									$TotalPay = 0;
									$TotalPaid= 0;
									$GrandPayable = 0;
									$GrandPaid = 0;
									$query1 = "select chargeID from tbstudentcharges where sType = '$StudType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result1 = mysql_query($query1,$conn);
									$num_rows1 = mysql_num_rows($result1);
									if ($num_rows1 > 0 ) {
										while ($row1 = mysql_fetch_array($result1)) 
										{
											$counter = $counter+1;
											$chargeID = $row1["chargeID"];
											
											$query = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$AdmnNo' And Status = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And ChargeID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$AmtPayable  = $dbarray['Payable'];
											$PaidAmount  = $dbarray['PaidAmount'];
											
											$query3 = "select ChargeName from tbclasscharges where ClassID='$StudClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' And ChargeName IN (Select ChargeName from tbchargemaster where ID ='$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' )";
											$result3 = mysql_query($query3,$conn);
											$dbarray3 = mysql_fetch_array($result3);	
											$ChargeName  = $dbarray3['ChargeName'];
											
											$TotalPay = $TotalPay +$AmtPayable;
											$TotalPaid = $TotalPaid +$PaidAmount;
?>
						  
										  <tr>
											<td width="42%" align="center"><?PHP echo $ChargeName; ?></td>
											<td width="27%"  align="center"><?PHP echo number_format($AmtPayable,2); ?></td>
											<td width="31%"  align="center"><?PHP echo number_format($PaidAmount,2); ?></td>
										  </tr>
<?PHP
										 }
									 }
									$GrandPayable = $TotalPay + $FinedPayable;
									$GrandPaid = $TotalPaid + $FinedPaid;
?>
								   <tr>
									<td width="42%">&nbsp;</td>
									<td width="27%"><div align="right"><strong>TOTAL</strong></div></td>
									<td width="31%" align="center"><hr><?PHP echo number_format($TotalPaid,2); ?><hr></td>
								  </tr>
								  <tr>
									<td width="42%">&nbsp;</td>
									<td width="27%"><div align="right"><strong>FINE</strong></div></td>
									<td width="31%" align="center"><?PHP echo number_format($FinedPaid,2); ?></td>
								  </tr>
								  <tr>
									<td width="42%">&nbsp;</td>
									<td width="27%"><div align="right"><strong>GRAND TOTAL</strong></div></td>
									<td width="31%" align="center"><hr><?PHP echo number_format($GrandPaid,2); ?><hr></td>
								  </tr>
								  <tr>
									 <td width="42%">&nbsp;</td>
									  <td width="27%">&nbsp;</td>
									  <td width="31%" align="center"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><hr>
									  	<br>Authorised Signatory
									  </td>
									</tr>
								 </tbody>
								</table>
							  </td>
							</tr>
						 </tbody>
						</table>
					  </td>
					  <td width="18" align="right" style="width:1px; height:100%" valign="top"><img src="images/line.gif" height="800" width="1">&nbsp;</td>
					  <td width="441" align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" valign="top">
					  <table width="100%" cellpadding="0" cellspacing="0">
						  <tbody>
							<tr>
							  <td rowspan="4" align="center" valign="bottom"><img src="images/uploads/<?PHP echo $schLogo; ?>" width="82" height="73"><hr></td>
							  <td align="center"  valign="bottom"><strong><?PHP echo strtoupper($sName); ?></strong></td>
							</tr>
							<tr>
							  <td align="center"  valign="bottom"><strong><?PHP echo strtoupper($sAddress); ?>, &nbsp;<?PHP echo strtoupper($sCountry); ?></strong></td>
							</tr>
							<tr>
							  <td align="center" valign="bottom"><strong><?PHP echo strtoupper($sPhone); ?>,&nbsp;<?PHP echo strtoupper(SchEmail1); ?>&nbsp;</strong></td>
							</tr>
							<tr>
							  <td align="center" valign="bottom"><strong>SCHOOL COPY</strong>
							    <hr></td>
							</tr>
						  </tbody>
						</table>
						<table width="100%" cellpadding="5" cellspacing="5">
						  <tbody>
							<tr>
							  <td width="28%" align="center">&nbsp;</td>
							  <td width="72%" align="center">&nbsp;</td>
							</tr>
							<tr>
							  <td colspan="2" align="left">Dated: </td>
							</tr>
							<tr>
							  <td colspan="2" align="left"><p>Please update the below student account as follow </p></td>
							</tr>
							<tr>
							  <td width="24%" align="left">Term / Section:</td>
							  <td width="76%" align="left"><?PHP echo strtoupper($sTerm); ?></td>
							</tr>
							<tr>
							  <td width="24%" align="left">Payment Mode:</td>
							  <td width="76%" align="left"><?PHP echo strtoupper($PayMode); ?></td>
							</tr>
							<tr>
							  <td width="24%" align="left">Student Name:</td>
							  <td width="76%" align="left"><?PHP echo strtoupper($StudName); ?></td>
							</tr>
							<tr>
							  <td width="24%" align="left">Registration No:</td>
							  <td width="76%" align="left"><?PHP echo strtoupper($RegNo); ?></td>
							</tr>
							<tr>
							  <td width="24%" align="left">Admission No:</td>
							  <td width="76%" align="left"><?PHP echo strtoupper($AdmnNo); ?></td>
							</tr>
							<tr>
							  <td width="24%" align="left">Student Class:</td>
							  <td width="76%" align="left"><?PHP echo GetClassName($StudClass); ?></td>
							</tr>
							<tr>
							  <td colspan="2"align="center">
							  	<table width="98%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 98%; TEXT-ALIGN: left;" >
								  <tbody>
									<tr>
									  <td width="42%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
									  <td width="27%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Payable</strong></font></td>
									  <td width="31%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount</strong></font></td>
								    </tr>
<?PHP
									$counter = 0;
									$TotalAmt = 0;
									$TotalPay = 0;
									$TotalPaid= 0;
									$GrandPayable = 0;
									$GrandPaid = 0;
									$query1 = "select chargeID from tbstudentcharges where sType = '$StudType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result1 = mysql_query($query1,$conn);
									$num_rows1 = mysql_num_rows($result1);
									if ($num_rows1 > 0 ) {
										while ($row1 = mysql_fetch_array($result1)) 
										{
											$counter = $counter+1;
											$chargeID = $row1["chargeID"];
											
											$query = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$AdmnNo' And Term = '$Activeterm' And Status = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') And ChargeID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$AmtPayable  = $dbarray['Payable'];
											$PaidAmount  = $dbarray['PaidAmount'];
											
											$query3 = "select ChargeName from tbclasscharges where ClassID='$StudClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' And ChargeName IN (Select ChargeName from tbchargemaster where ID ='$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' )";
											$result3 = mysql_query($query3,$conn);
											$dbarray3 = mysql_fetch_array($result3);	
											$ChargeName  = $dbarray3['ChargeName'];
											
											$TotalPay = $TotalPay +$AmtPayable;
											$TotalPaid = $TotalPaid +$PaidAmount;
?>
						  
										  <tr>
											<td width="42%" align="center"><?PHP echo $ChargeName; ?></td>
											<td width="27%"  align="center"><?PHP echo number_format($AmtPayable,2); ?></td>
											<td width="31%"  align="center"><?PHP echo number_format($PaidAmount,2); ?></td>
										  </tr>
<?PHP
										 }
									 }
									$GrandPayable = $TotalPay + $FinedPayable;
									$GrandPaid = $TotalPaid + $FinedPaid;
?>
								   <tr>
									<td width="42%">&nbsp;</td>
									<td width="27%"><div align="right"><strong>TOTAL</strong></div></td>
									<td width="31%" align="center"><hr><?PHP echo number_format($TotalPaid,2); ?><hr></td>
								  </tr>
								  <tr>
									<td width="42%">&nbsp;</td>
									<td width="27%"><div align="right"><strong>FINE</strong></div></td>
									<td width="31%" align="center"><?PHP echo number_format($FinedPaid,2); ?></td>
								  </tr>
								  <tr>
									<td width="42%">&nbsp;</td>
									<td width="27%"><div align="right"><strong>GRAND TOTAL</strong></div></td>
									<td width="31%" align="center"><hr><?PHP echo number_format($GrandPaid,2); ?><hr></td>
								  </tr>
								  <tr>
									 <td width="42%">&nbsp;</td>
									  <td width="27%">&nbsp;</td>
									  <td width="31%" align="center"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><hr>
									  	<br>Authorised Signatory
									  </td>
									</tr>
								 </tbody>
								</table>
							  </td>
							</tr>
						 </tbody>
						</table>
					  </td>
					</tr>
					<tr>
						<TD colspan="3" align="center"><a href="fees.php?subpg=Fee Receipt">Back to previous Page </a></TD>
					</tr>
			      </tbody>
			  </table>
			</TD>
		  </TR>
<?PHP
		}elseif ($Page == "Fee Defaulter Details") {
			$CountSMS = 0;
?>
		  <TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="fees.php?subpg=Student fee">Fees</a> &gt; <a href="fees.php?subpg=Fee Defaulter Details">Fee Defaulter</a> &gt; Fee Defaulter Report</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Fee Defaulter</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				 <tbody>
					<tr>
					  <td width="61%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
					  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Charge Amt</strong></font></td>
					  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Paid Amount</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Balance</strong></font></td>
					</tr>
<?PHP               
                    $classcounter = 0;
					$TotalFee = 0;
					if($SrchMethod == "All"){
						$counter = 0;
						$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$classcounter++;
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
								$DisplayClass = "False";
								$StuType = 0;
								$isEnable  = '0';
										$isEnable = ChkDefaulters($ClassID,$StuType);
										$TotalFee = $TotalFee +$isEnable ;
										if($isEnable>0){
											if($DisplayClass == "False"){
												$DisplayClass = "True";
												?>
												<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
                                                
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
												
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			         // $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate'";
					  $query7 = "select Amount,Balance,PaidAmount from tbfeepayment2 where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
								$showstudentname = false;
								if($TotalBal > 0 or $TotalPaid == 0){
			
										
	
											
?>
											<tr>
											  <td colspan="4" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
											</tr>
                                            <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
												<tr>
												  <td colspan="4" align="right" valign="top">List of charges yet to be paid.
												  <table width="60%" border="0" align="right" cellpadding="3" cellspacing="3">
												  <tr>
													  <td width="184" bgcolor="#666666" align="right"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
													  <td width="111" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Charge Amt</strong></font></td>
													  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Paid Amount</strong></font></td>
													  <td width="100" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Balance</strong></font></td>
												 </tr></table></td></tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									//$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and                             ChargeID='$chargeID' and LastEntryDate='$EntryDate'";
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment2 where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															$isDisplay = "True";
															//$isDisplay = "true";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															if($AmtPayable == 0){
																$isDisplay = "false";
															}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  <tr>
																<td width="184" align="right"><?PHP echo $ChargeName; ?></td>
																<td width="111" align="center"><?PHP echo $AmtPayable; ?></td>
																<td width="98" align="center"><?PHP echo $PaidAmount; ?></td>
																<td width="100" align="center" style="color:#F00"><?PHP echo $Balance; ?></td>
															  </tr>
<?PHP
															}
														 }
													 }
?>
												   <tr>
													<td width="184" align="center"><div align="right"><strong>TOTAL</strong></div></td>
													<td width="111" align="center"><hr><?PHP echo number_format($TotalPay,2); ?><hr></td>
													<td width="98" align="center"><hr><?PHP echo number_format($TotalPaid,2); ?><hr></td>
													<td width="100" align="center" style="color:#F00"> <hr>
<?PHP 
													echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													<hr></td>
												  </tr>
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
													  <tr>
														<td width="184" align="center"><div align="right"><strong>FINE</strong></div></td>
														<td width="111" align="center"><hr><?PHP //echo number_format($FinedPayable,2); ?>&nbsp;<hr></td>
														<td width="98" align="center"><hr><?PHP //echo number_format($FinedPaid,2); ?>&nbsp;<hr></td>
														<td width="100" align="center"><hr><?PHP //echo number_format($BalFined,2); ?>&nbsp;<hr></td>
													  </tr>
<?PHP
										  		 //	}

                          

								}
				           }
				       }                  
            
                   }             
             }
?>

<?PHP
         }
	}
?>
											 
    <tr>
						  <td colspan="4" align="left" ><strong>Total Fee Owed in School:<div style="color:#F00"> <?PHP echo "  "."N".number_format($TotalFee,2); ?></div></strong></td>
						</tr>                                         
                                              </tbody></table>
											</td>
										</tr>
<?PHP
											//}
										//}
									//}
								//}
							
					}elseif($SrchMethod == "Class"){
						//$OptClass
						$query = "select Class_Name from tbclassmaster where ID = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$ClassID  = $OptClass;
						$ClassName  = $dbarray['Class_Name'];
						$StuType = 0;
						 $DisplayClass = False;
								$isEnable  = '0';
										$isEnable = ChkDefaulters($ClassID,$StuType);
										if($isEnable>0){
												$DisplayClass = "True";
												?>

						<tr>
						  <td colspan="4" align="left"><strong>CLASS:&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
						</tr>
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
								$showstudentname = false;
								if($TotalBal > 0 or $TotalPaid == 0){
			
										
	
											
?>
											<tr>
											  <td colspan="4" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
											</tr>
                                            <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
												<tr>
												  <td colspan="4" align="right" valign="top">List of charges yet to be paid.
												  <table width="60%" border="0" align="right" cellpadding="3" cellspacing="3">
												  <tr>
													  <td width="184" bgcolor="#666666" align="right"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
													  <td width="111" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Charge Amt</strong></font></td>
													  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Paid Amount</strong></font></td>
													  <td width="100" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Balance</strong></font></td>
												 </tr></table></td></tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate'and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															$isDisplay = "True";
															//$isDisplay = "true";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															if($AmtPayable == 0){
																$isDisplay = "false";
															}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  <tr>
																<td width="184" align="right"><?PHP echo $ChargeName; ?></td>
																<td width="111" align="center"><?PHP echo $AmtPayable; ?></td>
																<td width="98" align="center"><?PHP echo $PaidAmount; ?></td>
																<td width="100" align="center" style="color:#F00"><?PHP echo $Balance; ?></td>
															  </tr>
<?PHP
															}
														 }
													 }
?>
												   <tr>
													<td width="184" align="center"><div align="right"><strong>TOTAL</strong></div></td>
													<td width="111" align="center"><hr><?PHP echo number_format($TotalPay,2); ?><hr></td>
													<td width="98" align="center"><hr><?PHP echo number_format($TotalPaid,2); ?><hr></td>
													<td width="100" align="center" style="color:#F00"> <hr>
<?PHP 
													echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													<hr></td>
												  </tr>
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
													  <tr>
														<td width="184" align="center"><div align="right"><strong>FINE</strong></div></td>
														<td width="111" align="center"><hr><?PHP //echo number_format($FinedPayable,2); ?>&nbsp;<hr></td>
														<td width="98" align="center"><hr><?PHP //echo number_format($FinedPaid,2); ?>&nbsp;<hr></td>
														<td width="100" align="center"><hr><?PHP //echo number_format($BalFined,2); ?>&nbsp;<hr></td>
													  </tr>
<?PHP
										  		 //	}

                          

								}
				           }
				       }  
?>
<tr>
						  <td colspan="4" align="left" ><strong>Total Fee Owed in Class:<div style="color:#F00"> <?PHP echo "  "."N".number_format($isEnable,2); ?></div></strong></td>
						</tr>
<?php
            
                   }             
             }

?>
					
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Student fee") {
?>
		  <TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="fees.php?subpg=Student fee">Fees</a> &gt; <a href="fees.php?subpg=Student Fee Details">Student fee</a> &gt; Student Fee Report</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Student fee</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="61%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
					  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Charge Amt</strong></font></td>
					  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Paid Amount</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Balance</strong></font></td>
					</tr>
<?PHP 
                     if($studentmethod == "all"){
						 $query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$classcounter++;
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
?>
								
												<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
							    $AllClassFee = true;
								if($AllClassFee == true){
			
										
	
											
?>
											<tr>
											  <td colspan="4" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
											</tr>
                                            <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
												<tr>
												  <td colspan="4" align="right" valign="top">List of charges yet to be paid.
												  <table width="60%" border="0" align="right" cellpadding="3" cellspacing="3">
												  <tr>
													  <td width="184" bgcolor="#666666" align="right"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
													  <td width="111" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Charge Amt</strong></font></td>
													  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Paid Amount</strong></font></td>
													  <td width="100" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Balance</strong></font></td>
												 </tr></table></td></tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															//$isDisplay = "false";
															$isDisplay = "True";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															if($AmtPayable == 0){
																$isDisplay = "false";
															}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  <tr>
																<td width="184" align="right"><?PHP echo $ChargeName; ?></td>
																<td width="111" align="center"><?PHP echo $AmtPayable; ?></td>
																<td width="98" align="center"><?PHP echo $PaidAmount; ?></td>
																<td width="100" align="center" style="color:#F00"><?PHP echo $Balance; ?></td>
															  </tr>
<?PHP
															}
														 }
													 }
?>
												   <tr>
													<td width="184" align="center"><div align="right"><strong>TOTAL</strong></div></td>
													<td width="111" align="center"><hr><?PHP echo number_format($TotalPay,2); ?><hr></td>
													<td width="98" align="center"><hr><?PHP echo number_format($TotalPaid,2); ?><hr></td>
													<td width="100" align="center" style="color:#F00"> <hr>
<?PHP 
													echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													<hr></td>
												  </tr>
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
													  <tr>
														<td width="184" align="center"><div align="right"><strong>FINE</strong></div></td>
														<td width="111" align="center"><hr><?PHP //echo number_format($FinedPayable,2); ?>&nbsp;<hr></td>
														<td width="98" align="center"><hr><?PHP //echo number_format($FinedPaid,2); ?>&nbsp;<hr></td>
														<td width="100" align="center"><hr><?PHP //echo number_format($BalFined,2); ?>&nbsp;<hr></td>
													  </tr>
<?PHP
										  		 //	}

                          

								}
				           }
						  
						  
				       } 
					  
					  
            
                   }  
				      
				   
							//}
						  }
						 // echo 'No Student In Class/Class Charges Not Yet defined';
						}elseif($studentmethod == "class"){
?>
<?PHP 
             
						$query = "select ID from tbclassmaster where Class_Name = '$classname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$row = mysql_fetch_array($result);
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
?>
								
												<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $classname; ?></strong></td>
												</tr>
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
							    $AllClassFee = true;
								if($AllClassFee == true){
			
										
	
											
?>
											<tr>
											  <td colspan="4" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
											</tr>
                                            <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
												<tr>
												  <td colspan="4" align="right" valign="top">List of charges yet to be paid.
												  <table width="60%" border="0" align="right" cellpadding="3" cellspacing="3">
												  <tr>
													  <td width="184" bgcolor="#666666" align="right"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
													  <td width="111" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Charge Amt</strong></font></td>
													  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Paid Amount</strong></font></td>
													  <td width="100" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Balance</strong></font></td>
												 </tr></table></td></tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															//$isDisplay = "false";
															$isDisplay = "True";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															if($AmtPayable == 0){
																$isDisplay = "false";
															}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  <tr>
																<td width="184" align="right"><?PHP echo $ChargeName; ?></td>
																<td width="111" align="center"><?PHP echo $AmtPayable; ?></td>
																<td width="98" align="center"><?PHP echo $PaidAmount; ?></td>
																<td width="100" align="center" style="color:#F00"><?PHP echo $Balance; ?></td>
															  </tr>
<?PHP
															}
														 }
													 }
?>
												   <tr>
													<td width="184" align="center"><div align="right"><strong>TOTAL</strong></div></td>
													<td width="111" align="center"><hr><?PHP echo number_format($TotalPay,2); ?><hr></td>
													<td width="98" align="center"><hr><?PHP echo number_format($TotalPaid,2); ?><hr></td>
													<td width="100" align="center" style="color:#F00"> <hr>
<?PHP 
													echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													<hr></td>
												  </tr>
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
													  <tr>
														<td width="184" align="center"><div align="right"><strong>FINE</strong></div></td>
														<td width="111" align="center"><hr><?PHP //echo number_format($FinedPayable,2); ?>&nbsp;<hr></td>
														<td width="98" align="center"><hr><?PHP //echo number_format($FinedPaid,2); ?>&nbsp;<hr></td>
														<td width="100" align="center"><hr><?PHP //echo number_format($BalFined,2); ?>&nbsp;<hr></td>
													  </tr>
<?PHP
										  		 }

                          

								}
				          }

             }elseif($studentmethod == "name"){
?>
<tr><td> <?PHP echo $studentname ?></td></tr>
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,AdmissionNo,Stu_Type,Stu_Class from tbstudentmaster where Stu_Full_Name = '$studentname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $studentname;
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										$ClassID = $row2["Stu_Class"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Class_Name from tbclassmaster where ID='$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$ClassName  = $dbarray1['Class_Name'];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
							    $AllClassFee = true;
								if($AllClassFee == true){
			
										
	
											
?>
											<tr>
											  <td colspan="4" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Class :&nbsp;</strong><?PHP echo $ClassName; ?></p></td>
											</tr>
                                            <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
												<tr>
												  <td colspan="4" align="right" valign="top">List of charges yet to be paid.
												  <table width="60%" border="0" align="right" cellpadding="3" cellspacing="3">
												  <tr>
													  <td width="184" bgcolor="#666666" align="right"><font color="#FFFFFF"><strong>Particulars</strong></font></td>
													  <td width="111" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Charge Amt</strong></font></td>
													  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Paid Amount</strong></font></td>
													  <td width="100" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Balance</strong></font></td>
												 </tr></table></td></tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															//$isDisplay = "false";
															$isDisplay = "True";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															if($AmtPayable == 0){
																$isDisplay = "false";
															}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  <tr>
																<td width="184" align="right"><?PHP echo $ChargeName; ?></td>
																<td width="111" align="center"><?PHP echo $AmtPayable; ?></td>
																<td width="98" align="center"><?PHP echo $PaidAmount; ?></td>
																<td width="100" align="center" style="color:#F00"><?PHP echo $Balance; ?></td>
															  </tr>
<?PHP
															}
														 }
													 }
?>
												   <tr>
													<td width="184" align="center"><div align="right"><strong>TOTAL</strong></div></td>
													<td width="111" align="center"><hr><?PHP echo number_format($TotalPay,2); ?><hr></td>
													<td width="98" align="center"><hr><?PHP echo number_format($TotalPaid,2); ?><hr></td>
													<td width="100" align="center" style="color:#F00"> <hr>
<?PHP 
													echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													<hr></td>
												  </tr>
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
													  <tr>
														<td width="184" align="center"><div align="right"><strong>FINE</strong></div></td>
														<td width="111" align="center"><hr><?PHP //echo number_format($FinedPayable,2); ?>&nbsp;<hr></td>
														<td width="98" align="center"><hr><?PHP //echo number_format($FinedPaid,2); ?>&nbsp;<hr></td>
														<td width="100" align="center"><hr><?PHP //echo number_format($BalFined,2); ?>&nbsp;<hr></td>
													  </tr>
<?PHP
										  		 }

                          

								}
				          }

			 }
?>
			 
			      </tbody>
			  </table>
			 </TD>
		  </TR>
<?PHP
		}elseif ($Page == "Deleted Receipt") {
?>
		  <TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="fees.php?subpg=Student fee">Fees</a> &gt; Deleted Receipt</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Deleted Receipt</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="68" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Receipt No</strong></font></td>
					  <td width="110" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Deleted On</strong></font></td>
					  <td width="177" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Admission No.</strong></font></td>
					  <td width="222" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Name</strong></font></td>
					  <td width="151" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Class</strong></font></td>
					  <td width="99" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount</strong></font></td>
					</tr>
<?PHP
					$query2 = "select * from tbreceipt where Status ='2'";
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$ReceiptNo = $row2["ID"];
							$DeleteDate = $row2["FeeDate"];
							$AdmnNo = $row2["AdmnNo"];
							
							$query3 = "select Stu_Sec,Stu_Full_Name,Stu_Type,Stu_Class from tbstudentmaster where AdmissionNo = '$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result3 = mysql_query($query3,$conn);
							$dbarray3 = mysql_fetch_array($result3);
							$Stu_Sec  = $dbarray3['Stu_Sec'];
							$Stu_Full_Name  = $dbarray3['Stu_Full_Name'];
							$Stu_Type  = $dbarray3['Stu_Type'];
							$StuClass  = $dbarray3['Stu_Class'];
							
							//$OptClass
							$query4 = "select Class_Name from tbclassmaster where ID = '$StuClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$ClassName  = $dbarray4['Class_Name'];
							
							$GetAmount = CalFeeAmount($ReceiptNo);
?>
						  <tr>
							<td width="68" align="center"><?PHP echo $ReceiptNo; ?></td>
							<td width="110" align="center"><?PHP echo long_date($DeleteDate); ?></td>
							<td width="177" align="center"><?PHP echo $AdmnNo; ?></td>
							<td width="222" align="center"><?PHP echo $Stu_Full_Name; ?></td>
							<td width="151" align="center"><?PHP echo $ClassName; ?></td>
							<td width="99" align="center"><?PHP echo number_format($GetAmount,2); ?></td>
						  </tr>
<?PHP
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Refundable amount details") {
?>
		  <TR>
			<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="fees.php?subpg=Student fee">Fees</a> &gt; <a href="fees.php?subpg=Refundable amount details">Refundable amount details</a> &gt; Refundable Amount</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Refundable Amount</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="68" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Receipt No</strong></font></td>
					  <td width="110" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Refunded On</strong></font></td>
					  <td width="177" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Admission No.</strong></font></td>
					  <td width="222" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Name</strong></font></td>
					  <td width="99" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount</strong></font></td>
					</tr>
<?PHP
					if($SrchMethod == "All"){
						$counter = 0;
						$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
								$DisplayClass = "False";
								
								$query2 = "select Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										$TotalAmount = 0;
										$query3 = "select * from tbreceipt where Status = '1' And AdmnNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										//$query3 = "select * from tbreceipt where AdmnNo = '$AdmissionNo'";
										$result3 = mysql_query($query3,$conn);
										$num_rows3 = mysql_num_rows($result3);
										if ($num_rows3 > 0 ) {
											while ($row3 = mysql_fetch_array($result3)) 
											{
												$ReceiptNo = $row3["ID"];
												$RefundDate = $row3["FeeDate"];
												
												$GetAmount = CalFeeAmount($ReceiptNo);
												$TotalAmount = $TotalAmount + $GetAmount;
											}
										}
										
										if($TotalAmount >0){
											if($DisplayClass == "False"){
												$DisplayClass = "True";
?>
												<tr>
												  <td colspan="5" align="left">
													<p style="margin-left:10px;"><strong>CLASS : </strong><?PHP echo $ClassName; ?></p>
												  </td>
												</tr>
<?PHP
											}
?>
										<tr>
											<td width="68" align="center"><?PHP echo $ReceiptNo; ?></td>
											<td width="110" align="center"><?PHP echo long_date($RefundDate); ?></td>
											<td width="177" align="center"><?PHP echo $AdmissionNo; ?></td>
											<td width="222" align="center"><?PHP echo $Stu_Full_Name; ?></td>
											<td width="99" align="center"><?PHP echo number_format($TotalAmount,2); ?></td>
										  </tr>
<?PHP
										}
									}
								}
							}
						}						
					}if($SrchMethod == "Class"){
						//$OptClass
						$query4 = "select Class_Name from tbclassmaster where ID = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result4 = mysql_query($query4,$conn);
						$dbarray4 = mysql_fetch_array($result4);
						$ClassName  = $dbarray4['Class_Name'];
						$ClassID = $OptClass;
						$DisplayClass = "False";
								
						$query2 = "select Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
						$result2 = mysql_query($query2,$conn);
						$num_rows2 = mysql_num_rows($result2);
						if ($num_rows2 > 0 ) {
							while ($row2 = mysql_fetch_array($result2)) 
							{
								$StuType = "";
								$Stu_Sec = $row2["Stu_Sec"];
								$Stu_Full_Name = $row2["Stu_Full_Name"];
								$AdmissionNo = $row2["AdmissionNo"];
								$StuType = $row2["Stu_Type"];
								$TotalAmount = 0;
								$query3 = "select * from tbreceipt where Status = '1' And AdmnNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								//$query3 = "select * from tbreceipt where AdmnNo = '$AdmissionNo'";
								$result3 = mysql_query($query3,$conn);
								$num_rows3 = mysql_num_rows($result3);
								if ($num_rows3 > 0 ) {
									while ($row3 = mysql_fetch_array($result3)) 
									{
										$ReceiptNo = $row3["ID"];
										$RefundDate = $row3["FeeDate"];
										
										$GetAmount = CalFeeAmount($ReceiptNo);
										$TotalAmount = $TotalAmount + $GetAmount;
									}
								}
								
								if($TotalAmount >0){
									if($DisplayClass == "False"){
										$DisplayClass = "True";
?>
										<tr>
										  <td colspan="5" align="left">
											<p style="margin-left:10px;"><strong>CLASS : </strong><?PHP echo $ClassName; ?></p>
										  </td>
										</tr>
<?PHP
									}
?>
								<tr>
									<td width="68" align="center"><?PHP echo $ReceiptNo; ?></td>
									<td width="110" align="center"><?PHP echo long_date($RefundDate); ?></td>
									<td width="177" align="center"><?PHP echo $AdmissionNo; ?></td>
									<td width="222" align="center"><?PHP echo $Stu_Full_Name; ?></td>
									<td width="99" align="center"><?PHP echo number_format($TotalAmount,2); ?></td>
								  </tr>
<?PHP
								}
							}
						}					
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Fee Defaulter List") {
?>
 <TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="fees.php?subpg=Student fee">Fees</a> &gt; <a href="fees.php?subpg=Fee Defaulter List">Fee Defaulter List</a> &gt; Fee Defaulter List</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Fee Defaulter List</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				 <tbody>
					
<?PHP               
                    $classcounter = 0;
					$TotalFee = 0;
					if($SrchMethod == "All"){
						$counter = 0;
						$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$classcounter++;
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
								$DisplayClass = "False";
								$StuType = 0;
								$isEnable  = '0';
										$isEnable = ChkDefaulters($ClassID,$StuType);
										$TotalFee = $TotalFee +$isEnable ;
										if($isEnable>0){
											if($DisplayClass == "False"){
												$DisplayClass = "True";
												?>
												<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
                                                <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
                                                
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
								$showstudentname = false;
								if($TotalBal > 0 or $TotalPaid == 0){
			
										
	
											
?>
											
												
												  
												  <tr>
													  <td width="61%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount Owed</strong></font></td>
												 </tr>
                                                 
                                            
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															$isDisplay = "True";
															//$isDisplay = "true";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															if($AmtPayable == 0){
																$isDisplay = "false";
															}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
	
                                            												  
<?PHP
															}
														 }
													 }
?>
<tr>
													  <td colspan"3" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
					  
					  <td  align="right" style="color:#F00"></td>
                      <td  align="right" style="color:#F00"></td>
                      <td  align="center" style="color:#F00"><strong><?PHP echo number_format($TotalBal,2); ?></strong></td>
												 </tr>												  
<?PHP 
													//echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
													  
<?PHP
										  		 //	}

                          

								}
				           }
				       }                  
            
                   }             
             }
?>

<?PHP
         }
	}
?>
											 
    <tr>
						  <td colspan="4" align="left" ><strong>Total Fee Owed in School:<div style="color:#F00"> <?PHP echo "  "."N".number_format($TotalFee,2); ?></div></strong></td>
						</tr>                                         
                                              </tbody></table>
											</td>
										</tr>
<?PHP
											//}
										//}
									//}
								//}
							
					}elseif($SrchMethod == "Class"){
						//$OptClass
						$query = "select Class_Name from tbclassmaster where ID = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$ClassID  = $OptClass;
						$ClassName  = $dbarray['Class_Name'];
						$StuType = 0;
						 $DisplayClass = False;
								$isEnable  = '0';
										$isEnable = ChkDefaulters($ClassID,$StuType);
										if($isEnable>0){
												$DisplayClass = "True";
												?>

						<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
                                                <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
								$showstudentname = false;
								if($TotalBal > 0 or $TotalPaid == 0){
			
										
	
											
?>
											 <tr>
													  <td width="61%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount Owed</strong></font></td>
												 </tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															$isDisplay = "True";
															//$isDisplay = "true";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															if($AmtPayable == 0){
																$isDisplay = "false";
															}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  
<?PHP
															}
														 }
													 }
?>
	 <tr>
													  <td colspan"3" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
					  
					  <td  align="right" style="color:#F00"></td>
                      <td  align="right" style="color:#F00"></td>
                      <td  align="center" style="color:#F00"><strong><?PHP echo number_format($TotalBal,2); ?></strong></td>
												 </tr>	
    											  
<?PHP 
													//echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
														
<?PHP
										  		 //	}

                          

								}
				           }
				       }  
?>
<tr>
						  <td colspan="4" align="left" ><strong>Total Fee Owed in Class:<div style="color:#F00"> <?PHP echo "  "."N".number_format($isEnable,2); ?></div></strong></td>
						</tr>
<?php
            
                   }             
             }

?>
					
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Total Fees Paid") {
?> <TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="fees.php?subpg=Student fee">Fees</a> &gt; <a href="fees.php?subpg=Total Fees Paid">Total Fees Paid</a> &gt; Total Fees paid</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Total Fees Paid</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				 <tbody>
					
<?PHP               
                    $classcounter = 0;
					$TotalPaid = 0;
					if($SrchMethod == "All"){
						$counter = 0;
						$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$classcounter++;
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
								$DisplayClass = "False";
								//$StuType = 0;
								$isEnable  = '1';
										$ClassTotalFeePaid = ChkClassTotalFeePaid($ClassID);
										$TotalFeePaid = $TotalFeePaid + $ClassTotalFeePaid ;
										if($isEnable>0){
											if($DisplayClass == "False"){
												$DisplayClass = "True";
												?>
												<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
                                                <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
                                                
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($Balance ==""){
																$Balance  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
								$showstudentname = false;
								if($TotalBal >= 0 ){
			
										
	
											
?>
											
												
												  
												  <tr>
													  <td width="61%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount Paid</strong></font></td>
												 </tr>
                                                 
                                            
<?PHP    
$counter = 0;
													$TotalAmtPaid = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance ==""){
																$Balance  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															$isDisplay = "True";
															//$isDisplay = "true";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															//if($AmtPayable == 0){
															//	$isDisplay = "false";
															//}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
																$TotalAmtPaid = $TotalAmtPaid + $PaidAmount;
															//}
															if($isDisplay == "True"){
?>
	
                                            												  
<?PHP
															}
														 }
													 }
?>
<tr>
													  <td colspan"3" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
					  
					  <td  align="right" style="color:#F00"></td>
                      <td  align="right" style="color:#F00"></td>
                      <td  align="center" style="color:#F00"><strong><?PHP echo number_format($TotalPaid,2); ?></strong></td>
												 </tr>												  
<?PHP 
													//echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
													  
<?PHP
										  		 //	}

                          

								}
				           }
				       }                  
            
                   }             
             }
?>

<?PHP
         }
	}
?>
											 
    <tr>
						  <td colspan="4" align="left" ><strong>Total Fee Paid in School:<div style="color:#F00"> <?PHP echo "  "."N".number_format($TotalFeePaid,2); ?></div></strong></td>
						</tr>                                         
                                              </tbody></table>
											</td>
										</tr>
<?PHP
											//}
										//}
									//}
								//}
							
					}elseif($SrchMethod == "Class"){
						//$OptClass
						$query = "select Class_Name from tbclassmaster where ID = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$ClassID  = $OptClass;
						$ClassName  = $dbarray['Class_Name'];
						$StuType = 0;
						 $DisplayClass = False;
								$isEnable  = '1';
										//$isEnable = ChkDefaulters($ClassID,$StuType);
										$ClassTotalFeePaid = ChkClassTotalFeePaid($ClassID);
										if($isEnable>0){
												$DisplayClass = "True";
												?>

						<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
                                                <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
								$showstudentname = false;
								if($TotalPaid >= 0){
			
										
	
											
?>
											 <tr>
													  <td width="61%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount Paid</strong></font></td>
												 </tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															$isDisplay = "True";
															//$isDisplay = "true";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															//if($AmtPayable == 0){
															//	$isDisplay = "false";
															//}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  
<?PHP
															}
														 }
													 }
?>
	 <tr>
													  <td colspan"3" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
					  
					  <td  align="right" style="color:#F00"></td>
                      <td  align="right" style="color:#F00"></td>
                      <td  align="center" style="color:#F00"><strong><?PHP echo number_format($TotalPaid,2); ?></strong></td>
												 </tr>	
    											  
<?PHP 
													//echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
														
<?PHP
										  		 //	}

                          

								}
				           }
				       }  
?>
<tr>
						  <td colspan="4" align="left" ><strong>Total Fee Paid in Class:<div style="color:#F00"> <?PHP echo "  "."N".number_format($ClassTotalFeePaid,2); ?></div></strong></td>
						</tr>
<?php
            
                   }             
             }

?>
					
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Charge Summary") {
?>
<TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="fees.php?subpg=Student fee">Fees</a> &gt; <a href="fees.php?subpg=Charge Summary">Charge Summary</a> &gt; Charge Summary</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Charge Summary</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				 <tbody>
					
<?PHP               
                    $classcounter = 0;
					//$TotalPaid = 0;
					if($SrchMethod == "All"){
						$counter = 0;
						$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$classcounter++;
								$ClassID = $row["ID"];
								$ClassName = $row["Class_Name"];
								$DisplayClass = "False";
								//$StuType = 0;
								$isEnable  = '1';
										$ClassTotalFeePaid = ChkClassTotalFeePaid($ClassID);
										$TotalFeePaid = $TotalFeePaid + $ClassTotalFeePaid ;
										//if($isEnable>0){
											//if($DisplayClass == "False"){
												//$DisplayClass = "True";
												?>
												<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
                                                <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
                                                                   
                                              
<?PHP
$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"]; ?>
                                                            
															<tr>
												  <td colspan="4" bgcolor="#666666" align="center"><font color="#FFFFFF" size="3"><strong>CHARGE NAME:&nbsp;&nbsp;&nbsp;<?PHP echo $ChargeName; ?></strong></font></td>
												</tr>
			                           <?PHP       
													  $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
																  
                                $counter = 0;
								 $TotalPay = 0;
			                         $TotalPaid = 0;
			                             $TotalBal =0;
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
							
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															//$Balance  = $dbarray7['Balance'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
														
															
															//if($Balance ==""){
															//	$Balance  = "0";
															//}
														 $query10 = "select Amount from tbclasscharges where ClassID = '$ClassID' and ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result10 = mysql_query($query10,$conn);
															$dbarray10 = mysql_fetch_array($result10);
															$AmtPayable  = $dbarray10['Amount'];
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															
															
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
													  $Balance = $AmtPayable -$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
														 
									}
								}
								?>
                                <tr>
												  <td colspan="2" align="left"><strong>CHARGE AMOUNT PAID :&nbsp;&nbsp;&nbsp;<span style="color:#030"><?PHP echo number_format($TotalPaid,2);?></span></strong></td>
                                                  <td colspan="1" align="left"><strong>CHARGE AMOUNT PAYABLE :&nbsp;&nbsp;&nbsp;<span style="color:#00F"><?PHP echo number_format($TotalPay,2); ?></span></strong></td>
                                                  <td colspan="1" align="left" ><strong>CHARGE AMOUNT BALANCE :&nbsp;&nbsp;&nbsp;<span style="color:#F00"><?PHP echo number_format($TotalBal,2); ?></span></strong></td>
												</tr>
                                
				<?PHP							
		}
	}
			?>
            <tr><td colspan="4"> &nbsp; <td></td>
            <tr><td colspan="4"> &nbsp; <td></td>
            <?PHP							
									}
								}
								?>
						</tbody></table>
											</td>
										</tr>	
                  <?PHP
					}elseif($SrchMethod == "Class"){
						//$OptClass
						$query = "select Class_Name from tbclassmaster where ID = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$ClassID  = $OptClass;
						$ClassName  = $dbarray['Class_Name'];
						$StuType = 0;
						 $DisplayClass = False;
								$isEnable  = '1';
										//$isEnable = ChkDefaulters($ClassID,$StuType);
										$ClassTotalFeePaid = ChkClassTotalFeePaid($ClassID);
										if($isEnable>0){
												$DisplayClass = "True";
												?>

						<tr>
												  <td colspan="4" align="left"><strong>CLASS :&nbsp;&nbsp;&nbsp;<?PHP echo $ClassName; ?></strong></td>
												</tr>
                                                <tr>
												  <td colspan="4" align="left" valign="top"><p style="margin-left:80px;"><strong>First Term / Section </strong></p></td>
												</tr>
<?PHP
$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";                 
                                 $counter = 0;
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$StuType = "";
										$RegNo = $row2["Stu_Regist_No"];
										$Stu_Sec = $row2["Stu_Sec"];
										$Stu_Full_Name = $row2["Stu_Full_Name"];
										$AdmissionNo = $row2["AdmissionNo"];
										$StuType = $row2["Stu_Type"];
										
										$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
										
										$query1 = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result1 = mysql_query($query1,$conn);
										$dbarray1 = mysql_fetch_array($result1);
										$Contact  = $dbarray0['Gr_Ph'];
										        
												$TotalPay = 0;
	                                             $TotalPaid= 0;
	                                                $TotalBal= 0;
												$query9 = "select ChargeName from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
			                                                 $TotalPay = $TotalPay +$AmtPayable;
			                                         $TotalPaid = $TotalPaid +$PaidAmount;
			                                             $TotalBal = $TotalBal +$Balance;
		}
	}
									
								$showstudentname = false;
								if($TotalPaid >= 0){
			
										
	
											
?>
											 <tr>
													  <td width="61%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="14%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="12%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong></strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Amount Paid</strong></font></td>
												 </tr>
<?PHP    
$counter = 0;
													//$TotalAmt = 0;
													$TotalPay = 0;
													$TotalPaid= 0;
													$TotalBal= 0;
$query5 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$counter = $counter+1;
			
			$ChargeName = $row5["ChargeName"];
			$AmtPayable  = $row5['Amount'];
			$query6 = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			    $result6 = mysql_query($query6,$conn);
				$dbarray6 = mysql_fetch_array($result6);
				$chargeID  = $dbarray6['ID'];
															
									$query4 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID='$chargeID' and LastEntryDate='$EntryDate' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
															$result4 = mysql_query($query4,$conn);
															$dbarray4 = mysql_fetch_array($result4);
															$AmtPayable2  = $dbarray4['Amount'];
															$PaidAmount  = $dbarray4['PaidAmount'];
															$Balance  = $dbarray4['Balance'];
															$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															if($AmtPayable ==""){
																$AmtPayable  = "0";
															}
															if($Balance == 0 and $AmtPayable !== 0){
																$Balance = $AmtPayable -$PaidAmount;
															}
															//$Balance = 0;
															//$query7 = "select Amount from tbclasscharges where ChargeName='$ChargeName' and ClassID ='ClassID'";
															//$result7 = mysql_query($query7,$conn);
															//$dbarray7 = mysql_fetch_array($result7);
															//$AmtPayable  = $dbarray7['Amount'];	
															//$ChargeName  = $dbarray7['ChargeName'];
															$isDisplay = "True";
															//$isDisplay = "true";
																		
															//if($AmtPayable ==""){
																//$AmtPayable  = $dbarray5['Amount'];
																$Balance = $AmtPayable - $PaidAmount;
															//}
															//if($AmtPayable == 0){
															//	$isDisplay = "false";
															//}
																//$Balance = $AmtPayable - $PaidAmount;
																//$TotalAmt = $TotalAmt +$Amount;
																$TotalPay = $TotalPay +$AmtPayable;
																$TotalPaid = $TotalPaid +$PaidAmount;
																$TotalBal = $TotalBal +$Balance;
															//}
															if($isDisplay == "True"){
?>
															  
<?PHP
															}
														 }
													 }
?>
	 <tr>
													  <td colspan"3" align="left"><p style="margin-left:30px;"><strong>Admission No :&nbsp;</strong><?PHP echo $AdmissionNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Name :&nbsp;</strong><?PHP echo $Stu_Full_Name; ?>&nbsp;[<?PHP echo $Contact; ?>]</p></td>
					  
					  <td  align="right" style="color:#F00"></td>
                      <td  align="right" style="color:#F00"></td>
                      <td  align="center" style="color:#F00"><strong><?PHP echo number_format($TotalPaid,2); ?></strong></td>
												 </tr>	
    											  
<?PHP 
													//echo number_format($TotalBal,2);
													//if($Method == "SMS"){
														//$isSend_Status="False";
														//echo "/".$AdmissionNo.",".$Stu_Full_Name.",".'1st Term'.",".$TotalBal.",".$Contact;
														//$isSend_Status = sendFeesDefaulter($AdmissionNo,$Stu_Full_Name,'1st Term',$TotalBal,$Contact);
														//if($isSend_Status == "False"){
															//$CountSMS = $CountSMS;
														//}elseif($isSend_Status == "True"){
														//	$CountSMS = $CountSMS +1;
														//}	
													//}
?>
													
<?PHP
										  			//if($isDisplay_fine == "True"){
?>
														
<?PHP
										  		 //	}

                          

								}
				           }
				       }  
?>
<tr>
						  <td colspan="4" align="left" ><strong>Total Fee Paid in Class:<div style="color:#F00"> <?PHP echo "  "."N".number_format($ClassTotalFeePaid,2); ?></div></strong></td>
						</tr>
<?php
            
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
