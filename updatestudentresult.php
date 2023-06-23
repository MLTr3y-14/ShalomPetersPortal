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
	//require_once 'excel_reader2.php';
	//include 'excel_reader2.php';
	global $userNames;
	//$_SESSION['username'] = 'admin';
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	//$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseLogo.processor.php'; 
	$uploadHandler2 = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'excel_result_upload.php'; 
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
	$Page = 'Examination';
	
	//$Page = "Time Table";
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
	
	$ResultUploadmsg = $_SESSION['ResultUploadmsg'];
	
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
		
		
$stmt = $pdo->prepare("SELECT * FROM tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varclass = json_encode($json_row);

$stmt = $pdo->prepare("SELECT * FROM tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varstudent = json_encode($json_row);
if(isset($_POST['OptSelExams']))
	{
		$OptSelExams = $_POST['OptSelExams'];
		$_SESSION['OptSelExams']= $OptSelExams;
		
	}
if(isset($_POST['OptClass']))
	{
		$OptClass = $_POST['OptClass'];
		$_SESSION['OptClass']= $OptClass;
		$query = "select Class_Name from tbclassmaster where ID ='$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				 $row = mysql_fetch_array($result); 
					  
					   $Class_Name = $row["Class_Name"];
		
	}
if(isset($_POST['OptSelStudent']))
	{
		$OptSelStudent = $_POST['OptSelStudent'];
		
	}
if(isset($_POST['OptSelSubject']))
	{
		$OptSelSubject = $_POST['OptSelSubject'];
		$_SESSION['OptSelSubject']= $OptSelSubject;
		$query = "select Subj_name from tbsubjectmaster where ID = '$OptSelSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				 $row = mysql_fetch_array($result); 
					  
					   $Subj_name = $row["Subj_name"];
										
        }
		//echo $Class_Name."_".$Subj_name.".xlx";


if(isset($_POST['excel_data_upload']))
	{
		//$OptSelStudent = $_POST['OptSelStudent'];
		 //echo "hi";
		 //$file = $_FILES['file']['tmp_name'];
         //$handle = fopen($file, "r");
		

	
	//$query = "update tbschool set Logo = '$FullFileName' where ID = '3'";
	//mysql_query($query) or die('Error, query failed');
	//$errormsg = "<font color = blue size = 1>Image uploaded successfully.</font>";
	//header('Location: ' . $uploadSuccess);

	$OptSelExams= $_SESSION['OptSelExams'];
		 $OptClass = $_SESSION['OptClass'];
		 $OptSelSubject = $_SESSION['OptSelSubject'];
		 $FullFileName = $_SESSION['fullfilename'];
		

		 require_once 'excel_reader2.php';
         //$data = new Spreadsheet_Excel_Reader($handle);
		//$data = new Spreadsheet_Excel_Reader("results/".$Class_Name."_".$Subj_name.".xls");
		//$data = new Spreadsheet_Excel_Reader("results/Excel_1999-2003_Data_Upload_Test.xls");
		  $data = new Spreadsheet_Excel_Reader("results/1484313835-Result_Upload_Test.xls");
		 
		//echo $Subj_name;
		 
		   $x = 3;
         // $query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$SelectedClassID'";
		  $query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$FullName = $row["Stu_Full_Name"];
										     $StuID   = $row["AdmissionNo"];
											 $x++;
											//echo $x;
						                     $counter1 = 0;
						  
						 // $query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID'";
						  $query5 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$OptClass' And ExamId = '$OptSelExams' And SubjectId = '$OptSelSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result5 = mysql_query($query5,$conn);
									$num_rows5 = mysql_num_rows($result5);
									if ($num_rows5 > 0 ) {
										while ($row5 = mysql_fetch_array($result5)) 
										{
											
											$SetupId = $row5["ID"];
											$ResultType = $row5["ResultType"];
											$Percentage = $row5["Percentage"];
											$TotalPer = $TotalPer +$Percentage;
											$SetupId2[$counter1] = $SetupId;
											$counter1 = $counter1 +1;
											
										}
									             }
									
								 
								 $ClassId= $OptClass;
						         $ExamId = $OptSelExams;
								 //$StuID = $OptSelStudent;
								 $SubjID = $OptSelSubject;
						          $TotalPer1 = 0;
								  $TotalMark = 0;
								   $y = 1;
								  
						  for($i=0; $i < sizeof($SetupId2); $i++) {
							  $ResultTypeId = $SetupId2[$i]; 
							      $y++;
								 // $w=0;
								//echo $ResultTypeId.' ';
								  								//   }
                   for($w=0;$w<count($data->sheets);$w++) // Loop to get all sheets in a file.
					
                            { 
                                   
								   if(count($data->sheets[$w][cells])>0) // checking sheet not empty
                                      {
									   
                                          // echo "hi";
										   $marks1 = $data->sheets[$w][cells][$x][$y];
										   	
											//echo $marks1.' '.$OptClass.' '.$OptSelExams.' '.$OptSelSubject.' '.$StuID.'-';
											//echo $ResultTypeId.' '.$marks1.'-';
											//echo $marks1.' ';
                                            //echo $OptClass;
											//echo $OptSelExams;
											//echo $OptSelSubject;
											 // echo $StuID;
                                                     }
 
  $query9 = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result9 = mysql_query($query9,$conn);
	          $num_rows9 = mysql_num_rows($result9);
									if ($num_rows9 > 0 ) {
						$query10 = "Update tbstudentperformance set Marks='$marks1' where ResultTypeId ='$ResultTypeId
						' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result10 = mysql_query($query10,$conn);
									}
									else{							
											$query11 = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId','$marks1','$Session_ID','$Term_ID')";
										
										$result11 = mysql_query($query11,$conn);
										
									            
									                                       }
							                                           }
										                          }
															//echo $OptClass;
											//echo $OptSelExams;
											//echo $OptSelSubject;
											 // echo $StuID;
	  
									                        }

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
width:145px;
}

.b{
overflow:auto;
width:auto;
height:320px;
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

<script language="JavaScript">
<!--
<!--
	//function openWin( windowURL, windowName, windowFeatures ) {
	//	return window.open( windowURL, windowName, windowFeatures ) ;
	//}
// -->

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
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
<script type="text/javascript">
<!--
function clearDefault(el) {
if (el.defaultValue==el.value) el.value = ""
}
// -->
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
					<!--
					//function WebForm_OnSubmit() {
					//if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
					//return true;
					//}
		
</script>
<script type="text/javascript">
     dojo.addOnLoad(function() {
		document.getElementById('divLoading').style.display = 'none';
		//document.getElementById('divLoading').style.display = 'block';
		formDlg = dijit.byId("formDialog");					
        // create the dialog:
        //secondDlg = new dijit.Dialog({
        //    title: "Programatic Dialog Creation",
        //    style: "width: 300px"
        //});
  
		                     	
							// display();
							 var cityJson = <?php echo $varclass; ?>;
							 //formDlg = dijit.byId("formDialog");
							   		
                                      new dijit.form.ComboBox({
                                       store: new dojo.data.ItemFileReadStore({
                                    data: cityJson
                                               }),
                                           //autoComplete: true,
			                          searchAttr: "Class_Name",
                                           //query: {
                                               // state: "*"
                                                        //},
                                      style: "width: 150px;",
                                                 // required: true,
                                                id: "selectclass",
                                 onChange: function(city) {
                                  dijit.byId('state').attr('value', (dijit.byId('city').item || {
                                  state: ''
                                        }).state);
                                                }
                                                 },
                                    "selectclass");				
                         
							   		  var studentJson = <?php echo $varstudent; ?>;
                                      new dijit.form.ComboBox({
                                       store: new dojo.data.ItemFileReadStore({
                                    data: studentJson
                                               }),
                                           //autoComplete: true,
			                          searchAttr: "Stu_Full_Name",
                                           //query: {
                                               // state: "*"
                                                        //},
                                      style: "width: 150px;",
                                                 // required: true,
                                                id: "selectstudent",
                                 onChange: "javascript:setTimeout('__doPostBack(\'selectstudent\',\'\')', 0)"
								 //function(city) {
                                 // dijit.byId('state').attr('value', (dijit.byId('city').item || {
                                 // state: ''}).state);}
                                                 },
                                    "selectstudent");		      
                                     
                                      });
					
</script>	
 <script type="text/javascript">
       
	
    		
					  </script>
</HEAD>
<BODY class="tundra" style="TEXT-ALIGN: center" background=Images/news-background.jpg ><div id="divLoading">   </div>
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
			  <TD width="858" align="left" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="20" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
  <?PHP
		if ($SubPage == "Update Student Result") {
?>
      <?PHP echo $errormsg; ?>
	  
	   <?PHP echo $ResultUploadmsg;
        $_SESSION['ResultUploadmsg']= '';
	   ?>
				
                    <script type="text/javascript">
					function showDialog() {
		// set the content of the dialog:
       // secondDlg.attr("content", "Hey, I wasn't there before, I was added at " + new Date() + "!");
        //secondDlg.show();
		formDlg.show();
		//var counter = '1';
		 }
			
		function editResult(data1,data2,data3,data4,data5) {
			
		 document.getElementById('divLoading').style.display = 'block';				
		 var SubjID = data1;
		 var ClassID = data2;
		 var ExamID = data3;
		 var StuID2 = data4;
		  var StuID = dijit.byId('AdmNO').attr("value");
		 //alert(ClassID);
		 //dijit.byId('AdmNO').attr("value")
		 dojo.xhrGet({
         url: 'getstudentresult.php',
		  handleAs: 'json',
         load: showresult,
         error: helloError,
        content: {name: SubjID, name2: ClassID, name3: ExamID, name4: StuID }
		
      });
		
			//			
					}
					
		
		function showresult(data,ioArgs) {
		 var SRlength = data.StudentResultType.length;
		 var SMlength = data.NewStudentMark.length;
		 var IDlength = data.entryid.length;
		 SRlength = SRlength +10;
		 IDlength = IDlength +20;
		var studentresulttype = data.StudentResultType[1];
		
		var txtobj1 = dijit.byId("1"); 
		txtobj1.attr("value","");
		var txtobj2 = dijit.byId("2"); 
		txtobj2.attr("value","");
		var txtobj3 = dijit.byId("3"); 
		txtobj3.attr("value","");
		var txtobj4 = dijit.byId("4"); 
		txtobj4.attr("value","");
		var txtobj5 = dijit.byId("5"); 
		txtobj5.attr("value","");
		var txtobj6 = dijit.byId("6"); 
		txtobj6.attr("value","");
		var txtobj7 = dijit.byId("7"); 
		txtobj7.attr("value","");
		var txtobj8 = dijit.byId("8"); 
		txtobj8.attr("value","");
		var txtobj9 = dijit.byId("9"); 
		txtobj9.attr("value","");
		var txtobj10 = dijit.byId("10"); 
		txtobj10.attr("value","");
		var txtobj11 = dijit.byId("11"); 
		txtobj11.attr("value","");
		var txtobj12 = dijit.byId("12"); 
		txtobj12.attr("value","");
		var txtobj13 = dijit.byId("13"); 
		txtobj13.attr("value","");
		var txtobj14 = dijit.byId("14"); 
		txtobj14.attr("value","");
		var txtobj15 = dijit.byId("15"); 
		txtobj15.attr("value","");
		var txtobj16 = dijit.byId("16"); 
		txtobj16.attr("value","");
		var txtobj17 = dijit.byId("17"); 
		txtobj17.attr("value","");
		var txtobj18 = dijit.byId("18"); 
		txtobj18.attr("value","");
		var txtobj19 = dijit.byId("19"); 
		txtobj19.attr("value","");
		var txtobj20 = dijit.byId("20"); 
		txtobj20.attr("value","");
		
		
		var imark = 0;
		
		if(imark<SMlength){
		var studentmark1 = data.NewStudentMark[0];
		var txtobj1 = dijit.byId("1"); 
		txtobj1.attr("value",studentmark1);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark2 = data.NewStudentMark[1];
		var txtobj2 = dijit.byId("2"); 
		txtobj2.attr("value",studentmark2);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark3 = data.NewStudentMark[2];
		var txtobj3 = dijit.byId("3"); 
		txtobj3.attr("value",studentmark3);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark4 = data.NewStudentMark[3];
		var txtobj4 = dijit.byId("4"); 
		txtobj4.attr("value",studentmark4);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark5 = data.NewStudentMark[4];
		var txtobj5 = dijit.byId("5"); 
		txtobj5.attr("value",studentmark5);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark6 = data.NewStudentMark[5];
		var txtobj6 = dijit.byId("6"); 
		txtobj6.attr("value",studentmark6);
		imark++;
		}
	    
		if(imark<SMlength){
		var studentmark7 = data.NewStudentMark[6];
		var txtobj7 = dijit.byId("7"); 
		txtobj7.attr("value",studentmark7);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark8 = data.NewStudentMark[7];
		var txtobj8 = dijit.byId("8"); 
		txtobj8.attr("value",studentmark8);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark9 = data.NewStudentMark[8];
		var txtobj9 = dijit.byId("9"); 
		txtobj9.attr("value",studentmark9);
		imark++;
		}
		
		if(imark<SMlength){
		var studentmark10 = data.NewStudentMark[9];
		var txtobj10 = dijit.byId("10"); 
		txtobj10.attr("value",studentmark10);
		imark++;
		}/* */
		
		
		var studentsubject = data.subject;
		var txtobjsubject = dijit.byId("subject"); 
		txtobjsubject.attr("value",studentsubject);
		
		var imark2 = 10;
		if(imark2<SRlength){
		var studentmark11 = data.StudentResultType[0];
		var txtobj11 = dijit.byId("11"); 
		txtobj11.attr("value",studentmark11);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark12 = data.StudentResultType[1];
		var txtobj12 = dijit.byId("12"); 
		txtobj12.attr("value",studentmark12);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark13 = data.StudentResultType[2];
		var txtobj13 = dijit.byId("13"); 
		txtobj13.attr("value",studentmark13);
		imark2++;
		}
		
		
		if(imark2<SRlength){
		var studentmark14 = data.StudentResultType[3];
		var txtobj14 = dijit.byId("14"); 
		txtobj14.attr("value",studentmark14);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark15 = data.StudentResultType[4];
		var txtobj15 = dijit.byId("15"); 
		txtobj15.attr("value",studentmark15);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark16 = data.StudentResultType[5];
		var txtobj16 = dijit.byId("16"); 
		txtobj16.attr("value",studentmark16);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark17 = data.StudentResultType[6];
		var txtobj17 = dijit.byId("17"); 
		txtobj17.attr("value",studentmark17);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark18 = data.StudentResultType[7];
		var txtobj18 = dijit.byId("18"); 
		txtobj18.attr("value",studentmark18);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark19 = data.StudentResultType[8];
		var txtobj19 = dijit.byId("19"); 
		txtobj19.attr("value",studentmark19);
		imark2++;
		}
		
		if(imark2<SRlength){
		var studentmark20 = data.StudentResultType[9];
		var txtobj20 = dijit.byId("20"); 
		txtobj20.attr("value",studentmark20);
		imark2++;
		}
		
		var imark3 = 20;
		if(imark3<IDlength){
		var studentmark21 = data.entryid[0];
		var txtobj21 = dijit.byId("21"); 
		txtobj21.attr("value",studentmark21);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark22 = data.entryid[1];
		var txtobj22 = dijit.byId("22"); 
		txtobj22.attr("value",studentmark22);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark23 = data.entryid[2];
		var txtobj23 = dijit.byId("23"); 
		txtobj23.attr("value",studentmark23);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark24 = data.entryid[3];
		var txtobj24 = dijit.byId("24"); 
		txtobj24.attr("value",studentmark24);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark25 = data.entryid[4];
		var txtobj25 = dijit.byId("25"); 
		txtobj25.attr("value",studentmark25);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark26 = data.entryid[5];
		var txtobj26 = dijit.byId("26"); 
		txtobj26.attr("value",studentmark26);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark27 = data.entryid[6];
		var txtobj27 = dijit.byId("27"); 
		txtobj27.attr("value",studentmark27);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark28 = data.entryid[7];
		var txtobj28 = dijit.byId("28"); 
		txtobj28.attr("value",studentmark28);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark29 = data.entryid[8];
		var txtobj29 = dijit.byId("29"); 
		txtobj29.attr("value",studentmark29);
		imark3++;
		}
		
		if(imark3<IDlength){
		var studentmark30 = data.entryid[9];
		var txtobj30 = dijit.byId("30"); 
		txtobj30.attr("value",studentmark30);
		imark3++;
		}
		var studentsubjectid = data.subjectid;
		var txtobjA = dijit.byId("subjectid"); 
		txtobjA.attr("value",studentsubjectid);
		
		var classsubjectid = data.classid;
		var txtobjB = dijit.byId("classid"); 
		txtobjB.attr("value",classsubjectid);
		
		var studentexamid = data.examid;
		var txtobjC = dijit.byId("examid"); 
		txtobjC.attr("value",studentexamid);
		
		var studentid2 = data.studentid;
		var txtobjD = dijit.byId("studentid"); 
		txtobjD.attr("value",studentid2);
		document.getElementById('divLoading').style.display = 'none';
		showDialog();
                
				 }
		
		function editDialog(){
			var studentResultEntry = document.getElementById('name');
		   studentResultEntry.innerHTML = '';
	      studentResultEntry.innerHTML = teacher;
		  
		  
			
		}
					
					
		 function updateresult()
{
//alert("Nice job!");
document.getElementById('divLoading').style.display = 'block';
dojo.xhrGet({
			
         url: 'updatestudentresult2.php',
         load: helloCallback2,
         error: helloError,
         content: {name1: dijit.byId('1').attr("value"), name2: dijit.byId('2').value, name3: dijit.byId('3').value, name4: dijit.byId('4').value, name5: dijit.byId('5').value, name6: dijit.byId('6').value, name7: dijit.byId('7').value, name8: dijit.byId('8').value, name9: dijit.byId('9').value, name10: dijit.byId('10').value, name11: dijit.byId('11').value, name12: dijit.byId('12').value, name13: dijit.byId('13').value, name14: dijit.byId('14').value, name15: dijit.byId('15').value, name16: dijit.byId('16').value, name17: dijit.byId('17').value, name18: dijit.byId('18').value, name19: dijit.byId('19').value, name20: dijit.byId('20').value, name21: dijit.byId('21').value, name22: dijit.byId('22').value, name23: dijit.byId('23').value, name24: dijit.byId('24').value, name25: dijit.byId('25').value, name26: dijit.byId('26').value, name27: dijit.byId('27').value, name28: dijit.byId('28').value, name29: dijit.byId('29').value, name30: dijit.byId('30').value, name31: dijit.byId('subjectid').value, name32: dijit.byId('classid').value, name33: dijit.byId('examid').value, name34: dijit.byId('studentid').value }
      });

}
function helloCallback2(data,ioArgs) {
		var theForm = document.forms['form1'];
					if (!theForm) {
						theForm = document.form1;
					}
		theForm.submit();
		//location.reload(true);
     }
	
                    
	function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }
	</script>
                   
                     <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
                    
                    <form name="import" action="<?php echo $uploadHandler2 ?>" method="post" enctype="multipart/form-data">
                        <TR><TD width="12%" valign="top"  align="right"><strong> Upload Result Via Excel </strong></TD>
					  <TD width="21%" valign="top"  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="file" type="file" name="file">
                      <input name="excel_data_upload" type="submit" id="excel_data_upload" value="Upload" <?PHP if (!($OptSelExams!='' && $OptClass != '' && $OptSelSubject != '')){ echo "disabled";} ?>>
                      </TD></TR>
                      </form>
                    <TR>
                    <form name="form1" id="form1" method="post" action="updatestudentresult.php?subpg=Update Student Result">
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
                    <TR><TD width="12%" valign="top"  align="right"><strong> &nbsp; </strong></TD>
					  <TD width="21%" valign="top"  align="left">&nbsp;
                      
                      </TD></TR>
                      
                      <TR><TD width="12%" valign="top"  align="right"><strong> &nbsp; </strong></TD>
					  <TD width="21%" valign="top"  align="left">&nbsp;
                      
                      </TD></TR>
					  <TD width="12%" valign="top"  align="right"><strong>Select Class Name </TD>
					  <TD width="21%" valign="top"  align="left">
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)">
						<option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
						  $query = "select EmpID from tbusermaster where UserName='$userNames'";
		                   $result = mysql_query($query,$conn);
		                   $dbarray = mysql_fetch_array($result);
		                     $Teacher_EmpID  = $dbarray['EmpID'];
						 $query = "select ClassId, SubjectId from tbclassteachersubj where EmpId = $Teacher_EmpID and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						$counter = 0;
					         if ($num_rows > 0 ) {
								 
								 while ($row = mysql_fetch_array($result)) 
						{
							     //$teacherid2[$counter] = $row["ID"];
								 //$classid[$counter] = $row["ClassId"];
								 $teacherid2 = $row["ID"];
								 $classid = $row["ClassId"];
						
						
						$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' and ID ='$classid'";
						
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
							<option value="<?PHP echo $ClassID; $SelectedClassID = $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
								}
								
							}
						  }
						
						}
						}else{
							
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
								$counter = $counter+1;
								$ClassID = $row["ID"];
								$Classname = $row["Class_Name"];
								
								if($OptClass =="$ClassID"){
?>
							<option value="<?PHP echo $ClassID; $SelectedClassID = $ClassID; ?>" selected="selected"><?PHP echo $Classname; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $ClassID; ?>"><?PHP echo $Classname; ?></option>
<?PHP
								}
								
							}
						}
						

							
							
						}
						
