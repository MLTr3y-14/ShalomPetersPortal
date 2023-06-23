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
	
	if(isset($_GET['ty']))
	{
		$ischecked = $_GET['ty'];
		$OptEmployeeName = $_GET['tch'];
		
	}
	if(isset($_GET['typ']))
	{
		$ischecked = $_GET['typ'];
		$OptEmployeeName = $_GET['stf'];
		$OptDepartment = $_GET['dpt'];
		//if($OptStaff != "")
		//{
		//	$query2 = "select ID,EmpName,EmpDept,EmpDesig from tbemployeemasters where ID ='$OptStaff'";
		//}else{
		//	$query2 = "select ID,EmpName,EmpDept,EmpDesig from tbemployeemasters order by EmpName";
		//}
	}
	if(isset($_GET['fdt']))
	{
		$FromDate = $_GET['fdt'];
		$ToDate = $_GET['tdt'];
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
			<TD><div align="center"><img src="images/upload/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
<?PHP
		if ($Page == "Department And Designation Wise details") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="empreport.php?subpg=Department And Designation Wise details">Report</a> &gt; <a href="empreport.php?subpg=Department And Designation Wise details">Department And Designation Wise details</a> &gt; Department And Designation Wise details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Departments And Designations Definition</strong></div>
				</div>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="70%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 70%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="39" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>#</strong></font></td>
					  <td width="114" bgcolor="#666666" align="left"><font color="#FFFFFF"><strong>Department</strong></font></td>
					  <td width="298" bgcolor="#666666" align="left"><font color="#FFFFFF"><strong>Designation List</strong></font></td>
					</tr>
<?PHP
					$Counter=0;
					$query1 = "select * from tbdepartments order by DeptName";
					$result1 = mysql_query($query1,$conn);
					$num_rows1 = mysql_num_rows($result1);
					if ($num_rows1 > 0 ) {
						while ($row1 = mysql_fetch_array($result1)) 
						{
							$Counter=$Counter+1;
							$DeptID = $row1["ID"];
							$DeptName = $row1["DeptName"];
?>
							  <tr>
								<td width="39" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $Counter; ?></td>
								<td width="114" align="left" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $DeptName; ?></td>
								<td width="298" align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; height:30px;">
<?PHP
								$query2 = "select * from tbdesignations where DeptId = '$DeptID' order by DesignName";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								if ($num_rows2 > 0 ) {
									while ($row2 = mysql_fetch_array($result2)) 
									{
										$DesignID = $row2["ID"];
										$DesignName = $row2["DesignName"];
										echo $DesignName."<br>";
									}
								}
?>								</td>
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
		}elseif ($Page == "Employee Department and Designation Details") {
?>
<TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="empreport.php?subpg=Department And Designation Wise details">Report</a> &gt; <a href="empreport.php?subpg=Department And Designation Wise details">Department And Designation Wise details</a> &gt; Department And Designation Wise details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Department and Designation Details For <?PHP echo $OptEmployeeName  ?></strong></div>
				</div>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="70%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 70%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				 <tbody>
					<tr>
					  <td width="57" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>#</strong></font></td>
                      <td width="282" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Employee Name</strong></font></td>
					  <td width="210" bgcolor="#666666" align="left"><font color="#FFFFFF"><strong>Department</strong></font></td>
					  <td width="172" bgcolor="#666666" align="left"><font color="#FFFFFF"><strong>Designation </strong></font></td>
					</tr>
<?PHP
					$Counter=0;
					$query = "select * from tbemployeemasters where EmpName ='$OptEmployeeName' order by EmpName";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$Counter=$Counter+1;
							$EmployeeName = $row["EmpName"];
							$DeptID = $row["EmpDept"];
							$DesignID = $row["EmpDesig"];
							
					          $query1 = "select DeptName from tbdepartments where ID = '$DeptID'";
						       $result1 = mysql_query($query1,$conn);
					             $row1 = mysql_fetch_array($result1);
							        $DeptName = $row1["DeptName"];
?>
							  
<?PHP
								$query2 = "select DesignName from tbdesignations where ID = '$DesignID'";
								  $result2 = mysql_query($query2,$conn);
								$row2 = mysql_fetch_array($result2);
										 $DesignName = $row2["DesignName"];
?>								
								 <tr>
								<td width="57" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $Counter; ?></td>
                                <td width="282" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $OptEmployeeName; ?></td>
								<td width="210" align="left" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $DeptName; ?></td>
								<td width="172" align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $DesignName; ?></td>
							  </tr>
<?PHP 
									}
								}
?>								
                                    
<?PHP
						//}
					//}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "All Employee Department and Designation Details") {
?>
<TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="empreport.php?subpg=Department And Designation Wise details">Report</a> &gt; <a href="empreport.php?subpg=Department And Designation Wise details">Department And Designation Wise details</a> &gt; Department And Designation Wise details</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>All Employee Department and Designation Details</strong></div>
				</div>
				
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="70%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 70%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="57" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>#</strong></font></td>
                      <td width="282" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Employee Name</strong></font></td>
					  <td width="210" bgcolor="#666666" align="left"><font color="#FFFFFF"><strong>Department</strong></font></td>
					  <td width="172" bgcolor="#666666" align="left"><font color="#FFFFFF"><strong>Designation </strong></font></td>
					</tr>
<?PHP
					$Counter=0;
					$query = "select * from tbemployeemasters order by EmpName";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$Counter=$Counter+1;
							$EmployeeName = $row["EmpName"];
							$DeptID = $row["EmpDept"];
							$DesignID = $row["EmpDesig"];
							
					          $query1 = "select DeptName from tbdepartments where ID = '$DeptID'";
						       $result1 = mysql_query($query1,$conn);
					             $row1 = mysql_fetch_array($result1);
							        $DeptName = $row1["DeptName"];
?>
							  
<?PHP
								$query2 = "select DesignName from tbdesignations where ID = '$DesignID'";
								  $result2 = mysql_query($query2,$conn);
								$row2 = mysql_fetch_array($result2);
										 $DesignName = $row2["DesignName"];
?>								
								 <tr>
								<td width="57" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $Counter; ?></td>
                                <td width="282" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $EmployeeName; ?></td>
								<td width="210" align="left" style="BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $DeptName; ?></td>
								<td width="172" align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; height:30px;"><?PHP echo $DesignName; ?></td>
							  </tr>
<?PHP 
									}
								}
