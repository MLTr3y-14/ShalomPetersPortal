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
	include 'sms/sms_processor.php';
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
	$Page = "Attendance";
	$audit=update_Monitory('Login','Administrator',$Page);
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$PageHasError = 0;
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	
	if(isset($_POST['OptClass']))
	{	
		$OptClass = $_POST['OptClass'];
		$query = "select ID from tbclassmaster where Class_Name = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result = mysql_query($query,$conn);
		$row = mysql_fetch_array($result);
		$ClassID = $row["ID"];
	
		$query = "select * from tbclassmaster where ID='$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$MaxLecAll  = $dbarray['MaxLec'];
		$BreakLec  = $dbarray['BreakLec'];
		$AvgWidth = 90/ ($MaxLecAll+1);
		$i=0;
		$query3 = "select ID from tbsubjectmaster where ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$OptClassEdit') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$SubjID = $row["ID"];
				$arr_Subj[$i]=$SubjID;
				$i = $i+1;
			}
		}
		$arr_Mon_Subj = "";
		$arr_Tue_Subj = "";
		$arr_Wed_Subj = "";
		$arr_Thur_Subj = "";
		$arr_Fri_Subj = "";
		$arr_Sat_Subj = "";
		
		$arr_Mon_LecNo = "";
		$arr_Tue_LecNo = "";
		$arr_Wed_LecNo = "";
		$arr_Thur_LecNo = "";
		$arr_Fri_LecNo = "";
		$arr_Sat_LecNo = "";
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' and WeekDay = 'Monday' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Mon_LecNo[$i]= $row["LecNo"];
				$arr_Mon_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' and WeekDay = 'Tuesday' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Tue_LecNo[$i]= $row["LecNo"];
				$arr_Tue_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' and WeekDay = 'Wednesday' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Wed_LecNo[$i]= $row["LecNo"];
				$arr_Wed_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' and WeekDay = 'Thurday' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Thur_LecNo[$i]= $row["LecNo"];
				$arr_Thur_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' and WeekDay = 'Friday' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Fri_LecNo[$i]= $row["LecNo"];
				$arr_Fri_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
		$i=1;
		$query3 = "select LecNo,SubjID from tbclasstimetable where ClassID = '$ClassID' and WeekDay = 'Saturday' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$LecNo = $row["LecNo"];
				$arr_Sat_LecNo[$i]= $row["LecNo"];
				$arr_Sat_Subj[$i] = $row["SubjID"]; 
				$i = $i+1;
			}
		}
	
	}
	

