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
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = 'Class Activities';
	
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
 $query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];                   
$stmt = $pdo->prepare("select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
                         // do something
                         $stmt->execute();
                        $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$varclass = json_encode($row2);     
    //echo $varclass;
	
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
                        display();
						formDlg = dijit.byId("formDialog");
							 })
					
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
		if ($SubPage == "Promote Class Student") {
?>


      <?PHP echo $errormsg; ?>
      
 <script type="text/javascript">
              
				//var formDlg;
	              // var secondDlg;
				function display() {
					   var classJSON = <?PHP echo $varclass; ?>;
					               displayclass(classJSON);
						  //alert(classJSON);
					 
					 }
				
			function displayclass(data) {
						//alert("i'm here and good");
						//get the placeholder element from the page
						var listBox = document.getElementById('group');
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
							entry.innerHTML = data[i].Class_Name;// +''+ data[i].Last
							field.appendChild( entry ); //insert entry into the field
							listBox.appendChild( field ); //display the field
						} //end for
					}//end function displayAll
					// -->
					
					 
					
					function handleOnClick(){
						document.getElementById('divLoading').style.display = 'block';
						
						var classname = eval('this.innerHTML');
						
						dojo.xhrGet({
                          url: 'getclassstudent.php',
		                   handleAs: 'json',
                          load: displayStudent,
                             error: helloError,
                            content: {name:classname }
                                     });
					}
					function displayStudent(data,ioArgs){
						var studentnamelength = data.studentname.length;
						//var subjectlength = data.subjectname.length;
						var classlistBox = document.getElementById('classstudent');
						classlistBox.innerHTML = '';
						var entryRow = document.getElementById('entrycolumn');
						entryRow.innerHTML = '';
						
						 var key = data.studentname[0];
						 var key2 = 'No Student'
						 var j = 1;
						if ( key == key2 )
						{
							var newcolumn = document.createElement('tr');
						     var entry = document.createElement('td');
							 entry.height = '45px';
							var field = document.createElement('fieldset');
							//entry.onclick = handleOnClick2; // set onclick event handler
							//entry.id = data.teacherid2[i]; // set the id
							field.innerHTML ='No Student In Class';// +''+ data[i].Last
							
							entry.appendChild(field); //insert entry into the field
							newcolumn.appendChild(entry);
							classlistBox.appendChild(newcolumn);
							//entry.innerHTML = 'No Student In Class';// +''+ data[i].Last
							
						
						
						
						}else{
							for ( var i = 0; i < studentnamelength; i++ ){
							 var newcolumn = document.createElement('tr');
						     var entry = document.createElement('td');
							 entry.height = '45px';
							var field = document.createElement('fieldset');
							//entry.onclick = handleOnClick2; // set onclick event handler
							//entry.id = data.teacherid2[i]; // set the id
							field.innerHTML = data.studentname[i];// +''+ data[i].Last
							
							entry.appendChild(field); //insert entry into the field
							newcolumn.appendChild(entry);
							classlistBox.appendChild(newcolumn);
							
							var entryCell2 = document.createElement('tr');
							var entry2 = document.createElement('td');
							entry2.height = '45px';
							var field2 = document.createElement('fieldset');
							var entryCheckBox = document.createElement('input');
							entryCheckBox.type = 'checkbox';
							//entry2.appendChild(entryCheckBox);
							field2.appendChild(entryCheckBox);
							entryCheckBox.value = data.studentname[i];
							entryCheckBox.name = 'studentname' + j;
							entry2.appendChild(field2);
							entryCell2.appendChild(entry2);
							entryRow.appendChild(entryCell2);
							j++;
						   	}
						}
						//var editBox = document.getElementById('edit');
						//editBox.innerHTML = '';
				         //editBox.innerHTML = "Click Here To Add New Class";
						 var name = data.name[0];
						 //var name = 'good';
						var teacherClass = document.getElementById('addclass');
						  teacherClass.innerHTML = '';
						  teacherClass.innerHTML = name;
						var teacherClass2 = document.getElementById('addclass2');
						  teacherClass2.innerHTML = '';
						  teacherClass2.innerHTML = name;  
						
						document.getElementById('divLoading').style.display = 'none';
					}
					
			function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
                        }		
					
					</script>
              <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="25%" valign="top"  align="left">CLICK ON A CLASS TO VIEW STUDENTS<div id="group" style="display:block">   </div></TD><TD valign="top"  align="center">
                  <table width="600px" style=" font-weight: bold;"align="left">
                  <tr><td width="350" align="left">CLASS STUDENT<div id="addclass"></div></td><td width="350" align="left">SELECT STUDENTS AS APPROPRIATE<div id="addclass2"></div></td></tr>
                 
                  <tr>
                    <td  align="left" valign="top"><table id="classstudent" width="250px" style=" font-weight: bold;"align="left" ></table></td>
                    <td align="left" valign="top"><table id="entrycolumn" width="150px" style=" font-weight: bold;"align="left" ></table></td>
                    </tr>
                    <tr><td> </td></tr></table>
                               </TD></TR>
                               <TR><TD colspan="2" align="center"> <button dojoType="dijit.form.Button" name="updateattendance" id="updateattendance">Promote Selected Class Student <script type="dojo/method" event="onClick">
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
			
			 </script></button></TD></TR></TBODY></TABLE>
 
  <?PHP
		}elseif ($SubPage == "Daily Class Activities") {
?>
  <?PHP echo $errormsg; ?>
   <?PHP 
    $activities = 'PUNCTUALITY';?>
      
 <script type="text/javascript">
              
				//var formDlg;
	              // var secondDlg;
				function display() {
					   var classJSON = <?PHP echo $varclass; ?>;
					               displayclass(classJSON);
						  //alert(classJSON);
					 
					 }
				
			function displayclass(data) {
						//alert("i'm here and good");
						//get the placeholder element from the page
						var listBox = document.getElementById('group');
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
							entry.innerHTML = data[i].Class_Name;// +''+ data[i].Last
							field.appendChild( entry ); //insert entry into the field
							listBox.appendChild( field ); //display the field
						} //end for
					}//end function displayAll
					// -->
					
					 
					
					function handleOnClick(){
						document.getElementById('divLoading').style.display = 'block';
						
						var classname = eval('this.innerHTML');
						
						dojo.xhrGet({
                          url: 'getclassstudent.php',
		                   handleAs: 'json',
                          load: displayStudent,
                             error: helloError,
                            content: {name:classname }
                                     });
					}
					function displayStudent(data,ioArgs){
						var studentnamelength = data.studentname.length;
						//var subjectlength = data.subjectname.length;
						var classlistBox = document.getElementById('classstudent');
						classlistBox.innerHTML = '';
						var entryRow = document.getElementById('entrycolumn');
						entryRow.innerHTML = '';
						
						 var key = data.studentname[0];
						 var key2 = 'No Student'
						 var j = 1;
						if ( key == key2 )
						{
							var newcolumn = document.createElement('tr');
						     var entry = document.createElement('td');
							 entry.height = '45px';
							var field = document.createElement('fieldset');
							//entry.onclick = handleOnClick2; // set onclick event handler
							//entry.id = data.teacherid2[i]; // set the id
							field.innerHTML ='No Student In Class';// +''+ data[i].Last
							
							entry.appendChild(field); //insert entry into the field
							newcolumn.appendChild(entry);
							classlistBox.appendChild(newcolumn);
							//entry.innerHTML = 'No Student In Class';// +''+ data[i].Last
							
						
						
						
						}else{
							for ( var i = 0; i < studentnamelength; i++ ){
							 var newcolumn = document.createElement('tr');
						     var entry = document.createElement('td');
							 entry.height = '45px';
							//var field = document.createElement('fieldset');
							entry.onclick = handleOnClick2; // set onclick event handler
							entry.id = data.studentname[i]; // set the id
							entry.innerHTML = data.studentname[i];// +''+ data[i].Last
							
							//entry.appendChild(field); //insert entry into the field
							newcolumn.appendChild(entry);
							classlistBox.appendChild(newcolumn);
							j++;
						   	}
						}
						//var editBox = document.getElementById('edit');
						//editBox.innerHTML = '';
				         //editBox.innerHTML = "Click Here To Add New Class";
						 var name = data.name[0];
						 //var name = 'good';
						var teacherClass = document.getElementById('addclass');
						  teacherClass.innerHTML = '';
						  teacherClass.innerHTML = name;
						var teacherClass2 = document.getElementById('addclass2');
						  teacherClass2.innerHTML = '';
						  teacherClass2.innerHTML = name;  
						
						document.getElementById('divLoading').style.display = 'none';
					}
					
			function handleOnClick2(){
				var studentname = eval('this.innerHTML');
				var studentname2 = document.getElementById('studentname');
					  studentname2.value = studentname;
				
							showDialog();
						       
			            }
						
			function handleOnClick3(){
				
				 
							//j++;
			          }
					  
					  function showDialog() {
		        formDlg.show();
		//alert("kolade");
		
		 
    }
	
 function OnChangeCheckbox21 (checkbox) {
            if (checkbox.checked) {
                //alert ("The check box is checked.");
				var activity210value = document.getElementById('activity221');
					  activity210value.value = document.getElementById("activity21").value;
            }
            else {
                //alert ("The check box is not checked.");
				var activity210value = document.getElementById('activity221');
					  activity210value.value = '';
            }
        }
	
	function OnChangeCheckbox22 (checkbox) {
            if (checkbox.checked) {
                //alert ("The check box is checked.");
				var activity310value = document.getElementById('activity222');
					  activity310value.value = document.getElementById("activity22").value;
            }
			else {
                //alert ("The check box is not checked.");
				var activity310value = document.getElementById('activity222');
					  activity310value.value = '';
            }
	 }
            
		function OnChangeCheckbox23 (checkbox) {
            if (checkbox.checked) {
                //alert ("The check box is checked.");
				var activity410value = document.getElementById('activity223');
					  activity410value.value = document.getElementById("activity23").value;
            }
			else {
                //alert ("The check box is not checked.");
				var activity410value = document.getElementById('activity223');
					  activity410value.value = '';
            }
           }
	
	function displaymessage()
{
	 
	//var datevalue = document.getElementById('classdate2');
					 // datevalue.value = dijit.byId("classdate").value;				  
		
		  
 
  document.getElementById('divLoading').style.display = 'block';	

      dojo.xhrPost({
      url: 'updateactivities.php',
	  handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'form1'
                  });

}

