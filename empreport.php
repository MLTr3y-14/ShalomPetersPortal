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
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
	}
	// filename: photo.php 

	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseEmp.processor.php?pg=Personal Details'; 
	// set a max file size for the html upload form 
	$max_file_size = 100000; // size in bytes OR 30kb
	
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Employee Report";
	$audit=update_Monitory('Login','Administrator',$Page);
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
	if(isset($_POST['OptDept']))
	{	
		$EmpCode = $_POST['SelEmpID'];
		$OptDept = $_POST['OptDept'];
		$EmpName = $_POST['EmpName'];
		$Age = $_POST['Age'];
		$Sex = $_POST['Sex'];
		$Address = $_POST['Address'];
		$OptCity = $_POST['OptCity'];
		$Citystate = $_POST['Citystate'];
		$EmpPhone = $_POST['EmpPhone'];
		$OthContact = $_POST['OthContact'];
		$OptDept = $_POST['OptDept'];
		$OptDesign = $_POST['OptDesign'];
		$Salary = $_POST['Salary'];
		
		$DOJ_Yr = $_POST['DOJ_Yr'];
		$DOJ_Mth = $_POST['DOJ_Mth'];
		$DOJ_Dy = $_POST['DOJ_Dy'];
		$DOB_Yr = $_POST['DOB_Yr'];
		$DOB_Mth = $_POST['DOB_Mth'];
		$DOB_Dy = $_POST['DOB_Dy'];
		
		$FatName = $_POST['FatName'];
		$Bank_no = $_POST['Bank_no'];
		$OptCat = $_POST['OptCat'];
		$EmpFilename = $_POST['EmpPhoto'];
		$Country = $_POST['Country'];
		$Qua = $dbarray['Qualification'];
		$Doc = $dbarray['Doc'];
	}
	if(isset($_POST['empmaster']))
	{
		$PageHasError = 0;
		$EmpCode = $_POST['SelEmpID'];
		$EmpName = formatHTMLStr($_POST['EmpName']);
		$Age = $_POST['Age'];
		$Sex = $_POST['Sex'];
		$Address = formatHTMLStr($_POST['Address']);
		$OptCity = $_POST['OptCity'];
		$Citystate = $_POST['Citystate'];
		$EmpPhone = $_POST['EmpPhone'];
		$OthContact = $_POST['OthContact'];
		$OptDept = $_POST['OptDept'];
		$OptDesign = $_POST['OptDesign'];
		$Salary = $_POST['Salary'];
		
		$DOJ_Yr = $_POST['DOJ_Yr'];
		$DOJ_Mth = $_POST['DOJ_Mth'];
		$DOJ_Dy = $_POST['DOJ_Dy'];
		$DOB_Yr = $_POST['DOB_Yr'];
		$DOB_Mth = $_POST['DOB_Mth'];
		$DOB_Dy = $_POST['DOB_Dy'];
		
		$DOJ = $_POST['DOJ_Yr']."-".$_POST['DOJ_Mth']."-".$_POST['DOJ_Dy'];
		$DOB = $_POST['DOB_Yr']."-".$_POST['DOB_Mth']."-".$_POST['DOB_Dy'];
		$FatName = formatHTMLStr($_POST['FatName']);
		$Bank_no = $_POST['Bank_no'];
		$OptCat = $_POST['OptCat'];
		$EmpFilename = $_POST['EmpPhoto'];
		$Country = $_POST['Country'];
		$isTeacher = $_POST['isTeacher'];
		if(!$_POST['isTeacher']){
			$isTeacher=0;
		}
		if(!$_POST['EmpName']){
			$errormsg = "<font color = red size = 1>Employee name is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['empmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbemployeemasters where EmpName = '$EmpName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The employee you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbemployeemasters(EmpName,EmpAge,EmpSex,EmpAddress,EmpCity,EmpState,EmpPhone,EmpOtherContact,EmpDept,EmpDesig,EmpSal,EmpDOJ,EmpDob,EmpFname,EmpBankNo,catCode,Photo,Country,isTeaching) Values ('$EmpName','$Age','$Sex','$Address','$OptCity','$Citystate','$EmpPhone','$OthContact','$OptDept','$OptDesign','$Salary','$DOJ','$DOB','$FatName','$Bank_no','$OptCat','$EmpFilename','$Country','$isTeacher')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$EmpName = "";
					$Age = "";
					$Sex = "";
					$Address = "";
					$OptCity = "";
					$Citystate = "";
					$EmpPhone = "";
					$OthContact = "";
					$OptDept = "";
					$OptDesign = "";
					$Salary = "";
					$DOJ = "";
					$DOB = "";
					$FatName = "";
					$Bank_no = "";
					$OptCat = "";
					$EmpFilename = "empty_r2_c2.jpg";
					$Country = "";
					$isTeacher = "";
				}
			}elseif ($_POST['empmaster'] =="Update"){
				$q = "update tbemployeemasters set EmpName='$EmpName',EmpAge='$Age',EmpSex='$Sex',EmpAddress='$Address',EmpCity='$OptCity',EmpState='$Citystate',EmpPhone='$EmpPhone',EmpOtherContact='$OthContact',EmpDOJ='$DOJ',EmpDob='$DOB',EmpFname='$FatName',EmpBankNo='$Bank_no',Photo='$EmpFilename',Country='$Country' where ID = '$EmpCode'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$EmpName = "";
				$Age = "";
				$Sex = "";
				$Address = "";
				$OptCity = "";
				$Citystate = "";
				$EmpPhone = "";
				$OthContact = "";
				$OptDept = "";
				$OptDesign = "";
				$Salary = "";
				$DOJ = "";
				$DOB = "";
				$FatName = "";
				$Bank_no = "";
				$OptCat = "";
				$isTeacher = "";
				$EmpFilename = "empty_r2_c2.jpg";
				$Country = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($userNames))
	{
		$query = "select EmpID from tbusermaster where UserName='$userNames'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpCode  = $dbarray['EmpID'];

		$query = "select * from tbemployeemasters where ID='$EmpCode'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpName  = formatDatabaseStr($dbarray['EmpName']);
		$Age  = $dbarray['EmpAge'];
		$Sex  = $dbarray['EmpSex'];
		$Address  = formatDatabaseStr($dbarray['EmpAddress']);
		$OptCity  = $dbarray['EmpCity'];
		$Citystate  = $dbarray['EmpState'];
		$EmpPhone  = $dbarray['EmpPhone'];
		$OthContact  = $dbarray['EmpOtherContact'];
		$OptDept  = $dbarray['EmpDept'];
		$OptDesign  = $dbarray['EmpDesig'];
		$Salary  = $dbarray['EmpSal'];
		$Qua = $dbarray['Qualification'];
		$Doc = $dbarray['Doc'];

		$arrDate=explode ('-', $dbarray['EmpDOJ']);
		$DOJ_Dy = $arrDate[2];
		$DOJ_Mth = $arrDate[1];
		$DOJ_Yr = $arrDate[0];
		
		$arrDate2=explode ('-', $dbarray['EmpDob']);
		$DOB_Dy = $arrDate2[2];
		$DOB_Mth = $arrDate2[1];
		$DOB_Yr = $arrDate2[0];

		$FatName  = formatDatabaseStr($dbarray['EmpFname']);
		$Bank_no  = $dbarray['EmpBankNo'];
		$OptCat  = $dbarray['catCode'];
		if($EmpFilename =="" Or $EmpFilename =="empty_r2_c2.jpg"){
			$EmpFilename  = $dbarray['Photo'];
		}
		$Country  = $dbarray['Country'];
		
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['ischecked']))
	{	
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}elseif($ischecked =="Teacher"){
			$chkTeacher = "checked='checked'";
		}
	}
	if(isset($_POST['ischecked1']))
	{	
		$ischecked = $_POST['ischecked1'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}elseif($ischecked =="Allemployee"){
			$chkAllemployee = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}
		elseif($ischecked =="StaffName"){
			$chkTeacherName = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}
		elseif($ischecked =="Staff"){
			$chkTeacher = "checked='checked'";
		}
	}
	if(isset($_POST['SubmitPrint']))
	{
		$OptEmployeeName = $_POST['selectemployee2'];
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}elseif($ischecked =="Teacher"){
			$chkTeacher = "checked='checked'";
		}
		if(!isset($_POST['ischecked']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria</font>";
			$PageHasError = 1;
		}
		if($ischecked == "Teacher"){
			$OptEmployeeName = $_POST['selectemployee2'];
			if($_POST['selectemployee2'] == "" or $_POST['selectemployee2'] == "Select Employee Name" )
			{
				$errormsg = "<font color = red size = 1>Please Select Employee Name</font>";
				$PageHasError = 1;
			}
		}				
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=employee_rpt.php?pg=Teacher subject&ty=$ischecked&tch=$OptEmployeeName\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrintDetails']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}elseif($ischecked =="Staff"){
			$chkTeacher = "checked='checked'";
		}
		if(!isset($_POST['ischecked']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria</font>";
			$PageHasError = 1;
		}
		if($ischecked == "Staff"){
			$OptTeacher = $_POST['OptTeacher'];
			if($_POST['OptTeacher'] =="0")
			{
				$errormsg = "<font color = red size = 1>Select Teacher</font>";
				$PageHasError = 1;
			}
		}				
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=employee_rpt.php?pg=Staff Details&typ=$ischecked&stf=$OptTeacher\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrintDesignation']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked1'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}elseif($ischecked =="Allemployee"){
			$chkAllemployee = "checked='checked'";
			$lockTeacher = "disabled='disabled'";
		}
		elseif($ischecked =="Staff"){
			$chkTeacher = "checked='checked'";
		}
		elseif($ischecked =="StaffName"){
			$chkTeacherName = "checked='checked'";
		}
		if(!isset($_POST['ischecked1']))
		{
			$errormsg = "<font color = red size = 1>Please Select Search Criteria</font>";
			$PageHasError = 1;
		}
		if($ischecked =="Allemployee"){
			$OptDepartment = $_POST['selectdept2'];
			$OptEmployeeName = $_POST['employeename3'];
			 if ($PageHasError == 0)
		      {
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=employee_rpt.php?pg=All Employee Department and Designation Details&typ=$ischecked&stf=$OptEmployeeName&dpt=$OptDepartment\">";
			exit;
		          }
		}
		if($ischecked =="StaffName"){
			//$OptDepartment = $_POST['selectdept2'];
			$OptEmployeeName = $_POST['selectemployee2'];
			if($_POST['selectemployee2'] == "" or $_POST['selectemployee2'] == "Select Employee Name" )
			{
				$errormsg = "<font color = red size = 1>Please Select Employee Name</font>";
				$PageHasError = 1;
			}
			 if ($PageHasError == 0)
		      {
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=employee_rpt.php?pg=Employee Department and Designation Details&typ=$ischecked&stf=$OptEmployeeName&dpt=$OptDepartment\">";
			exit;
		          }
		}
		if($ischecked == "Staff"){
			$OptDepartment = $_POST['selectdept2'];
			$OptEmployeeName = $_POST['employeename3'];
			if($_POST['selectdept2'] == "")
			{
				$errormsg = "<font color = red size = 1>Please Select Department</font>";
				$PageHasError = 1;
			}
			elseif($_POST['employeename3'] == "" or $_POST['employeename3'] == "Select Employee Name" )
			{
				$errormsg = "<font color = red size = 1>Please Select Employee Name</font>";
				$PageHasError = 1;
			}
			   if ($PageHasError == 0)
		      {
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=employee_rpt.php?pg=Employee Department and Designation Details&typ=$ischecked&stf=$OptEmployeeName&dpt=$OptDepartment\">";
			exit;
		          }
		}				
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=employee_rpt.php?pg=Department And Designation Wise details&typ=$ischecked&stf=$OptTeacher\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrintDuty']))
	{
		$FromDate = $_POST['from_Yr']."-".$_POST['from_Mth']."-".$_POST['from_Dy'];
		$from_Yr = $_POST['from_Yr'];
		$from_Mth = $_POST['from_Mth'];
		$from_Dy = $_POST['from_Dy'];
		if($FromDate=="--"){
			$errormsg = "<font color = red size = 1>From Date is empty</font>";
			$PageHasError = 1;
		}
		
		$ToDate = $_POST['to_Yr']."-".$_POST['to_Mth']."-".$_POST['to_Dy'];
		$to_Yr = $_POST['to_Yr'];
		$to_Mth = $_POST['to_Mth'];
		$to_Dy = $_POST['to_Dy'];
		
		if($ToDate=="--"){
			$errormsg = "<font color = red size = 1>To Date is empty</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=employee_rpt.php?pg=Teacher Duty&fdt=$FromDate&tdt=$ToDate\">";
			exit;
		}
	}
	