$stmt = $pdo->prepare("SELECT * FROM tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varclass = json_encode($json_row);

$stmt = $pdo->prepare("SELECT * FROM tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
");
// do something
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row2 = array ('items'=>$row2);             
$varteacher = json_encode($json_row2);

$stmt = $pdo->prepare("SELECT * FROM tbdepartmentswhere Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
");
// do something
$stmt->execute();
$row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row3 = array ('items'=>$row3);             
$vardepartment = json_encode($json_row3);



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
<script type="text/javascript">

dojo.addOnLoad(function() {
		//formDlg = dijit.byId("formDialog");	
		
		
		document.getElementById('divLoading').style.display = 'none';
        
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
            id: "selectclass",
            onChange: function setclass(){
	
	//alert('im good');
	setclassinput();
                   },
                   
		     },"selectclass");
		
		/*var dataJson2 = <?php echo varemployee; ?>;				
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
          //  onChange: function setemployee(){
	
	//alert('im good');
	//getemployee();
      //             },
                   
		     },"selectemployee");*/
		
		
		
	});	
function setclassinput(){
	
	var classname = dijit.byId("selectclass").value;
var classhiddeninput = document.getElementById('OptClass');
                classhiddeninput.value = classname ;
				//alert(classname);
				var theForm = document.forms['form1'];
					if (!theForm) {
						theForm = document.form1;
					}
		theForm.submit();
}
  
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
			  <TD width="218" style="background:url(images/side-menu.gif);" valign="top" align="left">
			  		<div id="Issue"><p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
                    </div>
			  </TD>
			  <TD width="860" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="93" align="center"style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><p>&nbsp;</p><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Lecture attendance") {
?>
				<p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" id="form1" method="post" action="attendance.php?subpg=Lecture attendance">
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
					
					dojo.addOnLoad(function() {
											
						document.getElementById('LectureAttendancePopup').style.display = 'none';				
											})
			       </script>
					<script type="text/javascript">
					
                     function showinfoDialog(data1,data2,data3,data4){
						  document.getElementById('divLoading').style.display = 'block';
	                   var classid = data1;
					   var subjectid = data2;
					    var lectureNo = data3;
						var lectureday = data4;
						var subjectid2 = document.getElementById('subjectid');
					       subjectid2.value = subjectid;
						   var lectureno2 = document.getElementById('lectureno');
					       lectureno2.value = lectureNo;
	                    // alert('im good' + classid + subjectid + lectureNo);
						  dojo.xhrGet({
                    url: 'getattendanceinfo.php',
		              handleAs: 'json',
                       load: getattendanceinfo,
                              error: helloError,
                           content: {name1: classid, name2: subjectid, name3: lectureNo, name4:lectureday }
                               });
						  
						   // var lectureno2 = document.getElementById('lectureno');
					       //lectureno2.value = lectureno;
						
                      }
						
						
			function getattendanceinfo(data,ioArgs){
				var studentnamelength = data.StudentName.length;
				var studentname = data.StudentName[0];
				var listBox = document.getElementById('studentname');
						listBox.innerHTML = '';
						var entryRow2 = document.createElement('tr');
						var entryCell1 = document.createElement('td');
						 entryCell1.innerHTML = 'Students Name:';
						 var entryCell2 = document.createElement('td');
						 entryCell2.innerHTML = '';
						 entryRow2.appendChild(entryCell1);
						 entryRow2.appendChild(entryCell2);
						 listBox.appendChild(entryRow2);
						//var i = 0;
						var j = 0;
						for ( var i = 1; i <= studentnamelength; i++ )
						{   
						    
						    var entryRow = document.createElement('tr');
							var l = 0;
							if(l < 2){
							var entryCell = document.createElement('td');
							//dynamically create a div element for each entry and a fieldset element to place it in
							var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							//entry.onclick = handleOnClick; // set onclick event handler
							entry.innerHTML = data.StudentName[j];// +''+ data[i].Last
							field.appendChild(entry); //insert entry into the field
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							 //display the field
							l++;
							//j++;
							}
							
							if(l < 2){
							var entryCell = document.createElement('td');
							//var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							var entryCheckBox = document.createElement('input');
							//entryCell.width = 24;
							entryCheckBox.type = 'checkbox';
							field.appendChild(entryCheckBox);
							entryCheckBox.value = data.StudentName[j];
							entryCheckBox.name = 'studentname' + i;
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							l++;
							   }
							  j++;
							 // listBox.innerHTML = 'Student Names:';
							listBox.appendChild(entryRow);
							
						    } //end for
							
						
				var class2 = data.ClassName;
				var lectureNo = data.lectureNo;
				var teacher2 = data.EmployeeName;
				var subject = data.SubjectName;
				var LectureDay = data.LectureDay;
				var empid = data.empid;
				if(LectureDay == 1){
					var lectureday = 'Monday';
				}
				else if(LectureDay == 2){
					var lectureday = 'Tuesday';
				}
				else if(LectureDay == 3){
					var lectureday = 'Wednesday';
				}
				else if(LectureDay == 4){
					var lectureday = 'Thursday';
				}
				else if(LectureDay == 5){
					var lectureday = 'Friday';
				}
				else if(LectureDay == 6){
					var lectureday = 'Saturday';
				}
				else if(LectureDay == 7){
					var lectureday = 'Sunday';
				}
				
				var subjectlistBox = document.getElementById('classinfo');
						subjectlistBox.innerHTML = '';
						var topinfo = 'Update Subject Teacher and Student Attendance For';
						subjectlistBox.innerHTML = topinfo + ' ' + class2 +' ' + lectureday + '----' + 'Lecture No:' + ' ' + lectureNo ;
				
				   var subjectlistBox2 = document.getElementById('teachername');
						subjectlistBox2.innerHTML = '';
						var entryRow2 = document.createElement('tr');
						var entryCell1 = document.createElement('td');
						 entryCell1.innerHTML = 'Subject Teacher Name:';
						 var entryCell2 = document.createElement('td');
						 entryCell2.innerHTML = '';
						 entryRow2.appendChild(entryCell1);
						 entryRow2.appendChild(entryCell2);
						 subjectlistBox2.appendChild(entryRow2);
						var entryRow = document.createElement('tr');
							var l = 0;
							if(l < 2){
							var entryCell = document.createElement('td');
							//dynamically create a div element for each entry and a fieldset element to place it in
							var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							//entry.onclick = handleOnClick; // set onclick event handler
							entry.innerHTML = teacher2;// +''+ data[i].Last
							field.appendChild(entry); //insert entry into the field
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							 //display the field
							l++;
							//j++;
							}
							
							if(l < 2){
							var entryCell = document.createElement('td');
							//var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							var entryCheckBox = document.createElement('input');
							//entryCell.width = 24;
							entryCheckBox.type = 'checkbox';
							field.appendChild(entryCheckBox);
							entryCheckBox.value =  teacher2;
							entryCheckBox.name = 'teachername2';
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							l++;
							   }
							  //subjectlistBox2.innerHTML = 'Teacher Name:';
							subjectlistBox2.appendChild(entryRow);
							 var studentnamelength2 = document.getElementById('studentnamelength');
					       studentnamelength2.value = studentnamelength;
						  // var teachername2 = document.getElementById('teachername2');
					       //teachername2.value = teacher;
						//subjectlistBox2.innerHTML = teacher;
				//alert('im good' + class + teacher2 + subject + studentname);
				document.getElementById('divLoading').style.display = 'none';
			       document.getElementById('LectureAttendancePopup').style.display = 'block';
				   
			}
			
		
		function updatesuccessful(data,ioArgs) {
		
	//alert(chargelength + studentcharge + studentpayment + studentname );
	        var attendancedate = data.attendancedate;
				var admnNo = data.admnNo;
				//var subjectid = data.subjectid;
				var teacherid = data.teacherid;
	    alert('Class Lecture Attendance Updated Successfully');
		 document.getElementById('LectureAttendancePopup').style.display = 'none';	
		  document.getElementById('divLoading').style.display = 'none';
	 
          }
		function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
                    }
					
	function WebForm_OnSubmit() {
					if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
					return true;
					}
					
					</script>
                    <p></p>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR><TD width="54%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SELECT A CLASS TO UPDATE LECTURE ATTENDANCE</div></TD><TD width="46%" valign="bottom">  <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Current Section: <?PHP echo $Activeterm; ?></strong></TD></TR>
                    
                    <TR><TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="selectclass">
                                          </TD></TR>
                                       <TR><TD id="classname" align="left" style="font-weight:bold"><div style="color:#00F">Click On A Subject To Update Attendance</div></TD><td align="left">Lecture TimeTable For <?php echo $OptClass; ?></td><TD></TD></TR>   
					  <TR><TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
					  		<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
							<TBODY>
							<TR bgcolor="#00CCFF">
							  <TD width="10%" align="left" valign="top">&nbsp;</TD>