?>
					  </select>
                      </TD></TR>
                      <TR><TD width="12%" valign="top"  align="right"><strong> Select Examination Name </TD>
					  <TD width="21%" valign="top"  align="left"> <select name="OptSelExams" onChange="javascript:setTimeout('__doPostBack(\'OptSelExams\',\'\')', 0)">
						<option value="" selected="selected">Select</option>
                        <?PHP
									$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ExamName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$ExamID = $row["ExamID"];
											$ExamName = $row["ExamName"];
											if($OptSelExams ==$ExamID){
?>
												<option value="<?PHP echo $ExamID; ?>" selected="selected"><?PHP echo $ExamName; ?></option>
<?PHP
											}else{
?>
												<option value="<?PHP echo $ExamID; ?>"><?PHP echo $ExamName; ?></option>
<?PHP
											}
										}
									}
?>
</select> </TD></TR>
<TR><TD width="12%" valign="top"  align="right"><strong> Select Student Name </strong></TD>
					  <TD width="21%" valign="top"  align="left"> <select name="OptSelStudent" onChange="javascript:setTimeout('__doPostBack(\'OptSelStudent\',\'\')', 0)">
						<option value="" selected="selected">Select</option>
                        <?PHP
									$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$SelectedClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$FullName = $row["Stu_Full_Name"];
										     $StuID   = $row["AdmissionNo"];
											if($OptSelStudent ==$StuID){
?>
												<option value="<?PHP echo $StuID; ?>" selected="selected"><?PHP echo $FullName; ?></option>
<?PHP
											}else{
?>
												<option value="<?PHP echo $StuID; ?>"><?PHP echo $FullName; ?></option>
<?PHP
											}
										}
									}
