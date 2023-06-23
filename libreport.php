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
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
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
	$Page = "Library Report";
	$audit=update_Monitory('Login','Administrator',$Page);
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['OptCat']))
	{
		$OptCat = $_POST['OptCat'];
	}
	if(isset($_POST['ischecked']))
	{	
		$ischecked = $_POST['ischecked'];
		if($ischecked =="Cat"){
			$chkCat = "checked='checked'";
			$lockSubCat = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockPublisher = "disabled='disabled'";
			$lockCondition = "disabled='disabled'";
			$lockBookCondition = "disabled='disabled'";
			$lockAcc = "disabled='disabled'";
			$lockPrice = "disabled='disabled'";
		}elseif($ischecked =="SubCat"){
			$chkSubCat = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockPublisher = "disabled='disabled'";
			$lockCondition = "disabled='disabled'";
			$lockBookCondition = "disabled='disabled'";
			$lockAcc = "disabled='disabled'";
			$lockPrice = "disabled='disabled'";
		}elseif($ischecked =="Author"){
			$chkAuthor = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockPublisher = "disabled='disabled'";
			$lockCondition = "disabled='disabled'";
			$lockBookCondition = "disabled='disabled'";
			$lockAcc = "disabled='disabled'";
			$lockPrice = "disabled='disabled'";
		}elseif($ischecked =="Publisher"){
			$chkPublisher = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockCondition = "disabled='disabled'";
			$lockBookCondition = "disabled='disabled'";
			$lockAcc = "disabled='disabled'";
			$lockPrice = "disabled='disabled'";
		}elseif($ischecked =="Condition"){
			$chkCondition = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockBookCondition = "disabled='disabled'";
			$lockPublisher = "disabled='disabled'";
			$lockAcc = "disabled='disabled'";
			$lockPrice = "disabled='disabled'";
		}elseif($ischecked =="BookCondition"){
			$chkBookCondition = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockCondition = "disabled='disabled'";
			$lockPublisher = "disabled='disabled'";
			$lockAcc = "disabled='disabled'";
			$lockPrice = "disabled='disabled'";
		}elseif($ischecked =="Accession"){
			$chkAccession = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockPublisher = "disabled='disabled'";
			$lockCondition = "disabled='disabled'";
			$lockBookCondition = "disabled='disabled'";
			$lockPrice = "disabled='disabled'";
		}elseif($ischecked =="Price"){
			$chkPrice = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockPublisher = "disabled='disabled'";
			$lockCondition = "disabled='disabled'";
			$lockBookCondition = "disabled='disabled'";
			$lockAcc = "disabled='disabled'";
		}
	}
	if(isset($_POST['SubmitPrint']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		if(!isset($_POST['ischecked']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria.</font>";
			$PageHasError = 1;
		}
		if($ischecked =="Cat"){
			if($_POST['OptCat'] == "0")
			{
				$errormsg = "<font color = red size = 1>Select Category.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$OptCat = $_POST['OptCat'];
				//$OptSubCat = $_POST['OptSubCat'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&cat=$OptCat\">";
				exit;
			}
		}elseif($ischecked =="SubCat"){
			if($_POST['OptSubCat']=="")
			{
				$errormsg = "<font color = red size = 1>Select Category.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$OptSubCat = $_POST['OptSubCat'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&subcat=$OptSubCat\">";
				exit;
			}
		}elseif($ischecked =="Author"){
			if($_POST['OptAuthor']=="")
			{
				$errormsg = "<font color = red size = 1>Select Author to display.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$OptAuthor = $_POST['OptAuthor'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&ath=$OptAuthor\">";
				exit;
			}
		}elseif($ischecked =="Publisher"){
			if($_POST['OptPublisher']=="")
			{
				$errormsg = "<font color = red size = 1>Select Publisher to display.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$OptPublisher = $_POST['OptPublisher'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&pub=$OptPublisher\">";
				exit;
			}
		}
		elseif($ischecked =="Condition"){
			if($_POST['isCond'] == "")
			{
				$errormsg = "<font color = red size = 1>Select book condition</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$isCond = $_POST['isCond'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&isc=$isCond\">";
				exit;
			}
		}elseif($ischecked =="BookCondition"){
			if($_POST['OptBookCondition'] == "")
			{
				$errormsg = "<font color = red size = 1>Select book condition</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				//$isCond = $_POST['isCond'];
				$OptBookCondition = $_POST['OptBookCondition'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&cond=$OptBookCondition\">";
				exit;
			   }
			}elseif($ischecked =="Accession"){
			if($_POST['ToBookNo'] =="" And $_POST['FrBookNo'] =="")
			{
				$errormsg = "<font color = red size = 1>Enter book accession no.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$ToBookNo = $_POST['ToBookNo'];
				$FrBookNo = $_POST['FrBookNo'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&frm=$FrBookNo&to=$ToBookNo\">";
				exit;
			}
		}elseif($ischecked =="Price"){
			if($_POST['FrPrice'] =="" And $_POST['ToPrice'] =="")
			{
				$errormsg = "<font color = red size = 1>Enter book price range.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$FrPrice = $_POST['FrPrice'];
				$ToPrice = $_POST['ToPrice'];
				echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books in Library&src=$ischecked&frp=$FrPrice&top=$ToPrice\">";
				exit;
			}
		}
	}
	
	if(isset($_POST['OptClass']))
	{
		$OptClass = $_POST['OptClass'];
	}
	if(isset($_POST['OptDept']))
	{
		$OptDept = $_POST['OptDept'];
	}
	if(isset($_POST['isChkStudent']))
	{	
		$isChkStudent = $_POST['isChkStudent'];
		if($isChkStudent =="All"){
			$chkAll = "checked='checked'";
			$lockClass = "disabled='disabled'";
		}elseif($isChkStudent =="Class"){
			$chkClass = "checked='checked'";
		}
	}
	if(isset($_POST['ischecked2']))
	{	
		$ischecked = $_POST['ischecked2'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Name"){
			$chkname = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}
	}
	if(isset($_POST['PrintStudent']))
	{
		$PageHasError = 0;
		//$PageHasError = 0;
		$ischecked = $_POST['ischecked2'];
		$OptClass = $_POST['className5'];
		$OptStudent = $_POST['StudentName5'];
		//$StuName = $_POST['StuName'];
		//$_POST['GetStudentFee'] = "Go";
		if($ischecked == "")
		{
			$errormsg = "<font color = red size = 1>Please Select A Filter</font>";
			$PageHasError = 1;
		}
		if($ischecked == "All")
		{
			//$errormsg = "<font color = red size = 1>Select A Class</font>";
			$PageHasError = 0;
		}
		elseif($ischecked == "Class" and $OptClass == "")
		{
			$errormsg = "<font color = red size = 1>Select A Class</font>";
			$PageHasError = 1;
		}
		elseif($ischecked == "Name" and $OptStudent == "")
		{
			$errormsg = "<font color = red size = 1>Select Student Name</font>";
			$PageHasError = 1;
		}

if ($PageHasError == 0)
		{
		
						echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books Issued to Student&src_stud=$ischecked&isClass=$OptClass&isAdm=$OptStudent\">";
						exit;
	 				
	          }
	}
	
if(isset($_POST['SubmitPrintStu_Wise']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked2'];
		$OptClass = $_POST['className5'];
		$OptStudent = $_POST['StudentName5'];
		//$StuName = $_POST['StuName'];
		//$_POST['GetStudentFee'] = "Go";
		if($ischecked2 == "")
		{
			$errormsg = "<font color = red size = 1>Please Select A Filter</font>";
			$PageHasError = 1;
		}
		if($ischecked2 == "All")
		{
			//$errormsg = "<font color = red size = 1>Select A Class</font>";
			$PageHasError = 0;
		}
		elseif($ischecked2 == "Class" and $OptClass == "")
		{
			$errormsg = "<font color = red size = 1>Select A Class</font>";
			$PageHasError = 1;
		}
		elseif($ischecked2 == "Name" and $OptStudent == "")
		{
			$errormsg = "<font color = red size = 1>Select Student Name</font>";
			$PageHasError = 1;
		}
		
		
		
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Student fee&adm=$OptStudent&t1=$Term1&t2=$Term2&class=$OptClass&checked=$ischecked\">";
			exit;
		}
	}
	
	
	if(isset($_POST['isChkEmp']))
	{	
		$isChkEmp = $_POST['isChkEmp'];
		if($isChkEmp =="All"){
			$chkAll = "checked='checked'";
			$lockdept = "disabled='disabled'";
		}elseif($isChkEmp =="Dept"){
			$chkDept = "checked='checked'";
		}
	}
	if(isset($_POST['PrintEmployee']))
	{
		 $isChkEmp = $_POST['isChkEmp'];
		 $OptDept = $_POST['OptDept'];
		$PageHasError = 0;
		if(!isset($_POST['isChkEmp']))
		{
			$errormsg = "<font color = red size = 1>Select Search Criteria.</font>";
			$PageHasError = 1;
		}
		
			
	if($isChkEmp =="Dept"){
				if($_POST['OptDept'] =="0")
				{
					$errormsg = "<font color = red size = 1>Select Department</font>";
					$PageHasError = 1;
				}
	           }
	if ($PageHasError == 0)	{
						                echo "<meta http-equiv=\"Refresh\" content=\"0;url=library_rpt.php?pg=Books Issued to Employee&src_emp=$isChkEmp&isDept=$OptDept\">";
	             }
						
}
	
$stmt = $pdo->prepare("SELECT * FROM tbclassmaster");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varclass = json_encode($json_row);

$stmt = $pdo->prepare("SELECT * FROM tbstudentmaster");
// do something
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row2 = array ('items'=>$row2);             
$varstudent2 = json_encode($json_row2);
//echo $varteacher;


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
width:450px;
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
.style1 {
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
<SCRIPT 
src="js/json/json2.js" 
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
			  <TD width="220" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="858" align="center" valign="top">
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
		if ($SubPage == "Books in Library") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="libreport.php?subpg=Books in Library">
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
				<TABLE width="100%" style="WIDTH: 100%" cellpadding="5" cellspacing="5">
				<TBODY>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><strong>Search Criteria</strong></TD>
					 </TR>
					 <TR>
					  <TD width="20%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="Cat" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkCat; ?>>
					  Category Name Wise
					  </label></TD>
					  <TD width="33%" valign="top"  align="left">Category Name 
					    <select name="OptCat" onChange="javascript:setTimeout('__doPostBack(\'OptHeader\',\'\')', 0)" style="width:100px;" <?PHP echo $lockCat; ?>>
                          <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,CatName from tblibcategorymst order by CatName";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$CatID = $row["ID"];
								$CatName = $row["CatName"];
								
								if($OptCat =="$CatID"){
?>
                         			 <option value="<?PHP echo $CatID; ?>" selected="selected"><?PHP echo $CatName; ?></option>
<?PHP
								}else{
?>
                          			<option value="<?PHP echo $CatID; ?>"><?PHP echo $CatName; ?></option>
<?PHP
								}
							}
						}
?>
                        </select></TD>
					  <TD width="15%" valign="top"  align="left"> </TD>
					  <TD width="34%" valign="top"  align="left">
					  </TD>
					 </TR>
                     <TR><TD width="25%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="SubCat" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkSubCat; ?>>Sub Category Name Wise  </label></TD>
					  <TD width="34%" valign="top"  align="left">Sub Category Name
					  <select name="OptSubCat" style="width:100px;" <?PHP echo $lockSubCat; ?>>
                        <option value="" selected="selected"></option>
<?PHP
						$counter = 0;
						
							$query = "select * from tblibsubcatmst order by SubCatName";
						
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$sCatID = $row["ID"];
								$sCatName = $row["SubCatName"];
								
								if($OptSubCat =="$sCatID"){
?>
                        			<option value="<?PHP echo $sCatID; ?>" selected="selected"><?PHP echo $sCatName; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $sCatID; ?>"><?PHP echo $sCatName; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></TD><TD></TD><TD></TD></TR>
					 <TR>
					  <TD width="18%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="Author" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAuthor; ?>>
					  Author Wise
					  </label></TD>
					  <TD width="33%" valign="top"  align="left">Author Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					    <select name="OptAuthor" style="width:100px;" <?PHP echo $lockAuthor; ?>>
                          <option value="" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,AuthorName from tbauthormaster order by AuthorName";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$AuthID = $row["ID"];
								$AuthorName = $row["AuthorName"];
								
								if($OptAuthor =="$AuthID"){
?>
                          <option value="<?PHP echo $AuthID; ?>" selected="selected"><?PHP echo $AuthorName; ?></option>
<?PHP
								}else{
?>
                          <option value="<?PHP echo $AuthID; ?>"><?PHP echo $AuthorName; ?></option>
<?PHP
								}
							}
						}
?>
                        </select></TD>
					  <TD width="15%" valign="top"  align="left"></TD>
					  <TD width="34%" valign="top"  align="left">
					  </TD>
					 </TR>
                     <TR><TD width="18%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="Publisher" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkPublisher; ?>>Publisher Wise</TD>
					  <TD width="34%" valign="top"  align="left">Publisher Name
					  <select name="OptPublisher" style="width:120px;" <?PHP echo $lockPublisher; ?>>
                        <option value="" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,PubName from tbpublisher order by PubName";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$PubID = $row["ID"];
								$PubName = $row["PubName"];
								
								if($OptPublisher =="$PubID"){
?>
                        			<option value="<?PHP echo $PubID; ?>" selected="selected"><?PHP echo $PubName; ?></option>
<?PHP
								}else{
?>
                       				<option value="<?PHP echo $PubID; ?>"><?PHP echo $PubName; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></TD><TD></TD><TD></TD></TR>
					 <TR>
					  <TD width="18%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="Condition" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkCondition; ?>>
					  Condition Details
					  </label></TD>
					  <TD width="33%" valign="top"  align="left">
					    <input name="isCond" type="radio" value="0" <?PHP echo $lockCondition; ?>>
					    Active 
					    <input name="isCond" type="radio" value="1" <?PHP echo $lockCondition; ?>>
					    Scrap 
					    <input name="isCond" type="radio" value="2" <?PHP echo $lockCondition; ?>> 
					    Lost </TD>
					  <TD width="15%" valign="top"  align="left"> </TD>
					  <TD width="34%" valign="top"  align="left">
					  </TD>
					 </TR>
                     <TR><TD width="15%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="BookCondition" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkBookCondition; ?>>Condition Details 2 </label> </TD>
					  <TD width="34%" valign="top"  align="left">Book Condition 
					  <select name="OptBookCondition" style="width:120px;" <?PHP echo $lockBookCondition; ?>>
                        <option value="" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,BCond from bookcondition order by BCond";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$BCondID = $row["ID"];
								$BCond = $row["BCond"];
								
								if($OptCondition =="$BCondID"){
?>
                        			<option value="<?PHP echo $BCondID; ?>" selected="selected"><?PHP echo $BCond; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $BCondID; ?>"><?PHP echo $BCond; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></TD><TD></TD></TR>
					 <TR>
					  <TD width="18%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="Accession" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAccession; ?>>
					  Accession No.
					  </label></TD>
					  <TD width="33%" valign="top"  align="left">Between 
					    <input name="FrBookNo" type="text"value="<?PHP echo $FrBookNo; ?>" size="20" <?PHP echo $lockAcc; ?>/></TD>
					  <TD width="15%" valign="top"  align="left">and </TD>
					  <TD width="34%" valign="top"  align="left"><input name="ToBookNo" type="text"value="<?PHP echo $ToBookNo; ?>" size="20" <?PHP echo $lockAcc; ?>/></TD>
					 </TR>
					  <TR>
					  <TD width="18%" valign="top"  align="left"><label>
					  <input name="ischecked" type="radio" value="Price" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkPrice; ?>>
					  Book Price
					  </label></TD>
					  <TD width="33%" valign="top"  align="left">Between					    
					    <input name="FrPrice" type="text"value="<?PHP echo $FrPrice; ?>" size="20" <?PHP echo $lockPrice; ?>/></TD>
					  <TD width="15%" valign="top"  align="left">and </TD>
					  <TD width="34%" valign="top"  align="left"><input name="ToPrice" type="text"value="<?PHP echo $ToPrice; ?>" size="20" <?PHP echo $lockPrice; ?>/></TD>
					  </TR>
					  <TR>
							<TD colspan="4">
							<div align="center">
							  <input type="submit" name="SubmitPrint" value="Print">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Books Issued to Student") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="libreport.php?subpg=Books Issued to Student">
				<div>
					<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
					<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
					<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
					</div>
					<script type="text/javascript">
					<!--
					dojo.addOnLoad(function() {
		//formDlg = dijit.byId("formDialog");					
        
 var dataJson = <?php echo $varclass; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson
            }),
            //autoComplete: true,
			searchAttr: "Class_Name",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectclass2",
            onChange: function setinput(){
	
	//alert('im good');
	setstudentinput();
      //studentselect1();	              
	    },
                   
		     },"selectclass2");
		
		 new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson
            }),
            //autoComplete: true,
			searchAttr: "Class_Name",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectclass3",
            onChange: function setinput2(){
	
	//alert('im good');
     // setstudentinput2();
      setstudentnameinput();	              
	       },
                   
		     },"selectclass3");
		
					   
		
	});	
					
					
					function setstudentinput(){
					 // alert('im good');
					 var classname = dijit.byId("selectclass2").value; 
		             var classhiddeninput = document.getElementById('className5');
					 classhiddeninput.value = classname;
					 //alert(classname);
					}
					
					function setstudentinput5(){
					  var studentname = document.getElementById('studentname3').value; 
		             var classhiddeninput2 = document.getElementById('StudentName5');
					 classhiddeninput2.value = studentname;
					 //alert(studentname);
					}
					
				function setstudentnameinput(){
						//alert('im good');
						dojo.xhrGet({
                            url: 'selectstudent1.php',
		                    handleAs: 'json',
                            load: studentselectfee,
                            error: helloError3,
                             content: {name1: dijit.byId('selectclass3').attr("value")}
                               });
					}
					function setdisplay1(){
				    document.getElementById('selectclass5').style.display = 'none';
					  document.getElementById('selectstudent5').style.display = 'none';
					  document.getElementById('studentName5').style.display = 'none';
					   }
					function setdisplay2(){
					  //alert('im good');
					  document.getElementById('selectclass5').style.display = 'block';
					  document.getElementById('selectstudent5').style.display = 'none';
					  document.getElementById('studentName5').style.display = 'none';
					}
					function setdisplay3(){
						 document.getElementById('selectstudent5').style.display = 'block';
						  document.getElementById('studentName5').style.display = 'block';
						  document.getElementById('selectclass5').style.display = 'none';
					  //alert('im good');
					}
					
					function studentselectfee(data,ioArgs){
			//alert('im good');
		var StudentName = document.getElementById('studentName5');
		  StudentName.innerHTML = "";
		 var studentnamelength = data.studentname.length
	      var studentnameselect ='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Student Name:<select id = "studentname3" onchange = "setstudentinput5();" ><option >Student Name</option>';
		 for ( var i = 0; i < studentnamelength; i++ ){
			 var studentname = data.studentname[i];
			 studentnameselect += '<option >' + studentname + '</option>';		
			 }
			 studentnameselect+='</select>';
		
		               StudentName.innerHTML = studentnameselect; 	  
		 
		 }
		 
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
					
		
	 function helloError3(data, ioArgs) {
        alert('Error when retrieving data from the server3!');
		//var listBox = document.getElementById('Names');
     }
					// -->
				</script>
				<TABLE width="83%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="81%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="625" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr><td width="143"> 1. All Student</td>
                            <td width="61">
