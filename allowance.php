<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
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
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	
	$Page = "Pay Roll";
	$audit=update_Monitory('Login','Administrator',$Page);
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_GET['desig_ID']))
	{
		$optDepartment = $_GET['desig_ID'];
	}
	if(isset($_POST['acatmaster']))
	{
		$PageHasError = 0;
		$aCatID = $_POST['SelaCatID'];
		$AllName = $_POST['AllName'];
		
		if(!$_POST['AllName']){
			$errormsg = "<font color = red size = 1>Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['acatmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tballowancecateg where Name = '$DepartName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The category you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tballowancecateg(Name,Session_ID,Term_ID 
) Values ('$AllName','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$AllName = "";
				}
			}elseif ($_POST['acatmaster'] =="Update"){
				$q = "update tballowancecateg set Name = '$AllName' where ID = '$aCatID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$aCatID = "";
				$AllName = "";
			}
		}
	}
	if(isset($_GET['acat_id']))
	{
		$aCatID = $_GET['acat_id'];
		$query = "select * from tballowancecateg where ID='$aCatID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$AllName  = $dbarray['Name'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['acatmaster_delete']))
	{
		$Total = $_POST['TotalaCat'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkaCatID'.$i]))
			{
				$chkaCatIDs = $_POST['chkaCatID'.$i];
				$q = "Delete From tballowancecateg where ID = '$chkaCatIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	
	if(isset($_POST['amaster']))
	{
		$PageHasError = 0;
		$alCode = $_POST['SelalID'];
		$alName = $_POST['alName'];
		$alAmount = $_POST['alAmount'];
		$aDesc = $_POST['aDesc'];
		$optCat = $_POST['optCat'];
		
		if(!$_POST['alAmount']){
			$errormsg = "<font color = red size = 1>Allowance Name is empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($alAmount)){
			$errormsg = "<font color = red size = 1>Amount should be in numbers.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['amaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from allowancemaster where aName = '$alName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The allowance you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into allowancemaster(aName,aDesc,Amount,CategID,Session_ID,Term_ID) Values ('$alName','$aDesc','$alAmount','$optCat','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$alCode = "";
					$alName = "";
					$alAmount = "";
					$aDesc = "";
					$optCat = "";
				}
			}elseif ($_POST['amaster'] =="Update"){
				$q = "update allowancemaster set aName = '$alName',aDesc = '$aDesc',Amount = '$alAmount',CategID = '$optCat' where ID = '$alCode' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$alCode = "";
				$alName = "";
				$alAmount = "";
				$aDesc = "";
				$optCat = "";
			}
		}
	}
	if(isset($_GET['al_id']))
	{
		$alCode = $_GET['al_id'];
		$query = "select * from allowancemaster where ID='$alCode' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$alName  = $dbarray['aName'];
		$alAmount  = $dbarray['Amount'];
		$aDesc  = $dbarray['aDesc'];
		$optCat  = $dbarray['CategID'];
		$disabled = " disabled='disabled'";
	}
	if(isset($_POST['amaster_delete']))
	{
		$Total = $_POST['Totalal'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkalID'.$i]))
			{
				$chkalIDs = $_POST['chkalID'.$i];
				$q = "Delete From allowancemaster where ID = '$chkalIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['OptAmount']))
	{
		$OptAmount = $_POST['OptAmount'];
		$_GET['cat_id'] = $_POST['cat_id'];
		
		
	}else{
		$OptAmount = "Amount";
	}
	if(isset($_POST['OptAllowance']))
	{$OptAllowance = $_POST['OptAllowance'];
	}
	if(isset($_POST['OptTypes']))
	{$OptTypes = $_POST['OptTypes'];
	}
	if(isset($_GET['cat_id']))
	{
		$cat_id = $_GET['cat_id'];
		if($cat_id !=""){
			$query = "select * from tbcategorymaster where ID='$cat_id' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$cName  = $dbarray['cName'];
			$sTitle = "For ".$cName." Staff";
		}
	}
	if(isset($_POST['OpenAllowance']))
	{
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=allowance.php?subpg=Allowance%20Master\">";
		exit;
	}
	if(isset($_POST['AddAllowance']))
	{
		$PageHasError = 0;
		$cat_id = $_POST['cat_id'];
		$OptAllowance = $_POST['OptAllowance'];
		$OptTypes = $_POST['OptTypes'];
		$OptAmount = $_POST['OptAmount'];
		$Amount = $_POST['Amount'];
		
		if($cat_id == ""){
			$errormsg = "<font color = red size = 1>Select employee category</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptAllowance']){
			$errormsg = "<font color = red size = 1>Allowance Name is empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($Amount)){
			$errormsg = "<font color = red size = 1>Amount should be in numbers.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$query = "select * from allowancemaster where ID='$OptAllowance' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$MaxAmount  = formatDatabaseStr($dbarray['Amount']);
			if($MaxAmount < $Amount){
				$errormsg = "<font color = red size = 1>".$OptAmount." entered should not be greater than =N=".$MaxAmount."</font>";
				$PageHasError = 1;
			}
			if($OptAmount == "Percentage %"){
				if($Amount > 100){
					$errormsg = "<font color = red size = 1>Percentage(%) entered should not be greater than 100% </font>";
					$PageHasError = 1;
				}
			}
			if ($PageHasError == 0)
			{
				$num_rows = 0;
				$q = "Insert into allowancesetup(aID,aType,aCal,Amount,CatID,Session_ID,Term_ID) Values ('$OptAllowance','$OptTypes','$OptAmount','$Amount','$cat_id','$Session_ID','$Term_ID')";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				$OptAllowance = "";
				$OptTypes = "";
				$Amount = "";
			}
		}
	}
	if(isset($_POST['SubAllowance']))
	{
		$Total = $_POST['TotalAllowance'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chksetupID'.$i]))
			{
				$chksetupID = $_POST['chksetupID'.$i];
				$q = "Delete From allowancesetup where ID = '$chksetupID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
				$result = mysql_query($q,$conn);
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
			  <TD width="222" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="751" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?><?PHP echo " ".$sTitle; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Allowance Category") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="allowance.php?subpg=Allowance Category">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="64%" valign="top"  align="left"><p style="margin-left:150px;">Name :&nbsp;&nbsp;&nbsp;
                            <input name="AllName" type="text" size="55" value="<?PHP echo $AllName; ?>">
                      </p>
					   </TD>
					</TR>
					<TR>
					   <TD align="left">
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="420" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="62" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="63" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="265" bgcolor="#F4F4F4"><div align="center"><strong>Name</strong></div></td>
                          </tr>
<?PHP
							$counter_aCat = 0;
							$query2 = "select * from tballowancecateg where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_aCat = $rstart;
							}else{
								$counter_aCat = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tballowancecateg where INSTR(Name,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Name";
							}else{
								$query3 = "select * from tballowancecateg where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
  LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "No record found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_dept = $counter_dept+1;
									$counter = $counter+1;
									$CatID = $row["ID"];
									$aCatName = $row["Name"];
?>
									  <tr bgcolor="#CCCCCC">
										<td><div align="center">
										<input name="chkaCatID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $CatID; ?>"></div></td>
										<td><div align="center"><a href="allowance.php?subpg=Allowance Category&acat_id=<?PHP echo $CatID; ?>"><?PHP echo $counter_dept; ?></a></div></td>
										<td><div align="center"><a href="allowance.php?subpg=Allowance Category&acat_id=<?PHP echo $CatID; ?>"><?PHP echo $aCatName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Category&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Category&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Category&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD>
						  <div align="center">
							 <input type="hidden" name="TotalaCat" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelaCatID" value="<?PHP echo $aCatID; ?>">
							 <input name="acatmaster" type="submit" id="acatmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="acatmaster_delete" type="submit" id="acatmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="acatmaster" type="submit" id="acatmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Allowance Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="allowance.php?subpg=Allowance Master">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="50%" valign="top"  align="left">
						<p align="center">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>"><input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </p>
					    <table width="310" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="45" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="48" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="189" bgcolor="#F4F4F4"><div align="center"><strong>Name</strong></div></td>
                          </tr>
<?PHP
							$counter_al = 0;
							$query2 = "select * from allowancemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_al = $rstart;
							}else{
								$counter_al = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from allowancemaster where INSTR(aName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by aName";
							}else{
								$query3 = "select * from allowancemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'  LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "No record found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_al = $counter_al+1;
									$counter = $counter+1;
									$aID = $row["ID"];
									$aName = $row["aName"];
?>
									  <tr bgcolor="#CCCCCC">
										<td><div align="center">
										<input name="chkalID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $aID; ?>"></div></td>
										<td><div align="center"><a href="allowance.php?subpg=Allowance Master&al_id=<?PHP echo $aID; ?>"><?PHP echo $counter_al; ?></a></div></td>
										<td><div align="center"><a href="allowance.php?subpg=Allowance Master&al_id=<?PHP echo $aID; ?>"><?PHP echo $aName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:50px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="50%" valign="top"  align="left">
							<TABLE width="98%">
							<TBODY>
							<TR>
							  <TD width="35%"  align="left">Code :</TD>
							  <TD width="65%"  align="left" valign="top">
									<input name="alCode" type="text" size="5" value="<?PHP echo $alCode; ?>" disabled="disabled">
							   </TD>
							</TR>
							<TR>
							  <TD width="35%"  align="left">Name :</TD>
							  <TD width="65%"  align="left" valign="top">
									<input name="alName" type="text" size="35" value="<?PHP echo $alName; ?>">
							   </TD>
							</TR>
							<TR>
							  <TD width="35%"  align="left">Maximum Amount :</TD>
							  <TD width="65%"  align="left" valign="top">
							  		<input name="alAmount" type="text" size="35" value="<?PHP echo $alAmount; ?>">
							   </TD>
							</TR>
							<TR>
							  <TD width="35%"  align="left">Description :</TD>
							  <TD width="65%"  align="left" valign="top">
										<textarea name="aDesc" cols="35" rows="4"><?PHP echo $aDesc; ?></textarea>
								</TD>
							</TR>
							<TR>
							  <TD width="35%"  align="left">Category :</TD>
							  <TD width="65%"  align="left" valign="top"><label>
							    <select name="optCat">
								<option value="0" selected="selected">&nbsp;</option>
<?PHP
								$query = "select ID,Name from tballowancecateg where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Name";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$CatID = $row["ID"];
										$CatName = $row["Name"];
										if($optCat =="$CatID"){
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
							      </select>
							  </label></TD>
							</TR>
						</TBODY>
						</TABLE>
					  </TD>
					 </TR>
					 <TR>
						 <TD colspan="2">
						  <div align="center">
							 <input type="hidden" name="Totalal" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelalID" value="<?PHP echo $alCode; ?>">
							 <input name="amaster" type="submit" id="amaster" value="Create New" <?PHP echo $disabled; ?>>
							 <input name="amaster_delete" type="submit" id="amaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
							 <input name="amaster" type="submit" id="amaster" value="Update">
							 <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					  </TR>
					 </TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Allowance Setup") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="allowance.php?subpg=Allowance Setup">
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
				<TABLE width="99%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="29%" valign="top"  align="left">
					    <table width="209" border="0" align="left" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="35" bgcolor="#F4F4F4"><div align="center" class="style21">SrNo.</div></td>
                            <td width="153" bgcolor="#F4F4F4"><div align="center"><strong>Category</strong></div></td>
                          </tr>
<?PHP
							$counter_cat = 0;
							$query3 = "select * from tbcategorymaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by cName";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows <= 0 ) {
								echo "No record found.";
							}
							else 
							{
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_cat = $counter_cat+1;
									$cID = $row["ID"];
									$cName = $row["cName"];
?>
									  <tr>
										<td><div align="center"><a href="allowance.php?subpg=Allowance Setup&cat_id=<?PHP echo $cID; ?>"><?PHP echo $counter_cat; ?></a></div></td>
										<td><div align="center"><a href="allowance.php?subpg=Allowance Setup&cat_id=<?PHP echo $cID; ?>"><?PHP echo $cName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    
					  </TD>
					  <TD width="71%" valign="top"  align="left">
							<TABLE width="100%">
							<TBODY>
							<TR>
							  <TD width="19%"  align="left">Allowance :</TD>
							  <TD width="81%"  align="left" valign="top"><label>
							    <select name="OptAllowance" onChange="javascript:setTimeout('__doPostBack(\'OptAllowance\',\'\')', 0)">
								<option value="0" selected="selected">&nbsp;</option>
<?PHP
								$query = "select ID,aName from allowancemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by aName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$alID = $row["ID"];
										$aName = $row["aName"];
										if($OptAllowance =="$alID"){
?>
											<option value="<?PHP echo $alID; ?>" selected="selected"><?PHP echo $aName; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $alID; ?>"><?PHP echo $aName; ?></option>
<?PHP
										}
									}
								}
?>

							      </select>
							    <input title="Click to open allowance master page" name="OpenAllowance" type="submit" id="OpenAllowance" value="....">
							  </label></TD>
							</TR>
							<TR>
							  <TD width="19%"  align="left">Type :</TD>
							  <TD width="81%"  align="left" valign="top">
							  <select name="OptTypes" onChange="javascript:setTimeout('__doPostBack(\'OptTypes\',\'\')', 0)">
<?PHP
									if($OptTypes =="Earning"){
?>
										<option value="Earning" selected="selected">Earning</option>
							    		<option value="Deduction">Deduction</option>
<?PHP
									}else{
?>
										<option value="Earning">Earning</option>
							    		<option value="Deduction" selected="selected">Deduction</option>
<?PHP
									}
?>
                              </select></TD>
							</TR>
							<TR>
							  <TD width="19%"  align="left">Calculation :</TD>
							  <TD width="81%"  align="left" valign="top">
							  <input type="hidden" name="cat_id" value="<?PHP echo $cat_id; ?>">
							   <select name="OptAmount" onChange="javascript:setTimeout('__doPostBack(\'OptAmount\',\'\')', 0)">
<?PHP
									if($OptAmount =="Amount"){
?>
										<option value="Amount" selected="selected">Amount</option>
										<option value="Percentage %">Percentage %</option>
<?PHP
									}else{
?>
										<option value="Amount" >Amount</option>
										<option value="Percentage %" selected="selected">Percentage %</option>
<?PHP
									}
?>
                               </select></TD>
							</TR>
							<TR>
							  <TD width="19%"  align="left"><?PHP echo $OptAmount; ?>   :</TD>
							  <TD width="81%"  align="left" valign="top">
							  	<input type="hidden" name="cat_id" value="<?PHP echo $cat_id; ?>">
							  	<input name="Amount" type="text" size="35" value="<?PHP echo $Amount; ?>">
							    <input title="Click to save allowance" name="AddAllowance" type="submit" id="SubmitAllowance" value="  +  " <?PHP echo $disabled; ?>>
							    <input title="Click to delete allowance" name="SubAllowance" type="submit" id="SubmitAllowance" value="  -  " <?PHP echo $disabled; ?>></TD>
							</TR>
							<TR>
							  <TD colspan="2" align="left">
							  	 <table width="517" border="0" align="left" cellpadding="3" cellspacing="3">
								  <tr>
									<td width="35" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
									<td width="39" bgcolor="#F4F4F4"><div align="center"><strong>SrNo.</strong></div></td>
									<td width="109" bgcolor="#F4F4F4"><div align="center"><strong>Allowance</strong></div></td>
									<td width="96" bgcolor="#F4F4F4"><div align="center"><strong>Type</strong></div></td>
									<td width="99" bgcolor="#F4F4F4"><div align="center"><strong>Formula</strong></div></td>
									<td width="82" bgcolor="#F4F4F4"><div align="center"><strong>Amount</strong></div></td>
								  </tr>
<?PHP
									$counter_setup = 0;
									$query2 = "select * from allowancesetup where CatID = '$cat_id'";
									$result2 = mysql_query($query2,$conn);
									$num_rows2 = mysql_num_rows($result2);
									
									if($rstart==0){
										$counter_setup = $rstart;
									}else{
										$counter_setup = $rstart-1;
									}
									$counter = 0;
									$query3 = "select * from allowancesetup where CatID = '$cat_id' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
									$num_rows = mysql_num_rows($result3);
									if ($num_rows <= 0 ) {
										echo "";
									}
									else 
									{
										while ($row = mysql_fetch_array($result3)) 
										{
											$counter_setup = $counter_setup+1;
											$counter = $counter+1;
											$SetupID = $row["ID"];
											$aID = $row["aID"];
											$aType = $row["aType"];
											$aCal = $row["aCal"];
											$Amount = $row["Amount"];
											
											$query = "select aName from allowancemaster where ID='$aID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$aName  = $dbarray['aName'];
											
?>											  <tr bgcolor="#CEDAE8">
												<td><div align="center">
											<input name="chksetupID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SetupID; ?>"></div></td>
											<td><div align="center"><?PHP echo $counter; ?></div></td>
											<td><div align="center"><?PHP echo $aName; ?></div></td>
											<td><div align="center"><?PHP echo $aType; ?></div></td>
											<td><div align="center"><?PHP echo $aCal; ?></div></td>
											<td><div align="center"><?PHP echo $Amount; ?></div></td>
										  </tr>
<?PHP
										 }
									 }
?>
									<tr>
										<td colspan="6"bgcolor="#FFFFFF"><p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Setup&st=0&ed=<?PHP echo $rend; ?>&cat_id=<?PHP echo $cat_id; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Setup&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&cat_id=<?PHP echo $cat_id; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="allowance.php?subpg=Allowance Setup&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&cat_id=<?PHP echo $cat_id; ?>">Next</a> </p></td>
								    </tr>
								</table>
							  	<input type="hidden" name="TotalAllowance" value="<?PHP echo $counter; ?>">
							  </TD>
							</TR>
						</TBODY>
						</TABLE>
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
