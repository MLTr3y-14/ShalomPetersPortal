<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	session_start();
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Utilities";
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
		$audit=update_Monitory('Login','Teacher',$Page);
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
		$audit=update_Monitory('Login','Administrators',$Page);
	}
	$PageHasError = 0;
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_GET['Optsrc']))
	{
		$_POST['OptSearch'] = $_GET['Optsrc'];
		$OptSearch = $_GET['Optsrc'];
		$OptClass = $_GET['clss'];
		$StuName = $_GET['name'];
		$_POST['StuName'] = $_GET['name'];
	}
	if(isset($_POST['optDepartment']))
	{
		$optDepartment = $_POST['optDepartment'];
		$OptDesign = $_POST['OptDesign'];
		$OptCat = $_POST['OptCat'];
		$OptSearch = $_POST['OptSearch'];
	}
	if(isset($_POST['GetEmployee']))
	{	
		$optDepartment = $_POST['optDepartment'];
		$OptDesign = $_POST['OptDesign'];
		$OptCat = $_POST['OptCat'];
		$OptSearch = $_POST['OptSearch'];
	}
	if(isset($_POST['OptEmp']))
	{	
		$OptEmp = $_POST['OptEmp'];
		$optDepartment = $_POST['optDepartment'];
		$OptDesign = $_POST['OptDesign'];
		$OptCat = $_POST['OptCat'];
		$OptSearch = $_POST['OptSearch'];
		
		$query = "select EmpName from tbemployeemasters where ID='$OptEmp'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmploName  = strtoupper($dbarray['EmpName']);
	}
	if(isset($_POST['SubmitRemark']))
	{	
		$RemarkID = $_POST['rmkID'];
		$OptEmp = $_POST['OptEmp'];
		$RmkDate = $_POST['RmkDate'];
		$RmkDate = DB_date($RmkDate);
		$Rmark = $_POST['Rmark'];
		//$Activeterm
		
		if(!$_POST['OptEmp']){
			$errormsg = "<font color = red size = 1>Select Employee.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['RmkDate']){
			$errormsg = "<font color = red size = 1>Date is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['Rmark']){
			$errormsg = "<font color = red size = 1>Remark is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['SubmitRemark'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbremark where EmpID='$OptEmp' and date_of_remark`='$RmkDate' and Remark='$Rmark' And Term ='$Activeterm'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The details you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbremark(EmpID,date_of_remark,Remark,Term) Values ('$OptEmp','$RmkDate','$Rmark','$Activeterm')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					$OptEmp= "";
					$RmkDate= "";
					$Rmark= "";
				}
			}elseif ($_POST['SubmitRemark'] =="Update"){
				$q = "update tbremark set EmpID = '$OptEmp',date_of_remark = '$RmkDate',Remark = '$Rmark' where ID = '$RemarkID'";

				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				$OptEmp= "";
				$RmkDate= "";
				$Rmark= "";
			}
			$OptEmp = $_POST['OptEmp'];
		$optDepartment = $_POST['optDepartment'];
		$OptDesign = $_POST['OptDesign'];
		$OptCat = $_POST['OptCat'];
		$OptSearch = $_POST['OptSearch'];
		}
	}
	if(isset($_POST['DelSubmitRemark']))
	{
		$Total = $_POST['Total'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkRmk'.$i]))
			{
				$chkRmk = $_POST['chkRmk'.$i];
				$q = "Delete From tbremark where ID = '$chkRmk'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_GET['rmk_id']))
	{	
		$RemarkID = $_GET['rmk_id'];
		$OptEmp = $_GET['emp_id'];
		$optDepartment = $_GET['dept'];
		$OptDesign = $_GET['desg'];
		$OptCat = $_GET['cat'];
		$OptSearch = $_GET['srch'];
		
		
		$query = "select * from tbremark where ID='$RemarkID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpNo  = $dbarray['EmpID'];
		$RmkDate  = $dbarray['date_of_remark'];
		$RmkDate = User_date($RmkDate);
		$Rmark  = $dbarray['Remark'];
		
		$query = "select EmpName from tbemployeemasters where ID='$EmpNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmploName  = strtoupper($dbarray['EmpName']);
		
		
		$_POST['OptSearch'] = $_GET['srch'];
		$_POST['OptEmp'] = $_GET['emp_id'];
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager</TITLE>
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
.style24 {color: #FFFFFF}
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
			  <TD width="751" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>

<?PHP
		if ($SubPage == "Employee Remarks") {
?>
			<p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="remark.php?subpg=Employee Remarks">
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
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="50%" valign="top"  align="left">
					  		<table width="350" cellpadding="4">
								<tr>
									<td width="97">
                                      <div align="left">
<?PHP
									if($OptSearch =="All")
									{
										echo "<input type='radio' name='OptSearch' value='All' checked='checked'/>All Employee";
									}else{
										echo "<input type='radio' name='OptSearch' value='All'/>All Employee";
									}
?>
                                      </div></td>
									<td width="229">&nbsp;</td>
								</tr>
								<tr>
									<td width="97">
                                      <div align="left">
<?PHP
									if($OptSearch =="Dept")
									{
										echo "<input type='radio' name='OptSearch' value='Dept' checked='checked'/>Department";
										echo $optDepartment;
									}else{
										echo "<input type='radio' name='OptSearch' value='Dept'/>Department";
									}
?>
                                      </div></td>
									<td width="229">
									<select name="optDepartment" onChange="javascript:setTimeout('__doPostBack(\'optDepartment\',\'\')', 0)">
                                      <option value="" selected="selected">Select</option>
<?PHP
										$query = "select ID,DeptName from tbdepartments order by DeptName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "No department Found.";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$DeptID = $row["ID"];
												$DeptName = $row["DeptName"];
												if($optDepartment =="$DeptID"){
?>
                                      				<option value="<?PHP echo $DeptID; ?>" selected="selected"><?PHP echo $DeptName; ?></option>
<?PHP
												}else{
?>
                                      				<option value="<?PHP echo $DeptID; ?>"><?PHP echo $DeptName; ?></option>
<?PHP
												}
											}
										}
?>
                                    </select></td>
								</tr>
								<tr>
									<td width="97">
                                      <div align="left">
<?PHP
									if($OptSearch =="Design")
									{
										echo "<input type='radio' name='OptSearch' value='Design' checked='checked'/>Designation";
									}else{
										echo "<input type='radio' name='OptSearch' value='Design'/>Designation";
									}
?>
                                      </div></td>
									<td width="229">
									<select name="OptDesign">
                                      <option value="0" selected="selected">&nbsp;</option>
<?PHP
							
										$query = "select ID,DesignName from tbdesignations where DeptId = '$optDepartment' order by DesignName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows > 0 ) {
											while ($row = mysql_fetch_array($result)) 
											{
												$DesignID = $row["ID"];
												$DesignName = $row["DesignName"];
												if($OptDesign =="$DesignID"){
?>
												  <option value="<?PHP echo $DesignID; ?>" selected="selected"><?PHP echo $DesignName; ?></option>
<?PHP
												}else{
?>
												  <option value="<?PHP echo $DesignID; ?>"><?PHP echo $DesignName; ?></option>
<?PHP
												}
											}
										}
?>
                                    </select></td>
								</tr>
								<tr>
									<td width="97">
                                      <div align="left">
<?PHP
									if($OptSearch =="Cat")
									{
										echo "<input type='radio' name='OptSearch' value='Cat' checked='checked'/>Category";
									}else{
										echo "<input type='radio' name='OptSearch' value='Cat'/>Category";
									}
?>
                                      </div></td>
									<td width="229">
								      <select name="OptCat">
                                        <option value="0" selected="selected">&nbsp;</option>
<?PHP
										$query = "select ID,cName from tbcategorymaster order by cName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$CatID = $row["ID"];
												$cName = $row["cName"];
												if($OptCat =="$CatID"){
?>
													<option value="<?PHP echo $CatID; ?>" selected="selected"><?PHP echo $cName; ?></option>
<?PHP
												}else{
?>
													<option value="<?PHP echo $CatID; ?>"><?PHP echo $cName; ?></option>
<?PHP
												}
											}
										}
?>
                                      </select>
								      <input type="submit" name="GetEmployee" value="Go">
								   </td>
								</tr>
							</table>
					        </TD>
					  <TD width="50%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  <select name="OptEmp" size="10" multiple style="width:350px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptSubj\',\'\')', 0)">
