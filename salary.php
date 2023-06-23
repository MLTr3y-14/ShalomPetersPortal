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
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Pay Roll";
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
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['st']))
	{	$rstart = $_POST['st'];
		$rend = $_POST['ed'];
	}
	if(isset($_POST['optType']))
	{	
		$optType = $_POST['optType'];
		if($optType =="All"){
			$lockEmp = "disabled='disabled'";
		}
	}
	if(isset($_POST['SalDetail_delete']))
	{
		$emp_id = $_POST['emp_id'];
		$_GET['emp_id'] = $_POST['emp_id'];
		$q = "Delete From salarydetail where EmpID = '$emp_id'";
		$result = mysql_query($q,$conn);
	}
if(isset($_POST['OptDOJ_Mth']))
	{
		$OptDOJ_Mth = $_POST['OptDOJ_Mth'];
	}
if(isset($_POST['OptDOJ_Yr']))
	{
		$OptDOJ_Yr = $_POST['OptDOJ_Yr'];
}
if(isset($_POST['SalDetail']))
	{
		$Total = $_POST['Total'];
		$emp_id = $_POST['emp_id'];
		//$_GET['emp_id'] = $_POST['emp_id'];
		$OptDOJ_Yr = $_POST['OptDOJ_Yr'];
		$OptDOJ_Mth = $_POST['OptDOJ_Mth'];
		$DOJ = $_POST['OptDOJ_Yr']."-".$_POST['OptDOJ_Mth'];
		$emp_Basic = $_POST['emp_Basic'];
		$NetSalary = $_POST['emp_Basic'];
		
		//$q = "Delete From salarydetail where EmpID = '$emp_id'";
		//$result = mysql_query($q,$conn);
		
		

		// $query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster";
	//$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	//$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	//$TotStu_In_Sch = $row4['numrows'];
	//$query3 = "select * from allowancesetup where CatID = '$catCode'";
									//$result3 = mysql_query($query3,$conn);
									//$num_rows = mysql_num_rows($result3);
	   $key ='false';
	   $counter1 = 0;
	   $query = "select ACode from salarydetail where EmpID='$emp_id' and SalDate = '$DOJ'";
		   $result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						$key ='true';
						while ($row = mysql_fetch_array($result)) 
						{  
						     $counter1++;
						    $AllowanceCode  = $row['ACode'];
							$ACode_array[$counter1] = $AllowanceCode;
						   }
					             
					//for($i=1;$i<=$Total;$i++){
							//$alCode1 = $ACode_array[$i];
			             //if(isset( $_POST['chkSalDetID'.$i]))
			               //  {
				          //$alCode = $_POST['chkSalDetID'.$i];
				            // $aType = $_POST['aType'.$i];
							/* if($alCode1 !==  $alCode){
							 $q = "Insert into salarydetail(EmpID,SalDate,ACode) Values ('$emp_id','$DOJ','$alCode')";
				             $result = mysql_query($q,$conn);
							 $errormsg = "<font color = blue size = 1>Employee Salary Updated Successfully.</font>";
							 }*/
							//         }
							// else{
								 //$q = "Delete From salarydetail where EmpID = '$emp_id' and Acode = '$alCode1' and SalDate = '$DOJ'";
								  $q = "Delete From salarydetail where EmpID = '$emp_id'and SalDate = '$DOJ'";
		                     $result1 = mysql_query($q,$conn);
							 $errormsg = "<font color = blue size = 1>Employee Salary Updated Successfully.</font>";
							 //}
					   // }
					}
					else{
						//$key ='true';
						 for($i=1;$i<=$Total;$i++){
							//$alCode1 = $ACode_array[$i];
			             if(isset( $_POST['chkSalDetID'.$i]))
			                 {
				          $alCode = $_POST['chkSalDetID'.$i];
				             $aType = $_POST['aType'.$i];
							        
						$q = "Insert into salarydetail(EmpID,SalDate,ACode) Values ('$emp_id','$DOJ','$alCode')";
				        $result = mysql_query($q,$conn);
						$errormsg = "<font color = blue size = 1>Employee Salary Added Successfully.</font>";
						                 }
						        }
					}
					
					$keycounter = 0;
					if($key == true){
						for($i=1;$i<=$Total;$i++){
							if(isset( $_POST['chkSalDetID'.$i]))
			                     {
				                     $alCode = $_POST['chkSalDetID'.$i];
					  $query = "select * from salarydetail where EmpID='$emp_id' and SalDate = '$DOJ' and ACode = $alCode";
		               $result = mysql_query($query,$conn);
					   $num_rows = mysql_num_rows($result);
					  if ($num_rows == 0) {
				         $q = "Insert into salarydetail(EmpID,SalDate,ACode) Values ('$emp_id','$DOJ','$alCode')";
				        $result = mysql_query($q,$conn);
						$errormsg = "<font color = blue size = 1>Employee Salary Added Successfully.</font>";
						                 }
								 }
						}
					}
							 
									 
					
					if($emp_id !=""){
			$query = "select ID,EmpName,EmpSal,catCode from tbemployeemasters where ID='$emp_id'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$EmpCode  = $dbarray['ID'];
			$EmpName  = $dbarray['EmpName'];
			$BasicSalary  = $dbarray['EmpSal'];
			$NetSalary  = $dbarray['EmpSal'];
			$catCode  = $dbarray['catCode'];
			$sTitle = "s For ".$EmpName;
			$NetSalary = $BasicSalary;
					}
				        
		}
	if(isset($_GET['emp_id']))
	{
		  
		//$OptDOJ_Yr = $_POST['OptDOJ_Yr'];
		//$OptDOJ_Mth = $_POST['OptDOJ_Mth'];
		//$DOJ = $_POST['OptDOJ_Yr']."-".$_POST['OptDOJ_Mth'];
		$emp_id = $_GET['emp_id'];
		$OptDOJ_Mth = $_GET['DOM'];
		$OptDOJ_Yr = $_GET['DOY'];
		if($emp_id !=""){
			$query = "select ID,EmpName,EmpSal,catCode from tbemployeemasters where ID='$emp_id'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$EmpCode  = $dbarray['ID'];
			$EmpName  = $dbarray['EmpName'];
			$BasicSalary  = $dbarray['EmpSal'];
			$NetSalary  = $dbarray['EmpSal'];
			$catCode  = $dbarray['catCode'];
			$sTitle = "s For ".$EmpName;
			$NetSalary = $BasicSalary;
			
			//$query2 = "select ACode from salarydetail where EmpID='$emp_id' and SalDate = $DOJ";
			
			//$arrDate2=explode ('-', $dbarray2['SalDate']);
			//$DOJ_Dy = $arrDate2[2];
			//$DOJ_Mth = $arrDate2[1];
			//$DOJ_Yr = $arrDate2[0];
		}
	}
	if(isset($_POST['OptMonth']))
	{
		$OptMonth = $_POST['OptMonth'];
	}
