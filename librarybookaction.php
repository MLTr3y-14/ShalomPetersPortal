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
	$Page = "Library";
	$audit=update_Monitory('Login','Administrator',$Page);
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$PageHasError = 0;
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['Optsearchby']))
	{
		$Optsearchby = $_POST['Optsearchby'];
		
	}
?>
<?PHP 
$stmt = $pdo->prepare("SELECT * FROM tbstudentmaster");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varstudent = json_encode($json_row);
$vardata = array(array('state'=>'NY','name'=>'Albany',name=>'kolade'),array(state=>'NM',name=>'Albuquerque',test=>'kolade'),array('state'=>'VA','name'=>'Alexandria','test'=>'kolade'));
$json_data = array ('items'=>$vardata);
$varcity =json_encode($json_data);

$stmt = $pdo->prepare("SELECT * FROM tbemployeemasters");
// do something
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row2 = array ('items'=>$row2);             
$varteacher = json_encode($json_row2);
//echo $varteacher;


// do something else
 $SubmitSearch = 'false';
	if(isset($_POST['SubmitSearch']))
		{   
		   $SubmitSearch = 'true';      
		}
	if(isset($_GET['Optsearchby']))
	{
		$Optsearchby = $_GET['Optsearchby'];
		 $SubmitSearch = 'true';
		 
			$Search_Key = $_GET['Search_Key'];
	

	}
	else {if(isset($_POST['Optsearchby']))
	{	
		//$Optsearch = $_POST['Optsearch'];
		$Search_Key = $_POST['Search_Key'];
	     $Optsearchby = $_POST['Optsearchby'];
	        }
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
  dojo.addOnLoad(function() {
		document.getElementById('divLoading').style.display = 'none';
		//document.getElementById('divLoading').style.display = 'block';
						 });
     // Load Dojo's code relating to the Button widget
     dojo.require("dijit.form.Button");
	 
	 function displaymessage()
{
	document.getElementById('divLoading').style.display = 'block';
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
    });
    function showDialogTwo() {
		
		
		
        // set the content of the dialog:
       // secondDlg.attr("content", "Hey, I wasn't there before, I was added at " + new Date() + "!");
        //secondDlg.show();
		formDlg.show();
		//var counter = '1';
		 
    }
	function showDialog(book){
		document.getElementById('divLoading').style.display = 'block';
		//var bookname = dojo.byId(book1).value;
		var bookid = book;
		dojo.xhrGet({
         url: 'getbookname.php',
		  handleAs: 'json',
         load: getbookname,
         error: helloError,
        content: {name:bookid }
      });
	}
    // function helloCallback(data,ioArgs) {
     //   document.getElementById('category').innerHTML= data;
    // }
    // function helloError(data, ioArgs) {
      //  alert('Error when retrieving data from the server!');
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
    dojo.addOnLoad(function() {
			//var txtobj = dijit.byId("student"); 
			//var bookname3 = JSON.parse;
			//var name3 = bookname3.items[1].ID;
    //txtobj.attr("value",name3);
		dojo.style("invisible", "opacity", "0");
		
		dojo.fadeOut({
                node: dojo.byId("category2"),
               duration: 600
           }).play();
 dojo.place("category1", "placement", "first");
 dojo.place("category2", "placement", "second");
 dojo.style("category1","display","block");
 dojo.style("category2","display","none");
  dojo.fadeIn({
                node: dojo.byId("category1"),
               duration: 2000
           }).play();
  
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
                dijit.byId('state').attr('value', (dijit.byId('city').item || {
                    state: ''
                }).state);
            }
        },
        "selectstudent");
		var teacherJson = <?php echo $varteacher; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: teacherJson
            }),
            //autoComplete: true,
			searchAttr: "EmpName",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectteacher",
           // onChange: function(EmpName) {
              //  dijit.byId('teacher').attr('value', (dijit.byId('teacher').item || {
                //    EmpName: ''
              //  }).teacher);
           // }
        },
        "selectteacher");

        new dijit.form.FilteringSelect({
            store: new dojo.data.ItemFileReadStore({
                url: '/moin_static185/js/dojo/trunk/dojo/..//dijit/tests/_data/states.json'
            }),
            autoComplete: true,
            style: "width: 150px;",
            id: "state",
            onChange: function(state) {
                dijit.byId('city').query.state = state || "*";
            }
        },
        "state");
		//var node = dojo.byId("category1");
       // dojo.connect(dijit.byId("buttonOne"), "onClick", function() {
           // dojo.fadeOut({
               // node: node,
               // duration: 300
           // }).play();
        //});
           requestoption = true;
	});
	function helloCallback(data,ioArgs) {
		document.getElementById('divLoading').style.display = 'none';
		//var data= "hello";
        alert(data);
     }
	 function helloCallback2(data,ioArgs) {
		//var data= "hello";
        //alert(data);
		location.reload(true);
     }
     function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
     }
	function getbookname(data,ioArgs){
		//var JSONtext = data;
		//var JSONobject = JSON.parse(JSONtext);
		//var bookname = JSON.parse(data);
		//JSONstring.hobby[1].isHobby;
		var book = data.name;
		var book1 = data.title;
		var book3 = data.issuedate;
		
		var txtobj = dijit.byId("studentname"); 
		// var book1 = 'kolade';
		txtobj.attr("value",book);
		//alert(book);
		var txtobj2 = dijit.byId("name"); 
		txtobj2.attr("value",book1);
		var txtobj3 = dijit.byId("sdate"); 
		txtobj3.attr("value",book3);
		document.getElementById('divLoading').style.display = 'none';
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
			  <TD width="218" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="860" align="center" valign="top">
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
		if ($SubPage == "Issue A Book") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarybookaction.php?subpg=Issue A Book">
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
                    <TABLE>
                      <TBODY>
                      <TR>
                      <TD width="100%"> </TD>
                       </TR>
                    <TR>
					  <TD colspan="6" valign="top"  align="left">					  
						<p style="margin-left:50px;">Search By:
                        <SELECT name="Optsearchby" onChange="javascript:setTimeout('__doPostBack(\'Optsearchby\',\'\')', 0)">
								<OPTION value="">Choose Search Option</OPTION>

									
<?PHP
								if($Optsearchby == "Book Title"){
?>
												<option value="Book Title" selected="selected">Book Title</option>
                                                <OPTION value="Author">Author</OPTION>
								                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                          </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Book Title :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP
											}elseif($Optsearchby == "Author"){
?>                                              
                                                <option value="Book Title" >Book Title</option>
                                                <option value="Author" selected="selected">Author</option>
                                                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Author:&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go" >
                            </label>
                                                
<?PHP
											}elseif($Optsearchby == "Year Of Publication"){
?>
                                              <option value="Book Title" >Book Title</option>
                                                <option value="Author" >Author</option>
                                                <OPTION value="Year Of Publication" selected="selected">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Year Of Publication :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP
											}elseif($Optsearchby == "Subject"){
?>
                                               <option value="Book Title" >Book Title</option>
                                                <option value="Author" >Author</option>
                                                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject" selected="selected">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Subject :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP
											}elseif($Optsearchby == "Accession No"){
?>
                                               <option value="Book Title" >Book Title</option>
                                                <option value="Author" >Author</option>
                                                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No" selected="selected">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Accession No :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>"  >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP

        }else{          $Search_Key = 'Search Key'; 


?>
                             
                                               
								<OPTION value="Book Title">Book Title</OPTION>
								<OPTION value="Author">Author</OPTION>
								<OPTION value="Year Of Publication">Year Of Publication</OPTION>
								<OPTION value="Subject">Subject</OPTION>
								<OPTION value="Accession No">Accession No</OPTION>
                                </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter<?PHP echo" " .$SearchKey;?> :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25"  value="<?PHP echo $Search_Key; ?> " disabled = "disabled">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go" disabled = "disabled" >
                            </label>
                                
<?PHP 
         }
?>                                
                                                  
				    </p> 
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                         <tr><td colspan="6"><div align="left" style="font-weight:5; color:#F00;" >Click On An Available Book To Issue</div></td></tr>
                          <tr>  
                            <td width="46" bgcolor="#F4F4F4"><div align="center" class="style21">Status</div></td>
                            <td width="139" bgcolor="#F4F4F4"><div align="center" class="style21">Title</div></td>
                            <td width="94" bgcolor="#F4F4F4"><div align="center"><strong>Class</strong></div></td>
							<td width="123" bgcolor="#F4F4F4"><div align="center"><strong>Edition</strong></div></td>
							<td width="109" bgcolor="#F4F4F4"><div align="center"><strong>ISBN No. </strong></div></td>
							<td width="96" bgcolor="#F4F4F4"><div align="center"><strong>Accession No</strong>. </div></td>
                          </tr>
<?PHP
							$counter_book = 0;
							$query2 = "select ID from tbbookmst";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_book = $rstart;
							}else{
								$counter_book = $rstart-1;
							}
							$counter = 0;
							if($SubmitSearch == 'true'){
								//$Search_Key = $_POST['Search_Key'];
								if($Optsearchby == "Book Title"){
									$query3 = "select * from tbbookmst where INSTR(Title,'$Search_Key') order by Title LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select * from tbbookmst where INSTR(Title,'$Search_Key') order by Title";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										<?PHP if($Status=="Not Available") { ?> <td bgcolor="#F00">  <?PHP } 
										else { ?> <td bgcolor="#cccccc"> <?PHP } echo $Status; ?></div></td>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Title; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo GetClassName($ClassID); ?></div></td><?PHP } 
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Edition; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $ISBN; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td><?PHP } ?>
										
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP  echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></a></div></td><?PHP } ?>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p> <?PHP 
								}elseif ($Optsearchby == "Author"){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(AuthorList,'$Search_Key') order by BookNo LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(AuthorList,'$Search_Key') order by BookNo";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										<?PHP if($Status=="Not Available") { ?> <td bgcolor="#F00">  <?PHP } 
										else { ?> <td bgcolor="#cccccc"> <?PHP } echo $Status; ?></div></td>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Title; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo GetClassName($ClassID); ?></div></td><?PHP } 
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Edition; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $ISBN; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td><?PHP } ?>
										
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP  echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></a></div></td><?PHP } ?>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>  <?PHP
								}elseif ($Optsearchby == "Year Of Publication"																																																																																															){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(YearofPub,'$Search_Key') order by BookNo LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(YearofPub,'$Search_Key') order by BookNo";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										<?PHP if($Status=="Not Available") { ?> <td bgcolor="#F00">  <?PHP } 
										else { ?> <td bgcolor="#cccccc"> <?PHP } echo $Status; ?></div></td>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Title; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo GetClassName($ClassID); ?></div></td><?PHP } 
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Edition; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $ISBN; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td><?PHP } ?>
										
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP  echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></a></div></td><?PHP } ?>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p> <?PHP 
								}elseif ($Optsearchby == "Subject"){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') order by BookNo LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') order by BookNo";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										<?PHP if($Status=="Not Available") { ?> <td bgcolor="#F00">  <?PHP } 
										else { ?> <td bgcolor="#cccccc"> <?PHP } echo $Status; ?></div></td>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Title; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo GetClassName($ClassID); ?></div></td><?PHP } 
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Edition; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $ISBN; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td><?PHP } ?>
										
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP  echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></a></div></td><?PHP } ?>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p> <?PHP
								}elseif ($Optsearchby == "Accession No"){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') order by BookNo LIMIT $rstart,$rend";
									
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') order by BookNo";
									
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										<?PHP if($Status=="Not Available") { ?> <td bgcolor="#F00">  <?PHP } 
										else { ?> <td bgcolor="#cccccc"> <?PHP } echo $Status; ?></div></td>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Title; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo GetClassName($ClassID); ?></div></td><?PHP } 
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Edition; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $ISBN; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td><?PHP } ?>
										
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP  echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></a></div></td><?PHP } ?>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p><?PHP 
									
								}
							}else{
								$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst LIMIT $rstart,$rend";
								
								
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst";
							$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										<?PHP if($Status=="Not Available") { ?> <td bgcolor="#F00">  <?PHP } 
										else { ?> <td bgcolor="#cccccc"> <?PHP } echo $Status; ?></div></td>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Title; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo GetClassName($ClassID); ?></div></td><?PHP } 
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $Edition; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td><?PHP } ?>
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP echo $ISBN; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td><?PHP } ?>
										
										<?PHP if($Status=="Not Available") {   ?><td bgcolor="#cccccc"><div align="center"> <?PHP  echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></div></td><?PHP }
										else { ?><td bgcolor="#cccccc"><div align="center"><a href="librarybookaction.php?subpg=Issue&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?></a></div></td><?PHP } ?>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Issue A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                        <?PHP 
						        }
						 ?>
					  </TD>
					</TR>
				</TBODY>
				</TABLE></form>
