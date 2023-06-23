<?PHP
	session_start();
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "System Setup";
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
	
	
	if(isset($_POST['Classmaster']))
	{
		$PageHasError = 0;
		$SelClassID = $_POST['Selec_ClassId'];
		$classname = $_POST['classname'];
		$classstandard = $_POST['classstandard'];
		$Maxlecture = $_POST['Maxlecture'];
		$Breaklecture = $_POST['Breaklecture'];
		$optIncharge = $_POST['optIncharge'];
		$SeatingCap = $_POST['SeatingCap'];
         
		 $Total = $_POST['TotalCharges'];
		  /*for($i=1;$i<=$Total;$i++){
			if(!$_POST['ClassChgAmt2'.$i])
				{
					 $errormsg = "<font color = red size = 1>A Class Charge Value is Empty. Please Enter a Value or Enter 'NULL'.</font>";
					 $PageHasError = 1;
				}
			}*/
		
		if(!$_POST['classname']){
			$errormsg = "<font color = red size = 1>Class Name is empty.</font>";
			$PageHasError = 1;
		}
		
					
		elseif(!is_numeric($Maxlecture)){
			$errormsg = "<font color = red size = 1>Max lecture Allowed should be in numbers</font>";
			$PageHasError = 1;
		}
		
		elseif(!is_numeric($Breaklecture)){
			$errormsg = "<font color = red size = 1>Break after lecture no should be in numbers</font>";
			$PageHasError = 1;
		}
	
		//if(!is_numeric($ClassChgAmt)){
			//$errormsg = "<font color = red size = 1>Error on class charge amount</font>";
		    //$PageHasError = 1;
		//}
		elseif(!$_POST['optIncharge']){
			$errormsg = "<font color = red size = 1>Select teacher incharge</font>";
			$PageHasError = 1;
		}
		elseif(!$_POST['SeatingCap']){
			$errormsg = "<font color = red size = 1>Seating Capacity is empty</font>";
			$PageHasError = 1;
		}
		elseif ($PageHasError == 0)
		{
			if ($_POST['Classmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbclassmaster where Class_Name = '$classname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The class you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
				$q = "Insert into tbclassmaster(Class_Name,Class_Standard,MaxLec,BreakLec,Session_ID,Term_ID) Values 
				      ('$classname','$classstandard','$Maxlecture','$Breaklecture','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$SelClassID = GetClassID($classname);
					
					$q = "Insert into tbclasssection(ClassID,Incharge,Seat_capacity,Session_ID,Term_ID) Values   
					 ('$SelClassID','$optIncharge','$SeatingCap','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
				
					
					//GET CLASS CHARGES
				
					$Total = $_POST['TotalCharges'];
					for($i=1;$i<=$Total;$i++){
						if($_POST['classChgName'.$i])
						{
							//$ClassChgAmt = $_POST['ClassChgAmt'.$i];
							$classChgName = $_POST['classChgName'.$i];
							
								$q = "Insert into tbclasscharges(ClassID,ChargeName,Session_ID,Term_ID) Values ('$SelClassID','$classChgName','$Session_ID','$Term_ID')";
								$result = mysql_query($q,$conn);
							}
							if($_POST['classChgName'.$i])
						{
							$ClassChgAmt = $_POST['ClassChgAmt2'.$i];
							
							    $q =  "update tbclasscharges set Amount = '$ClassChgAmt' where ChargeName = '$classChgName'and ClassID = '$SelClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($q,$conn);
							}
							elseif(!$_POST['classChgName'.$i]){
								$ClassChgAmt = 0;
							
							    $q =  "update tbclasscharges set Amount = '$ClassChgAmt' where ChargeName = '$classChgName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($q,$conn);
							}
								
					}
					
					
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					$classname = "";
					$classstandard = "";
					$Maxlecture = "";
					$Breaklecture = "";
					$optIncharge = "";
					$SeatingCap = "";
					$ClassChgAmt = "";
				}
			}
			
			elseif ($_POST['Classmaster'] =="Update")
			{
		      $SelClassID = $_POST['Selec_ClassId'];
		      $classname = $_POST['classname'];
		        $classstandard = $_POST['classstandard'];
		        $Maxlecture = $_POST['Maxlecture'];
		            $Breaklecture = $_POST['Breaklecture'];
		            $optIncharge = $_POST['optIncharge'];
		                $SeatingCap = $_POST['SeatingCap'];
				$q = "update tbclassmaster set Class_Name = '$classname',Class_Standard = '$classstandard',MaxLec = '$Maxlecture',BreakLec = '$Breaklecture' where ID = '$SelClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
				
				//$q = "Delete From tbclasscharges where ClassID = '$SelClassID'";
				//$result = mysql_query($q,$conn);
				
				$Total = $_POST['TotalCharges'];
				for($i=1;$i<=$Total;$i++){
					if($_POST['classChgName'.$i])
					{
						$ClassChgAmt = $_POST['ClassChgAmt2'.$i];
						 $classChgName = $_POST['classChgName'.$i];
						 $query = "select * from tbclasscharges where ChargeName='$classChgName' and ClassID = '$SelClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		                          $result = mysql_query($query,$conn);
								  $num_rows = mysql_num_rows($result);
				                     if ($num_rows > 0 ) 
				                         {
					               $q = "update tbclasscharges set Amount = '$ClassChgAmt' where ChargeName = '$classChgName' and ClassID = '$SelClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							       $result = mysql_query($q,$conn);
				}else {
							$q = "Insert into tbclasscharges(ClassID,ChargeName,Amount,Session_ID,Term_ID) Values ('$SelClassID','$classChgName','$ClassChgAmt','$Session_ID','$Term_ID')";
							$result = mysql_query($q,$conn);
				          }
						}
					
				}
				//$query = "select ID from tbclassmaster where Class_Name = '$classname'";
				
				
				
						$q = "update tbclasssection set Incharge = '$optIncharge',Seat_capacity = '$SeatingCap' where ClassID = '$SelClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result = mysql_query($q,$conn);
					
				
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				$disabled = " disabled='disabled'";
			
		
	}
  }
}
if(isset($_GET['Clas_id']))
	{
		$SelClassID = $_GET['Clas_id'];
		$query = "select * from tbclassmaster where ID='$SelClassID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$classname  = $dbarray['Class_Name'];
		$classstandard  = $dbarray['Class_Standard'];
		$Maxlecture  = $dbarray['MaxLec'];
		$Breaklecture  = $dbarray['BreakLec'];
		$disabled = " disabled='disabled'";
		$query = "select * from tbclasssection where ClassID='$SelClassID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$optIncharge  = $dbarray['Incharge'];
		$SeatingCap  = $dbarray['Seat_capacity'];
	}
	/*if(isset($_GET['term_id']))
	{
		$Selterm_id = $_GET['term_id'];
		$query = "select * from tbclasssection where ID='$Selterm_id'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SelClassID  = $dbarray['ClassID'];
		$Classterm  = $dbarray['ClassTerm'];
		$optIncharge  = $dbarray['Incharge'];
		$SeatingCap  = $dbarray['Seat_capacity'];
		
	}*/
	if(isset($_POST['Classmaster_delete']))
	{
		
		
		
		$Total = 0;
		$Total = $_POST['TotalClass'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkClassID'.$i]))
			{
				$chkClassIDs = $_POST['chkClassID'.$i];
				$q = "Delete From tbclasssection where ClassID = '$chkClassIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
				$q = "Delete From tbclasscharges where ClassID = '$chkClassIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
				$q = "Delete From tbclassmaster where ID = '$chkClassIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
			    $errormsg = "<font color = red size = 1>entry deleted successfully.</font>"; 	
				
				
			}
		  
		}
	  
	}
	if(isset($_POST['DayHotelCharges']))
	{
		$bk = "System Setup";
		$SubPage = "Hosteller and Day Scholar Charges";
	}
	if(isset($_POST['OptType']))
	{
		$OptType = $_POST['OptType'];
	}
	if(isset($_POST['AddSelected']))
	{
		if($_POST['OptType']== "select")
	   {
		$errormsg = "<font color = red size = 1>Please select student type.</font>";   
	    }
		else
		{
		$OptType = $_POST['OptType'];
		
		
		$Total = $_POST['TotalCharges'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkChargeID'.$i]))
			{
				$chargeID = $_POST['chkChargeID'.$i];
				 
				$num_rows = 0;
				$query = "select ID from tbstudentcharges where sType = '$OptType' and chargeID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The charge you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else{
				$q = "Insert into tbstudentcharges(sType,chargeID,Session_ID,Term_ID) Values ('$OptType','$chargeID','$Session_ID','$Term_ID')";
				$result = mysql_query($q,$conn);

				$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
			   }
			}
		}
	  }
	}
	if(isset($_POST['DeleteCharges']))
	{
		$OptType = $_POST['OptType'];
		$Total = $_POST['TotalCharges2'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkChargeID'.$i]))
			{
				$chargeID = $_POST['chkChargeID'.$i];
		$q = "Delete From tbstudentcharges where sType = '$OptType' and chargeID ='$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);
		
		$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
			}
		}
	}
	if(isset($_POST['SetTerm']))
	{
		$Classterm = $_POST['Classterm'];
		$q = "update section set Active = '0' where ID > '0'";
		$result = mysql_query($q,$conn);
		
		$q = "update section set Active = '1' where Section = '$Classterm'";
		$result = mysql_query($q,$conn);
	}
	if(isset($_POST['SetStudentTerm']))
	{
		$query2 = "select Section from section where Active = '1'";
		$result2 = mysql_query($query2,$conn);
		$dbarray2 = mysql_fetch_array($result2);
		$Activeterm  = $dbarray2['Section'];
		
		$counter_stud = 0;
		$query3 = "select ID,AdmissionNo from tbstudentmaster where Stu_Class !='' And AdmissionNo !=''";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$counter_stud = $counter_stud+1;
				$RecID = $row["ID"];
				$AdmissionNo = $row["AdmissionNo"];
										
				$q = "update tbstudentmaster set Stu_Sec = '$Activeterm' where ID = '$RecID'";
				$result = mysql_query($q,$conn);
			}
		}
		$errormsg = "<font color = blue size = 1>".$counter_stud." student records was update successfully.</font>";
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
<script language="JavaScript">
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->
</script>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
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
			  <TD width="221" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="847" align="center" valign="top">
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
		if ($SubPage == "Class Master") {
?>
				<?PHP echo $errormsg; ?>
                 
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong>
<?PHP
				if(isset($_GET['bk']))
				{
					$backpg = $_GET['bk'];
					echo $backpg;
				}
?>
				</strong></div>
				<form name="form1" method="post" action="mas_setup2.php?subpg=Class Master">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="left">
					  		<table width="451" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="76" >Class Name :</td>
								<td width="125" ><input name="classname" type="text" size="15" value="<?PHP echo $classname; ?>">
								*</td>
								<td width="93">Class Standard :</td>
								<td width="118"><input name="classstandard" type="text" size="13" value="<?PHP echo $classstandard; ?>"></td>
							  </tr>
							  <tr>
								<td colspan="4">Max lecture Allowed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="Maxlecture" type="text" size="25" value="<?PHP echo $Maxlecture; ?>">
								  <label>*</label></td>
							  </tr>
							  <tr>
								<td colspan="4">Break after lecture no :&nbsp;&nbsp;&nbsp;<input name="Breaklecture" type="text" size="25" value="<?PHP echo $Breaklecture; ?>">
								*</td>
							  </tr><tr><td colspan="4"><strong>Class Charges:</strong></td></tr>
							  <tr>
								<td colspan="4">
									
									<table width="339" border="1" align="left" cellpadding="0" cellspacing="0">
<?PHP
									$Count_Charge = 0;
									$query = "select ID,ChargeName from tbchargemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ChargeName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows <= 0 ) {
										echo "No charge Found.";
									}
									else 
									{
										while ($row = mysql_fetch_array($result)) 
										{
											$Count_Charge = $Count_Charge+1;
											$chargeID = $row["ID"];
											$ChargeName = $row["ChargeName"];
											
											// GET ClASS CHARGES
											$SelAmount = "";
											$query2 = "select Amount from tbclasscharges where ClassID='$SelClassID' And ChargeName ='$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result2 = mysql_query($query2,$conn);
											$dbarray2 = mysql_fetch_array($result2);
											$SelAmount  = $dbarray2['Amount'];
?>
											  <tr>
												<td width="261" bgcolor="#F4F4F4"><?PHP echo $ChargeName; ?></td>
												<td width="72">
												<input type="hidden" name="classChgName<?PHP echo $Count_Charge; ?>" value="<?PHP echo $ChargeName; ?>">
                                                <input type="hidden" name="ClassChgAmt<?PHP echo $Count_Charge; ?>" value="<?PHP echo $SelAmount; ?>">
												<input name="ClassChgAmt2<?PHP echo $Count_Charge; ?>" type="text" size="10" value="<?PHP echo $SelAmount; ?>"></td>
											  </tr>
<?PHP
										 }
									 }