function updatesuccessful(data, ioArgs) {
	     studentname = data.studentname;
		  activitydate = data.activitydate;
		  //var activitystatus = data.activitystatus;
		  alert(activitydate);
	document.getElementById('divLoading').style.display = 'none';	
       
		                 }		
					
					
			function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
                        }		
					
					</script>
              <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
                    
                    <TR><TD colspan="2" align="center"> </TD></TR>
                    <TR height="30px"><TD colspan="2" align="center"> </TD></TR>
					<TR>
					  <TD width="25%" valign="top"  align="left">CLICK ON A CLASS TO VIEW STUDENTS<div id="group" style="display:block">   </div></TD><TD valign="top"  align="center">
                  <table width="600px" style=" font-weight: bold;"align="left">
                  <tr><td width="350" align="left">CLASS STUDENT<div id="addclass"></div></td><td width="350" align="left">MY DAY AT AGAPE BUNDLES ACTIVITIES<div id="addclass2"></div></td></tr>
                 
                  <tr>
                    <td  align="left" valign="top"><table id="classstudent" width="250px" style=" font-weight: bold;"align="left" ></table></td>
                    <td align="left" valign="top"><table id="entrycolumn" width="150px" style=" font-weight: bold;"align="left" ></table></td>
                    </tr>
                    <tr><td> </td></tr></table>
                               </TD></TR>
                               <TR><TD colspan="2" align="center"> </TD></TR></TBODY></TABLE> 
                               
     <div dojoType="dijit.Dialog" id="formDialog" title="MY DAY AT AGAPE BUNDLE" style="display: none" execute="displaymessage();" >
               
        
		<script type="dojo/event" event="onSubmit" args="e">
              //dojo.stopEvent(e); // prevent the default submit
           // if (!this.isValid()) {
              // window.alert('Please fix fields');
              // return;
            //}

          // window.alert("Would submit here via xhr");
            // dojo.xhrPost( {
            //      url: 'foo.com/handler',
            //      content: { field: 'go here' },
            //      handleAs: 'json'
            //      load: function(data) { .. },
            //      error: function(data) { .. }
            //  });
            
        </script>
    <table width="1000px">
     <tr>
            <td align="left" colspan="3"> <strong> DATE:
             <form name="form1" id="form1" method="post" action="" >
             <input dojoType="dijit.form.DateTextBox" name="classdate" id="classdate" size="40">
                  <input type="hidden" type="text" name="studentname" id="studentname" /> 
                             <input type="hidden" name="activity221" id="activity221" /> 
                                 <input type="hidden" name="activity222" id="activity222" />  
                                  <input type="hidden" name="activity223" id="activity223" />    

