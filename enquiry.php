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
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Admission";
	$audit=update_Monitory('Login','Administrator',$Page);
	$PageHasError = 0;
	if(isset($_GET['filename']))
	{
		$EmpFilename = $_GET['filename'];
	}else{
		$EmpFilename = "empty_r2_c2.jpg";
	}
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	if($SubPage == "New_Enquiry")
	{
		$_POST['SubmitNew'] = "New Enquiry";
	}
	if(isset($_POST['SubmitNew']))
	{
		$SubPage = "New Enquiry";
		$dDay = date('d');
		$dMonth = date('m');
		$dYear = date('Y');
		$StudDOB_day = "dd";
		$StudDOB_month= "mm";
		$StudDOB_year = "yyyy";
		$PaIncome = "0.00";
	}
	if(isset($_POST['OpenOccupation']))
	{
		$SubPage = "Occupation";
	}
	if(isset($_POST['EmpMaster']))
	{
		$backpg = "Enquiry";
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=payroll.php?subpg=Employee%20Master&bk=$backpg\">";
		exit;
	}
	if(isset($_POST['ClassMaster']))
	{
	    $backpg = "<a href=welcome.php?pg=Master%20Setup&mod=admin>Home</a> &gt; <a href=enquiry.php?subpg=Enquiry>Enquiry</a> &gt; <a href=enquiry.php?subpg=New_Enquiry>New Equiry</a> &gt; Class Master";
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=mas_setup2.php?subpg=Class%20Master&bk=$backpg\">";
		exit;
	}
	if(isset($_POST['Occmaster']))
	{
		$PageHasError = 0;
		$OccID = $_POST['SelOccID'];
		$OccName = $_POST['OccName'];
		
		if(!$_POST['OccName']){
			$errormsg = "<font color = red size = 1>Occupation Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Occmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbfatheroccupation where Frocc = '$OccName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Occupation you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbfatheroccupation(Frocc) Values ('$OccName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$OccName = "";
				}
			}elseif ($_POST['Occmaster'] =="Update"){
				$q = "update tbfatheroccupation set Frocc = '$OccName' where ID = '$OccID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$OccID = "";
				$OccName = "";
			}
		}
	}
	if(isset($_GET['occ_id']))
	{
		$OccID = $_GET['occ_id'];
		$query = "select * from tbfatheroccupation where ID='$OccID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OccName  = $dbarray['Frocc'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['Occmaster_delete']))
	{
		$Total = $_POST['TotalOcc'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkoccID'.$i]))
			{
				$occIDs = $_POST['chkoccID'.$i];
				$q = "Delete From tbfatheroccupation where ID = '$occIDs'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SaveEnquiry']))
	{
		$PageHasError = 0;
		$OptEnqType = $_POST['OptEnqType'];
		$EnqID = $_POST['SelEnqID'];
		$dDay = $_POST['dDay'];
		$dMonth = $_POST['dMonth'];
		$dYear = $_POST['dYear'];
		$EnqDate = $dYear."-".$dMonth."-".$dDay;
		if(!is_numeric($dDay)){
			$errormsg = "<font color = red size = 1>Invalid Date (Day).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($dMonth)){
			$errormsg = "<font color = red size = 1>Invalid Date (Month).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($dYear)){
			$errormsg = "<font color = red size = 1>Invalid Date (Year).</font>";
			$PageHasError = 1;
		}
		$OptClass = $_POST['OptClass'];
		$OptEmp = $_POST['OptEmp'];
		$EnqRemarks = $_POST['EnqRemarks'];
		$StudName = $_POST['StudName'];
		$StudAdd = $_POST['StudAdd'];
		$StudDOB_day = $_POST['StudDOB_day'];
		$StudDOB_month = $_POST['StudDOB_month'];
		$StudDOB_year = $_POST['StudDOB_year'];
		$StudDOB = $StudDOB_year."-".$StudDOB_month."-".$StudDOB_day;
		if(!is_numeric($StudDOB_day)){
			$errormsg = "<font color = red size = 1>Invalid Date of Birth (Day)</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($StudDOB_month)){
			$errormsg = "<font color = red size = 1>Invalid Date of Birth (Month)</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($StudDOB_year)){
			$errormsg = "<font color = red size = 1>Invalid Date of Birth (Year)</font>";
			$PageHasError = 1;
		}
		$StudContact = $_POST['StudContact'];
		$StudEmail = $_POST['StudEmail'];
		$PaName = $_POST['PaName'];
		$PaAddress = $_POST['PaAddress'];
		$OptOcc = $_POST['OptOcc'];
		$PaContact = $_POST['PaContact'];
		$PaIncome = $_POST['PaIncome'];
		
		$infofrom = $_POST['infofrom'];
		$othersource = $_POST['othersource'];
		
		
		if(!$_POST['OptClass']){
			$errormsg = "<font color = red size = 1>Select student class.</font>";
			$PageHasError = 1;
		}
		elseif(!$_POST['StudName']){
			$errormsg = "<font color = red size = 1>Student Name is empty.</font>";
			$PageHasError = 1;
		}
		elseif(!$_POST['PaName']){
			$errormsg = "<font color = red size = 1>Parent / Guardian Name is empty.</font>";
			$PageHasError = 1;
		}
		elseif(!$_POST['PaContact']){
			$errormsg = "<font color = red size = 1>Parent / Guardian contact no. is empty.</font>";
			$PageHasError = 1;
		}
		
		elseif ($PageHasError == 0)
		{
			if ($_POST['SaveEnquiry'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbenquiry where StudentName = '$StudName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The enquiry you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$EnqStatus = 0;
					$q = "Insert into tbenquiry(EnType,EnquiryDate,ClassID,CounselorID,Remarks,StudentName,Address,DOB,StuContactNo,Email,GuardianName,GuardianAddress,GuardianOccID,GuardianContactNo,GuardianIncome,InfoSource,OtherInfoSource,Closed,ActiveSession) Values ('$OptEnqType','$EnqDate','$OptClass','$OptEmp','$EnqRemarks','$StudName','$StudAdd','$StudDOB','$StudContact','$StudEmail','$PaName','$PaAddress','$OptOcc','$PaContact','$PaIncome','$infofrom','$othersource','$EnqStatus','$SessionID')";
					$result = mysql_query($q,$conn);
					
					$query = "select ID from tbenquiry where StudentName='$StudName'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$EnqID  = $dbarray['ID'];
					$Total = 5;
					for($i=1;$i<=$Total;$i++){
						if(isset($_POST['Qua'.$i]))
						{
							$EntryID = $i;
							$StuQua = $_POST['Qua'.$i];
							$Stusch = $_POST['sch'.$i];
							$StuPYear = $_POST['PYear'.$i];
							$StuScore = $_POST['Score'.$i];
							$q = "Insert into tbenquiryeducation(EnquiryID,Qualification,University,PassingYear,Percentage,EntryID) Values ('$EnqID','$StuQua','$Stusch','$StuPYear','$StuScore','$EntryID')";
							$result = mysql_query($q,$conn);
						}
					}
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				}
			}elseif ($_POST['SaveEnquiry'] =="Update"){
				$EnqStatus = 0;
				$q = "update tbenquiry set EnType='$OptEnqType',EnquiryDate='$EnqDate',ClassID='$OptClass',CounselorID='$OptEmp',Remarks='$EnqRemarks',StudentName='$StudName',Address='$StudAdd',DOB='$StudDOB',StuContactNo='$StudContact',Email='$StudEmail',GuardianName='$PaName',GuardianAddress='$PaAddress',GuardianOccID='$OptOcc',GuardianContactNo='$PaContact',GuardianIncome='$PaIncome',InfoSource='$infofrom',OtherInfoSource='$othersource',Closed='$EnqStatus' where ID = '$EnqID'";
				$result = mysql_query($q,$conn);
				
				$Total = 5;
				$q = "select ID from tbenquiry where StudentName='$StudName'";
				$result = mysql_query($q,$conn);
				$dbarray = mysql_fetch_array($result);
			    $EnqID  = $dbarray['ID'];
				if(isset($_POST['Qua1']))
				{
				$StuQua1 = $_POST['Qua1'];
				}
				for($i=1;$i<=$Total;$i++){
					if(isset($_POST['Qua'.$i]) && ($_POST['Qua'.$i]))
					{
						$EntryID = $i;
						$StuQua = $_POST['Qua'.$i];
						$Stusch = $_POST['sch'.$i];
						$StuPYear = $_POST['PYear'.$i];
						$StuScore = $_POST['Score'.$i];
						$q = "update tbenquiryeducation set Qualification='$StuQua',University='$Stusch',PassingYear='$StuPYear',Percentage='$StuScore' where EnquiryID = '$EnqID' and EntryID = '$EntryID'";
						$result = mysql_query($q,$conn);
					}
				}
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
			}
		}
		
	}
	if(isset($_GET['enq_id']))
	{
		$EnqID = $_GET['enq_id'];
		$query = "select * from tbenquiry where ID='$EnqID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptEnqType  = $dbarray['EnType'];
		$arrDate=explode ('-', $dbarray['EnquiryDate']);
		$dDay = $arrDate[2];
		$dMonth = $arrDate[1];
		$dYear = $arrDate[0];
		
		$OptClass  = $dbarray['ClassID'];
		$OptEmp  = $dbarray['CounselorID'];
		$EnqRemarks  = $dbarray['Remarks'];
		$StudName  = $dbarray['StudentName'];
		$StudAdd  = $dbarray['Address'];
		$arrDate2=explode ('-', $dbarray['DOB']);
		$StudDOB_day = $arrDate2[2];
		$StudDOB_month = $arrDate2[1];
		$StudDOB_year = $arrDate2[0];
		
		$StudContact  = $dbarray['StuContactNo'];
		$StudEmail  = $dbarray['Email'];
		$PaName  = $dbarray['GuardianName'];
		$PaAddress  = $dbarray['GuardianAddress'];
		$OptOcc  = $dbarray['GuardianOccID'];
		$PaContact  = $dbarray['GuardianContactNo'];
		$PaIncome  = $dbarray['GuardianIncome'];
		$infofrom  = $dbarray['InfoSource'];
		$othersource  = $dbarray['OtherInfoSource'];
		for($i=1; $i<=5; $i++){	
		$query = "select * from tbenquiryeducation where EnquiryID='$EnqID' and EntryID='$i'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Qualification[$i] = $dbarray['Qualification'];
		$University[$i] = $dbarray['University'];
		$PassingYear[$i] = $dbarray['PassingYear'];
		$Percentage[$i] = $dbarray['Percentage'];
		}
	}
	if(isset($_POST['followupEnquiry']))
	{
		$EnqID = $_POST['SelEnqID'];
		$fDay = date('d');
		$fMonth = date('m');
		$fYear = date('Y');
		$SubPage = "Enquiry Follow Up";
	}
	if(isset($_POST['SaveFollowup']))
	{
		$PageHasError = 0;
		$EnqID = $_POST['SelEnqID'];
		$FollID = $_POST['SelFollID'];
		$fDay = $_POST['fDay'];
		$fMonth = $_POST['fMonth'];
		$fYear = $_POST['fYear'];
		$FollDate = $fYear."-".$fMonth."-".$fDay;
		if(!is_numeric($fDay)){
			$errormsg = "<font color = red size = 1>Invalid Date (Day).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($fMonth)){
			$errormsg = "<font color = red size = 1>Invalid Date (Month).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($fYear)){
			$errormsg = "<font color = red size = 1>Invalid Date (Year).</font>";
			$PageHasError = 1;
		}
		$OptMode = $_POST['OptMode'];
		$OptEmp = $_POST['OptEmp'];
		$Remarks = $_POST['Remarks'];
		
		if($_POST['OptEmp'] == "" Or $_POST['OptEmp'] = "0"){
			$errormsg = "<font color = red size = 1>Select counselor</font>";
			$PageHasError = 1;
		}
		if(!$_POST['Remarks']){
			$errormsg = "<font color = red size = 1>Remarks is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SaveFollowup'] =="Add to list"){
				$num_rows = 0;
				$query = "select ID from tbenquiryfollowup where EnquiryID = '$EnqID' and Remarks = '$Remarks'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The follow up record you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					 $query = "select EnquiryID from tbenquiryfollowup where EnquiryID = '$EnqID'";
					  $result = mysql_query($query,$conn);
				      $num_rows = mysql_num_rows($result);
					  $remarkid = $num_rows;
					  //echo $num_rows;
					  //++$remarkid;
					  if($remarkid == 0){
						  ++$remarkid;}
						  else{
							  $query = "select RemarkID from tbenquiryfollowup where EnquiryID = '$EnqID'";
					            $result = mysql_query($query,$conn);
								$counter = 0;
		                         while ($dbarray = mysql_fetch_array($result)){
									 $counter = $counter + 1;
									 if ($counter == $num_rows){
										 $remarkid=$dbarray["RemarkID"];
										 ++$remarkid;
									 }
										 
								}
							   }
					$q = "Insert into tbenquiryfollowup(EnquiryID,FollowUpDate,CounselorID,Mode,Remarks,RemarkID) Values ('$EnqID','$FollDate','$OptEmp','$OptMode','$Remarks','$remarkid')";
					$result = mysql_query($q,$conn);
					
					/*$q = "update tbenquiry set Closed = '1' where ID = '$EnqID'";
					$result = mysql_query($q,$conn);*/
					
					$errormsg = "<font color = blue size = 1>Added to list Successfully.</font>";
					$Remarks = "";
				}
			}elseif ($_POST['SaveFollowup'] =="Update list"){
				$q = "update tbenquiryfollowup set FollowUpDate = '$FollDate',CounselorID = '$OptEmp',Mode = '$OptMode',Remarks = '$Remarks' where RemarkID = '$FollID' and EnquiryID = '$EnqID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				//$q = "update tbenquiry set Closed = '1' where ID = '$EnqID'";
				$result = mysql_query($q,$conn);
					
				$Remarks = "";
			}
		}
	}
	if(isset($_GET['foll_id']))
	{
		$FollID = $_GET['foll_id'];
		$EnqID = $_GET['enq_id'];
		$query = "select * from tbenquiryfollowup where RemarkID='$FollID' and EnquiryID = '$EnqID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$arrDate=explode ('-', $dbarray['FollowUpDate']);
		$fDay = $arrDate[2];
		$fMonth = $arrDate[1];
		$fYear = $arrDate[0];
		
		$OptEmp  = $dbarray['CounselorID'];
		$OptMode  = $dbarray['Mode'];
		$Remarks  = $dbarray['Remarks'];
	}
	if(isset($_POST['DeleteFollowup']))
	{
		$EnqID = $_POST['SelEnqID'];
		$Total = $_POST['TotalList'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkfollID'.$i]))
			{
				$chkfollID = $_POST['chkfollID'.$i];
				$q = "Delete From tbenquiryfollowup where ID = '$chkfollID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SubmitShow'])){
		$OptClass = $_POST['OptClass'];
		$OptEnqType = $_POST['OptEnqType'];
		$FrmDate = $_POST['fEn_Yr']."-".$_POST['fEn_Mth']."-".$_POST['fEn_Dy'];
		$ToDate = $_POST['bEn_Yr']."-".$_POST['bEn_Mth']."-".$_POST['bEn_Dy'];
		
		$fEn_Yr = $_POST['fEn_Yr'];
		$fEn_Mth = $_POST['fEn_Mth'];
		$fEn_Dy = $_POST['fEn_Dy'];
		$bEn_Yr = $_POST['bEn_Yr'];
		$bEn_Mth = $_POST['bEn_Mth'];
		$bEn_Dy = $_POST['bEn_Dy'];
	}
	if(isset($_POST['SubmitPrint']))
	{
		$PageHasError=0;
		$FrmDate = $_POST['fEn_Yr']."-".$_POST['fEn_Mth']."-".$_POST['fEn_Dy'];
		$ToDate = $_POST['bEn_Yr']."-".$_POST['bEn_Mth']."-".$_POST['bEn_Dy'];
		$FrmDate2 = $_POST['fEn_Dy']."-".$_POST['fEn_Mth']."-".$_POST['fEn_Yr'];
		$ToDate2 = $_POST['bEn_Dy']."-".$_POST['bEn_Mth']."-".$_POST['bEn_Yr'];
		$Result = Date_Comparison($FrmDate2,$ToDate2);
		if($Result == "false"){
			$errormsg = "<font color = red size = 1>Invalid Date Entry</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if($FrmDate=="--" or $ToDate == "--"){
				$errormsg = "<font color = red size = 1>Select date range</font>";
			}else{
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=enquiry_rpt.php?pg=Print List&fdate=$FrmDate&tdate=$ToDate\">";
				exit;
			}
		}
	}
	if(isset($_POST['SubmitFollowup']))
	{
		$FrmDate = $_POST['fEn_Yr']."-".$_POST['fEn_Mth']."-".$_POST['fEn_Dy'];
		$ToDate = $_POST['bEn_Yr']."-".$_POST['bEn_Mth']."-".$_POST['bEn_Dy'];
		if($FrmDate=="--" or $ToDate == "--"){
			$errormsg = "<font color = red size = 1>Select date range</font>";
		}else{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=enquiry_rpt.php?pg=Enquiries Followup List&fdate=$FrmDate&tdate=$ToDate\">";
			exit;
		}
	}
   if(isset($_POST['SubmitSearch'])){
       $SubmitSearch = 'true'; 
	   $Search_Key = $_POST['Search_Key'];
	   }
   if(isset($_GET['Search_Key']))
	{
		//$Optsearch = $_GET['Optsearch'];
		 $SubmitSearch = 'true';
		$Search_Key = $_GET['Search_Key'];
		
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
.style21 {font-weight: bold}
.style22 {font-weight: bold}
.style23 {color: #FF0000}
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
			  <TD width="219" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="859" align="center" valign="top">
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
		if ($SubPage == "Enquiry") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="enquiry.php?subpg=Enquiry">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="18%" valign="top"  align="left">
					  		<p><input type="submit" name="SubmitNew" value="&nbsp;&nbsp;&nbsp;&nbsp;New Enquiry&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"></p>
							<p><input type="submit" name="SubmitShow" value="&nbsp;&nbsp;&nbsp;&nbsp;Show/Refresh&nbsp;&nbsp;">
							</p>
							<p>
					   </TD>
					  <TD width="82%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  		<p>&nbsp;</p>
					  		<TABLE width="100%" align="center">
								<TBODY>
                                <TR>
								  <TD width="54%"  align="center" style="text-decoration: underline; font-weight: bolder; font-size: 10px; WIDTH: 98%"><b> PRINT ENQIURY LIST/PRINT FOLLOW UP LIST</b></TD></TR>
								<TR>
								  <TD width="54%"  align="left"><p>Enquiry Type: 
									  
									  
								  <select name="OptEnqType">
									<option value="">Select Type</option>
<?PHP
									if($OptEnqType == "Normal"){
?>
										<option value="<?PHP echo $OptEnqType; ?>" selected="selected"><?PHP echo $OptEnqType; ?></option>
										<option value="Safe">Safe</option>
										<option value="Urgent">Urgent</option>
<?PHP
									}elseif($OptEnqType == "Safe"){
?>
										<option value="Normal">Normal</option>
										<option value="<?PHP echo $OptEnqType; ?>" selected="selected"><?PHP echo $OptEnqType; ?></option>
										<option value="Urgent">Urgent</option>
<?PHP
									}elseif($OptEnqType == "Urgent"){
?>
										<option value="Normal">Normal</option>
										<option value="Safe">Safe</option>
										<option value="<?PHP echo $OptEnqType; ?>" selected="selected"><?PHP echo $OptEnqType; ?></option>
<?PHP
									}else{
?>
										<option value="Normal">Normal</option>
										<option value="Safe">Safe</option>
										<option value="Urgent">Urgent</option>
<?PHP
									}
?>
						  		</select>
								    </p></TD>
								  <TD width="46%" valign="top"  align="left"></TD>
								</TR>
								<TR>
								  <TD colspan="2" valign="top"  align="left"><p>Select Date Interval:&nbsp;&nbsp;&nbsp;<span style="font-weight: bolder; WIDTH: 98%; color: #F00; font-size: 9px;"> FROM </span>
								    <select name="fEn_Dy">
								      <option value="" selected="selected">Day</option>
								      
<?PHP
										for($i=1; $i<=31; $i++){
											if($fEn_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								    </select>
								    <select name="fEn_Mth">
								       <option value="" selected="selected">Month</option>
<?PHP
											for($i=1; $i<=12; $i++){
												if($i == $fEn_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}else{
													echo "<option value=$i>$i</option>";
												}
											}
?>
					                </select>
								    <select name="fEn_Yr">
								      <option value="" selected="selected">Year</option>
 <?PHP
 										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($fEn_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
						            </label><span style="font-weight: bolder; WIDTH: 98%; color: #F00; font-size: 9px;">TO </span>
								    <select name="bEn_Dy">
                                      <option value="" selected="selected">Day</option>
<?PHP
										for($i=1; $i<=31; $i++){
											if($bEn_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								  </select>
								  <select name="bEn_Mth">
									<option value="" selected="selected">Month</option>
<?PHP
										for($i=1; $i<=12; $i++){
											if($bEn_Mth == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
                                    <select name="bEn_Yr">
                                    <option value="" selected="selected">Year</option>
<?PHP
										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($bEn_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                   </select>
                                   </label>
								  </p>
								    </TD>
								</TR><TR>
								  <TD colspan="1" valign="top"  align="right"><p> <input type="submit" name="SubmitPrint" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Print Enquiry List&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" disabled>
							</p>
							
							</TD><TD colspan="1" valign="top"  align="left"><p><input type="submit" name="SubmitFollowup" value=" &nbsp;Print Followups List&nbsp;&nbsp;" disabled>
							</p></TD>
								</TR></TBODY>
							</TABLE>
					  </TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">					  
						<p style="margin-left:150px;">Student Name :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="44" bgcolor="#F4F4F4"><div align="center" class="style21">Status</div></td>
                            <td width="82" bgcolor="#F4F4F4"><div align="center" class="style21">Enquiry Type.</div></td>
                            <td width="83" bgcolor="#F4F4F4"><div align="center"><strong>Date</strong></div></td>
							<td width="89" bgcolor="#F4F4F4"><div align="center"><strong>Class Name</strong></div></td>
							<td width="137" bgcolor="#F4F4F4"><div align="center"><strong>Student Name</strong></div></td>
							<td width="99" bgcolor="#F4F4F4"><div align="center"><strong>Phone</strong></div></td>
							<td width="109" bgcolor="#F4F4F4"><div align="center"><strong>Counselor</strong></div></td>
                          </tr>
<?PHP
						if(isset($_POST['SubmitShow']) And $FrmDate !="--" And $ToDate !="--")
						{
							$arrDateList = date_range($FrmDate,$ToDate);
							$i = 0;
							while(isset($arrDateList[$i])){
								$numrows = 0;
								$counter_Enq = 0;
								$query2 = "select * from tbenquiry";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
							
								if($rstart==0){
									$counter_Enq = $rstart;
								}else{
									$counter_Enq = $rstart-1;
								}
								$counter = 0;
								if($OptEnqType == ""){
									$query3 = "select * from tbenquiry where EnquiryDate = '$arrDateList[$i]' order by StudentName";
								}else{
									$query3 = "select * from tbenquiry where EnquiryDate = '$arrDateList[$i]' And EnType = '$OptEnqType' order by StudentName";
								}
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter_dept = $counter_dept+1;
										$counter = $counter+1;
										$EnqNo = $row["ID"];
										$EnType = $row["EnType"];
										$EnquiryDate = $row["EnquiryDate"];
										$ClassID = $row["ClassID"];
										$CounselorID = $row["CounselorID"];
										$Remarks = $row["Remarks"];
										$StudentName = $row["StudentName"];
										$Email = $row["Email"];
										$GuardianContactNo = $row["GuardianContactNo"];
										$Closed = $row["Closed"];
										$OnClick = "";
										if($Closed==0){
											$Status = "Pending";
											$bgColor="#FFBF80";
?>
											<tr bgcolor="#FFBF80">
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $EnType; ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo Long_date($EnquiryDate); ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $StudentName; ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $GuardianContactNo; ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo GET_EMP_NAME($CounselorID); ?></a></div></td>
								  </tr>
<?PHP
										}elseif($Closed==1){
											$Status = "Closed";
											$bgColor="#cccccc";
?>
											<tr bgcolor="#FFBF80">
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $EnType; ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo Long_date($EnquiryDate); ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo GetClassName($ClassID); ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $StudentName; ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $GuardianContactNo; ?></a></div></td>
												<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo GET_EMP_NAME($CounselorID); ?></a></div></td>
								  </tr>
<?PHP
										}
								 	}
							 	}
								$i=$i+1;
							}
						}else{
							$counter_Enq = 0;
							//$query2 = "select * from tbenquiry";
							//$result2 = mysql_query($query2,$conn);
							//$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_Enq = $rstart;
							}else{
								$counter_Enq = $rstart-1;
							}
							$counter = 0;
							if($SubmitSearch == 'true'){
								//$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbenquiry where INSTR(StudentName,'$Search_Key') and ActiveSession = '$SessionID' order by StudentName LIMIT $rstart,$rend";
								$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select * from tbenquiry where INSTR(StudentName,'$Search_Key') and ActiveSession = '$SessionID'";
								$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$EnqNo = $row["ID"];
									$EnType = $row["EnType"];
									$EnquiryDate = $row["EnquiryDate"];
									$ClassID = $row["ClassID"];
									$CounselorID = $row["CounselorID"];
									$Remarks = $row["Remarks"];
									$StudentName = $row["StudentName"];
									$Email = $row["Email"];
									$GuardianContactNo = $row["GuardianContactNo"];
									$Closed = $row["Closed"];
									$OnClick = "";
									if($Closed==0){
										$Status = "Pending";
										$bgColor="#FFBF80";
?>
										<tr bgcolor="#FFBF80">
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $EnType; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo Long_date($EnquiryDate); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $StudentName; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $GuardianContactNo; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo GET_EMP_NAME($CounselorID); ?></a></div></td>
								  </tr>
<?PHP
									}elseif($Closed==1){
										$Status = "Closed";
										$bgColor="#cccccc";
?>
										<tr bgcolor="#FFBF80">
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $EnType; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo Long_date($EnquiryDate); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo GetClassName($ClassID); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $StudentName; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $GuardianContactNo; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo GET_EMP_NAME($CounselorID); ?></a></div></td>
								  </tr>
<?PHP
									}
								 }
							 }else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
						// }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="enquiry.php?subpg=Enquiry&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="enquiry.php?subpg=Enquiry&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="enquiry.php?subpg=Enquiry&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
							
					<?PHP 		}else{
								$query3 = "select * from tbenquiry where ActiveSession = '$SessionID' LIMIT $rstart,$rend ";
							   $result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select * from tbenquiry where ActiveSession = '$SessionID'";
							   $result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$EnqNo = $row["ID"];
									$EnType = $row["EnType"];
									$EnquiryDate = $row["EnquiryDate"];
									$ClassID = $row["ClassID"];
									$CounselorID = $row["CounselorID"];
									$Remarks = $row["Remarks"];
									$StudentName = $row["StudentName"];
									$Email = $row["Email"];
									$GuardianContactNo = $row["GuardianContactNo"];
									$Closed = $row["Closed"];
									$OnClick = "";
									if($Closed==0){
										$Status = "Pending";
										$bgColor="#FFBF80";
?>
										<tr bgcolor="#FFBF80">
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $EnType; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo Long_date($EnquiryDate); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $StudentName; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo $GuardianContactNo; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>"><?PHP echo GET_EMP_NAME($CounselorID); ?></a></div></td>
								  </tr>
<?PHP
									}elseif($Closed==1){
										$Status = "Closed";
										$bgColor="#cccccc";
?>
										<tr bgcolor="#FFBF80">
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><?PHP echo $Status; ?></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $EnType; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo Long_date($EnquiryDate); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo GetClassName($ClassID); ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $StudentName; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo $GuardianContactNo; ?></a></div></td>
											<td bgcolor="<?PHP echo $bgColor; ?>"><div align="center"><a href="enquiry.php?subpg=Enquiry Form&enq_id=<?PHP echo $EnqNo; ?>" onClick="if (!confirm('The record is closed, click ok to open, and cancel to exit!')) {return false;}"><?PHP echo GET_EMP_NAME($CounselorID); ?></a></div></td>
								  </tr>
<?PHP
									}
								 }
							}else{
								?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							}
							 
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="enquiry.php?subpg=Enquiry&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="enquiry.php?subpg=Enquiry&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="enquiry.php?subpg=Enquiry&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
                 }
		    }
		}elseif ($SubPage == "New Enquiry" or $SubPage == "New_Enquiry"  or $SubPage == "Enquiry Form") {
?>
				<?PHP echo $errormsg.$Percentage['1']; ?>
<?PHP
			if($SubPage == "Enquiry Form"){
				$disabledsave = " disabled='disabled'";
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><span class="style22"><a href="index.php">Home</a> &gt; <a href="enquiry.php?subpg=Enquiry">Enquiry</a> &gt; Enquiry Form</span></div>
				<form name="form1" method="post" action="enquiry.php?subpg=Enquiry Form">
<?PHP
			}else{
				if(isset($_GET['enq_id']))
				{
					$disabledsave = " disabled='disabled'";
				}else{
					$disabledfollowup = " disabled='disabled'";
					$disabledupdate = " disabled='disabled'";
				}
?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><span class="style22"><a href="index.php">Home</a> &gt; <a href="enquiry.php?subpg=Enquiry">Enquiry</a> &gt; New Enquiry</span></div>
				<form name="form1" method="post" action="enquiry.php?subpg=New Enquiry">
<?PHP
			}
?>
				
				<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="15%" valign="top"  align="left">Enquiry Type</TD>
					  <TD width="29%" valign="top"  align="left"><label>
					    <select name="OptEnqType">
<?PHP
						if($OptEnqType == "Normal"){
							echo "<option value=Normal selected='selected'>Normal</option>";
						}else{
							echo "<option value=Normal>Normal</option>";
						}
						if($OptEnqType == "Safe"){
							echo "<option value='Safe' selected='selected'>Safe</option>";
						}else{
							echo "<option value='Safe'>Safe</option>";
						}
						if($OptEnqType == "Urgent"){
							echo "<option value='Urgent' selected='selected'>Urgent</option>";
						}else{
							echo "<option value='Urgent'>Urgent</option>";
						}
?>
					    </select>
					 	 &nbsp;&nbsp;&nbsp;Enquiry ID
					  	 <input name="EnqID" type="text" size="5" value="<?PHP echo $EnqID; ?>" disabled="disabled">
					  </label></TD>
					  <TD width="26%" valign="top"  align="left">Date
					    <input name="dDay" type="text" size="2"  value="<?PHP echo $dDay; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="dMonth" type="text" size="2"  value="<?PHP echo $dMonth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="dYear" type="text" size="2"  value="<?PHP echo $dYear; ?>" ONFOCUS="clearDefault(this)"></TD>
					  <TD width="30%" valign="top"  align="left">Enquiry for
					  <select name="OptClass">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$ClassID = $row["ID"];
									$ClassName = $row["Class_Name"];
									if($OptClass ==$ClassID){
?>
										<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $ClassName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $ClassID; ?>"><?PHP echo $ClassName; ?></option>
<?PHP
									}
								}
							}
?>
					    </select>
					  <span class="style23">					  *</span>
					  <!--<label>
					  <input name="ClassMaster" type="submit" id="ClassMaster" value="...">
					  </label>-->					  </TD>
					</TR>
					<TR>
					  <TD width="15%" valign="top"  align="left">Counselor Name</TD>
					  <TD width="29%" valign="top"  align="left"><label>
					    <select name="OptEmp">
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
									if($OptEmp ==$EmpID){
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
					    </select>
					    <!--<input name="EmpMaster" type="submit" id="Counselor" value="...">
					  </label>--></TD>
					  <TD colspan="2" valign="top"  align="left">Remarks: 
					    <label>
					    <input name="EnqRemarks" type="text" size="55" value="<?PHP echo $EnqRemarks; ?>">
					    </label></TD>
					</TR>
					</TBODY>
				</TABLE>
				<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
					<TR>
						<TD colspan="2" valign="top"  align="left">
						<table border="0" cellpadding="0" cellspacing="0" width="365">
						  <tr>
						   <td colspan="3"><img name="Untitled1_r1_c1" src="images/Untitled-1_r1_c1.jpg" width="365" height="15" border="0" id="Untitled1_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="15" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="Untitled1_r2_c1" src="images/Untitled-1_r2_c1.jpg" width="9" height="235" border="0" id="Untitled1_r2_c1" alt="" /></td>
						   <td valign="top">
						   		<TABLE width="100%" style="WIDTH: 100%">
								<TBODY>
								<TR>
									<TD width="26%"  align="left" valign="top">&nbsp;</TD>
									<TD width="74%"  align="left" valign="top">&nbsp;</TD>
								</TR>
								<TR>
									<TD width="26%"  align="left" valign="top">Name</TD>
									<TD width="74%"  align="left" valign="top"><label>
									  <input name="StudName" type="text" size="35" value="<?PHP echo $StudName; ?>">
									  <span class="style23"> *</span></label></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Address</TD>
									<TD valign="top"  align="left"><textarea name="StudAdd" cols="35" rows="3"><?PHP echo $StudAdd; ?></textarea></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">D.O.B</TD>
									<TD valign="top"  align="left">
										<input name="StudDOB_day" type="text" size="5" value="<?PHP echo $StudDOB_day; ?>" ONFOCUS="clearDefault(this)">
										/
										<input name="StudDOB_month" type="text" size="5" value="<?PHP echo $StudDOB_month; ?>" ONFOCUS="clearDefault(this)">
										/
										<input name="StudDOB_year" type="text" size="5" value="<?PHP echo $StudDOB_year; ?>" ONFOCUS="clearDefault(this)">
									</TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Contact No.</TD>
									<TD valign="top"  align="left"><input name="StudContact" type="text" size="35" value="<?PHP echo $StudContact; ?>"></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Email</TD>
									<TD valign="top"  align="left"><input name="StudEmail" type="text" size="35" value="<?PHP echo $StudEmail; ?>"></TD>
								</TR>
								</TBODY>
								</TABLE>
						   </td>
						   <td rowspan="2"><img name="Untitled1_r2_c3" src="images/Untitled-1_r2_c3.jpg" width="9" height="235" border="0" id="Untitled1_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="225" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="Untitled1_r3_c2" src="images/Untitled-1_r3_c2.jpg" width="347" height="10" border="0" id="Untitled1_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="10" border="0" alt="" /></td>
						  </tr>
						</table>
						</TD>
						<TD colspan="2" valign="top"  align="left">
						<table border="0" cellpadding="0" cellspacing="0" width="365">
						  <tr>
						   <td colspan="3"><img name="Untitled1_r1_c1" src="images/Untitled-1_r1_c12.jpg" width="365" height="15" border="0" id="Untitled1_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="15" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="Untitled1_r2_c1" src="images/Untitled-1_r2_c1.jpg" width="9" height="235" border="0" id="Untitled1_r2_c1" alt="" /></td>
						   <td>
						   		<TABLE width="100%" style="WIDTH: 100%">
								<TBODY>
								<TR>
									<TD width="31%"  align="left" valign="top">&nbsp;</TD>
									<TD width="69%"  align="left" valign="top">&nbsp;</TD>
								</TR>
								<TR>
									<TD width="31%"  align="left" valign="top">Name</TD>
									<TD width="69%"  align="left" valign="top"><label>
									  <input name="PaName" type="text" size="35" value="<?PHP echo $PaName; ?>">
									  <span class="style23"> *</span></label></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Address</TD>
									<TD valign="top"  align="left"><textarea name="PaAddress" cols="35" rows="3"><?PHP echo $PaAddress; ?></textarea></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Occupation</TD>
									<TD valign="top"  align="left">
									<select name="OptOcc">
                                      <option value="0" selected="selected">&nbsp;</option>
<?PHP
											$query = "select ID,Frocc from tbfatheroccupation order by Frocc";
											$result = mysql_query($query,$conn);
											$num_rows = mysql_num_rows($result);
											if ($num_rows <= 0 ) {
												echo "";
											}
											else 
											{
												while ($row = mysql_fetch_array($result)) 
												{
													$OccID = $row["ID"];
													$Frocc = $row["Frocc"];
													if($OptOcc ==$OccID){
?>
                                      				<option value="<?PHP echo $OccID; ?>" selected="selected"><?PHP echo $Frocc; ?></option>
<?PHP
												}else{
?>
													 <option value="<?PHP echo $OccID; ?>"><?PHP echo $Frocc; ?></option>
<?PHP
												}
											}
										}
?>
                                    </select>
									<input name="OpenOccupation" type="submit" id="OpenOccupation" value="..."></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Contact No.</TD>
									<TD valign="top"  align="left"><input name="PaContact" type="text" size="35" value="<?PHP echo $PaContact; ?>">
									  <span class="style23"> *</span></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Income [Monthly]</TD>
									<TD valign="top"  align="left"><input name="PaIncome" type="text" size="35" value="<?PHP echo $PaIncome; ?>"></TD>
								</TR>
								</TBODY>
								</TABLE>
						   </td>
						   <td rowspan="2"><img name="Untitled1_r2_c3" src="images/Untitled-1_r2_c3.jpg" width="9" height="235" border="0" id="Untitled1_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="225" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="Untitled1_r3_c2" src="images/Untitled-1_r3_c2.jpg" width="347" height="10" border="0" id="Untitled1_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="10" border="0" alt="" /></td>
						  </tr>
						</table>
						
						</TD>
					</TR>
				</TBODY>
				</TABLE>
				<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
					<TR>
						<TD width="77%"  align="right" valign="top">
						  <br>
						  <table width="100%" border="0" align="left" cellpadding="3" cellspacing="3">
							<tr>
							  <td width="166" bgcolor="#F4F4F4"><div align="center" class="style21">Qualification</div></td>
							  <td width="162" bgcolor="#F4F4F4"><div align="center"><strong>School Attended</strong></div></td>
							  <td width="105" bgcolor="#F4F4F4"><div align="center"><strong>Year of Passing</strong></div></td>
							  <td width="96" bgcolor="#F4F4F4"><div align="center"><strong>Percentage</strong></div></td>
							</tr>

							
<?PHP
								
								for($i=1; $i<=5; $i++){
?>
									<tr>
									  <td width="166"><input name="Qua<?PHP echo $i; ?>" type="text" value="<?PHP echo $Qualification[$i]; ?>" size="30"></td>
									  <td width="162"><input name="sch<?PHP echo $i; ?>" type="text" value="<?PHP echo $University[$i]; ?>" size="30"></td>
									  <td width="105"><input name="PYear<?PHP echo $i; ?>" type="text" value="<?PHP echo $PassingYear[$i]; ?>" size="10"></td>
									  <td width="96"><input name="Score<?PHP echo $i; ?>" type="text" size="15" value="<?PHP echo $Percentage[$i]; ?>"></td>
									</tr>
<?PHP
								}
							
?>
						  </table>
						  
						</TD>
						<TD width="23%"  align="left" valign="top"><strong>Info about school from:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?PHP
						if($infofrom =="News Paper"){
						  	echo"<input type=radio name='infofrom' value='News Paper' checked='checked'>&nbsp;&nbsp;&nbsp;News Paper<br>";
						}else{
							echo"<input type=radio name='infofrom' value='News Paper'>&nbsp;&nbsp;&nbsp;News Paper<br>";
						}
						if($infofrom =="Radio Set"){
						  	echo"<input type=radio name='infofrom' value='Radio Set' checked='checked'>&nbsp;&nbsp;&nbsp;Radio Set<br>";
						}else{
							echo"<input type=radio name='infofrom' value='Radio Set'>&nbsp;&nbsp;&nbsp;Radio Set<br>";
						}
						if($infofrom =="Word of Mouth"){
						  	echo"<input type=radio name=infofrom value='Word of Mouth' checked='checked'>&nbsp;&nbsp;&nbsp;Word of Mouth<br>";
						}else{
							echo"<input type=radio name=infofrom value='Word of Mouth'>&nbsp;&nbsp;&nbsp;Word of Mouth<br>";
						}
						if($infofrom =="Leaflet"){
						  	echo"<input type=radio name=infofrom value='Leaflet' checked='checked'>&nbsp;&nbsp;&nbsp;Leaflet<br>";
						}else{
							echo"<input type=radio name=infofrom value='Leaflet'>&nbsp;&nbsp;&nbsp;Leaflet<br>";
						}
						if($infofrom =="Banner"){
						  	echo"<input type=radio name=infofrom value='Banner' checked='checked'>&nbsp;&nbsp;&nbsp;Banner<br>";
						}else{
							echo"<input type=radio name=infofrom value='Banner'>&nbsp;&nbsp;&nbsp;Banner<br>";
						}
						if($infofrom =="Website"){
						  	echo"<input type=radio name=infofrom value='Website' checked='checked'>&nbsp;&nbsp;&nbsp;Website/Internet<br>";
						}else{
							echo"<input type=radio name=infofrom value='Website'>&nbsp;&nbsp;&nbsp;Website/Internet<br>";
						}
						if($infofrom =="others"){
						  	 echo"<input type=radio name=infofrom value='others' checked='checked'>&nbsp;&nbsp;&nbsp;Other Source</p>";
						}else{
							 echo"<input type=radio name=infofrom value='others'>&nbsp;&nbsp;&nbsp;Other Source</p>";
						}
?>
						  <p>Other Source </p>
						  <p>
						    <input type="text" name="othersource">
						  </p></TD>
					</TR>
					<TR>
						 <TD colspan="2">
						 <div align="center">
							 <input type="hidden" name="SelEnqID" value="<?PHP echo $EnqID; ?>">
							 <input name="followupEnquiry" type="submit" id="followupEnquiry" value="Follow up" <?PHP echo $disabledfollowup; ?>>
						     <input name="SaveEnquiry" type="submit" id="SaveEnquiry" value="Create New" <?PHP echo $disabledsave; ?>>
						     <input name="SaveEnquiry" type="submit" id="UpdateEnquiry" value="Update" <?PHP echo $disabledupdate; ?>>
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
					</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Occupation") {
?>
				<?PHP echo $errormsg; ?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><span class="style22"><a href="index.php">Home</a> &gt; <a href="enquiry.php?subpg=Enquiry">Enquiry</a> &gt; <a href="enquiry.php?subpg=New Enquiry">New Equiry</a> &gt; Occupation</span></div>
				<form name="form1" method="post" action="enquiry.php?subpg=Occupation">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" align="left"><div align="right">Occupation ID : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OccID" type="text" size="55" value="<?PHP echo $OccID; ?>" disabled="disabled"></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">Occupation : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OccName" type="text" size="55" value="<?PHP echo $OccName; ?>"></TD>
					</TR>
					<TR>
						 <TD colspan="2">
							 <table width="539" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="178"><strong>TICK</strong></td>
									<td width="178"><strong>OCCUPATION</strong></td>
									<td width="154"><strong>ID</strong></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbfatheroccupation order by Frocc";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Occupation found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelOccID = $row["ID"];
										$OccupationName = $row["Frocc"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkoccID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelOccID; ?>">
									           </div></td>
											<td><div align="center"><a href="enquiry.php?subpg=Occupation&occ_id=<?PHP echo $SelOccID; ?>"><?PHP echo $OccupationName; ?></a></div></td>
											<td><div align="center"><a href="enquiry.php?subpg=Occupation&occ_id=<?PHP echo $SelOccID; ?>"><?PHP echo $counter; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  </table>
						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						 <div align="center">
						   	 <input type="hidden" name="TotalOcc" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelOccID" value="<?PHP echo $OccID; ?>">
						     <input name="Occmaster" type="submit" id="Occmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="Occmaster_delete" type="submit" id="Occmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Occmaster" type="submit" id="Occmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Enquiry Follow Up") {
?>
				<?PHP echo $errormsg; ?>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><span class="style22"><a href="index.php">Home</a> &gt; <a href="enquiry.php?subpg=Enquiry">Enquiry</a> &gt; <a href="enquiry.php?subpg=New Enquiry&enq_id=<?PHP echo $EnqID; ?>"> Equiry Form</a> &gt; Enquiry Follow Up</span></div>
				<form name="form1" method="post" action="enquiry.php?subpg=Enquiry Follow Up">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="27%" valign="top"  align="left">Date
					    <input name="fDay" type="text" size="2"  value="<?PHP echo $fDay; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="fMonth" type="text" size="2"  value="<?PHP echo $fMonth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="fYear" type="text" size="2"  value="<?PHP echo $fYear; ?>" ONFOCUS="clearDefault(this)"></TD>
					   <TD width="73%" valign="top"  align="left">Mode
					    <select name="OptMode">
<?PHP
						if($OptMode == "In Person"){
							echo "<option value='In Person' selected='selected'>In Person</option>";
						}else{
							echo "<option value='In Person'>In Person</option>";
						}
						if($OptMode == "Telephone"){
							echo "<option value='Telephone' selected='selected'>Telephone</option>";
						}else{
							echo "<option value='Telephone'>Telephone</option>";
						}
						if($OptMode == "Email"){
							echo "<option value='Email' selected='selected'>Email</option>";
						}else{
							echo "<option value='Email'>Email</option>";
						}
						if($OptMode == "SMS"){
							echo "<option value='SMS' selected='selected'>SMS</option>";
						}else{
							echo "<option value='SMS'>SMS</option>";
						}
?>
					    </select>
					 	 &nbsp;&nbsp;&nbsp;Counselor Name
					  	 <select name="OptEmp">
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
									if($OptEmp ==$EmpID){
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
					    </select>
					  </TD>
					</TR>
					<TR>
					  <TD width="27%" align="left"><div align="right">Remarks : </div></TD>
					  <TD width="73%" valign="top"  align="left"><textarea name="Remarks" cols="65" rows="7"><?PHP echo $Remarks; ?></textarea></TD>
					</TR>
					<TR>
						 <TD colspan="2">
						                   
						                                               
						 <div align="center">
						 	 <input type="hidden" name="SelEnqID" value="<?PHP echo $EnqID; ?>">
							 <input type="hidden" name="SelFollID" value="<?PHP echo $FollID; ?>">
						     <input name="SaveFollowup" type="submit" id="SaveFollowup" value="Add to list">
							 <input name="SaveFollowup" type="submit" id="SaveFollowup" value="Update list">
							 <input name="reset" type="reset" id="Occmaster" value="Reset">
						</div>
						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
							 <table width="709" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="30"><strong>TICK</strong></td>
									<td width="99"><strong>Date</strong></td>
									<td width="121"><strong>FollowUP Mode</strong></td>
									<td width="152"><strong>Counselor</strong></td>
									<td width="259"><strong>Remarks</strong></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbenquiryfollowup where EnquiryID = '$EnqID'";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Follow up found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$query = "select * from tbenquiryfollowup where EnquiryID = '$EnqID' and RemarkID = '$counter'";
										$SelFollID = $row["ID"];
										$FollowUpDate = $row["FollowUpDate"];
										$CounselorID = $row["CounselorID"];
										$Mode = $row["Mode"];
										$Remarks = $row["Remarks"];
										$remarkid = $row["RemarkID"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkfollID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelFollID; ?>">
									           </div></td>
											<td><div align="center"><a href="enquiry.php?subpg=Enquiry Follow Up&enq_id=<?PHP echo $EnqID; ?>&foll_id=<?PHP echo $remarkid; ?>"><?PHP echo Long_date($FollowUpDate); ?></a></div></td>
											<td><div align="center"><a href="enquiry.php?subpg=Enquiry Follow Up&enq_id=<?PHP echo $EnqID; ?>&foll_id=<?PHP echo $remarkid; ?>"><?PHP echo $Mode; ?></a></div></td>
											<td><div align="center"><a href="enquiry.php?subpg=Enquiry Follow Up&enq_id=<?PHP echo $EnqID; ?>&foll_id=<?PHP echo $remarkid; ?>"><?PHP echo GET_EMP_NAME($CounselorID); ?></a></div></td>
											<td><div align="center"><a href="enquiry.php?subpg=Enquiry Follow Up&enq_id=<?PHP echo $EnqID; ?>&foll_id=<?PHP echo $remarkid; ?>"><?PHP echo $Remarks; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  </table>
						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						 <div align="center">
						 	<input type="hidden" name="SelEnqID" value="<?PHP echo $EnqID; ?>">
						 	<input type="hidden" name="TotalList" value="<?PHP echo $counter; ?>">
							 <input name="DeleteFollowup" type="submit" id="Occmaster" value="Remove From List" onClick="if (!confirm('Are you sure you want to remove this record, click ok to proceed, and cancel to exit!')) {return false;}">
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
