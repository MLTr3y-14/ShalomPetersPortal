<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	include 'sms/sms_processor';
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
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	if(isset($_GET['subpg']))
	{
		$SubPage = $_GET['subpg'];
	}
	$Page = "Hostel Management";
	$audit=update_Monitory('Login','Administrator',$Page);
	$PageHasError = 0;
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['Housemaster']))
	{
		$PageHasError = 0;
		$HouseID = $_POST['SelHouseID'];
		$HouseName = $_POST['HouseName'];
		
		if(!$_POST['HouseName']){
			$errormsg = "<font color = red size = 1>House Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Housemaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbhouse where HouseName = '$HouseName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The house name you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbhouse(HouseName) Values ('$HouseName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
					
					$HouseName = "";
				}
			}elseif ($_POST['Housemaster'] =="Update"){
				$q = "update tbhouse set HouseName = '$HouseName' where ID = '$HouseID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$HouseName = "";
				$HouseID = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['hou_id']))
	{
		$HouseID = $_GET['hou_id'];
		$query = "select * from tbhouse where ID='$HouseID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$HouseName  = $dbarray['HouseName'];
	}
	if(isset($_POST['Housemaster_delete']))
	{
		$Total = $_POST['TotalHouse'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkhouID'.$i]))
			{
				$chkhouID = $_POST['chkhouID'.$i];
				$q = "Delete From tbhouse where ID = '$chkhouID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['OptHouse']))
	{
		$RoomID = $_POST['SelRoomID'];
		$OptHouse = $_POST['OptHouse'];
		$RoomName = $_POST['RoomName'];
	}
	if(isset($_POST['roommaster']))
	{
		$PageHasError = 0;
		$RoomID = $_POST['SelRoomID'];
		$OptHouse = $_POST['OptHouse'];
		$RoomName = $_POST['RoomName'];
		
		if(!$_POST['OptHouse']){
			$errormsg = "<font color = red size = 1>Select House.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['RoomName']){
			$errormsg = "<font color = red size = 1>Room Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['roommaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbhostelroom where HouseID = '$OptHouse' and RoomName ='$RoomName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The room you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbhostelroom(HouseID,RoomName) Values ('$OptHouse','$RoomName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$RoomName = "";
				}
			}elseif ($_POST['roommaster'] =="Update"){
				$q = "update tbhostelroom set HouseID = '$OptHouse',RoomName = '$RoomName' where ID = '$RoomID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$RoomName = "";
			}
		}
	}
	if(isset($_GET['room_id']))
	{
		$RoomID = $_GET['room_id'];
		$_POST['OptHouse'] = $_GET['houid'];
		$query = "select * from tbhostelroom where ID='$RoomID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptHouse  = $dbarray['HouseID'];
		$RoomName  = $dbarray['RoomName'];
	}
	if(isset($_POST['roommaster_delete']))
	{
		$Total = $_POST['Totalrooms'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkrmID'.$i]))
			{
				$chkrmID = $_POST['chkrmID'.$i];
				$q = "Delete From tbhostelroom where ID = '$chkrmID'";
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
	
	if(isset($_GET['adm_No']))
	{
		$adm_No = $_GET['adm_No'];
		$query = "select * from tbstudentmaster where AdmissionNo='$adm_No'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$AdmnNo = $_GET['adm_No'];
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Class  = strtoupper($dbarray['Stu_Class']);
		$ClssName = GetClassName($Stu_Class);
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);
		
		$DOB  = long_date($dbarray['Stu_DOB']);
		$Email  = strtoupper($dbarray['Stu_Email']);
		$Address  = strtoupper($dbarray['Stu_Address']);

		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatherName  = $dbarray['Gr_Name_Mr'];
		$Contact  = $dbarray['Gr_Ph'];
		$EmergencyNo  = $dbarray['EmergNO'];
		$emergency  = $dbarray['EmergName'];

		
		$query = "select * from tbstudentroom where AdmNo='$adm_No' And Term = '$Activeterm'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$RoomID  = $dbarray['RoomID'];
		$HostNo  = $dbarray['HostelNo'];
		
		$query = "select * from tbhostelroom where ID='$RoomID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$HouseID  = $dbarray['HouseID'];
		$StudentRoom  = $dbarray['RoomName'];
		
		$query = "select * from tbhouse where ID='$HouseID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StudentHouse  = $dbarray['HouseName'];
		
		$_POST['OptSearch'] = $_GET['Optsrc'];
		$OptSearch = $_GET['Optsrc'];
		$OptClass = $_GET['clss'];
		$StuName = $_GET['name'];
		$_POST['StuName'] = $_GET['name'];
		
	}
	if(isset($_POST['SubmitAssign']))
	{
		$PageHasError = 0;
		$TotalSel = $_POST['TotalSel'];
		$OptRoom = $_POST['OptRoom'];
		if ($_POST['SubmitAssign'] !="Delete"){
			if(!$_POST['OptRoom']){
				$errormsg = "<font color = red size = 1>Room Type is empty.</font>";
				$PageHasError = 1;
			}
		}
		
		if ($PageHasError == 0)
		{
			for($i=1;$i<=$TotalSel;$i++){
				if(isset( $_POST['chkStud'.$i]))
				{
					$chkStud = $_POST['chkStud'.$i];
					$HostelNo = $_POST['HostelNo'.$i];
					if($HostelNo ==""){
						$errormsg = "<font color = red size = 1>Hostel No is empty.</font>";
						$PageHasError = 1;
					}
					if ($PageHasError == 0)
					{
						if ($_POST['SubmitAssign'] =="Create New"){
							$query = "select ID from tbstudentroom where AdmNo = '$chkStud' And Term = '$Activeterm'";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) 
							{
								$q = "update tbstudentroom set RoomID = '$OptRoom',HostelNo = '$HostelNo' where AdmNo = '$chkStud' And Term = '$Activeterm'";
								$result = mysql_query($q,$conn);
								$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
								
								$RoomID = "";
								$chkStud = "";
							}else {
								$q = "Insert into tbstudentroom(RoomID,AdmNo,HostelNo,Term) Values ('$OptRoom','$chkStud','$HostelNo','$Activeterm')";
								$result = mysql_query($q,$conn);
								$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
								
								$RoomID = "";
								$chkStud = "";
							}
						}elseif ($_POST['SubmitAssign'] =="Update"){
							$AssID = $_POST['AssID'.$i];
							$q = "update tbstudentroom set RoomID = '$OptRoom',AdmNo = '$chkStud',HostelNo = '$HostelNo', Term = '$Activeterm' where ID='$AssID'";
							$result = mysql_query($q,$conn);
							$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
							
							$RoomID = "";
							$chkStud = "";
						}elseif ($_POST['SubmitAssign'] =="Delete"){
							$AssID = $_POST['AssID'.$i];
							$q = "Delete From tbstudentroom where ID = '$AssID'";
							$result = mysql_query($q,$conn);
							$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
							
							$RoomID = "";
							$chkStud = "";
						}
					}
				}
			}
		}
		$OptSearch = $_POST['OptSearch'];
		$OptClass = $_POST['OptClass'];
		$StuName = $_POST['StuName'];
	}
	if(isset($_POST['OpenStudent']) or isset($_POST['ChkAllStud']))
	{	
		$OptHouse = $_POST['OptHouse'];
		$OptRooms = $_POST['OptRooms'];
		$rollDate = $_POST['roll_Yr']."-".$_POST['roo_Mth']."-".$_POST['roll_Dy'];
		$roll_Yr = $_POST['roll_Yr'];
		$roo_Mth = $_POST['roo_Mth'];
		$roll_Dy = $_POST['roll_Dy'];
		
		if(!rollDate){
			$errormsg = "<font color = red size = 1>Roll call date is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['OptHouse']){
			$errormsg = "<font color = red size = 1>Select House.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptRooms']){
			$errormsg = "<font color = red size = 1>Select Room</font>";
			$PageHasError = 1;
		}
		if(isset($_POST['ChkAllStud']))
		{
			$chkStudent = "checked='checked'";
		}else{
			$chkStudent = "";
		}
	}
	if(isset($_POST['SubmitSearch']))
	{
		$OptHouse = $_POST['OptHouse'];
		$OptRooms = $_POST['OptRooms'];
		$rollDate = $_POST['roll_Yr']."-".$_POST['roo_Mth']."-".$_POST['roll_Dy'];
		$roll_Yr = $_POST['roll_Yr'];
		$roo_Mth = $_POST['roo_Mth'];
		$roll_Dy = $_POST['roll_Dy'];
		
		if(!rollDate){
			$errormsg = "<font color = red size = 1>Roll call date is empty.</font>";
			$PageHasError = 1;
		}
		
		$OptType = $_POST['OptType'];
		$HostelNum = $_POST['HostelNum'];
	}
	if(isset($_POST['SubmitRollCall']))
	{	
		$OptHouse = $_POST['OptHouse'];
		$OptRooms = $_POST['OptRooms'];
		$rollDate = $_POST['roll_Yr']."-".$_POST['roo_Mth']."-".$_POST['roll_Dy'];
		$roll_Yr = $_POST['roll_Yr'];
		$roo_Mth = $_POST['roo_Mth'];
		$roll_Dy = $_POST['roll_Dy'];
		if(!rollDate){
			$errormsg = "<font color = red size = 1>Roll call date is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['OptRooms']){
			$errormsg = "<font color = red size = 1>Select Room.</font>";
			$PageHasError = 1;
		}
		$TotalStudent = $_POST['TotalStudent'];
		for($i=1;$i<=$TotalStudent;$i++){
			if(isset($_POST['chkHostelID'.$i]))
			{
				$chkHostelID = $_POST['chkHostelID'.$i];
				$AdmNos = $_POST['Adm'.$i];
				if ($_POST['SubmitRollCall'] =="P"){
					$AttStatus = 'P';
				}elseif ($_POST['SubmitRollCall'] =="A"){
					$AttStatus = 'A';
				}elseif ($_POST['SubmitRollCall'] =="L"){
					$AttStatus = 'L';
				}
				$query = "select Status from tbrollcall where HostelNo = '$chkHostelID' and Term = '$Activeterm' and Date = '$rollDate'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$q = "update tbrollcall set Status = '$AttStatus' where HostelNo = '$chkHostelID' and Term = '$Activeterm' and Date = '$rollDate'";
					$result = mysql_query($q,$conn);
				}else {
					$q = "Insert into tbrollcall(AdmNo,HostelNo,RoomID,Date,Status,Term) Values ('$AdmNos','$chkHostelID','$OptRooms','$rollDate','$AttStatus','$Activeterm')";
					$result = mysql_query($q,$conn);
				}
			}
		}
	}
	if(isset($_POST['NotifyParent']))
	{	
		$OptRooms = $_POST['OptRooms'];
		$rollDate = $_POST['roll_Yr']."-".$_POST['roo_Mth']."-".$_POST['roll_Dy'];
		$roll_Yr = $_POST['roll_Yr'];
		$roo_Mth = $_POST['roo_Mth'];
		$roll_Dy = $_POST['roll_Dy'];
		if(!rollDate){
			$errormsg = "<font color = red size = 1>Roll call date is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['OptRooms']){
			$errormsg = "<font color = red size = 1>Select Room.</font>";
			$PageHasError = 1;
		}
		$CountSMS = 0;
		$TotalStudent = $_POST['TotalStudent'];
		for($i=1;$i<=$TotalStudent;$i++){
			if(isset($_POST['chkHostelID'.$i]))
			{
				$chkHostelID = $_POST['chkHostelID'.$i];
				$StuAdmNo = $_POST['Adm'.$i];
				
				$query = "select * from tbrollcall where AdmNo = '$StuAdmNo' and HostelNo = '$chkHostelID' and Term = '$Activeterm' and Date = '$rollDate'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);		
				$Status  = $dbarray['Status'];
				
				$query = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo='$StuAdmNo'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$RegNo  = $dbarray['Stu_Regist_No'];
				$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

				$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$Contact  = $dbarray['Gr_Ph'];
				
				$isSend_Status="False";
				$isSend_Status = sendRollCall($StuAdmNo,$StudentName,$chkHostelID,$Status,$rollDate,$Contact);
				if($isSend_Status == "False"){
					$CountSMS = $CountSMS;
				}elseif($isSend_Status == "True"){
					$CountSMS = $CountSMS +1;
				}
			}
		}
		$errormsg = "<font color = blue size = 1>".$CountSMS." SMS messages sent Successfully.</font>";
	}
	if(isset($_POST['OpenHostelStudent']))
	{
		$Optexeat = $_POST['Optexeat'];
		$OptStuRoom = $_POST['OptStuRoom'];
		$HostelerNo = $_POST['HostelerNo'];
	}
	if(isset($_GET['admis_No']))
	{
		$admn_ID = $_GET['admis_No'];
		$query = "select * from tbstudentmaster where AdmissionNo='$admn_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$AdmnNo = $_GET['admis_No'];
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Class  = strtoupper($dbarray['Stu_Class']);
		$ClssName = GetClassName($Stu_Class);
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatherName  = $dbarray['Gr_Name_Mr'];
		$Contact  = $dbarray['Gr_Ph'];

		
		$query = "select * from tbstudentroom where AdmNo='$admn_ID' And Term = '$Activeterm'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$SelRmID  = $dbarray['RoomID'];
		$HostNo  = $dbarray['HostelNo'];
		
		$query = "select * from tbhostelroom where ID='$SelRmID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$HouseID  = $dbarray['HouseID'];
		$StudentRoom  = $dbarray['RoomName'];
		
		$query = "select * from tbhouse where ID='$HouseID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StudentHouse  = $dbarray['HouseName'];
		
		$_POST['Optexeat'] = $_GET['Optsrc'];
		$Optexeat = $_GET['Optsrc'];
		$OptStuRoom = $_GET['rmno'];
		$HostelerNo = $_GET['hotNo'];
		$_POST['HostelerNo'] = $_GET['hotNo'];
		
	}
	if(isset($_GET['ext_id']))
	{
		$ext_id = $_GET['ext_id'];
		$admn_ID = $_GET['admis2_No'];
		$query = "select * from tbstudentmaster where AdmissionNo='$admn_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$AdmnNo = $_GET['admis2_No'];
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Class  = strtoupper($dbarray['Stu_Class']);
		$ClssName = GetClassName($Stu_Class);
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatherName  = $dbarray['Gr_Name_Mr'];
		$Contact  = $dbarray['Gr_Ph'];

		
		$query = "select * from tbstudentroom where AdmNo='$admn_ID' And Term = '$Activeterm'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$SelRmID  = $dbarray['RoomID'];
		$HostNo  = $dbarray['HostelNo'];
		
		$query = "select * from tbhostelroom where ID='$SelRmID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$HouseID  = $dbarray['HouseID'];
		$StudentRoom  = $dbarray['RoomName'];
		
		$query = "select * from tbhouse where ID='$HouseID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StudentHouse  = $dbarray['HouseName'];
		
		$query = "select * from tbexeathostel where ID='$ext_id'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$ExtID  = $dbarray['ID'];
		$ReqDate  = $dbarray['ReqDate'];
		$ReqDate = User_date($ReqDate);
		$ReqTime  = $dbarray['ReqTime'];
		$arrReqTime=explode ('-', $ReqTime);
		$req_ss = $arrReqTime[2];
		$req_mm = $arrReqTime[1];
		$req_hr = $arrReqTime[0];
		
		$ExeatDate  = $dbarray['ExtDate'];
		$ExeatDate = User_date($ExeatDate);
		$ExtTime  = $dbarray['ExtTime'];
		$arrExtTime=explode ('-', $ExtTime);
		$ext_ss = $arrExtTime[2];
		$ext_mm = $arrExtTime[1];
		$ext_hr = $arrExtTime[0];
		
		$ReturnDate  = $dbarray['RetDate'];
		$ReturnDate = User_date($ReturnDate);
		$RetTime  = $dbarray['RetTime'];
		$arrRetTime=explode ('-', $RetTime);
		$ret_ss = $arrRetTime[2];
		$ret_mm = $arrRetTime[1];
		$ret_hr = $arrRetTime[0];
		
		
		$OptStatus  = $dbarray['Status'];
		$Rmarks  = $dbarray['Remarks'];
		
		$_POST['Optexeat'] = $_GET['Optsrc'];
		$Optexeat = $_GET['Optsrc'];
		$OptStuRoom = $_GET['rmno'];
		$HostelerNo = $_GET['hotNo'];
		$_POST['HostelerNo'] = $_GET['hotNo'];
		
	}
	if(isset($_POST['Submitexeat']))
	{	
		$AdmnNo = $_POST['StudAdmNo'];
		$admn_ID = $_POST['StudAdmNo'];
		$SelHostNo = $_POST['SelHostNo'];
		if(!$_POST['StudAdmNo']){
			$errormsg = "<font color = red size = 1>Select Student.</font>";
			$PageHasError = 1;
		}
		if(!$SelHostNo){
			$errormsg = "<font color = red size = 1>Select Student.</font>";
			$PageHasError = 1;
		}
		
		$OptStuRoom = $_POST['SelRoomNo'];
		$ExtID = $_POST['ExtID'];
		
		$ReqDate = $_POST['ReqDate'];
		$ReqDate = DB_date($ReqDate);
		if($ReqDate=="--" or $ReqDate==""){
			$errormsg = "<font color = red size = 1>Request Date is empty.</font>";
			$PageHasError = 1;
		}
		$ReqTime = $_POST['req_hr']."-".$_POST['req_mm']."-".$_POST['req_ss'];
		$req_hr = $_POST['req_hr'];
		$req_mm = $_POST['req_mm'];
		$req_ss = $_POST['req_ss'];
		
		$ExeatDate = $_POST['ExeatDate'];
		if($ExeatDate=="--" or $ExeatDate==""){
			$errormsg = "<font color = red size = 1>Exeat Date is empty.</font>";
			$PageHasError = 1;
		}
		$ExeatDate = DB_date($ExeatDate);
		$ExeatTime = $_POST['ext_hr']."-".$_POST['ext_mm']."-".$_POST['ext_ss'];
		$ext_hr = $_POST['ext_hr'];
		$ext_mm = $_POST['ext_mm'];
		$ext_ss = $_POST['ext_ss'];
		
		$ReturnDate = $_POST['ReturnDate'];
		if($ReturnDate=="--" or $ReturnDate==""){
			$errormsg = "<font color = red size = 1>Return Date is empty.</font>";
			$PageHasError = 1;
		}
		$ReturnDate = DB_date($ReturnDate);
		$ReturnTime = $_POST['ret_hr']."-".$_POST['ret_mm']."-".$_POST['ret_ss'];
		$ret_hr = $_POST['ret_hr'];
		$ret_mm = $_POST['ret_mm'];
		$ret_ss = $_POST['ret_ss'];
		
		$OptStatus = $_POST['OptStatus'];
		$Rmarks = $_POST['Rmarks'];
		
		if(!$_POST['ReqDate']){
			$errormsg = "<font color = red size = 1>Request Date is empry.</font>";
			$PageHasError = 1;
		}
		if(!$ReqTime){
			$errormsg = "<font color = red size = 1>Request Time is empry.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['ExeatDate']){
			$errormsg = "<font color = red size = 1>Exeat Date is empry.</font>";
			$PageHasError = 1;
		}
		if(!$ExeatTime){
			$errormsg = "<font color = red size = 1>Exeat Time is empry.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['ReturnDate']){
			$errormsg = "<font color = red size = 1>Return Date is empry.</font>";
			$PageHasError = 1;
		}
		if(!$ReturnTime){
			$errormsg = "<font color = red size = 1>Return Time is empry.</font>";
			$PageHasError = 1;
		}
		if($OptStuRoom == ""){
			$errormsg = "<font color = red size = 1>Room is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['OptStatus']){
			$errormsg = "<font color = red size = 1>Select Status.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['Submitexeat'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbexeathostel where AdmNo='$AdmnNo' and HostelNo='$SelHostNo' and ReqDate='$ReqDate' and ExtDate ='$ExeatDate' and Ret ='$ReturnDate'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The exeat details you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbexeathostel(AdmNo,HostelNo,RoomID,ReqDate,ReqTime,ExtDate,ExtTime,RetDate,RetTime,Status,Remarks,Term) Values ('$AdmnNo','$SelHostNo','$OptStuRoom','$ReqDate','$ReqTime','$ExeatDate','$ExeatTime','$ReturnDate','$ReturnTime','$OptStatus','$Rmarks','$Activeterm')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					
					$ReqDate = "";
					$req_hr = "";
					$req_mm = "";
					$req_ss = "";
					$ExeatDate = "";
					$ext_hr = "";
					$ext_mm = "";
					$ext_ss = "";
					$ReturnDate = "";
					$ret_hr = "";
					$ret_mm = "";
					$ret_ss = "";
					$OptStatus = "";
					$Rmarks = "";
				}
			}elseif ($_POST['Submitexeat'] =="Update"){
				$q = "update tbexeathostel set AdmNo = '$AdmnNo',HostelNo = '$SelHostNo',RoomID = '$OptStuRoom',ReqDate = '$ReqDate',ReqTime = '$ReqTime',ExtDate = '$ExeatDate',ExtTime = '$ExeatTime',RetDate = '$ReturnDate',RetTime = '$ReturnTime',Status = '$OptStatus',Remarks = '$Rmarks'  where ID = '$ExtID'";
				
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$ReqDate = "";
				$req_hr = "";
				$req_mm = "";
				$req_ss = "";
				$ExeatDate = "";
				$ext_hr = "";
				$ext_mm = "";
				$ext_ss = "";
				$ReturnDate = "";
				$ret_hr = "";
				$ret_mm = "";
				$ret_ss = "";
				$OptStatus = "";
				$Rmarks = "";
			}
		}
		$query = "select * from tbstudentmaster where AdmissionNo='$AdmnNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Class  = strtoupper($dbarray['Stu_Class']);
		$ClssName = GetClassName($Stu_Class);
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatherName  = $dbarray['Gr_Name_Mr'];
		$Contact  = $dbarray['Gr_Ph'];
		
		$query = "select * from tbstudentroom where AdmNo='$AdmnNo' And Term = '$Activeterm'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$SelRmID  = $dbarray['RoomID'];
		$HostNo  = $dbarray['HostelNo'];
		
		$query = "select * from tbhostelroom where ID='$RoomID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$HouseID  = $dbarray['HouseID'];
		$StudentRoom  = $dbarray['RoomName'];
		
		$query = "select * from tbhouse where ID='$HouseID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StudentHouse  = $dbarray['HouseName'];
	}
	if(isset($_POST['DelSubmitexeat']))
	{
		$Total = $_POST['Total'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkExtID'.$i]))
			{
				$chkExtID = $_POST['chkExtID'.$i];
				$q = "Delete From tbexeathostel where ID = '$chkExtID'";
				$result = mysql_query($q,$conn);
			}
		}
		$AdmnNo = $_POST['StudAdmNo'];
		$admn_ID = $_POST['StudAdmNo'];
		$SelHostNo = $_POST['SelHostNo'];
		
		$query = "select * from tbstudentmaster where AdmissionNo='$AdmnNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Class  = strtoupper($dbarray['Stu_Class']);
		$ClssName = GetClassName($Stu_Class);
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatherName  = $dbarray['Gr_Name_Mr'];
		$Contact  = $dbarray['Gr_Ph'];
		
		$query = "select * from tbstudentroom where AdmNo='$AdmnNo' And Term = '$Activeterm'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$SelRmID  = $dbarray['RoomID'];
		$HostNo  = $dbarray['HostelNo'];
		
		$query = "select * from tbhostelroom where ID='$RoomID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$HouseID  = $dbarray['HouseID'];
		$StudentRoom  = $dbarray['RoomName'];
		
		$query = "select * from tbhouse where ID='$HouseID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StudentHouse  = $dbarray['HouseName'];
	}
	if(isset($_POST['SubmitNotify']))
	{
		$Total = $_POST['Total'];
		$CountSMS=0;
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkExtID'.$i]))
			{
				$chkExtID = $_POST['chkExtID'.$i];
				
				$query = "select * from tbexeathostel where ID='$chkExtID'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$ExtID  = $dbarray['ID'];
				if($ExtID !=""){
					$StuAdmNo  = $dbarray['AdmNo'];
					$HostNo  = $dbarray['HostelNo'];
					$ExeatDate  = User_date($dbarray['ExtDate']);
					$ReturnDate  = User_date($dbarray['RetDate']);
				
					$query = "select Stu_Regist_No,Stu_Full from tbstudentmaster where AdmissionNo='$StuAdmNo'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$RegNo  = strtoupper($dbarray['Stu_Regist_No']);
					$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

					$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo'";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$Contact  = $dbarray['Gr_Ph'];
					
					$isSend_Status="False";
					$isSend_Status = sendExeat($StuAdmNo,$StudentName,$HostNo,$ExeatDate,$ReturnDate,$Contact);
					if($isSend_Status == "False"){
						$CountSMS = $CountSMS;
					}elseif($isSend_Status == "True"){
						$CountSMS = $CountSMS +1;
					}
				}
			}
		}
		$errormsg = "<font color = blue size = 1>".$CountSMS." SMS messages sent Successfully.</font>";
		
		$AdmnNo = $_POST['StudAdmNo'];
		$admn_ID = $_POST['StudAdmNo'];
		$SelHostNo = $_POST['SelHostNo'];
		
		$query = "select * from tbstudentmaster where AdmissionNo='$AdmnNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RegNo  = $dbarray['Stu_Regist_No'];
		$Stu_Class  = strtoupper($dbarray['Stu_Class']);
		$ClssName = GetClassName($Stu_Class);
		$StudentName  = strtoupper($dbarray['Stu_Full_Name']);

		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatherName  = $dbarray['Gr_Name_Mr'];
		$Contact  = $dbarray['Gr_Ph'];
		
		$query = "select * from tbstudentroom where AdmNo='$AdmnNo' And Term = '$Activeterm'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$SelRmID  = $dbarray['RoomID'];
		$HostNo  = $dbarray['HostelNo'];
		
		$query = "select * from tbhostelroom where ID='$RoomID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$HouseID  = $dbarray['HouseID'];
		$StudentRoom  = $dbarray['RoomName'];
		
		$query = "select * from tbhouse where ID='$HouseID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StudentHouse  = $dbarray['HouseName'];
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
.style24 {color: #FFFFFF}
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

</style>
</HEAD>
<BODY style="TEXT-ALIGN: center" background=Images/news-background.jpg>
<?PHP include 'datecalander.php'; ?>
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
<TABLE width="101%" bgcolor="#f4f4f4">
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
					  <TD height="55" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "House Setup") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="hostel.php?subpg=House Setup">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>HOUSE NAME</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter = 0;
								$query = "select * from tbhouse order by HouseName ";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No House Found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelHouID = $row["ID"];
										$HouName = $row["HouseName"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkhouID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelHouID; ?>">
									           </div></td>
											<td><div align="left"><a href="hostel.php?subpg=House Setup&hou_id=<?PHP echo $SelHouID; ?>"><?PHP echo $HouName; ?></a></div></td>
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
								<td width="104">Code :</td>
								<td width="276"><input name="Code" type="text" size="5" value="<?PHP echo $HouseID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>House Name :</td>
								<td><input name="HouseName" type="text" size="45" value="<?PHP echo $HouseName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalHouse" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelHouseID" value="<?PHP echo $HouseID; ?>">
						     <input name="Housemaster" type="submit" id="Housemaster" value="Create New">
						     <input name="Housemaster_delete" type="submit" id="Housemaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Housemaster" type="submit" id="Housemaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Room Setup") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="hostel.php?subpg=Room Setup">
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
					  <TD width="12%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="19%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="19%" valign="top"  align="left">Select House  </TD>
					  <TD width="50%" valign="top"  align="left">
					  <select name="OptHouse" onChange="javascript:setTimeout('__doPostBack(\'OptHouse\',\'\')', 0)">
                        <option value="" selected="selected">Select</option>
