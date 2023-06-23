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
		$FrmDate = $_GET['fdate']; 
		$ToDate = $_GET['tdate'];
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
	  	<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
	  </TR>
<?PHP
		if ($Page == "Print List") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="enquiry.php?subpg=Enquiry">Enquiry</a> &gt; Print List</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Enquiries List</strong></div><div align="center" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: maroon; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><?PHP echo Long_date($FrmDate); ?> - <?PHP echo Long_date($ToDate); ?></div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><strong>Pending job</strong></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="11%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Date</strong></font></td>
					  <td width="7%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Enq. No.</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Address</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Phone</strong></font></td>
					  <td width="16%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Counselor</strong></font></td>
					  <td width="17%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Remarks</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$query = "select ID,Class_Name from tbclassmaster order by ID";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$Class_ID = $row["ID"];
							$Class_Name = $row["Class_Name"];
							
							$arrDateList = date_range($FrmDate,$ToDate);
							$i = 0;
							while(isset($arrDateList[$i])){
								$numrows = 0;
								$query4   = "SELECT COUNT(*) AS numrows FROM tbenquiry Where Closed = 0 and ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]'";
								$result4  = mysql_query($query4,$conn) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$TotRec = $row4['numrows'];
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><strong>CLASS NAME:&nbsp;&nbsp;&nbsp;<?PHP echo $Class_Name; ?></strong></td>
									</tr>
<?PHP
								}
								$query3 = "select * from tbenquiry where Closed = 0 And ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' order by StudentName";
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter = $counter+1;
										$EnqNo = $row["ID"];
										$EnquiryDate = $row["EnquiryDate"];
										$StudentName = $row["StudentName"];
										$Address = $row["Address"];
										$GuardianContactNo = $row["GuardianContactNo"];
										$CounselorID = $row["CounselorID"];
										$Remarks = $row["Remarks"];
?>
										<tr>
										  <td width="11%"  align="center"><?PHP echo $EnquiryDate; ?>&nbsp;</td>
										  <td width="7%" align="center"><?PHP echo $EnqNo; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $StudentName; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $Address; ?>&nbsp;</td>
										  <td width="13%" align="center"><?PHP echo $GuardianContactNo; ?>&nbsp;</td>
										  <td width="16%" align="center"><?PHP echo GET_EMP_NAME($CounselorID); ?>&nbsp;</td>
										  <td width="17%" align="center"><?PHP echo $Remarks; ?>&nbsp;</td>
										</tr>
<?PHP
									}
								}
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><hr><strong>Total Enquiries for <?PHP echo $Class_Name; ?> are &nbsp;&nbsp;&nbsp;<?PHP echo $TotRec; ?></strong><hr></td>
									</tr>
<?PHP
								}
								$i=$i+1;
							}
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br>
			  <div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><strong>Closed job</strong>s</div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="11%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Date</strong></font></td>
					  <td width="7%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Enq. No.</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Address</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Phone</strong></font></td>
					  <td width="16%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Counselor</strong></font></td>
					  <td width="17%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Remarks</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$query = "select ID,Class_Name from tbclassmaster order by ID";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$Class_ID = $row["ID"];
							$Class_Name = $row["Class_Name"];
							
							$arrDateList = date_range($FrmDate,$ToDate);
							$i = 0;
							while(isset($arrDateList[$i])){
								$numrows = 0;
								$query4   = "SELECT COUNT(*) AS numrows FROM tbenquiry Where Closed = 1 and ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]'";
								$result4  = mysql_query($query4,$conn) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$TotRec = $row4['numrows'];
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><strong>CLASS NAME:&nbsp;&nbsp;&nbsp;<?PHP echo $Class_Name; ?></strong></td>
									</tr>
<?PHP
								}
								$query3 = "select * from tbenquiry where Closed = 1 And ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' order by StudentName";
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter = $counter+1;
										$EnqNo = $row["ID"];
										$EnquiryDate = $row["EnquiryDate"];
										$StudentName = $row["StudentName"];
										$Address = $row["Address"];
										$GuardianContactNo = $row["GuardianContactNo"];
										$CounselorID = $row["CounselorID"];
										$Remarks = $row["Remarks"];
?>
										<tr>
										  <td width="11%"  align="center"><?PHP echo $EnquiryDate; ?>&nbsp;</td>
										  <td width="7%" align="center"><?PHP echo $EnqNo; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $StudentName; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $Address; ?>&nbsp;</td>
										  <td width="13%" align="center"><?PHP echo $GuardianContactNo; ?>&nbsp;</td>
										  <td width="16%" align="center"><?PHP echo GET_EMP_NAME($CounselorID); ?>&nbsp;</td>
										  <td width="17%" align="center"><?PHP echo $Remarks; ?>&nbsp;</td>
										</tr>