?>
									</table>
								</td>
							  </tr>
							  <!----><tr>
								<td colspan="4"><div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid;">
								   <p>Incharge:&nbsp;
                                  <select name="optIncharge">
								  	<option value="" selected="selected">Select</option>
<?PHP
									$query = "select ID,EmpName from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName";
									//$query = "select ID,EmpName from tbemployeemasters order by EmpName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows <= 0 ) {
										echo "No teacher Found.";
									}
									else 
									{
										while ($row = mysql_fetch_array($result)) 
										{
											$EmpID = $row["ID"];
											$EmpName = $row["EmpName"];
											if($optIncharge =="$EmpID"){
?>
												<option value="<?PHP echo $EmpID; ?>" selected="selected"><?PHP echo $EmpName; ?></option>
<?PHP
											}
?>
											<option value="<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></option>
<?PHP
										}
									}
?>
                                  </select>
                                  	</p>						  
                                  <p>Seating Capacity:&nbsp;
								  <input name="SeatingCap" type="text" size="15" value="<?PHP echo $SeatingCap; ?>">
                                  </p>
								  <table width="420" border="0" align="center" cellpadding="3" cellspacing="3">
									  <tr>
										<!--<td width="39" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
										<td width="130" bgcolor="#F4F4F4"><div align="center" class="style21">Term</div></td>-->
										<td width="128" bgcolor="#F4F4F4"><div align="center"><strong>Incharge</strong></div></td>
										<td width="84" bgcolor="#F4F4F4"><div align="center"><strong>Seating</strong></div></td>
									  </tr>
