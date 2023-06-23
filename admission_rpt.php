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
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	if(isset($_GET['rid']))
	{
		$regis_id = $_GET['rid'];
		$query = "select * from tbregistration where ID='$regis_id' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$ReDate  = $dbarray['Stu_DateRegis'];
		$FirstName  = $dbarray['Stu_FirstName'];
		$MidName  = $dbarray['Stu_MidName'];
		$LastName  = $dbarray['Stu_LastName'];
		$RegAddress  = $dbarray['Stu_Address'];
		$PhoneNo  = $dbarray['Stu_Phone'];
		$ClassID  = $dbarray['Stu_ClassID'];
		$regisFee  = $dbarray['RegFee'];
		$PaMr  = $dbarray['Stu_Father'];
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
	if(isset($_GET['admid']))
	{
		$AdmnNo = $_GET['admid'];
		$query3 = "select * from tb_slcertificate where admno='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$isFailed = $row["failed"];
				if($isFailed == 1){
					$isFailed = "YES";
				}else{
					$isFailed = "NO";
				}
				$ispromoted = $row["Promot"];
				if($ispromoted == 1){
					$ispromoted = "YES";
				}else{
					$ispromoted = "NO";
				}
				$last_due_mth = $row["due_Month"];
				$last_due_yr = $row["due_Year"];
				$workingday = $row["No_work_days"];
				$Noofdayspresent = $row["No_Pr_Days"];
				$GamePlayed = $row["Games"];
				$Conduct = $row["Conduct"];
				$AppDate = $row["App_Date"];
				$IssDate  = $row["Iss_Date"];
				$OptLastClass = $row["Last_Class"];
				
				$i = 0;
				$query2 = "select SubjectId from tbclasssubjectrelation where ClassId = '$OptLastClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result2 = mysql_query($query2,$conn);
				$num_rows2 = mysql_num_rows($result2);
				if ($num_rows2 > 0 ) {
					while ($row2 = mysql_fetch_array($result2)) 
					{
						$arr_Subj[$i] = $row2["SubjectId"]; 
						$i = $i+1;
					}
				}
				
				$OptPromotedClass = $row["Pr_Class"];
				$AdmnDate = $row["Date_Admin"];
				$Reasonsleaving = $row["Reason"];
				$remarks = $row["AnyOther"];
				
				$query = "select Stu_Full_Name,Stu_DOB,Stu_Regist_No from tbstudentmaster where AdmissionNo='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$StudentName  = $dbarray['Stu_Full_Name'];
				$DOB  = $dbarray['Stu_DOB'];
				$StuRegNo  = $dbarray['Stu_Regist_No'];
				
				$query = "select Gr_Name_Mr,Gr_Name_Ms from tbstudentdetail where Stu_Regist_No='$StuRegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$fathername  = $dbarray['Gr_Name_Mr'];
				$mothername  = $dbarray['Gr_Name_Ms'];
			
			}
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
    <TD height="503" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
	  <TR>
	  	<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
	  </TR>
<?PHP
		if ($Page == "Registration Certificate") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="registration.php?subpg=Admission">Admission</a> &gt; <a href="registration.php?subpg=Registration">Registration</a> &gt; Registration Certificate</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Registration Certificate</strong></div>
				<table  width="80%" cellpadding="8" cellspacing="0" border="0">
				  <tbody>
					<tr>
					  <td colspan="2" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Registration No : <?PHP echo $regis_id; ?> </strong></td>
					  <td colspan="2" align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Dated : <?PHP echo Long_date($ReDate); ?></strong> </td>
					</tr>		
					<tr>
					  <td colspan="4" align="left">&nbsp;</td>
					</tr>
					<tr>
					  <td width="19%" align="left">Student Name:</td>
					  <td colspan="3" align="left"><?PHP echo $LastName; ?>&nbsp;<?PHP echo $MidName; ?>&nbsp;<?PHP echo $FirstName; ?>&nbsp; </td>
					</tr>
					<tr>
					  <td align="left">Father's Name:</td>
					  <td colspan="3" align="left"><?PHP echo $PaMr; ?>&nbsp;</td>
					</tr>
					<tr>
					  <td align="left">Address:</td>
					  <td colspan="3" align="left"><?PHP echo $RegAddress; ?>&nbsp;</td>
					</tr>
					<tr>
					  <td align="left">&nbsp;</td>
					  <td colspan="3" align="left">&nbsp;</td>
					</tr>
					<tr>
					  <td align="left">Contact No.:</td>
					  <td colspan="3" align="left"><?PHP echo $PhoneNo; ?>&nbsp;</td>
					</tr>
					<tr>
					  <td align="left">Class Name:</td>
					  <td width="31%" align="left"><?PHP echo GetClassName($ClassID); ?>&nbsp;</td>
					  <td width="14%" align="left">Registration Fee:</td>
					  <td width="36%" align="left"><?PHP echo number_format($regisFee,2); ?>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="4" align="left">&nbsp;</td>
					</tr>
					<tr>
					  <td align="left">&nbsp;</td>
					  <td width="31%" align="left">&nbsp;</td>
					  <td width="14%" align="left">&nbsp;</td>
					  <td width="36%" align="left"><hr></td>
					</tr>
					<tr>
					  <td align="left">&nbsp;</td>
					  <td width="31%" align="left">&nbsp;</td>
					  <td width="14%" align="left">&nbsp;</td>
					  <td width="36%" align="center">Authorised Signatory</td>
					</tr>
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
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Enquiries Followup List</strong></div><div align="center" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><?PHP echo Long_date($FrmDate); ?> - <?PHP echo Long_date($ToDate); ?></div>
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
					$query = "select ID,Class_Name from tbclassmaster order by ID where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
								$query4   = "SELECT COUNT(*) AS numrows FROM tbenquiry Where Closed = 0 and ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
								$query3 = "select * from tbenquiry where Closed = 0 And ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by StudentName";
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
												$query5 = "select * from tbenquiryfollowup where EnquiryID ='$EnqNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
					$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
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
								$query4   = "SELECT COUNT(*) AS numrows FROM tbenquiry Where Closed = 1 and ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
								$query3 = "select * from tbenquiry where Closed = 1 And ClassID = '$Class_ID' and EnquiryDate = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by StudentName";
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
												$query5 = "select * from tbenquiryfollowup where EnquiryID ='$EnqNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
		}elseif ($Page == "Transfer Certificate") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="registration.php?subpg=Admission">Admission</a> &gt; <a href="studcharges.php?subpg=School%20Leaving%20certificate">School Leaving certificate</a> &gt; Transfer Certificate</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Transfer Certificate</strong></div>
				<table  width="80%" cellpadding="8" cellspacing="0" border="0">
				  <tbody>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Admission No : <?PHP echo $AdmnNo; ?> </strong></td>
					  <td align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Dated : <?PHP echo Long_date($IssDate); ?></strong> </td>
					</tr>		
					<tr>
					  <td colspan="2" align="left">&nbsp;</td>
					</tr>
					<tr >
					  <td width="38%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Name of Student:</div></td>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo $StudentName; ?>&nbsp; </td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Father's/Guardian's Name:</div></td>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo $fathername; ?>&nbsp;</td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Mother's/Guardian's:</div></td>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo $mothername; ?>&nbsp;</td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Date of Admission to the school</div></td>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo Long_date($AdmnDate); ?></td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Date of Birth .:</div></td>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo Long_date($DOB); ?>&nbsp;</td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Class in which the student last studied:</div></td>
					  <td width="62%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo GetClassName($OptLastClass); ?>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Whether failed, if so once/twice in the same class &nbsp;&nbsp;<?PHP echo $isFailed; ?>&nbsp;</div></td>
					</tr>
					<tr>
					  <td colspan="2" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Subject Studied:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>
