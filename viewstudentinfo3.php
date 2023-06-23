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
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
?>
<?PHP $dsn = "mysql:host=localhost;dbname=skoolnet";
$username = "root";

$password = "tingate200";

try {

    $pdo = new PDO($dsn, $username, $password);

}

catch(PDOException $e) {

    die("Could not connect to the database\n");

}
$stmt = $pdo->prepare("SELECT * FROM tbstudentmaster");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varstudent = json_encode($json_row);

$stmt = $pdo->prepare("SELECT * FROM tbemployeemasters");
// do something
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row2 = array ('items'=>$row2);             
$varteacher = json_encode($json_row2);
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
.style22 {color: #FFFFFF}
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
	var formDlg;
	var secondDlg;
    dojo.addOnLoad(function() {
		formDlg = dijit.byId("formDialog");					
        // create the dialog:
        //secondDlg = new dijit.Dialog({
        //    title: "Programatic Dialog Creation",
        //    style: "width: 300px"
        //});
    
 var cityJson = <?php echo $varstudent; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: cityJson
            }),
            //autoComplete: true,
			searchAttr: "Stu_Full_Name",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectstudent",
            onChange: function(city) {
                dijit.byId('state').attr('value', (dijit.byId('city').item || {state: ''}).state);}
        
		  },"selectstudent");
		
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
					  <TD height="55" align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
<?PHP
		if ($SubPage == "View Student Info") {
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
			  
			  var name = data[0].Stu_Full_Name;
			  var class = data.studentclass;
			  var address = data[0].Stu_Address;
			  var date = data[0].Stu_DOB;
			  var gender = data[0].Stu_Gender;
			  var state = data[0].Stu_State;
			  var phone = data[0].Stu_Phone;
			  var email = data[0].Stu_Email;
			  var studenttype = data[0].Stu_Type;
			  var studentweight = data['studentotherdetails'][0].Stu_Wt;
			  //var studentweight = '';
			  var studentheight =  data['studentotherdetails'][0].Stu_Ht;
			  var studentage =  data['studentotherdetails'][0].Stu_Age;
			  var grname =  data['studentotherdetails'][0].Gr_Name_Mr;
			  var graddress =  data['studentotherdetails'][0].Gr_Addr;
			  var grphoneno =  data['studentotherdetails'][0].Phy_MbPh;
			  var bloodgroup =  data['studentotherdetails'][0].BloodGroup;
			  var studentreligion =  data['studentotherdetails'][0].Religion;
			  var emmergencyno =  data['studentotherdetails'][0].EmergNO;
			  var picture = data.studentpicture;

			var listBox = document.getElementById('studentname');
			listBox.innerHTML = '';
			listBox.innerHTML = '<fieldset><b>STUDENT NAME:</b>' + ' ' + name + '</fieldset><p><fieldset><b>STUDENT CLASS:</b>' + ' ' + class + '</fieldset></p><p><fieldset><b>STUDENT ADDRESS:</b>' + ' ' + address + '</fieldset></p><p><fieldset><b>STUDENT DATE OF BIRTH:</b>' + ' ' + date + '</fieldset></p><p><fieldset><b>STUDENT GENDER:</b>' + ' ' + gender + '</fieldset></p><p><fieldset><b>STUDENT STATE:</b>' + ' ' + state + '</fieldset></p><p><fieldset><b>STUDENT PHONE:</b>' + ' ' + phone + '</fieldset></p><p><fieldset><b>STUDENT EMAIL:</b>' + ' ' + email + '</fieldset></p><p><fieldset><b>STUDENT WEIGHT:</b>' + ' ' + studentweight + '</fieldset></p><p><fieldset><b>STUDENT HEIGHT:</b>' + ' ' + studentheight + '</fieldset></p><p><fieldset><b>STUDENT AGE:</b>' + ' ' + studentage + '</fieldset></p><p><fieldset><b>PARENT/GUARDIAN NAME:</b>' + ' ' + grname + '</fieldset></p><p><fieldset><b>PARENT/GUARDIAN CONTACT ADDRESS:</b>' + ' ' + graddress + '</fieldset></p><p><fieldset><b>PARENT/GUARDIAN PHONE NO:</b>' + ' ' + grphoneno + '</fieldset></p><p><fieldset><b>BLOOD GROUP:</b>' + ' ' + bloodgroup + '</fieldset></p><p><fieldset><b>RELIGION:</b>' + ' ' + studentreligion + '</fieldset></p><p><fieldset><b>EMMERGENCY NO:</b>' + ' ' + emmergencyno + '</fieldset></p>';
			
			
			var entry = document.getElementById('studentimage');
			entry.src = 'images/' + picture; 
			
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
           <b>Enter/Select Student Name:</b>
              </label>
<input id="selectstudent">
                             </TD><TD width="10%" id="test2" align="left"> <button dojoType="dijit.form.Button" name="studentinfo" id="studentinfo">View Student Info <script type="dojo/method" event="onClick">
                     
		 dojo.xhrGet({
         url: 'viewstudentinfo2.php',
		 handleAs: 'json',
         load: displaydata,
         error: helloError,
         content: {name: dijit.byId('selectstudent').attr("value")}
      });
		 
			
			
                </script></button></TD>
                
                <TD width="10%" align="left"> <button dojoType="dijit.form.Button" name="studentresult" id="studentresult">View Student Result <script type="dojo/method" event="onClick">
      </script></button></TD><!--<TD width="10%" align="left"> <button dojoType="dijit.form.Button" name="teachers" id="teachers">Teachers <script type="dojo/method" event="onClick"></script></button></TD><TD width="10%" align="left"> <button dojoType="dijit.form.Button" name="teach" id="teach">Teach <script type="dojo/method" event="onClick"></script></button>
                             </TD>-->
                             
                             </TR>
                   <TR>
					  <TD width="40%" valign="top"  align="left"><div id="Names" style="display:block"><fieldset><b>STUDENT INFO</b><div id="studentname"> </div></fieldset><div id="testing"></div></div></TD><TD align="right" valign="top"> <table border="0" cellpadding="0" cellspacing="0" width="221">
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
						   <td><img id="studentimage" src="images/empty_r2_c2.jpg" width="178" height="175"></td>
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

