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
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$bg="maroon";
	$Page = "Examination";
	$audit=update_Monitory('Login','Administrator',$Page);
	
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
		$rend = 3;
	}
	if(isset($_POST['OptClass']))
	{
		$OptClass = $_POST['OptClass'];
		
	}
	if(isset($_POST['OptSelExams']))
	{
		$OptSelExams = $_POST['OptSelExams'];
		
	}
	if(isset($_POST['OptClassName']))
	{
		$OptClassName = $_POST['OptClassName'];
		
	}
	if(isset($_POST['OptExamName']))
	{
		$OptExamName = $_POST['OptExamName'];
		
	}
    if(isset($_POST['DeleteExamSetup_delete']))
	{
		$Total = $_POST['TotalExamSetup'];
		for($i=1;$i<=$Total;$i++){
			if(isset($_POST['chkExamsetupID'.$i]))
			{
				$chkExamsetupID = $_POST['chkExamsetupID'.$i];
				$q = "Delete From tbclassexamsetup where ID = '$chkExamsetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	 if(isset($_POST['DeleteExamSetup_delete2']))
	{
		$Total = $_POST['TotalExamSetup2'];
		for($i=1;$i<=$Total;$i++){
			if(isset($_POST['chkExamsetupID2'.$i]))
			{
				$chkExamsetupID = $_POST['chkExamsetupID2'.$i];
				$q = "Delete From tbclassexamsetup where ID = '$chkExamsetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
		$ClassId = $_POST['ClassId'];
		$SubjID = $_POST['SubjectId'];
		$ExamId = $_POST['ExamId'];
	}
if(isset($_POST['SubmitMarksetup']))
	{	
		$OptClassName = $_POST['OptClassName'];
		$OptExamName = $_POST['OptExamName'];
	}
	if(isset($_POST['SubmitSave']))
	{	
		$OptClassName = $_POST['OptClassName'];
		$OptExamName = $_POST['OptExamName'];
		$OptClassName = $_POST['ClassID'];
		$OptExamName = $_POST['ExamID'];
		$Total = $_POST['TotalSubj'];
		for($i=1;$i<=$Total;$i++){
			$SubjID = $_POST['SubjID'.$i];
			$MarkID = $_POST['MarkID'.$i];
			$MaxMk = $_POST['Max'.$i];
			$PassMk = $_POST['Pass'.$i];
			if(!is_numeric($_POST['Max'.$i])){
				$PageHasError = 1;
			}
			if(!is_numeric($_POST['Pass'.$i])){
				$PageHasError = 1;
			}
			if($MaxMk >100){
				$errormsg = "<font color = red size = 1>Max Mark Should not be greater than 100%</font>";
				$PageHasError = 1;
			}
			if($PassMk >100){
				$errormsg = "<font color = red size = 1>Pass Mark Should not be greater than 100%</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{
				$num_rows = 0;
				$query = "select ID from tbexammarkssetup where ClassID = '$OptClassName' and ExamID = '$OptExamName' and SubjectID = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$q = "update tbexammarkssetup set MaxMark='$MaxMk',PassMark='$PassMk' where ClassID = '$OptClassName' and ExamID = '$OptExamName' and SubjectID = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				}else {
					$q = "Insert into tbexammarkssetup(ClassID, ExamID, SubjectID, MaxMark, PassMark, Session_ID,Term_ID) Values ('$OptClassName', '$OptExamName', '$SubjID', '$MaxMk', '$PassMk' ,'$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				}
			}
		}
	}
if(isset($_POST['SubmitSave1'])) 
  {
	  $SubPage = "Edit Class Subject Mark Setup";
  }
if(isset($_GET['setup_id']))
	{
		$examsetupID = $_GET['setup_id'];
		$ExamId = $_GET['examid'];
		$query = "select * from tbclasssubjectrelation where ID='$examsetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$ClassId  = $dbarray['ClassId'];
		$SubjID  = $dbarray['SubjectId'];
		
	}
if(isset($_POST['Assessment']))
	{
		$PageHasError = 0;
		$ClassId = $_POST['ClassId'];
		$SubjID = $_POST['SubjectId'];
		$ExamId = $_POST['ExamId'];
		$OptAssessment = $_POST['OptAssessment'];
		$OptPercentage = $_POST['OptPercentage'];
		
		if(!$_POST['OptAssessment']){
			$errormsg = "<font color = red size = 1>Assessment Name is empty.</font>";
			$PageHasError = 1;
		}
		elseif(!$_POST['OptPercentage']){
			$errormsg = "<font color = red size = 1>Please Enter Percentage.</font>";
			$PageHasError = 1;
		}
		
		elseif ($PageHasError == 0)
		{
			if ($_POST['Assessment'] =="Create New"){
				$num_rows = 0;
				$query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId ='$ExamId' And SubjectId = '$SubjID' And ResultType = 'OptAssessment' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
				    $result = mysql_query($query,$conn);
				    $num_rows = mysql_num_rows($result);
					$dbarray = mysql_fetch_array($result);
		            $ID  = $dbarray['ID'];
				if ($num_rows > 0 )
				{  
					
					$errormsg = "<font color = red size = 1>The Assessment you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbclassexamsetup(ClassId,SubjectId,ExamId,ResultType,Percentage,Session_ID,Term_ID) Values ('$ClassId','$SubjID','$ExamId','$OptAssessment','$OptPercentage','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					   
				}
			}elseif ($_POST['Assessment'] =="Update"){
				$query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId ='$ExamId' And SubjectId = '$SubjID' And ResultType = '$OptAssessment' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									$dbarray = mysql_fetch_array($result);
		                            $AssessmentID  = $dbarray['ID'];
				$q = "update tbclassexamsetup set ResultType = '$OptAssessment',Percentage = '$OptPercentage' where ID = '$AssessmentID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				
			}
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
</script>
<SCRIPT type=text/javascript>
<!--
var theForm = document.forms['aspnetForm'];
if (!theForm) {
    theForm = document.aspnetForm;
}
function __doPostBack(eventTarget, eventArgument) {
    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
        theForm.__EVENTTARGET.value = eventTarget;
        theForm.__EVENTARGUMENT.value = eventArgument;
        theForm.submit();
    }
}
// -->
</SCRIPT>
<script type="text/javascript">
					<!--
					function WebForm_OnSubmit() {
					if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
					return true;
					}
					// -->
					</script>
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
					  <TD height="55" 
					  align="left" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $SubPage; ?></TD></TR></TBODY></TABLE>
                     
                      
                      <BR>
 <?PHP
		if ($SubPage == "Manage Weighted Result") {
?>
      <?PHP echo $errormsg; ?>                    
                      
				
                    <form name="form1" method="post" action="exam2.php?subpg=Manage Weighted Result">
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
					  <TD width="12%" valign="top"  align="right"><strong>Select Class Name </TD>
					  <TD width="21%" valign="top"  align="left">
					  <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)">
						<option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
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
				    </TBODY>
				</TABLE>
                
				<BR>
   
<table width="672" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="38" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
							<td width="103" bgcolor="#F4F4F4" colspan="1"><div align="center" class="style21">Subject</div></td>
							<td width="121" colspan="2" bgcolor="#F4F4F4"><div align="center" class="style21">Details</div></td>
                          </tr>
						  <tr>
                            <td width="38" height="20" bgcolor="#FFFFFF">&nbsp;</td>
                            <td width="103" bgcolor="#FFFFFF">&nbsp;</td>
							<td width="85" height="20" bgcolor="#ECE9D8">
								<strong>ASSESSMENT</strong></td>
							<td width="85" height="20" bgcolor="#ECE9D8"><strong>ALLOCATED MARK </strong></td>
								  </tr>
							  

                   <?PHP 
				          $counter = 0;
						  $ClassId= $OptClass;
						  $ExamId = $OptSelExams;
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
?>
						  
						  
						                         
								<tr><td height="32" ><div align="center"><?PHP echo $counter; ?></div></td>
								<td height="32" ><div align="center"><a href="exam2.php?subpg=Class Subject Assessment Setup&setup_id=<?PHP echo $SetupId; ?>&examid=<?PHP echo $OptSelExams; ?>"><?PHP echo GetSubjectName($SubjID); ?></a></div></td>
                                <td  ><table width="121">
                                 <?PHP 
								 $ClassId= $OptClass;
						         $ExamId = $OptSelExams;
						  
						  
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
											$TotalPer = $TotalPer +$Percentage;
?>
                                
								<tr><td width="38" height="32" align="left" colspan="1"><input name="chkExamsetupID<?PHP echo $counter1; ?>" type="checkbox" value="<?PHP echo $SetupId; ?>"> <?PHP echo $ResultType; ?></td>
                                </tr>
                                	
                                <?PHP
										}
									             }
								  ?>
                                  
                                  </table></td>
                                  <td  ><table width="121">
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
                                  </tr>
                                  <tr><td></td><td></td><td width="169" align="left"><input type="hidden" name="TotalExamSetup" value="<?PHP echo $counter1; ?>"><input name="DeleteExamSetup_delete" type="submit" id="DeleteExamSetup_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}"></td><td width="110"></td></tr>
                                  <tr><td></td><td></td><td width="169" align="right"><hr><strong>Total</strong><hr></td><td width="110"><hr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $TotalPer1; ?>%<hr></td></tr>
                               
								
                              <?PHP
							       }
									    }
							?>	
								  
								
                             
        </table>
                           </form>
                            </TD> </TR></TBODY></TABLE>
                           </TD></TR></TABLE>
                    </TD></TR>
                </TBODY></TABLE>
<?PHP
		}elseif ($SubPage == "Class Subject Mark Setup") {
?>
				<?PHP 
				echo $errormsg; ?>
				<form name="form1" method="post" action="exam2.php?subpg=Class Subject Mark Setup">
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
					  <TD width="38%" valign="top"  align="left"><strong>Select Class Name
					    <select name="OptClassName" onChange="javascript:setTimeout('__doPostBack(\'OptClassName\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
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
										
										if($OptClassName =="$ClassID"){
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
						      </select>
					  </strong></TD>
					  <TD width="62%" valign="top"  align="left" ><strong>Select Exam Name: </strong>
					    <select name="OptExamName" onChange="javascript:setTimeout('__doPostBack(\'OptExamName\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								$query = "select ExamID,ExamName from tbexaminationmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
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
										$ExamID = $row["ExamID"];
										$ExamName = $row["ExamName"];
										
										if($OptExamName =="$ExamID"){
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
						      </select>
					    					  </TD>
					</TR>
                    <TR>
					  <TD width="38%" valign="top" colspan="2"  align="left"><strong><input type="hidden" name="TotalSubj" value="<?PHP echo $counter; ?>">
						  <input name="SubmitSave1" type="submit" id="SubmitMark" value="&nbsp;&nbsp;Edit&nbsp;&nbsp;"></strong></TD></TR>
					<TR>
					  <TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
					  		<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
							<TBODY>
							<TR bgcolor="#00CCFF">
							  <TD width="28%"  align="left" valign="top">&nbsp;</TD>
							  <TD width="20%"  align="center" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Max Mark (%) </span></TD>
							  <TD width="21%"  align="center" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Pass Mark (%) </span></TD>
							  <TD width="31%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Exam Name </span></TD>
							</TR>
<?PHP
							$counter = 0;
					     	$query3 = "select SubjectId from tbclasssubjectrelation where ClassId = '$OptClassName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$SubjectId = $row["SubjectId"];
								   $query = "select ID,MaxMark,PassMark from tbexammarkssetup where ClassID = '$OptClassName' And ExamID = '$OptExamName' And SubjectID = '$SubjectId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$MarkID  = $dbarray['ID'];
									$MaxMark  = $dbarray['MaxMark'];
									$PassMark  = $dbarray['PassMark'];
									
?>
									<TR>
									  <TD width="28%" bgcolor="#00CCFF" align="left" valign="top"><span class="style3 style22"><?PHP echo GetSubjectName($SubjectId); ?></span></TD>
									  <TD width="20%" align="center" valign="top" bgcolor="#FFFFCC"><span class="style3">&nbsp;
									    <input type="hidden" name="SubjID<?PHP echo $counter; ?>" value="<?PHP echo $SubjectId; ?>">
										<input type="hidden" name="MarkID<?PHP echo $counter; ?>" value="<?PHP echo $MarkID; ?>">
									    <?PHP echo $MaxMark; ?>
									  </span></TD>
									  <TD width="21%" align="center" valign="top" bgcolor="#FFFFCC"><span class="style3">&nbsp;
									    <?PHP echo $PassMark; ?>
									  </span></TD>
									  <TD width="31%" align="left" valign="top" bgcolor="#FFFFCC"><span class="style3"><?PHP echo GetExamName($OptExamName); ?></span></TD>
									</TR>
<?PHP
								 }
							 }
?>
							</TBODY>
							</TABLE>
						</TD>
					</TR>
					<TR>
						<TD colspan="2">
						<div align="left"><strong>
						  <input type="hidden" name="TotalSubj" value="<?PHP echo $counter; ?>">
						  <input name="SubmitSave1" type="submit" id="SubmitMark" value="&nbsp;&nbsp;Edit&nbsp;&nbsp;"></strong>
						</div>
						<p align="center"><span class="style23">Please Note:</span> The Max Mark and the pass mark should be in percentage (%) </p></TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Edit Class Subject Mark Setup") {
?>
			<?PHP 
			
				echo $errormsg;?>
				<form name="form1" method="post" action="exam2.php?subpg=Class Subject Mark Setup">
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
					  <TD width="38%" valign="top"  align="left"><strong><?PHP $query3 = "select Class_Name from tbclassmaster where Id = '$OptClassName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					  $result = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$Classname = $row["Class_Name"];
									}
								}
					   ?> Class Name: &nbsp;&nbsp;&nbsp;<?PHP echo $Classname;?>
					    
  </strong></TD>
					  <TD width="62%" valign="top"  align="left" ><strong><?PHP 
					  $query = "select ExamName from tbexaminationmaster where ExamID = '$OptExamName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$ExamName = $row["ExamName"];
									}
								}
					  
					  ?>  Exam Name: &nbsp;&nbsp;&nbsp;<?PHP echo $ExamName;?> </strong>
					    				  </TD>
					</TR>
					<TR>
					  <TD colspan="2" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left">
					  		<TABLE width="100%" style="WIDTH: 100%;" cellpadding="10">
							<TBODY>
							<TR bgcolor="#00CCFF">
							  <TD width="28%"  align="left" valign="top">&nbsp;</TD>
							  <TD width="20%"  align="center" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Max Mark (%) </span></TD>
							  <TD width="21%"  align="center" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Pass Mark (%) </span></TD>
							  <TD width="31%"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3 style22">Exam Name </span></TD>
							</TR>