<?PHP
					    $n = 0;
						while(isset($arr_Subj[$n])){
							echo GetSubjectName($arr_Subj[$n])."  , ";
							$n = $n+1;
						}
?> 
					  </strong></div></td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Whether qualified for promotion to the higher class</div></td>
					  <td width="62%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"> <?PHP echo $ispromoted; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if so, to which class (in fig)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo GetClassName($OptPromotedClass); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(In Word)_______________</td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Month upto which the student has paid school dues.</div></td>
					  <td width="62%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo $last_due_mth; ?>&nbsp;&nbsp;<?PHP echo $last_due_yr; ?></td>
					</tr>
					<tr>
					  <td align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Total No. of working days</div></td>
					  <td width="62%" align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $workingday; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total No working days present&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $Noofdayspresent; ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
					  <td align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Game Played or extra curricular activities in which the student usually took part</div></td>
					  <td width="62%" align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo $GamePlayed; ?></td>
					</tr>
					<tr>
					  <td align="left" colspan="2"><div align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;">(Mention achievement level therein):  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;________________________________________</div></td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">General Conduct</div></td>
					  <td width="62%" align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo $Conduct; ?> &nbsp;</td>
					</tr>
					<tr>
					  <td align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Date of application</div></td>
					  <td width="62%" align="left"style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo Long_date($AppDate); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date of issue of certificate: <?PHP echo Long_date($IssDate); ?></td>
					</tr>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><div align="left">Reason for leaving the school</div></td>
					  <td width="62%" align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo $Reasonsleaving; ?>&nbsp;</td>
					</tr>
					<tr>
					  <td align="left">&nbsp;</td>
					  <td width="62%" align="center"><p>&nbsp;</p>
					    <p>_________________________________<br>
				          <br>
				      Authorised Signatory</p></td>
					</tr>
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
