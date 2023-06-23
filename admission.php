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
		$Adm_Dy = date('d');
		$Adm_Mth = date('m');
		$Adm_Yr = date('Y');
	}
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	$Page = "Admission";
	$audit=update_Monitory('Login','Administrator',$Page);
	//Get Total Student in School
	$numrows = 0;
	$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotStu_In_Sch = $row4['numrows'];
	$PageHasError = 0;
	if(isset($_GET['filename']))
	{
		$StuFilename = $_GET['filename'];
	}else{
		$StuFilename = "empty_r2_c2.jpg";
	}
	if(isset($_GET['afilename']))
	{
		$AccomFilename = $_GET['afilename'];
	}else{
		$AccomFilename = "empty_r2_c2.jpg";
	}
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	$RegNo = $_SESSION['regisNo'];
	$_SESSION['regisNo'] = "";
	$StudentName = $_SESSION['FirstName'];
	$_SESSION['FirstName'] = "";

if(isset($_POST['SubmitAdmn']))
	{
		//$AdmID = $_POST['SelAdmID'];
		//$RegNo = $_POST['SelRegNo'];
		
		
		//$RegFee = $_POST['SelRegFee'];
		
			
		
		$Adm_Dy = $_POST['Adm_Dy'];
		$Adm_Mth = $_POST['Adm_Mth'];
		$Adm_Yr = $_POST['Adm_Yr'];
		$AdmDate = $Adm_Yr."-".$Adm_Mth."-".$Adm_Dy;
		if(!is_numeric($Adm_Dy)){
			$errormsg = "<font color = red size = 1>Invalid Admission Date (Day).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($Adm_Mth)){
			$errormsg = "<font color = red size = 1>Invalid Admission Date (Month).</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($Adm_Yr)){
			$errormsg = "<font color = red size = 1>Invalid Admission Date (Year).</font>";
			$PageHasError = 1;
		}
		

		$RegNo = $_POST['RegNo'];
		//$RegNo = 2;
		$query = "select * from tbregistration where ID='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$num_rows = mysql_num_rows($result);
		if ($num_rows <= 0 ) 
		{
			$errormsg = "<font color = red size = 1>The student you are trying to admit has not been registered</font>";
			$PageHasError = 1;
		}
		
		
		$AdmNo = $_POST['AdmNo'];
		$AdmNo2 = $_POST['AdmNo2'];
		if($_POST['AdmNo'] == ""){
			$errormsg = "<font color = red size = 1>Admission No is empty.</font>";
			$PageHasError = 1;
		}
		if($_POST['AdmNo2'] == ""){
			$errormsg = "<font color = red size = 1>Admission No is empty.</font>";
			$PageHasError = 1;
		}
		if($_POST['StudentName'] == ""){
			$errormsg = "<font color = red size = 1>Student name is empty.</font>";
			$PageHasError = 1;
		}
		
		if($_POST['OptClass'] == ""){
			$errormsg = "<font color = red size = 1>Class name is empty.</font>";
			$PageHasError = 1;
		}
			
		
		
		
		//$errormsg = "<font color = red size = 1>Student registration no is empty.</font>";
			//$PageHasError = 1;
			
	    $OptClass = $_POST['OptClass'];	
		$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
		$StudentName = $_POST['StudentName'];
		$PlaceofBirth = $_POST['PlaceofBirth'];
		$NickName = $_POST['NickName'];
		$OptGender = $_POST['OptGender'];
		$DOB_Dy = $_POST['DOB_Dy'];
		$DOB_Mth = $_POST['DOB_Mth'];
		$DOB_Yr = $_POST['DOB_Yr'];
		$StuDOB = $DOB_Yr."-".$DOB_Mth."-".$DOB_Dy;
		$ReasonDOB = $_POST['ReasonDOB'];
		$LastSchool = $_POST['LastSchool'];
		$Religion = $_POST['Religion'];
		$SchoolLeaving = $_POST['SchoolLeaving'];
		$Age = $_POST['Age'];
		$Height = $_POST['Height'];
		$Weight = $_POST['Weight'];
		$SchoolLeaving_day = $_POST['SchoolLeaving_day'];
		$SchoolLeaving_mth = $_POST['SchoolLeaving_mth'];
		$SchoolLeaving_yr = $_POST['SchoolLeaving_yr'];
		$SLDate = $SchoolLeaving_yr."-".$SchoolLeaving_mth."-".$SchoolLeaving_day;
		$CertNo = $_POST['CertNo'];
		$BloodGroup = $_POST['BloodGroup'];
		$OptSection = $_POST['OptSection'];
		$StuFilename = $_POST['StuFilename'];
		$StuEmail = $_POST['StuEmail'];
		$Stutype = $_POST['Stutype'];
		if(!$_POST['GrMr']){
			$errormsg = "<font color = red size = 1>Please Enter Parent/Guardian Name</font>";
			$PageHasError = 1;
		}
		$GrMr = $_POST['GrMr'];
		$GrMrs = $_POST['GrMrs'];
		$GrAddress = $_POST['GrAddress'];
		$OptCity = $_POST['OptCity'];
		$State = $_POST['State'];
		$GrTel = $_POST['GrTel'];
		$EmergencyNo = $_POST['EmergencyNo'];
		$ContactPerson = $_POST['ContactPerson'];
		$PhyName = $_POST['PhyName'];
		$PhyTel = $_POST['PhyTel'];
		$PhyMobile = $_POST['PhyMobile'];
		$AccomName = $_POST['AccomName'];
		$AccomOcc = $_POST['AccomOcc'];
		$AccomFilename = $_POST['AccomFilename'];
		$BedWelter = $_POST['BedWelter'];
		$CommDiseases = $_POST['CommDiseases'];
		$OptTransMode = $_POST['OptTransMode'];
		$Remarks = $_POST['Remarks'];
		$Games = $_POST['Games'];
		$Curricular = $_POST['Curricular'];
		//$PicName = $_POST['PicName'];
		//$query = "select * from tbstudenttemppic";
				//$result = mysql_query($query,$conn);
				//$row = mysql_fetch_array($result);
							//$content = $row["Content"];
							//$EmpFilename = $row["PicName"];
							//$status = $row["Status"];
							//$picstatus = $row["PictureStatus"];
		
		
		
		
		if($PageHasError == 0)
		{
			$num_rows = 0;
			$query = "select ID from tbstudentmaster where Stu_Regist_No = '$RegNo' or AdmissionNo = '$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			$num_rows2 = 0;
			$query2 = "select ID from tbstudentmaster where Stu_Full_Name = '$StudentName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result2 = mysql_query($query2,$conn);
			$num_rows2 = mysql_num_rows($result2);
			if ($num_rows > 0 ) 
			{$errormsg = "<font color = red size = 1>The student registration/admission no you are trying to save already exist.</font>";
					$PageHasError = 1;
			}
			elseif($num_rows2 > 0 )
			{$errormsg = "<font color = red size = 1>The Admission information you are trying to save already exist.</font>";
					$PageHasError = 1;
			}
				/*if ($_POST['SubmitNext'] == "Next."){;
					$q = "update tbstudentmaster set Stu_Regist_No='$RegNo',Stu_Date_of_Admis='$AdmDate',Stu_Class='$OptClass',Stu_Sec='$OptSection',Stu_Full_Name='$StudentName',Stu_DOB='$StudDOB',Stu_Gender='$OptGender',Stu_Address='$GrAddress',Stu_City='$OptCity',Stu_State='$State', Stu_Phone='$Tel_No',Stu_Email='$StuEmail',Stu_Reg_Fee='$RegFee',Stu_Type='$Stutype',Stu_Photo='$StuFilename',AdmissionNo='$AdmissionNo',SchoolLeft='$LastSchool',LeavingDate='$SLDate',CertificateNo='$CertNo',OldStudent='$OldStudent',ReasonDOB='$ReasonDOB' where Stu_Regist_No = '$RegNo'";
					$result = mysql_query($q,$conn);	
					
					$q = "update tbstudentdetail set Stu_Regist_No='$RegNo',Stu_Wt='$Weight',Stu_Ht='$Height',Stu_Age='$Age',Gr_Name_Mr='$GrMr',Gr_Name_Ms='$GrMrs',Gr_City='$OptCity',Gr_State='$State',Gr_Ph='$GrTel',Cert_No='$CertNo', ScLv_Date='$SLDate',BloodGroup='$BloodGroup', Religion='$Religion',NickName='$NickName', PlaceofBirth='$PlaceofBirth', EmergNO='$Emergency',EmergName='$ContactPerson',Gr_Addr='$GrAddress' where Stu_Regist_No = '$RegNo'";
					$result = mysql_query($q,$conn);
					
					//GET OTHER PAGE FIELD RECORDS
					$query1 = "select * from tbstudentmaster where Stu_Regist_No='$RegNo'";
					$result1 = mysql_query($query1,$conn);
					$dbarray1 = mysql_fetch_array($result1);
					$OptTransMode  = $dbarray1['Mode_of_coming'];
					$Remarks  = $dbarray1['RemarksCon'];
					$Games  = $dbarray1['Games'];
		
					$query2 = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
					$result2 = mysql_query($query2,$conn);
					$dbarray2 = mysql_fetch_array($result2);
					$PhyName  = $dbarray2['Phy_Name'];
					$PhyTel  = $dbarray2['Phy_Ph'];
					$PhyMobile  = $dbarray2['Phy_MbPh'];
					$AccomName  = $dbarray2['Per_Name'];
					$AccomFilename  = $dbarray2['Per_Photo'];
					$AccomOcc  = $dbarray2['Per_Occp'];
					$Remarks  = $dbarray2['Remarks'];
					$OptTransMode  = $dbarray2['Tr_Mode'];
					$BedWelter  = $dbarray2['BedWetter'];
					$CommDiseases  = $dbarray2['CommunicableDisease'];
					$Curricular  = $dbarray2['ExtraCurricular'];
				
					$SubPage = "Other Details";
				}else{
					$errormsg = "<font color = red size = 1>The student you are trying to admit already exist.</font>";
					$PageHasError = 1;
				}
			}else {
				//Get Total Student in class
				$numrows = 0;
				$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster Where Stu_Class = '$OptClass'";
				$result4  = mysql_query($query4,$conn) or die('Error, query failed');
				$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
				$TotStu_In_Class = $row4['numrows'];
				
				// GET MAX Seating for class
				$SelAmount = "";
				$query2 = "select Seat_capacity from tbclasssection where ClassID='$OptClass' And ClassTerm ='$OptSection'";
				$result2 = mysql_query($query2,$conn);
				$dbarray2 = mysql_fetch_array($result2);
				$Seat_capacity  = $dbarray2['Seat_capacity'];
				
				if($TotStu_In_Class >= $Seat_capacity){
					$errormsg = "<font color = red size = 1>Exceeded ".GetClassName($OptClass)." seating capacity which is ".$Seat_capacity.".</font>";
					$PageHasError = 1;  
				}
				if ($PageHasError == 0)
				{*/
					else{   $q = "insert into tbstudentmaster (Stu_Regist_No,Stu_Date_of_Admis,Stu_Class,Stu_Sec,Stu_Full_Name,Stu_DOB,Stu_Gender,Stu_Address,Stu_City,Stu_State,Stu_Phone,Stu_Email,Stu_Type,Mode_of_coming,AdmissionNo,RemarksCon,SchoolLeft,LeavingDate,CertificateNo,ReasonDOB,Games,Session_ID,Term_ID) Values ('$RegNo','$AdmDate','$OptClass','$OptSection','$StudentName','$StuDOB','$OptGender','$GrAddress','$OptCity','$State','$GrTel','$StuEmail','$Stutype','$OptTransMode','$AdmissionNo','$Remarks','$LastSchool','$SLDate','$CertNo','$ReasonDOB','$Games','$Session_ID','$Term_ID')"; 
					$result = mysql_query($q,$conn);
					
					$query = "Insert into tbstudentdetail (Stu_Regist_No,Stu_Wt,Stu_Ht,Stu_Age,Gr_Name_Mr,Gr_Name_Ms,Gr_City,Gr_State,Gr_Ph,Phy_Name,Phy_Ph,Phy_MbPh,Per_Name,Per_Occp,Cert_No,ScLv_Date,Remarks,Tr_Mode,BloodGroup,Religion,BedWetter,NickName,CommunicableDisease,ExtraCurricular,PlaceofBirth,EmergNO,EmergName,Gr_Addr,Session_ID,Term_ID) Values	('$RegNo','$Weight','$Height','$Age','$GrMr','$GrMrs','$OptCity','$State','$GrTel','$PhyName','$PhyTel','$PhyMobile','$AccomName','$AccomOcc','$CertNo','$SLDate','$Remarks','$OptTransMode','$BloodGroup','$Religion','$BedWelter','$NickName','$CommDiseases','$Curricular','$PlaceofBirth','$EmergencyNo','$ContactPerson','$GrAddress','$Session_ID','$Term_ID')";
					$result = mysql_query($query,$conn);
		            
					$query2 = "update tbregistration set Status = '1' where ID='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($query2,$conn);
					
					$AdmissionNo = "";
		$StudentName = "";
		$PlaceofBirth = "";
		$NickName = "";
		$OptGender = "";
		$DOB_Dy = "";
		$DOB_Mth = "";
		$DOB_Yr = "";
		$StudDOB = "";
		$Tel_No = "";
		$ReasonDOB = "";
		$LastSchool = "";
		$Religion = "";
		$SchoolLeaving = "";
		$Age = "";
		$Height = "";
		$Weight = "";
		$SchoolLeaving_day = "";
		$SchoolLeaving_mth = "";
		$SchoolLeaving_yr = "";
		$SLDate = "";
		$CertNo = "";
		$BloodGroup = "";
		$OptSection = "";
		$StuFilename = "";
		$StuEmail = "";
		$Stutype = "";
		$GrMr = "";
		$GrMrs = "";
		$GrAddress = "";
		$OptCity = "";
		$State = "";
		$GrTel = "";
		$EmergencyNo = "";
		$ContactPerson = "";
		$PhyName= "";
		$PhyTel= "";
		$PhyMobile= "";
		$AccomName= "";
		$AccomOcc= "";
		$BedWelter= "";
		$CommDiseases= "";
		$OptTransMode= "";
		$Remarks= "";
		$Games= "";
		$Curricular= "";
				
				$errormsg = "<font color = blue size = 1>Operation was Successfully.</font>";
					
					//$SubPage = "Other Details";
				}
			}
		}
			
	