$dsn = "mysql:host=localhost;dbname=skoolnet";
$username = "root";

$password = "tingate200";

try {

    $pdo = new PDO($dsn, $username, $password);

}

catch(PDOException $e) {

    die("Could not connect to the database\n");

}
$stmt = $pdo->prepare("SELECT * FROM tbdepartments");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$vardept = json_encode($json_row);

$stmt = $pdo->prepare("SELECT * FROM tbemployeemasters");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varemployee = json_encode($json_row);
//echo $varteacher;

$stmt = $pdo->prepare("SELECT * FROM tbemployeemasters where isTeaching = '1'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varemployee2 = json_encode($json_row);

// do something else
//echo $vardept;
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
<SCRIPT 
src="js/json/json2.js" 
type=text/JavaScript></SCRIPT>

 <link rel="stylesheet" type="text/css" href="dojoroot/dijit/themes/tundra/tundra.css"
        />
 <script type="text/javascript" src="dojoroot/dojo/dojo.js"
    djConfig="parseOnLoad: true"></script>

 <!-- SECTION 2 -->
  <script type="text/javascript">
     // Load Dojo's code relating to the Button widget
     dojo.require("dijit.form.Button");
 </script>
  <script type="text/javascript">
    dojo.require("dijit.form.CheckBox");
</script>
<script type="text/javascript">
    dojo.require("dijit.form.ComboBox");
	dojo.require("dijit.form.FilteringSelect");
    dojo.require("dojo.data.ItemFileReadStore");
	dojo.require("dijit.form.Form");
	dojo.require("dijit.Dialog");
    dojo.require("dijit.form.TextBox");
    dojo.require("dijit.form.DateTextBox");
    dojo.require("dijit.form.TimeTextBox");
    dojo.require("dijit.form.ComboBox");
	dojo.require("dijit.form.Button");
</script>

  <script type="text/javascript">
 
dojo.addOnLoad(function() {
		formDlg = dijit.byId("formDialog");					
        
 var dataJson = <?php echo $vardept; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson
            }),
            //autoComplete: true,
			searchAttr: "DeptName",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectdept",
            onChange: function setstudent(){
	       // alert('im good');
		    setemployee2();
	        setemployee();       },
                   
		    },"selectdept");
		
		