</strong> </td></tr>
        
        <tr>
            <td colspan="3"> 

            </td>
           </tr>
       <tr>
            <td colspan="3">
                <label for="Activity1">
                    <b> <?PHP echo $activities; ?></b>
                </label>
            </td>
           </tr>
           <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           
       <tr>
            <td>
               I was early to School <input name="activity21" id="activity21" type="checkbox"  onclick="OnChangeCheckbox21 (this)" value="activity21">
            </td>
            <td>
              I was late to School <input name="activity22" id="activity22" type="checkbox"  onclick="OnChangeCheckbox22 (this)" value="activity22">
            </td>
            <td>
              I was in bed during Assembly<input name="activity23" id="activity23" type="checkbox"  onclick="OnChangeCheckbox23 (this)" value="activity23">
            </td>
        </tr>
     
     <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td colspan="3"><b>MY STATE OF HEALTH</b>
                
            </td>
           </tr>
     
     <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td>
               I was well <input name="activity31" id="activity31" type="checkbox" value="activity31">
            </td>
            <td>
              I was Quarantied <input name="activity32" id="activity32" type="checkbox" value="activity32">
            </td>
            <td>
              I was feverish<input name="activity33" id="activity33" type="checkbox" value="activity33">
            </td>
        </tr>
        <tr>
            <td>
               I had slight headache <input name="activity34" id="activity34" type="checkbox" value="activity34">
            </td>
            <td>
              I had stomach ache <input name="activity35" id="activity35" type="checkbox" value="activity35">
            </td>
            <td>
              I threw up<input name="activity36" id="activity36" type="checkbox" value="activity36">
            </td>
        </tr>
        <tr>
            <td>
               I stooled <input name="activity37" id="activity37" type="checkbox" value="activity37">
            </td>
            <td>
              I has running nose <input name="activity38" id="activity38" type="checkbox" value="activity38">
            </td>
            <td>
              I had cough<input name="activity39" id="activity39" type="checkbox" value="activity33">
            </td>
        </tr>
        <tr>
            <td>&nbsp;
               
            </td>
            <td>
              I had blisters <input name="activity310" id="activity310" type="checkbox" value="activity31">
            </td>
            <td>&nbsp;
              
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td colspan="3"><b>MY FEEDING HABIT</b>
                
            </td>
           </tr>
     
     <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td>
               I ate my first meal at <input name="activity41" id="activity41" type="text">
            </td>
            <td>
              I finished all my food <input name="activity42" id="activity42" type="text" >
            </td>
            <td>
              I ate my other meal<input name="activity43" id="activity43" type="text">
            </td>
        </tr>
        <tr>
            <td>
               I ate all my snacks <input name="activity44" id="activity44" type="checkbox" value="activity44">
            </td>
            <td>
              I had no snacks<input name="activity45" id="activity45" type="checkbox" value="activity45">
            </td>
            <td>
              I had my lunch by<input name="activity46" id="activity46" type="text">
            </td>
        </tr>
        <tr>
            <td>
               I finished all my food<input name="activity47" id="activity47" type="text">
            </td>
            <td>
              I ate my other meal <input name="activity48" id="activity48" type="text">
            </td>
            <td>
              I was given an alternative meal for<input name="activity49" id="activity49" type="text">
            </td>
        </tr>
        <tr>
            <td>&nbsp;
               
            </td>
            <td>
              I had my last meal by <input name="activity410" id="activity410" type="text">
            </td>
            <td>&nbsp;
              
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td colspan="3"><b>MY TEMPERAMENT</b>
                
            </td>
           </tr>
     
     <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td>
               Moody <input name="activity51" id="activity51" type="checkbox" value="activity51">
            </td>
            <td>
              Sad <input name="activity52" id="activity52" type="checkbox" value="activity52">
            </td>
            <td>
              Lively<input name="activity53" id="activity53" type="checkbox" value="activity53">
            </td>
        </tr>
        <tr>
            <td>
               Happy <input name="activity54" id="activity54" type="checkbox" value="activity54">
            </td>
            <td>
              I stuck to my Caregiver(s) <input name="activity54" id="activity54" type="checkbox" value="activity54">
            </td>
            <td>&nbsp;
              
            </td>
        </tr>
         <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td colspan="3"><b>NAP</b>
                
            </td>
           </tr>
     
     <tr>
            <td colspan="3">&nbsp;
                
            </td>
           </tr>
           <tr>
            <td>
               I slept for long<input name="activity51" id="activity51" type="checkbox" value="activity51">
            </td>
            <td>
              I did not sleep for long <input name="activity52" id="activity52" type="checkbox" value="activity52">
            </td>
            <td>
              I did not sleep at all<input name="activity53" id="activity53" type="checkbox" value="activity53">
            </td>
        </tr>
         <tr>
            <td colspan="3"> I slept <input name="activity48" id="activity48" type="text">Times
                
            </td>
            </tr>
            <tr>
            <td colspan="3">&nbsp;
                
            </td>
            </tr>
             <tr>
            <td colspan="3"><b>HOME-WORK PAGE</b>
                
            </td>
            </tr>
            <tr>
            <td colspan="3">&nbsp;
                
            </td>
            </tr>
            <tr>
            <td>
               I did my home-work<input name="activity51" id="activity51" type="checkbox" value="activity51">
            </td>
            <td>
              I did my home-work half way<input name="activity52" id="activity52" type="checkbox" value="activity52">
            </td>
            <td>
              My home-work was done for me<input name="activity53" id="activity53" type="checkbox" value="activity53">
            </td>
        </tr>
         <tr>
            <td colspan="3"> I didn't do my home-work<input name="activity53" id="activity53" type="checkbox" value="activity53">
                
            </td>
            </tr>
            <tr>
            <td colspan="3">&nbsp;
                
            </td>
            </tr>
           <tr>
            <td align="center" colspan="3">
                
               <button dojoType="dijit.form.Button" type="submit" onClick="return dijit.byId('formDialog').isValid();" >
                   DONE
                </button>
                <button dojoType="dijit.form.Button" type="button" onClick="dijit.byId('formDialog').hide();">
                    CANCEL
                </button>
            </td>
        </tr>
    </table>
     
                              
</div>  </form>

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