/*if(isset($_POST['SubmitAdmn']))
	{
		$RegNo = $_POST['SelRegNo'];
		$AdmissionNo = $_POST['SelAdmNo'];
		$PhyName = $_POST['PhyName'];
		$PhyTel = $_POST['PhyTel'];
		$PhyMobile = $_POST['PhyMobile'];
		$AccomName = $_POST['AccomName'];
		$AccomOcc = $_POST['AccomOcc'];
		$AccomFilename = $_POST['AccomFilename'];
		$BedWelter = $_POST['BedWelter'];
		$CommDiseases = $_POST['CommDiseases'];
		$OptTransMode = $_POST['OptTransMode'];
		$Remarks = $_POST['Remarks'];
		$Games = $_POST['Games'];
		$Curricular = $_POST['Curricular'];

		if ($PageHasError == 0)
		{
			$num_rows = 0;
			$query = "select ID from tbstudentmaster where Stu_Regist_No = '$RegNo'";
			$result = mysql_query($query,$conn);
			$num_rows = mysql_num_rows($result);
			if ($num_rows <= 0 ) 
			{
				$errormsg = "<font color = red size = 1>The admission record you are trying to save does not exist.</font>";
				$PageHasError = 1;
				$SubPage = "Admission";
				//$SubPage = "Other Details";
			}else {				
				$q = "update tbstudentmaster set Mode_of_coming = '$OptTransMode',RemarksCon = '$Remarks',Games = '$Games' where Stu_Regist_No = '$RegNo'";
				$result = mysql_query($q,$conn);
				
				$q = "update tbstudentdetail set Phy_Name = '$PhyName',Phy_Ph = '$PhyTel',Phy_MbPh = '$PhyMobile',Per_Name='$AccomName',Per_Photo='$AccomFilename',Per_Occp='$AccomOcc',Remarks='$Remarks',Tr_Mode='$OptTransMode',BedWetter='$BedWelter',CommunicableDisease='$CommDiseases',ExtraCurricular= '$Curricular' where Stu_Regist_No = '$RegNo'";
				$result = mysql_query($q,$conn);
				
				$q = "update tbregistration set Status='1' where ID = '$RegNo'";
				$result = mysql_query($q,$conn);
					
				$PhyName= "";
				$PhyTel= "";
				$PhyMobile= "";
				$AccomName= "";
				$AccomOcc= "";
				$BedWelter= "";
				$CommDiseases= "";
				$OptTransMode= "";
				$Remarks= "";
				$Games= "";
				$Curricular= "";
				
				$errormsg = "<font color = blue size = 1>Operation was Successfully.</font>";
				$SubPage = "Other Details";
			}
		}
	}*/
	 if(isset($_POST['EditAdmn']))
	{
		
		

		$RegNo = $_POST['RegNo'];
		$AdmNo = $_POST['AdmNo'];
		$AdmNo2 = $_POST['AdmNo2'];
		$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
		$StudentName = $_POST['StudentName'];
		$OptClass = $_POST['OptClass'];	
		$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
		$PlaceofBirth = $_POST['PlaceofBirth'];
		$NickName = $_POST['NickName'];
		$OptGender = $_POST['OptGender'];
		$DOB_Dy = $_POST['DOB_Dy'];
		$DOB_Mth = $_POST['DOB_Mth'];
		$DOB_Yr = $_POST['DOB_Yr'];
		$StuDOB = $DOB_Yr."-".$DOB_Mth."-".$DOB_Dy;
		$Tel_No = $_POST['Tel_No'];
		$ReasonDOB = $_POST['ReasonDOB'];
		$LastSchool = $_POST['LastSchool'];
		$Religion = $_POST['Religion'];
		$SchoolLeaving = $_POST['SchoolLeaving'];
		$Age = $_POST['Age'];
		$Height = $_POST['Height'];
		$Weight = $_POST['Weight'];
		$SchoolLeaving_day = $_POST['SchoolLeaving_day'];
		$SchoolLeaving_mth = $_POST['SchoolLeaving_mth'];
		$SchoolLeaving_yr = $_POST['SchoolLeaving_yr'];
		$SLDate = $SchoolLeaving_yr."-".$SchoolLeaving_mth."-".$SchoolLeaving_day;
		$CertNo = $_POST['CertNo'];
		$BloodGroup = $_POST['BloodGroup'];
		$OptSection = $_POST['OptSection'];
		$StuFilename = $_POST['StuFilename'];
		$StuEmail = $_POST['StuEmail'];
		$Stutype = $_POST['Stutype'];
		
		
		
		$GrMr = $_POST['GrMr'];
		if(isset($_POST['GrMr'])){
		$GrMr = $_POST['GrMr'];
		$q = "update tbstudentdetail set Gr_Name_Mr ='$GrMr' where Stu_Regist_No = '$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);
		}
		$GrMrs = $_POST['GrMrs'];
		if(isset($_POST['GrAddress'])){
		$GrAddress = $_POST['GrAddress'];
		$q = "update tbstudentdetail set Gr_Addr ='$GrAddress' where Stu_Regist_No = '$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);
		$errormsg = "<font color = blue size = 1>Operation was Successfully.</font>".$GrAddress.$RegNo;
		}
		$OptCity = $_POST['OptCity'];
		$State = $_POST['State'];
		$GrTel = $_POST['GrTel'];
		$EmergencyNo = $_POST['EmergencyNo'];
		$ContactPerson = $_POST['ContactPerson'];
		$PhyName = $_POST['PhyName'];
		$PhyTel = $_POST['PhyTel'];
		$PhyMobile = $_POST['PhyMobile'];
		$AccomName = $_POST['AccomName'];
		$AccomOcc = $_POST['AccomOcc'];
		$AccomFilename = $_POST['AccomFilename'];
		$BedWelter = $_POST['BedWelter'];
		$CommDiseases = $_POST['CommDiseases'];
		$OptTransMode = $_POST['OptTransMode'];
		$Remarks = $_POST['Remarks'];
		$Games = $_POST['Games'];
		$Curricular = $_POST['Curricular'];
		
	  $q = "update tbstudentmaster set Stu_DOB='$StuDOB',Stu_Gender='$OptGender',Stu_Address='$GrAddress',Stu_City='$OptCity',Stu_State='$State',Stu_Phone='$Tel_No',Stu_Email='$StuEmail',Mode_of_coming='$OptTransMode',RemarksCon='$Remarks2',SchoolLeft='$LastSchool',LeavingDate='$SLDate',CertificateNo='$CertNo',ReasonDOB = '$ReasonDOB',Games='$Games'  where Stu_Regist_No = '$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);
					
				
	  $q = "update tbstudentdetail set Stu_Wt='$Weight',Stu_Ht='$Height',Stu_Age='$Age',Gr_Name_Mr='$GrMr',Gr_Name_Ms='$GrMrs',Gr_City='$OptCity',Gr_State='$State',Gr_Ph='$GrTel',Phy_Name = '$PhyName',Phy_Ph = '$PhyTel',Phy_MbPh = '$PhyMobile',Per_Name='$AccomName',Per_Occp='$AccomOcc',Cert_No ='$CertNo',ScLv_Date='$SLDate',Remarks='$Remarks',Tr_Mode='$OptTransMode',BloodGroup ='$BloodGroup',Religion='$Religion',BedWetter='$BedWelter',NickName ='$NickName',CommunicableDisease='$CommDiseases',ExtraCurricular= '$Curricular',PlaceofBirth='$PlaceofBirth',EmergNO='$EmergencyNo',EmergName='$ContactPerson',Gr_Addr='$GrAddress' where Stu_Regist_No = '$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	$result = mysql_query($q,$conn);
	
	    //$RegNo = "";
		$AdmNo = "";
		$AdmNo2 = "";
        $AdmissionNo = "";
		$StudentName = "";
		$PlaceofBirth = "";
		$NickName = "";
		$OptGender = "";
		$DOB_Dy = "";
		$DOB_Mth = "";
		$DOB_Yr = "";
		$StudDOB = "";
		$Tel_No = "";
		$ReasonDOB = "";
		$LastSchool = "";
		$Religion = "";
		$SchoolLeaving = "";
		$Age = "";
		$Height = "";
		$Weight = "";
		$SchoolLeaving_day = "";
		$SchoolLeaving_mth = "";
		$SchoolLeaving_yr = "";
		$SLDate = "";
		$CertNo = "";
		$BloodGroup = "";
		$OptSection = "";
		$StuFilename = "";
		$StuEmail = "";
		$Stutype = "";
		$GrMr = "";
		$GrMrs = "";
		$GrAddress = "";
		$OptCity = "";
		$State = "";
		$GrTel = "";
		$EmergencyNo = "";
		$ContactPerson = "";
		$PhyName= "";
		$PhyTel= "";
		$PhyMobile= "";
		$AccomName= "";
		$AccomOcc= "";
		$BedWelter= "";
		$CommDiseases= "";
		$OptTransMode= "";
		$Remarks= "";
		$Games= "";
		$Curricular= "";
				
				//$errormsg = "<font color = blue size = 1>Operation was Successfully.</font>";
		         $errormsg = $errormsg.$RegNo;
	  }
	if(isset($_GET['adm_id']))
	{
		$AdmID = $_GET['adm_id'];
		$query = "select Stu_Regist_No,AdmissionNo from tbstudentmaster where ID='$AdmID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RegNo  = $dbarray['Stu_Regist_No'];
		$AdmissionNo = $dbarray['AdmissionNo'];
		$EditAdmissionInfo = true;
		$_POST['SubmitBack'] = "Back";
	}
	if(isset($_POST['SubmitBack']))
	{
		/*if(!isset($_GET['adm_id']))
		{
			$RegNo = $_POST['SelRegNo'];
			$AdmissionNo = $_POST['SelAdmNo'];
		}*/
		
		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$Weight  = $dbarray['Stu_Wt'];
		$Height  = $dbarray['Stu_Ht'];
		$Age  = $dbarray['Stu_Age'];
		$GrMr  = $dbarray['Gr_Name_Mr'];
		$GrMrs  = $dbarray['Gr_Name_Ms'];
		$OptCity  = $dbarray['Gr_City'];
		$State  = $dbarray['Gr_State'];
		$GrTel  = $dbarray['Gr_Ph'];
		$CertNo  = $dbarray['Cert_No'];
		$PhyName  = $dbarray['Phy_Name'];
		$PhyTel  = $dbarray['Phy_Ph'];
		$PhyMobile  = $dbarray['Phy_MbPh'];
		$AccomName  = $dbarray['Per_Name'];
		$AccomOcc  = $dbarray['Per_Occp'];
		$BedWelter  = $dbarray['BedWetter'];
		$CommDiseases  = $dbarray['CommunicableDisease'];
		$arrDate=explode ('-', $dbarray['ScLv_Date']);
		$SchoolLeaving_day = $arrDate[2];
		$SchoolLeaving_mth = $arrDate[1];
		$SchoolLeaving_yr = $arrDate[0];
		$BloodGroup  = $dbarray['BloodGroup'];
		$Religion  = $dbarray['Religion'];
		$NickName  = $dbarray['NickName'];
		$PlaceofBirth  = $dbarray['PlaceofBirth'];
		$EmergencyNo  = $dbarray['EmergNO'];
		$ContactPerson  = $dbarray['EmergName'];
		$GrAddress  = $dbarray['Gr_Addr'];
		$Remarks = $dbarray['Remarks'];
		$Curricular = $dbarray['ExtraCurricular'];
		
		$query = "select * from tbstudentmaster where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$arrDate=explode ('-', $dbarray['Stu_Date_of_Admis']);
		$Adm_Dy = $arrDate[2];
		$Adm_Mth = $arrDate[1];
		$Adm_Yr = $arrDate[0];
		$OptClass  = $dbarray['Stu_Class'];
		$OptSection  = $dbarray['Stu_Sec'];
		$StudentName  = $dbarray['Stu_Full_Name'];
		$arrDate2= explode('-', $dbarray['Stu_DOB']);
		$DOB_Dy = $arrDate2[2];
		$DOB_Mth = $arrDate2[1];
		$DOB_Yr = $arrDate2[0];
		$OptGender  = $dbarray['Stu_Gender'];
		$Tel_No  = $dbarray['Stu_Phone'];
		$StuEmail  = $dbarray['Stu_Email'];
		$RegFee  = $dbarray['Stu_Reg_Fee'];
		$Stutype  = $dbarray['Stu_Type'];
		$StuFilename  = $dbarray['Stu_Photo'];
		$arrDate3=explode ('-', $dbarray['AdmissionNo']);
		$AdmNo = $arrDate3[0];
		$AdmNo2 = $arrDate3[1];
		$LastSchool  = $dbarray['SchoolLeft'];
		$OldStudent  = $dbarray['OldStudent'];
		$ReasonDOB  = $dbarray['ReasonDOB'];
		$Games = $dbarray['Games'];
		$OptTransMode = $dbarray['Mode_of_coming'];
		
		
		//Get Total Student in class
		$numrows = 0;
		$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster Where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result4  = mysql_query($query4,$conn) or die('Error, query failed');
		$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
		$TotStu_In_Class = $row4['numrows'];
		
		//Get Total Student in School
		$numrows = 0;
		$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result4  = mysql_query($query4,$conn) or die('Error, query failed');
		$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
		$TotStu_In_Sch = $row4['numrows'];
		
		//$SubPage = "Admission";
	}
	
	if(isset($_POST['GetRegistration']))
	{
		$RegNo = $_POST['RegNo'];
		$StuFilename = $_POST['Stufilename'];
		
		$query = "select * from tbregistration where ID='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		
		$OptClass  = $dbarray['Stu_ClassID'];
		$StudentName  = $dbarray['Stu_FirstName']." ".$dbarray['Stu_MidName']." ".$dbarray['Stu_LastName'];
		$arrDate2=explode ('-', $dbarray['Stu_DOB']);
		$DOB_Dy = $arrDate2[2];
		$DOB_Mth = $arrDate2[1];
		$DOB_Yr = $arrDate2[0];
		$RegFee  = $dbarray['RegFee'];
		$OptGender  = $dbarray['Stu_Gender'];
		$GrAddress  = $dbarray['Stu_Address'];
		$GrMr  = $dbarray['Stu_Father'];
		$GrMrs  = $dbarray['Stu_Mother'];
		$OptCity  = $dbarray['CityID'];
		$State  = $dbarray['Stu_State'];
		$Tel_No  = $dbarray['Stu_Phone'];
		
		//Get Total Student in class
		$numrows = 0;
		$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster Where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result4  = mysql_query($query4,$conn) or die('Error, query failed');
		$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
		$TotStu_In_Class = $row4['numrows'];
	}
	
	if(isset($_GET['regNo']))
	{
		$RegNo = $_GET['regNo'];
		$query = "select * from tbregistration where ID='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";;
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		
		$OptClass  = $dbarray['Stu_ClassID'];
		$StudentName  = $dbarray['Stu_FirstName']." ".$dbarray['Stu_MidName']." ".$dbarray['Stu_LastName'];
		$arrDate2=explode ('-', $dbarray['Stu_DOB']);
		$DOB_Dy = $arrDate2[2];
		$DOB_Mth = $arrDate2[1];
		$DOB_Yr = $arrDate2[0];
		$RegFee  = $dbarray['RegFee'];
		$OptGender  = $dbarray['Stu_Gender'];
		$GrAddress  = $dbarray['Stu_Address'];
		$GrMr  = $dbarray['Stu_Father'];
		$GrMrs  = $dbarray['Stu_Mother'];
		$OptCity  = $dbarray['CityID'];
		$State  = $dbarray['Stu_State'];
		$Tel_No  = $dbarray['Stu_Phone'];
				
		$query = "select * from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$Weight  = $dbarray['Stu_Wt'];
		$Height  = $dbarray['Stu_Ht'];
		$Age  = $dbarray['Stu_Age'];
		if($GrMr==""){
			$GrMr  = $dbarray['Gr_Name_Mr'];
		}
		if($GrMrs==""){
			$GrMrs  = $dbarray['Gr_Name_Ms'];
		}
		if($OptCity==""){
			$OptCity  = $dbarray['Gr_City'];
		}
		if($State==""){
			$State  = $dbarray['Gr_State'];
		}
		$GrTel  = $dbarray['Gr_Ph'];
		$CertNo  = $dbarray['Cert_No'];
		$arrDate=explode ('-', $dbarray['ScLv_Date']);
		$SchoolLeaving_day = $arrDate[2];
		$SchoolLeaving_mth = $arrDate[1];
		$SchoolLeaving_yr = $arrDate[0];
		$BloodGroup  = $dbarray['BloodGroup'];
		$Religion  = $dbarray['Religion'];
		$NickName  = $dbarray['NickName'];
		$PlaceofBirth  = $dbarray['PlaceofBirth'];
		$Emergency  = $dbarray['EmergNO'];
		$ContactPerson  = $dbarray['EmergName'];
		if($GrAddress==""){
			$GrAddress  = $dbarray['Gr_Addr'];
		}
		
		//GET OTHER PAGE FIELD RECORDS
		$PhyName  = $dbarray['Phy_Name'];
		$PhyTel  = $dbarray['Phy_Ph'];
		$PhyMobile  = $dbarray['Phy_MbPh'];
		$AccomName  = $dbarray['Per_Name'];
		if($AccomFilename =="empty_r2_c2.jpg" or $AccomFilename ==""){
			$AccomFilename  = $dbarray['Per_Photo'];
		}
		$AccomOcc  = $dbarray['Per_Occp'];
		$Remarks  = $dbarray['Remarks'];
		$OptTransMode  = $dbarray['Tr_Mode'];
		$BedWelter  = $dbarray['BedWetter'];
		$CommDiseases  = $dbarray['CommunicableDisease'];
		$Curricular  = $dbarray['ExtraCurricular'];
		
		
		$query = "select * from tbstudentmaster where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$arrDate=explode ('-', $dbarray['Stu_Date_of_Admis']);
		$Adm_Dy = $arrDate[2];
		$Adm_Mth = $arrDate[1];
		$Adm_Yr = $arrDate[0];
		$OptClass  = $dbarray['Stu_Class'];
		$OptSection  = $dbarray['Stu_Sec'];
		if($StudentName==""){
			$StudentName  = $dbarray['Stu_Full_Name'];
		}
		if($DOB_Dy =="" and $DOB_Mth=="" and $DOB_Yr=""){
			$arrDate2=explode ('-', $dbarray['Stu_DOB']);
			$DOB_Dy = $arrDate2[2];
			$DOB_Mth = $arrDate2[1];
			$DOB_Yr = $arrDate2[0];
		}
		if($OptGender==""){
			$OptGender  = $dbarray['Stu_Gender'];
		}
		if($Tel_No==""){
			$Tel_No  = $dbarray['Stu_Phone'];
		}
		$StuEmail  = $dbarray['Stu_Email'];
		if($RegFee==""){
			$RegFee  = $dbarray['Stu_Reg_Fee'];
		}
		$Stutype  = $dbarray['Stu_Type'];
		if($StuFilename =="empty_r2_c2.jpg" or $StuFilename ==""){
			$StuFilename  = $dbarray['Stu_Photo'];
		}
		$arrDate3=explode ('-', $dbarray['AdmissionNo']);
		$AdmNo = $arrDate3[0];
		$AdmNo2 = $arrDate3[1];
		$LastSchool  = $dbarray['SchoolLeft'];
		if($OldStudent==""){
			$OldStudent  = $dbarray['OldStudent'];
		}
		$ReasonDOB  = $dbarray['ReasonDOB'];
		
		//GET OTHER PAGE FIELD RECORDS
		$OptTransMode  = $dbarray['Mode_of_coming'];
		$Remarks  = $dbarray['RemarksCon'];
		$Games  = $dbarray['Games'];
		
		//Get Total Student in class
		$numrows = 0;
		$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster Where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result4  = mysql_query($query4,$conn) or die('Error, query failed');
		$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
		$TotStu_In_Class = $row4['numrows'];
		
	}
	// filename: photo.php 

	// first let's set some variables 
	
	// make a note of the current working directory relative to root. 
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 
	
	// make a note of the location of the upload handler script 
	$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseStuPhoto.processor.php?regNo='.$RegNo;
	$uploadHandler2 = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browseAccPhoto.processor.php?regNo='.$RegNo; 
	// set a max file size for the html upload form 
	$max_file_size = 100000; // size in bytes OR 30kb
	if(isset($_POST['SubmitNewAdm']))
	{	
		$SubPage = "Admission";
	}
	if(isset($_POST['SubmitShowAdm'])){	
		$OptStutype = $_POST['OptStutype'];
		$OptSection = $_POST['OptSection'];
		
		$FrmDate = $_POST['fEn_Yr']."-".$_POST['fEn_Mth']."-".$_POST['fEn_Dy'];
		$ToDate = $_POST['bEn_Yr']."-".$_POST['bEn_Mth']."-".$_POST['bEn_Dy'];
		
		$fEn_Yr = $_POST['fEn_Yr'];
		$fEn_Mth = $_POST['fEn_Mth'];
		$fEn_Dy = $_POST['fEn_Dy'];
		$bEn_Yr = $_POST['bEn_Yr'];
		$bEn_Mth = $_POST['bEn_Mth'];
		$bEn_Dy = $_POST['bEn_Dy'];
	}
	if(isset($_POST['Admmaster_delete']))
	{
		$Total = $_POST['TotalAdm'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkadmID'.$i]))
			{
				$chkadmID = $_POST['chkadmID'.$i];
				$chkregNo = $_POST['chkregNo'.$i];
				$q = "Delete From tbstudentdetail where Stu_Regist_No = '$chkregNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
				$q = "Delete From tbstudentmaster where Stu_Regist_No = '$chkregNo'and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
				$q = "update tbregistration set Status='0' where ID = '$chkregNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Subcharges']))
	{	
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=studcharges.php?subpg=Charges and Documents\">";
		exit;
	}
	
  if(isset($_POST['SubmitSearch'])){
       $SubmitSearch = 'true'; 
	   $Search_Key = $_POST['Search_Key'];
	   }
   if(isset($_GET['Search_Key']))
	{
		//$Optsearch = $_GET['Optsearch'];
		 $SubmitSearch = 'true';
		$Search_Key = $_GET['Search_Key'];
		
	}

    if(isset($_POST['excel_data_upload']))
      {

		  require_once 'excel_reader2.php';

		   
					             
		   
		   $data = new Spreadsheet_Excel_Reader("admission_data.xls");

               for($w=0;$w<count($data->sheets);$w++) // Loop to get all sheets in a file.

			   {

				   if (count($data->sheets[$w][cells]) > 0) // checking sheet not empty
				   {
					   
					   
					   // $z = count($data->sheets[$w][cells]);
					     $x = 4;
					   for ($x = 4; $x <= count($data->sheets[$w][cells]); $x++) {
                           
						 //  for ($x = 4; $x <= 8; $x++) {
						   //   for($data->sheets[$w][cells][$x][1]  !=  null){


						   $dateadmitted = $data->sheets[$w][cells][$x][1];
						   //echo $dateadmitted;
						   //  echo $z;

						   $admNo = $data->sheets[$w][cells][$x][2];
						   $firstname = $data->sheets[$w][cells][$x][3];
					       //$StudentName = $data->sheets[$w][cells][$x][4];
						   $middlename = $data->sheets[$w][cells][$x][4];
						   $lastname = $data->sheets[$w][cells][$x][5];
						   $sex = $data->sheets[$w][cells][$x][6];
						   $dateofbirth = $data->sheets[$w][cells][$x][7];
						   $address = $data->sheets[$w][cells][$x][8];
						   $stateoforigin = $data->sheets[$w][cells][$x][9];
						   $nameofguardian = $data->sheets[$w][cells][$x][10];
						   $guardianoccupation = $data->sheets[$w][cells][$x][11];
						   $lastschoolattended = $data->sheets[$w][cells][$x][12];
						   $presentclass = $data->sheets[$w][cells][$x][13];
						   
						   $query = "select * from tbclassmaster where Class_Name = '$presentclass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					       $result = mysql_query($query, $conn);
						   $dbarray = mysql_fetch_array($result);		
		                     $OptClass  = $dbarray['ID'];
						   
					      // $OptClass = 1;
						   $StudentName = $firstname.' '.$middlename.' '.$lastname;
						   //echo $StudentName;
						  // exit;
						   
					 //  echo $dateadmitted.' '.$admNo.' '.$StudentName.' '.$address.' '.$nameofguardian.' '.$data->sheets[$w][cells][$x][10];
					        //$dateadmitted = "2011-11-16";
					        //$dateofbirth = "2011-11-16";
					  /*$q = "Insert into tbregistration (Stu_DateRegis,Stu_ClassID,Stu_FirstName,Stu_MidName,Stu_LastName,Stu_DOB,
					  Stu_Gender,Stu_Address,Stu_State,Stu_Father,Stu_Mother) Values ('$dateadmitted','$OptClass','$firstname','$middlename','$lastname','$dateofbirth','$sex','$address','$stateoforigin','$nameofguardian','$nameofguardian')";
					  $result = mysql_query($q, $conn);*/
					  
					  

					  /* $q = "insert into tbstudentmaster (Stu_Regist_No,Stu_Date_of_Admis,Stu_Class,Stu_Full_Name,Stu_DOB,Stu_Gender,Stu_State,AdmissionNo,SchoolLeft) Values ('$regID','$dateadmitted','$OptClass','$StudentName','$dateofbirth','$sex','$stateoforigin','$admNo','$lastschoolattended')";
					  $result = mysql_query($q, $conn);*/

	                //  $query = "Insert into tbstudentdetail (Stu_Regist_No,Gr_Name_Mr,Gr_Name_Ms) Values ('79','nddd','kdjdj')";
					 //  $result = mysql_query($query, $conn);

					   /*$query2 = "update tbregistration set Status = '1' where ID='$regID'";
					   $result = mysql_query($query2, $conn);*/
						  $num_rows = 0;
						   $query = "select ID from tbregistration where Stu_FirstName = '$firstname'
                          and Stu_MidName = '$middlename' and Stu_LastName = '$lastname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						   $result = mysql_query($query, $conn);
						   $num_rows = mysql_num_rows($result);
						   if ($num_rows > 0) {
							   $errormsg = "<font color = red size = 1>The registration you are trying to save already exist.</font>";
							   $PageHasError = 1;

						   } else {
					
					$q = "Insert into tbregistration (Stu_DateRegis,Stu_ClassID,Stu_FirstName,Stu_MidName,Stu_LastName,Stu_DOB,
					  Stu_Gender,Stu_Address,Stu_State,Stu_Father,Stu_Mother,Session_ID,Term_ID) Values ('$dateadmitted','$OptClass','$firstname','$middlename','$lastname','$dateofbirth','$sex','$address','$stateoforigin','$nameofguardian','$nameofguardian','$Session_ID','$Term_ID')";
					  $result = mysql_query($q, $conn);

						   }
						   
						   $query = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' ORDER BY ID DESC LIMIT 1";
					     $result = mysql_query($query, $conn);
						  $dbarray = mysql_fetch_array($result);		
		                     $regID  = $dbarray['ID'];
						     //$regID++;
						   //echo $regID;

						   $num_rows = 0;
						   $query = "select ID from tbstudentmaster where Stu_Regist_No = '$regID' or AdmissionNo = '$admNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						   $result = mysql_query($query, $conn);
						   $num_rows = mysql_num_rows($result);
						   $num_rows2 = 0;
						   $query2 = "select ID from tbstudentmaster where Stu_Full_Name = '$StudentName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						   $result2 = mysql_query($query2, $conn);
						   $num_rows2 = mysql_num_rows($result2);
						  // $num_rows2 = 2;
						   if ($num_rows > 0) {
							   $errormsg = "<font color = red size = 1>The student registration/admission no you are trying to save already exist.</font>";
							   $PageHasError = 1;
						   } elseif ($num_rows2 > 0) {
							   $errormsg = "<font color = red size = 1>The Admission information you are trying to save already exist.</font>";
							   $PageHasError = 1;
						   } else {
							   $q = "insert into tbstudentmaster (Stu_Regist_No,Stu_Date_of_Admis,Stu_Class,Stu_Full_Name,Stu_DOB,
                 Stu_Gender,Stu_State,AdmissionNo,SchoolLeft,Session_ID,Term_ID)
                 Values ('$regID','$dateadmitted','$OptClass','$StudentName','$dateofbirth','$sex',
                 '$stateoforigin','$admNo','$lastschoolattended','$Session_ID','$Term_ID')";
							   $result = mysql_query($q, $conn);

							   $query = "Insert into tbstudentdetail (Stu_Regist_No,Gr_Name_Mr,Gr_Name_Ms,Session_ID,Term_ID)
                        Values	('$admNo','$nameofguardian','$nameofguardian','$Session_ID','$Term_ID')";
							   $result = mysql_query($query, $conn);

							   $query2 = "update tbregistration set Status = '1' where ID='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							   $result = mysql_query($query2, $conn);
							    
								$errormsg = "<font color = red size = 1>Student Data Upload Succesful.</font>";
							   $PageHasError = 1;

						   }


					   }

				   }

			   }
	  }
	  
	  if(isset($_POST['excel_data_upload2']))
      {

		  require_once 'excel_reader2.php';
		  
		  $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 



               // make a note of the directory that will recieve the uploaded file 
                 //$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self ; 
                 $uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'admission/';
				 // make a note of the location of the upload form in case we need it 
   $uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'admission.php?subpg=Admission';
                    // fieldname used within the file <input> of the HTML form 
                                 $fieldname = 'file';
								 
				$errors = array(1 => 'php.ini max file size exceeded', 
				2 => 'html form max file size exceeded', 
				3 => 'file upload was only partial', 
				4 => 'no file was attached'); 

                     // check the upload form was actually submitted else print the form 
                             $fieldname = 'file';



                    isset($_POST['excel_data_upload2']) 
	                    or error('the upload form is needed', $uploadForm); 

                       // check for PHP's built-in uploading errors 
                           ($_FILES[$fieldname]['error'] == 0) 
	                      or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 
	 
// check that the file we are working on really was the subject of an HTTP upload 
@is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
	or error('not an HTTP upload', $uploadForm); 
	 
// validation... since this is an image upload script we should run a check   
// to make sure the uploaded file is in fact an image. Here is a simple check: 
// getimagesize() returns false if the file tested is not an image. 
//@getimagesize($_FILES[$fieldname]['tmp_name']) 
//	or error('only image uploads are allowed', $uploadForm); 
	 
// make a unique filename for the uploaded file and check it is not already 
// taken... if it is already taken keep trying until we find a vacant one 
// sample filename: 1140732936-filename.jpg 
$now = time(); 
while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['name'])) 
{ 
	$now++; 
} 