<?PHP
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									
									$query = "select TimeNo from tbclasstimetable where LecNo='$i' And ClassID = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$TimeNo  = $dbarray['TimeNo'];
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div><br>$TimeNo</TD>";
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>BREAK</div></TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='left' valign='top'><div align='center' class='style3'>$i</div><br>$TimeNo</TD>";
									}
								}

?>
                              </TR><TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Monday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Mon_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Mon_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 1;
?>
											<a href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo]); ?><?PHP //echo '/'.$OptClass; ?><?PHP // echo $arr_Mon_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Mon_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Mon_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 1;
?>
											<a href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Mon_Subj[$LNo]); ?> </a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Tuesday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Tue_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Tue_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 2;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);">
	<?PHP echo GetSubjectName($arr_Tue_Subj[$LNo]); ?><?PHP echo '/'.$OptClass; ?><?PHP // echo $arr_Tue_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Tue_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Tue_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 2;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);">
	<?PHP echo GetSubjectName($arr_Tue_Subj[$LNo]); ?><?PHP // echo  '/'.$OptClass; ?><?PHP //echo $arr_Tue_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Wednesday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Wed_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Wed_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 3;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo]); ?><?PHP // echo '/'.$OptClass; ?><?PHP //echo $arr_Wed_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Wed_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Wed_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 3;