<?PHP
						$counter = 0;
						$query = "select ID,HouseName from tbhouse order by HouseName";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$HouID = $row["ID"];
								$HouseName = $row["HouseName"];
								
								if($OptHouse =="$HouID"){
?>
                        			<option value="<?PHP echo $HouID; ?>" selected="selected"><?PHP echo $HouseName; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $HouID; ?>"><?PHP echo $HouseName; ?></option>
<?PHP
								}
							}
						}
?>
                      </select></TD>
					</TR>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><p align="center">Room Name 
					    <input name="RoomName" type="text" size="35" value="<?PHP echo $RoomName; ?>"></p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="SelRoomID" value="<?PHP echo $RoomID; ?>">
							 <input name="roommaster" type="submit" id="roommaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="roommaster" type="submit" id="roommaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4"><p>&nbsp;</p><hr>
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="602" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="54" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="75" bgcolor="#F4F4F4"><div align="center" class="style21">Sr.No</div></td>
                            <td width="443" bgcolor="#F4F4F4"><div align="left"><strong>Room Name</strong></div></td>
                          </tr>
<?PHP
							$counter_room = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tbhostelroom where INSTR(RoomName,'$Search_Key') order by RoomName ";
							}elseif($_POST['OptHouse'] != ""){
								$query2 = "select * from tbhostelroom where HouseID = '$OptHouse' order by RoomName";
							}else{
								$query2 = "select * from tbhostelroom order by RoomName";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_room = $rstart;
							}else{
								$counter_room = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbhostelroom where INSTR(RoomName,'$Search_Key') order by RoomName LIMIT $rstart,$rend";
							}elseif($_POST['OptHouse'] != ""){
								$query3 = "select * from tbhostelroom where HouseID = '$OptHouse' order by RoomName LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tbhostelroom order by RoomName LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_room = $counter_room+1;
									$counter = $counter+1;
									$RmID = $row["ID"];
									$HouID = $row["HouseID"];
									$RoomName = $row["RoomName"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkrmID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $RmID; ?>"></div></td>
										<td><div align="center"><a href="hostel.php?subpg=Room Setup&room_id=<?PHP echo $RmID; ?>&houid=<?PHP echo $HouID; ?>"><?PHP echo $counter_room; ?></a></div></td>
										<td><div align="left"><a href="hostel.php?subpg=Room Setup&room_id=<?PHP echo $RmID; ?>&houid=<?PHP echo $HouID; ?>"><?PHP echo $RoomName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="hostel.php?subpg=Room Setup&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="hostel.php?subpg=Room Setup&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="hostel.php?subpg=Room Setup&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalrooms" value="<?PHP echo $counter; ?>">
						     <input name="roommaster_delete" type="submit" id="roommaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Assign Student To Rooms") {
?>
			<?PHP echo $errormsg; ?>
			<form name="form1" method="post" action="hostel.php?subpg=Assign Student To Rooms">
			<TABLE width="94%" style="WIDTH: 100%">
				<TBODY>
				<TR>
				  <TD width="42%" valign="top"  align="left">
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
						$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
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
							<td width="17%" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
							<td width="52%" bgcolor="#F4F4F4"><div align="center" class="style21">Name</div></td>
							<td width="31%" bgcolor="#F4F4F4"><div align="center" class="style21">Hostel No.</div></td>
						  </tr>
<?PHP
							$counter_stud = 0;
							$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller'";
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
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller' And Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}elseif($_POST['OptSearch'] == "Class"){
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Type='Hosteller' And Stu_Class = '$OptClass' And Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}elseif($_POST['OptSearch'] == "Name"){
									$Search_Key = $_POST['StuName'];
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Type='Hosteller' And INSTR(Stu_Full_Name,'$Search_Key') And Stu_Sec ='$Activeterm' order by Stu_Full_Name";
								}else{
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Type='Hosteller' And Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}
							}else{
								$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster  where Stu_Type='Hosteller' And Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_stud = $counter_stud+1;
									$counter = $counter+1;
									$RecID = $row["ID"];
									$AdmissionNo = $row["AdmissionNo"];
									$Stu_Full_Name = $row["Stu_Full_Name"];
									
									$query = "select * from tbstudentroom where AdmNo='$AdmissionNo' And Term='$Activeterm'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$AssID  = $dbarray['ID'];
									$HostelID  = $dbarray['HostelNo'];
									if($AssID !=""){
										$bg="#FFBF80";
										$fbg="#003399";
									}else{
										$bg="#666666";
										$fbg="#FFFFFF";
									}
?>
									  <tr bgcolor="<?PHP echo $bg; ?>">
										<td><div align="center">
											<input type="hidden" name="AssID<?PHP echo $counter; ?>" value="<?PHP echo $AssID; ?>">
											<input name="chkStud<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $AdmissionNo; ?>"></div></td>
										<td><div align="center"><a href="hostel.php?subpg=Assign Student To Rooms&adm_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['OptSearch']; ?>&clss=<?PHP echo $OptClass; ?>&name=<?PHP echo $Search_Key; ?>"><font color="<?PHP echo $fbg; ?>"><?PHP echo $Stu_Full_Name; ?></font></a></div></td>
										<td>
										  <input name="HostelNo<?PHP echo $counter; ?>" type="text" value="<?PHP echo $HostelID; ?>" size="10" maxlength="15">
									    </td>
									  </tr>
<?PHP
								 }
							 }