// now let's move the file to its final location and allocate the new filename to it
//include 'library/config.php';
//include 'library/opendb.php';
$FullFileName = $now.'-'.$_FILES[$fieldname]['name'];

@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
	or error('receiving directory insuffiecient permission', $uploadForm);
// Save File Full Name to Database
// Save File Full Name to Database
//$instr = fopen($uploadFilename,"r");
//$image = addslashes(fread($instr,filesize($uploadFilename)));
//fclose($instr);
if(!get_magic_quotes_gpc())
{
    $FullFileName = addslashes($FullFileName);
	//$_SESSION['fullfilename']= $FullFileName;
}

$fullfilenamedirectory = $uploadsDirectory.$FullFileName;
chmod($fullfilenamedirectory,0755);
//$query = "update tbschool set Logo = '$FullFileName' where ID = '3'";
	//mysql_query($query) or die('Error, query failed');
	//$errormsg = "<font color = blue size = 1>Image uploaded successfully.</font>";
	//header('Location: ' . $uploadSuccess);

	
		
		// $OptSelExams= $_SESSION['OptSelExams'];
		// $OptClass = $_SESSION['OptClass'];
		// $OptSelSubject = $_SESSION['OptSelSubject'];
		// $FullFileName = $_SESSION['fullfilename'];
		

		 require_once 'excel_reader2.php';
         //$data = new Spreadsheet_Excel_Reader($handle);
		$data = new Spreadsheet_Excel_Reader("admission/".$FullFileName);				 

		   
					             
		   
		   //$data = new Spreadsheet_Excel_Reader("admission_data.xls");

               for($w=0;$w<count($data->sheets);$w++) // Loop to get all sheets in a file.

			   {

				   if (count($data->sheets[$w][cells]) > 0) // checking sheet not empty
				   {
					   
					   
					   // $z = count($data->sheets[$w][cells]);
					     $x = 4;
					   for ($x = 4; $x <= count($data->sheets[$w][cells]); $x++) {
                           
						 //  for ($x = 4; $x <= 8; $x++) {
						   //   for($data->sheets[$w][cells][$x][1]  !=  null){


						   $dateadmitted = $data->sheets[$w][cells][$x][1];
						   //echo $dateadmitted;
						   //  echo $z;

						   $admNo = $data->sheets[$w][cells][$x][2];
						   $firstname = $data->sheets[$w][cells][$x][3];
					       //$StudentName = $data->sheets[$w][cells][$x][4];
						   $middlename = $data->sheets[$w][cells][$x][4];
						   $lastname = $data->sheets[$w][cells][$x][5];
						   $sex = $data->sheets[$w][cells][$x][6];
						   $dateofbirth = $data->sheets[$w][cells][$x][7];
						   $address = $data->sheets[$w][cells][$x][8];
						   $stateoforigin = $data->sheets[$w][cells][$x][9];
						   $nameofguardian = $data->sheets[$w][cells][$x][10];
						   $guardianoccupation = $data->sheets[$w][cells][$x][11];
						   $lastschoolattended = $data->sheets[$w][cells][$x][12];
						   $presentclass = $data->sheets[$w][cells][$x][13];
						   
						   $query = "select * from tbclassmaster where Class_Name = '$presentclass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					       $result = mysql_query($query, $conn);
						   $dbarray = mysql_fetch_array($result);		
		                     $OptClass  = $dbarray['ID'];
						   
					      // $OptClass = 1;
						   $StudentName = $firstname.' '.$middlename.' '.$lastname;
						   //echo $StudentName;
						  // exit;
						
						  $num_rows = 0;
						   $query = "select ID from tbregistration where Stu_FirstName = '$firstname'
                          and Stu_MidName = '$middlename' and Stu_LastName = '$lastname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						   $result = mysql_query($query, $conn);
						   $num_rows = mysql_num_rows($result);
						   if ($num_rows > 0) {
							   $errormsg = "<font color = red size = 1>The registration you are trying to save already exist.</font>";
							   $PageHasError = 1;

						   } else {
					
					$q = "Insert into tbregistration (Stu_DateRegis,Stu_ClassID,Stu_FirstName,Stu_MidName,Stu_LastName,Stu_DOB,
					  Stu_Gender,Stu_Address,Stu_State,Stu_Father,Stu_Mother,Session_ID,Term_ID) Values ('$dateadmitted','$OptClass','$firstname','$middlename','$lastname','$dateofbirth','$sex','$address','$stateoforigin','$nameofguardian','$nameofguardian','$Session_ID','$Term_ID')";
					  $result = mysql_query($q, $conn);

						   }
						   
						   $query = "select * from tbregistration where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' ORDER BY ID DESC LIMIT 1";
					     $result = mysql_query($query, $conn);
						  $dbarray = mysql_fetch_array($result);		
		                     $regID  = $dbarray['ID'];
						     //$regID++;
						   //echo $regID;

						   $num_rows = 0;
						   $query = "select ID from tbstudentmaster where Stu_Regist_No = '$regID' or AdmissionNo = '$admNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						   $result = mysql_query($query, $conn);
						   $num_rows = mysql_num_rows($result);
						   $num_rows2 = 0;
						   $query2 = "select ID from tbstudentmaster where Stu_Full_Name = '$StudentName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						   $result2 = mysql_query($query2, $conn);
						   $num_rows2 = mysql_num_rows($result2);
						  // $num_rows2 = 2;
						   if ($num_rows > 0) {
							   $errormsg = "<font color = red size = 1>The student registration/admission no you are trying to save already exist.</font>";
							   $PageHasError = 1;
						   } elseif ($num_rows2 > 0) {
							   $errormsg = "<font color = red size = 1>The Admission information you are trying to save already exist.</font>";
							   $PageHasError = 1;
						   } else {
							   $q = "insert into tbstudentmaster (Stu_Regist_No,Stu_Date_of_Admis,Stu_Class,Stu_Full_Name,Stu_DOB,
                 Stu_Gender,Stu_State,AdmissionNo,SchoolLeft,Session_ID,Term_ID)
                 Values ('$regID','$dateadmitted','$OptClass','$StudentName','$dateofbirth','$sex',
                 '$stateoforigin','$admNo','$lastschoolattended','$Session_ID','$Term_ID')";
							   $result = mysql_query($q, $conn);

							   $query = "Insert into tbstudentdetail (Stu_Regist_No,Gr_Name_Mr,Gr_Name_Ms,Session_ID,Term_ID)
                        Values	('$admNo','$nameofguardian','$nameofguardian','$Session_ID','$Term_ID')";
							   $result = mysql_query($query, $conn);

							   $query2 = "update tbregistration set Status = '1' where ID='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							   $result = mysql_query($query2, $conn);
							    
								$errormsg = "<font color = red size = 1>Student Data Upload Succesful.</font>";
							   $PageHasError = 1;

						   }


					   }

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
.style22 {color: #FFFFFF}
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
		if ($SubPage == "Admission") {
?>
				<?PHP echo $errormsg;
				 $query = "select * from tbstudenttemppic and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$row = mysql_fetch_array($result);
							$status = $row["Status"];
							
							if($status == 1){
							$imagephoto = "getstudenttemppic.php";
							$query = "update tbstudenttemppic set Status = '0', PictureStatus = '1' where ID = 1 and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							}else{
							$query = "update tbstudenttemppic set PictureStatus = '0' where ID = 1 and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);}
							//echo $Picturestatus;
							//echo $content;
							
							/*$query = "select * from tbemployeetemppic";
				             $result = mysql_query($query,$conn);
				               $row = mysql_fetch_array($result);
				            $content = $row["Content"];
							echo $content;*/
		                    $query = "update tbemployeetemppic set Status = '0', PictureStatus = '1' where ID = 2 and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				            $result = mysql_query($query,$conn);
							if($employeegetPicstatus == 1){
								$query = "update tbemployeetemppic set PictureStatus = '1' where ID = 1 and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		                         $result = mysql_query($query,$conn);
							}
							
				?>
				
				<form name="form1" method="post" action="admission.php?subpg=Admission">
				  <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>

					<TR><td>
							<form name="import" action="" method="post" enctype="multipart/form-data">
					<TR><TD width="12%" valign="top"  align="right"><strong> Upload Student Data Via Excel </strong></TD>
						<TD width="21%" valign="top"  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="file" type="file" name="file">
							<input name="excel_data_upload" type="submit" id="excel_data_upload" value="Upload" >
						</TD></TR>
				</form>
			  </td></TR>
			<TR><td>
					&nbsp;
			</td></TR>

					 <TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;">
						<table width="707" border="0" cellspacing="1" cellpadding="1" style="height:30px;">
						   <tr>
							 <td width="165"><div align="center" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Personal Information</strong></div></td>
							 <td width="177" bgcolor="#666666"><div align="center" class="style22">Other Details</div></td>
						   </tr>
						</table></div>
					  </TD>
					</TR>

					 <TR>
					  <TD colspan="4" valign="top"  align="left">
						<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;">
						Reg No:
						<input name="RegNo" type="text" size="5" value="<?PHP echo $RegNo; ?>" style="background-color:#FFFF99">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admit into: &nbsp;&nbsp;&nbsp;
						<select name="OptClass" style="background-color:#FFFF99">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name ";
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
						&nbsp;&nbsp;&nbsp;&nbsp;
			  &nbsp;Old Student&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Admission Date
					  <input name="Adm_Dy" type="text" size="2"  value="<?PHP echo $Adm_Dy; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="Adm_Mth" type="text" size="2"  value="<?PHP echo $Adm_Mth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="Adm_Yr" type="text" size="2"  value="<?PHP echo $Adm_Yr; ?>" ONFOCUS="clearDefault(this)">  
					    <input type="hidden" name="Stufilename" value="<?PHP echo $StuFilename; ?>">
						<input name="GetRegistration" type="submit" id="GetRegistration" value="Retreive Information">
						</div>
					  </TD>
					</TR>
					</TBODY>
					</TABLE>
				    <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="13%" valign="top"  align="left">Admission No </TD>
					  <TD width="27%" valign="top"  align="left"><input name="AdmNo" type="text"value="<?PHP echo $AdmNo; ?>" size="10"/>
					    <input name="AdmNo2" type="text" value="<?PHP echo $AdmNo2; ?>" size="5"/>
					    *</TD>
					  <TD width="22%" valign="top"  align="left">Student Name : </TD>
					  <TD width="38%" valign="top"  align="left"><input name="StudentName" type="text" value="<?PHP echo $StudentName; ?>" size="45" style="background-color:#FFFF99"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Place of Birth</TD>
					  <TD width="27%" valign="top"  align="left"><input type="text" name="PlaceofBirth" value="<?PHP echo $PlaceofBirth; ?>"/></TD>
					  <TD width="22%" valign="top"  align="left">Nick Name : </TD>
					  <TD width="38%" valign="top"  align="left"><input name="NickName" type="text" value="<?PHP echo $NickName; ?>" size="45"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Age</TD>
					  <TD width="27%" valign="top"  align="left"><input name="Age" type="text" value="<?PHP echo $Age; ?>" size="5"/>
						Gender
  						<select name="OptGender">
    <?PHP
						if($OptGender == "M"){
							echo "<option value='M' selected='selected'>Male</option>";
						}else{
							echo "<option value='M'>Male</option>";
						}
						if($OptGender == "F"){
							echo "<option value='F' selected='selected'>Female</option>";
						}else{
							echo "<option value='F'>Female</option>";
						}
?>
  </select></TD>
					  <TD width="22%" valign="top"  align="left">Date of Birth  : </TD>
					  <TD width="38%" valign="top"  align="left">
					  <input name="DOB_Dy" type="text" size="2"  value="<?PHP echo $DOB_Dy; ?>" ONFOCUS="clearDefault(this)" style="background-color:#FFFF99">
					    /
					    <input name="DOB_Mth" type="text" size="2"  value="<?PHP echo $DOB_Mth; ?>" ONFOCUS="clearDefault(this)" style="background-color:#FFFF99">
					    /
					    <input name="DOB_Yr" type="text" size="2"  value="<?PHP echo $DOB_Yr; ?>" ONFOCUS="clearDefault(this)" style="background-color:#FFFF99">
						</TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Height : </TD>
					  <TD width="27%" valign="top"  align="left"><input name="Height" type="text" value="<?PHP echo $Height; ?>" size="5"/>
Tel No
  <input name="Tel_No" type="text" value="<?PHP echo $Tel_No; ?>" size="10" style="background-color:#FFFF99"/></TD>
					  <TD width="22%" valign="top"  align="left">Reason if DOB not Known </TD>
					  <TD width="38%" valign="top"  align="left"><input name="ReasonDOB" type="text" value="<?PHP echo $ReasonDOB; ?>" size="45"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Weight : </TD>
					  <TD width="27%" valign="top"  align="left"><input type="text" name="Weight" value="<?PHP echo $Weight; ?>"/></TD>
					  <TD width="22%" valign="top"  align="left">Last School Attended  : </TD>
					  <TD width="38%" valign="top"  align="left"><input name="LastSchool" type="text" value="<?PHP echo $LastSchool; ?>" size="45"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Religion : </TD>
					  <TD width="27%" valign="top"  align="left">
					  <select name="Religion">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,ReligionName from tbreligionmaster order by ReligionName ";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
									$ReligionName = $row["ReligionName"];
									if($Religion ==$ReligionName){
?>
										<option value="<?PHP echo $ReligionName; ?>" selected="selected"><?PHP echo $ReligionName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $ReligionName; ?>"><?PHP echo $ReligionName; ?></option>
<?PHP
									}
								}
							}
?>
					    </select>
						</TD>
					  <TD width="22%" valign="top"  align="left">School Leaving Date  : </TD>
					  <TD width="38%" valign="top"  align="left">
					  <input name="SchoolLeaving_day" type="text" size="2"  value="<?PHP echo $SchoolLeaving_day; ?>">
					    /
					    <input name="SchoolLeaving_mth" type="text" size="2"  value="<?PHP echo $SchoolLeaving_mth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="SchoolLeaving_yr" type="text" size="2"  value="<?PHP echo $SchoolLeaving_yr; ?>" ONFOCUS="clearDefault(this)"> 
					    Cert No. 
					    <input name="CertNo" type="text" value="<?PHP echo $CertNo; ?>" size="5"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Blood Group: </TD>
					  <TD width="27%" valign="top"  align="left"><input type="text" name="BloodGroup" value="<?PHP echo $BloodGroup; ?>"/></TD>
					  <TD width="22%" valign="top"  align="left"></TD>
					  <TD width="38%" valign="top"  align="left" rowspan="3" >
						<table width="221" border="0" align="center" cellpadding="0" cellspacing="0">
						  
						  <tr>
						   
						   
						  </tr>
						  <tr>
						   
						   <td><input type="hidden" name="StuFilename" value="<?PHP echo $StuFilename; ?>"></td>
						   
						   <td><img src="images/spacer.gif" width="1" height="175" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   
						   <td><img src="images/spacer.gif" width="1" height="22" border="0" alt="" /></td>
						  </tr>
						</table>
					    
					    <p align="left">Email: 
					      <input name="StuEmail" type="text" size="35" value="<?PHP echo $StuEmail; ?>">
					    </p></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Student Type: </TD>
					  <TD width="27%" valign="top"  align="left">
<?PHP
						if($Stutype =="Scholar"){
?>
					  		<input name="Stutype" type="radio" value="Scholar" checked="checked">Day Scholar  
<?PHP
						}else{
?>
							<input name="Stutype" type="radio" value="Scholar">Day Scholar
<?PHP
						}
?>
           <?PHP
						if($Stutype =="Hosteller"){
?>
					  		<input name="Stutype" type="radio" value="Hosteller" checked="checked">Hosteller  
<?PHP
						}else{
?>
							<input name="Stutype" type="radio" value="Hosteller">Hosteller
<?PHP
						}
?>
				     </TD>
					  <TD width="22%" valign="top"  align="left">

					  </TD>
					</TR>
					<TR>
					  <TD height="162" colspan="3"  align="left" valign="top"><label><br>
					    <br>
					  </label>
					  <table border="0" cellpadding="0" cellspacing="0" width="365">
						  <tr>
						   <td colspan="3"><img name="Untitled1_r1_c1" src="images/Untitled-1_r1_c12.jpg" width="365" height="15" border="0" id="Untitled1_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="15" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="Untitled1_r2_c1" src="images/Untitled-1_r2_c1.jpg" width="9" height="235" border="0" id="Untitled1_r2_c1" alt="" /></td>
						   <td>
						   		<TABLE width="100%" style="WIDTH: 100%">
								<TBODY>
								<TR>
									<TD width="21%"  align="left" valign="top">Mr.</TD>
									<TD width="79%"  align="left" valign="top"><input name="GrMr" type="text" size="35" value="<?PHP echo $GrMr; ?>" style="background-color:#FFFF99"></TD>
								</TR>
								<TR>
									<TD width="21%"  align="left" valign="top">Mrs</TD>
									<TD width="79%"  align="left" valign="top"><label>
									  <input name="GrMrs" type="text" size="35" value="<?PHP echo $GrMrs; ?>" style="background-color:#FFFF99">
									</label></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Address</TD>
									<TD valign="top"  align="left"><textarea name="GrAddress" cols="35" rows="3" style="background-color:#FFFF99"><?PHP echo $GrAddress; ?></textarea></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">City</TD>
									<TD valign="top"  align="left">
									<select name="OptCity" style="background-color:#FFFF99">
									<option value="0" selected="selected">&nbsp;</option>
<?PHP
										$query = "select ID,CityName from mcity order by CityName ";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$CityID = $row["ID"];
												$CityName = $row["CityName"];
												if($OptCity =="$CityID"){
?>
													<option value="<?PHP echo $CityID; ?>" selected="selected"><?PHP echo $CityName; ?></option>
<?PHP
												}else{
?>
													<option value="<?PHP echo $CityID; ?>"><?PHP echo $CityName; ?></option>
<?PHP
												}
											}
										}