var dataJson2 = <?php echo $varemployee; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson2
            }),
            //autoComplete: true,
			searchAttr: "EmpName",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectemployee",
            onChange: function setemployee(){
	       // alert('im good');
	        setemployeeinput();     
			                          },
                   
		    },"selectemployee");
		
		var dataJson2 = <?php echo $varemployee2; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson2
            }),
            //autoComplete: true,
			searchAttr: "EmpName",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectemployee3",
            onChange: function setemployee(){
	       // alert('im good');
	        setemployeeinput();     
			                          },
                   
		    },"selectemployee3");
		
	});	
</script>

</HEAD>
<BODY class="tundra" style="TEXT-ALIGN: center" background=Images/news-background.jpg>
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
		if ($SubPage == "Personal Details") {
?>
				<?PHP echo $errormsg; ?>
				<form id="Upload2" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post"> 
				  <TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
					<TBODY>
					<TR bgcolor="#CCCCCC">
						<TD colspan="2" align="left"><input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"></TD>
					</TR>
					<TR>
						<TD width=13%><label for="file">
						  <div align="left">Select  Picture:</div>
						  </label>  </TD>
						<TD width=87% align="left"><input id="file" type="file" name="file">
						  <input id="submit" type="submit" name="submit" value="Upload"></TD>
					</TR>
				  </TBODY></TABLE>	
				</form>
				<form name="form1" method="post" action="empreport.php?subpg=Personal Details">
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
					  <TD width="58%" valign="top"  align="left">
					  	<TABLE width="100%" style="WIDTH: 100%"  border="0">
							<TBODY>
							<TR>
							  <TD width="37%" valign="top"  align="left">Date of Joining</TD>
							  <TD width="63%" valign="top"  align="left">Dy:
							    <label>
							    <select name="DOJ_Dy">
<?PHP
									for($i=1; $i<=31; $i++){
										if($DOJ_Dy == $i){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
							      </select>
							    Mth
							    <select name="DOJ_Mth">
<?PHP
									for($i=1; $i<=12; $i++){
										if($DOJ_Mth == $i){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
                                </select> 
							    Yr
							    <select name="DOJ_Yr">
<?PHP
									$CurYear = date('Y');
									for($i=1990; $i<=$CurYear; $i++){
										if($DOJ_Yr == $i){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
                                </select> 
							    </label></TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Name</TD>
							  <TD width="63%" valign="top"  align="left">
							    <input name="EmpName" type="text" size="25" value="<?PHP echo $EmpName; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Date of Birth</TD>
							  <TD width="63%" valign="top"  align="left">Dy:
							    <label>
							    <select name="DOB_Dy">
<?PHP
									for($i=1; $i<=31; $i++){
										if($DOB_Dy == $i){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
							      </select>
							    Mth
							    <select name="DOB_Mth">
<?PHP
									for($i=1; $i<=12; $i++){
										if($DOB_Mth == $i){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
                                </select> 
							    Yr
							    <select name="DOB_Yr">
<?PHP
									for($i=1950; $i<=1999; $i++){
										if($DOB_Yr == $i){
											echo "<option value=$i selected=selected>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
                                </select> 
							    </label></TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Age </TD>
							  <TD width="63%" valign="top"  align="left">
							    <input name="Age" type="text" size="5" value="<?PHP echo $Age; ?>">
							    Sex							      
							    <select name="Sex">
<?PHP
									if($Sex == "M"){
?>
										<option value="<?PHP echo $Sex; ?>" selected="selected">Male</option>
										<option value="F">Female</option>
<?PHP
									}elseif($Sex == "F"){
?>
										<option value="M">Male</option>
										<option value="<?PHP echo $Sex; ?>" selected="selected">Female</option>
<?PHP
									}else{
?>
										<option value="M">Male</option>
                                  		<option value="F">Female</option>
<?PHP
									}
?>
                                </select></TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Father's / Spouse Name</TD>
							  <TD width="63%" valign="top"  align="left">
							    <input name="FatName" type="text" size="25" value="<?PHP echo $FatName; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Address</TD>
							  <TD width="63%" valign="top"  align="left">
							    <textarea name="Address" cols="30" rows="3"><?PHP echo $Address; ?></textarea>
							  </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">City</TD>
							  <TD width="63%" valign="top"  align="left">
								<select name="OptCity">
									<option value="" selected="selected">Select</option>
<?PHP
									$query = "select ID,CityName from mcity order by CityName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows <= 0 ) {
										echo "";
									}else{
										while ($row = mysql_fetch_array($result)) 
										{
											$CityID = $row["ID"];
											$CityName = $row["CityName"];
											if($OptCity =="$CityID"){
?>
												<option value="<?PHP echo $CityID; ?>" selected="selected"><?PHP echo $CityName; ?></option>
<?PHP
											}else{
?>
												<option value="<?PHP echo $CityID; ?>"><?PHP echo $CityName; ?></option>
<?PHP
											}
										}
									}
?>
						  </select>
							  </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">State</TD>
							  <TD width="63%" valign="top"  align="left">
								<SELECT name="Citystate">
								<OPTION value="">Choose State</OPTION>
<?PHP
								if($Citystate != ""){
?>
									<option value="<?PHP echo $Citystate; ?>" selected="selected"><?PHP echo $Citystate; ?></option>
<?PHP
								}
?>
								<OPTION value="Abia">Abia</OPTION>
								<OPTION value="Adamawa">Adamawa</OPTION>
								<OPTION value="Akwa Ibom">Akwa Ibom</OPTION>
								<OPTION value="Anambra">Anambra</OPTION>
								<OPTION value="Bauchi">Bauchi</OPTION>
								<OPTION value="Bayelsa">Bayelsa</OPTION>
								<OPTION value="Benue">Benue</OPTION>
								<OPTION value="Borno">Borno</OPTION>
								<OPTION value="Cross River">Cross River</OPTION>
								<OPTION value="Delta">Delta</OPTION>
								<OPTION value="Ebonyi">Ebonyi</OPTION>
								<OPTION value="Edo">Edo</OPTION>
								<OPTION value="Ekiti">Ekiti</OPTION>
								<OPTION value="Enugu">Enugu</OPTION>
								<OPTION value="Abuja">Abuja</OPTION>
								<OPTION value="Gombe">Gombe</OPTION>
								<OPTION value="Imo">Imo</OPTION>
								<OPTION value="Jigawa">Jigawa</OPTION>
								<OPTION value="Kaduna">Kaduna</OPTION>
								<OPTION value="Kano">Kano</OPTION>
								<OPTION value="Katsina">Katsina</OPTION>
								<OPTION value="Kebbi">Kebbi</OPTION>
								<OPTION value="Kogi">Kogi</OPTION>
								<OPTION value="Kwara">Kwara</OPTION>
								<OPTION value="Lagos">Lagos</OPTION>
								<OPTION value="Nassarawa">Nassarawa</OPTION>
								<OPTION value="Niger">Niger</OPTION>
								<OPTION value="Ogun">Ogun</OPTION>
								<OPTION value="Ondo">Ondo</OPTION>
								<OPTION value="Osun">Osun</OPTION>
								<OPTION value="Oyo">Oyo</OPTION>
								<OPTION value="Plateau">Plateau</OPTION>
								<OPTION value="Rivers">Rivers</OPTION>
								<OPTION value="Sokoto">Sokoto</OPTION>
								<OPTION value="Taraba">Taraba</OPTION>
								<OPTION value="Yobe">Yobe</OPTION>
								<OPTION value="Zamfara">Zamfara</OPTION>
							</SELECT>
							  </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Country</TD>
							  <TD width="63%" valign="top"  align="left">
							    <input name="Country" type="text" size="25" value="<?PHP echo $Country; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Phone</TD>
							  <TD width="63%" valign="top"  align="left">
							    <input name="EmpPhone" type="text" size="25" value="<?PHP echo $EmpPhone; ?>">
							 </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Other Contact</TD>
							  <TD width="63%" valign="top"  align="left">
							    <input name="OthContact" type="text" size="25" value="<?PHP echo $OthContact; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left">Bank A/C No</TD>
							  <TD width="63%" valign="top"  align="left">
							    <input name="Bank_no" type="text" size="25" value="<?PHP echo $Bank_no; ?>">
							 </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Qualification</TD>
							  <TD width="59%" valign="top"  align="left">
							    <textarea name="Qua" cols="35" rows="3" disabled="disabled"><?PHP echo $Qua; ?></textarea>
							 </TD>
							 <TR>
							  <TD width="41%" valign="top"  align="left">Document Submitted by Employee </TD>
							  <TD width="59%" valign="top"  align="left">
							    <textarea name="Doc" cols="35" rows="3" disabled="disabled"><?PHP echo $Doc; ?></textarea>
							 </TD>
							</TR>
							<TR>
							  <TD width="37%" valign="top"  align="left"> Employee Salary (=N=):</TD>
							  <TD width="63%" valign="top"  align="left"><input name="Salary" type="text" size="5" value="<?PHP echo $Salary; ?>" disabled="disabled"></TD>
							</TR>
						</TBODY>
						</TABLE>
					  </TD>
					  <TD width="42%" valign="top"  align="left" style="BORDER-LEFT: #cccccc 1px solid;">
					  	<table width="221" border="0" align="center" cellpadding="0" cellspacing="0">
						  <tr>
						   <td><img src="images/spacer.gif" width="21" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="178" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="22" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
						  </tr>
						
						  <tr>
						   <td colspan="3"><img name="empty_r1_c1" src="images/empty_r1_c1.jpg" width="221" height="20" border="0" id="empty_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="empty_r2_c1" src="images/empty_r2_c1.jpg" width="21" height="197" border="0" id="empty_r2_c1" alt="" /></td>
						   <td><input type="hidden" name="EmpPhoto" value="<?PHP echo $EmpFilename; ?>"><img src="images/uploads/<?PHP echo $EmpFilename; ?>" width="178" height="175"></td>
						   <td rowspan="2"><img name="empty_r2_c3" src="images/empty_r2_c3.jpg" width="22" height="197" border="0" id="empty_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="175" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="empty_r3_c2" src="images/empty_r3_c2.jpg" width="178" height="22" border="0" id="empty_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="22" border="0" alt="" /></td>
						  </tr>
						</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2" align="left">
						 Dept : 
						  <select name="OptDept" onChange="javascript:setTimeout('__doPostBack(\'OptDept\',\'\')', 0)" disabled="disabled">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,DeptName from tbdepartments order by DeptName";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result)) 
								{
									$DeptID = $row["ID"];
									$DeptName = $row["DeptName"];
									if($OptDept ==$DeptID){
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
                           </select>
						  *
						   &nbsp;&nbsp;&nbsp;&nbsp;
						 Design : 
						   <select name="OptDesign"  disabled="disabled">
						   <option value="0" selected="selected">&nbsp;</option>
<?PHP
							
							$query = "select ID,DesignName from tbdesignations where DeptId = '$OptDept' order by DesignName";
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
                           </select>&nbsp;&nbsp;&nbsp;&nbsp;
						 Category : 
						   <select name="OptCat"  disabled="disabled">
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
                           </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
							 <input type="hidden" name="Totalemp" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelEmpID" value="<?PHP echo $EmpCode; ?>">
							 <input name="empmaster" type="submit" id="empmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
					</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Teachers' subject") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="empreport.php?subpg=Teachers' subject">
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
					 function setemployeeinput(){
							 var empname = dijit.byId("selectemployee3").value;
							 
							  var selectemp = document.getElementById('selectemployee2');
				     selectemp.value = empname;
						 }
						 
					function WebForm_OnSubmit() {
					if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
					return true;
					}
					// -->
				</script>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="54%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="503" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
						 <label><input name="ischecked" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAll; ?>>
                            </label>
                              All Teacher 
<?PHP
						}
?>
                              </td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked" type="radio" value="Teacher" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkTeacher; ?>> 
                              Select Teacher: <?PHP if($_POST['ischecked'] == "Teacher"){?>
							  <input id="selectemployee3"> 
                              <?PHP } ?> 
                              
							  
							  </td>
                          </tr>
						  <tr>
                            <td width="324">&nbsp;</td>
							</tr>
                        </table>
					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrint" value="Print">
                              <input type="hidden" name="selectemployee2" id="selectemployee2">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Teachers' Duty") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="empreport.php?subpg=Teacher Duty">
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
					  <TD width="24%"  align="right" valign="top"><p><strong>Duty Date Range </strong></p></TD>
					  <TD width="76%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="24%"  align="right" valign="top">From</TD>
					  <TD width="76%" valign="top"  align="left">
					  <select name="from_Dy" style="width:45px;">
						  <option value="" selected="selected">Day</option>
						  
<?PHP
							for($i=1; $i<=31; $i++){
								if($from_Dy == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						<select name="from_Mth">
						   <option value="" selected="selected">Month</option>
<?PHP
								for($i=1; $i<=12; $i++){
									if($i == $from_Mth){
										echo "<option value=$i selected='selected'>$i</option>";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
						</select>
						<select name="from_Yr">
						  <option value="" selected="selected">Year</option>
<?PHP
							$CurYear = date('Y');
							for($i=2009; $i<=$CurYear; $i++){
								if($from_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
					   &nbsp; &nbsp;To&nbsp;&nbsp;
					   <select name="to_Dy" style="width:45px;">
						  <option value="" selected="selected">Day</option>
						  
<?PHP
							for($i=1; $i<=31; $i++){
								if($to_Dy == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						<select name="to_Mth">
						   <option value="" selected="selected">Month</option>
<?PHP
								for($i=1; $i<=12; $i++){
									if($i == $to_Mth){
										echo "<option value=$i selected='selected'>$i</option>";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
						</select>
						<select name="to_Yr">
						  <option value="" selected="selected">Year</option>
<?PHP
							$CurYear = date('Y');
							for($i=2009; $i<=$CurYear; $i++){
								if($to_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
						</select>
						</p>						</TD>
					</TR>
					<TR>
							<TD colspan="2">
							<div align="center">
							  <p>&nbsp;							    </p>
							  <p>
							    <input type="submit" name="SubmitPrintDuty" value="Print">
							      </p>
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Employee Remarks") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="empreport.php?subpg=Employee Remarks">
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
					  <TD width="54%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="503" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
						 <label><input name="ischecked" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAll; ?>>
                            </label>
                              All Staff 
<?PHP
						}
?>
                              </td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked" type="radio" value="Staff" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkTeacher; ?>> 
                              Select Staff: 
							    <select name="OptTeacher" <?PHP echo $lockTeacher; ?>>
<?PHP		
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,EmpName from tbemployeemasters where ID = '$Teacher_EmpID'";
								}else{
									$query = "select ID,EmpName from tbemployeemasters order by EmpName";
								}
								
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$EmpID = $row["ID"];
										$EmpName = $row["EmpName"];
										
										if($OptTeacher =="$EmpID"){
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
							  
							  </td>
                          </tr>
						  <tr>
                            <td width="324">&nbsp;</td>
							</tr>
                        </table>
					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrintDetails" value="Print">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Department And Designation Wise details") {
?>               
<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="empreport.php?subpg=Department And Designation Wise details">
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
					
					function setemployee(){
	                         // alert('im good');
	                         
							 dojo.xhrGet({
                                  url: 'selectemployee.php',
		                          handleAs: 'json',
                                  load: setemployeename,
                                  error: helloError,
                                  content: {name1: dijit.byId('selectdept').attr("value")}
                                });
							  
	                      }
						 function setemployee2(){
							 var deptname = dijit.byId("selectdept").value;
							 
							  var selectdept = document.getElementById('selectdept2');
				     selectdept.value = deptname;
						 }
						  function setemployeeinput(){
							 var empname = dijit.byId("selectemployee").value;
							 
							  var selectemp = document.getElementById('selectemployee2');
				     selectemp.value = empname;
						 }
						  
			function setemployeename(data,ioArgs){
				//alert('im good');
		var EmployeeName = document.getElementById('employeename2');
		  EmployeeName.innerHTML = "";
		 var employeenamelength = data.employeename.length;
		  var employeenameselect ='<select id = "employeename3" name = "employeename3" onchange = "setstudentfee();" ><option >Select Employee Name</option>';
		 for ( var i = 0; i < employeenamelength; i++ ){
			 var employeename = data.employeename[i];
			 employeenameselect += '<option >' + employeename + '</option>';		
			 }
			 employeenameselect+='</select>';
		
		 EmployeeName.innerHTML = employeenameselect; 	  
		 
		  // var studentname = '';
			//var studentnametxt = dijit.byId("studentname"); 
		    // studentnametxt.attr("value",studentname);
		 }
								  
			function helloError(data,ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }
					// -->
				</script>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="54%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="604" height="161" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="530">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
						 <label><input name="ischecked1" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked1\',\'\')', 0)" <?PHP echo $chkAll; ?>>
                            </label>
                              All Department And Designation Definition
<?PHP
						}
?>
                              </td>
                          </tr>
                          <tr><td><label><input name="ischecked1" type="radio" value="Allemployee" onClick="javascript:setTimeout('__doPostBack(\'ischecked1\',\'\')', 0)" <?PHP echo $chkAllemployee; ?>>
                            </label>
                              All Employee Department And Designation</td></tr>
                            <tr>
                            <td width="530">
                            <input name="ischecked1" type="radio" value="StaffName" onClick="javascript:setTimeout('__doPostBack(\'ischecked1\',\'\')', 0)" <?PHP echo $chkTeacherName; ?>> 
                              View Specific Employee Department And Designation:<?PHP if($_POST['ischecked1'] == "StaffName"){?> <B>EMPLOYEE NAME</B> 
							  <input id="selectemployee" name="selectemployee" >
                              <?PHP }else{ 
							  }?>
							  </td>
                          </tr>
						  <tr>
                            <td width="530">
                            <input name="ischecked1" type="radio" value="Staff" onClick="javascript:setTimeout('__doPostBack(\'ischecked1\',\'\')', 0)" <?PHP echo $chkTeacher; ?>> 
                              View Specific Employee Designation:<?PHP if($_POST['ischecked1'] == "Staff"){?> <B>DEPARTMENT</B> 
							  <input id="selectdept" name="selectdept" >
                              <?PHP }else{ 
							  }?>
							  </td>
                          </tr>
						  <tr>
                            <td width="530" align="right"><div id="employeename2"> </div></td>
							</tr>
                        </table>
					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrintDesignation" value="Print">
                              <input type="hidden" name="selectdept2" id="selectdept2">
                              <input type="hidden" name="selectemployee2" id="selectemployee2">
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