?>
						</table>
						<p><img src="images/orange.jpg" width="19" height="17" align="left">&nbsp;&nbsp;&nbsp; List of Assigned Student</p>
						<p><img src="images/black.jpg" width="19" height="17" align="left">&nbsp;&nbsp;&nbsp; List of Unassigned Student</p>
						<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="hostel.php?subpg=Assign Student To Rooms&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;</a>| &nbsp;<a href="hostel.php?subpg=Assign Student To Rooms&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
						<p>
						&nbsp;&nbsp;&nbsp;&nbsp; Select Room
						<select name="OptRoom">
						<option value="" selected="selected">&nbsp;</option>
<?PHP
						$query = "select ID,RoomName from tbhostelroom order by RoomName";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows <= 0 ) {
							echo "";
						}
						else 
						{
							while ($row = mysql_fetch_array($result)) 
							{
								$RoomID = $row["ID"];
								$RoomName = $row["RoomName"];
								if($OptRoom ==$RoomID){
?>
									<option value="<?PHP echo $RoomID; ?>" selected="selected"><?PHP echo $RoomName; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $RoomID; ?>"><?PHP echo $RoomName; ?></option>
<?PHP
								}
							}
						}
?>
						</select>
						</p>
						<p>&nbsp;</p>
						<div align="center">
							 <input type="hidden" name="StudAdmNo" value="<?PHP echo $admn_ID; ?>">
							  <input type="hidden" name="TotalSel" value="<?PHP echo $counter; ?>">
							 <input name="SubmitAssign" type="submit" id="SubmitAssign" value="Create New">
							  <input name="SubmitAssign" type="submit" id="SubmitAssign" value="Update">
							  <input name="SubmitAssign" type="submit" id="SubmitAssign" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
							 <input type="reset" name="Reset" value="Reset">
						</div>
						</TD>
					 <TD width="58%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					<TABLE width="99%">
						<TBODY>
						<TR>
						  <TD width="23%"  align="left">Hostel No     :</TD>
						  <TD width="77%"  align="left" valign="top"><label>
						    <input name="HostNo" type="text" value="<?PHP echo $HostNo; ?>" size="15" maxlength="15" disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Admn No     :</TD>
						  <TD width="77%"  align="left" valign="top"><label>
						    <input name="AdmnNo" type="text" size="35" value="<?PHP echo $AdmnNo; ?>" disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Student Name     :</TD>
						  <TD width="77%"  align="left" valign="top"><label>
						    <input name="StudentName" type="text" size="35" value="<?PHP echo $StudentName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Father Name     :</TD>
						  <TD width="77%"  align="left" valign="top"><label>
						    <input name="FatherName" type="text" size="35" value="<?PHP echo $FatherName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Student Class     :</TD>
						  <TD width="77%"  align="left" valign="top"><label>
						    <input name="ClassName" type="text" size="35" value="<?PHP echo $ClssName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Date of Birth     :</TD>
						  <TD width="77%"  align="left" valign="top"><label>
						    <input name="DOB" type="text" size="35" value="<?PHP echo $DOB; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Address    :</TD>
						  <TD  align="left" valign="top"><label>
						  <textarea name="Description" cols="45" rows="3"  disabled="disabled" style="background:#FFFFFF"><?PHP echo $fatAddress; ?></textarea>
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Contact No   :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="Contact" type="text" size="35" value="<?PHP echo $Contact; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Email Address   :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="Email" type="text" size="35" value="<?PHP echo $Email; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Emergency Name   :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="emergency" type="text" size="25" value="<?PHP echo $emergency; ?>"  disabled="disabled" style="background:#FFFFFF">
						  Phone.
						  <input name="EmergencyNo" type="text" size="15" value="<?PHP echo $EmergencyNo; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Student House    :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="StudentHouse" type="text" size="35" value="<?PHP echo $StudentHouse; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="23%"  align="left">Student Room    :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="StudentRoom" type="text" size="35" value="<?PHP echo $StudentRoom; ?>" disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
							<TD colspan="2"><p>
							  </p></TD>
						</TR>
					</TBODY>
					</TABLE>
					</TD>
				</TR>
			</TBODY>
			</TABLE>
			</form>
