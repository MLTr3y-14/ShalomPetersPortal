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
		$Adm_Dy = date('d');
		$Adm_Mth = date('m');
		$Adm_Yr = date('Y');
	}
	$Page = "Admission";
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
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	$PageHasError = 0;
	if(isset($_GET['filename']))
	{
		$StuFilename = $_GET['filename'];
	}else{
		$StuFilename = "empty_r2_c2.jpg";
	}
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['GetStudent']))
	{
		$OptSearch = $_POST['OptSearch'];
		$OptClass = $_POST['OptClass'];
		$StuName = $_POST['StuName'];
	}
	if(isset($_GET['admNo']))
	{
		$AdmNo = $_GET['admNo'];
		$query = "select Stu_Type,Stu_Full_Name,Stu_Class from tbstudentmaster where AdmissionNo='$AdmNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StuType  = $dbarray['Stu_Type'];
		$StuName  = strtoupper($dbarray['Stu_Full_Name']);
		$StuClass  = strtoupper($dbarray['Stu_Class']);
	}
	if(isset($_POST['Openstudentcharge']))
	{
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=mas_setup2.php?subpg=Hosteller and Day Scholar Charges&bk=$backpg \">";
		exit;
	}
	if(isset($_POST['OptCharge']))
	{
		$AdmNo = $_POST['admNo'];
		$OptCharge = $_POST['OptCharge'];
		
		$query = "select Stu_Type,Stu_Full_Name,Stu_Class from tbstudentmaster where AdmissionNo='$AdmNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StuType  = $dbarray['Stu_Type'];
		$StuName  = strtoupper($dbarray['Stu_Full_Name']);
		$StuClass  = strtoupper($dbarray['Stu_Class']);
		
		//GET CHARGE NAME
		$query2 = "select ChargeName from tbchargemaster where ID = '$OptCharge' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result2 = mysql_query($query2,$conn);
		$dbarray2 = mysql_fetch_array($result2);
		$chName  = $dbarray2['ChargeName'];
		
		//GET CHARGE AMOUNT
		$query2 = "select Amount from tbclasscharges where ClassID = '$StuClass' and ChargeName = '$chName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result2 = mysql_query($query2,$conn);
		$dbarray2 = mysql_fetch_array($result2);
		$chargeAmount  = $dbarray2['Amount'];
	}
	if(isset($_POST['Addcharges']))
	{
		$PageHasError = 0;
		$OptCharge = $_POST['OptCharge'];
		$AdmNo = $_POST['admNo'];
		$chargeAmount = $_POST['chargeAmount'];
		
		if(!$_POST['OptCharge']){
			$errormsg = "<font color = red size = 1>Select charge applicable to student.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['admNo']){
			$errormsg = "<font color = red size = 1>Student not selected.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($chargeAmount)){
			$errormsg = "<font color = red size = 1>Amount should be in numbers.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$query = "select * from tbstudentsubmitedcharges where ChargeID='$OptCharge' and Admno='$AdmNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) 
			{
				$errormsg = "<font color = red size = 1>The selected charge has already been assign to student.</font>";
				$PageHasError = 1;
			}else {
				$q = "Insert into tbstudentsubmitedcharges(Admno,ChargeID,Amount,,Session_ID,Term_ID) Values ('$AdmNo','$OptCharge','$chargeAmount','$Session_ID','$Term_ID')";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				
				$chargeAmount = "";
			}
		}
	}
	if(isset($_GET['sc_id']))
	{
		$sc_id = $_GET['sc_id'];

		$query = "select * from tbstudentsubmitedcharges where ID='$sc_id' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptCharge  = $dbarray['ChargeID'];
		$chargeAmount  = $dbarray['Amount'];

	}
	if(isset($_POST['deletecharges']))
	{
		$OptCharge = $_POST['OptCharge'];
		$AdmNo = $_POST['admNo'];
		$chargeAmount = $_POST['chargeAmount'];
		$query = "select * from tbstudentsubmitedcharges where ChargeID='$OptCharge' and Admno='$AdmNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0 ) 
		{
			$q = "Delete From tbstudentsubmitedcharges where Admno = '$AdmNo' and ChargeID = '$OptCharge' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($q,$conn);
			$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
		}else{
			$errormsg = "<font color = red size = 1>record has already been deleted.</font>";
			$PageHasError = 1;
		}
	}
	if(isset($_POST['chargesUpdate']))
	{
		$Total = $_POST['Totaldoc'];
		$AdmNo = $_POST['admNo'];
		$q = "Delete From tbstudentsubmiteddoc where AdmNo = '$AdmNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result = mysql_query($q,$conn);
			
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkDocID'.$i]))
			{
				$chkDocID = $_POST['chkDocID'.$i];
				$query = "select * from tbstudentsubmiteddoc where AdmNo='$AdmNo' and Doc_ID = '$chkDocID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The selected document has already been assign to student.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbstudentsubmiteddoc(AdmNo,Doc_ID,Session_ID,Term_ID) Values ('$AdmNo','$chkDocID','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);

					$errormsg = "<font color = blue size = 1>updated Successfully.</font>";
				}
			}
		}
	}
	if(isset($_POST['AdmForm']))
	{	
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=admission.php?subpg=Admission\">";
		exit;
	}
	if(isset($_GET['admnNo']))
	{
		$AdmNo = $_GET['admnNo'];
		$query = "select Stu_Type,Stu_Full_Name,Stu_Class from tbstudentmaster where AdmissionNo='$AdmNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StuType  = $dbarray['Stu_Type'];
		$StuName  = strtoupper($dbarray['Stu_Full_Name']);
		$StuClass  = strtoupper($dbarray['Stu_Class']);
	}
	if(isset($_GET['SL_admNo']))
	{
		$SL_AdmnNo = $_GET['SL_admNo'];
		$query3 = "select * from tb_slcertificate where admno='$SL_AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$AdmnNo = $row["admno"];
				$isFailed = $row["failed"];
				$ispromoted = $row["Promot"];
				$last_due_mth = $row["due_Month"];
				$last_due_yr = $row["due_Year"];
				$workingday = $row["No_work_days"];
				$Noofdayspresent = $row["No_Pr_Days"];
				$GamePlayed = $row["Games"];
				$Conduct = $row["Conduct"];
				$AppDate = $row["App_Date"];
				$IssDate  = $row["Iss_Date"];
				$OptLastClass = $row["Last_Class"];
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
				
				$query = "select Gr_Name_Mr from tbstudentdetail where Stu_Regist_No='$StuRegNo'and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$fathername  = $dbarray['Gr_Name_Mr'];
			
			}
		}else{
			$query = "select Stu_Full_Name,Stu_DOB,Stu_Date_of_Admis,Stu_Class,Games from tbstudentmaster where AdmissionNo='$SL_AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$AdmnNo = $SL_AdmnNo;
			$StudentName  = $dbarray['Stu_Full_Name'];
			$DOB  = $dbarray['Stu_DOB'];
			$AdmnDate  = $dbarray['Stu_Date_of_Admis'];
			$StuRegNo  = $dbarray['Stu_Regist_No'];
			$OptLastClass  = $dbarray['Stu_Class'];
			$GamePlayed  = $dbarray['Games'];
			
			$query = "select Gr_Name_Mr from tbstudentdetail where Stu_Regist_No='$StuRegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$fathername  = $dbarray['Gr_Name_Mr'];
			
			$workingday = 91;
			$Noofdayspresent = GetTotalAtt($SL_AdmnNo);
		}
	}
	if(isset($_POST['SLUpdate']))
	{
		$PageHasError = 0;
		$AdmnNo = $_POST['AdmnNo'];
		$isFailed = $_POST['isFailed'];
		$ispromoted = $_POST['ispromoted'];
		$last_due_mth = $_POST['last_due_mth'];
		$last_due_yr = $_POST['last_due_yr'];
		$workingday = $_POST['workingday'];
		$Noofdayspresent = $_POST['Noofdayspresent'];
		$GamePlayed = $_POST['GamePlayed'];
		$Conduct = $_POST['Conduct'];
		$AppDate = $_POST['AppDate'];
		$IssDate = $_POST['IssDate'];
		$OptLastClass = $_POST['OptLastClass'];
		$OptPromotedClass = $_POST['OptPromotedClass'];
		$AdmnDate = $_POST['AdmnDate'];
		$Reasonsleaving = $_POST['Reasonsleaving'];
		$remarks = $_POST['remarks'];
		
		if(!$_POST['AdmnNo']){
			$errormsg = "<font color = red size = 1>Student Admission No is Empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($workingday)){
			$errormsg = "<font color = red size = 1>No of working day should be in numbers.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($Noofdayspresent)){
			$errormsg = "<font color = red size = 1> No. of days present should be in numbers. </font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SLUpdate'] =="Update"){
				$query = "select * from tb_slcertificate where admno='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$q = "update tb_slcertificate set failed = '$isFailed',Promot = '$ispromoted',due_Month = '$last_due_mth',due_Year = '$last_due_yr',No_work_days = '$workingday',No_Pr_Days = '$Noofdayspresent',Games = '$GamePlayed',Conduct = '$Conduct',App_Date = '$AppDate',Iss_Date = '$IssDate',Last_Class = '$OptLastClass',Pr_Class = '$OptPromotedClass',Date_Admin = '$AdmnDate',Reason = '$Reasonsleaving',AnyOther = '$remarks' where admno = '$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($q,$conn);
					
					$q = "update tbstudentmaster set Stu_Class = '0',Stu_Sec = '--Left School--' where AdmissionNo = '$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($q,$conn);
						
					$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				}else {				
					$q = "Insert into tb_slcertificate(admno,failed,Promot,due_Month,due_Year,No_work_days,No_Pr_Days,Games,Conduct,App_Date,Iss_Date,Last_Class,Pr_Class,Date_Admin,Reason,AnyOther,Session_ID,Term_ID) Values ('$AdmnNo','$isFailed','$ispromoted','$last_due_mth','$last_due_yr','$workingday','$Noofdayspresent','$GamePlayed','$Conduct','$AppDate','$IssDate','$OptLastClass','$OptPromotedClass','$AdmnDate','$Reasonsleaving','$remarks','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					
					$q = "update tbstudentmaster set Stu_Class = '0',Stu_Sec = '--Left School--' where AdmissionNo = '$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($q,$conn);
					
					$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
					
					
					
					$AdmnNo = "";
					$isFailed = "";
					$ispromoted = "";
					$last_due_mth = "";
					$last_due_yr = "";
					$workingday = "";
					$Noofdayspresent = "";
					$GamePlayed = "";
					$Conduct = "";
					$AppDate = "";
					$IssDate = "";
					$OptLastClass = "";
					$OptPromotedClass = "";
					$AdmnDate = "";
					$Reasonsleaving = "";
					$remarks = "";
				}
			}elseif ($_POST['SLUpdate'] =="Delete"){
				$q = "Delete From tb_slcertificate where admno = '$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
			}
		}
	}
	if(isset($_POST['PrintForm']))
	{
		$AdmnNo = $_POST['AdmnNo'];
		if(!$_POST['AdmnNo']){
			$errormsg = "<font color = red size = 1>Student Admission No is Empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=admission_rpt.php?pg=Transfer Certificate&admid=$AdmnNo\">";
			exit;
		}
	}
	if(isset($_POST['OptSearch']))
	{	
		$OptSearch = $_POST['OptSearch'];
		
	}
	if(isset($_POST['GetStudent']))
	{
		$OptSearch = $_POST['OptSearch'];
		if($OptSearch == "Class"){
			$OptClass = $_POST['OptClass'];
		}elseif($OptSearch == "Name"){
			$Search_Key = $_POST['StuName'];
		}
		
		
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager</TITLE>
<META content="SkoolNet Manager." name="Short Title">
<META content="SkoolNet Manager" name=AGLS.Function>
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

.style24 {
	color: #FFFFFF;
	font-weight: bold;
}
.style25 {color: #FFFFFF}
</style>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
<tr><td id="ds_calclass">
</td></tr>
</table>
<script type="text/javascript">
var ds_i_date = new Date();
ds_c_month = ds_i_date.getMonth() + 1;
ds_c_year = ds_i_date.getFullYear();

// Get Element By Id
function ds_getel(id) {
	return document.getElementById(id);
}

// Get the left and the top of the element.
function ds_getleft(el) {
	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}
function ds_gettop(el) {
	var tmp = el.offsetTop;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}

// Output Element
var ds_oe = ds_getel('ds_calclass');
// Container
var ds_ce = ds_getel('ds_conclass');

// Output Buffering
var ds_ob = ''; 
function ds_ob_clean() {
	ds_ob = '';
}
function ds_ob_flush() {
	ds_oe.innerHTML = ds_ob;
	ds_ob_clean();
}
function ds_echo(t) {
	ds_ob += t;
}

var ds_element; // Text Element...

var ds_monthnames = [
'January', 'February', 'March', 'April', 'May', 'June',
'July', 'August', 'September', 'October', 'November', 'December'
]; // You can translate it for your language.

var ds_daynames = [
'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
]; // You can translate it for your language.

// Calendar template
function ds_template_main_above(t) {
	return '<table cellpadding="3" cellspacing="1" class="ds_tbl">'
	     + '<tr>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_py();">&lt;&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_pm();">&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_hi();" colspan="3">[Close]</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_nm();">&gt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_ny();">&gt;&gt;</td>'
		 + '</tr>'
	     + '<tr>'
		 + '<td colspan="7" class="ds_head">' + t + '</td>'
		 + '</tr>'
		 + '<tr>';
}

function ds_template_day_row(t) {
	return '<td class="ds_subhead">' + t + '</td>';
	// Define width in CSS, XHTML 1.0 Strict doesn't have width property for it.
}

function ds_template_new_week() {
	return '</tr><tr>';
}

function ds_template_blank_cell(colspan) {
	return '<td colspan="' + colspan + '"></td>'
}

function ds_template_day(d, m, y) {
	return '<td class="ds_cell" onclick="ds_onclick(' + d + ',' + m + ',' + y + ')">' + d + '</td>';
	// Define width the day row.
}

function ds_template_main_below() {
	return '</tr>'
	     + '</table>';
}

// This one draws calendar...
function ds_draw_calendar(m, y) {
	// First clean the output buffer.
	ds_ob_clean();
	// Here we go, do the header
	ds_echo (ds_template_main_above(ds_monthnames[m - 1] + ' ' + y));
	for (i = 0; i < 7; i ++) {
		ds_echo (ds_template_day_row(ds_daynames[i]));
	}
	// Make a date object.
	var ds_dc_date = new Date();
	ds_dc_date.setMonth(m - 1);
	ds_dc_date.setFullYear(y);
	ds_dc_date.setDate(1);
	if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) {
		days = 31;
	} else if (m == 4 || m == 6 || m == 9 || m == 11) {
		days = 30;
	} else {
		days = (y % 4 == 0) ? 29 : 28;
	}
	var first_day = ds_dc_date.getDay();
	var first_loop = 1;
	// Start the first week
	ds_echo (ds_template_new_week());
	// If sunday is not the first day of the month, make a blank cell...
	if (first_day != 0) {
		ds_echo (ds_template_blank_cell(first_day));
	}
	var j = first_day;
	for (i = 0; i < days; i ++) {
		// Today is sunday, make a new week.
		// If this sunday is the first day of the month,
		// we've made a new row for you already.
		if (j == 0 && !first_loop) {
			// New week!!
			ds_echo (ds_template_new_week());
		}
		// Make a row of that day!
		ds_echo (ds_template_day(i + 1, m, y));
		// This is not first loop anymore...
		first_loop = 0;
		// What is the next day?
		j ++;
		j %= 7;
	}
	// Do the footer
	ds_echo (ds_template_main_below());
	// And let's display..
	ds_ob_flush();
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// A function to show the calendar.
// When user click on the date, it will set the content of t.
function ds_sh(t) {
	// Set the element to set...
	ds_element = t;
	// Make a new date, and set the current month and year.
	var ds_sh_date = new Date();
	ds_c_month = ds_sh_date.getMonth() + 1;
	ds_c_year = ds_sh_date.getFullYear();
	// Draw the calendar
	ds_draw_calendar(ds_c_month, ds_c_year);
	// To change the position properly, we must show it first.
	ds_ce.style.display = '';
	// Move the calendar container!
	the_left = ds_getleft(t);
	the_top = ds_gettop(t) + t.offsetHeight;
	ds_ce.style.left = the_left + 'px';
	ds_ce.style.top = the_top + 'px';
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// Hide the calendar.
function ds_hi() {
	ds_ce.style.display = 'none';
}

// Moves to the next month...
function ds_nm() {
	// Increase the current month.
	ds_c_month ++;
	if (ds_c_month > 12) {
		ds_c_month = 1; 
		ds_c_year++;
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous month...
function ds_pm() {
	ds_c_month = ds_c_month - 1; // Can't use dash-dash here, it will make the page invalid..
	if (ds_c_month < 1) {
		ds_c_month = 12; 
		ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the next year...
function ds_ny() {
	// Increase the current year.
	ds_c_year++;
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous year...
function ds_py() {
	// Decrease the current year.
	ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Format the date to output.
function ds_format_date(d, m, y) {
	// 2 digits month.
	m2 = '00' + m;
	m2 = m2.substr(m2.length - 2);
	// 2 digits day.
	d2 = '00' + d;
	d2 = d2.substr(d2.length - 2);
	// YYYY-MM-DD
	return y + '-' + m2 + '-' + d2;
}

function ds_onclick(d, m, y) {
	ds_hi();
	// Set the value of it, if we can.
	if (typeof(ds_element.value) != 'undefined') {
		ds_element.value = ds_format_date(d, m, y);
	// Maybe we want to set the HTML in it.
	} else if (typeof(ds_element.innerHTML) != 'undefined') {
		ds_element.innerHTML = ds_format_date(d, m, y);
	// I don't know how should we display it, just alert it to user.
	} else {
		alert (ds_format_date(d, m, y));
	}
}
</script>
<SCRIPT type=text/javascript>
<!--
var theForm = document.forms['aspnetForm'];
if (!theForm) {
    theForm = document.aspnetForm;
}
function __doPostBack(eventTarget, eventArgument) {
    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
        theForm.__EVENTTARGET.value = eventTarget;
        theForm.__EVENTARGUMENT.value = eventArgument;
        theForm.submit();
    }
}
// -->
</SCRIPT>
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
			  <TD width="223" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="855" align="center" valign="top">
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
<?PHP
		if ($SubPage == "Charges and Documents") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="studcharges.php?subpg=Charges and Documents">
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
				<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="28%" valign="top"  align="left">
					  		<strong>Search Criteria</strong>
							<p>
<?PHP
							if($OptSearch =="All")
							{
								echo "<input type='radio' name='OptSearch' value='All' checked='checked'/>All Student";
							}else{
								echo "<input type='radio' name='OptSearch' value='All'/>All Student";
							}
?>
							</p>
							<p>
<?PHP
							if($OptSearch =="Class")
							{
								echo "<input type='radio' name='OptSearch' value='Class' checked='checked'/>Class";
							}else{
								echo "<input type='radio' name='OptSearch' value='Class'/>Class";
							}
?>
							<select name="OptClass" style="background-color:#FFFF99;">
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
							</p>
							<p>
<?PHP
							if($OptSearch =="Name")
							{
								echo "<input type='radio' name='OptSearch' value='Name' checked='checked'/>Name";
							}else{
								echo "<input type='radio' name='OptSearch' value='Name'/>Name";
							}
?>					  		   
							<input type="text" name="StuName" size="15" value="<?PHP echo $StuName; ?>">
							<input name="GetStudent" type="submit" id="GetStudent" value="Go"></p>

							<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="70%" bgcolor="#F4F4F4"><div align="center" class="style21">Name</div></td>
								<td width="30%" bgcolor="#F4F4F4"><div align="center" class="style21">Adm No.</div></td>
							  </tr>
<?PHP
								$counter_stud = 0;
								$query2 = "select * from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_stud = $rstart;
								}else{
									$counter_stud = $rstart-1;
								}
								$counter = 0;
								if(isset($_POST['OptSearch']))
								{
									if($_POST['OptSearch'] == "All"){
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}elseif($_POST['OptSearch'] == "Class"){
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' and Stu_Class = '$OptClass' LIMIT $rstart,$rend";
									}elseif($_POST['OptSearch'] == "Name"){
										$Search_Key = $_POST['StuName'];
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 order by Stu_Full_Name";
									}else{
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}
								}else{
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter_stud = $counter_stud+1;
										$counter = $counter+1;
										$AdmID = $row["ID"];
										$AdmissionNo = $row["AdmissionNo"];
										$Stu_Full_Name = $row["Stu_Full_Name"];
?>
										  <tr bgcolor="#FFFFFF">
											<td><div align="center"><a href="studcharges.php?subpg=Charges and Documents&admNo=<?PHP echo $AdmissionNo; ?>"><?PHP echo $Stu_Full_Name; ?></a></div></td>
											<td><div align="center"><a href="studcharges.php?subpg=Charges and Documents&admNo=<?PHP echo $AdmissionNo; ?>"><?PHP echo $AdmissionNo; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
                        	</table>
							<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="studcharges.php?subpg=Charges and Documents&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;</a>| &nbsp;<a href="studcharges.php?subpg=Charges and Documents&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>

					  		<p>&nbsp;</p>
							</TD>
					 	 <TD width="72%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  	<P><strong>Student Name : </strong><?PHP echo $StuName; ?>&nbsp;</P>
						<P><strong>Active Term :  </strong><?PHP echo $Activeterm; ?></P>
					  	<TABLE width="100%">
							<TBODY>
							<TR>
							  <TD width="19%"  align="left">Charge Name  :</TD>
							  <TD width="81%"  align="left" valign="top"><label>
							  <input type="hidden" name="admNo" value="<?PHP echo $AdmNo; ?>">
							  <select name="OptCharge" onChange="javascript:setTimeout('__doPostBack(\'OptCharge\',\'\')', 0)">
								<option value="0" selected="selected">&nbsp;</option>
<?PHP							
								$query = "select chargeID from tbstudentcharges where sType = '$StuType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$chargeID = $row["chargeID"];
										//GET CHARGE ID
										$query2 = "select ChargeName from tbchargemaster where ID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
										$result2 = mysql_query($query2,$conn);
										$dbarray2 = mysql_fetch_array($result2);
										$chName  = $dbarray2['ChargeName'];
										
										if($OptCharge =="$chargeID"){
?>
											<option value="<?PHP echo $chargeID; ?>" selected="selected"><?PHP echo $chName; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $chargeID; ?>"><?PHP echo $chName; ?></option>
<?PHP
										}
									}
								}
?>
							      </select>
							    <input title="Click to edit student charges" name="Openstudentcharge" type="submit" id="Openstudentcharge" value="...">
							  </label></TD>
							</TR>
							<TR>
							  <TD width="19%"  align="left">Amount :</TD>
							  <TD width="81%"  align="left" valign="top"><input type="text" name="chargeAmount" size="15" value="<?PHP echo $chargeAmount; ?>">
							    <input title="Click to add student charges" name="Addcharges" type="submit" id="Addcharges" value=" + ">
							    <input title="Click to delete student charges" name="deletecharges" type="submit" id="deletecharges" value=" - " onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}"></TD>
							</TR>
							<TR>
								<TD colspan="2">
								<table width="403" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr>
									<td width="82" bgcolor="#F4F4F4"><div align="center"><strong>SrNo.</strong></div></td>
									<td width="214" bgcolor="#F4F4F4"><div align="center"><strong>Charge Name</strong></div></td>
									<td width="158" bgcolor="#F4F4F4"><div align="center"><strong>Charge Amoun</strong></div></td>
								  </tr>
<?PHP
									$counter_chg = 0;
									$counter = 0;
									$sumAmount = 0;
									$query3 = "select * from tbstudentsubmitedcharges where Admno = '$AdmNo' and Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
									$num_rows = mysql_num_rows($result3);
									if ($num_rows <= 0 ) {
										echo "";
									}
									else 
									{
										while ($row = mysql_fetch_array($result3)) 
										{
											$counter_chg = $counter_chg+1;
											$counter = $counter+1;
											$sChargeID = $row["ID"];
											$ChargeID = $row["ChargeID"];
											$Amount = $row["Amount"];
											$sumAmount = $sumAmount + $Amount;
											//GET CHARGE ID
											$query2 = "select ChargeName from tbchargemaster where ID = '$ChargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result2 = mysql_query($query2,$conn);
											$dbarray2 = mysql_fetch_array($result2);
											$chName  = $dbarray2['ChargeName'];
											
?>										  <tr bgcolor="#FFFFFF">
											<td><div align="center"><?PHP echo $counter; ?></div></td>
											<td><div align="center"><a href="studcharges.php?subpg=Charges and Documents&sc_id=<?PHP echo $sChargeID; ?>&admNo=<?PHP echo $AdmNo; ?>"><?PHP echo $chName; ?></a></div></td>
											<td><div align="center"><?PHP echo number_format($Amount,2); ?></div></td>
										  </tr>
<?PHP
										 }
									 }
?>
									 <tr bgcolor="#FFFFFF">
										<td><div align="center">&nbsp;</div></td>
										<td><div align="center">Total</div></td>
										<td><div align="center"><hr><?PHP echo number_format($sumAmount,2); ?><hr></div></td>
									  </tr>
								</table>
								</TD>
							</TR>
							<TR>
								<TD colspan="2"><p>&nbsp;</p>
								  <div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 11pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps">
								    <div align="center"><strong>Documents Received</strong></div>
								  </div>
								  <table width="285" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr>
									<td width="108" bgcolor="#F4F4F4"><div align="center"><strong>Tick.</strong></div></td>
									<td width="156" bgcolor="#F4F4F4"><div align="center"><strong>Document Name</strong></div></td>
								  </tr>
<?PHP
									$counter_doc = 0;
									$query2 = "select * from tbdocumentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result2 = mysql_query($query2,$conn);
									$num_rows2 = mysql_num_rows($result2);
									
									if($rstart==0){
										$counter_doc = $rstart;
									}else{
										$counter_doc = $rstart-1;
									}
									$counter = 0;
									$query3 = "select * from tbdocumentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
									$num_rows = mysql_num_rows($result3);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result3)) 
										{
											$counter_doc = $counter_doc+1;
											$counter = $counter+1;
											$DocID = $row["ID"];
											$Name = $row["Name"];
											
											//Check submitted document
											$numrows = 0;
											$chk = "";
											$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentsubmiteddoc Where AdmNo = $AdmNo' and Doc_ID = '$DocID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result4  = mysql_query($query4,$conn) or die('Error, query failed');
											$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
											$numrows = $row4['numrows'];
											if($numrows >0 ){
												$chk = "checked='checked'";
											}
											
?>										  <tr bgcolor="#FFFFFF">
											<td><div align="center"><input name="chkDocID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $DocID; ?>" <?PHP echo $chk; ?>></div></td>
											<td><div align="center"><?PHP echo $Name; ?></div></td>
										  </tr>
<?PHP
										 }
									 }
?>
								</table>								</TD>
							</TR>
						</TBODY>
						</TABLE>
					  </TD>
					</TR>
					<TR>
					 <TD colspan="2">
					 <div align="center">
						 <input type="hidden" name="admNo" value="<?PHP echo $AdmNo; ?>">
						  <input type="hidden" name="Totaldoc" value="<?PHP echo $counter; ?>">
						 <input name="AdmForm" type="submit" id="AdmForm" value="Admission Form">
						 <input name="chargesUpdate" type="submit" id="chargesUpdate" value="Update">
						 <input type="reset" name="Reset" value="Reset">
					</div>					 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}if ($SubPage == "School Leaving certificate") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="studcharges.php?subpg=School Leaving certificate">
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
				<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="24%" valign="top"  align="left">
					  		<strong>Search Criteria</strong>
							<p>
<?PHP
							if($OptSearch =="All")
							{
?>								
							<input type="radio" name="OptSearch" value="All" onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked="checked"/>All Student <input name="GetStudent" type="submit" id="GetStudent" value="Go"> 
							<?PHP }else{
								?>
								 <input type="radio" name="OptSearch" value="All"onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)"/>All Student <input name="GetStudent" type="submit" id="GetStudent" value="Go" disabled="disabled" >
                        <?PHP         
							}
                          ?>

							</p>
							<p>
