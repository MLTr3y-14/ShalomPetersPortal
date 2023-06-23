<?PHP
	session_start();
	
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'library/dsndatabase.php';
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
	
	
	$Page = "Pay Roll";
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
		$audit=update_Monitory('Login','Teacher',$Page);
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
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
	// filename: photo.php 

	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseEmp.processor.php?pg=Employee Master'; 
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
	
	/*if(isset($_GET['filename']))
	{
		$EmpFilename = $_GET['filename'];
	}else{
		$EmpFilename = "empty_r2_c2.jpg";
	}*/
	$editkey = false;
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_GET['errormsg'])){
		$errormsg = $_GET['errormsg'];
	}
	if(isset($_GET['desig_ID']))
	{
		$optDepartment = $_GET['desig_ID'];
	}
	if(isset($_POST['deptmaster']))
	{
		$PageHasError = 0;
		$DeptID = $_POST['SelDeptID'];
		$DepartName = $_POST['DepartName'];
		
		if(!$_POST['DepartName']){
			$errormsg = "<font color = red size = 1>Department Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['deptmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbdepartments where DeptName = '$DepartName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The department you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbdepartments(DeptName) Values ('$DepartName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$DepartName = "";
				}
			}elseif ($_POST['deptmaster'] =="Update"){
				$q = "update tbdepartments set DeptName = '$DepartName' where ID = '$DeptID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$DeptID = "";
				$DepartName = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['dept_id']))
	{
		$DeptID = $_GET['dept_id'];
		$query = "select * from tbdepartments where ID='$DeptID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$DepartName  = $dbarray['DeptName'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['deptmaster_delete']))
	{
		$Total = $_POST['Totaldept'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkDeptID'.$i]))
			{
				$DeptIDs = $_POST['chkDeptID'.$i];
				$q = "Delete From tbdepartments where ID = '$DeptIDs'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['optDepartment']))
	{	
		$optDepartment = $_POST['optDepartment'];
	}
	if(isset($_POST['desigmaster']))
	{
		$PageHasError = 0;
		$DesigID = $_POST['SelDesigID'];
		$Designation = $_POST['Designation'];
		$departID = $_POST['departID'];
		
		if(!$_POST['Designation']){
			$errormsg = "<font color = red size = 1>Designation Name is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['departID']){
			$errormsg = "<font color = red size = 1>Department not selected.</font>";
			$PageHasError = 1;
		}
		//Check if the selected book has been issued to student
		$numrows = 0;
		$query4   = "SELECT COUNT(*) AS numrows FROM tbdesignations Where DesignName = '$Designation' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result4  = mysql_query($query4,$conn) or die('Error, query failed');
		$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
		$rsNo = $row4['numrows'];
		if($rsNo > 0){
			$errormsg = "<font color = red size = 1>Only unique designation name are allowed, the name already exist in another department.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['desigmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbdesignations where DesignName = '$Designation' And DeptId = '$departID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The designation you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbdesignations(DesignName,DeptId,Session_ID,Term_ID) Values ('$Designation','$departID','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$Designation = "";
					$departID = "";
				}
			}elseif ($_POST['desigmaster'] =="Update"){
				$q = "update tbdesignations set DesignName = '$Designation',DeptId = '$departID' where ID = '$DesigID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$DesigID = "";
				$departID = "";
				$Designation = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['desig_id']))
	{
		$DesigID = $_GET['desig_id'];
		$query = "select * from tbdesignations where ID='$DesigID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Designation  = $dbarray['DesignName'];
		$optDepartment  = $dbarray['DeptId'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['desigmaster_delete']))
	{
		$Total = $_POST['Totaldesig'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkDesigID'.$i]))
			{
				$chkDesigIDs = $_POST['chkDesigID'.$i];
				$q = "Delete From tbdesignations where ID = '$chkDesigIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	
	if(isset($_POST['catmaster']))
	{
		$PageHasError = 0;
		$EmpCatID = $_POST['SelCatID'];
		$CatName = $_POST['CatName'];
		$CatDescription = $_POST['CatDescription'];
		
		if(!$_POST['CatName']){
			$errormsg = "<font color = red size = 1>Name is empty.</font>";
			$PageHasError = 1;
		}

		if ($PageHasError == 0)
		{
			if ($_POST['catmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbcategorymaster where cName = '$CatName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The category you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbcategorymaster(cName,cDesc,Session_ID,Term_ID) Values ('$CatName','$CatDescription','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CatName = "";
					$CatDescription = "";
				}
			}elseif ($_POST['catmaster'] =="Update"){
				$q = "update tbcategorymaster set cName = '$CatName',cDesc = '$CatDescription' where ID = '$EmpCatID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$EmpCatID = "";
				$CatName = "";
				$CatDescription = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['cat_id']))
	{
		$EmpCatID = $_GET['cat_id'];
		$query = "select * from tbcategorymaster where ID='$EmpCatID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CatName  = $dbarray['cName'];
		$CatDescription  = $dbarray['cDesc'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['catmaster_delete']))
	{
		$Total = $_POST['Totalcat'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCatID'.$i]))
			{
				$chkCatIDs = $_POST['chkCatID'.$i];
				$q = "Delete From tbcategorymaster where ID = '$chkCatIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
				$result = mysql_query($q,$conn);
			}
		}
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
		$isTeacher = $_POST['isTeacher'];
		$Qua = $_POST['Qua'];
		$Doc = $_POST['Doc'];
		$EmpFilename = $_POST['EmpPhoto'];
		$Country = $_POST['Country'];
	}
	if(isset($_POST['empmaster']))
	{
		$PageHasError = 0;
		$EmpCode = $_POST['SelEmpID'];
		$EmpName = $_POST['EmpName'];
		$Age = $_POST['Age'];
		$Sex = $_POST['Sex'];
		$Address = $_POST['Address'];
		$OptCity = $_POST['OptCity'];
		$Citystate = $_POST['Citystate'];
		$EmpPhone = $_POST['EmpPhone'];
		$OthContact = $_POST['OthContact'];
		$OptDept = $_POST['OptDept'];
		//$OptDesign = $_POST['OptDesign'];
		$Salary = $_POST['Salary'];
		
		$DOJ_Yr = $_POST['DOJ_Yr'];
		$DOJ_Mth = $_POST['DOJ_Mth'];
		$DOJ_Dy = $_POST['DOJ_Dy'];
		$DOB_Yr = $_POST['DOB_Yr'];
		$DOB_Mth = $_POST['DOB_Mth'];
		$DOB_Dy = $_POST['DOB_Dy'];
		
		$DOJ = $_POST['DOJ_Yr']."-".$_POST['DOJ_Mth']."-".$_POST['DOJ_Dy'];
		$DOB = $_POST['DOB_Yr']."-".$_POST['DOB_Mth']."-".$_POST['DOB_Dy'];
		$FatName = $_POST['FatName'];
		$Bank_no = $_POST['Bank_no'];
		$OptCat = $_POST['OptCat'];
		$Country = $_POST['Country'];
		$isTeacher = $_POST['isTeacher'];
		$Qua = $_POST['Qua'];
		$Doc = $_POST['Doc'];
		
		
		/*if($picstatus==0){
			$errormsg = "<font color = red size = 1>Please upload employee picture.</font>";
			$PageHasError = 1;
		}*/
		if(!$_POST['OptDept']){
			$errormsg = "<font color = red size = 1>Department Name is empty.</font>";
			$PageHasError = 1;
		}
		elseif(!is_numeric($Salary)){
			$errormsg = "<font color = red size = 1>Employee salary should be in numbers.</font>";
			$errormsg = $content;
			$PageHasError = 1;
		}
		
		if(!$_POST['EmpName']){
			$errormsg = "<font color = red size = 1>Employee name is empty.</font>";
			$PageHasError = 1;
		}
		
		elseif ($PageHasError == 0)
		{
			if ($_POST['empmaster'] =="Accept New Employee Details"){
				$num_rows = 0;
				$query = "select ID from tbemployeemasters where EmpName = '$EmpName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The employee you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else 
				     {
					$query = "Insert into tbemployeemasters(EmpName,EmpAge,EmpSex,EmpAddress,EmpCity,EmpState,EmpPhone,EmpOtherContact,EmpDept,EmpSal,EmpDOJ,EmpDob,EmpFname,EmpBankNo,catCode,Country,isTeaching,Qualification,Doc,Session_ID,Term_ID) Values ('$EmpName','$Age','$Sex','$Address','$OptCity','$Citystate','$EmpPhone','$OthContact','$OptDept','$Salary','$DOJ','$DOB','$FatName','$Bank_no','$OptCat','$Country','$isTeacher','$Qua','$Doc','$Session_ID','$Term_ID')";      	                
					$result = mysql_query($query,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					
					//$EmpName = "";
					$Age = "";
					$Sex = "";
					//$Address = "";
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
					//$EmpFilename = "empty_r2_c2.jpg";
					$Qua = "";
					$Doc = "";
					$Country = "";
					$isTeacher = "";
				}
			}elseif ($_POST['empmaster'] =="Update Employee Details"){
				$q = "update tbemployeemasters set EmpName='$EmpName',EmpAge='$Age',EmpSex='$Sex',EmpAddress='$Address',EmpCity='$OptCity',EmpState='$Citystate',EmpPhone='$EmpPhone',EmpOtherContact='$OthContact',EmpDept='$OptDept',EmpSal='$Salary',EmpDOJ='$DOJ',EmpDob='$DOB',EmpFname='$FatName',EmpBankNo='$Bank_no',catCode='$OptCat',Country='$Country',isTeaching='$isTeacher',Qualification='$Qua',Doc='$Doc' where ID = '$EmpCode' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				
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
				$Qua = "";
				$Doc = "";
				$disabled = " disabled='disabled'";
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=payroll.php?subpg=Edit Employee Info&errormsg=$errormsg\">";
		         exit;
			}
		}
	}
	if(isset($_GET['emp_id']))
	{
		$EmpCode = $_GET['emp_id'];
		$query = "select * from tbemployeemasters where ID='$EmpCode' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
		$arrDate=explode ('-', $dbarray['EmpDOJ']);
		$DOJ_Dy = $arrDate[2];
		$DOJ_Mth = $arrDate[1];
		$DOJ_Yr = $arrDate[0];
		$employeegetPicstatus = 1;
		
		$arrDate2=explode ('-', $dbarray['EmpDob']);
		$DOB_Dy = $arrDate2[2];
		$DOB_Mth = $arrDate2[1];
		$DOB_Yr = $arrDate2[0];
		

		$FatName  = formatDatabaseStr($dbarray['EmpFname']);
		$Bank_no  = $dbarray['EmpBankNo'];
		$OptCat  = $dbarray['catCode'];
		$Country  = $dbarray['Country'];
		$isTeacher = $dbarray['isTeaching'];
		$Qua = $dbarray['Qualification'];
		$Doc = $dbarray['Doc'];
		$disabled = " disabled='disabled'";
		$imagephoto = "getemployeepic.php?image_id=".$EmpCode;
		$editkey = true;
	}
	if(isset($_POST['empmaster_delete']))
	{
		$Total = $_POST['Totalemp'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkEmpID'.$i]))
			{
				$chkEmpIDs = $_POST['chkEmpID'.$i];
				$q = "Delete From tbemployeemasters where ID = '$chkEmpIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['submit']))
	{
		$imagephoto = "getemployeepicture.php";
	}
$Optsearch = 'true';	
 if(isset($_POST['SubmitSearch'])){
       $SubmitSearch = 'true'; 
	   $Search_Key = $_POST['Search_Key'];
	   }
   if(isset($_GET['Search_Key']))
	{
		$Optsearch = $_GET['Optsearch'];
		 $SubmitSearch = 'true';
		$Search_Key = $_GET['Search_Key'];
		
	}
	

$stmt = $pdo->prepare("SELECT * FROM tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varemployee = json_encode($json_row);

//echo $json_row[2];



// do something else

	
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
</style>
<SCRIPT 
src="css/jquery-1.2.3.min.js" 
type=text/javascript></SCRIPT>

<SCRIPT 
src="css/menu.js" 
type=text/JavaScript></SCRIPT>

<SCRIPT 
src="js/json/json2.js" 
type=text/JavaScript></SCRIPT>

<script language="JavaScript">
function pagereload(){
	location.reload(true);
}

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
<!-- SECTION 1 -->
  <link rel="stylesheet" type="text/css" href="dojoroot/dijit/themes/tundra/tundra.css"
        />
  
  
  <script type="text/javascript" src="dojoroot/dojo/dojo.js"
    djConfig="parseOnLoad: true"></script>

 <!-- SECTION 2 -->
  <script type="text/javascript">
     // Load Dojo's code relating to the Button widget
     dojo.require("dijit.form.Button");
	 function displaymessage()
{
//alert("Nice job!");
dojo.xhrGet({
         url: 'receivebook.php',
         load: helloCallback2,
         error: helloError,
         content: {name: dijit.byId('name').attr("value"), name2: dijit.byId('studentname').value, name3: dijit.byId('sdate').value, name4: dijit.byId('edate').value, name5: dijit.byId('time').value, name6: dijit.byId('desc').value 
		 }
      });

}
    dojo.require("dijit.Dialog");
    dojo.require("dijit.form.TextBox");
    dojo.require("dijit.form.DateTextBox");
    dojo.require("dijit.form.TimeTextBox");
	dojo.require("dijit.form.Form");
	//var formDlg;
	//var secondDlg;
    dojo.addOnLoad(function() {
			document.getElementById('divLoading').style.display = 'none';				
		//formDlg = dijit.byId("formDialog");					
        // create the dialog:
        //secondDlg = new dijit.Dialog({
        //    title: "Programatic Dialog Creation",
        //    style: "width: 300px"
        //});
    
          
        
		});
   // }
  </script>
  <script type="text/javascript">
    dojo.require("dijit.form.CheckBox");
</script>
<script type="text/javascript">
    dojo.require("dijit.form.ComboBox");
</script>
<script type="text/javascript">
    dojo.require("dijit.form.FilteringSelect");
    dojo.require("dojo.data.ItemFileReadStore");
	</script>
</HEAD>
<BODY class="tundra" style="TEXT-ALIGN: center" background=Images/news-background.jpg><div id="divLoading">   </div>
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
			  <TD width="224" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="854" align="center" valign="top">
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
		if ($SubPage == "Department Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="payroll.php?subpg=Department Master">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="left"><p style="margin-left:150px;">Department Name :&nbsp;&nbsp;&nbsp;
                            <input name="DepartName" type="text" size="55" value="<?PHP echo $DepartName; ?>">
                      </p>
					   </TD>
					</TR>
					<TR>
					   <TD align="left">
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="420" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="62" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="63" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="265" bgcolor="#F4F4F4"><div align="center"><strong>Department Name</strong></div></td>
                          </tr>
<?PHP
							$counter_dept = 0;
							$query2 = "select * from tbdepartments where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_dept = $rstart;
							}else{
								$counter_dept = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbdepartments where INSTR(DeptName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by DeptName";
							}else{
								$query3 = "select * from tbdepartments where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "No department records found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$DeptNameID = $row["ID"];
									$DeptName = $row["DeptName"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkDeptID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $DeptNameID; ?>"></div></td>
										<td><div align="center"><a href="payroll.php?subpg=Department Master&dept_id=<?PHP echo $DeptNameID; ?>"><?PHP echo $counter_dept; ?></a></div></td>
										<td><div align="center"><a href="payroll.php?subpg=Department Master&dept_id=<?PHP echo $DeptNameID; ?>"><?PHP echo $DeptName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Department%20Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Department%20Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Department%20Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center">
							 <input type="hidden" name="Totaldept" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelDeptID" value="<?PHP echo $DeptID; ?>">
							 <input name="deptmaster" type="submit" id="deptmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="deptmaster_delete" type="submit" id="deptmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="deptmaster" type="submit" id="deptmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Designation Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="payroll.php?subpg=Designation Master">
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
					  <TD width="64%" valign="top"  align="left">
					  <p style="margin-left:150px;">Department Name :&nbsp;&nbsp;&nbsp;
                           <label>
							<select name="optDepartment" class="psd2xhmtl-example" onChange="javascript:setTimeout('__doPostBack(\'optDepartment\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
							$query = "select ID,DeptName from tbdepartments where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by DeptName";
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
						  </select>
                           </label>
                      </p>
					   </TD>
					</TR>
					<TR>
					  <TD width="64%" valign="top"  align="left">
					  <p style="margin-left:150px;">Designation Name :&nbsp;&nbsp;&nbsp;
                           <label>
                           <input name="Designation" type="text" size="55" value="<?PHP echo $Designation; ?>">
                           </label>
                      </p>
					   </TD>
					</TR>
					<TR>
					   <TD align="left">
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="420" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="62" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="63" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="265" bgcolor="#F4F4F4"><div align="center"><strong>Designation Name</strong></div></td>
                          </tr>
<?PHP
							$counter_dept = 0;
							$query2 = "select * from tbdesignations  where DeptId = '$optDepartment' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_dept = $rstart;
							}else{
								$counter_dept = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbdesignations where INSTR(DesignName,'$Search_Key') And DeptId = '$optDepartment' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by DesignName";
							}else{
								$query3 = "select * from tbdesignations where DeptId = '$optDepartment' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_desig = $counter_desig+1;
									$counter = $counter+1;
									$DesigNameID = $row["ID"];
									$DesigName = $row["DesignName"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkDesigID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $DesigNameID; ?>"></div></td>
										<td><div align="center"><a href="payroll.php?subpg=Designation Master&desig_id=<?PHP echo $DesigNameID; ?>"><?PHP echo $counter_desig; ?></a></div></td>
										<td><div align="center"><a href="payroll.php?subpg=Designation Master&desig_id=<?PHP echo $DesigNameID; ?>"><?PHP echo $DesigName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Designation Master&st=0&ed=<?PHP echo $rend; ?>&desig_ID=<?PHP echo $optDepartment; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Designation Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&desig_ID=<?PHP echo $optDepartment; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Designation Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&desig_ID=<?PHP echo $optDepartment; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center">
						     <input type="hidden" name="departID" value="<?PHP echo $optDepartment; ?>">
							 <input type="hidden" name="Totaldesig" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelDesigID" value="<?PHP echo $DesigID; ?>">
							 <input name="desigmaster" type="submit" id="desigmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="desigmaster_delete" type="submit" id="desigmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="desigmaster" type="submit" id="desigmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Employee Category") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="payroll.php?subpg=Employee Category">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="left"><p style="margin-left:150px;">Code :&nbsp;
                            <input name="EmpCatID" type="text" size="5" value="<?PHP echo $EmpCatID; ?>" disabled="disabled">
                            &nbsp;&nbsp;&nbsp;
							Name :&nbsp;
							<input name="CatName" type="text" size="35" value="<?PHP echo $CatName; ?>">
                      </p>
					   </TD>
					</TR>
					<TR>
					  <TD width="64%" valign="top"  align="left"><p style="margin-left:150px;">Description :&nbsp;&nbsp;&nbsp;
                            <textarea name="CatDescription" cols="55" rows="4"><?PHP echo $CatDescription; ?></textarea>
                      </p><hr>
					   </TD>
					</TR>
					<TR>
					   <TD align="left">
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="420" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="62" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="63" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="265" bgcolor="#F4F4F4"><div align="center"><strong>Name</strong></div></td>
                          </tr>
<?PHP
							$counter_dept = 0;
							$query2 = "select * from tbcategorymaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_cat = $rstart;
							}else{
								$counter_cat = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbcategorymaster where INSTR(cName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by cName";
							}else{
								$query3 = "select * from tbcategorymaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "No category found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_cat = $counter_cat+1;
									$counter = $counter+1;
									$catID = $row["ID"];
									$catName = $row["cName"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkCatID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $catID; ?>"></div></td>
										<td><div align="center"><a href="payroll.php?subpg=Employee Category&cat_id=<?PHP echo $catID; ?>"><?PHP echo $counter_cat; ?></a></div></td>
										<td><div align="center"><a href="payroll.php?subpg=Employee Category&cat_id=<?PHP echo $catID; ?>"><?PHP echo $catName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Employee Category&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Employee Category&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Employee Category&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center">
							 <input type="hidden" name="Totalcat" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCatID" value="<?PHP echo $EmpCatID; ?>">
							 <input name="catmaster" type="submit" id="deptmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="catmaster_delete" type="submit" id="catmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="catmaster" type="submit" id="deptmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Employee Master") {
			
?>
				<?PHP echo $errormsg; ?>
<?PHP          
				if(isset($_GET['bk'])){$backpg = $_GET['bk'];}
				if($backpg !=""){
?>
          <input type="hidden" name="content" value="<?PHP echo $content; ?>">
				<p><div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href=welcome.php?pg=Master%20Setup&mod=admin>Home</a> &gt; <a href=enquiry.php?subpg=Enquiry>Enquiry</a> &gt; <a href=enquiry.php?subpg=New_Enquiry>New Equiry</a> &gt; Employee Master
				</strong></div></p>
                
<?PHP 
				}
?>
				<p><div align="left" style="font-size:18px; color: #000;">  <?if(isset($_GET['emp_id'])){ echo'UPDATING EMPLOYEE INFO' . $EmpName;} else {echo 'SAVING NEW EMPLOYEE INFO'; }  echo "SAVING NEW EMPLOYEE INFO";?> </div> </p>

				
				<form name="form1" method="post" action="payroll.php?subpg=Employee Master">
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
					  	<TABLE width="100%" style="WIDTH: 100%"  border="0">
							<TBODY>
							<TR>
							  <TD width="41%" valign="top"  align="left">Date of Joining</TD>
							  <TD width="59%" valign="top"  align="left">Dy:
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
							    </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Name</TD>
							  <TD width="59%" valign="top"  align="left">
							    <input name="EmpName" type="text" size="25" value="<?PHP echo $EmpName; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Date of Birth</TD>
							  <TD width="59%" valign="top"  align="left">Dy:
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
							    </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Age </TD>
							  <TD width="59%" valign="top"  align="left">
							    <input name="Age" type="text" size="5" value="<?PHP echo $Age; ?>">
							    Sex							      
							    <select name="Sex">
                                  <option value=""></option>
                                  <option value="M">Male</option>
                                  <option value="F">Female</option>
                                </select></TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Next of kin </TD>
							  <TD width="59%" valign="top"  align="left">
							    <input name="FatName" type="text" size="25" value="<?PHP echo $FatName; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Address</TD>
							  <TD width="59%" valign="top"  align="left">
							    <textarea name="Address" cols="30" rows="3"><?PHP echo $Address; ?></textarea>
							  </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">City</TD>
							  <TD width="59%" valign="top"  align="left">
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
							  <TD width="41%" valign="top"  align="left">State</TD>
							  <TD width="59%" valign="top"  align="left">
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
							  <TD width="41%" valign="top"  align="left">Country</TD>
							  <TD width="59%" valign="top"  align="left">
							    <input name="Country" type="text" size="25" value="<?PHP echo $Country; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Phone</TD>
							  <TD width="59%" valign="top"  align="left">
							    <input name="EmpPhone" type="text" size="25" value="<?PHP echo $EmpPhone; ?>">
							 </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Other Contact</TD>
							  <TD width="59%" valign="top"  align="left">
							    <input name="OthContact" type="text" size="25" value="<?PHP echo $OthContact; ?>">
							  </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Bank A/C No</TD>
							  <TD width="59%" valign="top"  align="left">
							    <input name="Bank_no" type="text" size="25" value="<?PHP echo $Bank_no; ?>">
							 </TD>
							</TR>
							<TR>
							  <TD width="41%" valign="top"  align="left">Qualification</TD>
							  <TD width="59%" valign="top"  align="left">
							    <textarea name="Qua" cols="35" rows="3"><?PHP echo $Qua; ?></textarea>
							 </TD>
							 <TR>
							  <TD width="41%" valign="top"  align="left">Document Submitted by Employee </TD>
							  <TD width="59%" valign="top"  align="left">
							    <textarea name="Doc" cols="35" rows="3"><?PHP echo $Doc; ?></textarea>
							 </TD>
							</TR>							<TR>
							  <TD width="41%" valign="top"  align="left">Teaching</TD>
							  <TD width="59%" valign="top"  align="left">

								 
                                 <?PHP
						if($isTeacher == 1){
?>
					  		<input name="isTeacher" type="radio" value="1" checked="checked">Yes  
<?PHP
						}else{
?>
							<input name="isTeacher" type="radio" value="1">Yes
<?PHP
						}
?>
           <?PHP
						if($isTeacher == 0){
?>
					  		<input name="isTeacher" type="radio" value="0" checked="checked">No  
<?PHP
						}else{
?>
							<input name="isTeacher" type="radio" value="0">No
<?PHP
						}
?>

							  </TD>
							</TR>
						</TBODY>
						</TABLE>
					  </TD>
					  <TD width="50%" valign="top"  align="left" style="BORDER-LEFT: #cccccc 1px solid;">
					  	
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2" align="left">
						 Dept : 
						  <select name="OptDept" >
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
						 

                           </select>&nbsp;&nbsp;&nbsp;&nbsp;
						 Category : 
						   <select name="OptCat">
						   <option value="0" selected="selected">&nbsp;</option>
<?PHP
							//$query = "select ID,cName from tbcategorymaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by cName";
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
                           </select>&nbsp;&nbsp;&nbsp;&nbsp;
						 Employee Salary (=N=): <input name="Salary" type="text" size="10" value="<?PHP echo $Salary; ?>">
						  &nbsp;&nbsp;&nbsp;&nbsp;
						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
							 <input type="hidden" name="Totalemp" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelEmpID" value="<?PHP echo $EmpCode; ?>">
                             <?PHP if($editkey == false){ ?>
							 <input name="empmaster" type="submit" id="empmaster" value="Accept New Employee Details" >
                             <?php }else{ ?>
						     <input name="empmaster" type="submit" id="empmaster" value="Update Employee Details" >
                             <?PHP } ?>
						     
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
					</TBODY>
				</TABLE>
				</form>
 <?PHP
		}elseif ($SubPage == "Upload Employee Picture") {
?>
 <?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="payroll.php?subpg=Upload Employee Picture">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="left">
                      
					  	Search Employee (By Name):&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    <table border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            
                            <td width="125" bgcolor="#F4F4F4"><div align="center" class="style21">Employee Name </div></td>
                            <td width="54" bgcolor="#F4F4F4"><div align="center"><strong>Teaching</strong></div></td>
							<td width="87" bgcolor="#F4F4F4"><div align="center"><strong>Phone</strong></div></td>
                          </tr>
<?PHP
							$counter_emp = 0;
							$query2 = "select * from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_emp = $rstart;
							}else{
								$counter_emp = $rstart-1;
							}
							$counter = 0;
							if($SubmitSearch == 'true')
							{
								$query4 = "select * from tbemployeemasters where INSTR(EmpName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName LIMIT $rstart,$rend";
						$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
								$query3 = "select * from tbemployeemasters where INSTR(EmpName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName LIMIT $rstart,$rend";
						$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_emp = $counter_emp+1;
									$counter = $counter+1;
									$EmpID = $row["ID"];
									$EmpName = $row["EmpName"];
									$EmpDept = $row["EmpDept"];
									$EmpPhone = $row["EmpPhone"];
									$isTeacher = $row["isTeaching"];
									if($isTeacher == 1){
										$EmpTeach = "True";
									}elseif($isTeacher == 0){
										$EmpTeach = "False";
									}
?>
									  <tr>
										
										<td><div align="center"><a href="uploademployeepic.php?emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></a></div></td>
										<td><div align="center"><a href="uploademployeepic.php?emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpTeach; ?></a></div></td>
										<td><div align="center"><a href="uploademployeepic.php?emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpPhone; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Upload Employee Picture&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Upload Employee Picture&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Upload Employee Picture&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p></TD></TR></TBODY></TABLE></form>
                  <?PHP 
							}else{
								$query3 = "select * from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
							                                                                              //}
							$result3 = mysql_query($query3,$conn);
							$num_rows2 = mysql_num_rows($result3);
							if ($num_rows2 <= 0 ) {
								?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_emp = $counter_emp+1;
									$counter = $counter+1;
									$EmpID = $row["ID"];
									$EmpName = $row["EmpName"];
									$EmpDept = $row["EmpDept"];
									$EmpPhone = $row["EmpPhone"];
									$isTeacher = $row["isTeaching"];
									if($isTeacher == 1){
										$EmpTeach = "True";
									}elseif($isTeacher == 0){
										$EmpTeach = "False";
									}
?>
									  <tr>
										
										<td><div align="center"><a href="uploademployeepic.php?emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></a></div></td>
										<td><div align="center"><a href="uploademployeepic.php?emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpTeach; ?></a></div></td>
										<td><div align="center"><a href="uploademployeepic.php?emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpPhone; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Upload Employee Picture&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Upload Employee Picture&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Upload Employee Picture&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD></TR></TBODY></TABLE></form>

<?PHP
		    }
		}elseif ($SubPage == "View Employee Info") {
?>
            <?PHP echo $errormsg; ?>
                <script type="text/javascript">
                        
						
				function displaynames(data) {
						//alert("i'm here and good");
						//get the placeholder element from the page
						var listBox = document.getElementById('Names');
						listBox.innerHTML = '';
						//listBox.innerHTML = 'Bello Kolade';//clear the names on the page
						
						//iterate over retrieved entries and display them on the page
						for ( var i = 0; i < data.length; i++ )
						{
							//dynamically create a div element for each entry and a fieldset element to place it in
							var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							entry.onclick = handleOnClick; // set onclick event handler
							entry.id = i; // set the id
							entry.innerHTML = data[i].EmpName;// +''+ data[i].Last
							field.appendChild( entry ); //insert entry into the field
							listBox.appendChild( field ); //display the field
						} //end for
					}//end function displayAll
					// -->
           
		  function displaydata(data,ioArgs){
			  
			  var name = data[0].EmpName;
			  //var class = data.studentclass;
			  var age = data[0].EmpAge;
			  var gender = data[0].EmpSex;
			  var address = data[0].EmpAddress;
			  var state = data[0].EmpState;
			  var phone = data[0].EmpPhone;
			  var salary = data[0].EmpSal;
			  var othercontact = data[0].EmpOtherContact;
			  var doj = data[0].EmpDOJ;
			  var dob = data[0].EmpDob;
			  var qualification = data[0].Qualification;
			  /*var studenttype = data[0].EmpDOJ;
			  var studentweight = data['studentotherdetails'][0].Stu_Wt;
			  //var studentweight = '';
			  var studentheight =  data['studentotherdetails'][0].Stu_Ht;
			  var studentage =  data['studentotherdetails'][0].Stu_Age;
			  var grname =  data['studentotherdetails'][0].Gr_Name_Mr;
			  var graddress =  data['studentotherdetails'][0].Gr_Addr;
			  var grphoneno =  data['studentotherdetails'][0].Phy_MbPh;
			  var bloodgroup =  data['studentotherdetails'][0].BloodGroup;
			  var studentreligion =  data['studentotherdetails'][0].Religion;
			  var emmergencyno =  data['studentotherdetails'][0].EmergNO;*/
			  var picture = data.employeepicture;
			  var department = data.employeedept;
			  var category = data.employcategory;
			  var teaching = data.employeteachingstatus;

			var listBox = document.getElementById('studentname');
			listBox.innerHTML = '';
			listBox.innerHTML = '<fieldset><b>EMPLOYEE NAME:</b>' + ' ' + name + '</fieldset><p><fieldset><b>EMPLOYEE AGE:</b>' + ' ' + age + '</fieldset></p><p><fieldset><b>EMPLOYEE ADDRESS:</b>' + ' ' + address + '</fieldset></p><p><fieldset><b>EMPLOYEE DATE OF BIRTH(YY/MM/DD):</b>' + ' ' + dob + '</fieldset></p><p><fieldset><b>EMPLOYEE GENDER:</b>' + ' ' + gender + '</fieldset></p><p><fieldset><b>EMPLOYEE STATE:</b>' + ' ' + state + '</fieldset></p><p><fieldset><b>EMPLOYEE PHONE:</b>' + ' ' + phone + '</fieldset></p><p><fieldset><b>EMPLOYEE OTHER CONTACT:</b>' + ' ' + othercontact + '</fieldset></p><p><fieldset><b>EMPLOYEE DEPARTMENT:</b>' + ' ' + department + '</fieldset></p><p><fieldset><b>EMPLOYEE CATEGORY:</b>' + ' ' + category + '</fieldset></p><p><fieldset><b>EMPLOYEE SALARY:</b>' + ' ' + salary + '</fieldset></p><p><fieldset><b>EMPLOYEE DATE OF JOINING</b>' + ' ' + doj + '</fieldset></p><p><fieldset><b>EMPLOYEE QUALIFICATIONS</b>' + ' ' + qualification + '</fieldset></p><p><fieldset><b>TEACHING:</b>' + ' ' + teaching + '</fieldset></p>'; /*<p><fieldset><b>BLOOD GROUP:</b>' + ' ' + bloodgroup + '</fieldset></p><p><fieldset><b>RELIGION:</b>' + ' ' + studentreligion + '</fieldset></p><p><fieldset><b>EMMERGENCY NO:</b>' + ' ' + emmergencyno + '</fieldset></p>';*/
			
			
			var entry = document.getElementById('employeeimage');
			entry.src = 'images/' + picture; 
			document.getElementById('divLoading').style.display = 'none';
			
			//var test = document.getElementById('testing');
			//var test2 = document.getElementById('test2');
			//test.appendChild(test2)
			//test.innerHTML = '';
			//test.innerHTML = test2;
						
		      }
		function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }
</script>
                <TABLE height="300" >
                      <TBODY>
                     <TR valign="top"><TD width="10%" align="left">
                <label for="student">
           <b>Enter/Select Employee Name:</b>
              </label>
<input id="selectemployee">
                             </TD><TD width="10%" id="test2" align="left"> <button dojoType="dijit.form.Button" name="studentinfo" id="studentinfo">View Empoyee Info <script type="dojo/method" event="onClick">
							 
           document.getElementById('divLoading').style.display = 'block';          
		 dojo.xhrGet({
         url: 'viewemployeeinfo.php',
		 handleAs: 'json',
         load: displaydata,
         error: helloError,
         content: {name: dijit.byId('selectemployee').attr("value")}
      });
		 
			
			
                </script></button></TD>
                
                <TD width="10%" align="left"> <button dojoType="dijit.form.Button" name="studentresult" id="studentresult">View Employee Class/Subject <script type="dojo/method" event="onClick">
      </script></button></TD><!--<TD width="10%" align="left"> <button dojoType="dijit.form.Button" name="teachers" id="teachers">Teachers <script type="dojo/method" event="onClick"></script></button></TD><TD width="10%" align="left"> <button dojoType="dijit.form.Button" name="teach" id="teach">Teach <script type="dojo/method" event="onClick"></script></button>
                             </TD>-->
                             
                             </TR>
                   <TR>
					  <TD width="40%" valign="top"  align="left"><div id="Names" style="display:block"><fieldset><b>EMPLOYEE INFO</b><div id="studentname"> </div></fieldset><div id="testing"></div></div></TD><TD align="right" valign="top"> <table border="0" cellpadding="0" cellspacing="0" width="221">
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
						   <td><img id="employeeimage" src="images/empty_r2_c2.jpg" width="178" height="175"></td>
						   <td rowspan="2"><img name="empty_r2_c3" src="images/empty_r2_c3.jpg" width="22" height="197" border="0" id="empty_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="175" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="empty_r3_c2" src="images/empty_r3_c2.jpg" width="178" height="22" border="0" id="empty_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="22" border="0" alt="" /></td></form>
						  </tr>
						</table>	</TD></TR>
                             </TBODY>
                             </TABLE>
<?PHP
		}elseif ($SubPage == "Edit Employee Info") {
?>
            <?PHP echo $errormsg; ?>
            <form name="form1" method="post" action="payroll.php?subpg=Edit Employee Info">
				<TABLE width="96%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="30%" valign="top"  align="left">
					  	<p align="center">Search Employee (By Name) :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="20" value="<?PHP echo $Search_Key; ?>">
							<input name="SubmitSearch" type="submit" id="Search" value="Go">
                        </p>
					  	<table width="209" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr bgcolor="#999999">
                            <td width="197"><div align="center" class="style22">Employee Name</div></td>
                          </tr>
<?PHP
							$counter_emp = 0;
							$query2 = "select ID,EmpName from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_emp = $rstart;
							}else{
								$counter_emp = $rstart-1;
							}
							$counter = 0;
							if($SubmitSearch == 'true')
							{
								//$Search_Key = $_POST['Search_Key'];
								$query4 = "select * from tbemployeemasters where INSTR(EmpName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName LIMIT $rstart,$rend";
						$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
								$query3 = "select * from tbemployeemasters where INSTR(EmpName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName LIMIT $rstart,$rend";
						$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
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
										<td style="border-bottom:#CCCCCC 1px solid;"><div align="center"><a href="payroll.php?subpg=Employee Master&emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="payroll.php?subpg=Edit Employee Info&st=0&ed=<?PHP echo $rend; ?>&emp_id=<?PHP echo $EmpID; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;</a> |&nbsp;&nbsp;<a href="payroll.php?subpg=Edit Employee Info&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&emp_id=<?PHP echo $EmpID; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Edit Employee Info&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&emp_id=<?PHP echo $EmpID; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
                        
					<?PHP 
							}else{
								$query3 = "select * from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
							                                                                              //}
							$result3 = mysql_query($query3,$conn);
							$num_rows2 = mysql_num_rows($result3);
							if ($num_rows2 <= 0 ) {
								?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
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
										<td style="border-bottom:#CCCCCC 1px solid;"><div align="center"><a href="payroll.php?subpg=Employee Master&emp_id=<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="payroll.php?subpg=Edit Employee Info&st=0&ed=<?PHP echo $rend; ?>&emp_id=<?PHP echo $EmpID; ?>">First&nbsp;</a> |&nbsp;&nbsp;<a href="payroll.php?subpg=Edit Employee Info&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&emp_id=<?PHP echo $EmpID; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="payroll.php?subpg=Edit Employee Info&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&emp_id=<?PHP echo $EmpID; ?>">Next</a> </p>
                        
 <?PHP
		}
	}
?>
					  </TD></TR></TBODY></TABLE></form>
            

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