<?PHP
		}elseif ($SubPage == "Roll Call") {
?>
				<p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="hostel.php?subpg=Roll Call">
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
					  <TD width="44%" valign="top"  align="left">
					  		<table width="301" cellpadding="4">
								<tr>
									<td width="43"><div align="right">House :</div></td>
									<td width="234">
									<select name="OptHouse" onChange="javascript:setTimeout('__doPostBack(\'OptHouse\',\'\')', 0)">
                       				 <option value="" selected="selected">Select</option>
<?PHP
										$counter = 0;
										$query = "select ID,HouseName from tbhouse order by HouseName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows > 0 ) {
											while ($row = mysql_fetch_array($result)) 
											{
												$counter = $counter+1;
												$HouID = $row["ID"];
												$HouseName = $row["HouseName"];
								
												if($OptHouse =="$HouID"){
?>
                        							<option value="<?PHP echo $HouID; ?>" selected="selected"><?PHP echo $HouseName; ?></option>
<?PHP
												}else{
?>
                        							<option value="<?PHP echo $HouID; ?>"><?PHP echo $HouseName; ?></option>
<?PHP
												}
											}
										 }
?>
									  </select>
									</td>
								</tr>
								<tr>
									<td width="43"><div align="right">Room :</div></td>
									<td width="234">
									<select name="OptRooms">
										<option value="" selected="selected">Select</option>