<?PHP
							if($OptSearch =="Class")
							{
?>
							<input type='radio' name='OptSearch' value='Class' onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)"  checked='checked'/>Class 

							<select name="OptClass">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 order by Class_Name";
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
					    	</select> <input name="GetStudent" type="submit" id="GetStudent" value="Go">
<?PHP		               
                           }else{
?>
								<input type='radio' name='OptSearch' value='Class' onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)"/>Class <select name="OptClass">
							<option value="" disabled="disabled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option></select> <input name="GetStudent" type="submit" id="GetStudent" value="Go" disabled="disabled">
<?PHP			           }
?>
					  		</p>
							<p>
<?PHP
							if($OptSearch =="Name")
							{
?>								
								<input type='radio' name='OptSearch' value='Name' onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" checked='checked'/>Name
                                <input type="text" name="StuName" size="10" value="<?PHP echo $StuName; ?>">
							<input name="GetStudent" type="submit" id="GetStudent" value="Go">
<?PHP 
}else{
?>	
								<input type='radio' name='OptSearch' value='Name' onClick="javascript:setTimeout('__doPostBack(\'Optsearch\',\'\')', 0)" />Name
							
					  		   
							<input type="text" name="StuName" size="10" value="<?PHP echo $StuName; ?>">
							<input name="GetStudent" type="submit" id="GetStudent" value="Go" disabled = 'disabled'></p>
<?PHP                             
                            }