?>
                        </select></TD></TR>
                        
                      <TR><TD width="12%" valign="top"  align="right"><strong> Select Subject (If Uploading Via Excel) </strong></TD>
					  <TD width="21%" valign="top"  align="left"> <select name="OptSelSubject" onChange="javascript:setTimeout('__doPostBack(\'OptSelStudent\',\'\')', 0)">
						<option value="" selected="selected">Select</option>
                        <?PHP
									$ClassId= $OptClass;
						  $ExamId = $OptSelExams;
						  $StuID = $OptSelStudent;
						  $query4 = "select ID,SubjectId from tbclasssubjectrelation where ClassId ='$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result4 = mysql_query($query4,$conn);
						  $num_rows4 = mysql_num_rows($result4);
									if ($num_rows4 > 0 ) {
										while ($row4 = mysql_fetch_array($result4)) 
										{
											$SubjID = $row4["SubjectId"];
										     //$StuID   = $row["AdmissionNo"];
											if($OptSelSubject ==$SubjID){
?>
												<option value="<?PHP echo $SubjID; ?>" selected="selected"><?PHP echo GetSubjectName($SubjID); ?></option>
<?PHP
											}else{
?>
												<option value="<?PHP echo $SubjID; ?>"><?PHP echo GetSubjectName($SubjID); ?></option>
<?PHP
											}
										}
									}