?>								
                                    
<?PHP
						//}
					//}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>

<?PHP
		}elseif ($Page == "Teacher subject") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="empreport.php?subpg=Teachers' subject">Report</a> &gt; <a href="empreport.php?subpg=Teachers' subject">Teachers' subject</a> &gt; Teachers' subject Report</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Teacher Subject</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				
				<table  width="65%" align="center" cellpadding="0" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 65%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="134" bgcolor="#666666" align="right"><p><font color="#FFFFFF"><strong>Subject name</strong></font></p></td>
					  <td width="113" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Short Name</strong></font></td>
					  <td width="99" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Subject Type</strong></font></td>
					  <td width="68" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Priority</strong></font></td>
					</tr>
<?PHP               
                   $counter = 0;
                   if($OptEmployeeName == "")
		{ $query2 = "select * from tbemployeemasters where isTeaching = '1'"; }
		else{
		     $query2 = "select * from tbemployeemasters where EmpName = '$OptEmployeeName'";
		}
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter = $counter+1;
							$EmpID = $row2["ID"];
							$EmpName = $row2["EmpName"];
							if($counter % 2 == 1){
								$bgs="#F2F2F2";
							}else{
								$bgs="#FFFFFF";
							}
?>
							<tr bgcolor="<?PHP echo $bgs; ?>">
							  <td colspan="4" align="left"><strong><?PHP echo $EmpName; ?></strong></td>
							</tr>