<?PHP
							$counter = 0;
					     	$query3 = "select SubjectId from tbclasssubjectrelation where ClassId = '$OptClassName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$SubjectId = $row["SubjectId"];
								   $query = "select ID,MaxMark,PassMark from tbexammarkssetup where ClassID = '$OptClassName' And ExamID = '$OptExamName' And SubjectID = '$SubjectId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$MarkID  = $dbarray['ID'];
									$MaxMark  = $dbarray['MaxMark'];
									$PassMark  = $dbarray['PassMark'];
									
?>
									<TR>
									  <TD width="28%" bgcolor="#00CCFF" align="left" valign="top"><span class="style3 style22"><?PHP echo GetSubjectName($SubjectId); ?></span></TD>
									  <TD width="20%" align="left" valign="top" bgcolor="#FFFFCC"><span class="style3">&nbsp;
									    <input type="hidden" name="SubjID<?PHP echo $counter; ?>" value="<?PHP echo $SubjectId; ?>">
										<input type="hidden" name="MarkID<?PHP echo $counter; ?>" value="<?PHP echo $MarkID; ?>">
									    <input name="Max<?PHP echo $counter; ?>" type="text" size="15" value="<?PHP echo $MaxMark; ?>">
									  </span></TD>
									  <TD width="21%" align="left" valign="top" bgcolor="#FFFFCC"><span class="style3">&nbsp;
									    <input name="Pass<?PHP echo $counter; ?>" type="text" size="15" value="<?PHP echo $PassMark; ?>">
									  </span></TD>
									  <TD width="31%" align="left" valign="top" bgcolor="#FFFFCC"><span class="style3"><?PHP echo GetExamName($OptExamName); ?></span></TD>
									</TR>