?>
                        </select></TD></TR>
				    </TBODY> 
				</TABLE>
                
				<BR>
                <table width="672" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="107" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
							<td width="107" bgcolor="#F4F4F4" colspan="1"><div align="center" class="style21">Subject</div></td>
							<td colspan="3" bgcolor="#F4F4F4"><div align="center" class="style21">Details</div></td>
                          </tr>
                          <tr>
                            <td width="107" height="20" bgcolor="#FFFFFF">&nbsp;</td>
                            <td width="107" height="20" bgcolor="#FFFFFF">&nbsp;</td>
                            <td width="134" bgcolor="#ECE9D8"><strong>ASSESSMENT</strong></td>
							<td width="120" height="20" bgcolor="#ECE9D8">
								<strong>STUDENT SCORE</strong></td>
							<td width="156" height="20" bgcolor="#ECE9D8"><strong>ALLOCATED MARK </strong></td>
								  </tr>
                     <?PHP 
					      
				          $counter = 0;
						  $ClassId= $OptClass;
						  $ExamId = $OptSelExams;
						  $StuID = $OptSelStudent;
						  $query4 = "select ID,SubjectId from tbclasssubjectrelation where ClassId ='$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result4 = mysql_query($query4,$conn);
						  $num_rows4 = mysql_num_rows($result4);
									if ($num_rows4 > 0 ) {
										while ($row4 = mysql_fetch_array($result4)) 
										{
											$counter = $counter +1;
											$SetupId = $row4["ID"];
											$SubjID = $row4["SubjectId"];
											$TotalPer = $TotalPer +$Percentage;
											//$StuID1 = '5667.23';
											$StuID1 = ConvertToNo($StuID);
											$StuIDkey = NoKeyFlag($StuID);
?>
						  
						  
						                         
								<tr><td height="32" ><div align="center"><?PHP echo $counter; ?></div></td>
								<td height="32" ><a href="#" onClick="editResult(<?PHP echo $SubjID.','.$ClassId.','.$ExamId.','.$StuID1.','.$StuIDkey; ?>);"><?PHP echo GetSubjectName($SubjID); ?></a> </td>
                                <td  ><table width="121">
                                 <?PHP 
								 $SetupId2 = array();
								 $ClassId= $OptClass;
						         $ExamId = $OptSelExams;
						          $counter1 = 0;
						  
						  $query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											
											$SetupId = $row["ID"];
											$ResultType = $row["ResultType"];
											$Percentage = $row["Percentage"];
											$TotalPer = $TotalPer +$Percentage;
											$SetupId2[$counter1] = $SetupId;
											$counter1 = $counter1 +1;
?>
                                
								<tr><td width="38" height="32" align="left" colspan="1"><div id="<?PHP echo $ResultType; ?>" onClick="editResult2()"> <?PHP echo $ResultType; ?></div></td>
                                </tr>
                                	
                                <?PHP
										}
									             }
												 
								  ?>
                                  
                                  </table></td><td><table width="121">
                                  <?PHP 
								 
								 $ClassId= $OptClass;
						         $ExamId = $OptSelExams;
								 $StuID = $OptSelStudent;
						          $TotalPer1 = 0;
								  $TotalMark = 0;
						  for($i=0; $i < sizeof($SetupId2); $i++) {
							  $ResultTypeId = $SetupId2[$i]; 
						  $query = "select ID,Marks from tbstudentperformance where class_id ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID' And AdmnNo = '$StuID' And ResultTypeId = '$ResultTypeId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result = mysql_query($query,$conn);
									//$num_rows = mysql_num_rows($result);
									//if ($num_rows > 0 ) {
										//while ($row = mysql_fetch_array($result)) 
										//{
											$row = mysql_fetch_array($result);
											//$counter2 = $counter2 +1;
											$SetupId = $row["ID"];
											//$ResultTypeId = $row["ResultType"];
											$marks = $row["Marks"];
											$TotalMark = $TotalMark +$marks;
?>
                                
								<tr>
                                <td width="32" height="32" align="center" > <?PHP echo $marks; ?></td></tr>
                                	
                                <?PHP
										}
									             
								  ?>
                                  
                                  </table></td><td>
                                  <table width="121">
                                  <?PHP 
								 $ClassId= $OptClass;
						         $ExamId = $OptSelExams;
								 $TotalPer1 = 0;
						  
						  
						  $query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$counter1 = $counter1 +1;
											$SetupId = $row["ID"];
											$ResultType = $row["ResultType"];
											$Percentage = $row["Percentage"];
											$TotalPer1 = $TotalPer1 +$Percentage;