<?PHP
							
							$query3 = "select ClassId from tbclassteachersubj where EmpId = '$EmpID'";
							$result3 = mysql_query($query3,$conn);
							$num_rows3 = mysql_num_rows($result3);
							if ($num_rows3 > 0 ) {
								while ($row3 = mysql_fetch_array($result3)) 
								{
									$ClassID = $row3["ClassId"];
?>
									<tr bgcolor="<?PHP echo $bgs; ?>">
									  <td colspan="4" align="left"><p style="margin-left:30px;"><strong><?PHP echo GetClassName($ClassID); ?></strong></p></td>
									</tr>
<?PHP
									$query4 = "select SubjectId from tbclassteachersubj where EmpId = '$EmpID' and ClassId = '$ClassID'";
									$result4 = mysql_query($query4,$conn);
									$num_rows4 = mysql_num_rows($result4);
									if ($num_rows4 > 0 ) {
										while ($row4 = mysql_fetch_array($result4)) 
										{
											$SubjectID = $row4["SubjectId"];
											
											$query5 = "select * from tbsubjectmaster where ID='$SubjectID'";
											$result5 = mysql_query($query5,$conn);
											$dbarray5 = mysql_fetch_array($result5);
											$Subjname  = $dbarray5['Subj_name'];
											$ShortName  = $dbarray5['ShortName'];
											$Type  = $dbarray5['Sub_Type'];
											if($Type==0){
												$SubjectType="Compulsory";
											}else{
												$SubjectType="Optional";
											}
											$Priority  = $dbarray5['Subj_Priority'];
											if($Priority=='A'){
												$SubjPriority="High";
											}elseif($Priority=='B'){
												$SubjPriority="Normal";
											}elseif($Priority=='C'){
												$SubjPriority="Low";
											}
?>
										  <tr bgcolor="<?PHP echo $bgs; ?>">
											<td width="134" align="right" style="BORDER-BOTTOM: #ccc 1px solid; height:20px;"><?PHP echo $Subjname; ?></td>
											<td width="113" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:20px;"><?PHP echo $ShortName; ?></td>
											<td width="99" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:20px;"><?PHP echo $SubjectType; ?></td>
											<td width="68" align="center" style="BORDER-RIGHT: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; height:20px;"><?PHP echo $SubjPriority; ?></td>
										  </tr>
<?PHP
										}
									}
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
		}elseif ($Page == "Staff Details") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="empreport.php?subpg=Employee Remarks">Report</a> &gt; <a href="empreport.php?subpg=Employee Remarks">Employee Remarks</a> &gt; Employee Remarks</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Employee Remarks</strong></div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				
				<table  width="95%" align="center" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 95%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="180" bgcolor="#666666" align="right"><p align="center"><font color="#FFFFFF"><strong>Employee name</strong></font></p></td>
					  <td width="152" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Department</strong></font></td>
					  <td width="99" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Remark Date</strong></font></td>
					  <td width="456" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Remark</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$counter = $counter+1;
							$EmpID = $row2["ID"];
							$EmpName = $row2["EmpName"];
							$EmpDept = $row2["EmpDept"];
							$EmpDesig = $row2["EmpDesig"];
							if($counter % 2 == 1){
								$bgs="#F2F2F2";
							}else{
								$bgs="#FFFFFF";
							}
?>
							<tr bgcolor="<?PHP echo $bgs; ?>">
							  <td align="left"><div style="margin-left:30px;"><strong><?PHP echo $EmpName; ?></strong></div></td>
							  <td align="left"><div style="margin-left:30px;"><strong><?PHP echo GET_DEPT_NAME($EmpDept); ?></strong></div></td>
							  <td colspan="2" align="Right">&nbsp;</td>
							</tr>
<?PHP							
							$query4 = "select * from tbremark where EmpId = '$EmpID' and Term = '$Activeterm'";
							$result4 = mysql_query($query4,$conn);
							$num_rows4 = mysql_num_rows($result4);
							if ($num_rows4 > 0 ) {
								while ($row4 = mysql_fetch_array($result4)) 
								{
									$date_of_remark = $row4["date_of_remark"];
									$Remark = $row4["Remark"];
									
?>
								  <tr bgcolor="<?PHP echo $bgs; ?>">
									<td width="180" align="right" style="BORDER-BOTTOM: #ccc 1px solid; height:20px;">&nbsp;</td>
									<td width="152" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:20px;">&nbsp;</td>
									<td width="99" align="center" style="BORDER-BOTTOM: #ccc 1px solid; height:20px;"><?PHP echo Long_Date($date_of_remark); ?></td>
									<td width="456" align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; height:20px;"><div style="margin-left:30px;"><?PHP echo $Remark; ?></div></td>
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
		}elseif ($Page == "Teacher Duty") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="empreport.php?subpg=Teacher Duty">Report</a> &gt; <a href="empreport.php?subpg=Teacher Duty">Teacher Duty</a> &gt; Teacher Duty Report</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Teacher Duty </strong></div>
				</div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 10pt; COLOR: #666666; FONT-VARIANT: inherit;">Bewteen &nbsp;&nbsp;&nbsp;<?PHP echo Long_date($FromDate); ?> &nbsp;&nbsp;&nbsp;And &nbsp;&nbsp;&nbsp;<?PHP echo Long_date($ToDate); ?></div>
				
				<table  width="65%" align="center" cellpadding="5" cellspacing="5" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 65%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="134" bgcolor="#666666" align="right"><font color="#FFFFFF"><strong>Duty Name</strong></font></td>
					  <td width="113" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Teacher</strong></font></td>
					  <td width="99" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>From Date</strong></font></td>
					  <td width="68" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>To Date</strong></font></td>
					</tr>
<?PHP
					$counter = 0;
					$arrDateRange = date_range($FromDate,$ToDate);
					$i=0;
					while(isset($arrDateRange[$i])){
						//Check if from date exst
						$query = "select ID from tbassignduty where From_Date = '$arrDateRange[$i]'";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$adutyid = $row["ID"];
								$n=0;
								while(isset($arrDateRange[$n])){
									//Check if to date exst
									$query1 = "select * from tbassignduty where ID = '$adutyid' And To_Date = '$arrDateRange[$n]'";
									$result1 = mysql_query($query1,$conn);
									$dbarray1 = mysql_fetch_array($result1);
									$Dutyid  = $dbarray1['Dutyid'];
									$Empid  = $dbarray1['Empid'];
									$From_Date  = $dbarray1['From_Date'];
									$To_Date  = $dbarray1['To_Date'];
									
									if($Dutyid !=""){
										$query2 = "select TeacherDuty from tbteachersduty where ID='$Dutyid'";
										$result2 = mysql_query($query2,$conn);
										$dbarray2 = mysql_fetch_array($result2);
										$TeacherDuty  = $dbarray2['TeacherDuty'];
?>
									  <tr>
										<td><div align="center"><?PHP echo $TeacherDuty; ?></div></td>
										<td><div align="center"><?PHP echo GET_EMP_NAME($Empid); ?></div></td>
										<td><div align="center"><?PHP echo Long_date($From_Date); ?></div></td>
										<td><div align="center"><?PHP echo Long_date($To_Date); ?></div></td>
									  </tr>
<?PHP
									}
									$n=$n+1;
								}
							}
						}
						$i=$i+1;
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