?>
					     			 </select>
									State.
									 <SELECT name="State" style="width:80px;">
                                       <OPTION value="">State</OPTION>
<?PHP
								if($State != ""){
?>
                                       <option value="<?PHP echo $State; ?>" selected="selected"><?PHP echo $State; ?></option>
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
									<TD valign="top"  align="left">Telephone</TD>
									<TD valign="top"  align="left"><input name="GrTel" type="text" size="10" value="<?PHP echo $GrTel; ?>"> 
									 
                                     </TD><TD> EmergencyNO </TD>
									    <TD><input name="EmergencyNo" type="text" size="10" value="<?PHP echo $EmergencyNo; ?>"></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Contact Person </TD>
									<TD valign="top"  align="left"><input name="ContactPerson" type="text" size="35" value="<?PHP echo $ContactPerson; ?>"></TD>
								</TR>
								</TBODY>
								</TABLE>
						   </td>
						   <td rowspan="2"><img name="Untitled1_r2_c3" src="images/Untitled-1_r2_c3.jpg" width="9" height="235" border="0" id="Untitled1_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="225" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="Untitled1_r3_c2" src="images/Untitled-1_r3_c2.jpg" width="347" height="10" border="0" id="Untitled1_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="10" border="0" alt="" /></td>
						  </tr>
						</table>
					  </TD>
					</TR>
					<TR>
						<TD colspan="4" valign="top"  align="left">
							</TD>
					</TR>
					<TR>
						 <TD colspan="4">
						 <div align="center">
						 	<input type="hidden" name="SelRegNo" value="<?PHP echo $RegNo; ?>">
							 <input type="hidden" name="SelRegFee" value="<?PHP echo $RegFee; ?>">
							 <input type="hidden" name="SelAdmID" value="<?PHP echo $AdmID; ?>">
<?PHP 
							
?>
				
						</div>
						 </TD>
					</TR>
					</TBODY>
					</TABLE>
					<br><br>
				
				
			
				  
				  <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: blue; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Family Physician / Pediatrician</strong></div></TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">Name</TD>
					  <TD width="18%" valign="top"  align="left"><input type="text" name="PhyName" value="<?PHP echo $PhyName; ?>"/></TD>
					  <TD width="24%" valign="top"  align="left"><div align="right">Telephone No </div></TD>
					  <TD width="47%" valign="top"  align="left"><input name="PhyTel" type="text" value="<?PHP echo $PhyTel; ?>" size="15"/> 
					    Mobile No 
					      <input name="PhyMobile" type="text" value="<?PHP echo $PhyMobile; ?>" size="15"/></TD>
					</TR>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: blue; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Name and identity of the person accompany the child </strong></div></TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">  Name </TD>
					  <TD width="18%" valign="top"  align="left"><input type="text" name="AccomName" value="<?PHP echo $AccomName; ?>"/></TD>
					  <TD width="24%" valign="top"  align="left"><div align="right">Occupation</div></TD>
					  <TD width="47%" valign="top"  align="left"><input type="text" name="AccomOcc" value="<?PHP echo $AccomOcc; ?>"/></TD>
					</TR>
					<TR>
					  <TD colspan="3" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: blue; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Other details </strong></div></TD>
					  <TD width="47%" valign="top"  align="left" rowspan="5">
					 
					  </TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">Bed Welter  : </TD>
					  <TD width="18%" valign="top"  align="left">
<?PHP
						if($BedWelter =="Yes")
						{
?>
					 		<input type="radio" name="BedWelter" value="Yes" checked="checked"/>Yes
<?PHP
						}else{
?>
							<input type="radio" name="BedWelter" value="Yes"/>Yes
<?PHP
						}
						if($BedWelter =="No")
						{
?>
					 		<input type="radio" name="BedWelter" value="No" checked="checked"/>No
<?PHP
						}else{
?>
							<input type="radio" name="BedWelter" value="No"/>No
<?PHP
						}
?>
						</TD>
					  <TD width="24%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">Communicable Diseases: </TD>
					  <TD width="24%" valign="top"  align="left">
<?PHP
						if($CommDiseases =="Yes")
						{
?>
					 		<input type="radio" name="CommDiseases" value="Yes" checked="checked"/>Yes
<?PHP
						}else{
?>
							<input type="radio" name="CommDiseases" value="Yes"/>Yes
<?PHP
						}
						if($CommDiseases =="No")
						{
?>
					 		<input type="radio" name="CommDiseases" value="No" checked="checked"/>No
<?PHP
						}else{
?>
							<input type="radio" name="CommDiseases" value="No"/>No
<?PHP
						}
?>
						</TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">Transportation Mode  : </TD>
					  <TD width="24%" valign="top"  align="left">				
						
						<select name="OptTransMode">
<?PHP
						if($OptTransMode == "FOOT"){
							echo "<option value='FOOT' selected='selected'>BY FOOT</option>";
						}else{
							echo "<option value='FOOT'>BY FOOT</option>";
						}
						if($OptTransMode == "CYCLE"){
							echo "<option value='CYCLE' selected='selected'>CYCLE</option>";
						}else{
							echo "<option value='CYCLE'>CYCLE</option>";
						}
						if($OptTransMode == "PARENTS"){
							echo "<option value='PARENTS' selected='selected'>PARENTS</option>";
						}else{
							echo "<option value='PARENTS'>PARENTS</option>";
						}
						if($OptTransMode == "VAN"){
							echo "<option value='VAN' selected='selected'>VAN</option>";
						}else{
							echo "<option value='VAN'>VAN</option>";
						}
						if($OptTransMode == "BUS"){
							echo "<option value='BUS' selected='selected'>LOCAL BUS</option>";
						}else{
							echo "<option value='BUS'>LOCAL BUS</option>";
						}
?>
					    </select>
					  </TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">Remarks</TD>
					  <TD colspan="2" valign="top"  align="left"><textarea name="Remarks" cols="40" rows="5"><?PHP echo $Remarks; ?></textarea></TD>
					</TR>

					<TR>
					  <TD width="11%" valign="top"  align="left">Games</TD>
					  <TD width="18%" valign="top"  align="left"><input type="text" name="Games" value="<?PHP echo $Games; ?>"/></TD>
					  <TD width="24%" valign="top"  align="left"><div align="right">Extra Curricular Activities </div></TD>
					  <TD width="47%" valign="top"  align="left"><input name="Curricular" type="text" value="<?PHP echo $Curricular; ?>" size="55"/></TD>
					</TR>
					<TR>
						<TD colspan="3" valign="top"  align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <div>
							 <input type="hidden" name="SelRegNo" value="<?PHP echo $RegNo; ?>">
							 <input type="hidden" name="SelAdmNo" value="<?PHP echo $AdmissionNo; ?>">
						     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             <?PHP if($EditAdmissionInfo == true)
							 {?><input name="EditAdmn" type="submit" id="EditAdmn" value="Done With Student Info Edit"><?PHP 
							 }else{ ?>
                             <input name="SubmitAdmn" type="submit" id="SubmitAdmn" value="Submit"><?PHP }
							 $EditAdmissionInfo = false;?>
						     <input type="reset" name="Reset" value="Reset">
						</div>
						  </TD>
						 <TD>
						 
						 </TD>
					</TR>
                     <TR><TD colspan="3"> 
                            </TD></TR>
                            <TR><TD colspan="4" align="center"> <P></P>
                            <p></p> 
                            <p></p> 
                            <p></p><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps; BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total No. of student in <?PHP echo GetClassName($OptClass); ?> is  <?PHP echo $TotStu_In_Class; ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;Total No. of student in school is  <?PHP echo $TotStu_In_Sch; ?>
							</div>    </TD></TR>
					</TBODY>
					</TABLE>
					<br><br>
				</form>
<?PHP
		}elseif ($SubPage == "Edit Student Info") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="admission.php?subpg=Edit Student Info">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="18%" valign="top"  align="left">
					  		<p><input type="submit" name="SubmitNewAdm" value="&nbsp;&nbsp;New Registration&nbsp;&nbsp;&nbsp;"></p>
							<p><input type="submit" name="Subcharges" value="Charges and Docs"></p>
							<p>&nbsp;</p>					   </TD>
					  <TD valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  		<TABLE width="100%" align="center" style="WIDTH: 98%">
								<TBODY>
								<TR><TD width="67%" align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 10pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'Times New Roman', Times, serif; HEIGHT: 53px; FONT-VARIANT: small-caps">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Filter Student Type With Admission Date Range </TD>
								  <TR><TD width="33%" valign="top" align="left"><p>Student Type: 	
								  <select name="OptStutype">
								      <option value="">Select</option>