?>
											<a   href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Wed_Subj[$LNo]); ?><?PHP //echo '/'.$OptClass; ?><?PHP //echo $arr_Wed_Subj[$LNo]; ?></a>

<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Thursday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Thur_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Thur_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 4;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo]); ?><?PHP //echo  '/'.$OptClass; ?><?PHP //echo $arr_Thur_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Thur_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Thur_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 4;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Thur_Subj[$LNo]); ?><?PHP //echo '/'.$OptClass; ?><?PHP //echo $arr_Thur_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Friday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Fri_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Fri_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 5;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Fri_Subj[$LNo]); ?><?PHP //echo '/'.$OptClass; ?><?PHP //echo $arr_Fri_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Fri_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Fri_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 5;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Fri_Subj[$LNo]); ?><?PHP //echo '/'.$OptClass; ?><?PHP //echo $arr_Fri_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							<TR>
							  <TD width="10%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3">Saturday</span></TD>
<?PHP
							  	$LNo = 1;
								for($i=1;$i<=$MaxLecAll;$i++)
								{
									if($i==$BreakLec){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Sat_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Sat_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 6;
?>
											<a  href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo]); ?><?PHP //echo '/'.$OptClass; ?><?PHP //echo $arr_Sat_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
										echo "<TD width='$AvgWidth%'  align='center'' valign='top'>&nbsp;</TD>";
									}elseif($i<=$MaxLecAll){
										echo "<TD width='$AvgWidth%'  align='center'' valign='top' bgcolor='#FFFFCC'>";
										if($arr_Sat_LecNo[$LNo] == $i	){
											$subjectname2 = GetSubjectName($arr_Sat_Subj[$LNo]);
											$subjectid = GetSubjectID($subjectname2);
											$lectureday = 6;
?>
											<a href="#" onClick="showinfoDialog(<?PHP echo $ClassID.','.$subjectid.','.$i.','.$lectureday; ?>);"><?PHP echo GetSubjectName($arr_Sat_Subj[$LNo]); ?><?PHP //echo '/'.$OptClass; ?><?PHP //echo $arr_Sat_Subj[$LNo]; ?></a>
<?PHP
											$LNo = $LNo +1;
										}
										echo "</TD>";
									}
								}