<?PHP
								 }
							 }
?>
							</TBODY>
							</TABLE>
						</TD>
					</TR>
					<TR>
						<TD colspan="2">
						<div align="center">
						  <input type="hidden" name="TotalSubj" value="<?PHP echo $counter; ?>">
                           <input type="hidden" name="ClassID" value="<?PHP echo $OptClassName; ?>">
                           <input type="hidden" name="ExamID" value="<?PHP echo $OptExamName; ?>">
						  <input name="SubmitSave" type="submit" id="SubmitMark" value="&nbsp;&nbsp;Save&nbsp;&nbsp; ">
						</div>
						<p align="center"><span class="style23">Please Note:</span> The Max Mark and the pass mark should be in percentage (%) </p></TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Class Subject Assessment Setup") {
?>
    <?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="exam2.php?subpg=Class Subject Assessment Setup">
                <?PHP
				$query4 = "select ID from tbclasssubjectrelation where ClassId ='$ClassId' and SubjectId ='$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result4 = mysql_query($query4,$conn);
						  $row4 = mysql_fetch_array($result4); 
						  $SetupId = $row4["ID"];
				$query1 = "select ExamName from tbexaminationmaster where ExamID = '$ExamId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result1 = mysql_query($query1,$conn);
									$row1 = mysql_fetch_array($result1);
									$ExamName = $row1["ExamName"];
				   $query2 = "select Class_Name from tbclassmaster where ID ='$ClassId' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				    $result2 = mysql_query($query2,$conn);
					$row2 = mysql_fetch_array($result2);
					$Class_Name = $row2["Class_Name"];
				         $counter1 = 0;
						$query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
						             $row = mysql_fetch_array($result);
										
											$counter1 = $counter1 +1;
											$SetupId2 = $row["ID"];
											$ResultType = $row["ResultType"];
											$Percentage = $row["Percentage"];
											$TotalPer = $TotalPer +$Percentage;
										}
											