<?PHP
										$counter_term = 0;
										$query3 = "select * from tbclasssection where ClassID = '$SelClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result3 = mysql_query($query3,$conn);
										$num_rows = mysql_num_rows($result3);
										if ($num_rows <= 0 ) {
											//echo "No term Found.";
										}
										else 
										{
											while ($row = mysql_fetch_array($result3)) 
											{
												$counter_term = $counter_term+1;
												$Incharge = $row["Incharge"];
												$Seat_capacity = $row["Seat_capacity"];
?>
												  <tr>
												  	<!--<td><div align="center">
											     <input name="chkTermID" type="checkbox" value="">
									           </div></td>-->
													
													<td><div align="center"><?PHP echo GET_EMP_NAME($Incharge); ?></div></td>
													<td><div align="center"><?PHP echo $Seat_capacity; ?></div></td>
												  </tr>
<?PHP
											 }
										 }
?>
									</table>
								   </div>
								</td>
							   </tr>
							</table>
					  </TD>
					  <TD width="36%" align="left" valign="top">
							<table width="246" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="191"><strong>Class Name</strong></td>
								<td width="191"><strong>Class Standard</strong></td>
							  </tr>
<?PHP
								$counter_Class = 0;
								$query = "select * from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ID";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Class Found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter_Class = $counter_Class+1;
										$Class_ID = $row["ID"];
										$Class_Name = $row["Class_Name"];
										$Class_Standard = $row["Class_Standard"];
						
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkClassID<?PHP echo $counter_Class; ?>" type="checkbox" value="<?PHP echo $Class_ID; ?>">
									           </div></td>
											<td><div align="center"><a href="mas_setup2.php?subpg=Class Master&Clas_id=<?PHP echo $Class_ID; ?>"><?PHP echo $Class_Name; ?></a></div></td>
											<td><div align="center"><a href="mas_setup2.php?subpg=Class Master&Clas_id=<?PHP echo $Class_ID; ?>"><?PHP echo $Class_Standard; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						     <input type="hidden" name="TotalClass" value="<?PHP echo $counter_Class; ?>">		
							 <input type="hidden" name="TotalCharges" value="<?PHP echo $Count_Charge; ?>">						
							 <input type="hidden" name="Selec_ClassId" value="<?PHP echo $SelClassID; ?>">
							 <input type="hidden" name="Selec_Selterm_id" value="<?PHP echo $Selterm_id; ?>">
							 <input name="DayHotelCharges" type="submit" id="Classmaster2" value="Define Hostel and Day Scholar Charges">						
						     <input name="Classmaster" type="submit" id="Classmaster" value="Create New" >
						     <input name="Classmaster_delete" type="submit" id="Classmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Classmaster" type="submit" id="Classmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Hosteller and Day Scholar Charges") {
?>

				<?PHP echo $errormsg; ?>
               


				
<?PHP
			
?>
				
<?PHP
			
?>
				<form name="form1" method="post" action="mas_setup2.php?subpg=Hosteller and Day Scholar Charges">
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
                    
                    <TR><td width="64%" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;Select Student Type :<label>
								<select name="OptType" onChange="javascript:setTimeout('__doPostBack(\'OptType\',\'\')', 0)">
<?PHP
									if($OptType =="Scholar"){
?>
                                        <option value="select" >Select</option>
										<option value="Scholar" selected="selected">Day Scholar</option>
										<option value="Hosteller">Hosteller</option>
<?PHP
									}elseif($OptType =="Hosteller"){
?> 
                                         <option value="select" >Select</option>
										<option value="Hosteller" selected="selected">Hosteller</option>
										<option value="Scholar">Day Scholar</option>
<?PHP
									}else{
?>                                       
                                        <option value="select" selected="selected">Select</option>
										<option value="Scholar">Day Scholar</option>
										<option value="Hosteller">Hosteller</option>
<?PHP
									}
?>
								    </select>
								</label></td></TR>
					<TR>
					  <TD width="490" valign="top"  align="left">
					  		<table width="451" border="2" align="left" cellpadding="3"  cellspacing="3">
                            
							  <tr>
                              <?PHP
							    if($OptType =="Hosteller"){
							?>
								<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>HOSTELLER CHARGES </strong><br>
									<table width="259" border="0" align="left" cellpadding="3" cellspacing="3">
									  <tr bgcolor="#ECE9D8">
										<td width="65"><div align="center"><strong>TICK</strong></div></td>
										<td width="173"><strong>Charge Name</strong></td>
									  </tr>
<?PHP
										$Count_Charge = 0;
										
										$query = "select ID,chargeID from tbstudentcharges where sType='$OptType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "No charge Found.";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$Count_Charge = $Count_Charge+1;
												$chargeID = $row["chargeID"];
												$query2 = "select ChargeName from tbchargemaster where ID='$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
												$result2 = mysql_query($query2,$conn);
												$num_rows2 = mysql_num_rows($result2);
												if($num_rows2 >0){
													$row2= mysql_fetch_array($result2);
												$ChargeName = $row2["ChargeName"];
												}
											
?>
											  <tr>
												<td><div align="center">
													<input name="chkChargeID<?PHP echo $Count_Charge; ?>" type="checkbox" value="<?PHP echo $chargeID; ?>" <?PHP echo $isTic; ?>>
												</div></td>
												<td><div align="left"><?PHP echo $ChargeName; ?></div></td>
											  </tr>
<?PHP
                               $ChargeName="";
											 }
										 }