?>
							 
							</TR>
							</TBODY>
							</TABLE>
							</TD>
					</TR>
				</TBODY>
				</TABLE>  
                <div id="LectureAttendancePopup"><div id="InsideTable"><table height="200" width="400" bgcolor="#FFFFFF" style="BORDER-RIGHT: #000 1px solid; BORDER-TOP: #000 1px solid; BORDER-LEFT: #000 1px solid; BORDER-BOTTOM: #000 1px solid;" ><tr><td valign="top"><div id="classinfo"></div></td></tr><tr><td valign="top"> <strong> DATE: <input dojoType="dijit.form.DateTextBox" name="classdate" id="classdate" size="40"></strong></td></tr><tr><td valign="top"><table><div id="teachername"></div></table></td></tr><tr><td valign="top"><table align="center"><div id="studentname"></div></table></td></tr><tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button dojoType="dijit.form.Button" name="updateattendance" id="updateattendance">Update Attendance <script type="dojo/method" event="onClick">
				
				var AttendanceDate = document.getElementById('attendancedate');		
				     var dateAttendance = dijit.byId("classdate").value;
					 AttendanceDate.value = dateAttendance;
				
		 var datElementValue = document.getElementById("attendancedate");
	    if ( datElementValue.value.length == 0){
		   alert('Please Select A Date');
		   datElementValue.focus();
		   return false;
	      }
		 // alert(datElementValue);
			
			document.getElementById('divLoading').style.display = 'block';
            dojo.xhrPost({
      url: 'updatelectureattendance.php',
	  handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'form1'
                  });        
		
		 // alert('im good');
			
			 </script></button><button dojoType="dijit.form.Button" name="closedialog" id="closedialog">Cancel <script type="dojo/method" event="onClick">
		
			document.getElementById('LectureAttendancePopup').style.display = 'none';
			 </script></button></td></tr></table></div></div>
               <input type="hidden" name="studentnamelength" id="studentnamelength" />
                <input type="hidden" name="subjectid" id="subjectid" />
                <input type="hidden" name="attendanecedate" id="attendancedate" />
                <input type="hidden" name="lectureno" id="lectureno" />
                <input type="hidden" name="OptClass" id="OptClass" value="<?PHP echo $OptClass ?>" />
                <input type="hidden" name="teachername" id="teachername" />
				</form>
