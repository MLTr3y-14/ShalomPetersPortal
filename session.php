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
	global $userNames,$Teacher_EmpID,$Activeterm;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	//GET ACTIVE TERM
	//$query2 = "select Section from section where Active = '1'";
	//$result2 = mysql_query($query2,$conn);
	//$dbarray2 = mysql_fetch_array($result2);
	//$Activeterm  = $dbarray2['Section'];
	
	//GET SESSION 
	//$query = "select * from session where Status='1'";
	//$result = mysql_query($query,$conn);
	//$dbarray = mysql_fetch_array($result);
	//$OptSession  = $dbarray['ID'];
	//$SessName  = $dbarray['SessionName'];
	//$SessFrDate  = $dbarray['FromDate'];
	//$SessToDate  = $dbarray['ToDate'];
	
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Session";
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
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 3;
	}
	if(isset($_POST['SessionMaster']))
	{
		$PageHasError = 0;
		$NewSessionName = $_POST['NewSessionName'];
		$nFrDate = $_POST['nFrDate'];
		$nToDate = $_POST['nToDate'];
		$Result = Date_Comparison($nFrDate,$nToDate);
		$PageHasError = 1;
		if($Result == "false"){
			$errormsg = "<font color = red size = 1>Invalid Date Entry</font>";
			$PageHasError = 1;
		}
		if(!$_POST['NewSessionName']){
			$errormsg = "<font color = red size = 1>Session Name is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['nFrDate']){
			$errormsg = "<font color = red size = 1>From Date is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['nToDate']){
			$errormsg = "<font color = red size = 1>To Date is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['SessionMaster'] =="Create Session"){
				$num_rows = 0;
				$query = "select ID from session where SessionName = '$NewSessionName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Session you are trying to create already exist.</font>";
					$PageHasError = 1;
				}else {
					$nFrDate = DB_date($nFrDate);
					$nToDate = DB_date($nToDate);
					$q = "Insert into session(SessionName,FromDate,ToDate,Status) Values ('$NewSessionName','$nFrDate','$nToDate','1')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Created Successfully.</font>";
					
					
					$query = "select ID from session where SessionName='$NewSessionName' And FromDate = '$nFrDate' And ToDate='$nToDate'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$SessionID  = strtoupper($dbarray['ID']);
					
					$q = "update session set Status = '0' where ID != '$SessionID'";
					$result = mysql_query($q,$conn);
					
					$NewSessionName = "";
					$nFrDate = "";
					$nToDate = "";
				}
			}elseif ($_POST['SessionMaster'] =="Update"){
				//NewsessID
				$NewsessID = $_POST['NewsessID'];
				$nFrDate = DB_date($nFrDate);
				$nToDate = DB_date($nToDate);
				$q = "update session set SessionName = '$NewSessionName',FromDate = '$nFrDate',ToDate = '$nToDate' where ID = '$NewsessID'";

				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				$NewSessionName = "";
				$nFrDate = "";
				$nToDate = "";
			}
		}
	}
	if(isset($_POST['SessionMaster_edit']))
	{
		$query = "select * from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$NewSessionID  = $dbarray['ID'];
		$NewSessionName  = strtoupper($dbarray['SessionName']);
		$nFrDate  = User_date($dbarray['FromDate']);
		$nToDate  = User_date($dbarray['ToDate']);
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['SessionMaster_delete']))
	{
		$q = "Delete From session where Status = '1'";
		$result = mysql_query($q,$conn);
		
		$query = "select ID from session where Status='0' order by ID desc";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SessionID  = strtoupper($dbarray['ID']);
		
		$q = "update session set Status = '1' where ID = '$SessionID'";
		$result = mysql_query($q,$conn);
	}
	if(isset($_POST['OptSession']))
	{
		$OptSession = $_POST['OptSession'];
		$query = "select * from session where ID='$OptSession'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SessName  = $dbarray['SessionName'];
		$SessFrDate  = $dbarray['FromDate'];
		$SessToDate  = $dbarray['ToDate'];
	}
	if(isset($_POST['ChgSession']))
	{
		$PageHasError = 0;
		$OptSession = $_POST['OptSession'];
		//$SessFrDate = DB_date($_POST['SessFrDate']);
		//$SessToDate = DB_date($_POST['SessToDate']);
		
		if(!$_POST['OptSession']){
			$errormsg = "<font color = red size = 1>Select Session.</font>";
			$PageHasError = 1;
		}
		
		
		if ($PageHasError == 0)
		{
			$q = "update session set Status = '1' where ID = '$OptSession'";
			$result = mysql_query($q,$conn);
			
			$q = "update session set Status = '0' where ID != '$OptSession'";
			$result = mysql_query($q,$conn);
			
			$errormsg = "<font color = blue size = 1>Session Change Successfully.</font>";
		}
	}