?>
                                    <TR>
						 <TD align="center" colspan="2">
						  <div>
						  	<input type="hidden" name="TotalCharges2" value="<?PHP echo $Count_Charge; ?>">
						    <input name="DeleteCharges" type="submit" id="DeleteCharges" value="Delete Selected" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						  </div>
						  </TD>
					</TR>
                           			</table>
								  
								</td>
							  <?PHP 
                                     }
						elseif($OptType =="Scholar"){
						   ?>
                        <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>DAY SCHOLAR CHARGES </strong><br>
									<table width="259" border="0" align="left" cellpadding="3" cellspacing="3">
									  <tr bgcolor="#ECE9D8">
										<td width="65"><div align="center"><strong>TICK</strong></div></td>
										<td width="173"><strong>Charge Name</strong></td>
									  </tr>
<?PHP
										$Count_Charge = 0;
										
										$query = "select ID,chargeID from tbstudentcharges where sType='$OptType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "No charge Found.";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$Count_Charge = $Count_Charge+1;
												$chargeID = $row["chargeID"];
												$query2 = "select ChargeName from tbchargemaster where ID='$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
												$result2 = mysql_query($query2,$conn);
												$num_rows2 = mysql_num_rows($result2);
												if($num_rows2 >0){
													$row2= mysql_fetch_array($result2);
												$ChargeName = $row2["ChargeName"];
												}
											
?>
											  <tr>
												<td><div align="center">
													<input name="chkChargeID<?PHP echo $Count_Charge; ?>" type="checkbox" value="<?PHP echo $chargeID; ?>" <?PHP echo $isTic; ?>>
												</div></td>
												<td><div align="left"><?PHP echo $ChargeName; ?></div></td>
											  </tr>
<?PHP
                                  $ChargeName="";
											 }
										 }
