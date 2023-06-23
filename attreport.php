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
	$Page = "Attendance Report";
	$audit=update_Monitory('Login','Administrator',$Page);
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
	if(isset($_GET['totsent']))
	{	
		$totsent = $_GET['totsent'];
		$errormsg = "<font color = blue size = 1>".$totsent." SMS messages was sent successfully</font>";
	}
	if(isset($_POST['OptClass']))
	{	
		$OptClass = $_POST['OptClass'];
	}
	if(isset($_POST['GetStudent']))
	{	
	    if(isset($_POST['OptStudClass'])){
			
		$OptStudClass = $_POST['OptStudClass'];
		
		}else{
			
		  $OptStudClass = $_POST['OptStudClass2'];	
		}
		
		$from_att_Dy = $_POST['fromdate'];
		$to_att_Dy = $_POST['todate'];
		$specific_att_Dy = $_POST['specificdate'];
		$studentname = $_POST['studentname3'];
		
		if(isset($_POST['OptStudClass'])){
		if($_POST['OptStudClass']== ""){
			$errormsg = "<font color = red size = 1>Please Select Class.</font>";
			$PageHasError = 1;
		                     }
		  if($_POST['fromdate']== ""){
			$errormsg = "<font color = red size = 1>Please Select From Date.</font>";
			$PageHasError = 1;
		}
		elseif($_POST['todate']== ""){
			$errormsg = "<font color = red size = 1>Please Select To Date.</font>";
			$PageHasError = 1;
		}
		}
		if(isset($_POST['OptStudClass2'])){
		if($_POST['OptStudClass2']== ""){
			$errormsg = "<font color = red size = 1>Please Select Class.</font>";
			$PageHasError = 1;
			          }
		if($_POST['specificdate']== ""){
			$errormsg = "<font color = red size = 1>Please Select Date.</font>";
			$PageHasError = 1;
		           }
		}
		
		
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=attendance_rpt.php?pg=Student attendance&cid=$OptStudClass&frdt=$from_att_Dy&todt=$to_att_Dy&specificdt=$specific_att_Dy&studname=$studentname&mth=Display\">";
			exit;
		}
	}
	if(isset($_POST['NotifyParent']))
	{	
		$OptStudClass = $_POST['OptStudClass'];
		$attDate = $_POST['att_Yr']."-".$_POST['att_Mth']."-".$_POST['att_Dy'];
		$att_Yr = $_POST['att_Yr'];
		$att_Mth = $_POST['att_Mth'];
		$att_Dy = $_POST['att_Dy'];
		
		if($_POST['att_Yr'] == "" or $_POST['att_Mth'] == "" or $_POST['att_Dy'] == ""){
			$errormsg = "<font color = red size = 1>Attedance date is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptStudClass']){
			$errormsg = "<font color = red size = 1>Select Class.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=attendance_rpt.php?pg=Student attendance&cid=$OptStudClass&dt=$attDate&mth=SMS\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrint']))
	{	
		$StuName = $_POST['StuName'];
		$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
		
		if(!$_POST['OptType']){
			$errormsg = "<font color = red size = 1>Select search type</font>";
			$PageHasError = 1;
		}
		if($AdmissionNo == "-" and !$_POST['StuName']){
			$errormsg = "<font color = red size = 1>either Admission No is empty or Student Name is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=attendance_rpt.php?pg=Student attendance&name=$StuName&adm=$AdmissionNo\">";
			exit;
		}
	}
	if(isset($_POST['ischecked']))
	{
		$att_Yr = $_POST['att_Yr'];
		$att_Mth = $_POST['att_Mth'];
		$att_Dy = $_POST['att_Dy'];
		
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockdept = "disabled='disabled'";
		}elseif($ischecked =="Dept"){
			$chkdept = "checked='checked'";
		}
	}
	if(isset($_POST['OptDepartment']))
	{	
		$OptDepartment = $_POST['OptDepartment'];
	}
	if(isset($_POST['GetEmployee']))
	{
		if(isset($_POST['OptDepartment'])){
			
		$OptDepartment = $_POST['OptDepartment'];
		
		}else{
			
		  $OptDepartment = $_POST['OptDepartment2'];	
		}
		
		$from_att_Dy = $_POST['fromdate'];
		$to_att_Dy = $_POST['todate'];
		$specific_att_Dy = $_POST['specificdate'];
		$employeename = $_POST['employeename3'];
		
		if(isset($_POST['OptDepartment'])){
		if($_POST['OptDepartment']== ""){
			$errormsg = "<font color = red size = 1>Please Select Department.</font>";
			$PageHasError = 1;
		                     }
		  if($_POST['fromdate']== ""){
			$errormsg = "<font color = red size = 1>Please Select From Date.</font>";
			$PageHasError = 1;
		}
		elseif($_POST['todate']== ""){
			$errormsg = "<font color = red size = 1>Please Select To Date.</font>";
			$PageHasError = 1;
		}
		}
		if(isset($_POST['OptDepartment2'])){
		if($_POST['OptDepartment2']== ""){
			$errormsg = "<font color = red size = 1>Please Select Department.</font>";
			$PageHasError = 1;
			          }
		if($_POST['specificdate']== ""){
			$errormsg = "<font color = red size = 1>Please Select Date.</font>";
			$PageHasError = 1;
		           }
		}
		
		
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=attendance_rpt.php?pg=Employee Attendance&did=$OptDepartment&frdt=$from_att_Dy&todt=$to_att_Dy&specificdt=$specific_att_Dy&employeename=$employeename&mth=Display\">";
			exit;
		}
				
	}
	$checked1 = "checked";
	$disabled2 = "disabled";
	if(isset($_POST['Viewtype']))
	{	
		$Viewtype = $_POST['Viewtype'];
		if($Viewtype == "DateRange")
		{
		  $checked1 = "checked";
		  $disabled1 = "";
		  $disabled2 = "disabled";
		}else{
			$checked2 = "checked";
			$disabled2 = "";
			$disabled1 = "disabled";
		}
		//DateRange
		//SpecificDate
		//"checked"
		//$_POST['SubmitGo'] = "SubmitGo";
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
width:auto;
}