if(isset($_POST['OptYear']))
	{
		$OptYear = $_POST['OptYear'];
}
if(isset($_POST['SalGenerate']))
	{
		if(isset($_POST['optType']))
		{
			$optType = $_POST['optType'];
			$OptMonth = $_POST['OptMonth'];
			$OptYear = $_POST['OptYear'];
			$DOJ = $OptYear."-".$OptMonth;
			if ($_POST['optType'] =="All" ){
				$query = "select ID,EmpSal,catCode from tbemployeemasters";
			}elseif ($_POST['optType'] =="Category" ){
				$OptCat = $_POST['OptCat'];
				$query = "select ID,EmpSal,catCode from tbemployeemasters where catCode ='$OptCat'";
			}elseif ($_POST['optType'] =="Employee" ){
				$optEmp = $_POST['optEmp'];
				$query = "select ID,EmpSal,catCode from tbemployeemasters where ID ='$optEmp'";
			}
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) {
				while ($row = mysql_fetch_array($result)) 
				{
					$EmpCode = $row["ID"];
					$EmpSal = $row["EmpSal"];
					$catCode = $row["catCode"];
					
					$NetPay = 0;
					$TotalDeduction = 0;
					$query2 = "select * from allowancesetup where CatID = '$catCode' and aType = 'Deduction'";
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$SetupID = $row2["ID"];
							$aCal = $row2["aCal"];
							$Amount = $row2["Amount"];
							
							$query3 = "select * from salarydetail where EmpID='$EmpCode' And ACode = '$SetupID' And SalDate='$DOJ'";
							$result3 = mysql_query($query3,$conn);
							$num_rows3 = mysql_num_rows($result3);
							if($num_rows3 >0){
								if($aCal=="Amount"){
									$TotalDeduction = $TotalDeduction + $Amount;
								}elseif($aCal=="Percentage %"){
									$TotalDeduction = $TotalDeduction + $EmpSal / 100 * $Amount;
								}
							}
						}
					}
					$TotalEarning = 0;
					$query4 = "select * from allowancesetup where CatID = '$catCode' and aType = 'Earning'";
					$result4 = mysql_query($query4,$conn);
					$num_rows4 = mysql_num_rows($result4);
					if ($num_rows4 > 0 ) {
						while ($row4 = mysql_fetch_array($result4)) 
						{
							$SetupID = $row4["ID"];
							$aCal = $row4["aCal"];
							$Amount = $row4["Amount"];
							
							$query5 = "select * from salarydetail where EmpID='$EmpCode' And ACode = '$SetupID' And SalDate='$DOJ'";
							$result5 = mysql_query($query5,$conn);
							$num_rows5 = mysql_num_rows($result5);
							if($num_rows5 >0){
								if($aCal=="Amount"){
									$TotalEarning = $TotalEarning + $Amount;
								}elseif($aCal=="Percentage %"){
									$TotalEarning = $TotalEarning + $EmpSal / 100 * $Amount;
								}
							}
						}
					}
					$NetPay = $EmpSal + $TotalEarning - $TotalDeduction;
					
					$q2 = "Delete From salary where EmpId = '$EmpCode'";
					$result2 = mysql_query($q2,$conn);
				
					$q1 = "Insert into salary(EmpId,SMonth,SYear,BasicPay,GrossPay,totalDeductions,totalAdditions,NetPay) Values ('$EmpCode','$OptMonth','$OptYear','$EmpSal','$EmpSal','$TotalDeduction','$TotalEarning','$NetPay')";
					$result1 = mysql_query($q1,$conn);
					
					$errormsg = "<font color = blue size = 1>Generated Successfully.</font>";
				
				}
			}
		}
	}