<?PHP
										$counter = 0;
										$query = "select ID,RoomName from tbhostelroom where HouseID = '$OptHouse' order by RoomName";
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
												$RoomID = $row["ID"];
												$RoomName = $row["RoomName"];
												
												if($OptRooms =="$RoomID"){
?>
													<option value="<?PHP echo $RoomID; ?>" selected="selected"><?PHP echo $RoomName; ?></option>
<?PHP
												}else{
?>
													<option value="<?PHP echo $RoomID; ?>"><?PHP echo $RoomName; ?></option>
<?PHP
												}
											}
										}
?>
									  </select>
									</td>
								</tr>
								<tr>
									<td width="43"><div align="right">Date:</div></td>
									<td width="234">
									<select name="roll_Dy">
								      <option value="" selected="selected">Day</option>
								      
<?PHP
										for($i=1; $i<=31; $i++){
											if($roll_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								    </select>
								    <select name="roo_Mth">
								       <option value="" selected="selected">Month</option>
<?PHP
											for($i=1; $i<=12; $i++){
												if($i == $roo_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}else{
													echo "<option value=$i>$i</option>";
												}
											}
?>
					                </select>
								    <select name="roll_Yr">
								      <option value="" selected="selected">Year</option>
 <?PHP
 										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($roll_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
								    <label>
								    <input type="submit" name="OpenStudent" value="Go">
								    </label></td>
								</tr>
							</table>
					        <div align="center"> 
							  <input type="submit" name="SubmitRollCall" value="P" style="background:#F2F2F2"> 
							  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
							  <input type="submit" name="SubmitRollCall" value="L" style=" background:#FFCC33">
							  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							  <input type="submit" name="SubmitRollCall" value="A" style=" background:#FF9C97">
							  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
							  Present&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Leave&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Absent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							</div></TD>
					  <TD width="56%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">Search Hostel Student
					 	 <TABLE width="90%" align="center" style="WIDTH: 98%" cellpadding="5" cellspacing="4">
							<TBODY>
								<TR>
								  <TD width="29%"  align="left">
<?PHP
									if($OptType =='HostelNo')
									{
										echo "<input name='OptType' type='radio' value='HostelNo' checked='checked'>";
									}else{
										echo "<input name='OptType' type='radio' value='HostelNo'>";
									}
?>
								    Hostel No.: </TD>
								  <TD width="71%" valign="top"  align="left">
								    <input name="HostelNum" type="text"value="<?PHP echo $HostelNum; ?>" size="15"/>
								    <input name="SubmitSearch" type="submit" id="SubmitSearch" value="Search">
								  </TD>
								</TR>
								</TBODY>
						 </TABLE>
					  </TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">		
					    <p>&nbsp;</p>			  
						<label>
					  		<input type="checkbox" name="ChkAllStud" value="checkbox" onClick="javascript:setTimeout('__doPostBack(\'ChkAllStud\',\'\')', 0)">
					  		</label>
					  		Select All
							<table width="124%" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="31" bgcolor="#666666"><div align="center" class="style24"><strong>Tick</strong></div></td>
								<td width="161" bgcolor="#666666"><div align="center" class="style24"><strong>Hostel No </strong></div></td>
								<td width="196" bgcolor="#666666"><div align="center" class="style24"><strong> Student Name </strong></div></td>
								<td width="161" bgcolor="#666666"><div align="center" class="style24"><strong>Room No </strong></div></td>
								<td width="122" bgcolor="#666666"><div align="center" class="style24"><strong> Roll Call Date </strong></div></td>
								<td width="63" bgcolor="#666666"><div align="center" class="style24"><strong> Status </strong></div></td>
							  </tr>
<?PHP
								$counter_Stud = 0;
								if($OptType =='HostelNo'){
									$query3 = "select AdmNo,HostelNo,RoomID from tbstudentroom where HostelNo = '$HostelNum' and Term = '$Activeterm'";
								}else{
									$query3 = "select AdmNo,HostelNo,RoomID from tbstudentroom where RoomID ='$OptRooms' and Term = '$Activeterm'";
								}
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter_Stud = $counter_Stud+1;
										$counter = $counter+1;
										$AdmissionNo = $row["AdmNo"];
										$HostelID = $row["HostelNo"];
										$RmID = $row["RoomID"];
										
										
										$query = "select Status from tbrollcall where AdmNo='$AdmissionNo' and HostelNo = '$HostelID' and RoomID = '$OptRooms' and Date = '$rollDate' and Term = '$Activeterm'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$Status  = $dbarray['Status'];
										
										$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo='$AdmissionNo' and Stu_Sec = '$Activeterm'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$Stu_Full_Name  = $dbarray['Stu_Full_Name'];
										
										$query = "select * from tbhostelroom where ID='$RmID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);	
										$StudentRoom  = $dbarray['RoomName'];
		
										$bg = "";
										if($Status == ""){
											$Status = "-";
										}elseif($Status =="P"){
											 $bg = "bgcolor='#F2F2F2'";
										}elseif($Status =="L"){
											 $bg = "bgcolor='#FFCC33'";
										}elseif($Status =="A"){
											 $bg = "bgcolor='#FF9C97'";
										}
?>								  
										  <tr <?PHP echo $bg; ?>>
											<td>
											   <div align="center">
											   <input type="hidden" name="Adm<?PHP echo $counter_Stud; ?>" value="<?PHP echo $AdmissionNo; ?>">
												 <input name="chkHostelID<?PHP echo $counter_Stud; ?>" type="checkbox" value="<?PHP echo $HostelID; ?>" <?PHP echo $chkStudent; ?>></div></td>
											<td><div align="center"><?PHP echo $HostelID; ?></div></td>
											<td><div align="center"><?PHP echo $Stu_Full_Name; ?></div></td>
											<td><div align="center"><?PHP echo $StudentRoom; ?></div></td>
											<td><div align="center"><?PHP echo Long_date($rollDate); ?></div></td>
											<td><div align="center"><?PHP echo $Status; ?></div></td>
										  </tr>
<?PHP
									}
								}
?>
							</table>
							<div align="center">
							<input type="hidden" name="TotalStudent" value="<?PHP echo $counter_Stud; ?>">
							<input name="NotifyParent" type="submit" id="NotifyParent" value="Notify Parent">
							</div>
					    </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Student Exeat") {
?>
			<?PHP echo $errormsg; ?>
			<form name="form1" method="post" action="hostel.php?subpg=Student Exeat">
			<TABLE width="94%" style="WIDTH: 100%">
				<TBODY>
				<TR>
				  <TD width="40%" valign="top"  align="left">
						<strong>Search Criteria</strong>
						<p>
<?PHP
						if($Optexeat =="All")
						{
							echo "<input type='radio' name='Optexeat' value='All' checked='checked'/>All Student";
						}else{
							echo "<input type='radio' name='Optexeat' value='All'/>All Student";
						}
?>
						</p>
						<p>
<?PHP
						if($Optexeat =="Room")
						{
							echo "<input type='radio' name='Optexeat' value='Room' checked='checked'/>Room";
						}else{
							echo "<input type='radio' name='Optexeat' value='Room'/>Room";
						}
?>
						<select name="OptStuRoom">
						<option value="" selected="selected">&nbsp;</option>
<?PHP
						$query = "select ID,RoomName from tbhostelroom order by RoomName";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$RoomID = $row["ID"];
								$RoomName = $row["RoomName"];
								
								if($OptStuRoom ==$RoomID){
?>
									<option value="<?PHP echo $RoomID; ?>" selected="selected"><?PHP echo $RoomName; ?></option>
<?PHP
								}else{
?>
									<option value="<?PHP echo $RoomID; ?>"><?PHP echo $RoomName; ?></option>
<?PHP
								}
							}
						}