<?PHP
		}elseif ($SubPage == "Issue") {
			if(isset($_GET['book_id']))
			{  
			   $id = $_GET['book_id'];
				$query3 = "select Title,BookNo from tbbookmst where ID = $id";
				$result = mysql_query($query3,$conn);
		     $dbarray = mysql_fetch_array($result);
		         $Title  = $dbarray['Title'];
		    $BookNo  = $dbarray['BookNo'];
			}
?>
         <TABLE width="99%" style="WIDTH: 98%">
					<TBODY><TR><TD width="47%" valign="top"><TABLE>
					<TR>
					  <TD width="14%" valign="top"  align="left">Issue Date</TD>
                      <?PHP
						if($reg_Dy==""){$reg_Dy = date('d');}
						if($reg_Mth==""){$reg_Mth = date('m');}
						if($reg_Yr==""){$reg_Yr = date('Y');}
?>
					  <form name="IssueForm" method="post"><TD width="45%" valign="top"  align="left">

					  <input name="reg_Dy" type="text" size="2" id="reg_Dy"  value="<?PHP echo $reg_Dy; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="reg_Mth" type="text" id="reg_Mth"  size="2"  value="<?PHP echo $reg_Mth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="reg_Yr" type="text" size="2" id="reg_Yr"  value="<?PHP echo $reg_Yr; ?>" ONFOCUS="clearDefault(this)">* <span class="style22">Format= DD / MM / YYYY</span>					  </TD>
					  
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Return Date</TD>
					  <TD width="32%" valign="top"  align="left"><input name="return_Dy" type="text" id="return_Dy" size="2"  value="<?PHP echo $reg_Dy; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="return_Mth" type="text" id="return_Mth" size="2"  value="<?PHP echo $reg_Mth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="return_Yr" type="text" id="return_Yr" size="2"  value="<?PHP echo $reg_Yr; ?>" ONFOCUS="clearDefault(this)">* <span class="style22">Format= DD / MM / YYYY</span>
					  </TD>
					  
					</TR>
                    <TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Book Information</strong></div></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left">Book Title:</TD><TD width="32%" valign="top"  align="left"><input type="text" name="BookTitle" id="BookTitle" size="60" value="<?PHP echo $Title; ?>" disabled="disabled"/>
					  </TD>
                      </TR>
                      <TR>
					  <TD width="14%" valign="top"  align="left">Accession No:</TD><TD width="32%" valign="top"  align="left"><input type="text" name="regisNo" value="<?PHP echo $BookNo; ?>" disabled="disabled"/>
					  </TD>
                      </TR></TABLE></TD>
                           <TD width="53%">
                                    <TABLE width="441" height="194" id="tablesideborder"><TR><TD width="128" >
                                    <div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 12pt; color:<?PHP echo $bg; ?>;  FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>&nbsp;&nbsp;&nbsp;Issue Book To: </strong></div>
						  </TD><TD width="99" colspan="1" align="left" valign="top"><div class="tundra"> <button dojoType="dijit.form.Button" name="student" id="student">Student <script type="dojo/method" event="onClick">
 // Don't forget to replace the value for 'url' with
 // the value of appropriate file for your server
// (i.e. 'HelloWorldResponsePOST.asp') for an ASP server
 // dojo.xhrGet({
   //   url: 'getstudent.php',
     // load: helloCallback,
      //error: helloError,
 //});
 // var node = dojo.byId("category1");
 requestoption = true;
 dojo.style("invisible", "opacity", "0");
 dojo.fadeOut({
                node: dojo.byId("category2"),
               duration: 1000
           }).play();
       dojo.style("category1", "display", "block");
		dojo.style("category2", "display", "none");
  dojo.fadeIn({
                node: dojo.byId("category1"),
               duration: 500
           }).play();
  
  
</script></button>
                          </div></TD>
                          <TD width="197" align="left" valign="top"><div class="tundra"> <button dojoType="dijit.form.Button" name="teacher" id="teacher">Teacher <script type="dojo/method" event="onClick">
      // var node = dojo.byId("category1");
	  // if(node){
           // node.innerHTML = "I was found!";
            //     }else{
       //console.log("no node with id='fooBar' found!");
        //                     }
		requestoption = false;
		dojo.style("invisible", "opacity", "0");					 
		dojo.fadeOut({
                node: dojo.byId("category1"),
               duration: 300
           }).play();
		dojo.style("category1", "display", "none");
		dojo.style("category2", "display", "block");
		//dojo.place("category1", "invisible", "first");
		//dojo.place("category2", "category1", "replace");
		
		dojo.fadeIn({
                node: dojo.byId("category2"),
               duration: 500
           }).play();
	   
	  // var node = dojo.byId("category2");					 
		
  
 // Don't forget to replace the value for 'url' with
 // the value of appropriate file for your server
// (i.e. 'HelloWorldResponsePOST.asp') for an ASP server
 // dojo.xhrGet({
  //    url: 'getteacher.php',
   //   load: helloCallback,
    //  error: helloError,
// });
</script></button>

                          </div>
                       </TD>
              <TR><TD colspan="3"> 
              <div id= "placement">        
             <div id="category1">
              
 
 
  
<P><label for="student">
    Enter Student Name:
</label>
<input id="selectstudent">
   </form>


</P>
</div></div>
<div id="Issue" class="tundra">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button dojoType="dijit.form.Button" id="IssueBook">Issue Book <script type="dojo/method" event="onClick">
//var widget = dijit.byId("teacher");
//alert(widget.attr("value"));
// Don't forget to replace the value for 'url' with
 // the value of appropriate file for your server
// (i.e. 'HelloWorldResponsePOST.asp') for an ASP server
if (!requestoption) {
	document.getElementById('divLoading').style.display = 'block';
   dojo.xhrGet({
         url: 'HelloWorldResponseGET.php',
         load: helloCallback,
         error: helloError,
         content: {name: dijit.byId('selectteacher').attr("value"), name2: dojo.byId('BookTitle').value, name3: dojo.byId('reg_Dy').value, name4: dojo.byId('reg_Mth').value, name5: dojo.byId('reg_Yr').value, name6: dojo.byId('return_Dy').value, name7: dojo.byId('return_Mth').value, name8: dojo.byId('return_Yr').value 
		 }
      });
  }else{
	  document.getElementById('divLoading').style.display = 'block';
	  dojo.xhrGet({
         url: 'HelloWorldResponseGET.php',
         load: helloCallback,
         error: helloError,
         content: {name: dijit.byId('selectstudent').attr("value"), name2: dojo.byId('BookTitle').value, name3: dojo.byId('reg_Dy').value, name4: dojo.byId('reg_Mth').value, name5: dojo.byId('reg_Yr').value, name6: dojo.byId('return_Dy').value, name7: dojo.byId('return_Mth').value, name8: dojo.byId('return_Yr').value 
		 }
      });
  }


</script></button></div>
<div id = "invisible">
<div id="category2"><P><label for="teacher">
   Enter Teacher Name:
</label>
<input id="selectteacher">


</P>
</div></div>
 

                          
                         
                          </TD></TR>
                          
                          		  
						
                      
                      <TR><TD height="40" colspan="2"></TD></TR>
                      <TR><TD height="40" colspan="2"></TD></TR>
                      <TR><TD height="40" colspan="2"></TD></TR>
                      </TABLE></TD></TR>
                    </TBODY></TABLE>


<?PHP
		}elseif ($SubPage == "Receive A Book") {
?>
              <?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarybookaction.php?subpg=Receive A Book">
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
                    <TABLE>
                      <TBODY>
                      <TR>
                      <TD width="100%"> </TD>
                       </TR>
                    <TR>
					  <TD colspan="6" valign="top"  align="left">					  
						<p style="margin-left:50px;">Search By:
                        <SELECT name="Optsearchby" onChange="javascript:setTimeout('__doPostBack(\'Optsearchby\',\'\')', 0)">
								<OPTION value="">Choose Search Option</OPTION>

									
<?PHP
								if($Optsearchby == "Book Title"){
?>
												<option value="Book Title" selected="selected">Book Title</option>
                                                <OPTION value="Author">Author</OPTION>
								                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                          </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Book Title :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP
											}elseif($Optsearchby == "Author"){
?>                                              
                                                <option value="Book Title" >Book Title</option>
                                                <option value="Author" selected="selected">Author</option>
                                                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Author:&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go" >
                            </label>
                                                
<?PHP
											}elseif($Optsearchby == "Year Of Publication"){
?>
                                              <option value="Book Title" >Book Title</option>
                                                <option value="Author" >Author</option>
                                                <OPTION value="Year Of Publication" selected="selected">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Year Of Publication :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP
											}elseif($Optsearchby == "Subject"){
?>
                                               <option value="Book Title" >Book Title</option>
                                                <option value="Author" >Author</option>
                                                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject" selected="selected">Subject</OPTION>
								                   <OPTION value="Accession No">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Subject :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>" >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP
											}elseif($Optsearchby == "Accession No"){
?>
                                               <option value="Book Title" >Book Title</option>
                                                <option value="Author" >Author</option>
                                                <OPTION value="Year Of Publication">Year Of Publication</OPTION>
								                   <OPTION value="Subject">Subject</OPTION>
								                   <OPTION value="Accession No" selected="selected">Accession No</OPTION>
                                                   </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter Accession No :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>"  >
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go"  >
                            </label>
<?PHP }else{          $SearchKey = 'Search Key'; 
?>
                             
                                               
								<OPTION value="Book Title">Book Title</OPTION>
								<OPTION value="Author">Author</OPTION>
								<OPTION value="Year Of Publication">Year Of Publication</OPTION>
								<OPTION value="Subject">Subject</OPTION>
								<OPTION value="Accession No">Accession No</OPTION>
                                </SELECT>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  		  
						  Enter<?PHP echo" " .$SearchKey;?> :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25"  value="<?PHP echo $SearchKey; ?> " disabled = "disabled">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go" disabled = "disabled" >
                            </label>
                                
<?PHP 
         }
?>                                
                                                  
				    </p> 
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                         <tr><td colspan="6"><div align="left" style="font-weight:5; color:#F00;" >Click On The Book To Receive</div></td></tr>
                          <tr>  
                            
                            <td width="139" bgcolor="#F4F4F4"><div align="center" class="style21">Title</div></td>
                            <td width="94" bgcolor="#F4F4F4"><div align="center"><strong>Class</strong></div></td>
							<td width="123" bgcolor="#F4F4F4"><div align="center"><strong>Edition</strong></div></td>
							<td width="109" bgcolor="#F4F4F4"><div align="center"><strong>ISBN No. </strong></div></td>
							<td width="96" bgcolor="#F4F4F4"><div align="center"><strong>Accession No</strong>. </div></td>
                          </tr>
<?PHP
							$counter_book = 0;
							$status = '1';
							$query2 = "select ID from tbbookmst where Status = '$status'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_book = $rstart;
							}else{
								$counter_book = $rstart-1;
							}
							$counter = 0;
							if($SubmitSearch == 'true'){
								//$Search_Key = $_POST['Search_Key'];
								if($Optsearchby == "Book Title"){
									$query3 = "select * from tbbookmst where INSTR(Title,'$Search_Key') and Status = '$status' order by Title LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select * from tbbookmst where INSTR(Title,'$Search_Key') and Status = '$status' order by Title";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" onclick= "showDialogTwo();showDialog(<?PHP echo $bookID ?>);">
  <?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a> <input type="hidden" name="book" id="bookid" value="<?PHP echo $bookID; ?>" /></div></td>
										 <td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" "><?PHP echo $ISBN; ?></a></div></td>
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?> </a></div></td>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
                        <input dojoType="dijit.form.TextBox" type="text" name="student" id="student" 
>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
                        <?PHP 
								}elseif ($Optsearchby == "Author"){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(AuthorList,'$Search_Key') and Status = '$status' order by BookNo LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(AuthorList,'$Search_Key') and Status = '$status' order by BookNo";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" onclick= "showDialogTwo();showDialog(<?PHP echo $bookID ?>);">
  <?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a> <input type="hidden" name="book" id="bookid" value="<?PHP echo $bookID; ?>" /></div></td>
										 <td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" "><?PHP echo $ISBN; ?></a></div></td>
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?> </a></div></td>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
                        <input dojoType="dijit.form.TextBox" type="text" name="student" id="student" 
>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
                        <?PHP 
								}elseif ($Optsearchby == "Year Of Publication"																																																																																															){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(YearofPub,'$Search_Key') and Status = '$status' order by BookNo LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(YearofPub,'$Search_Key') and Status = '$status' order by BookNo";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" onclick= "showDialogTwo();showDialog(<?PHP echo $bookID ?>);">
  <?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a> <input type="hidden" name="book" id="bookid" value="<?PHP echo $bookID; ?>" /></div></td>
										 <td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" "><?PHP echo $ISBN; ?></a></div></td>
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?> </a></div></td>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
                        <input dojoType="dijit.form.TextBox" type="text" name="student" id="student" 
>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
                        <?PHP 
								}elseif ($Optsearchby == "Subject"){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') and Status = '$status' order by BookNo LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') and Status = '$status' order by BookNo";
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" onclick= "showDialogTwo();showDialog(<?PHP echo $bookID ?>);">
  <?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a> <input type="hidden" name="book" id="bookid" value="<?PHP echo $bookID; ?>" /></div></td>
										 <td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" "><?PHP echo $ISBN; ?></a></div></td>
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?> </a></div></td>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
                        <input dojoType="dijit.form.TextBox" type="text" name="student" id="student" 
>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
                         <?PHP
								}elseif ($Optsearchby == "Accession No"){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') and Status = '$status' order by BookNo LIMIT $rstart,$rend";
									
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') and Status = '$status' order by BookNo";
									
									$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" onclick= "showDialogTwo();showDialog(<?PHP echo $bookID ?>);">
  <?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a> <input type="hidden" name="book" id="bookid" value="<?PHP echo $bookID; ?>" /></div></td>
										 <td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" "><?PHP echo $ISBN; ?></a></div></td>
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?> </a></div></td>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
                        <input dojoType="dijit.form.TextBox" type="text" name="student" id="student" 
>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=0&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearchby=<?PHP echo $Optsearchby; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p> 
							<?PHP 		
								}
							}else{
								$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where Status = '$status' LIMIT $rstart,$rend";
								
								
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$ClassID = $row["ClassID"];
									$Edition = $row["Edition"];
									$Status = $row["Status"];
									$ISBN = $row["ISBN"];
									$Price = $row["Price"];
									$BookNo = $row["BookNo"];
									if($Status==0){
										$Status = "Available";
									}elseif($Status==1){
										$Status = "Not Available";
									}
									
?>
									<tr><div align="center">
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" onclick= "showDialogTwo();showDialog(<?PHP echo $bookID ?>);">
  <?PHP echo $Title; 
										
										
										//$_SESSION['Title']= $Title;}  ?></a> <input type="hidden" name="book" id="bookid" value="<?PHP echo $bookID; ?>" /></div></td>
										 <td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="#" "><?PHP echo $ISBN; ?></a></div></td>
										
										<td bgcolor="#cccccc"><div align="center"><a href="#" ><?PHP echo $BookNo; $_SESSION['BookNo'] = $BookNo; ?> </a></div></td>
								  </tr>
<?PHP
									
								 }
							 }
							else {
								 ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
                        </table>
                        <input dojoType="dijit.form.TextBox" type="text" name="student" id="student" 
>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarybookaction.php?subpg=Receive A Book&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p> 
                        <?PHP 
						      }
						?>
					  </TD>
					</TR>
				</TBODY>
				</TABLE></form>
                
                <div dojoType="dijit.Dialog" id="formDialog" title="RECEIVE A BOOK" style="display: none" execute="displaymessage();">
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
        <tr>
        
            <td>
                <label for="name">
                    Book Name:
                </label>
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="name" id="name" value="">
            </td>
        </tr>
       <tr>
            <td>
                <label for="StudentName">
                    Previously Issued To:
                </label>
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="studentname" id="studentname">
            </td>
        </tr>
        <tr>
            <td>
                <label for="date">
                    Issued date:
                </label>
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="sdate" id="sdate">
            </td>
        </tr>
        <tr>
            <td>
                <label for="date">
                    Returned date:
                </label>
            </td>
            <td>
                <input dojoType="dijit.form.DateTextBox" type="text" name="edate" id="edate">
            </td>
        </tr>
        <tr>
            <td>
                <label for="date">
                    Time:
                </label>
            </td>
            <td>
                <input dojoType="dijit.form.TimeTextBox" type="text" name="time" id="time">
            </td>
        </tr>
        <tr>
            <td>
                <label for="desc">
                    Description:
                </label>
            </td>
            <td>
                <input dojoType="dijit.form.TextBox" type="text" name="desc" id="desc">
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <button dojoType="dijit.form.Button" type="submit" onClick="return dijit.byId('formDialog').isValid();pagereload();">
                    Receive
                </button>
                <button dojoType="dijit.form.Button" type="button" onClick="dijit.byId('formDialog').hide();">
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