<?PHP
		}elseif ($SubPage == "Class Daily Attendance") {
?>
				<p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" id="form1" method="post" action="attendance.php?subpg=Class Daily Attendance">
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
            onChange: function setclass(){
	
	//alert('im good');
	getstudent();
                   },
                   
		     },"selectclass2");
		
		
	});	
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
					
					function getstudent() {
						//alert('im good');
						document.getElementById('divLoading').style.display = 'block';
						
		var classvalue = document.getElementById('classvalue');
					  classvalue.value = dijit.byId("selectclass2").value;				
		 dojo.xhrGet({
         url: 'selectstudent1.php',
		 handleAs: 'json',
         load: studentname,
         error: helloError,
         content: {name1: dijit.byId('selectclass2').attr("value")}
      });
					}
				
				
				function studentname(data,ioArgs) {
					var studentnamelength = data.studentname.length;
					var listBox = document.getElementById('studentname2');
						listBox.innerHTML = '';
						var j = 0;
						for ( var i = 1; i <= studentnamelength; i++ )
						{   
						    
						    var entryRow = document.createElement('tr');
							var l = 0;
							if(l < 2){
							var entryCell = document.createElement('td');
							//dynamically create a div element for each entry and a fieldset element to place it in
							var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							//entry.onclick = handleOnClick; // set onclick event handler
							entry.innerHTML = data.studentname[j];// +''+ data[i].Last
							field.appendChild(entry); //insert entry into the field
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							 //display the field
							l++;
							//j++;
							}
							
							if(l < 2){
							var entryCell = document.createElement('td');
							//var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							var entryCheckBox = document.createElement('input');
							//entryCell.width = 24;
							entryCheckBox.type = 'checkbox';
							field.appendChild(entryCheckBox);
							entryCheckBox.value = data.studentname[j];
							entryCheckBox.name = 'studentname' + i;
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							l++;
							   }
							  j++;
							listBox.appendChild(entryRow);
							
						    } //end for
							
					 var AttendanceDate = document.getElementById('attendancedate');		
				     var dateAttendance = dijit.byId("classdate").value;
					 AttendanceDate.value = dateAttendance; 
					 
					 var namelength = document.getElementById('studentnamelength');
					  namelength.value = studentnamelength;
					  
					  var namelength = document.getElementById('studentnamelength');
					  namelength.value = studentnamelength;
				    // alert('im good');         
					    document.getElementById('divLoading').style.display = 'none';     
			        }
		function updatesuccessful(data,ioArgs) {
		document.getElementById('divLoading').style.display = 'none';
	//alert(chargelength + studentcharge + studentpayment + studentname );
	     alert('Class Attendance Updated Successfully');
	 
          }
			
			function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
                    }
					// -->
					</script>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="44%" align="left">
					  		<table width="828" cellpadding="4">
                            <TR><TD width="200" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-weight:bold"> Select A Class To Update Daily Student Attendance</div></TD><TD width="200" valign="bottom">  <strong>Current Section: <?PHP echo $Activeterm; ?></strong></TD><TD width="228" valign="bottom"><strong> DATE: <input dojoType="dijit.form.DateTextBox" name="classdate" id="classdate" size="40"></strong></TD></TR>
                    
								<tr>
									<td width="400"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="selectclass2"></td></tr>
                               <tr><td ><div style="color:#F00">Select Tick-Box As Appropriate</div></td></tr><tr><td><table id="studentname2"></table> </td></tr>
							</table>
							
							
					    </TD>
					</TR>
                    <TR><TD align="center"> <button dojoType="dijit.form.Button" name="updateattendance" id="updateattendance">Update Attendance <script type="dojo/method" event="onClick">
					var classvalue = document.getElementById('classvalue');
					  classvalue.value = dijit.byId("selectclass2").value;
	
	        var AttendanceDate = document.getElementById('attendancedate');		
				     var dateAttendance = dijit.byId("classdate").value;
					 AttendanceDate.value = dateAttendance; 
	//if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) 
	   // {return false;}
	 
		  
		  var classElementValue = document.getElementById("classvalue");
	    if ( classElementValue.value.length == 0){
		   alert('Please Select A Class');
		   datElementValue.focus();
		   return false;
	      }
		  
		  var datElementValue = document.getElementById("attendancedate");
	    if ( datElementValue.value.length == 0){
		   alert('Please Select A Date');
		   datElementValue.focus();
		   return false;
	      }
		 // alert(datElementValue);
		 document.getElementById('divLoading').style.display = 'block';	
            dojo.xhrPost({
      url: 'updatestudentattendance.php',
	  //handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'form1'
                  });        
		
		 // alert('im good');
			
			 </script></button></TD></TR>
				</TBODY>
				</TABLE><input type="hidden" name="studentnamelength" id="studentnamelength" />
                <input type="hidden" name="attendanecedate" id="attendancedate" />
                <input type="hidden" name="classvalue" id="classvalue" />
                <input type="hidden" name="term" id="term" />
				</form>