?>
						</select>
						</p>
						<p>
<?PHP
						if($Optexeat =="HostNo")
						{
							echo "<input type='radio' name='Optexeat' value='HostNo' checked='checked'/>Name";
						}else{
							echo "<input type='radio' name='Optexeat' value='HostNo'/>Hostel No.";
						}
?>					  		   
						<input type="text" name="HostelerNo" size="10" value="<?PHP echo $HostelerNo; ?>">
						<input name="OpenHostelStudent" type="submit" id="OpenHostelStudent" value="Go"></p>
						<table width="92%" border="0" align="center" cellpadding="3" cellspacing="3" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
						  <tr>
							<td width="17%" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
							<td width="52%" bgcolor="#F4F4F4"><div align="center" class="style21">Name</div></td>
							<td width="31%" bgcolor="#F4F4F4"><div align="center" class="style21">Hostel No.</div></td>
						  </tr>
<?PHP
							$counter_stud = 0;
							
							if(isset($_POST['Optexeat']))
							{
								if($_POST['Optexeat'] == "All"){
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller' and Stu_Sec ='$Activeterm'";
								}elseif($_POST['Optexeat'] == "Room"){
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where AdmissionNo IN (Select AdmNo from tbstudentroom where RoomID = '$OptStuRoom') and Stu_Type='Hosteller' and Stu_Sec ='$Activeterm'";
								}elseif($_POST['Optexeat'] == "HostNo"){
									$Search_Key = $_POST['HostelerNo'];
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where AdmissionNo IN (Select AdmNo from tbstudentroom where HostelNo = '$Search_Key') and Stu_Type='Hosteller' and Stu_Sec ='$Activeterm'";
								}else{
									$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller' and Stu_Sec ='$Activeterm'";
								}
							}else{
								$query2 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller' and Stu_Sec ='$Activeterm'";
							}
						
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_stud = $rstart;
							}else{
								$counter_stud = $rstart-1;
							}
							$counter = 0;
							
							if(isset($_POST['Optexeat']))
							{
								if($_POST['Optexeat'] == "All"){
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller' and Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}elseif($_POST['Optexeat'] == "Room"){
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where AdmissionNo IN (Select AdmNo from tbstudentroom where RoomID = '$OptStuRoom') and Stu_Type='Hosteller' and Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}elseif($_POST['Optexeat'] == "HostNo"){
									$Search_Key = $_POST['HostelerNo'];
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where AdmissionNo IN (Select AdmNo from tbstudentroom where HostelNo = '$Search_Key') and Stu_Type='Hosteller' and Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}else{
									$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller' and Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
								}
							}else{
								$query3 = "select ID,AdmissionNo,Stu_Full_Name from tbstudentmaster where Stu_Type='Hosteller' and Stu_Sec ='$Activeterm' LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_stud = $counter_stud+1;
									$counter = $counter+1;
									$RecID = $row["ID"];
									$AdmissionNo = $row["AdmissionNo"];
									$Stu_Full_Name = $row["Stu_Full_Name"];
									
									$query = "select * from tbstudentroom where AdmNo='$AdmissionNo' And Term='$Activeterm'";
									$result = mysql_query($query,$conn);
									$dbarray = mysql_fetch_array($result);
									$AssID  = $dbarray['ID'];
									$HostelID  = $dbarray['HostelNo'];
									$bg="#FFBF80";
									$fbg="#003399";
?>
									  <tr bgcolor="<?PHP echo $bg; ?>">
										<td><div align="center">
											<input type="hidden" name="AssID<?PHP echo $counter; ?>" value="<?PHP echo $AssID; ?>">
											<input name="chkStud<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $AdmissionNo; ?>"></div></td>
										<td><div align="center"><a href="hostel.php?subpg=Student Exeat&admis_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['Optexeat']; ?>&rmno=<?PHP echo $OptStuRoom; ?>&hotNo=<?PHP echo $Search_Key; ?>"><font color="<?PHP echo $fbg; ?>"><?PHP echo $Stu_Full_Name; ?></font></a></div></td>
										<td><div align="center"><a href="hostel.php?subpg=Student Exeat&admis_No=<?PHP echo $AdmissionNo; ?>&Optsrc=<?PHP echo $_POST['Optexeat']; ?>&rmno=<?PHP echo $OptStuRoom; ?>&hotNo=<?PHP echo $Search_Key; ?>"><font color="<?PHP echo $fbg; ?>"><?PHP echo $HostelID; ?></font></a></div>
										  
									    </td>
									  </tr>
<?PHP
								 }
							 }