<?PHP
							if($_SESSION['module'] != "Teacher"){
?>
								<label>
                              	<input name="ischecked2" type="radio" value="All" onClick="setdisplay1();" <?PHP echo $chkAll; ?>>
                            	</label>All
<?PHP
							}
?>
							
							
							</td><td width="371"></td>
                          </tr>
						  <tr>
                            <td> 2. By Class</td><td width="61"><input name="ischecked2" type="radio" value="Class" onClick="setdisplay2();" <?PHP echo $chkclass; ?>></td> 
                              <td><div id="selectclass5" style="display:none">Select Class: <input id="selectclass2" > </div></td>
							<!--<select name="OptClass" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
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
										$ClassID = $row["ID"];
										$Classname = $row["Class_Name"];
										
										if($OptClass =="$ClassID"){
?>
											<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
										}
									}
								}
?>
					  </select>-->
							  
							  
                          </tr>
						  <tr><td> 3. By Individual Student</td>
                            <td width="61"><input name="ischecked2" type="radio" value="Name" onClick="setdisplay3();" <?PHP echo $chkname; ?>> </td>
                              <td align="left"><div  id="selectstudent5" style="display:none">Select Student Class: <input id="selectclass3" ></div>						  
                                
                                <!--<input name="GetStudentFee" type="submit" id="GetStudentFee" value="Go"></td>-->
                         </td> </tr>
                         <tr><td></td><td></td><td align="left"><div  id="studentName5" style="display:none"> </div></td></tr>
						  <!--<tr>
                            <td width="60"><strong>Select Term or Section to display </strong></td>
							</tr>
						  <tr>
                            <td width="60" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><label>
                              <input type="checkbox" name="1stTerm" value="1">
                            </label>
                              First Term</td>
                          </tr>
						  <tr>
                            <td width="60" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="2ndTerm" value="2">
                              Second Term</td>
                          </tr>
						  <tr>
                            <td width="60" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="3rdTerm" value="3">
                              Third Term</td>
                          </tr>-->
                        </table>
					  </TD>
					  <TD width="10%" valign="top">
					  <select name="OptStudent" size="20" multiple style="width:32px; background:#66FFFF;">