if(isset($_POST['Session_delete']))
	{
		
		$Total = $_POST['TotalSession'];
		//echo $Total;
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chksessionID'.$i]))
			{
				$sessIDs = $_POST['chksessionID'.$i];
				$q = "Delete From session where ID = '$sessIDs'";
				$result = mysql_query($q,$conn);
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
.style22 {
	color: #FFFFFF;
	font-weight: bold;
}
.style23 {color: #990000}
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

</style>
<SCRIPT 
src="js/json/json2.js" 
type=text/JavaScript></SCRIPT>
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
  dojo.addOnLoad(function() {
		document.getElementById('divLoading').style.display = 'none';
						  });
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
			  <TD width="217" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			       <TD width="861" align="center" valign="top">
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
		if ($SubPage =="Session List" ) {
?>
                      <?PHP echo $errormsg; ?>
                      <form name="form1" method="post" action="session.php?subpg=Session List">
			<TABLE style="WIDTH:100%">
					<TBODY>
					<TR>
					  <TD width="35%" valign="top" align="center"><B>Select Session Tick Box To Delete </B> </TD>
					  <TD width="65%" valign="top"  align="left"><div style="color:#09F">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Click On A Session To Edit</div></TD>
					</TR>
					
<?PHP                                   $counter = 0;
										$query = "select * from session order by SessionName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "No Session Found.";
									?> <TR>
					  <TD width="35%" valign="bottom"  align="left"><?PHP echo $SessionName; ?>  </TD><TD width="65%" valign="bottom"  align="left"></TD>
					</TR>  
								<?PHP }
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$counter = $counter+1;
												$SessID = $row["ID"];
												$SessionName = $row["SessionName"];
												
?>
                                      				

             <TR><td width="35%" height="42"  align="center" valign="bottom">
								<div>
											     <input name="chksessionID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SessID; ?>">
									           </div></td>
					  <TD width="65%" valign="bottom"  align="left"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="editResult(<?PHP echo $SubjID.','.$ClassId.','.$ExamId.','.$StuID; ?>);"><?PHP echo $SessionName; ?>  </a></TD>
					</TR>                                    				
<?PHP
												}
											}
								
?>
                   <TR>
						 <TD height="66" colspan="2" valign="bottom">
						 <div align="center" > <input type="hidden" name="TotalSession" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelDocID" value="<?PHP echo $SessID; ?>">
                             <input name="Session_delete" type="submit" id="userprofile_delete" value="Delete Selected" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
                </div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>		
<?PHP
		}elseif ($SubPage == "Create New Session") {
?>
	<?PHP echo $errormsg; ?>
				<form name="createsession" id="createsession" method="post">
                <script type="text/javascript">
					<!--
					
					function __doPostBack(eventTarget, eventArgument) {
						if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
							theForm.__EVENTTARGET.value = eventTarget;
							theForm.__EVENTARGUMENT.value = eventArgument;
							theForm.submit();
						}
					}
					// -->
					function updatesuccessful(data,ioArgs) {
						 var SessionAvailable = data['SessionAvailable'];
						
	               document.getElementById('divLoading').style.display = 'none';
	          //alert(chargelength + studentcharge + studentpayment + studentname );
			     if(SessionAvailable == 'true'){
	               alert('Session Already Available Please Create Another Session');
				              }else{
								var datentry = dijit.byId("fromdate");
								   datentry.value = '';
								var datentry2 = dijit.byId("todate");
								   datentry2.value = '';
								 var sessionentry = document.getElementById("NewSessionName");
								 sessionentry.value = '';
							alert('Session Created Successfully');	  
							  }
	 
                                     }
					function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }				 
					</script>
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="left"><p style="margin-left:150px;">&nbsp;</p>  </TD>
					</TR>
					<TR>
					   <TD align="left">
					    <TABLE width="72%" align="center" cellpadding="7">
                          <TBODY>
                            <TR>
                              <TD width="36%"  align="left"><strong>Create New Session </strong></TD>
                              <TD width="64%"  align="left" valign="top">