<?PHP
									if($OptStutype == "Scholar"){
?>
								      <option value="<?PHP echo $OptStutype; ?>" selected="selected">Day Scholar</option>
								      <option value="Hosteller">Hosteller</option>
<?PHP
									}elseif($OptStutype == "Hosteller"){
?>
								      <option value="Scholar">Day Scholar</option>
								      <option value="<?PHP echo $OptStutype; ?>" selected="selected"><?PHP echo $OptStutype; ?></option>
<?PHP
									}else{
?>
								      <option value="Scholar">Day Scholar</option>
								      <option value="Hosteller">Hosteller</option>
<?PHP
									}
?>
									</select>
								  </p>								    </TD>
								  
								</TR>
								<TR>
								  <TD colspan="2" valign="top"  align="left"><p>Admission  Date Interval:<span style="font-weight: bolder; WIDTH: 98%; color: #F00; font-size: 9px;"> &nbsp;&nbsp;&nbsp; BETWEEN </span> 
								      <select name="fEn_Dy">
								      <option value="0" selected="selected">Day</option>
								      
<?PHP
										$Cur_Dy = date('d');
										echo $Cur_Dy;
										for($i=1; $i<=31; $i++){
											if($fEn_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $Cur_Dy){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								    </select>
								    <select name="fEn_Mth">
								       <option value="0" selected="selected">Month</option>
<?PHP
											$Cur_Mth = date('m');
											for($i=1; $i<=12; $i++){
												if($i == $fEn_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
													
												}elseif($i == $Cur_Mth){
													echo "<option value=$i selected='selected'>$i</option>";
												}else{
													echo "<option value=$i>$i</option>";
												}
											}
?>
					                </select>
								    <select name="fEn_Yr">
								      <option value="0" selected="selected">Year</option>
 <?PHP
										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($fEn_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $CurYear){
													echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
						            </label><span style="font-weight: bolder; WIDTH: 98%; color: #F00; font-size: 9px;">AND</span>
								    <select name="bEn_Dy">
                                      <option value="0" selected="selected">Day</option>
<?PHP
										$Cur_Dy = date('d');
										echo $Cur_Dy;
										for($i=1; $i<=31; $i++){
											if($bEn_Dy == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $Cur_Dy){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
								  </select>
								  <select name="bEn_Mth">
									<option value="0" selected="selected">Month</option>
<?PHP
										$Cur_Mth = date('m');
										for($i=1; $i<=12; $i++){
											if($bEn_Mth == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $Cur_Mth){
												echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                    </select>
                                    <select name="bEn_Yr">
                                    <option value="0" selected="selected">Year</option>
<?PHP
										$CurYear = date('Y');
										for($i=2009; $i<=$CurYear; $i++){
											if($bEn_Yr == $i){
												echo "<option value=$i selected=selected>$i</option>";
											}elseif($i == $CurYear){
													echo "<option value=$i selected=selected>$i</option>";
											}else{
												echo "<option value=$i>$i</option>";
											}
										}
?>
                                   </select>
                                   </label>
								  </p>								    </TD>
								</TR>
                                <TR><TD width="33%" valign="top" align="center"><input type="submit" name="SubmitShowAdm" value="&nbsp;&nbsp;&nbsp;&nbsp;Show / Refresh&nbsp;&nbsp;" disabled>
                                           </TD></TR>
								</TBODY>
							</TABLE>
<?PHP

							if(isset($_POST['SubmitShowAdm']))
							{
?>
								<p align="center"><em>Date range: &nbsp;<?PHP echo Long_date($FrmDate); ?>&nbsp;&nbsp;&nbsp;&nbsp;To&nbsp;&nbsp;&nbsp;&nbsp;<?PHP echo Long_date($ToDate); ?>&nbsp;</em></p>
<?PHP

							}
?>						</TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">					  
						<p style="margin-left:150px;">Student Name :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="44" bgcolor="#F4F4F4"><div align="center" class="style21"><strong>Tick</strong></div></td>
                            <td width="69" bgcolor="#F4F4F4"><div align="center" class="style21"><strong>Reg. No </strong></div></td>
                            <td width="96" bgcolor="#F4F4F4"><div align="center"><strong>Adm. No </strong></div></td>
							<td width="132" bgcolor="#F4F4F4"><div align="center"><strong>Student Name</strong></div></td>
							<td width="94" bgcolor="#F4F4F4"><div align="center"><strong>Enrolled in </strong></div></td>
							<td width="99" bgcolor="#F4F4F4"><div align="center"><strong>Adm. Date </strong></div></td>
							<td width="109" bgcolor="#F4F4F4"><div align="center"><strong>Section / Term </strong></div></td>
                          </tr>
<?PHP

						if(isset($_POST['SubmitShowAdm']))
						{		
							$arrDateList = date_range($FrmDate,$ToDate);
							$i = 0;
							$counter_Adm = 0;
							$counter = 0;
							while(isset($arrDateList[$i])){
								$num_rows2 = 0;
								$numrows = 0;
								if($OptStutype!="" and $OptSection!=""){
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_Sec='$OptSection' and Stu_Type='$OptStutype' and Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}elseif($OptStutype=="" and $OptSection!=""){
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_Sec='$OptSection' and Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}elseif($OptStutype!="" and $OptSection==""){
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_Type='$OptStutype' and Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}elseif($OptStutype=="" and $OptSection==""){
									$query4 = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								}
								$result4  = mysql_query($query4,$conn) or die('Error, query failed');
								$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
								$num_rows2 = $row4['numrows'];
							
								if($rstart==0){
									$counter_Adm = $rstart;
								}else{
									$counter_Adm = $rstart-1;
								}
								
								if($OptStutype!="" and $OptSection!=""){
									$query3 = "select ID,Stu_Regist_No,AdmissionNo,Stu_Class,Stu_Full_Name,Stu_Date_of_Admis,Stu_Sec from tbstudentmaster where Stu_Sec='$OptSection' and Stu_Type='$OptStutype' and Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}elseif($OptStutype=="" and $OptSection!=""){
									$query3 = "select ID,Stu_Regist_No,AdmissionNo,Stu_Class,Stu_Full_Name,Stu_Date_of_Admis,Stu_Sec from tbstudentmaster where Stu_Sec='$OptSection' and Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}elseif($OptStutype!="" and $OptSection==""){
									$query3 = "select ID,Stu_Regist_No,AdmissionNo,Stu_Class,Stu_Full_Name,Stu_Date_of_Admis,Stu_Sec from tbstudentmaster where Stu_Type='$OptStutype' and Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}elseif($OptStutype=="" and $OptSection==""){
									$query3 = "select ID,Stu_Regist_No,AdmissionNo,Stu_Class,Stu_Full_Name,Stu_Date_of_Admis,Stu_Sec from tbstudentmaster where Stu_Date_of_Admis = '$arrDateList[$i]' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
								}
								$result3 = mysql_query($query3,$conn);
								$num_rows = mysql_num_rows($result3);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result3)) 
									{
										$counter_Adm = $counter_Adm+1;
										$counter = $counter+1;
										$AdmID = $row["ID"];
										$Stu_Regist_No = $row["Stu_Regist_No"];
										$AdmissionNo = $row["AdmissionNo"];
										$Stu_Class = $row["Stu_Class"];
										$Stu_Full_Name = $row["Stu_Full_Name"];
										$StudentName = $row["StudentName"];
										$Stu_Date_of_Admis = $row["Stu_Date_of_Admis"];
										$Stu_Sec = $row["Stu_Sec"];
?>
										<tr bgcolor="#00FFFF">
										<td><div align="center">
											<input type="hidden" name="chkregNo<?PHP echo $counter; ?>" value="<?PHP echo $Stu_Regist_No; ?>">
											<input name="chkadmID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $AdmID; ?>"></div></td>
										<td><div align="center"><a href="admission.php?subpg=Admission&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Regist_No; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Admission&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $AdmissionNo; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Admission&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Full_Name; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Admission&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo GetClassName($Stu_Class); ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Admission&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Date_of_Admis; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Admission&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Sec; ?></a></div></td>
									   </tr>
<?PHP
								 	}
							 	}
								$i=$i+1;
							}
?>
							 <tr>
							 	<td colspan="7"><p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></td>
							 </tr>
<?PHP
						}else{
							$counter_Adm = 0;							
							//Get Total Admitted Student
							$num_rows2 = 0;
							//$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster";
							//$result4  = mysql_query($query4,$conn) or die('Error, query failed');
							//$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
							//$num_rows2 = $row4['numrows'];
		
							if($rstart==0){
								$counter_Adm = $rstart;
							}else{
								$counter_Adm = $rstart-1;
							}
							$counter = 0;
							if($SubmitSearch == 'true'){
								//$Search_Key = $_POST['Search_Key'];
								$query3 = "select ID,Stu_Regist_No,AdmissionNo,Stu_Class,Stu_Full_Name,Stu_Date_of_Admis,Stu_Sec from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name LIMIT $rstart,$rend";
								$query4 = "select ID,Stu_Regist_No,AdmissionNo,Stu_Class,Stu_Full_Name,Stu_Date_of_Admis,Stu_Sec from tbstudentmaster where INSTR(Stu_Full_Name,'$Search_Key') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
								$result4 = mysql_query($query4,$conn);
							$num_rows2 = mysql_num_rows($result4);
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_Adm = $counter_Adm+1;
									$counter = $counter+1;
									$AdmID = $row["ID"];
									$Stu_Regist_No = $row["Stu_Regist_No"];
									$AdmissionNo = $row["AdmissionNo"];
									$Stu_Class = $row["Stu_Class"];
									$Stu_Full_Name = $row["Stu_Full_Name"];
									$StudentName = $row["StudentName"];
									$Stu_Date_of_Admis = $row["Stu_Date_of_Admis"];
									$Stu_Sec = $row["Stu_Sec"];
?>
									<tr bgcolor="#00FFFF">
										<td><div align="center">
											<input type="hidden" name="chkregNo<?PHP echo $counter; ?>" value="<?PHP echo $Stu_Regist_No; ?>">
											<input name="chkadmID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $AdmID; ?>"></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Regist_No; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $AdmissionNo; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Full_Name; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo GetClassName($Stu_Class); ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Date_of_Admis; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Sec; ?></a></div></td>
								  </tr>
<?PHP
								 }
							 }else{
								  ?>
							 
								<div style="color:#F00">No registration records found</div>
							   <?PHP 
							 }
?>
							 <tr>
							 	<td colspan="7"><p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p></td>
							 </tr>
<?PHP                
                      //}
							}else{
								$query3 = "select ID,Stu_Regist_No,AdmissionNo,Stu_Class,Stu_Full_Name,Stu_Date_of_Admis,Stu_Sec from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' LIMIT $rstart,$rend";
							    $query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result4  = mysql_query($query4,$conn) or die('Error, query failed');
							$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
							$num_rows2 = $row4['numrows'];
							
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_Adm = $counter_Adm+1;
									$counter = $counter+1;
									$AdmID = $row["ID"];
									$Stu_Regist_No = $row["Stu_Regist_No"];
									$AdmissionNo = $row["AdmissionNo"];
									$Stu_Class = $row["Stu_Class"];
									$Stu_Full_Name = $row["Stu_Full_Name"];
									$StudentName = $row["StudentName"];
									$Stu_Date_of_Admis = $row["Stu_Date_of_Admis"];
									$Stu_Sec = $row["Stu_Sec"];
?>
									<tr bgcolor="#00FFFF">
										<td><div align="center">
											<input type="hidden" name="chkregNo<?PHP echo $counter; ?>" value="<?PHP echo $Stu_Regist_No; ?>">
											<input name="chkadmID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $AdmID; ?>"></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Regist_No; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $AdmissionNo; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Full_Name; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo GetClassName($Stu_Class); ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Date_of_Admis; ?></a></div></td>
										<td><div align="center"><a href="admission.php?subpg=Student Admission Info Edit&adm_id=<?PHP echo $AdmID; ?>"><?PHP echo $Stu_Sec; ?></a></div></td>
								  </tr>
<?PHP
								 }
							 }
?>
							 <tr>
							 	<td colspan="7"><p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="admission.php?subpg=Edit Student Info&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></td>
							 </tr>
<?PHP                
                      }
						 }
