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
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
	}
	
	
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	// filename: photo.php 
	//header and logo transfer to images
	

	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseLogo.processor.php'; 
	$uploadHandler2 = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseHeader.processor.php'; 
	// set a max file size for the html upload form 
	$max_file_size = 1000000; // size in bytes OR 30kb
	
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	/*if(isset($_GET['filename']))
	{
		$LogoFilename = $_GET['filename'];
	}else{
		$LogoFilename = "empty_r2_c2.jpg";
	}
	if(isset($_GET['Hfilename']))
	{
		$HeaderFilename = $_GET['Hfilename'];
	}else{
		$HeaderFilename = "empty_r2_c2.jpg";
	}*/
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	//$query2 = "select ID from tbschool ";
	//$result2 = mysql_query($query2,$conn);
	//$dbarray2 = mysql_fetch_array($result2);
	//$SchID  = $dbarray2['ID'];
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
		
	$Page = "System Setup";
	$audit=update_Monitory('Login','Administrator',$Page);
	$errormsg ="";
	if(isset($_POST['SubmitSchool']))
	{
		$PageHasError = 0;
		$SchID = 3;
		$schName = $_POST['schName'];
		$LogoFilename = $_POST['LogoFilename'];
		$HeaderFilename = $_POST['$HeaderFilename'];
		$schOtherName1 = $_POST['schOtherName1'];
		$schOtherName2 = $_POST['schOtherName2'];
		$schAddress = $_POST['schAddress'];
		$schState = $_POST['schState'];
		$schCountry = $_POST['schCountry'];
		$schPhone = $_POST['schPhone'];
		$schFax = $_POST['schFax'];
		$schEmail1 = $_POST['schEmail1'];
		$schEmail2 = $_POST['schEmail2'];

		if(!$_POST['schName']){
			$errormsg = "<font color = red size = 1>School Name is empty.</font>";
			$PageHasError = 1;
			}
		elseif(strlen($_POST['schName'])>200){
			$errormsg = "<font color = red size = 1>Sorry, Profile Name is longer than 200 characters, please shorten it.</font>";
			$PageHasError = 1;
			$SchID = 3;
		}
		/*if($SchID ==""){
			$errormsg = "<font color = red size = 1>School ID is empty</font>";
			$PageHasError = 1;
		}*/
		
		elseif ($PageHasError == 0)
		{
			/*$query = "select * from tbschool where ID = '$SchID'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0 ) {
			  $q = "update tbschool set SchName = '$schName', otherName1 = '$schOtherName1',OtherName2 = '$schOtherName2', SchAddress = '$schAddress',SchState = '$schState', SchCountry = '$schCountry',SchPhone = '$schPhone', SchFax = '$schFax',SchEmail1 = '$schEmail1', SchEmail2 = '$schEmail2', Logo = '$LogoFilename' where ID = '$SchID'";
			  $result = mysql_query($q,$conn);
			  
			  $errormsg = "<font color = blue size = 1>Record updated successfully.</font>";
			}
			else 
			{
				if ($SchID ==1) */
					$q = "update tbschool set SchName = '$schName', otherName1 = '$schOtherName1',OtherName2 = '$schOtherName2', SchAddress = '$schAddress',SchState = '$schState', SchCountry = '$schCountry',SchPhone = '$schPhone', SchFax = '$schFax',SchEmail1 = '$schEmail1', SchEmail2 = '$schEmail2', Logo = '$LogoFilename', HeaderPic = '$HeaderFilename' where ID = '$SchID'";
			  $result = mysql_query($q,$conn);
				/*}else{
					$q = "Insert into tbschool(SchName,otherName1,OtherName2,SchAddress,SchState,SchCountry,SchPhone,SchFax,SchEmail1,SchEmail2,Logo) Values ('$schName','$schOtherName1','$schOtherName2','$schAddress','$schState','$schCountry','$schPhone','$schFax','$schEmail2','$schEmail2','$LogoFilename')where ID = '$SchID'";
					$result = mysql_query($q,$conn);*/
				
				$errormsg = "<font color = blue size = 1>Record saved successfully.</font>";
				$schName ="";
				$schOtherName1 ="";
				$schOtherName2 ="";
				$schAddress ="";
				$schState ="";
				$schCountry ="";
				$schPhone ="";
				$schFax ="";
				$schEmail1 ="";
				$schEmail2 ="";}
		}
			
		
	
	if(isset($_POST['citymaster']))
	{
		$PageHasError = 0;
		$CityID = $_POST['SelCityID'];
		$CityName = $_POST['CityName'];
		$Citystate = $_POST['Citystate'];
		$CityCountry = $_POST['CityCountry'];
		
		if(!$_POST['CityName']){
			$errormsg = "<font color = red size = 1>City Name is empty.</font>";
			$PageHasError = 1;
		}
		
		elseif ($PageHasError == 0)
		{
			if ($_POST['citymaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from mcity where CityName = '$CityName' And state = '$Citystate'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The City you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into mcity(CityName,state,Country,Session_ID,Term_ID) Values ('$CityName','$Citystate','$CityCountry')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CityID = "";
					$CityName = "";
					$Citystate = "";
					$CityCountry = "";
				}
			}elseif ($_POST['citymaster'] =="Update"){
				$q = "update mcity set CityName = '$CityName', state = '$Citystate', Country = '$CityCountry' where ID = '$CityID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CityID = "";
				$CityName = "";
				$Citystate = "";
				$CityCountry = "";
			}
		}
	}
	if(isset($_GET['city_id']))
	{
		$CityID = $_GET['city_id'];
		$query = "select * from mcity where ID='$CityID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CityName  = $dbarray['CityName'];
		$Citystate  = $dbarray['state'];
		$CityCountry  = $dbarray['Country'];
	}
	if(isset($_POST['citymaster_delete']))
	{
		$Total = $_POST['TotalCity'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkcyID'.$i]))
			{
				$CyIDs = $_POST['chkcyID'.$i];
				$q = "Delete From mcity where ID = '$CyIDs'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Docmaster']))
	{
		$PageHasError = 0;
		$DocID = $_POST['SelDocID'];
		$DocName = $_POST['DocName'];
		
		if(!$_POST['DocName']){
			$errormsg = "<font color = red size = 1>Document Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Docmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbdocumentmaster where Name = '$DocName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Document you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbdocumentmaster(Name,Session_ID,Term_ID) Values ('$DocName','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$DocName = "";
				}
			}elseif ($_POST['Docmaster'] =="Update"){
				$q = "update tbdocumentmaster set Name = '$DocName' where ID = '$DocID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$DocID = "";
				$DocName = "";
			}
		}
	}
	if(isset($_GET['doc_id']))
	{
		$DocID = $_GET['doc_id'];
		$query = "select * from tbdocumentmaster where ID='$DocID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$DocName  = $dbarray['Name'];
	}
	if(isset($_POST['Docmaster_delete']))
	{
		$Total = $_POST['TotalDoc'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkdocID'.$i]))
			{
				$docIDs = $_POST['chkdocID'.$i];
				$q = "Delete From tbdocumentmaster where ID = '$docIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Relmaster']))
	{
		$PageHasError = 0;
		$RelID = $_POST['SelRelID'];
		$Religion = $_POST['Religion'];
		$Description = $_POST['Description'];
		
		if(!$_POST['Religion']){
			$errormsg = "<font color = red size = 1>Religion Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Relmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbreligionmaster where ReligionName = '$Religion'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Religion you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbreligionmaster(ReligionName,ReligionDesc) Values ('$Religion','$Description')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$Code = "";
					$Religion = "";
					$Description = "";
				}
			}elseif ($_POST['Relmaster'] =="Update"){
				$q = "update tbreligionmaster set ReligionName = '$Religion',ReligionDesc = '$Description' where ID = '$RelID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$Code = "";
				$Religion = "";
				$Description = "";
			}
		}
	}
	if(isset($_GET['rel_id']))
	{
		$Code = $_GET['rel_id'];
		$query = "select * from tbreligionmaster where ID='$Code'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Religion  = $dbarray['ReligionName'];
		$Description  = $dbarray['ReligionDesc'];
	}
	if(isset($_POST['Relmaster_delete']))
	{
		$Total = $_POST['TotalRel'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkrelID'.$i]))
			{
				$relIDs = $_POST['chkrelID'.$i];
				$q = "Delete From tbreligionmaster where ID = '$relIDs'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['chargetype']))
	{
		$chargetype = $_POST['chargetype'];
		$ChargeName = $_POST['ChargeName'];
	$ShortName = $_POST['ShortName'];
	}
	if(isset($_POST['newStudent']))
	{
		$newStudent = $_POST['newStudent'];
		$ChargeName = $_POST['ChargeName'];
	$ShortName = $_POST['ShortName'];
	}
	if(isset($_POST['oldStudent']))
	{
		$oldStudent = $_POST['oldStudent'];
		$ChargeName = $_POST['ChargeName'];
	$ShortName = $_POST['ShortName'];
	}
	
	if(isset($_POST['Chgmaster']))
	{
		$PageHasError = 0;
		$chargeID = $_POST['SelChgID'];
		$ChargeName = $_POST['ChargeName'];
		$ShortName = $_POST['ShortName'];
		$chargetype = $_POST['chargetype'];
		if($_POST['newStudent'] == 1){
			$newStudent = $_POST['newStudent'];
		}else{
		     $newStudent = 0;
		                   }
		if($_POST['oldStudent'] == 1){
			$oldStudent = $_POST['oldStudent'];
		 }else{
		     $oldStudent = 0;
			  }
		
		if(!$_POST['ChargeName']){
			$errormsg = "<font color = red size = 1>Charge Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Chgmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The school charge you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					if($newStudent==""){
						$newStudent = 0;
					}
					if($oldStudent==""){
						$oldStudent = 0;
					}
					
					$q = "Insert into tbchargemaster(ChargeName,ShortName,Required,NewStudent,OldStudent,Session_ID,Term_ID) Values ('$ChargeName','$ShortName','$chargetype','$newStudent','$oldStudent','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					$ChargeName = "";
					$ShortName = "";
					$chargetype = "";
					$newStudent = "";
					$oldStudent = "";
					
				}
			}elseif ($_POST['Chgmaster'] =="Update"){
				$q = "update tbchargemaster set ChargeName = '$ChargeName',ShortName = '$ShortName',Required = '$chargetype',NewStudent = '$newStudent',OldStudent = '$oldStudent' where ID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$ChargeName = "";
				$ShortName = "";
				$chargetype = "";
				$newStudent = "";
				$oldStudent = "";
				
			}
		}
	}
	if(isset($_GET['chg_id']))
	{
		$chargeID = $_GET['chg_id'];
		$query = "select * from tbchargemaster where ID='$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$ChargeName  = $dbarray['ChargeName'];
		$ShortName  = $dbarray['ShortName'];
		$chargetype  = $dbarray['Required'];
		$newStudent  = $dbarray['NewStudent'];
		$oldStudent  = $dbarray['OldStudent'];
		
	}
	if(isset($_POST['Chgmaster_delete']))
	{
		$Total = $_POST['TotalChg'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkchgID'.$i]))
			{
				$chgIDs = $_POST['chkchgID'.$i];
				$query = "select * from tbchargemaster where ID='$chgIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		              $result = mysql_query($query,$conn);
		                 $dbarray = mysql_fetch_array($result);
		                  $ChargeName  = $dbarray['ChargeName'];
						  $q = "Delete From tbclasscharges where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				        $result = mysql_query($q,$conn);
				$q = "Delete From tbchargemaster where ID = '$chgIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$q = "Delete From tbfeepayment where ChargeID = '$chgIDs' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
			}
		}
	}
	if(isset($_POST['Leavemaster']))
	{
		$PageHasError = 0;
		$sLeaveID = $_POST['SelLeaveID'];
		$LeaveType = $_POST['LeaveType'];
		$Description = $_POST['Description'];
		$isPaid = $_POST['isPaid'];
		if($isPaid == 0){
			$isPaidNo  = "checked='checked'";
			$isPaidYes  = "";
		}elseif($isPaid == 1){
			$isPaidNo  = "";
			$isPaidYes  = "checked='checked'";
		}
		$dat = date('Y'.'-'.'m'.'-'.'d');
		if(!$_POST['LeaveType']){
			$errormsg = "<font color = red size = 1>Leave Type is empty.</font>";
			$PageHasError = 1;
		}
		if($_POST['isPaid'] ==""){
			$errormsg = "<font color = red size = 1>Paid should be selected.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['Leavemaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbleavemaster where LeaveName = '$LeaveType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Leave Type you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbleavemaster(LeaveName,Descr,lDate,Paid,Session_ID,Term_ID) Values ('$LeaveType','$Description','$dat','$isPaid','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
					
					$LeaveType = "";
					$Description = "";
					$dat = "";
					$isPaid = "";
					$isPaidYes = "";
					$isPaidNo = "";
				}
			}elseif ($_POST['Leavemaster'] =="Update"){
				$q = "update tbleavemaster set LeaveName = '$LeaveType',Descr = '$Description',lDate = '$dat', Paid = '$isPaid' where ID = '$sLeaveID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$LeaveType = "";
				$Description = "";
				$dat = "";
				$isPaid = "";
				$isPaidYes = "";
				$isPaidNo = "";
			}
		}
	}
	if(isset($_GET['lev_id']))
	{
		$sLeaveID = $_GET['lev_id'];
		$query = "select * from tbleavemaster where ID='$sLeaveID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$LeaveType  = $dbarray['LeaveName'];
		$Description  = $dbarray['Descr'];
		$isPaid  = $dbarray['Paid'];
		if($isPaid == 0){
			$isPaidNo  = "checked='checked'";
			$isPaidYes  = "";
		}elseif($isPaid == 1){
			$isPaidNo  = "";
			$isPaidYes  = "checked='checked'";
		}
	}
	if(isset($_POST['Leave_delete']))
	{
		$Total = $_POST['TotalLeave'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chklevID'.$i]))
			{
				$chklevID = $_POST['chklevID'.$i];
				$q = "Delete From tbleavemaster where ID = '$chklevID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['GetStudent']))
	{
		$OptSearch = $_POST['OptSearch'];
		$OptClass = $_POST['OptClass'];
		$StuName = $_POST['StuName'];
	}
	if(isset($_POST['GetStaff']))
	{
		$OpteSearch = $_POST['OpteSearch'];
		$OptDept = $_POST['OptDept'];
		$EmpName = $_POST['EmpName'];
	}
	if(isset($_GET['adm_No']))
	{
		$adm_No = $_GET['adm_No'];
		$query = "select Stu_Type,Stu_Full_Name,Stu_Class from tbstudentmaster where AdmissionNo='$adm_No' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StuType  = $dbarray['Stu_Type'];
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);
		$stuClass  = strtoupper($dbarray['Stu_Class']);
		$stuClass = GetClassName($stuClass);

		
		$_POST['OptSearch'] = $_GET['Optsrc'];
		$OptSearch = $_GET['Optsrc'];
		$OptClass = $_GET['clss'];
		$StuName = $_GET['name'];
		$_POST['StuName'] = $_GET['name'];
		
	}
	if(isset($_GET['emp_ID']))
	{
		$emp_ID = $_GET['emp_ID'];
		$query = "select EmpName,EmpDept from tbemployeemasters where ID='$emp_ID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpDept  = strtoupper($dbarray['EmpDept']);
		$Staffname  = strtoupper($dbarray['EmpName']);
		
		$query = "select DeptName from tbdepartments where ID = '$EmpDept' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$staffdept = $dbarray['DeptName'];

		
		$_POST['OpteSearch'] = $_GET['Optsrc'];
		$OpteSearch = $_GET['Optsrc'];
		$OptDept = $_GET['dept'];
		$EmpName = $_GET['name'];
		$_POST['EmpName'] = $_GET['name'];
		
	}
	if(isset($_POST['SubmitLeave']))
	{
		$PageHasError = 0;
		$lev_aid = $_POST['AppID'];
		$adm_No = $_POST['StudAdmNo'];
		if($adm_No==""){
			$adm_No = 0;
		}else{
			$AppType = "Student";
		}
		$emp_ID = $_POST['StaffID'];
		if($emp_ID==""){
			$emp_ID = 0;
		}else{
			$AppType = "Staff";
		}
		$OptLeave = $_POST['OptLeave'];
		$frmDate = $_POST['fr_Yr']."-".$_POST['fr_Mth']."-".$_POST['fr_Dy'];
		$fr_Yr = $_POST['fr_Yr'];
		$fr_Mth = $_POST['fr_Mth'];
		$fr_Dy = $_POST['fr_Dy'];
		
		$toDate = $_POST['to_Yr']."-".$_POST['to_Mth']."-".$_POST['to_Dy'];
		$fr_Yr = $_POST['to_Yr'];
		$fr_Mth = $_POST['to_Mth'];
		$fr_Dy = $_POST['to_Dy'];
		
		
		$Description = $_POST['Description'];
		$dat = date('Y'.'-'.'m'.'-'.'d');
		if(!$_POST['OptLeave']){
			$errormsg = "<font color = red size = 1>Leave Type is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SubmitLeave'] =="Create New"){
				$num_rows = 0;
				if($AdmNo>0){
					$query = "select ID from tbleaveapplication where LeaveID = '$OptLeave' And AdmNo = '$adm_No' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				}elseif($emp_ID>0){
					$query = "select ID from tbleaveapplication where LeaveID = '$OptLeave' And EmpID = '$emp_ID' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				}
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Leave Application you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbleaveapplication(LeaveID,AdmNo,EmpID,Leave_Fr,Leave_To,Descr,AppType,Term,Session_ID,Term_ID) Values ('$OptLeave','$adm_No','$emp_ID','$frmDate','$toDate','$Description','$AppType','$Activeterm','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);

					$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
					
					$OptLeave = "";
					$adm_No = "";
					$frmDate = "";
					$toDate = "";
					$Description = "";
					$AppType = "";
				}
			}elseif ($_POST['SubmitLeave'] =="Update"){
				$q = "update tbleaveapplication set LeaveID = '$OptLeave',Leave_Fr = '$frmDate',Leave_To = '$toDate', Descr = '$Description' where ID='$lev_aid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$OptLeave = "";
				$adm_No = "";
				$frmDate = "";
				$toDate = "";
				$Description = "";
				$AppType = "";
			}elseif ($_POST['SubmitLeave'] =="Delete"){
				$q = "Delete From tbleaveapplication where ID = '$lev_aid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
				
				$OptLeave = "";
				$adm_No = "";
				$frmDate = "";
				$toDate = "";
				$Description = "";
				$AppType = "";
			}
		}
		$OptSearch = $_POST['OptSearch'];
		$OptClass = $_POST['OptClass'];
		$StuName = $_POST['StuName'];
	}
	if(isset($_GET['lev_aid']))
	{
		$lev_aid = $_GET['lev_aid'];
		$query = "select * from tbleaveapplication where ID='$lev_aid' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptLeave  = $dbarray['LeaveID'];
		$adm_No  = $dbarray['AdmNo'];
		$Description  = $dbarray['Descr'];
		$EmpID  = $dbarray['EmpID'];
		$Leave_Fr  = $dbarray['Leave_Fr'];
		$arrDate=explode ('-', $Leave_Fr);
		$fr_Dy = $arrDate[2];
		$fr_Mth = $arrDate[1];
		$fr_Yr = $arrDate[0];
		$Leave_To  = $dbarray['Leave_To'];
		$arrDate2=explode ('-', $Leave_To);
		$to_Dy = $arrDate2[2];
		$to_Mth = $arrDate2[1];
		$to_Yr = $arrDate2[0];
		
		$query = "select Stu_Type,Stu_Full_Name,Stu_Class from tbstudentmaster where AdmissionNo='$adm_No' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StuType  = $dbarray['Stu_Type'];
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);
		$stuClass  = strtoupper($dbarray['Stu_Class']);
		$stuClass = GetClassName($stuClass);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNET Manager</TITLE>
<META content="Viodvla.com is the official website of the Directorate for Road Traffic Services in Nigeria." name="Short Title">
<META content="Nigeria Centre for Road Traffic" name=AGLS.Function>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/menu.css" type=text/css rel=stylesheet>
<style type="text/css">
<!--
.style2 {font-size: 12px}
-->
</style>
<style type="text/css">
<!--
.style1 {font-size: 11px}
-->
</style>

<style type="text/css">
<!--
.style5 {
	color: #000099;
	font-weight: bold;
}

.style17 {color: #000000; font-weight: bold; }
.style20 {font-size: 12px}
-->
</style>
<style type="text/css">td img {display: block;}</style>
<style type="text/css">
.a{
margin:0px auto;
position:relative;
width:300px;
}

.b{
overflow:auto;
width:auto;
height:250px;
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
			  <TD width="204" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="874" align="center" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="55" 
					  align="left" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "School Name and Logo Setup") {
?>
                   <?PHP echo $errormsg; ?>
                   <?PHP
							$query = "select * from tbschool WHERE ID = '3'";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
									$SchID = $row["ID"];
									$schName = $row["SchName"];
									$schOtherName1 = $row["otherName1"];
									$schOtherName2 = $row["OtherName2"];
									$schAddress = $row["SchAddress"];
									$schState = $row["SchState"];
									$schCountry = $row["SchCountry"];
									$schPhone = $row["SchPhone"];
									$schFax = $row["SchFax"];
									$schEmail1 = $row["SchEmail1"];
									$schEmail2 = $row["SchEmail2"];
									$LogoFilename = $row["Logo"];
									$HeaderFilename = $row["HeaderPic"];
								}
							}
		/*function copyemz($file1,$file2){ 
          $contentx =@file_get_contents($file1); 
                   $openedfile = fopen($file2, "w"); 
                   fwrite($openedfile, $contentx); 
                   fclose($openedfile); 
                    if ($contentx === FALSE) { 
                    $status=false; 
                    }else {$status=true;} 
                    
                    return $status; 
     } */
							$file1 = 'images/upload/'.$HeaderFilename;  //header and logo transfer from images/uploads file to images 
							$file2 = 'images/'.$HeaderFilename;
							$contentx =@file_get_contents($file1); 
							$openedfile = fopen($file2, "w"); 
							 fwrite($openedfile, $contentx); 
                                     fclose($openedfile); 
							//$file3 =  copyemz($file1,$file2);
							$file4 = 'images/upload/'.$LogoFilename;
							$file5 = 'images/'.$LogoFilename;
							$contentx =@file_get_contents($file4); 
							$openedfile = fopen($file5, "w"); 
							 fwrite($openedfile, $contentx); 
                                     fclose($openedfile); 
							//$file6 = copyemz($file4,$file5);
						
			
?>
			
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="75%" align="left" valign="top">
					        <form id="Upload2" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post"> 
							  <TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
								<TBODY>
								<TR bgcolor="#CCCCCC">
									<TD colspan="2" align="left"><input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"></TD>
								</TR>
								<TR>
									<TD width=19%><label for="file">
									  <div align="right">LOGO FILE</div>
									  </label>  </TD>
									<TD width=81% align="left"><input id="file" type="file" name="file">
                                     <input type="hidden" name="SchID" value="<?PHP echo $SchID; ?>">
									  <input id="submit" type="submit" name="submit" value="Upload logo"></TD>
								</TR>
							  </TBODY></TABLE>	
							</form>

					  		<form id="Upload2" action="" method="post"> 
					  		<table width="529" border="0" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="93"><div align="right">Name : </div></td>
								<td colspan="3">
								    <input name="schName" type="text" size="55"  value="<?PHP echo $schName; ?>">								</td>
							  </tr>
							  <tr>
								<td><div align="right">Other Name 1 : </div></td>
								<td colspan="3"><input name="schOtherName1" type="text" size="55"  value="<?PHP echo $schOtherName1; ?>"></td>
							  </tr>
							  <tr>
								<td><div align="right">Other Name 2 : </div></td>
								<td  colspan="3"><input name="schOtherName2" type="text" size="55"  value="<?PHP echo $schOtherName2; ?>"></td>
							  </tr>
							  <tr>
								<td><div align="right">Address : </div></td>
								<td colspan="3"><input name="schAddress" type="text" size="55"  value="<?PHP echo $schAddress; ?>"></td>
							  </tr>
							  <tr>
								<td><div align="right">State : </div></td>
								<td width="157"><input name="schState" type="text" size="25"  value="<?PHP echo $schState; ?>"></td>
								<td width="72">Country</td>
								<td width="168"><input name="schCountry" type="text" size="25"  value="<?PHP echo $schCountry; ?>"></td>
							  </tr>
							  <tr>
								<td><div align="right">Phone : </div></td>
								<td><input name="schPhone" type="text" size="25"  value="<?PHP echo $schPhone; ?>"></td>
								<td>Fax</td>
								<td><input name="schFax" type="text" size="25"  value="<?PHP echo $schFax; ?>"></td>
							  </tr>
							  <tr>
								<td><div align="right">Email : </div></td>
								<td><input name="schEmail1" type="text" size="25"  value="<?PHP echo $schEmail1; ?>"></td>
								<td>Email 2 </td>
								<td><input name="schEmail2" type="text" size="25"  value="<?PHP echo $schEmail2; ?>"></td>
							  </tr>
							  <TR>
									 <TD colspan="4">
									<div align="center">
									  <input type="hidden" name="LogoFilename" value="<?PHP echo $LogoFilename; ?>">
                                      <input type="hidden" name="HeaderFilename" value="<?PHP echo $HeaderFilename; ?>">
									  <input type="hidden" name="schoolID" value="<?PHP echo $SchID; ?>">
									  <input name="SubmitSchool" type="submit" id="SubmitSave" value="Create New">
									  <input type="reset" name="Reset2" value="Reset">
									</div>
									<label>
									<div align="center"></div>
									</label></TD>
								</TR>
							</table>
					  		</form>
					  </TD>
					  <TD width="25%" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="221">
						  <tr>
						   <td><img src="images/spacer.gif" width="21" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="178" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="22" height="1" border="0" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
						  </tr>
						     <?php $LogoFilename = "ShalomPetersLogo.jpg";  ?>
							<?php $HeaderFilename = "PeterMonsterilogo.jpg";  ?>
						  <tr>
						   <td colspan="3"><img name="empty_r1_c1" src="images/empty_r1_c1.jpg" width="221" height="20" border="0" id="empty_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="empty_r2_c1" src="images/empty_r2_c1.jpg" width="21" height="197" border="0" id="empty_r2_c1" alt="" /></td>
						   <td><img src="images/<?PHP echo $LogoFilename; ?>" width="178" height="175"></td>
						   <td rowspan="2"><img name="empty_r2_c3" src="images/empty_r2_c3.jpg" width="22" height="197" border="0" id="empty_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="175" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="empty_r3_c2" src="images/empty_r3_c2.jpg" width="178" height="22" border="0" id="empty_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="22" border="0" alt="" /></td>
						  </tr>
						</table>	      
					    </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						 	<Div style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:144px;">
						   		<div align="center"><img src="images/<?php echo $HeaderFilename ?>" width="825" height="144"></div>
							</Div>
							<form id="Upload2" action="<?php echo $uploadHandler2 ?>" enctype="multipart/form-data" method="post"> 
							  <TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
								<TBODY>
								<TR bgcolor="#CCCCCC">
									<TD colspan="2" align="left"><input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"></TD>
								</TR>
								<TR>
									<TD width=19%><label for="file">
									  <div align="right">Header File:</div>
									  </label>  </TD>
									<TD width=81% align="left">
										<input id="file" type="file" name="file">
									   <input type="hidden" name="SchoID" value="<?PHP echo $SchID; ?>">
									  <input id="submit" type="submit" name="submit" value="Upload Header">
									  </TD>
								</TR>
							  </TBODY></TABLE>	
							</form>	
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
<?PHP
		}elseif ($SubPage == "City Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="mas_setup.php?subpg=City Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" align="left"><div align="right">City ID : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="CityID" type="text" size="55" value="<?PHP echo $CityID; ?>" disabled="disabled"></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">City Name : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="CityName" type="text" size="55"value="<?PHP echo $CityName; ?>"></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">State : </div></TD>
					  <TD width="79%" valign="top"  align="left">
					  		<SELECT name="Citystate">
								<OPTION value="">Choose State</OPTION>
<?PHP
								if($Citystate != ""){
?>
									<option value="<?PHP echo $Citystate; ?>" selected="selected"><?PHP echo $Citystate; ?></option>
<?PHP
								}
?>
								<OPTION value="Abia">Abia</OPTION>
								<OPTION value="Adamawa">Adamawa</OPTION>
								<OPTION value="Akwa Ibom">Akwa Ibom</OPTION>
								<OPTION value="Anambra">Anambra</OPTION>
								<OPTION value="Bauchi">Bauchi</OPTION>
								<OPTION value="Bayelsa">Bayelsa</OPTION>
								<OPTION value="Benue">Benue</OPTION>
								<OPTION value="Borno">Borno</OPTION>
								<OPTION value="Cross River">Cross River</OPTION>
								<OPTION value="Delta">Delta</OPTION>
								<OPTION value="Ebonyi">Ebonyi</OPTION>
								<OPTION value="Edo">Edo</OPTION>
								<OPTION value="Ekiti">Ekiti</OPTION>
								<OPTION value="Enugu">Enugu</OPTION>
								<OPTION value="Federal Capital Territory">Federal Capital Territory</OPTION>
								<OPTION value="Gombe">Gombe</OPTION>
								<OPTION value="Imo">Imo</OPTION>
								<OPTION value="Jigawa">Jigawa</OPTION>
								<OPTION value="Kaduna">Kaduna</OPTION>
								<OPTION value="Kano">Kano</OPTION>
								<OPTION value="Katsina">Katsina</OPTION>
								<OPTION value="Kebbi">Kebbi</OPTION>
								<OPTION value="Kogi">Kogi</OPTION>
								<OPTION value="Kwara">Kwara</OPTION>
								<OPTION value="Lagos">Lagos</OPTION>
								<OPTION value="Nassarawa">Nassarawa</OPTION>
								<OPTION value="Niger">Niger</OPTION>
								<OPTION value="Ogun">Ogun</OPTION>
								<OPTION value="Ondo">Ondo</OPTION>
								<OPTION value="Osun">Osun</OPTION>
								<OPTION value="Oyo">Oyo</OPTION>
								<OPTION value="Plateau">Plateau</OPTION>
								<OPTION value="Rivers">Rivers</OPTION>
								<OPTION value="Sokoto">Sokoto</OPTION>
								<OPTION value="Taraba">Taraba</OPTION>
								<OPTION value="Yobe">Yobe</OPTION>
								<OPTION value="Zamfara">Zamfara</OPTION>
							</SELECT></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">Country: </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="CityCountry" type="text" size="55"value="<?PHP echo $CityCountry; ?>"></TD>
					</TR>
					<TR>
						 <TD colspan="2">
							 <p>&nbsp;</p>
							 <table width="463" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="50"><strong>TICK</strong></td>
									<td width="169"><strong>CITY</strong></td>
									<td width="47"><strong>ID</strong></td>
									<td width="158"><strong>STATE</strong></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from mcity order by CityName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No city found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelCityID = $row["ID"];
										$citynames = $row["CityName"];
										$state = $row["state"];
										$Country = $row["Country"];
										
			
?>
										  <tr>
											<td>
											   <div align="center">
											     <input type="hidden" name="CyID<?PHP echo $counter; ?>" value="<?PHP echo $SelCityID; ?>">
											     <input name="chkcyID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelCityID; ?>">
									           </div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=City Master&city_id=<?PHP echo $SelCityID; ?>"><?PHP echo $citynames; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=City Master&city_id=<?PHP echo $SelCityID; ?>"><?PHP echo $SelCityID; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=City Master&city_id=<?PHP echo $SelCityID; ?>"><?PHP echo $state; ?></a></div></td>
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
						   	 <input type="hidden" name="TotalCity" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCityID" value="<?PHP echo $CityID; ?>">
						     <input name="citymaster" type="submit" id="userprofile" value="Create New">
						     <input name="citymaster_delete" type="submit" id="userprofile_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="citymaster" type="submit" id="userprofile" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Document Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="mas_setup.php?subpg=Document Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" align="left"><div align="right">Document ID : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="DocID" type="text" size="55" value="<?PHP echo $DocID; ?>" disabled="disabled"></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">Document : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="DocName" type="text" size="55" value="<?PHP echo $DocName; ?>"></TD>
					</TR>
					<TR>
						 <TD colspan="2">
							 <table width="539" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="178"><strong>TICK</strong></td>
									<td width="178"><strong>DOCUMENT</strong></td>
									<td width="154"><strong>ID</strong></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbdocumentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Name";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Document found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelDocID = $row["ID"];
										$documentName = $row["Name"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkdocID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelDocID; ?>">
									           </div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Document Master&doc_id=<?PHP echo $SelDocID; ?>"><?PHP echo $documentName; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Document Master&doc_id=<?PHP echo $SelDocID; ?>"><?PHP echo $SelDocID; ?></a></div></td>
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
						   	 <input type="hidden" name="TotalDoc" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelDocID" value="<?PHP echo $DocID; ?>">
						     <input name="Docmaster" type="submit" id="userprofile" value="Create New">
						     <input name="Docmaster_delete" type="submit" id="userprofile_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Docmaster" type="submit" id="userprofile" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Religion Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="mas_setup.php?subpg=Religion Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>RELIGION</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter = 0;
								$query = "select * from tbreligionmaster order by ReligionName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Religion Found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelRelID = $row["ID"];
										$ReligionName = $row["ReligionName"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkrelID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelRelID; ?>">
									           </div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Religion Master&rel_id=<?PHP echo $SelRelID; ?>"><?PHP echo $ReligionName; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="66">Code :</td>
								<td width="314"><input name="Code" type="text" size="55" value="<?PHP echo $Code; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Religion :</td>
								<td><input name="Religion" type="text" size="55" value="<?PHP echo $Religion; ?>"></td>
							  </tr>
							  <tr>
								<td>Description :</td>
								<td><textarea name="Description" cols="55" rows="5"><?PHP echo $Description; ?></textarea></td>
							  </tr>
							</table>
					  
					  
					  
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalRel" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelRelID" value="<?PHP echo $Code; ?>">
						     <input name="Relmaster" type="submit" id="Relmaster" value="Create New">
						     <input name="Relmaster_delete" type="submit" id="Relmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Relmaster" type="submit" id="Relmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "School Charges") {
?>
				<?PHP echo $errormsg;
				?>
				<form name="form1" method="post" action="mas_setup.php?subpg=School Charges">
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
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="16%" align="left"><div align="right">Charge Name : </div></TD>
					  <TD width="48%"  align="left" valign="top"><input name="ChargeName" type="text" size="55" value="<?PHP echo $ChargeName; ?>"></TD>
					  <TD width="15%"  align="left" valign="top" rowspan="2"><div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid">
<?PHP
						if($chargetype == 0){
							$Chk_chargetype0 ="checked='checked'";
						}elseif($chargetype == 1){
							$Chk_chargetype1 ="checked='checked'";
						}
?>
					    <p><input name="chargetype" type="radio" value="0" <?PHP echo $Chk_chargetype0; ?> onChange="javascript:setTimeout('__doPostBack(\'chargetype\',\'\')', 0)">Compulsory</p>
					    <p><input name="chargetype" type="radio" value="1" <?PHP echo $Chk_chargetype1; ?> onChange="javascript:setTimeout('__doPostBack(\'chargetype\',\'\')', 0)">Optional</p></div></TD>
					  <TD width="21%"  align="left" valign="top"  rowspan="2"><div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid">
<?PHP
						if($newStudent == 1){
							$Chk_newStudent ="checked='checked'";
						         }
					if($oldStudent == 1){
							$Chk_oldStudent ="checked='checked'";
							    }
?>
                          <p><input name="newStudent" type="checkbox" value="1" <?PHP echo $Chk_newStudent; ?> onChange="javascript:setTimeout('__doPostBack(\'newStudent\',\'\')', 0)">New Student</p>
                           <p><input name="oldStudent" type="checkbox" value="1" <?PHP echo $Chk_oldStudent; ?> onChange="javascript:setTimeout('__doPostBack(\'oldStudent\',\'\')', 0)">Old Student</p></div></TD>
					</TR>
					<TR>
					  <TD width="16%" align="left"><div align="right">Short Name : </div></TD>
					  <TD width="48%" valign="top"  align="left"><input name="ShortName" type="text" size="55" value="<?PHP echo $ShortName; ?>"></TD>
					</TR>
					
					<TR>
					  <TD colspan="4"align="right">&nbsp;</TD>
					</TR>
					<TR>
					  <TD colspan="4"align="right">&nbsp;</TD>
					</TR>
					<TR>
						 <TD colspan="4">
						 <div align="center">
						 	 <input type="hidden" name="SelChgID" value="<?PHP echo $chargeID; ?>">
						     <input name="Chgmaster" type="submit" id="Chgmaster" value="Create New">
						     <input name="Chgmaster" type="submit" id="Chgmaster" value="Update">
							 <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				<hr>
				 <table width="539" border="0" align="center" cellpadding="3" cellspacing="3">
					  <tr bgcolor="#ECE9D8">
						<td width="51"><strong>TICK</strong></td>
						<td width="292"><strong>CHARGE NAME </strong></td>
						<td width="166"><strong>SHORT NAME </strong></td>
					  </tr>
<?PHP
					$counter = 0;
					$query = "select ID,ChargeName,ShortName from tbchargemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows <= 0 ) {
						echo "No School Charge found.";
					}
					else 
					{
						while ($row = mysql_fetch_array($result)) 
						{
							$counter = $counter+1;
							$SelChargeIID = $row["ID"];
							$ChargeName = $row["ChargeName"];
							$ShortName = $row["ShortName"];
?>
							  <tr>
								<td>
								   <div align="center">
									 <input name="chkchgID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelChargeIID; ?>">
								   </div></td>
								<td><div align="center"><a href="mas_setup.php?subpg=School Charges&chg_id=<?PHP echo $SelChargeIID; ?>"><?PHP echo $ChargeName; ?></a></div></td>
								<td><div align="center"><a href="mas_setup.php?subpg=School Charges&chg_id=<?PHP echo $SelChargeIID; ?>"><?PHP echo $ShortName; ?></a></div></td>
							  </tr>
<?PHP
						 }
					 }
?>
					 <tr>
						 <td colspan="3">
						 <div align="center">
						   	 <input type="hidden" name="TotalChg" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelChgID" value="<?PHP echo $chargeID; ?>">
						     <input name="Chgmaster_delete" type="submit" id="Chgmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						</div>
						 </td>
					</tr>
				  </table>			
				</form>
<?PHP
		}elseif ($SubPage == "Leave Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="mas_setup.php?subpg=Leave Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" align="left"><div align="right">Leave Type : </div></TD>
					  <TD width="79%" valign="top"  align="left"><input name="LeaveType" type="text" size="55" value="<?PHP echo $LeaveType; ?>"></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">Leave Description : </div></TD>
					  <TD width="79%" valign="top"  align="left">
					  	<input name="Description" type="text" size="55" value="<?PHP echo $Description; ?>"></TD>
					</TR>
					<TR>
					  <TD width="21%" align="left"><div align="right">Paid : </div></TD>
					  <TD width="79%" valign="top"  align="left">
					  	<input name="isPaid" type="radio" value="1" <?PHP echo $isPaidYes; ?>> Yes 
					    <input name="isPaid" type="radio" value="0" <?PHP echo $isPaidNo; ?>>No </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						 <div align="center">
							 <input type="hidden" name="SelLeaveID" value="<?PHP echo $sLeaveID; ?>">
						     <input name="Leavemaster" type="submit" id="Leavemaster" value="Create New">
						     <input name="Leavemaster" type="submit" id="Leavemaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
					<TR>
						 <TD colspan="2">
							 <table width="539" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="48"><strong>TICK</strong></td>
									<td width="124"><div align="center"><strong>LEAVE TYPE </strong></div></td>
									<td width="236"><div align="center"><strong>DESCRIPTION </strong></div></td>
									<td width="92"><div align="center"><strong>PAID STATUS</strong></div></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbleavemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by LeaveName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Leave found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$LeaveID = $row["ID"];
										$LeaveName = $row["LeaveName"];
										$Descr = $row["Descr"];
										$Paid = $row["Paid"];
										if($Paid == 0){
											$iPaid = "No";
										}elseif($Paid == 1){
											$iPaid = "Yes";
										}
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chklevID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $LeaveID; ?>">
									           </div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Leave Master&lev_id=<?PHP echo $LeaveID; ?>"><?PHP echo $LeaveName; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Leave Master&lev_id=<?PHP echo $LeaveID; ?>"><?PHP echo $Descr; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Leave Master&lev_id=<?PHP echo $LeaveID; ?>"><?PHP echo $iPaid; ?></a></div></td>
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
						   	 <input type="hidden" name="TotalLeave" value="<?PHP echo $counter; ?>">
						     <input name="Leave_delete" type="submit" id="Leave_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input type="reset" name="Reset" value="Reset">
						</div>
						 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Student Leave Application") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="mas_setup.php?subpg=Student Leave Application">
				<TABLE width="94%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="32%" valign="top"  align="left">
					  		<strong>Search Criteria</strong>
							<p>
<?PHP
							if($OptSearch =="All")
							{
								echo "<input type='radio' name='OptSearch' value='All' checked='checked'/>All Student";
							}else{
								echo "<input type='radio' name='OptSearch' value='All'/>All Student";
							}
?>
							</p>
							<p>
<?PHP
							if($OptSearch =="Class")
							{
								echo "<input type='radio' name='OptSearch' value='Class' checked='checked'/>Class";
							}else{
								echo "<input type='radio' name='OptSearch' value='Class'/>Class";
							}
?>
							<select name="OptClass">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
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
									$ClassID = $row["ID"];
									$ClassName = $row["Class_Name"];
									if($OptClass ==$ClassID){
?>
										<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $ClassName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $ClassID; ?>"><?PHP echo $ClassName; ?></option>
<?PHP
									}
								}
							}
?>
					    	</select>
					  		</p>
							<p>
<?PHP
							if($OptSearch =="Name")
							{
								echo "<input type='radio' name='OptSearch' value='Name' checked='checked'/>Name";
							}else{
								echo "<input type='radio' name='OptSearch' value='Name'/>Name";
							}
?>					  		   
							<input type="text" name="StuName" size="10" value="<?PHP echo $StuName; ?>">
							<input name="GetStudent" type="submit" id="GetStudent" value="Go"></p>
							<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
							  <tr>
								<td width="30%" bgcolor="#F4F4F4"><div align="center" class="style21">Admn No</div></td>
								<td width="70%" bgcolor="#F4F4F4"><div align="center" class="style21">Name</div></td>
							  </tr>
<?PHP
								$counter_stud = 0;
								$query2 = "select * from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_stud = $rstart;
								}else{
									$counter_stud = $rstart-1;
								}
								$counter = 0;
								
								if(isset($_POST['OptSearch']))
								{
									if($_POST['OptSearch'] == "All"){
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}elseif($_POST['OptSearch'] == "Class"){
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}elseif($_POST['OptSearch'] == "Name"){
										$Search_Key = $_POST['StuName'];
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
									}else{
										$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}
								}else{
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter_stud = $counter_stud+1;
										$counter = $counter+1;
										$AdmID = $row["ID"];
										$AdmissionNo = $row["AdmissionNo"];
										$Stu_Full_Name = $row["Stu_Full_Name"];
?>
										  <tr bgcolor="#666666">
											<td>
							 <div align="center"><a href="mas_setup.php?subpg=Student Leave Application&adm_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>"><font color="#FFFFFF"><?PHP echo $AdmissionNo; ?></font></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Student Leave Application&adm_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>"><font color="#FFFFFF"><?PHP echo $Stu_Full_Name; ?></font></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
                        	</table>
							<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="mas_setup.php?subpg=Student Leave Application&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;</a>| &nbsp;<a href="mas_setup.php?subpg=Student Leave Application&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  		<p>&nbsp;</p>
							</TD>
					 	 <TD width="68%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  	<TABLE width="101%">
							<TBODY>
							<TR>
							  <TD width="23%"  align="left">Leave From    :</TD>
							  <TD width="30%"  align="left" valign="top">
							  <select name="fr_Dy" style="width:40px;">
								      <option value="" selected="selected">Dy</option>
								      
<?PHP
										$Cur_Dy = date('d');
										for($i=1; $i<=31; $i++){
											if($fr_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $Cur_Dy){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								    </select>
								    <select name="fr_Mth" style="width:40px;">
								       <option value="" selected="selected">Mth</option>
<?PHP
											$Cur_Mth = date('m');
											for($i=1; $i<=12; $i++){
												if($i == $fr_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}elseif($i == $Cur_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}else{
													echo "<option value=$i>$i</option>";
												}
											}
?>
					                </select>
								    <select name="fr_Yr"  style="width:60px;">
								      <option value="" selected="selected">Yr</option>
 <?PHP
 										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($fr_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $CurYear){
													echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
								    
									</label></TD>
							  <TD width="7%"  align="left">To</TD>
							  <TD width="40%"  align="left" valign="top"><label>
							  <select name="to_Dy" style="width:40px;">
								  <option value="" selected="selected">Day</option>
								  
<?PHP
									$Cur_Dy = date('d');
										for($i=1; $i<=31; $i++){
											if($to_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $Cur_Dy){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								</select>
								<select name="to_Mth" style="width:40px;">
								       <option value="" selected="selected">Month</option>
<?PHP
										$Cur_Mth = date('m');
										for($i=1; $i<=12; $i++){
											if($i == $to_Mth){
												echo "<option value=$i selected='selected'>$i</option>";
											}elseif($i == $Cur_Mth){
												echo "<option value=$i selected='selected'>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
					                </select>
								    <select name="to_Yr" style="width:60px;">
								      <option value="" selected="selected">Year</option>
 <?PHP
 										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($to_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $CurYear){
													echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
								    <label></label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Leave Type    :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
								<select name="OptLeave">
								<option value="0" selected="selected">&nbsp;</option>
<?PHP							
								$query = "select ID,LeaveName from tbleavemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$LeaveID = $row["ID"];
										$LeaveName = $row["LeaveName"];
										
										if($OptLeave =="$LeaveID"){
?>
											<option value="<?PHP echo $LeaveID; ?>" selected="selected"><?PHP echo $LeaveName; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $LeaveID; ?>"><?PHP echo $LeaveName; ?></option>
<?PHP
										}
									}
								}
?>
							      </select> 
							  </label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Leave Description    :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
							  <textarea name="Description" cols="45" rows="3"><?PHP echo $Description; ?></textarea>
							  </label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Student Name    :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
							  <input name="StudentName" type="text" size="45" value="<?PHP echo $StudentName; ?>">
							  </label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Student Class   :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
							  <input name="stuClass" type="text" size="35" value="<?PHP echo $stuClass; ?>">
							  </label></TD>
							</TR>
							<TR>
								<TD colspan="4"><p>
								<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="131"><div align="center"><strong> NAME</strong></div></td>
									<td width="92"><div align="center"><strong>LEAVE TYPE </strong></div></td>
									<td width="97"><div align="center"><strong>CLASS </strong></div></td>
									<td width="83"><div align="center"><strong>FROM</strong></div></td>
									<td width="63"><div align="center"><strong>TO</strong></div></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbleaveapplication where AppType = 'Student' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Leave_Fr";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$LeaveAppID = $row["ID"];
										$LeaveID = $row["LeaveID"];
										$AdmNo = $row["AdmNo"];
										$Leave_Fr = $row["Leave_Fr"];
										$Leave_To = $row["Leave_To"];
										
										//GET STUDENT DETAILS
										$query2 = "select Stu_Full_Name, Stu_Class from tbstudentmaster where AdmissionNo = '$AdmNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result2 = mysql_query($query2,$conn);
										$dbarray2 = mysql_fetch_array($result2);
										$Stu_Full_Name  = $dbarray2['Stu_Full_Name'];
										$Stu_Class  = $dbarray2['Stu_Class'];
										$Stu_Class = GetClassName($Stu_Class);
										
										//GET LEAVE TYPE
										$query2 = "select LeaveName from tbleavemaster where ID = '$LeaveID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result2 = mysql_query($query2,$conn);
										$dbarray2 = mysql_fetch_array($result2);
										$LeaveName  = $dbarray2['LeaveName'];
										
										$frDate= Long_date($Leave_Fr);
										$toDate= Long_date($Leave_To);
									
?>
										  <tr>
											<td><div align="center"><a href="mas_setup.php?subpg=Student Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $Stu_Full_Name; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Student Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $LeaveName; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Student Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $Stu_Class; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Student Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $frDate; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Student Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $toDate; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  </table>
								
								
								</p></TD>
							</TR>
						</TBODY>
						</TABLE>
						</TD>
					</TR>
					<TR>
					 <TD colspan="2">
					 <div align="center">
						 <input type="hidden" name="StudAdmNo" value="<?PHP echo $adm_No; ?>">
						  <input type="hidden" name="AppID" value="<?PHP echo $lev_aid; ?>">
						 <input name="SubmitLeave" type="submit" id="SubmitLeave" value="Create New">
						  <input name="SubmitLeave" type="submit" id="SubmitLeave" value="Update">
						  <input name="SubmitLeave" type="submit" id="SubmitLeave" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						 <input type="reset" name="Reset" value="Reset">
					</div>					 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Staff Leave Application") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="mas_setup.php?subpg=Staff Leave Application">
				<TABLE width="94%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="32%" valign="top"  align="left">
					  		<strong>Search Criteria</strong>
							<p>
<?PHP
							if($eOptSearch =="All")
							{
								echo "<input type='radio' name='OpteSearch' value='All' checked='checked'/>All Student";
							}else{
								echo "<input type='radio' name='OpteSearch' value='All'/>All Staff";
							}
?>
							</p>
							<p>
<?PHP
							if($OpteSearch =="Dept")
							{
								echo "<input type='radio' name='OpteSearch' value='Dept' checked='checked'/>Class";
							}else{
								echo "<input type='radio' name='OpteSearch' value='Dept'/>Department";
							}
?>
							<select name="OptDept">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,DeptName from tbdepartments where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by DeptName";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
									$DeptID = $row["ID"];
									$DeptName = $row["DeptName"];
									if($OptDept ==$DeptID){
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
					  		</p>
							<p>
<?PHP
							if($OpteSearch =="Name")
							{
								echo "<input type='radio' name='OpteSearch' value='Name' checked='checked'/>Name";
							}else{
								echo "<input type='radio' name='OpteSearch' value='Name'/>Name";
							}
?>					  		   
							<input type="text" name="EmpName" size="10" value="<?PHP echo $EmpName; ?>">
							<input name="GetStaff" type="submit" id="GetStaff" value="Go"></p>
							<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
							  <tr>
								<td width="30%" bgcolor="#F4F4F4"><div align="center" class="style21">ID</div></td>
								<td width="70%" bgcolor="#F4F4F4"><div align="center" class="style21">Staff Name</div></td>
							  </tr>
<?PHP
								$counter_stud = 0;
								$query2 = "select * from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_stud = $rstart;
								}else{
									$counter_stud = $rstart-1;
								}
								$counter = 0;
								
								if(isset($_POST['OpteSearch']))
								{
									if($_POST['OpteSearch'] == "All"){
										$query3 = "select ID,EmpName from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}elseif($_POST['OpteSearch'] == "Dept"){
										$query3 = "select ID,EmpName from tbemployeemasters where EmpDept = '$OptDept' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}elseif($_POST['OpteSearch'] == "Name"){
										$Search_Key = $_POST['EmpName'];
										$query3 = "select ID,EmpName from tbemployeemasters where INSTR(EmpName,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName";
									}else{
										$query3 = "select ID,EmpName from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
									}
								}else{
									$query3 = "select ID,EmpName from tbemployeemasters where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter_emp = $counter_stud+1;
										$counter = $counter+1;
										$SelEmpID = $row["ID"];
										$EmpName = $row["EmpName"];
?>
										  <tr bgcolor="#666666">
											<td>
							 <div align="center"><a href="mas_setup.php?subpg=Staff Leave Application&emp_ID=<?PHP echo $SelEmpID; ?>&Optsrc=<?PHP echo $_POST['OpteSearch']; ?>&dept=<?PHP echo $OptDept; ?>&name=<?PHP echo $Search_Key; ?>"><font color="#FFFFFF"><?PHP echo $counter; ?></font></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Staff Leave Application&emp_ID=<?PHP echo $SelEmpID; ?>&Optsrc=<?PHP echo $_POST['OpteSearch']; ?>&dept=<?PHP echo $OptDept; ?>&name=<?PHP echo $Search_Key; ?>"><font color="#FFFFFF"><?PHP echo $EmpName; ?></font></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
                        	</table>
							<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="mas_setup.php?subpg=Staff Leave Application&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;</a>| &nbsp;<a href="mas_setup.php?subpg=Staff Leave Application&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  		<p>&nbsp;</p>
							</TD>
					 	 <TD width="68%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  	<TABLE width="101%">
							<TBODY>
							<TR>
							  <TD width="23%"  align="left">Leave From    :</TD>
							  <TD width="30%"  align="left" valign="top">
							  <select name="fr_Dy" style="width:40px;">
								      <option value="" selected="selected">Dy</option>
								      
<?PHP
										$Cur_Dy = date('d');
										for($i=1; $i<=31; $i++){
											if($fr_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $Cur_Dy){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								    </select>
								    <select name="fr_Mth" style="width:40px;">
								       <option value="" selected="selected">Mth</option>
<?PHP
											$Cur_Mth = date('m');
											for($i=1; $i<=12; $i++){
												if($i == $fr_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}elseif($i == $Cur_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}else{
													echo "<option value=$i>$i</option>";
												}
											}
?>
					                </select>
								    <select name="fr_Yr"  style="width:60px;">
								      <option value="" selected="selected">Yr</option>
 <?PHP
 										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($fr_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $CurYear){
													echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
								    
									</label></TD>
							  <TD width="7%"  align="left">To</TD>
							  <TD width="40%"  align="left" valign="top"><label>
							  <select name="to_Dy" style="width:40px;">
								  <option value="" selected="selected">Day</option>
								  
<?PHP
									$Cur_Dy = date('d');
										for($i=1; $i<=31; $i++){
											if($to_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $Cur_Dy){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								</select>
								<select name="to_Mth" style="width:40px;">
								       <option value="" selected="selected">Month</option>
<?PHP
										$Cur_Mth = date('m');
										for($i=1; $i<=12; $i++){
											if($i == $to_Mth){
												echo "<option value=$i selected='selected'>$i</option>";
											}elseif($i == $Cur_Mth){
												echo "<option value=$i selected='selected'>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
					                </select>
								    <select name="to_Yr" style="width:60px;">
								      <option value="" selected="selected">Year</option>
 <?PHP
 										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($to_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $CurYear){
													echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
								    <label></label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Leave Type    :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
								<select name="OptLeave">
								<option value="0" selected="selected">&nbsp;</option>
<?PHP							
								$query = "select ID,LeaveName from tbleavemaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$LeaveID = $row["ID"];
										$LeaveName = $row["LeaveName"];
										
										if($OptLeave =="$LeaveID"){
?>
											<option value="<?PHP echo $LeaveID; ?>" selected="selected"><?PHP echo $LeaveName; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $LeaveID; ?>"><?PHP echo $LeaveName; ?></option>
<?PHP
										}
									}
								}
?>
							      </select> 
							  </label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Leave Description    :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
							  <textarea name="Description" cols="45" rows="3"><?PHP echo $Description; ?></textarea>
							  </label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Staff Name    :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
							  <input name="Staffname" type="text" size="45" value="<?PHP echo $Staffname; ?>">
							  </label></TD>
							</TR>
							<TR>
							  <TD width="23%"  align="left">Staff Department   :</TD>
							  <TD colspan="3"  align="left" valign="top"><label>
							  <input name="staffdept" type="text" size="35" value="<?PHP echo $staffdept; ?>">
							  </label></TD>
							</TR>
							<TR>
								<TD colspan="4"><p>
								<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
								  <tr bgcolor="#ECE9D8">
								    <td width="131"><div align="center"><strong>NAME</strong></div></td>
									<td width="92"><div align="center"><strong>LEAVE TYPE </strong></div></td>
									<td width="97"><div align="center"><strong>DEPT. </strong></div></td>
									<td width="83"><div align="center"><strong>FROM</strong></div></td>
									<td width="63"><div align="center"><strong>TO</strong></div></td>
								  </tr>
<?PHP
								$counter = 0;
								$query = "select * from tbleaveapplication where AppType = 'Staff' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Leave_Fr";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$LeaveAppID = $row["ID"];
										$LeaveID = $row["LeaveID"];
										$empID = $row["EmpID"];
										$Leave_Fr = $row["Leave_Fr"];
										$Leave_To = $row["Leave_To"];
										
										//GET STAFF DETAILS
										$query2 = "select ID, EmpName,EmpDept from tbemployeemasters where ID = '$empID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result2 = mysql_query($query2,$conn);

										$dbarray2 = mysql_fetch_array($result2);
										$StID  = $dbarray2['ID'];
										$EmpName  = $dbarray2['EmpName'];
										$EmpDept  = $dbarray2['EmpDept'];
										
										//GET LEAVE TYPE
										$query2 = "select DeptName from tbdepartments where ID = '$EmpDept' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
										$result2 = mysql_query($query2,$conn);
										$dbarray2 = mysql_fetch_array($result2);
										$DeptName  = $dbarray2['DeptName'];
										
										$frDate= Long_date($Leave_Fr);
										$toDate= Long_date($Leave_To);
									
?>
										  <tr>
											<td><div align="center"><a href="mas_setup.php?subpg=Staff Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $EmpName; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Staff Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $LeaveName; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Staff Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $DeptName; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Staff Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $frDate; ?></a></div></td>
											<td><div align="center"><a href="mas_setup.php?subpg=Staff Leave Application&lev_aid=<?PHP echo $LeaveAppID; ?>"><?PHP echo $toDate; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							  </table>
								
								
								</p></TD>
							</TR>
						</TBODY>
						</TABLE>
						</TD>
					</TR>
					<TR>
					 <TD colspan="2">
					 <div align="center">
						 <input type="hidden" name="StaffID" value="<?PHP echo $emp_ID; ?>">
						  <input type="hidden" name="AppID" value="<?PHP echo $lev_aid; ?>">
						 <input name="SubmitLeave" type="submit" id="SubmitLeave" value="Create New">
						  <input name="SubmitLeave" type="submit" id="SubmitLeave" value="Update">
						  <input name="SubmitLeave" type="submit" id="SubmitLeave" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						 <input type="reset" name="Reset" value="Reset">
					</div>					 </TD>
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
			<TD align="center"> Copyright © <?PHP echo date_default_timezone_set('Y'); ?> SkoolNet Manager. All right reserved.</TD>
		  </TR>
		</TABLE>	  
	  </TD>
	</TR>
</TBODY>
</TABLE> 	
</BODY></HTML>