<?PHP
								
?>
							  &nbsp;
							  
							  </TD>
                            </TR>
                            
                            
                            <TR>
                              <TD width="100%"  align="left" colspan="2"><div align="left">New Session Name <input name="NewSessionName" id="NewSessionName" type="text" size="50" value="<?PHP echo $NewSessionName; ?>"> </div></TD>
                              
                            </TR>
							<TR>
                              <TD width="50%" align="left" valign="top">From<input dojoType="dijit.form.DateTextBox" name="fromdate" id="fromdate" size="30"></TD>
                          <TD width="50%" align="left" >
                              To
                               <input dojoType="dijit.form.DateTextBox" name="todate" id="todate" size="40"></TD>
							</TR>
                          </TBODY>
                        </TABLE>
					    <p style="margin-left:150px;">&nbsp;</p>					    </TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center">
<?PHP
							
?>
							 	
<?PHP
							
?>
								
							   <button dojoType="dijit.form.Button" name="updatereceipt" id="updatereceipt"> Create Session <script type="dojo/method" event="onClick">
								
			var EntryDate = document.getElementById('entrydate');		
				     var datentry = dijit.byId("fromdate").value;
					 EntryDate.value = datentry;
					 
			var EntryDate2 = document.getElementById('entrydate2');		
				     var datentry2 = dijit.byId("todate").value;
					 EntryDate2.value = datentry2;
					
					var SessionName = document.getElementById('sessionname');		
				     var sessionentry = document.getElementById("NewSessionName").value;
					 SessionName.value = sessionentry;
				
		 var datElementValue = document.getElementById("entrydate");
	    if ( datElementValue.value.length == 0){
		   alert('Please Select From Date');
		   datElementValue.focus();
		   return false;
	      }	
		  
		  var datElementValue2 = document.getElementById("entrydate2");
	    if ( datElementValue2.value.length == 0){
		   alert('Please Select To Date');
		   datElementValue.focus();
		   return false;
	      }	
		  
		  var sessionValue = document.getElementById("sessionname");
	    if ( sessionValue.value.length == 0){
		   alert('Please Enter Session Name');
		   datElementValue.focus();
		   return false;
	      }	
		 // var datElementValue1 = document.getElementById("entrydate").value;
		  //alert(datElementValue1 + datentry);
		  document.getElementById('divLoading').style.display = 'block';					
								   dojo.xhrPost({
      url: 'createsession.php',
	  handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'createsession'
                  });        
		
		 // alert('im good');
			
			 </script>
<?PHP
							
?>
							 
						  </div>						  </TD>
					</TR>
				</TBODY>
				</TABLE>
                <input type="hidden" name="entrydate" id="entrydate" />
                <input type="hidden" name="entrydate2" id="entrydate2" />
                <input type="hidden" name="sessionname" id="sessionname" />
				</form>
<?PHP
		}elseif ($SubPage == "Change Active Session") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" id="form1" method="post" action="">
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
				function updatesuccessful(data,ioArgs) {
					 document.getElementById('divLoading').style.display = 'none';
					alert('Session Set Successfully As Active');
					    var theForm = document.forms['form1'];
					if (!theForm) {
						theForm = document.form1;
					}
		                         theForm.submit();
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
					  <TD width="64%" valign="top"  align="left"><p style="margin-left:150px;">&nbsp;</p>					   </TD>
					</TR>
					<TR>
					   <TD align="left">
					    <TABLE width="72%" align="center" cellpadding="7">
                          <TBODY>
                            <TR>
                              <TD width="36%"  align="left"><strong>Present Active Session/Term: </strong></TD>
                              <TD width="64%"  align="left" valign="top"><B><?PHP   
							$query = "select ID,SessionName from session where Status = '1'";
											    $result = mysql_query($query,$conn);
	                                      $num_rows = mysql_num_rows($result);
	                                                if ($num_rows > 0 ) {
														$row = mysql_fetch_array($result);
												          $SessionName = $row["SessionName"];
														  $query = "select Section from section where Active = '1'";
											                  $result = mysql_query($query,$conn);
												                $row = mysql_fetch_array($result);
												                    $TermName = $row["Section"];
												          echo $SessionName.'/'.$TermName;
													}else{
														echo 'No Session Selected As Active, Please Select an Active Session or Create a New Session';}
								?></B></TD>
                            </TR>
                            <TR>
                              <TD width="36%"  align="left"><strong>Change Session </strong></TD>
                              <TD width="64%"  align="left" valign="top">&nbsp;</TD>
                            </TR>
                            <TR>
                              <TD width="36%"  align="left"><div align="right">Select Session  :</div></TD>
                              <TD width="64%"  align="left" valign="top"><label>
                                <select name="OptSession" >
                                      <option value="" >Select</option>
<?PHP
										$query = "select * from session order by SessionName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "No Session Found.";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$SessID = $row["ID"];
												$SessionName = $row["SessionName"];
												if($OptSession =="$SessID"){
?>
                                      				<option value="<?PHP echo $SessID; ?>" ><?PHP echo $SessionName; ?></option>
<?PHP
												}else{
?>
                                      				<option value="<?PHP echo $SessID; ?>"><?PHP echo $SessionName; ?></option>
<?PHP
												}
											}
										}