?>
                        </table>					  </TD>
					</TR>
					<TR>
					 <TD colspan="2">
					 <div align="center">
						 <input type="hidden" name="TotalAdm" value="<?PHP echo $counter; ?>">
						 <input name="Admmaster_delete" type="submit" id="Admmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						 <input type="reset" name="Reset" value="Reset">
					</div>					 </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP           
         
          
		}elseif ($SubPage == "Student Admission Info Edit") {
?>
      <?PHP echo $errormsg.$RegNo; ?>
				<form name="form1" method="post" action="admission.php?subpg=Student Admission Info Edit">
				  <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					 <TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;">
						<table width="707" border="0" cellspacing="1" cellpadding="1" style="height:30px;">
						   <tr>
							 <td width="165"><div align="center" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Personal Information</strong></div></td>
							 <td width="177" bgcolor="#666666"><div align="center" class="style22">Other Details</div></td>
						   </tr>
						</table></div>
					  </TD>
					</TR>
					 <TR>
					  <TD colspan="4" valign="top"  align="left">
						<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;">
						Reg No:
						<input name="RegNo1" type="text" size="5" value="<?PHP echo $RegNo; ?>" disabled>
						<input type="hidden" name="RegNo" value="<?PHP echo $RegNo; ?>">
					  </TD>
					</TR>
					</TBODY>
					</TABLE>
				    <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="13%" valign="top"  align="left">Admission No </TD>
					  <TD width="27%" valign="top"  align="left"><input name="AdmNo" type="text"value="<?PHP echo $AdmNo; ?>" size="10" disabled/>
					    <input name="AdmNo2" type="text" value="<?PHP echo $AdmNo2; ?>" size="5" disabled/>
					    *</TD>
					  <TD width="22%" valign="top"  align="left">Student Name : </TD>
					  <TD width="38%" valign="top"  align="left"><input name="StudentName" type="text" value="<?PHP echo $StudentName; ?>" size="45" disabled/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Place of Birth</TD>
					  <TD width="27%" valign="top"  align="left"><input type="text" name="PlaceofBirth" value="<?PHP echo $PlaceofBirth; ?>"/></TD>
					  <TD width="22%" valign="top"  align="left">Nick Name : </TD>
					  <TD width="38%" valign="top"  align="left"><input name="NickName" type="text" value="<?PHP echo $NickName; ?>" size="45"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Age</TD>
					  <TD width="27%" valign="top"  align="left"><input name="Age" type="text" value="<?PHP echo $Age; ?>" size="5"/>
						Gender
  						<select name="OptGender">
    <?PHP
						if($OptGender == "M"){
							echo "<option value='M' selected='selected'>Male</option>";
						}else{
							echo "<option value='M'>Male</option>";
						}
						if($OptGender == "F"){
							echo "<option value='F' selected='selected'>Female</option>";
						}else{
							echo "<option value='F'>Female</option>";
						}
?>
  </select></TD>
					  <TD width="22%" valign="top"  align="left">Date of Birth  : </TD>
					  <TD width="38%" valign="top"  align="left">
					  <input name="DOB_Dy" type="text" size="2"  value="<?PHP echo $DOB_Dy; ?>" ONFOCUS="clearDefault(this)" style="background-color:#FFFF99">
					    /
					    <input name="DOB_Mth" type="text" size="2"  value="<?PHP echo $DOB_Mth; ?>" ONFOCUS="clearDefault(this)" style="background-color:#FFFF99">
					    /
					    <input name="DOB_Yr" type="text" size="2"  value="<?PHP echo $DOB_Yr; ?>" ONFOCUS="clearDefault(this)" style="background-color:#FFFF99">
						</TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Height : </TD>
					  <TD width="27%" valign="top"  align="left"><input name="Height" type="text" value="<?PHP echo $Height; ?>" size="5"/>
Tel No
  <input name="Tel_No" type="text" value="<?PHP echo $Tel_No; ?>" size="10" style="background-color:#FFFF99"/></TD>
					  <TD width="22%" valign="top"  align="left">Reason if DOB not Known </TD>
					  <TD width="38%" valign="top"  align="left"><input name="ReasonDOB" type="text" value="<?PHP echo $ReasonDOB; ?>" size="45"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Weight : </TD>
					  <TD width="27%" valign="top"  align="left"><input type="text" name="Weight" value="<?PHP echo $Weight; ?>"/></TD>
					  <TD width="22%" valign="top"  align="left">Last School Attended  : </TD>
					  <TD width="38%" valign="top"  align="left"><input name="LastSchool" type="text" value="<?PHP echo $LastSchool; ?>" size="45"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Religion : </TD>
					  <TD width="27%" valign="top"  align="left">
					  <select name="Religion">
							<option value="" selected="selected">&nbsp;</option>
<?PHP
							$query = "select ID,ReligionName from tbreligionmaster order by ReligionName";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result)) 
								{
									$ReligionName = $row["ReligionName"];
									if($Religion ==$ReligionName){
?>
										<option value="<?PHP echo $ReligionName; ?>" selected="selected"><?PHP echo $ReligionName; ?></option>
<?PHP
									}else{
?>
										<option value="<?PHP echo $ReligionName; ?>"><?PHP echo $ReligionName; ?></option>
<?PHP
									}
								}
							}