<?PHP
		}elseif ($SubPage == "Employee attendance") {
?>
				<p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" id="form1" method="post" action="attendance.php?subpg=Employee attendance">
				<div>
					<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
					<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
					<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
					</div>
					<script type="text/javascript">
					<!--
		dojo.addOnLoad(function() {
									
		var deptJson = <?php echo $vardepartment;?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: deptJson
            }),
            //autoComplete: true,
			searchAttr: "DeptName",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectdepartment",
            onChange: function setemployee(){
	         //alert('im good');
	             getemployee();
                  },
                   
		     },"selectdepartment");
		
		
	});	
	
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
					  function getemployee(){
						   document.getElementById('divLoading').style.display = 'block';
					    // alert('im good');
						 //var classvalue = document.getElementById('classvalue');
					  //classvalue.value = dijit.byId("selectclass2").value;				
		 dojo.xhrGet({
         url: 'getemployee.php',
		 handleAs: 'json',
         load: employeename,
         error: helloError,
         content: {name1: dijit.byId('selectdepartment').attr("value")}
      });
					  }
					  
				function employeename(data,ioArgs) {
					var employeenamelength = data.employeename.length;
					var listBox = document.getElementById('employeename');
						listBox.innerHTML = '';
						var j = 0;
						for ( var i = 1; i <= employeenamelength; i++ )
						{   
						    
						    var entryRow = document.createElement('tr');
							var l = 0;
							if(l < 2){
							var entryCell = document.createElement('td');
							//dynamically create a div element for each entry and a fieldset element to place it in
							var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							//entry.onclick = handleOnClick; // set onclick event handler
							entry.innerHTML = data.employeename[j];// +''+ data[i].Last
							field.appendChild(entry); //insert entry into the field
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							 //display the field
							l++;
							//j++;
							}
							
							if(l < 2){
							var entryCell = document.createElement('td');
							//var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							var entryCheckBox = document.createElement('input');
							//entryCell.width = 24;
							entryCheckBox.type = 'checkbox';
							field.appendChild(entryCheckBox);
							entryCheckBox.value = data.employeename[j];
							entryCheckBox.name = 'employeename' + i;
							entryCell.appendChild(field);
							entryRow.appendChild(entryCell);
							l++;
							   }
							  j++;
							listBox.appendChild(entryRow);
							
						    } //end for
							
					/* var AttendanceDate = document.getElementById('attendancedate');		
				     var dateAttendance = dijit.byId("classdate").value;
					 AttendanceDate.value = dateAttendance; 
					 
					 var namelength = document.getElementById('studentnamelength');
					  namelength.value = studentnamelength;
					  
					  var namelength = document.getElementById('studentnamelength');
					  namelength.value = studentnamelength;
				    // alert('im good');  */       
					  document.getElementById('divLoading').style.display = 'none';        
			        }
				function updatesuccessful(data,ioArgs) {
		
	//alert(chargelength + studentcharge + studentpayment + studentname );
	     alert('Employee Attendance Updated Successfully');
		  document.getElementById('divLoading').style.display = 'none';
	 
          }
					
					function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
                    }
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
					  <TD colspan="2" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  	<TABLE width="100%" align="center" style="WIDTH: 100%" cellpadding="5" cellspacing="4">
							<TBODY>
								<TR>
								  <TD width="279"  align="left"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-weight:bold"> Select Department/Employee To Update Daily Attendance</div></TD><TD width="220" valign="bottom">  <strong>Current Section: <?PHP echo $Activeterm; ?></strong></TD><TD width="285" valign="bottom"><strong> DATE: <input dojoType="dijit.form.DateTextBox" name="classdate" id="classdate" size="40"></strong></TD></TR>
                                  <tr>
									<td width="279"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="selectdepartment"></td></tr>
                               <tr><td ><div style="color:#F00">Select Tick-Box As Appropriate</div></td></tr><tr><td><table id="employeename"></table> </td></tr>
								 
							</TBODY></table>
							<input type="hidden" name="employeevalue" id="employeevalue" >
                            <input type="hidden" name="attendanecedate" id="attendancedate" />
							
					    </TD>
					</TR>
                    <TR><TD align="center"> <button dojoType="dijit.form.Button" name="updateattendance" id="updateattendance">Update Attendance <script type="dojo/method" event="onClick">
                    var employeevalue = document.getElementById('employeevalue');
					  employeevalue.value = dijit.byId("selectdepartment").value;
	
	        var AttendanceDate = document.getElementById('attendancedate');		
				     var dateAttendance = dijit.byId("classdate").value;
					 AttendanceDate.value = dateAttendance; 
	//if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) 
	   // {return false;}
	 
		  
		  var classElementValue = document.getElementById("employeevalue");
	    if ( classElementValue.value.length == 0){
		   alert('Please Select Department/Employee');
		   datElementValue.focus();
		   return false;
	      }
		  
		  var datElementValue = document.getElementById("attendancedate");
	    if ( datElementValue.value.length == 0){
		   alert('Please Select A Date');
		   datElementValue.focus();
		   return false;
	      }
		 // alert(datElementValue);
		 document.getElementById('divLoading').style.display = 'block';
			
            dojo.xhrPost({
      url: 'updateemployeeattendance.php',
	  //handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'form1'
                  });        
		
		 // alert('im good');</script>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}
?>
				
              </th>
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