?>
                                    </select>
                              </label></TD>
                            </TR>
                            
                          </TBODY>
                        </TABLE>
					    <p style="margin-left:150px;">&nbsp;</p>					    </TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center">
							 <input type="hidden" name="Totalexam" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelExamID" value="<?PHP echo $ExaminationID; ?>">
							 <button dojoType="dijit.form.Button" name="updatereceipt" id="updatereceipt">Set Selected Session As Active <script type="dojo/method" event="onClick">
							 document.getElementById('divLoading').style.display = 'block';					
		dojo.xhrPost({
      url: 'setactivesession.php',
	  //handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'form1'
                  });        
		
		 // alert('im good');
			
			 </script>
						  </div>						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Change Active Term") {
				//GET ACTIVE SECTION
				$query2 = "select Section from section where Active = '1'";
				$result2 = mysql_query($query2,$conn);
				$dbarray2 = mysql_fetch_array($result2);
				$Classterm  = $dbarray2['Section'];
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" id="form1" method="post">
                <script type="text/javascript">
				<!--
				function WebForm_OnSubmit() {
				if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
				return true;
				}
				function updatesuccessful(data,ioArgs) {
					 document.getElementById('divLoading').style.display = 'none';
					alert('Term Set Successfully As Active');
					var theForm = document.forms['form1'];
					if (!theForm) {
						theForm = document.form1;
					}
		                         theForm.submit();
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
					  <TD width="64%" valign="top"  align="left">
					  		<table width="634" border="0" align="center" cellpadding="3" cellspacing="3">
							 <tr>
								<td width="161" >&nbsp;</td>
								<td width="452" >&nbsp;</td>
							 </tr>
							 <tr>
								<td width="161" >&nbsp;</td>
								<td width="452" >&nbsp;</td>
							 </tr>
							 <tr>
								<td width="161" >Active Session/Term:</td>
								<td width="452" ><B><?PHP   
							$query = "select ID,SessionName from session where Status = '1'";
											    $result = mysql_query($query,$conn);
	                                      $num_rows = mysql_num_rows($result);
	                                                if ($num_rows > 0 ) {
														$row = mysql_fetch_array($result);
												          $SessionName = $row["SessionName"];
														  $query = "select Section from section where Active = '1'";
											                  $result = mysql_query($query,$conn);
												                $row = mysql_fetch_array($result);
												                    $TermName = $row["Section"];
												          echo $SessionName.'/'.$TermName;
													}else{
														echo 'No Session Selected As Active, Please Select an Active Session or Create a New Session';}
								?></B></td>
							 </tr>
							  <tr>
								<td width="161" >Select Active Term For Present Session :</td>
								<td width="452" ><label>
								<select name="Classterm">
								    <option value="">Select Term</option>
										<option value="1st Term">1st Term</option>
										<option value="2nd Term">2nd Term</option>
										<option value="3rd Term">3rd Term</option>
								  </select>
								<button dojoType="dijit.form.Button" name="updatereceipt" id="updatereceipt">Set Selected Term As Active <script type="dojo/method" event="onClick">
							 document.getElementById('divLoading').style.display = 'block';					
		dojo.xhrPost({
      url: 'setactiveterm.php',
	  //handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'form1'
                  });        
		
		 // alert('im good');
			
			 </script>
								</button></label></td><td><!--<input name="SetStudentTerm" type="submit" id="SetStudentTerm" value="Update Student Section / Term ">
								</label>--></td>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>
							  </tr>
							</table>
						</TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center"></div>
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