?>
					    </select>
						</TD>
					  <TD width="22%" valign="top"  align="left">School Leaving Date  : </TD>
					  <TD width="38%" valign="top"  align="left">
					  <input name="SchoolLeaving_day" type="text" size="2"  value="<?PHP echo $SchoolLeaving_day; ?>">
					    /
					    <input name="SchoolLeaving_mth" type="text" size="2"  value="<?PHP echo $SchoolLeaving_mth; ?>" ONFOCUS="clearDefault(this)">
					    /
					    <input name="SchoolLeaving_yr" type="text" size="2"  value="<?PHP echo $SchoolLeaving_yr; ?>" ONFOCUS="clearDefault(this)"> 
					    Cert No. 
					    <input name="CertNo" type="text" value="<?PHP echo $CertNo; ?>" size="5"/></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Blood Group: </TD>
					  <TD width="27%" valign="top"  align="left"><input type="text" name="BloodGroup" value="<?PHP echo $BloodGroup; ?>"/></TD>
					  <TD width="22%" valign="top"  align="left"></TD>
					  <TD width="38%" valign="top"  align="left" rowspan="3" >
						<table width="221" border="0" align="center" cellpadding="0" cellspacing="0">
						  
						  <tr>
						   
						   
						  </tr>
						  <tr>
						   
						   <td><input type="hidden" name="StuFilename" value="<?PHP echo $StuFilename; ?>"></td>
						   
						   <td><img src="images/spacer.gif" width="1" height="175" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   
						   <td><img src="images/spacer.gif" width="1" height="22" border="0" alt="" /></td>
						  </tr>
						</table>
					    
					    <p align="left">Email: 
					      <input name="StuEmail" type="text" size="35" value="<?PHP echo $StuEmail; ?>">
					    </p></TD>
					</TR>
					<TR>
					  <TD width="13%" valign="top"  align="left">Student Type: </TD>
					  <TD width="27%" valign="top"  align="left">