?>
                                
								<tr>
                                <td width="32" height="32" align="center" > <?PHP echo $Percentage; ?></td></tr>
                                	
                                <?PHP
										}
									             }
								  ?>
                                  
                                  </table></td>
                                  <tr><td></td><td></td><td></td><td width="169"><hr><strong>Total</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $TotalMark; ?>%<hr></td><td width="110"><hr><strong>Total</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $TotalPer1; ?>%<hr></td></tr>
                                  </tr>
                                  
                    <?PHP
							       }
									    }
										//echo $SetupId2[4];
							?>	
                               <input dojoType="dijit.form.TextBox" type="hidden" name=" AdmNO" id="AdmNO" value="<?PHP echo $StuID; ?>">
                                  </table></form>
   
   <div dojoType="dijit.Dialog" id="formDialog" title="EDIT/UPDATE STUDENT RESULT" style="display: none" execute="updateresult();">
        <script type="dojo/event" event="onSubmit" args="e">
            dojo.stopEvent(e); // prevent the default submit
            if (!this.isValid()) {
                window.alert('Please fix fields');
                return;
            }

            window.alert("Would submit here via xhr");
            // dojo.xhrPost( {
            //      url: 'foo.com/handler',
            //      content: { field: 'go here' },
            //      handleAs: 'json'
            //      load: function(data) { .. },
            //      error: function(data) { .. }
            //  });
            
        </script>
    <table>
      <tr><td colspan="2">  <input dojoType="dijit.form.TextBox" type="text" name="subject" id="subject" style="color: #FFF; font-weight:800; background-color: #999"></td></tr>
        <tr>
        
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="11" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="1" >
            </td>
        </tr>
       <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="12" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="2">
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="13" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="3" >
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="14" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
               <input dojoType="dijit.form.TextBox" type="text" name="name" id="4">
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="15" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
               <input dojoType="dijit.form.TextBox" type="text" name="name" id="5">
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="16" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="6">
                <input dojoType="dijit.form.TextBox" type="hidden" name="subjectid" id="subjectid">
                <input dojoType="dijit.form.TextBox" type="hidden" name="classid" id="classid">
                <input dojoType="dijit.form.TextBox" type="hidden" name="examid" id="examid">
                <input dojoType="dijit.form.TextBox" type="hidden" name="studentid" id="studentid">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="21">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="22">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="23">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="24">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="25">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="26">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="27">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="28">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="29">
                <input dojoType="dijit.form.TextBox" type="hidden" name="entryid" id="30">
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="17" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
               <input dojoType="dijit.form.TextBox" type="text" name="name" id="7">
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="18" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
               <input dojoType="dijit.form.TextBox" type="text" name="name" id="8">
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="19" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
               <input dojoType="dijit.form.TextBox" type="text" name="name" id="9">
            </td>
        </tr>
        <tr>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="20" style="color: #FFF; font-weight:800; background-color: #999">
            </td>
            <td>
               <input dojoType="dijit.form.TextBox" type="text" name="name" id="10">
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <button dojoType="dijit.form.Button" type="submit" onClick="return dijit.byId('formDialog').isValid();pagereload();">
                    Done
                </button>
                <button dojoType="dijit.form.Button" id="button2" type="button" onClick="dijit.byId('formDialog').hide();">
                    Cancel
                </button>
            </td>
        </tr>
    </table>
</div>
            
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