<?PHP
									}
								}
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><hr><strong>Total Enquiries for <?PHP echo $Class_Name; ?> are &nbsp;&nbsp;&nbsp;<?PHP echo $TotRec; ?></strong><hr></td>
									</tr>
<?PHP
								}
								$i=$i+1;
							}
						}
					}
?>
			      </tbody>
			  </table>
			</TD>
		  </TR>
<?PHP
		}elseif ($Page == "Enquiries Followup List") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong>
<?PHP
				if(isset($_GET['bk']))
				{
					$backpg = $_GET['bk'];
					echo $backpg;
				}
?>
				</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Enquiries Followup List</strong></div><div align="center" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: maroon; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><?PHP echo Long_date($FrmDate); ?> - <?PHP echo Long_date($ToDate); ?></div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><strong>Pending job</strong></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="11%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Date</strong></font></td>
					  <td width="7%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Enq. No.</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Address</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Phone</strong></font></td>
					  <td width="16%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Counselor</strong></font></td>
					  <td width="17%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Remarks</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$query = "select ID,Class_Name from tbclassmaster order by ID";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$Class_ID = $row["ID"];
							$Class_Name = $row["Class_Name"];
							
							$arrDateList = date_range($FrmDate,$ToDate);
							$i = 0;
							while(isset($arrDateList[$i])){
								$numrows = 0;
								$query4   = "SELECT COUNT(*) AS numrows FROM tbenquiry Where Closed = 0 and ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]'";
								$result4  = mysql_query($query4,$conn) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$TotRec = $row4['numrows'];
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><strong>CLASS NAME:&nbsp;&nbsp;&nbsp;<?PHP echo $Class_Name; ?></strong></td>
									</tr>
<?PHP
								}
								$query3 = "select * from tbenquiry where Closed = 0 And ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' order by StudentName";
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter = $counter+1;
										$EnqNo = $row["ID"];
										$EnquiryDate = $row["EnquiryDate"];
										$StudentName = $row["StudentName"];
										$Address = $row["Address"];
										$GuardianContactNo = $row["GuardianContactNo"];
										$CounselorID = $row["CounselorID"];
										$Remarks = $row["Remarks"];
?>
										<tr>
										  <td width="11%"  align="center"><?PHP echo $EnquiryDate; ?>&nbsp;</td>
										  <td width="7%" align="center"><?PHP echo $EnqNo; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $StudentName; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $Address; ?>&nbsp;</td>
										  <td width="13%" align="center"><?PHP echo $GuardianContactNo; ?>&nbsp;</td>
										  <td width="16%" align="center"><?PHP echo GET_EMP_NAME($CounselorID); ?>&nbsp;</td>
										  <td width="17%" align="center"><?PHP echo $Remarks; ?>&nbsp;</td>
										</tr>
										<tr>
										  <td colspan="7" align="center">
										  	<table  width="78%" border="1" cellpadding="5" cellspacing="0">
											  <tbody>
												<tr>
												  <td width="11%" align="center" bgcolor="#CCCCCC"><strong>Date</strong></td>
												  <td width="7%" align="center" bgcolor="#CCCCCC"><strong>Counselor</strong></td>
												  <td width="18%" align="center" bgcolor="#CCCCCC"><strong>Follow up mode</strong></td>
												  <td width="18%" align="center" bgcolor="#CCCCCC"><strong>Remark</strong></td>
												</tr>
<?PHP
												$query5 = "select * from tbenquiryfollowup where EnquiryID ='$EnqNo'";
												$result5 = mysql_query($query5,$conn);
												$num_rows5 = mysql_num_rows($result5);
												if ($num_rows5 > 0 ) {
													while ($row5 = mysql_fetch_array($result5)) 
													{
														$FollID = $row5["ID"];
														$FollowUpDate = $row5["FollowUpDate"];
														$FollCounselorID = $row5["CounselorID"];
														$FollMode = $row5["Mode"];
														$FollRemarks = $row5["Remarks"];
?>
										  				<tr>
														  <td width="11%" align="center"><strong><?PHP echo $FollowUpDate; ?>&nbsp;</strong></td>
														  <td width="7%" align="center"><strong><?PHP echo $FollCounselorID; ?>&nbsp;</strong></td>
														  <td width="18%" align="center"><strong><?PHP echo $FollMode; ?>&nbsp;</strong></td>
														  <td width="18%" align="center"><strong><?PHP echo $FollRemarks; ?>&nbsp;</strong></td>
														</tr>
<?PHP
													 }
												 }	
?>
											  </tbody>
											</table>
										  </td>
										</tr>