<?PHP
						if($Stutype =="Scholar"){
?>
					  		<input name="Stutype" type="radio" value="Scholar" checked="checked">Day Scholar  
<?PHP
						}else{
?>
							<input name="Stutype" type="radio" value="Scholar">Day Scholar
<?PHP
						}
?>
           <?PHP
						if($Stutype =="Hosteller"){
?>
					  		<input name="Stutype" type="radio" value="Hosteller" checked="checked">Hosteller  
<?PHP
						}else{
?>
							<input name="Stutype" type="radio" value="Hosteller">Hosteller
<?PHP
						}
?>
				     </TD>
					  <TD width="22%" valign="top"  align="left">

					  </TD>
					</TR>
					<TR>
					  <TD height="162" colspan="3"  align="left" valign="top"><label><br>
					    <br>
					  </label>
					  <table border="0" cellpadding="0" cellspacing="0" width="365">
						  <tr>
						   <td colspan="3"><img name="Untitled1_r1_c1" src="images/Untitled-1_r1_c12.jpg" width="365" height="15" border="0" id="Untitled1_r1_c1" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="15" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td rowspan="2"><img name="Untitled1_r2_c1" src="images/Untitled-1_r2_c1.jpg" width="9" height="235" border="0" id="Untitled1_r2_c1" alt="" /></td>
						   <td>
						   		<TABLE width="100%" style="WIDTH: 100%">
								<TBODY>
								<TR>
									<TD width="21%"  align="left" valign="top">Mr.</TD>
									<TD width="79%"  align="left" valign="top"><input name="GrMr" type="text" size="35" value="<?PHP echo $GrMr; ?>" style="background-color:#FFFF99"></TD>
								</TR>
								<TR>
									<TD width="21%"  align="left" valign="top">Mrs</TD>
									<TD width="79%"  align="left" valign="top"><label>
									  <input name="GrMrs" type="text" size="35" value="<?PHP echo $GrMrs; ?>" style="background-color:#FFFF99">
									</label></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Address</TD>
									<TD valign="top"  align="left"><textarea name="GrAddress" cols="35" rows="3" style="background-color:#FFFF99"><?PHP echo $GrAddress; ?></textarea></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">City</TD>
									<TD valign="top"  align="left">
									<select name="OptCity" style="background-color:#FFFF99">
									<option value="0" selected="selected">&nbsp;</option>
<?PHP
										$query = "select ID,CityName from mcity order by CityName";
										$result = mysql_query($query,$conn);
										$num_rows = mysql_num_rows($result);
										if ($num_rows <= 0 ) {
											echo "";
										}
										else 
										{
											while ($row = mysql_fetch_array($result)) 
											{
												$CityID = $row["ID"];
												$CityName = $row["CityName"];
												if($OptCity =="$CityID"){
?>
													<option value="<?PHP echo $CityID; ?>" selected="selected"><?PHP echo $CityName; ?></option>
<?PHP
												}else{
?>
													<option value="<?PHP echo $CityID; ?>"><?PHP echo $CityName; ?></option>
<?PHP
												}
											}
										}
?>
					     			 </select>
									State.
									 <SELECT name="State" style="width:80px;">
                                       <OPTION value="">State</OPTION>
<?PHP
								if($State != ""){
?>
                                       <option value="<?PHP echo $State; ?>" selected="selected"><?PHP echo $State; ?></option>
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
									<TD valign="top"  align="left">Telephone</TD>
									<TD valign="top"  align="left"><input name="GrTel" type="text" size="10" value="<?PHP echo $GrTel; ?>"> 
									  EmergencyNO 
									    <input name="EmergencyNo" type="text" size="10" value="<?PHP echo $Emergency; ?>"></TD>
								</TR>
								<TR>
									<TD valign="top"  align="left">Contact Person </TD>
									<TD valign="top"  align="left"><input name="ContactPerson" type="text" size="35" value="<?PHP echo $ContactPerson; ?>"></TD>
								</TR>
								</TBODY>
								</TABLE>
						   </td>
						   <td rowspan="2"><img name="Untitled1_r2_c3" src="images/Untitled-1_r2_c3.jpg" width="9" height="235" border="0" id="Untitled1_r2_c3" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="225" border="0" alt="" /></td>
						  </tr>
						  <tr>
						   <td><img name="Untitled1_r3_c2" src="images/Untitled-1_r3_c2.jpg" width="347" height="10" border="0" id="Untitled1_r3_c2" alt="" /></td>
						   <td><img src="images/spacer.gif" width="1" height="10" border="0" alt="" /></td>
						  </tr>
						</table>
					  </TD>
					</TR>
					<TR>
						<TD colspan="4" valign="top"  align="left">
							</TD>
					</TR>
					<TR>
						 <TD colspan="4">
						 <div align="center">
						 	<input type="hidden" name="SelRegNo" value="<?PHP echo $RegNo; ?>">
							 <input type="hidden" name="SelRegFee" value="<?PHP echo $RegFee; ?>">
							 <input type="hidden" name="SelAdmID" value="<?PHP echo $AdmID; ?>">
<?PHP 
							
?>
				
						</div>
						 </TD>
					</TR>
					</TBODY>
					</TABLE>
					<br><br>
				
				
			
				  
				  <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: blue; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Family Physician / Pediatrician</strong></div></TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">Name</TD>
					  <TD width="18%" valign="top"  align="left"><input type="text" name="PhyName" value="<?PHP echo $PhyName; ?>"/></TD>
					  <TD width="24%" valign="top"  align="left"><div align="right">Telephone No </div></TD>
					  <TD width="47%" valign="top"  align="left"><input name="PhyTel" type="text" value="<?PHP echo $PhyTel; ?>" size="15"/> 
					    Mobile No 
					      <input name="PhyMobile" type="text" value="<?PHP echo $PhyMobile; ?>" size="15"/></TD>
					</TR>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: blue; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Name and identity of the person accompany the child </strong></div></TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">  Name </TD>
					  <TD width="18%" valign="top"  align="left"><input type="text" name="AccomName" value="<?PHP echo $AccomName; ?>"/></TD>
					  <TD width="24%" valign="top"  align="left"><div align="right">Occupation</div></TD>
					  <TD width="47%" valign="top"  align="left"><input type="text" name="AccomOcc" value="<?PHP echo $AccomOcc; ?>"/></TD>
					</TR>
					<TR>
					  <TD colspan="3" valign="top"  align="left"><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: blue; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps"><strong>Other details </strong></div></TD>
					  <TD width="47%" valign="top"  align="left" rowspan="5">
					 
					  </TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">Bed Welter  : </TD>
					  <TD width="18%" valign="top"  align="left">
<?PHP
						if($BedWelter =="Yes")
						{
?>
					 		<input type="radio" name="BedWelter" value="Yes" checked="checked"/>Yes
<?PHP
						}else{
?>
							<input type="radio" name="BedWelter" value="Yes"/>Yes
<?PHP
						}
						if($BedWelter =="No")
						{
?>
					 		<input type="radio" name="BedWelter" value="No" checked="checked"/>No
<?PHP
						}else{
?>
							<input type="radio" name="BedWelter" value="No"/>No
<?PHP
						}
?>
						</TD>
					  <TD width="24%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">Communicable Diseases: </TD>
					  <TD width="24%" valign="top"  align="left">
<?PHP
						if($CommDiseases =="Yes")
						{
?>
					 		<input type="radio" name="CommDiseases" value="Yes" checked="checked"/>Yes
<?PHP
						}else{
?>
							<input type="radio" name="CommDiseases" value="Yes"/>Yes
<?PHP
						}
						if($CommDiseases =="No")
						{
?>
					 		<input type="radio" name="CommDiseases" value="No" checked="checked"/>No
<?PHP
						}else{
?>
							<input type="radio" name="CommDiseases" value="No"/>No
<?PHP
						}
?>
						</TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">Transportation Mode  : </TD>
					  <TD width="24%" valign="top"  align="left">				
						
						<select name="OptTransMode">
<?PHP
						if($OptTransMode == "FOOT"){
							echo "<option value='FOOT' selected='selected'>BY FOOT</option>";
						}else{
							echo "<option value='FOOT'>BY FOOT</option>";
						}
						if($OptTransMode == "CYCLE"){
							echo "<option value='CYCLE' selected='selected'>CYCLE</option>";
						}else{
							echo "<option value='CYCLE'>CYCLE</option>";
						}
						if($OptTransMode == "PARENTS"){
							echo "<option value='PARENTS' selected='selected'>PARENTS</option>";
						}else{
							echo "<option value='PARENTS'>PARENTS</option>";
						}
						if($OptTransMode == "VAN"){
							echo "<option value='VAN' selected='selected'>VAN</option>";
						}else{
							echo "<option value='VAN'>VAN</option>";
						}
						if($OptTransMode == "BUS"){
							echo "<option value='BUS' selected='selected'>LOCAL BUS</option>";
						}else{
							echo "<option value='BUS'>LOCAL BUS</option>";
						}
?>
					    </select>
					  </TD>
					</TR>
					<TR>
					  <TD width="11%" valign="top"  align="left">Remarks</TD>
					  <TD colspan="2" valign="top"  align="left"><textarea name="Remarks" cols="40" rows="5"><?PHP echo $Remarks; ?></textarea></TD>
					</TR>

					<TR>
					  <TD width="11%" valign="top"  align="left">Games</TD>
					  <TD width="18%" valign="top"  align="left"><input type="text" name="Games" value="<?PHP echo $Games; ?>"/></TD>
					  <TD width="24%" valign="top"  align="left"><div align="right">Extra Curricular Activities </div></TD>
					  <TD width="47%" valign="top"  align="left"><input name="Curricular" type="text" value="<?PHP echo $Curricular; ?>" size="55"/></TD>
					</TR>
					<TR>
						<TD colspan="3" valign="top"  align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <div>
							 <input type="hidden" name="SelRegNo" value="<?PHP echo $RegNo; ?>">
							 <input type="hidden" name="SelAdmNo" value="<?PHP echo $AdmissionNo; ?>">
						     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             <?PHP if($EditAdmissionInfo == true)
							 {?><input name="EditAdmn" type="submit" id="EditAdmn" value="Done With Student Info Edit"><?PHP 
							 }else{ ?>
                             <input name="SubmitAdmn" type="submit" id="SubmitAdmn" value="Submit"><?PHP }
							 $EditAdmissionInfo = false;?>
						     <input type="reset" name="Reset" value="Reset">
						</div>
						  </TD>
						 <TD>
						 
						 </TD>
					</TR>
                     <TR><TD colspan="3"> 
                            </TD></TR>
                            <TR><TD colspan="4" align="center"> <P></P>
                            <p></p> 
                            <p></p> 
                            <p></p><div align="left" style="FONT-WEIGHT: lighter; FONT-SIZE: 10pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Trebuchet MS, Arial, Verdana; HEIGHT: 23px; FONT-VARIANT: small-caps; BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total No. of student in <?PHP echo GetClassName($OptClass); ?> is  <?PHP echo $TotStu_In_Class; ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;Total No. of student in school is  <?PHP echo $TotStu_In_Sch; ?>
							</div>    </TD></TR>
					</TBODY>
					</TABLE>
					<br><br>
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
			<TD align="center"> Copyright � <?PHP echo date('Y'); ?> SkoolNet Manager. All right reserved.</TD>
		  </TR>
		</TABLE>	  
	  </TD>
	</TR>
</TBODY>
</TABLE> 	
</BODY></HTML>