<?PHP
								$counter = 0;
								if(isset($_POST['OptSearch']))
								{
									if($OptSearch=='All'){
										$query = "select ID,EmpName from tbemployeemasters order by EmpName";
									}elseif($OptSearch=='Dept'){
										$query = "select ID,EmpName from tbemployeemasters where EmpDept = '$optDepartment' order by EmpName";
									}elseif($OptSearch=='Design'){
										$query = "select ID,EmpName from tbemployeemasters where EmpDesig = '$OptDesign' order by EmpName";
									}elseif($OptSearch=='Cat'){
										$query = "select ID,EmpName from tbemployeemasters where catCode = '$OptCat' order by EmpName";
									}else{
										$query = "select ID,EmpName from tbemployeemasters order by EmpName";
									}
								}else{
									$query = "select ID,EmpName from tbemployeemasters order by EmpName";
								}
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$EmpID = $row["ID"];
										$EmpName = $row["EmpName"];
										if($counter <= 9){
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}else{
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										
										if($OptEmp =="$EmpID"){
?>
                        					<option value="<?PHP echo $EmpID; ?>" selected="selected"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $EmpName; ?></option>
<?PHP
										}else{
?>
                       						<option value="<?PHP echo $EmpID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $EmpName; ?></option>
<?PHP
										}
									}
								}