if(isset($_POST['Gen_Dy']))
	{
		$Gen_Dy = $_POST['Gen_Dy'];
	}
if(isset($_POST['Gen_Mth']))
	{
		$Gen_Mth = $_POST['Gen_Mth'];
	}
if(isset($_POST['Gen_Yr']))
	{
		$Gen_Yr = $_POST['Gen_Yr'];
}
if(isset($_POST['SalDetails']))
	{
		if(isset($_POST['optType']))
		{
			$optType = $_POST['optType'];
			$OptMonth = $_POST['OptMonth'];
			$OptYear = $_POST['OptYear'];
			$DOJ = $OptYear."-".$OptMonth;
			$query2 = "select * from salary where SMonth='$OptMonth' and SYear='$OptYear'";
			$result2 = mysql_query($query2,$conn);
			$num_rows2 = mysql_num_rows($result2);
			if($num_rows2 == 0){
				$errormsg = "<font color = red size = 1>Salary not yet generated</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{			
				if ($_POST['optType'] =="All" ){
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=payroll_rpt.php?pg=SalaryDetails&mth=$OptMonth&yr=$OptYear&ty=$optType\">";
					exit;
				}elseif ($_POST['optType'] =="Employee" ){
					$optEmp = $_POST['optEmp'];
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=payroll_rpt.php?pg=SalaryDetails&mth=$OptMonth&yr=$OptYear&ty=$optType&optEmp=$optEmp\">";
					exit;
				}
			}
			
		}else{
			$errormsg = "<font color = red size = 1>Select option to view salary details</font>";
			$PageHasError = 1;
		}
	}
	if(isset($_POST['SubBankLetter']))
	{
		if(isset($_POST['optType']))
		{
			$optType = $_POST['optType'];
			$OptMonth = $_POST['OptMonth'];
			$OptYear = $_POST['OptYear'];
			$query2 = "select * from salary where SMonth='$OptMonth' and SYear='$OptYear'";
			$result2 = mysql_query($query2,$conn);
			$num_rows2 = mysql_num_rows($result2);
			if($num_rows2 == 0){
				$errormsg = "<font color = red size = 1>Salary not yet generated</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				if ($_POST['optType'] =="All" ){
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=payroll_rpt.php?pg=BankLetter&mth=$OptMonth&yr=$OptYear&ty=$optType\">";
					exit;
				}elseif ($_POST['optType'] =="Employee" ){
					$optEmp = $_POST['optEmp'];
					echo "<meta http-equiv=\"Refresh\" content=\"0;url=payroll_rpt.php?pg=BankLetter&mth=$OptMonth&yr=$OptYear&ty=$optType&optEmp=$optEmp\">";
					exit;
				}
			}
		}else{
			$errormsg = "<font color = red size = 1>Select option to view salary details</font>";
			$PageHasError = 1;
		}
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
.style21 {font-weight: bold}
.style22 {
	color: #FFFFFF;
	font-weight: bold;
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
			  <TD width="856" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?><?PHP echo $sTitle; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Salary Detail") {
?>
				<?PHP echo $errormsg; 
				//.$DOJ." ".$emp_id.$alCode.$ACode_array[1] ?>
				<form name="form1" method="post" action="salary.php?subpg=Salary Detail">
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
					
					
					<!--
					function WebForm_OnSubmit() {
					if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
					return true;
					}
					// -->
                  </script>
                <script type="text/javascript">
				      function printpage(){
						  window.print();
					  }
				</script>
				
                <TABLE width="96%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="30%" valign="top"  align="left">
					  	<p align="center">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="20" value="<?PHP echo $Search_Key; ?>">
							<input name="SubmitSearch" type="submit" id="Search" value="Go">
                        </p>
					  	<table width="209" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr bgcolor="#999999">
                            <td width="197"><div align="center" class="style22">Employee Name</div></td>
                          </tr>
<?PHP                         if(!(isset($OptDOJ_Mth)))
                                        {
                                           $OptDOJ_Mth = date('m'); 
										   if($OptDOJ_Mth == 01){
										    $OptDOJ_Mth = 1;}
										     elseif($OptDOJ_Mth == 02){
										     $OptDOJ_Mth = 2; }
											 elseif($OptDOJ_Mth == 03){
										     $OptDOJ_Mth = 3; }
											 elseif($OptDOJ_Mth == 04){
										     $OptDOJ_Mth = 4; }
											 elseif($OptDOJ_Mth == 05){
										     $OptDOJ_Mth = 5; }
											 elseif($OptDOJ_Mth == 06){
										     $OptDOJ_Mth = 6; }
											 elseif($OptDOJ_Mth == 07){
										     $OptDOJ_Mth = 7; }
											 elseif($OptDOJ_Mth == 08){
										     $OptDOJ_Mth = 8; }
											 elseif($OptDOJ_Mth == 09){
										     $OptDOJ_Mth = 9; }
										}
                                      if(!(isset($OptDOJ_Yr)))
									  {
									     $OptDOJ_Yr = date('Y');       
										 }
                              $DOJ = $OptDOJ_Yr."-".$OptDOJ_Mth;
							$counter_emp = 0;
							$query2 = "select ID,EmpName from tbemployeemasters";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_emp = $rstart;
							}else{
								$counter_emp = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbemployeemasters where INSTR(EmpName,'$Search_Key') order by EmpName";
							}else{
								$query3 = "select * from tbemployeemasters LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "No record found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_al = $counter_al+1;
									$counter = $counter+1;
									$EmpID = $row["ID"];
									$EmpName = $row["EmpName"];
?>
									  <tr bgcolor="#FFFFFF">
										<td style="border-bottom:#CCCCCC 1px solid;"><div align="center"><a href="salary.php?subpg=Salary Detail&emp_id=<?PHP echo $EmpID; ?>&st=<?PHP echo $rstart ?>&ed=<?PHP echo $rend; ?>&DOM=<?PHP echo $OptDOJ_Mth ?>&DOY=<?PHP echo $OptDOJ_Yr ?>"><?PHP echo $EmpName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="salary.php?subpg=Salary Detail&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;</a> |&nbsp;&nbsp;<a href="salary.php?subpg=Salary Detail&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="salary.php?subpg=Salary Detail&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="3%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="67%" valign="top"  align="left">
					  <TABLE width="100%">
                        <TBODY>
                          <TR>
                            <TD width="16%"  align="left">Emp Code</TD>
                            <TD width="24%"  align="left" valign="top"><input name="EmpCode" type="text" size="20" value="<?PHP echo $EmpCode; ?>" disabled="disabled"></TD>
                            <TD width="22%" valign="middle"  align="right">Today's Date</TD>
                            <TD width="38%"  align="left" valign="top"> 
<?PHP
							$DOJ_Dy1 = date('d');
							$DOJ_Mth1 = date('m');
							$DOJ_Yr1 = date('Y');
?>
						  <input name="DOJ_Dy1" type="text" size="2"  value="<?PHP echo $DOJ_Dy1; ?>" disabled ONFOCUS="clearDefault(this)">
							/
							<input name="DOJ_Mth1" type="text" size="2"  value="<?PHP echo $DOJ_Mth1; ?>" disabled ONFOCUS="clearDefault(this)">
							/
							<input name="DOJ_Yr1" type="text" size="2"  value="<?PHP echo $DOJ_Yr1; ?>" disabled ONFOCUS="clearDefault(this)"><br> 
							Format= DD / MM / YYYY</TD></TR>
                            <TR><TD></TD><TD></TD><TD>Select Salary Month</TD><TD>
                            <select name="OptDOJ_Mth" onChange="javascript:setTimeout('__doPostBack(\'OptDOJ_Mth\',\'\')', 0)">
                                 <option value="0" selected="selected">Month</option>
<?PHP
									$Cur_Mth = date('m');
									for($i=1; $i<=12; $i++){
										if($OptDOJ_Mth == $i){
											echo "<option value=$i selected=selected>".Get_Month_Name($i)."</option>";
											$Cur_Mth = 0;
										}elseif($i == $Cur_Mth){
											echo "<option value=$i selected=selected>".Get_Month_Name($i)."</option>";
										}else{
											echo "<option value=$i>".Get_Month_Name($i)."</option>";
										}
									}
							
?>
                                </select>
                                 <select name="OptDOJ_Yr" onChange="javascript:setTimeout('__doPostBack(\'OptDOJ_Yr\',\'\')', 0)">
<?PHP
									$CurYear = date('Y');
									$YearKey = 0;
									for($i=2009; $i<=$CurYear; $i++){
										if($OptDOJ_Yr == $i){
											echo "<option value=$i selected=selected>$i</option>";
											$YearKey++;
										}elseif($i == $CurYear && $YearKey == 0){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
                           </select>
                            </TD>
                          </TR>
                          <TR>
                            <TD width="16%"  align="left">Basic Salary</TD>
                            <TD colspan="3"  align="left" valign="top">
								<input name="BasicSalary" type="text" size="20" value="<?PHP echo $BasicSalary; ?>" disabled="disabled"></TD>
                          </TR>
						  <TR>
                            <TD width="16%"  align="left">&nbsp;</TD>
                            <TD colspan="3"  align="left" valign="top">&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD colspan="4" align="left">
								<table width="100%" border="0" align="left" cellpadding="3" cellspacing="3">
                                <tr>
                                  <td width="33" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                                  <td width="83" bgcolor="#F4F4F4"><div align="center"><strong>Allowance.</strong></div></td>
                                  <td width="69" bgcolor="#F4F4F4"><div align="center"><strong>Type</strong></div></td>
                                  <td width="82" bgcolor="#F4F4F4"><div align="center"><strong>Formula</strong></div></td>
                                  <td width="85" bgcolor="#F4F4F4"><div align="center"><strong>Amount</strong></div></td>
                                  <td width="77" bgcolor="#F4F4F4"><div align="center"><strong>Net</strong></div></td>
                                </tr>
<?PHP                                 /*if($OptDOJ_Mth == date('m'))
                                        {
                                           $OptDOJ_Mth = date('m'); 
										   }
                                      if($OptDOJ_Yr == date('Y'))
									  {
									     $OptDOJ_Yr = date('Y');       
										 }*/
		                              //$DOJ_Dy = $_POST['DOJ_Dy'];
									  //$OptDOJ_Yr = date('Y');
									  //$OptDOJ_Mth = 6;
		                             
									$counter = 0;
									$query3 = "select * from allowancesetup where CatID = '$catCode'";
									$result3 = mysql_query($query3,$conn);
									$num_rows = mysql_num_rows($result3);
									if ($num_rows <= 0 ) {
										echo "";
									}
									else 
									{
										while ($row = mysql_fetch_array($result3)) 
										{
											$counter = $counter+1;
											$SetupID = $row["ID"];
											$aID = $row["aID"];
											$aType = $row["aType"];
											$aCal = $row["aCal"];
											$Amount = $row["Amount"];
											$NetAmount = 0;
											$query = "select aName from allowancemaster where ID='$aID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$aName  = $dbarray['aName'];
											if($aCal=="Amount"){
												$NetAmount = $Amount;
											}elseif($aCal=="Percentage %"){
												$NetAmount = $BasicSalary / 100 * $Amount;
											}
											if($aType=="Deduction"){
												$NetAmount = "-".$NetAmount;
											}
											$isTic = "";
										$query2 = "select * from salarydetail where EmpID='$emp_id' And ACode = '$SetupID' and SalDate='$DOJ'";
											$result2 = mysql_query($query2,$conn);
											$num_rows2 = mysql_num_rows($result2);
											if($num_rows2 >0){
												$isTic  = "checked='checked'";
												$NetSalary = $NetSalary + $NetAmount;
											}
?>
											<tr bgcolor="#CEDAE8">
											  <td><div align="center">
												  <input name="chkSalDetID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SetupID; ?>" <?PHP echo $isTic; ?> >
												  <input type="hidden" name="aType<?PHP echo $counter; ?>" value="<?PHP echo $aType; ?>">
											  </div></td>
											  <td><div align="center"><?PHP echo $aName; ?></div></td>
											  <td><div align="center"><?PHP echo $aType; ?></div></td>
											  <td><div align="center"><?PHP echo $aCal; ?></div></td>
											  <td><div align="center"><?PHP echo number_format($Amount,2); ?></div></td>
											  <td><div align="center"><?PHP echo number_format($NetAmount,2); ?>
											  <input type="hidden" name="NetAmount<?PHP echo $counter; ?>" value="<?PHP echo $NetAmount; ?>"></div></td>
											</tr>
<?PHP
										 }
									 }
?>	
									<tr>
										<td colspan="4" align="right">&nbsp;</TD>
										<td colspan="2"  align="left" valign="top">&nbsp;</TD>
									 </tr>
									<tr>
										<td colspan="4" align="right"><strong>Net Salary</strong></TD>
										<td colspan="2"  align="left" valign="top">
											<input name="NetSalary" type="text" size="20" value="<?PHP echo number_format($NetSalary,2); ?>" style=" background:#FF9966; text-align:right"></TD>
									  </tr>
                              </table>
                            </TD>
                          </TR>
						  <TR>
                        </TBODY>
                      </TABLE></TD>
					 </TR>
					 <TR>
					 	<TD colspan="3">
						  <div align="center">
						  	 <input type="hidden" name="Total" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="emp_id" value="<?PHP echo $emp_id; ?>">
                             <input type="hidden" name="st" value="<?PHP echo $rstart; ?>">
							 <input type="hidden" name="ed" value="<?PHP echo $rend; ?>">
							 <input type="hidden" name="emp_Basic" value="<?PHP echo $BasicSalary; ?>">
							 <input name="SalDetail" type="submit" id="amaster" value="Generate Net Salary">
							 <input name="SalDetail_delete" type="submit" id="amaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
							 <input type="reset" name="Reset" value= "<?PHP echo $DOJ; ?>">
                             <input type="button" value="Print Salary Details" onClick="printpage()">
						   </div>
						  </TD>
					  </TR>
					 </TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Generate Salary") {
?>
				<?PHP echo $errormsg.$DOJ; ?>
				<form name="form1" method="post" action="salary.php?subpg=Generate Salary">
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
				<TABLE width="88%">
					<TBODY>
					 <TR>
					  <TD width="20%" valign="top"  align="left">
<?PHP
							if ($optType == "All") {
?>
								<input type="radio" name="optType" value="All" onClick="javascript:setTimeout('__doPostBack(\'optType\',\'\')', 0)" checked="checked"> All Employees
<?PHP
							}else{
?>
								<input type="radio" name="optType" value="All" onClick="javascript:setTimeout('__doPostBack(\'optType\',\'\')', 0)"> All Employees
<?PHP
							}
?>
					  </TD>
					  <TD width="31%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="17%" valign="top"  align="left"><P>Month &amp; Year</P></TD>
					  <TD width="32%" valign="top"  align="left"><P>
							<select name="OptMonth" onChange="javascript:setTimeout('__doPostBack(\'OptMonth\',\'\')', 0)">
							<option value="0" selected="selected">Month</option>
<?PHP
									$Cur_Mth = date('m');
									for($i=1; $i<=12; $i++){
										if($OptMonth == $i){
											echo "<option value=$i selected=selected>".Get_Month_Name($i)."</option>";
											$Cur_Mth = 0;
										}elseif($i == $Cur_Mth){
											echo "<option value=$i selected=selected>".Get_Month_Name($i)."</option>";
										}else{
											echo "<option value=$i>".Get_Month_Name($i)."</option>";
										}
									}
									echo "<option value=13>All Month</option>";
?>
							</select>
							<select name="OptYear" onChange="javascript:setTimeout('__doPostBack(\'OptYear\',\'\')', 0)">
								<option value="0" selected="selected">Year</option>
<?PHP
									$CurYear = date('Y');
									$YearKey = 0;
									for($i=2009; $i<=$CurYear; $i++){
										if($OptYear == $i){
											echo "<option value=$i selected=selected>$i</option>";
											$YearKey++;
										}elseif($i == $CurYear && $YearKey == 0){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
							</select>
                        </P></TD>
					 </TR>
					 <TR>
					  <TD width="20%" valign="top"  align="left"><p>
<?PHP
							if ($optType == "Employee") {
?>
								<input type="radio" name="optType" value="Employee" onClick="javascript:setTimeout('__doPostBack(\'optType\',\'\')', 0)" checked="checked"> Employee Wise
<?PHP
							}else{
?>
								<input type="radio" name="optType" value="Employee" onClick="javascript:setTimeout('__doPostBack(\'optType\',\'\')', 0)"> Employee Wise
<?PHP
							}
?>
					   </p></TD>
					  <TD width="31%" valign="top"  align="left"><P>
					  <select name="optEmp" <?PHP echo $lockEmp; ?>>
						<option value="0" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,EmpName from tbemployeemasters order by EmpName";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$EmpID = $row["ID"];
									$EmpName = $row["EmpName"];
									if($optEmp =="$EmpID"){
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
                      </select></P></TD>
					  <TD width="17%" valign="top"  align="left"><P>Generation Date </P></TD>
					  <TD width="32%" valign="top"  align="left"><P>
                        
                        <select name="Gen_Dy" onChange="javascript:setTimeout('__doPostBack(\'Gen_Dy\',\'\')', 0)">
							<option value="0" selected="selected">Day</option>
							
<?PHP
							$Cur_Dy = date('d');
							echo $Cur_Dy;
							for($i=1; $i<=31; $i++){
								if($Gen_Dy == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Cur_Dy = 0;
								}elseif($i == $Cur_Dy){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                        </select>
						<select name="Gen_Mth" onChange="javascript:setTimeout('__doPostBack(\'Gen_Mth\',\'\')', 0)">
							<option value="0" selected="selected">Month</option>
<?PHP
							$Cur_Mth = date('m');
							for($i=1; $i<=12; $i++){
								if($Gen_Mth == $i){
									echo "<option value=$i selected=selected>".Get_Month_Name($i)."</option>";
									$Cur_Mth = 0;
								}elseif($i == $Cur_Mth){
									echo "<option value=$i selected=selected>".Get_Month_Name($i)."</option>";
								}else{
									echo "<option value=$i>".Get_Month_Name($i)."</option>";
								}
							}
?>
						</select>
						<select name="Gen_Yr" onChange="javascript:setTimeout('__doPostBack(\'Gen_Yr\',\'\')', 0)">
							<option value="0" selected="selected">Year</option>
<?PHP
						$CurYear = date('Y');
						$YearKey = 0;
									for($i=2009; $i<=$CurYear; $i++){
										if($Gen_Yr == $i){
											echo "<option value=$i selected=selected>$i</option>";
											$YearKey++;
										}elseif($i == $CurYear && $YearKey == 0){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
</select>
                        </P></TD>
					 </TR>
					 <TR>
					 	<TD colspan="4">&nbsp;
						  </TD>
					  </TR>
					  <TR>
					 	<TD colspan="4">
						  <div align="center">
						  	 <input type="hidden" name="Total" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="emp_id" value="<?PHP echo $emp_id; ?>">
							 <input type="hidden" name="emp_Basic" value="<?PHP echo $BasicSalary; ?>">
							 <input name="SalGenerate" type="submit" id="SalGenerate" value="Generate">
							 <input name="SalDetails" type="submit" id="SalDetails" value="Details">
							 <input name="SubBankLetter" type="submit" id="SubBankLetter" value="Bank Letter">
						   </div>
						  </TD>
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