.b{
overflow:auto;
width:auto;
height:400px;
}
.b2{
overflow:auto;
width:auto;
height:300px;
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

<SCRIPT 
src="js/json/json2.js" 
type=text/JavaScript></SCRIPT>

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
  dojo.addOnLoad(function() {
		document.getElementById('divLoading').style.display = 'none';
		//document.getElementById('divLoading').style.display = 'block';
						  });
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
<BODY class="tundra" style="TEXT-ALIGN: center" background=Images/news-background.jpg> <div id="divLoading">   </div>
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
					  <TD height="55" align="center"style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><p>&nbsp;</p><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Student attendance details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="attreport.php?subpg=Student attendance details">
				<div>
					<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
					<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
					<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
					</div>
					<script type="text/javascript">
					<!--
					dojo.addOnLoad(function() {
											
						//var viewtype1 = document.getElementById('viewtype1');
               // viewtype1.display = "none" ;
						
											})
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
		function showstudent1(){
			document.getElementById('divLoading').style.display = 'block';
			var studentclass = document.getElementById('OptStudClass').value;
		dojo.xhrGet({
         url: 'selectstudent1.php',
		 handleAs: 'json',
         load: studentselect1,
          error: helloError1,
          content: {name1: studentclass }
              });
		//alert('im good' + class);
					}
					
		function showstudent2(){
			document.getElementById('divLoading').style.display = 'block';
			var studentclass = document.getElementById('OptStudClass2').value;
		 // var class = ClassName.value;
		  dojo.xhrGet({
         url: 'selectstudent1.php',
		 handleAs: 'json',
         load: studentselect2,
         error: helloError1,
         content: {name1: studentclass }
            });
						
		//alert('im good' + class);
		            }
					
		
		function studentselect1(data,ioArgs){
			document.getElementById('divLoading').style.display = 'none';
		var StudentName = document.getElementById('studentselect1');
		  StudentName.innerHTML = "";
		 var studentnamelength = data.studentname.length
		  var studentnameselect ='<select id = "studentname3" name = "studentname3" onchange = "setstudentfee();" ><option >Student Name</option>';
		 for ( var i = 0; i < studentnamelength; i++ ){
			 var studentname = data.studentname[i];
			 studentnameselect += '<option >' + studentname + '</option>';		
			 }
			 studentnameselect+='</select>';
		
		 StudentName.innerHTML = studentnameselect; 	  
		 //var studentinput = document.createElement('select');
		 //studentinput.id = 'studentname';
		// studentinput.value = studentname;
		// StudentName.appendChild(studentinput);
		  // var studentname = '';
			//var studentnametxt = dijit.byId("studentname"); 
		    // studentnametxt.attr("value",studentname);
		 }
		 
		 function studentselect2(data,ioArgs){
			 document.getElementById('divLoading').style.display = 'none';
		var StudentName = document.getElementById('studentselect2');
		  StudentName.innerHTML = "";
		 var studentnamelength = data.studentname.length
		  var studentnameselect ='<select id = "studentname3" name = "studentname3" onchange = "setstudentfee();" ><option >Student Name</option>';
		 for ( var i = 0; i < studentnamelength; i++ ){
			 var studentname = data.studentname[i];
			 studentnameselect += '<option >' + studentname + '</option>';		
			 }
			 studentnameselect+='</select>';
		
		 StudentName.innerHTML = studentnameselect; 	  
		 //var studentinput = document.createElement('select');
		 //studentinput.id = 'studentname';
		// studentinput.value = studentname;
		// StudentName.appendChild(studentinput);
		  // var studentname = '';
			//var studentnametxt = dijit.byId("studentname"); 
		    // studentnametxt.attr("value",studentname);
		 }
		 function helloError1(data, ioArgs) {
        alert('Please Select A Class/Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }
					// -->
					</script>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD valign="top"  align="left" colspan="2">
                      <input name="Viewtype" type="radio" value="DateRange"  <?PHP echo $checked1;  ?> onChange="javascript:setTimeout('__doPostBack(\'Viewtype\',\'\')', 0)"><div><b>View By Date Range:</b></div></td><td  valign="bottom">
                      
                       <a href="#"><div style="color:#03C" onClick="showstudent1();"><b><?PHP if($disabled1 == "")
		{ echo "Click Here To View For A Specific Student In A Selected Class"; }?></b></div></a></td></tr>
									<tr><td width="213" align="center"><div>Select Class :</div></td>
									<td width="139">
									<select name="OptStudClass" id="OptStudClass" <?PHP echo $disabled1;  ?> >
										<option value="" selected="selected">Select</option>
<?PHP
										$counter = 0;
										if($_SESSION['module'] == "Teacher"){
											$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
										}else{
											$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
												
												if($OptStudClass =="$Classname"){
?>
													<option value="<?PHP echo $Classname; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
												}else{
?>
													<option value="<?PHP echo $Classname; ?>"><?PHP echo $Classname; ?></option>
<?PHP
												}
											}
										}
?>
									  </select>
									</td><td width="364"><div id="studentselect1"></div></td>
								</tr>
								<tr>
									<td width="213" align="center"><div>Select Date Range:</div></td>
									<td width="139"> <B> FROM: <input dojoType="dijit.form.DateTextBox" name="fromdate" id="fromdate" size="40" <?PHP echo $disabled1;  ?>>
									TO:<input dojoType="dijit.form.DateTextBox" name="todate" id="todate" size="40" <?PHP echo $disabled1;  ?>>
								   </B> </td><td> <label>
								    <input type="submit" name="GetStudent" value="Go" <?PHP echo $disabled1;  ?>>
								    </label></td>
								</tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
					<TR>
					  <TD valign="top"  align="left" colspan="2"><input name="Viewtype" type="radio" value="SpecificDate" <?PHP echo $checked2; ?>  onChange="javascript:setTimeout('__doPostBack(\'Viewtype\',\'\')', 0)"><div><b>View By A Specific Date:</b></div></td><td style="color:#03C" valign="bottom">
                      <a href="#" d><div style="color:#03C" onClick="showstudent2();"><b><?PHP if($disabled2 == "")
		{ echo "Click Here To View For A Specific Student In A Selected Class"; }?></b></div></a> </td></tr>
									<tr><td width="213" align="center"><div>Select Class :</div></td>
									<td width="139">
									<select name="OptStudClass2" id="OptStudClass2" <?PHP echo $disabled2;  ?> >
										<option value="" selected="selected">Select</option>
<?PHP
										$counter = 0;
										if($_SESSION['module'] == "Teacher"){
											$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
										}else{
											$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
												
												if($OptStudClass =="$Classname"){
?>
													<option value="<?PHP echo $Classname; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
												}else{
?>
													<option value="<?PHP echo $Classname; ?>"><?PHP echo $Classname; ?></option>
<?PHP
												}
											}
										}
?>
									  </select>
									</td><td width="364"><div id="studentselect2"></div></td>
								</tr>
								<tr>
									<td width="213" align="center"><div>Select Date:</div></td>
									<td width="139">
                                    <B> <input dojoType="dijit.form.DateTextBox" name="specificdate" id="specificdate" size="40" <?PHP echo $disabled2;  ?>>
									
								   </B>
									
								    </td><td><label>
								    <input type="submit" name="GetStudent" value="Go" <?PHP echo $disabled2;  ?>>
								    </label></td>
								</tr>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Employee Attendance details") {
?>
				<p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="attreport.php?subpg=Employee Attendance details">
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
					function showemployee1(){
						document.getElementById('divLoading').style.display = 'block';
			var department = document.getElementById('OptDepartment').value;
		dojo.xhrGet({
         url: 'selectemployee.php',
		 handleAs: 'json',
         load: employeeselect1,
          error: helloError1,
          content: {name1: department }
              });
		//alert('im good' + department);
					}
					
		function showemployee2(){
			document.getElementById('divLoading').style.display = 'block';
			var department = document.getElementById('OptDepartment2').value;
		 // var class = ClassName.value;
		  dojo.xhrGet({
         url: 'selectemployee.php',
		 handleAs: 'json',
         load: employeeselect2,
         error: helloError1,
         content: {name1: department }
            });
						
		//alert('im good' + department);
		            }
					
		
		function employeeselect1(data,ioArgs){
			document.getElementById('divLoading').style.display = 'none';
		var EmployeeName = document.getElementById('employeeselect1');
		  EmployeeName.innerHTML = "";
		 var employeenamelength = data.employeename.length
		  var employeenameselect ='<select id = "employeename3" name = "employeename3" onchange = "setstudentfee();" ><option >Employee Name</option>';
		 for ( var i = 0; i < employeenamelength; i++ ){
			 var employeename = data.employeename[i];
			 employeenameselect += '<option >' + employeename + '</option>';		
			 }
			 employeenameselect+='</select>';
		
		 EmployeeName.innerHTML = employeenameselect; 
		 }
		 
		 function employeeselect2(data,ioArgs){
			 document.getElementById('divLoading').style.display = 'none';
		var EmployeeName = document.getElementById('employeeselect2');
		  EmployeeName.innerHTML = "";
		 var employeenamelength = data.employeename.length
		  var employeenameselect ='<select id = "employeename3" name = "employeename3" onchange = "setstudentfee();" ><option >Employee Name</option>';
		 for ( var i = 0; i < employeenamelength; i++ ){
			 var employeename = data.employeename[i];
			 employeenameselect += '<option >' + employeename + '</option>';		
			 }
			 employeenameselect+='</select>';
		
		 EmployeeName.innerHTML = employeenameselect; 	  
		 
		 }
		 function helloError1(data, ioArgs) {
        alert('Please Select A Class/Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }
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
					  <TD valign="top"  align="left" colspan="2">
                      <input name="Viewtype" type="radio" value="DateRange"  <?PHP echo $checked1;  ?> onChange="javascript:setTimeout('__doPostBack(\'Viewtype\',\'\')', 0)"><div><b>View By Date Range:</b></div></td><td  valign="bottom">
                      
                       <a href="#"><div style="color:#03C" onClick="showemployee1();"><b><?PHP if($disabled1 == "")
		{ echo "Click Here To View For A Specific Employee In A Selected Department"; }?></b></div></a></td></tr>
									<tr><td width="213" align="center"><div>Select Department :</div></td>
									<td width="139">
									<select name="OptDepartment" id="OptDepartment" <?PHP echo $disabled1;  ?> >
										<option value="" selected="selected">Select</option>
<?PHP
										$counter = 0;
										if($_SESSION['module'] == "Teacher"){
											$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
										}else{
											$query = "select ID,DeptName from tbdepartments where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by DeptName";
										}
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
												if($OptDepartment =="$DeptID"){
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
									</td><td width="364"><div id="employeeselect1"></div></td>
								</tr>
								<tr>
									<td width="213" align="center"><div>Select Date Range:</div></td>
									<td width="139"> <B> FROM: <input dojoType="dijit.form.DateTextBox" name="fromdate" id="fromdate" size="40" <?PHP echo $disabled1;  ?>>
									TO:<input dojoType="dijit.form.DateTextBox" name="todate" id="todate" size="40" <?PHP echo $disabled1;  ?>>
								   </B> </td><td> <label>
								    <input type="submit" name="GetEmployee" value="Go" <?PHP echo $disabled1;  ?>>
								    </label></td>
								</tr>
                                <tr><td></td></tr>
                                <tr><td></td></tr>
					<TR>
					  <TD valign="top"  align="left" colspan="2"><input name="Viewtype" type="radio" value="SpecificDate" <?PHP echo $checked2; ?>  onChange="javascript:setTimeout('__doPostBack(\'Viewtype\',\'\')', 0)"><div><b>View By A Specific Date:</b></div></td><td style="color:#03C" valign="bottom">
                      <a href="#" d><div style="color:#03C" onClick="showemployee2();"><b><?PHP if($disabled2 == "")
		{ echo "Click Here To View For A Specific Employee In A Selected Department"; }?></b></div></a> </td></tr>
									<tr><td width="213" align="center"><div>Select Department :</div></td>
									<td width="139">
									<select name="OptDepartment2" id="OptDepartment2" <?PHP echo $disabled2;  ?> >
										<option value="" selected="selected">Select</option>
<?PHP
										$counter = 0;
										if($_SESSION['module'] == "Teacher"){
											$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
										}else{
											$query = "select ID,DeptName from tbdepartments where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by DeptName";
										}
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
												if($OptDepartment =="$DeptID"){
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
									</td><td width="364"><div id="employeeselect2"></div></td>
								</tr>
								<tr>
									<td width="213" align="center"><div>Select Date:</div></td>
									<td width="139">
                                    <B> <input dojoType="dijit.form.DateTextBox" name="specificdate" id="specificdate" size="40" <?PHP echo $disabled2;  ?>>
									
								   </B>
									
								    </td><td><label>
								    <input type="submit" name="GetEmployee" value="Go" <?PHP echo $disabled2;  ?>>
								    </label></td>
								</tr>
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