<?PHP
						if(isset($_POST['GetStudentFee']))
						{
							if($ischecked =="All"){
								$Search_Key = $_POST['Search_Key'];
								$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster order by Stu_Full_Name";
							}elseif($ischecked =="Class"){
								$OptClass = $_POST['OptClass'];
								$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$OptClass' order by Stu_Full_Name";
							}elseif($ischecked =="Name"){
								$StuName = $_POST['StuName'];
								$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where INSTR(Stu_Full_Name,'$StuName') order by Stu_Full_Name";
							}
						}else{
							$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where ID = '0' order by Stu_Full_Name";
						}
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$Stu_Full_Name = $row["Stu_Full_Name"];
								$AdmnNo = $row["AdmissionNo"];
								
								if($OptStudent =="$AdmnNo"){
?>
                       					 	<option value="<?PHP echo $AdmnNo; ?>" selected="selected">&nbsp;&nbsp;&nbsp;<?PHP echo $Stu_Full_Name; ?></option>
<?PHP
										}else{
?>
                        					<option value="<?PHP echo $AdmnNo; ?>">&nbsp;&nbsp;&nbsp;<?PHP echo $Stu_Full_Name; ?></option>
<?PHP
										}
									}
								}
?>
                      </select>
					  </TD>
					</TR>
					<TR>
							<TD colspan="2">
							<div align="center">
							  <input type="submit" name="PrintStudent" value="Show Report">
							  <input type="submit" name="NotifyParent2" value="Notify Parent" disabled>
                              <input type="hidden" name="className5" id="className5">
							  <input type="hidden" name="StudentName5" id="StudentName5">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Books Issued to Employee") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="libreport.php?subpg=Books Issued to Employee">
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
				<p>&nbsp;</p>
				<TABLE width="85%" style="WIDTH: 85%" cellpadding="5" cellspacing="5">
				<TBODY>
					<TR>
					  <TD valign="top"  align="left" ><strong>Filter Criteria</strong></TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
<?PHP
						if($_SESSION['module'] != "Teacher"){
?>
							<input name="isChkEmp" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isChkEmp\',\'\')', 0)" <?PHP echo $chkAll; ?>/>
					  All Employee
<?PHP
						}
?>
					   </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left"><label>
					 <input name="isChkEmp" type="radio" value="Dept" onClick="javascript:setTimeout('__doPostBack(\'isChkEmp\',\'\')', 0)" <?PHP echo $chkDept; ?>> 
					 Department </label>
					 <select name="OptDept" onChange="javascript:setTimeout('__doPostBack(\'OptDept\',\'\')', 0)" style="width:100px;" <?PHP echo $lockdept; ?>>
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
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
								$counter = $counter+1;
								$DeptID = $row["ID"];
								$DeptName = $row["DeptName"];
								
								if($OptDept == "$DeptID"){
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
						</TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left">
					  
					  </TD>
					 </TR>
					 <TR>
					  <TD width="38%" valign="top"  align="left"><label></label></TD>
					 </TR>
					  <TR>
							<TD>
							<div align="center">
							<input type="hidden" name="Totalbox" value="<?PHP echo $counter; ?>">
							  <input type="submit" name="PrintEmployee" value="Print">
							</div>							</TD>
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