?>
						</table>
						<p>&nbsp;</p>
						<p><?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;<a href="hostel.php?subpg=Student Exeat&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;</a>| &nbsp;<a href="hostel.php?subpg=Student Exeat&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
						<p>&nbsp;</p>
						
						</TD>
					 <TD width="60%"  align="left" valign="top" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					<TABLE width="99%">
						<TBODY>
						<TR>
						  <TD width="32%"  align="left">Hostel No     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="HostNo" type="text" value="<?PHP echo $HostNo; ?>" size="15" maxlength="15" disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Admn No     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="AdmnNo" type="text" size="35" value="<?PHP echo $AdmnNo; ?>" disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Student Name     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="StudentName" type="text" size="35" value="<?PHP echo $StudentName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Father Name     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="FatherName" type="text" size="35" value="<?PHP echo $FatherName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Student Class     :</TD>
						  <TD width="68%"  align="left" valign="top"><label>
						    <input name="ClassName" type="text" size="35" value="<?PHP echo $ClssName; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Contact No   :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="Contact" type="text" size="35" value="<?PHP echo $Contact; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Email Address   :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="Email" type="text" size="35" value="<?PHP echo $Email; ?>"  disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Room No.    :</TD>
						  <TD  align="left" valign="top"><label>
						  <input name="StudentRoom2" type="text" size="35" value="<?PHP echo $StudentRoom; ?>" disabled="disabled" style="background:#FFFFFF">
						  </label></TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Request Date     &amp; Time:</TD>
						  <TD  align="left" valign="top"><input class="fil_ariane_passif" onClick="ds_sh(this);" name="ReqDate" readonly="readonly" style="cursor: text" value="<?PHP echo $ReqDate; ?>"/>
						    <select name="req_hr" style="width:40px">
							  <option value="" selected="selected">H</option>
							  