?>							
                            
							<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
							  <tr>
								<td width="70%" bgcolor="#F4F4F4"><div align="center" class="style21">Name</div></td>
							  </tr>
<?PHP
								$counter_stud = 0;
								$query2 = "select * from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_stud = $rstart;
								}else{
									$counter_stud = $rstart-1;
								}
								$counter = 0;
								if(isset($_POST['GetStudent']))
								{
									if($_POST['OptSearch'] == "All"){
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 LIMIT $rstart,$rend";
									}elseif($_POST['OptSearch'] == "Class"){
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 LIMIT $rstart,$rend";
									}elseif($_POST['OptSearch'] == "Name"){
										$Search_Key = $_POST['StuName'];
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 order by Stu_Full_Name";
									}
								}
								else{
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 LIMIT $rstart,$rend";
								}
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter_stud = $counter_stud+1;
										$counter = $counter+1;
										$AdmID = $row["ID"];
										$AdmissionNo = $row["AdmissionNo"];
										$Stu_Full_Name = $row["Stu_Full_Name"];
?>
										  <tr bgcolor="#666666">
											<td><div align="center"><a href="studcharges.php?subpg=School Leaving certificate&SL_admNo=<?PHP echo $AdmissionNo; ?>"><font color="#FFFFFF"><?PHP echo $Stu_Full_Name; ?></font></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
                        	</table>
							<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="studcharges.php?subpg=School Leaving certificate&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;</a>| &nbsp;<a href="studcharges.php?subpg=School Leaving certificate&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>

					  		<p>&nbsp;</p>
							</TD>
					 	 <TD width="76%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  	<TABLE width="100%">
							<TBODY>
							<TR>
							  <TD width="20%"  align="left">Admn No   :</TD>
							  <TD width="26%"  align="left" valign="top"><label>
							    <input type="text" name="AdmnNo" size="15" value="<?PHP echo $AdmnNo; ?>"  style="background-color:#FFFF99">
							  *</label></TD>
							  <TD width="21%"  align="left">No of day school opened:</TD>
							  <TD width="33%"  align="left" valign="top"><label>
							    <input name="workingday" type="text" size="5" value="<?PHP echo $workingday; ?>" style="background-color:#FFFF99">
							  </label></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Student Name :</TD>
							  <TD width="26%"  align="left" valign="top">
							  	<input type="text" name="StudentName" size="20" value="<?PHP echo $StudentName; ?>" disabled="disabled" style="background-color:#FFFF99"></TD>
							  <TD width="21%"  align="left">No. of days present: </TD>
							  <TD width="33%"  align="left" valign="top"><input name="Noofdayspresent" type="text" size="5" style="background-color:#FFFF99" value="<?PHP echo $Noofdayspresent; ?>"></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Birth Date    :</TD>
							  <TD width="26%"  align="left" valign="top">
							  <input name="DOB" style="cursor: text; background-color:#FFFF99" value="<?PHP echo $DOB; ?>" size="15" readonly="readonly"/></TD>
							  <TD width="21%"  align="left">Game Played    :</TD>
							  <TD width="33%"  align="left" valign="top"><label></label>
							    <textarea name="GamePlayed" cols="25"><?PHP echo $GamePlayed; ?></textarea></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Father Name  :</TD>
							  <TD width="26%"  align="left" valign="top"><input type="text" name="fathername" size="20" value="<?PHP echo $fathername; ?>" disabled="disabled" style="background-color:#FFFF99"></TD>
							  <TD width="21%"  align="left">Reasons for leaving: </TD>
							  <TD width="33%"  align="left" valign="top"><textarea name="Reasonsleaving" cols="25"><?PHP echo $Reasonsleaving; ?></textarea></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Admn Date    :</TD>
							  <TD width="26%"  align="left" valign="top"><label></label>
							    <input name="AdmnDate" style="cursor: text; background-color:#FFFF99" value="<?PHP echo $AdmnDate; ?>" size="15" readonly="readonly"/></TD>
							  <TD width="21%"  align="left">Date of application    :</TD>
							  <TD width="33%"  align="left" valign="top"><label></label>
							    <input name="AppDate" style="cursor: text" onClick="ds_sh(this);" value="<?PHP echo $AppDate; ?>" size="15" readonly="readonly"/></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Current Class:</TD>
							  <TD width="26%"  align="left" valign="top">
							  <select name="OptLastClass">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 order by Class_Name";
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
									if($OptLastClass ==$ClassID){
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
							  </TD>
							  <TD width="21%"  align="left">Date of issue:  </TD>
							  <TD width="33%"  align="left" valign="top">
							  <input name="IssDate" class="fil_ariane_passif" style="cursor: text" onClick="ds_sh(this);" value="<?PHP echo $IssDate; ?>" size="15" readonly="readonly"/></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Failed in any class    :</TD>
							  <TD width="26%"  align="left" valign="top"><label>
							  <select name="isFailed">
<?PHP 
								if($isFailed == "1"){
?>
							    	<option value="1" selected="selected">Yes</option>
									<option value="0">No</option>
<?PHP 
								}elseif($isFailed == "0"){
?>
                                	<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
<?PHP 
								}else{
?>	
									<option value="1">Yes</option>
									<option value="0">No</option>
<?PHP 
								}
?>
							    </select>
							  </label></TD>
							  <TD width="21%"  align="left">Conduct &amp; behaviour    :</TD>
							  <TD width="33%"  rowspan="2" align="left" valign="top"><label></label>
							    <textarea name="Conduct" cols="25" rows="4"><?PHP echo $Conduct; ?></textarea></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Promoted in last section  :</TD>
							  <TD width="26%"  align="left" valign="top">
							  <select name="ispromoted">
<?PHP 
								if($ispromoted == "1"){
?>
							    	<option value="1" selected="selected">Yes</option>
									<option value="0">No</option>
<?PHP 
								}elseif($ispromoted == "0"){
?>
                                	<option value="1">Yes</option>
									<option value="0" selected="selected">No</option>
<?PHP 
								}else{
?>	
									<option value="1">Yes</option>
									<option value="0">No</option>
<?PHP 
								}
?>
                              </select>
							  </TD>
							  <TD width="21%"  align="left">&nbsp;</TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Last due paid in month and year :</TD>
							  <TD width="26%"  align="left" valign="top"><label>
							  <select name="last_due_mth">
<?PHP 
								if(isset($_POST['last_due_mth'])){

							    	echo "<option value=$last_due_mth>$last_due_mth</option>";
								}
?>
								<option value="Jan">Jan</option>
							    <option value="Feb">Feb</option>
							    <option value="Mar">Mar</option>
							    <option value="Apr">Apr</option>
							    <option value="May">May</option>
							    <option value="Jun">Jun</option>
							    <option value="Jul">Jul</option>
							    <option value="Aug">Aug</option>
							    <option value="Sep">Sep</option>
							    <option value="Oct">Oct</option>
							    <option value="Nov">Nov</option>
							    <option value="Dec">Dec</option>
                              </select>
							  <input name="last_due_yr" type="text" size="5" value="<?PHP echo $last_due_yr; ?>" ONFOCUS="clearDefault(this)">
							  </label></TD>
							  <TD width="21%"  align="left">Other remarks    :</TD>
							  <TD width="33%"  rowspan="2" align="left" valign="top"><label></label>
							    <textarea name="remarks" cols="25" rows="4"><?PHP echo $remarks; ?></textarea></TD>
							</TR>
							<TR>
							  <TD width="20%"  align="left">Class promoted to  :</TD>
							  <TD width="26%"  align="left" valign="top">
							  <select name="OptPromotedClass">
								<option value="0" selected="selected">&nbsp;</option>
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
											if($OptPromotedClass ==$ClassID){
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
							  </TD>
							  <TD width="21%"  align="left">&nbsp;</TD>
							</TR>
							<TR>
								<TD colspan="4"><p>* Required Field 
								</p></TD>
							</TR>
						</TBODY>
						</TABLE>
						</TD>
					</TR>
					<TR>
					 <TD colspan="2">
					 <div align="center">
						 <input type="hidden" name="SL_admNo" value="<?PHP echo $SL_AdmnNo; ?>">
						 <input name="PrintForm" type="submit" id="PrintForm" value="Print cert">
						 <input name="SLUpdate" type="submit" id="SLUpdate" value="Update" onClick="if (!confirm('Warning : Once clicked on ok, automatically the student information will be deleted from the current assigned class. are you sure you want to continue!')) {return false;}">
						  <input name="SLUpdate" type="submit" id="SLDelete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						 <input type="reset" name="Reset" value="Reset">
					</div>					 </TD>
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