?>
                        <option> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                      </select></TD>
					</TR>
					<TR>
						<TD>&nbsp;</TD>
						<TD>&nbsp;</TD>
					</TR>
					<TR>
						<TD>Date of Remark 
						  <input class="fil_ariane_passif" onClick="ds_sh(this);" name="RmkDate" readonly="readonly" style="cursor: text" value="<?PHP echo $RmkDate; ?>"/> 
						  Employee ID
						  <input name="EmpNo" type="text" size="5" value="<?PHP echo $OptEmp; ?>"></TD>
						<TD>Employee Name 						  
						  <input name="EmploName" type="text" size="45" value="<?PHP echo $EmploName; ?>"></TD>
					</TR>
					<TR>
						<TD colspan="2" valign="middle">Remark&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="Rmark" cols="75" rows="5"><?PHP echo $Rmark; ?></textarea></TD>
					</TR>
					<TR>
							<TD colspan="2"><div align="center"><p>
							  <input type="hidden" name="rmkID" value="<?PHP echo $RemarkID; ?>">
							  <input type="hidden" name="EmpNo" value="<?PHP echo $OptEmp; ?>">
							  <input name="SubmitRemark" type="submit" id="SubmitRemark" value="Create New">
							  <input name="SubmitRemark" type="submit" id="SubmitRemark" value="Update">
						</p>
							</div></TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left"><table width="143%" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="46" bgcolor="#666666"><div align="center" class="style24"><strong>Tick</strong></div></td>
								<td width="133" bgcolor="#666666"><div align="center" class="style24"><strong>Date of remark  </strong></div></td>
								<td width="309" bgcolor="#666666"><div align="center" class="style24"><strong> Remark </strong></div></td>
								<td width="182" bgcolor="#666666"><div align="center" class="style24"><strong> Department </strong></div></td>
							  </tr>
<?PHP
								$counter = 0;
								$query3 = "select * from tbremark where EmpID ='$OptEmp' and Term = '$Activeterm'";
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter = $counter+1;
										$rmID = $row["ID"];
										$EmplID = $row["EmpID"];
										$date_of_remark = $row["date_of_remark"];
										$date_of_remark = User_date($date_of_remark);
										$Remark = $row["Remark"];
										
										$query = "select EmpDept from tbemployeemasters where ID='$EmplID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$EmpDept  = $dbarray['EmpDept'];
										
										$query = "select DeptName from tbdepartments where ID='$EmpDept'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$DeptName  = $dbarray['DeptName'];
?>								  
										  <tr "#FFFFFF">
											<td>
											   <div align="center">
												 <input name="chkRmk<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $rmID; ?>"></div></td>
											<td><div align="center"><a href="remark.php?subpg=Employee Remarks&rmk_id=<?PHP echo $rmID; ?>&emp_id=<?PHP echo $OptEmp; ?>&dept=<?PHP echo $optDepartment; ?>&desg=<?PHP echo $OptDesign; ?>&cat=<?PHP echo $OptCat; ?>&srch=<?PHP echo $OptSearch; ?>"><?PHP echo $date_of_remark; ?></a></div></td>
											<td><div align="center"><a href="remark.php?subpg=Employee Remarks&rmk_id=<?PHP echo $rmID; ?>&emp_id=<?PHP echo $OptEmp; ?>&dept=<?PHP echo $optDepartment; ?>&desg=<?PHP echo $OptDesign; ?>&cat=<?PHP echo $OptCat; ?>&srch=<?PHP echo $OptSearch; ?>"><?PHP echo $Remark; ?></a></div></td>
											<td><div align="center"><a href="remark.php?subpg=Employee Remarks&rmk_id=<?PHP echo $rmID; ?>&emp_id=<?PHP echo $OptEmp; ?>&dept=<?PHP echo $optDepartment; ?>&desg=<?PHP echo $OptDesign; ?>&cat=<?PHP echo $OptCat; ?>&srch=<?PHP echo $OptSearch; ?>"><?PHP echo $DeptName; ?></a></div></td>
										  </tr>
<?PHP
									}
								}
?>
							</table>
							<input type="hidden" name="TotalStudent" value="<?PHP echo $counter_Stud; ?>">
					    </TD>
					</TR>
					<TR>
							<TD colspan="2"><div align="center"><p>
							  <input type="hidden" name="Total" value="<?PHP echo $counter; ?>">
							 <input name="DelSubmitRemark" type="submit" id="DelSubmitRemark" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						</p>
							</div></TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}
?>
              </TD>
			</TR>
		</TBODY>
		</TABLE>
		<BR></TD>
	  </TR>
	 </TABLE>
      </TD></TR></TBODY></TABLE>
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