<?PHP
								for($i=1; $i<=24; $i++){
									if($req_hr == $i){
										echo "<option value=$i selected=selected>$i</option>";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
							</select>:
							<select name="req_mm" style="width:40px">
							   <option value="" selected="selected">M</option>
<?PHP
									for($i=1; $i<=60; $i++){
										if($i == $req_mm){
											echo "<option value=$i selected='selected'>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
							</select>:
							<select name="req_ss" style="width:40px">
							  <option value="00" selected="selected">00</option>
							</select>
						  </TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Exeat Date     &amp; Time:</TD>
						  <TD  align="left" valign="top"><input class="fil_ariane_passif" onClick="ds_sh(this);" name="ExeatDate" readonly="readonly" style="cursor: text" value="<?PHP echo $ExeatDate; ?>"/>
						    <select name="ext_hr" style="width:40px">
							  <option value="" selected="selected">H</option>
							  
<?PHP
								for($i=1; $i<=24; $i++){
									if($ext_hr == $i){
										echo "<option value=$i selected=selected>$i</option>";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
							</select>:
							<select name="ext_mm" style="width:40px">
							   <option value="" selected="selected">M</option>
<?PHP
									for($i=1; $i<=60; $i++){
										if($i == $req_mm){
											echo "<option value=$i selected='selected'>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
							</select>:
							<select name="ext_ss" style="width:40px">
							  <option value="00" selected="selected">00</option>
							</select>
						  </TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Return Date     &amp; Time:</TD>
						  <TD  align="left" valign="top"><input class="fil_ariane_passif" onClick="ds_sh(this);" name="ReturnDate" readonly="readonly" style="cursor: text" value="<?PHP echo $ReturnDate; ?>"/>
						    <select name="ret_hr" style="width:40px">
							  <option value="" selected="selected">H</option>
							  
<?PHP
								for($i=1; $i<=24; $i++){
									if($ret_hr == $i){
										echo "<option value=$i selected=selected>$i</option>";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
							</select>:
							<select name="ret_mm" style="width:40px">
							   <option value="" selected="selected">M</option>
<?PHP
									for($i=1; $i<=60; $i++){
										if($i == $req_mm){
											echo "<option value=$i selected='selected'>$i</option>";
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
							</select>:
							<select name="ret_ss" style="width:40px">
							  <option value="00" selected="selected">00</option>
							</select>
						  </TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Status:</TD>
						  <TD  align="left" valign="top">
						  <select name="OptStatus">
							<option value="">&nbsp;</option>
<?PHP
							if($OptStatus == "Granted"){
?>
								<option value="<?PHP echo $OptStatus; ?>" selected="selected"><?PHP echo $OptStatus; ?></option>
								<option value="Pending">Pending</option>
								<option value="Refuse">Refuse</option>
<?PHP
							}elseif($OptStatus == "Pending"){
?>
								<option value="Granted">Granted</option>
								<option value="<?PHP echo $OptStatus; ?>" selected="selected"><?PHP echo $OptStatus; ?></option>
								<option value="Refuse">Refuse</option>
<?PHP
							}elseif($OptStatus == "Refuse"){
?>
								<option value="Granted">Granted</option>
								<option value="Pending">Pending</option>
								<option value="<?PHP echo $OptStatus; ?>" selected="selected"><?PHP echo $OptStatus; ?></option>
<?PHP
							}else{
?>
								<option value="Granted">Granted</option>
								<option value="Pending">Pending</option>
								<option value="Refuse">Refuse</option>
<?PHP
							}
?>
						  </select>
						  
						  </TD>
						</TR>
						<TR>
						  <TD width="32%"  align="left">Remarks:</TD>
						  <TD  align="left" valign="top"><label>
						    <textarea name="Rmarks" cols="45" rows="4"><?PHP echo $Rmarks; ?></textarea>
						  </label>						  </TD>
						</TR>
						<TR>
							<TD colspan="2"><div align="center">
							<input type="hidden" name="ExtID" value="<?PHP echo $ExtID; ?>">
							 <input type="hidden" name="StudAdmNo" value="<?PHP echo $admn_ID; ?>">
							  <input type="hidden" name="SelHostNo" value="<?PHP echo $HostNo; ?>">
							 <input type="hidden" name="SelRoomNo" value="<?PHP echo $SelRmID; ?>">
							 <input name="Submitexeat" type="submit" id="Submitexeat" value="Create New">
							  <input name="Submitexeat" type="submit" id="Submitexeat" value="Update">
							</div></TD>
						</TR>
						<TR>
							<TD colspan="2">
							<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="48" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="106" bgcolor="#F4F4F4"><div align="center" class="style21">Status</div></td>
                            <td width="244" bgcolor="#F4F4F4"><div align="left"><strong>Request Date</strong></div></td>
							<td width="244" bgcolor="#F4F4F4"><div align="left"><strong>Exeat Date</strong></div></td>
							<td width="244" bgcolor="#F4F4F4"><div align="left"><strong>Return Date</strong></div></td>
                          </tr>
<?PHP
							$counter_room = 0;
							$query2 = "select * from tbexeathostel where AdmNo = '$admn_ID' and Term = '$Activeterm'";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_room = $rstart;
							}else{
								$counter_room = $rstart-1;
							}
							$counter = 0;
							$query3 = "select * from tbexeathostel where AdmNo = '$admn_ID' and Term = '$Activeterm' LIMIT $rstart,$rend";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$ExetID = $row["ID"];
									$DbStatus = $row["Status"];
									$DbReqDate = $row["ReqDate"];
									$DbExtDate = $row["ExtDate"];
									$DbRetDate = $row["RetDate"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkExtID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $ExetID; ?>"></div></td>
										<td><div align="center"><a href="hostel.php?subpg=Student Exeat&ext_id=<?PHP echo $ExetID; ?>&admis2_No=<?PHP echo $admn_ID; ?>&Optsrc=<?PHP echo $_POST['Optexeat']; ?>&rmno=<?PHP echo $OptStuRoom; ?>&hotNo=<?PHP echo $Search_Key; ?>"><font color="<?PHP echo $fbg; ?>"><?PHP echo $DbStatus; ?></a></div></td>
										<td><div align="left"><a href="hostel.php?subpg=Student Exeat&ext_id=<?PHP echo $ExetID; ?>&admis2_No=<?PHP echo $admn_ID; ?>&Optsrc=<?PHP echo $_POST['Optexeat']; ?>&rmno=<?PHP echo $OptStuRoom; ?>&hotNo=<?PHP echo $Search_Key; ?>"><font color="<?PHP echo $fbg; ?>"><?PHP echo User_date($DbReqDate); ?></a></div></td>
										<td><div align="left"><a href="hostel.php?subpg=Student Exeat&ext_id=<?PHP echo $ExetID; ?>&admis2_No=<?PHP echo $admn_ID; ?>&Optsrc=<?PHP echo $_POST['Optexeat']; ?>&rmno=<?PHP echo $OptStuRoom; ?>&hotNo=<?PHP echo $Search_Key; ?>"><font color="<?PHP echo $fbg; ?>"><?PHP echo User_date($DbExtDate); ?></a></div></td>
										<td><div align="left"><a href="hostel.php?subpg=Student Exeat&ext_id=<?PHP echo $ExetID; ?>&admis2_No=<?PHP echo $admn_ID; ?>&Optsrc=<?PHP echo $_POST['Optexeat']; ?>&rmno=<?PHP echo $OptStuRoom; ?>&hotNo=<?PHP echo $Search_Key; ?>"><font color="<?PHP echo $fbg; ?>"><?PHP echo User_date($DbRetDate); ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="hostel.php?subpg=Student Exeat&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="hostel.php?subpg=Student Exeat&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="hostel.php?subpg=Student Exeat&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
							</div>
							</TD>
						</TR>
						<TR>
							<TD colspan="2"><div align="center">
							 <input type="hidden" name="Total" value="<?PHP echo $counter; ?>">
							 <input name="DelSubmitexeat" type="submit" id="DelSubmitexeat" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
							 <input type="submit" name="SubmitNotify" value="Notify Parent">
						</div></TD>
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