?>
                           			<TR>
						 <TD colspan="2">
						  <div align="center">
						  	<input type="hidden" name="TotalCharges2" value="<?PHP echo $Count_Charge; ?>">
						     <input name="DeleteCharges" type="submit" id="DeleteCharges" value="Delete Selected" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						  </div>
						  </TD>
					</TR>
                                    </table>
								  
								</td>
							  
                        <?PHP
						}
						?>
                        </tr>
							</table>
						</TD>
					  <TD width="500" valign="top"  align="left">
					  		<table width="410" border="2" align="left" cellpadding="3" cellspacing="3">
							  <tr><td colspan="2"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELECT CHARGES AS APPLICABLE :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><br>
									<table width="259" border="0" align="left" cellpadding="3" cellspacing="3">
									  <tr bgcolor="#ECE9D8">
										<td width="65"><div align="center"><strong>TICK</strong></div></td>
										<td width="173"><strong>Charge Name</strong></td></tr>
                                        <?PHP
										$Count_Charge = 0;
										$query = "select ID,ChargeName from tbchargemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
 order by ChargeName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "No charge Found.";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$Count_Charge = $Count_Charge+1;
												$chargeID = $row["ID"];
												$ChargeName = $row["ChargeName"];
											
?>
											  <tr>
												<td><div align="center">
													<input name="chkChargeID<?PHP echo $Count_Charge; ?>" type="checkbox" value="<?PHP echo $chargeID; ?>" <?PHP echo $isTic; ?>>
												</div></td>
												<td><div align="left"><?PHP echo $ChargeName; ?></div></td>
											  </tr>
<?PHP
											 }
										 }
?>
                           			<TR>
						 <TD colspan="2">
						  <div align="center">
						  	<input type="hidden" name="TotalCharges" value="<?PHP echo $Count_Charge; ?>">
						     <input name="AddSelected" type="submit" id="AddSelected" value="Add Selected" >
						  </div>
						  </TD>
					</TR>
                   </table>
                        </td></tr></table></TD>                
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