<?PHP
									}
								}
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><hr><strong>Total Enquiries for <?PHP echo $Class_Name; ?> are &nbsp;&nbsp;&nbsp;<?PHP echo $TotRec; ?></strong><hr></td>
									</tr>
<?PHP
								}
								$i=$i+1;
							}
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br>
			  <div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"><strong>Closed job</strong>s</div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="11%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Date</strong></font></td>
					  <td width="7%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Enq. No.</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Student</strong></font></td>
					  <td width="18%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Address</strong></font></td>
					  <td width="13%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Phone</strong></font></td>
					  <td width="16%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Counselor</strong></font></td>
					  <td width="17%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Remarks</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$query = "select ID,Class_Name from tbclassmaster order by ID";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$Class_ID = $row["ID"];
							$Class_Name = $row["Class_Name"];
							
							$arrDateList = date_range($FrmDate,$ToDate);
							$i = 0;
							while(isset($arrDateList[$i])){
								$numrows = 0;
								$query4   = "SELECT COUNT(*) AS numrows FROM tbenquiry Where Closed = 1 and ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]'";
								$result4  = mysql_query($query4,$conn) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$TotRec = $row4['numrows'];
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><strong>CLASS NAME:&nbsp;&nbsp;&nbsp;<?PHP echo $Class_Name; ?></strong></td>
									</tr>
<?PHP
								}
								$query3 = "select * from tbenquiry where Closed = 1 And ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' order by StudentName";
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter = $counter+1;
										$EnqNo = "";
										$EnqNo = $row["ID"];
										$EnquiryDate = $row["EnquiryDate"];
										$StudentName = $row["StudentName"];
										$Address = $row["Address"];
										$GuardianContactNo = $row["GuardianContactNo"];
										$CounselorID = $row["CounselorID"];
										$Remarks = $row["Remarks"];
?>
										<tr>
										  <td width="11%"  align="center"><?PHP echo $EnquiryDate; ?>&nbsp;</td>
										  <td width="7%" align="center"><?PHP echo $EnqNo; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $StudentName; ?>&nbsp;</td>
										  <td width="18%" align="center"><?PHP echo $Address; ?>&nbsp;</td>
										  <td width="13%" align="center"><?PHP echo $GuardianContactNo; ?>&nbsp;</td>
										  <td width="16%" align="center"><?PHP echo GET_EMP_NAME($CounselorID); ?>&nbsp;</td>
										  <td width="17%" align="center"><?PHP echo $Remarks; ?>&nbsp;</td>
										</tr>
										<tr>
										  <td colspan="7" align="center">
										  	<table  width="78%" border="1" cellpadding="5" cellspacing="0">
											  <tbody>
												<tr>
												  <td width="11%" align="center" bgcolor="#CCCCCC"><strong>Date</strong></td>
												  <td width="7%" align="center" bgcolor="#CCCCCC"><strong>Counselor</strong></td>
												  <td width="18%" align="center" bgcolor="#CCCCCC"><strong>Follow up mode</strong></td>
												  <td width="18%" align="center" bgcolor="#CCCCCC"><strong>Remarks</strong></td>
												</tr>
<?PHP
												$query5 = "select * from tbenquiryfollowup where EnquiryID ='$EnqNo'";
												$result5 = mysql_query($query5,$conn);
												$num_rows5 = mysql_num_rows($result5);
												if ($num_rows5 > 0 ) {
													while ($row5 = mysql_fetch_array($result5)) 
													{
														$FollID = $row5["ID"];
														$FollowUpDate = $row5["FollowUpDate"];
														$FollCounselorID = $row5["CounselorID"];
														$FollMode = $row5["Mode"];
														$FollRemarks = $row5["Remarks"];
														
?>
										  				<tr>
														  <td width="11%" align="center"><strong><?PHP echo $FollowUpDate; ?>&nbsp;</strong></td>
														  <td width="7%" align="center"><strong><?PHP echo $FollCounselorID; ?>&nbsp;</strong></td>
														  <td width="18%" align="center"><strong><?PHP echo $FollMode; ?>&nbsp;</strong></td>
														  <td width="18%" align="center"><strong><?PHP echo $FollRemarks; ?>&nbsp;</strong></td>
														</tr>
<?PHP
													 }
												 }	
?>
											  </tbody>
											</table>
										  </td>
										</tr>
<?PHP
									}
								}
								if($TotRec >0){
?>
									<tr>
									  <td colspan="7" align="left"><hr><strong>Total Enquiries for <?PHP echo $Class_Name; ?> are &nbsp;&nbsp;&nbsp;<?PHP echo $TotRec; ?></strong><hr></td>
									</tr>
<?PHP
								}
								$i=$i+1;
							}
						}
					}
?>
			      </tbody>
			  </table>
			</TD>
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