?>
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" align="left"><div align="right">Class Name : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OptClassName" type="text" size="55" value="<?PHP echo $Class_Name; ?>" disabled="disabled"></TD>
					</TR>
                    <TR>
					  <TD width="21%" align="left"><div align="right">Subject : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OptClassSubject" type="text" size="55" value="<?PHP echo GetSubjectName($SubjID); ?>" disabled="disabled"></TD>
					</TR>
                    <TR>
					  <TD width="21%" align="left"><div align="right">Examination Name : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OptExamID" type="text" size="55" value="<?PHP echo $ExamName; ?>" disabled="disabled"></TD>
					</TR>
                    <?PHP if(isset($_GET['setup_id2']))
	             { 
				     $assessmentID = $_GET['setup_id2'];
				    $query = "select ResultType,Percentage from tbclassexamsetup where ID ='$assessmentID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						    $result = mysql_query($query,$conn);
						   $row = mysql_fetch_array($result);
						   $ResultType = $row["ResultType"];
						   $Percentage = $row["Percentage"];
	                 ?>
					<TR>
					  <TD width="21%" align="left"><div align="right">Assessment : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OptAssessment" type="text" size="55" value="<?PHP echo $ResultType; ?>"></TD>
					</TR>
                    <TR>
					  <TD width="21%" align="left"><div align="right">Allocated Mark : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OptPercentage" type="text" size="55" value="<?PHP echo $Percentage; ?>"></TD>
					</TR>
                    <?PHP   
	                  }else{ 
				?>	
                <TR>
					  <TD width="21%" align="left"><div align="right">Assessment : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OptAssessment" type="text" size="55" value=""></TD>
					</TR>
                    <TR>
					  <TD width="21%" align="left"><div align="right">Allocated Mark : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="OptPercentage" type="text" size="55" value=""></TD>
					</TR>
                    <?PHP
					  }
					  ?>
					<TR>
						 <TD colspan="2">
							 <table width="539" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="178"><strong>TICK</strong></td>
									<td width="178"><strong>ASSESSMENT</strong></td>
									<td width="154"><strong>ALLOCATED MARK</strong></td>
								  </tr>
                                 <?PHP $query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								 $TotalPer = 0;
						  $result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$counter1 = $counter1 +1;
											$SetupId2 = $row["ID"];
											$ResultType = $row["ResultType"];
											$Percentage = $row["Percentage"];
											$TotalPer = $TotalPer +$Percentage;
	                       ?>

										  <tr>
											<td>
											   <div align="center">
											     <input name="chkExamsetupID2<?PHP echo $counter1; ?>" type="checkbox" value="<?PHP echo $SetupId2; ?>">
									           </div></td>
											<td><div align="center"><a href="exam2.php?subpg=Class Subject Assessment Setup&setup_id2=<?PHP echo $SetupId2; ?>&examid=<?PHP echo $ExamId; ?>&setup_id=<?PHP echo $SetupId; ?>"><?PHP echo $ResultType; ?></a></div></td>
											<td><div align="center"><a href="exam2.php?subpg=Class Subject Assessment Setup&setup_id2=<?PHP echo $SetupId2; ?>&examid=<?PHP echo $ExamId; ?>&setup_id=<?PHP echo $SetupId; ?>"><?PHP echo $Percentage; ?></a></div></td>
										  </tr>
                                         
<?PHP
									 }
								 }
?>
 <tr><td></td><td width="169" align="right"><hr><strong>Total</strong><hr></td><td width="110"><hr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $TotalPer; ?>%<hr></td></tr>
							  </table>
						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						 <div align="center">
						   	 <input type="hidden" name="ClassId" value="<?PHP echo $ClassId; ?>">
							 <input type="hidden" name="ExamId" value="<?PHP echo $ExamId; ?>">
                             <input type="hidden" name="SubjectId" value="<?PHP echo $SubjID; ?>">
						     <input name="Assessment" type="submit" id="userprofile" value="Create New">
						     <input type="hidden" name="TotalExamSetup2" value="<?PHP echo $counter1; ?>">
                             <input name="DeleteExamSetup_delete2" type="submit" id="DeleteExamSetup_delete2" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Assessment" type="submit" id="userprofile" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>

<?PHP
		}elseif ($SubPage == " Class Subject Allocated Mark Setup") {
?>				


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
