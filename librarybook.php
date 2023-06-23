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
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
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
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['Issmaster']))
	{
		$PageHasError = 0;
		$SelBookID = $_POST['SelBookID'];
		$SelAdmnNo = $_POST['SelAdmnNo'];
		$_POST['SubmitSearch'] = "Go";
		$ISSDate = date('Y')."-".date('m')."-".date('d');
		$due_day = $_POST['due_day'];
		$due_mth = $_POST['due_mth'];
		$due_yr = $_POST['due_yr'];
		$DUEDate = $due_yr."-".$due_mth."-".$due_day;
		
		if ($PageHasError == 0)
		{
			if ($_POST['Issmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from TbbookIssStd where bookID = '$SelBookID' And AdmnNo = '$SelAdmnNo'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>This book has been issued.</font>";
					$PageHasError = 1;
				}else {
					$fineCount = 0;
					$fineAmount = 0;
					$q = "Insert into TbbookIssStd(bookID,AdmnNo,IssDate,DueDate,TotalFine,FineRec,ReturnDate) Values ('$SelBookID','$SelAdmnNo','$ISSDate','$DUEDate','$fineCount','$fineAmount','')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				}
			}elseif ($_POST['Issmaster'] =="Update"){
				$fineCount = 0;
				$fineAmount = 0;
				$q = "update TbbookIssStd set DueDate = '$DUEDate' where bookID = '$SelBookID' And AdmnNo = '$SelAdmnNo'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
			}
		}
	}
	if(isset($_POST['IssEmployee']))
	{
		$PageHasError = 0;
		$SelBookID = $_POST['SelBookID'];
		$SelEmpNo = $_POST['SelEmpID'];
		$_POST['SubmitSearch'] = "Go";
		$ISSDate = date('Y')."-".date('m')."-".date('d');
		$due_day = $_POST['due_day'];
		$due_mth = $_POST['due_mth'];
		$due_yr = $_POST['due_yr'];
		$DUEDate = $due_yr."-".$due_mth."-".$due_day;
		
		if ($PageHasError == 0)
		{
			if ($_POST['IssEmployee'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbbookissemp where bookID = '$SelBookID' And EmpID = '$SelEmpNo'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>This book has been issued.</font>";
					$PageHasError = 1;
				}else {
					$fineCount = 0;
					$fineAmount = 0;
					$q = "Insert into tbbookissemp(bookID,EmpID,IssDate,DueDate,FineCount,Fine,ReturnDate) Values ('$SelBookID','$SelEmpNo','$ISSDate','$DUEDate','$fineCount','$fineAmount','')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
				}
			}elseif ($_POST['IssEmployee'] =="Update"){
				$fineCount = 0;
				$fineAmount = 0;
				$q = "update tbbookissemp set DueDate = '$DUEDate' where bookID = '$SelBookID' And EmpID = '$SelEmpNo'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
			}
		}
	}
	if(isset($_GET['book_id']))
	{
		$Selbookid = $_GET['book_id'];
		$_POST['isBookSearch'] = $_GET['isSrch'];
		$isBookSearch = $_GET['isSrch'];
		$_POST['OptAuthor'] = $_GET['ath'];
		$_POST['OptCat'] = $_GET['cat'];
		$_POST['OptSubCat'] = $_GET['scat'];
		$_POST['Title'] = $_GET['title'];
		$_POST['BookNo'] = $_GET['bkno'];
		
		$_POST['SubmitSearch'] = "Go";
		if($SubPage == "Book Issue to Employee"){
			$SelEmpNo = $_GET['EmpID'];
			$_POST['isEmpSearch'] = $_GET['src'];
			$_POST['OptDept'] = $_GET['dept'];
			$_POST['OptDesign'] =  $_GET['deig'];
			$_POST['EmpName'] = $_GET['empname'];
			
			$EmployeeName = "";
			$Department = "";
			
			//Get Other Details Book
			$query = "select Title,CatID,BookNo from tbbookmst where ID='$Selbookid'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$BookTitle = $dbarray['Title'];
			$CatID = $dbarray['CatID'];
			$AccessionNo = $dbarray['BookNo'];
			
			$query = "select CatName from tblibcategorymst where ID = '$CatID' order by CatName";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$Category = $dbarray['CatName'];
			
			//Check if the selected book has been issued to Employee
			$numrows = 0;
			$query4   = "SELECT COUNT(*) AS numrows FROM tbbookissemp Where bookID = '$Selbookid' and ReturnDate=''";
			$result4  = mysql_query($query4,$conn) or die('Error, query failed');
			$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
			$rsNo = $row4['numrows'];
			if($rsNo > 0){
				$query = "select EmpID,IssDate,DueDate from tbbookissemp where bookID = '$Selbookid' and ReturnDate=''";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$SelEmpNo = $dbarray['EmpID'];
				$issDate=explode ('-', $dbarray['IssDate']);
				$iss_Day = $issDate[2];
				$iss_mth = $issDate[1];
				$iss_yr = $arrDate[0];
			
				$dueDate=explode ('-', $dbarray['DueDate']);
				$due_day = $dueDate[2];
				$due_mth = $dueDate[1];
				$due_yr = $dueDate[0];
	
				//Get Other Details Student
				$query3 = "select EmpName,EmpDept from tbemployeemasters where ID ='$SelEmpNo'";
				$result = mysql_query($query3,$conn);
				$dbarray = mysql_fetch_array($result);
				$EmployeeName = $dbarray['EmpName'];
				$EmpDept = $dbarray['EmpDept'];
				
				$query = "select ID,DeptName from tbdepartments where ID = '$EmpDept' order by DeptName";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$Department = $dbarray['DeptName'];
			}else{
				//Get Other Details Student
				$query3 = "select EmpName,EmpDept from tbemployeemasters where ID ='$SelEmpNo'";
				$result = mysql_query($query3,$conn);
				$dbarray = mysql_fetch_array($result);
				$EmployeeName = $dbarray['EmpName'];
				$EmpDept = $dbarray['EmpDept'];
				
				$query = "select ID,DeptName from tbdepartments where ID = '$EmpDept' order by DeptName";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$Department = $dbarray['DeptName'];
			}
		}else{
			//Student Information
			$SelAdmNo = $_GET['AdmNo'];
			
			$_POST['isStudSearch'] = $_GET['src'];
			$_POST['OptClass'] = $_GET['cls'];
			
			$arrAdm=explode ('-', $_GET['adm']);
			$_POST['AdmNo'] = $arrAdm[0];
			$_POST['AdmNo2'] = $arrAdm[1];
			$AdmissionNo =  $_GET['adm'];
			$_POST['StudName'] = $_GET['nm'];
			
			$StudentName = "";
			$sClass = "";
			
			//Get Other Details Book
			$query = "select Title,CatID,BookNo from tbbookmst where ID='$Selbookid'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$BookTitle = $dbarray['Title'];
			$CatID = $dbarray['CatID'];
			$AccessionNo = $dbarray['BookNo'];
			
			$query = "select CatName from tblibcategorymst where ID = '$CatID' order by CatName";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$Category = $dbarray['CatName'];
			
			//Check if the selected book has been issued to student
			$numrows = 0;
			$query4   = "SELECT COUNT(*) AS numrows FROM tbbookissstd Where bookID = '$Selbookid' and ReturnDate=''";
			$result4  = mysql_query($query4,$conn) or die('Error, query failed');
			$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
			$rsNo = $row4['numrows'];
			if($rsNo > 0){
				$query = "select AdmnNo,IssDate,DueDate from tbbookissstd where bookID = '$Selbookid' and ReturnDate=''";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$SelAdmNo = $dbarray['AdmnNo'];
				$issDate=explode ('-', $dbarray['IssDate']);
				$iss_Day = $issDate[2];
				$iss_mth = $issDate[1];
				$iss_yr = $arrDate[0];
			
				$dueDate=explode ('-', $dbarray['DueDate']);
				$due_day = $dueDate[2];
				$due_mth = $dueDate[1];
				$due_yr = $dueDate[0];
	
				//Get Other Details Student
				$query3 = "select Stu_Full_Name,Stu_Class from tbstudentmaster where AdmissionNo ='$SelAdmNo'";
				$result = mysql_query($query3,$conn);
				$dbarray = mysql_fetch_array($result);
				$StudentName = $dbarray['Stu_Full_Name'];
				$ClassID = $dbarray['Stu_Class'];
				
				$query = "select ID,Class_Name from tbclassmaster where ID = '$ClassID' order by Class_Name";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$sClass = $dbarray['Class_Name'];
			}else{
				//Get Other Details Student
				$query3 = "select Stu_Full_Name,Stu_Class from tbstudentmaster where AdmissionNo ='$SelAdmNo'";
				$result = mysql_query($query3,$conn);
				$dbarray = mysql_fetch_array($result);
				$StudentName = $dbarray['Stu_Full_Name'];
				$ClassID = $dbarray['Stu_Class'];
				
				$query = "select ID,Class_Name from tbclassmaster where ID = '$ClassID' order by Class_Name";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$sClass = $dbarray['Class_Name'];
			}
		}
	}
	if(isset($_POST['SubmitSearch']))
	{
		$OptAuthor = $_POST['OptAuthor'];
		$OptCat = $_POST['OptCat'];
		$OptSubCat = $_POST['OptSubCat'];
		$Title = $_POST['Title'];
		$BookNo = $_POST['BookNo'];
		
		if($SubPage == "Book Issue to Employee"){
			$OptDept = $_POST['OptDept'];
			$OptDesign = $_POST['OptDesign'];
			$EmpName = $_POST['EmpName'];
		}else{
			$OptClass = $_POST['OptClass'];
			$AdmNo = $_POST['AdmNo'];
			$AdmNo2 = $_POST['AdmNo2'];
			$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
			$StudName = $_POST['StudName'];
		}
	}
	if(isset($_POST['isBookSearch']))
	{
		$OptAuthor = $_POST['OptAuthor'];
		$OptCat = $_POST['OptCat'];
		$OptSubCat = $_POST['OptSubCat'];
		$Title = $_POST['Title'];
		$BookNo = $_POST['BookNo'];
		if($SubPage == "Book Issue to Employee"){
			$OptDept = $_POST['OptDept'];
			$OptDesign = $_POST['OptDesign'];
			$EmpName = $_POST['EmpName'];
		}else{
			$OptClass = $_POST['OptClass'];
			$AdmNo = $_POST['AdmNo'];
			$AdmNo2 = $_POST['AdmNo2'];
			$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
			$StudName = $_POST['StudName'];
		}
		
		$isBookSearch = $_POST['isBookSearch'];
		if($isBookSearch =="All"){
			$chkAll = "checked='checked'";
			$lockAuthor = "disabled='disabled'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockTitle = "disabled='disabled'";
			$lockAccNo = "disabled='disabled'";
		}elseif($isBookSearch =="Author"){
			$lockAll = "disabled='disabled'";
			$chkAuthor = "checked='checked'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockTitle = "disabled='disabled'";
			$lockAccNo = "disabled='disabled'";
		}elseif($isBookSearch =="Category"){
			$lockAll = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$chkCat = "checked='checked'";
			$lockSubCat = "disabled='disabled'";
			$lockTitle = "disabled='disabled'";
			$lockAccNo = "disabled='disabled'";
		}elseif($isBookSearch =="SubCategory"){
			$lockAll = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockCat = "disabled='disabled'";
			$chkSubCat = "checked='checked'";
			$lockTitle = "disabled='disabled'";
			$lockAccNo = "disabled='disabled'";
		}elseif($isBookSearch =="Title"){
			$lockAll = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$chkTitle = "checked='checked'";
			$lockAccNo = "disabled='disabled'";
		}elseif($isBookSearch =="AccessionNo"){
			$lockAll = "disabled='disabled'";
			$lockAuthor = "disabled='disabled'";
			$lockCat = "disabled='disabled'";
			$lockSubCat = "disabled='disabled'";
			$lockTitle = "disabled='disabled'";
			$chkAccNo = "checked='checked'";
		}
	}
	if(isset($_POST['isStudSearch']))
	{	
		$OptAuthor = $_POST['OptAuthor'];
		$OptCat = $_POST['OptCat'];
		$OptSubCat = $_POST['OptSubCat'];
		$Title = $_POST['Title'];
		$BookNo = $_POST['BookNo'];
		
		$OptClass = $_POST['OptClass'];
		$AdmNo = $_POST['AdmNo'];
		$AdmNo2 = $_POST['AdmNo2'];
		$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
		$StudName = $_POST['StudName'];
		
		$isStudSearch = $_POST['isStudSearch'];
		if($isStudSearch =="All"){
			$chkAlls = "checked='checked'";
			$lockclass = "disabled='disabled'";
			$lockAdmnNo = "disabled='disabled'";
			$lockName = "disabled='disabled'";
		}elseif($isStudSearch =="Class"){
			$chkClass = "checked='checked'";
			$lockAdmnNo = "disabled='disabled'";
			$lockName = "disabled='disabled'";
		}elseif($isStudSearch =="AdmnNo"){
			$lockclass = "disabled='disabled'";
			$chkAdmnNo = "checked='checked'";
			$lockName = "disabled='disabled'";
		}elseif($isStudSearch =="Name"){
			$lockclass = "disabled='disabled'";
			$lockAdmnNo = "disabled='disabled'";
			$chkName = "checked='checked'";
		}
	}
	if(isset($_POST['isEmpSearch']))
	{	
		$OptAuthor = $_POST['OptAuthor'];
		$OptCat = $_POST['OptCat'];
		$OptSubCat = $_POST['OptSubCat'];
		$Title = $_POST['Title'];
		$BookNo = $_POST['BookNo'];

		$OptDept = $_POST['OptDept'];
		$OptDesign = $_POST['OptDesign'];
		$EmpName = $_POST['EmpName'];

		
		$isEmpSearch = $_POST['isEmpSearch'];
		if($isEmpSearch =="All"){
			$chkAlls = "checked='checked'";
			$lockdept = "disabled='disabled'";
			$lockDesig = "disabled='disabled'";
			$lockName = "disabled='disabled'";
		}elseif($isEmpSearch =="Department"){
			$chkDept = "checked='checked'";
			$lockDesig = "disabled='disabled'";
			$lockName = "disabled='disabled'";
		}elseif($isEmpSearch =="Designation"){
			$lockdept = "disabled='disabled'";
			$chkDesig = "checked='checked'";
			$lockName = "disabled='disabled'";
		}elseif($isEmpSearch =="Name"){
			$lockdept = "disabled='disabled'";
			$lockDesig = "disabled='disabled'";
			$chkName = "checked='checked'";
		}
	}
	if(isset($_POST['GetIssueBook']))
	{
		$AccessionNo = $_POST['AccessionNo'];
		if(!$_POST['AccessionNo']){
			$errormsg = "<font color = red size = 1>Accession No is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$Amount = 0;
			$query = "select ID,Title,CatID,SubCatID from tbbookmst where BookNo = '$AccessionNo'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$SelBookNo = $dbarray['ID'];
			$BookTitle = $dbarray['Title'];
			$CatID = $dbarray['CatID'];
			$SubCatID = $dbarray['SubCatID'];
			if($SelBookNo ==""){
				$errormsg = "<font color = red size = 1>Invalid Accession No.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{	
				$query = "select CatName from tblibcategorymst where ID = '$CatID' order by CatName";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$Category = $dbarray['CatName'];
						
				$query = "select SubCatName from tblibsubcatmst where ID = '$SubCatID' order by SubCatName";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$subCategory = $dbarray['SubCatName'];
				
				$query = "select * from tbbookissstd where bookID = '$SelBookNo'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$SelAdmNo = $dbarray['AdmnNo'];
				if($SelAdmNo !=""){
					$issDate=explode ('-', $dbarray['IssDate']);
					$iss_Day = $issDate[2];
					$iss_mth = $issDate[1];
					$iss_yr = $arrDate[0];
				
					$dueDate=explode ('-', $dbarray['DueDate']);
					$due_day = $dueDate[2];
					$due_mth = $dueDate[1];
					$due_yr = $dueDate[0];
					
					$TodaysDate = date('Y')."-".date('m')."-".date('d');
					$arrDateList = date_range($dbarray['DueDate'],$TodaysDate);
					$i=0;
					$FineCounter = 0;
					while(isset($arrDateList[$i])){
						$FineCounter = $FineCounter +1;
						$i=$i+1;
					}
					//Calculate Fine Amount
					$query3 = "select FinePerDay from TbLibFineMst where Finefrom <='$FineCounter' and Fineto >= '$FineCounter'";
					$result = mysql_query($query3,$conn);
					$dbarray = mysql_fetch_array($result);
					$FinePerDay = $dbarray['FinePerDay'];
					
					$TotalFine = $FinePerDay *$FineCounter;
					$TotalFine = number_format($TotalFine,2);
					
					//Get Other Details Student
					$query3 = "select Stu_Full_Name,Stu_Class,Stu_Sec from tbstudentmaster where AdmissionNo ='$SelAdmNo'";
					$result = mysql_query($query3,$conn);
					$dbarray = mysql_fetch_array($result);
					$IssuedTo = $dbarray['Stu_Full_Name'];
					$ClassID = $dbarray['Stu_Class'];
					$Section = $dbarray['Stu_Sec'];
					
					$query = "select ID,Class_Name from tbclassmaster where ID = '$ClassID' order by Class_Name";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$ClassName = $dbarray['Class_Name'];
				}
			}
		}
		
	}
	if(isset($_POST['retStudent']))
	{
		$AccessionNo = $_POST['AccessionNo'];
		$AmountRec = $_POST['Amount'];
		if(!$_POST['AccessionNo']){
			$errormsg = "<font color = red size = 1>Accession No is empty.</font>";
			$PageHasError = 1;
		}
		if(!is_numeric($AmountRec)){
			$errormsg = "<font color = red size = 1>Invalid Amount Received.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$AmountRec = $_POST['Amount'];
			$query = "select ID from tbbookmst where BookNo = '$AccessionNo'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$SelBookNo = $dbarray['ID'];
			if($SelBookNo ==""){
				$errormsg = "<font color = red size = 1>Invalid Accession No.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{	
				$RETDate = $_POST['ret_Yr']."-".$_POST['ret_Mth']."-".$_POST['ret_Day'];
				$ret_Day = $_POST['ret_Day'];
				$ret_Mth = $_POST['ret_Mth'];
				$ret_Yr = $_POST['ret_Yr'];
				
				$query = "select * from tbbookissstd where bookID = '$SelBookNo' and ReturnDate =''";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$issueID = $dbarray['ID'];
				$SelAdmNo = $dbarray['AdmnNo'];
				$arrDateList = date_range($dbarray['DueDate'],$RETDate);
				$i=0;
				$FineCounter = 0;
				while(isset($arrDateList[$i])){
					$FineCounter = $FineCounter +1;
					$i=$i+1;
				}
				//Calculate Fine Amount
				$query3 = "select FinePerDay from TbLibFineMst where Finefrom <='$FineCounter' and Fineto >= '$FineCounter'";
				$result = mysql_query($query3,$conn);
				$dbarray = mysql_fetch_array($result);
				$FinePerDay = $dbarray['FinePerDay'];
				
				$TotalFine = $FinePerDay *$FineCounter;
				
				$q = "update tbbookissstd set TotalFine='$TotalFine',FineRec='$AmountRec',ReturnDate='$RETDate' where ID = '$issueID'";
				//echo $q;
				$result = mysql_query($q,$conn);
				
				
				
				//echo $q;
				
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
			}
		}
	}
	if(isset($_POST['GetIssueBookEmp']))
	{
		$AccessionNo = $_POST['AccessionNo'];
		if(!$_POST['AccessionNo']){
			$errormsg = "<font color = red size = 1>Accession No is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$Amount = 0;
			$query = "select ID,Title,CatID,SubCatID from tbbookmst where BookNo = '$AccessionNo'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$SelBookNo = $dbarray['ID'];
			$BookTitle = $dbarray['Title'];
			$CatID = $dbarray['CatID'];
			$SubCatID = $dbarray['SubCatID'];
			if($SelBookNo ==""){
				$errormsg = "<font color = red size = 1>Invalid Accession No.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{	
				$query = "select CatName from tblibcategorymst where ID = '$CatID' order by CatName";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$Category = $dbarray['CatName'];
						
				$query = "select SubCatName from tblibsubcatmst where ID = '$SubCatID' order by SubCatName";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$subCategory = $dbarray['SubCatName'];
				
				$query = "select * from tbbookissemp where bookID = '$SelBookNo' and ReturnDate=''";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$SelEmpID = $dbarray['EmpID'];
				
				$issDate=explode ('-', $dbarray['IssDate']);
				$iss_Day = $issDate[2];
				$iss_mth = $issDate[1];
				$iss_yr = $arrDate[0];
			
				$dueDate=explode ('-', $dbarray['DueDate']);
				$due_day = $dueDate[2];
				$due_mth = $dueDate[1];
				$due_yr = $dueDate[0];
				
				
				//Get Other Details Student
				$query3 = "select EmpName,EmpDept,EmpDesig from tbemployeemasters where ID ='$SelEmpID'";
				$result = mysql_query($query3,$conn);
				$dbarray = mysql_fetch_array($result);
				$IssuedTo = $dbarray['EmpName'];
				$EmpDept = $dbarray['EmpDept'];
				$EmpDesig = $dbarray['EmpDesig'];
				
				$query2 = "select DeptName from tbdepartments where ID='$EmpDept'";
				$result2 = mysql_query($query2,$conn);
				$dbarray2 = mysql_fetch_array($result2);
				$Department  = $dbarray2['DeptName'];
				
				$query2 = "select DesignName from tbdesignations where ID='$EmpDesig'";
				$result2 = mysql_query($query2,$conn);
				$dbarray2 = mysql_fetch_array($result2);
				$Designation  = $dbarray2['DesignName'];
			}
		}
		
	}
	if(isset($_POST['retEmployee']))
	{
		$AccessionNo = $_POST['AccessionNo'];
		if(!$_POST['AccessionNo']){
			$errormsg = "<font color = red size = 1>Accession No is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$query = "select ID from tbbookmst where BookNo = '$AccessionNo'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$SelBookNo = $dbarray['ID'];
			if($SelBookNo ==""){
				$errormsg = "<font color = red size = 1>Invalid Accession No.</font>";
				$PageHasError = 1;
			}
			if ($PageHasError == 0)
			{	
				$RETDate = $_POST['ret_Yr']."-".$_POST['ret_Mth']."-".$_POST['ret_Day'];
				$ret_Day = $_POST['ret_Day'];
				$ret_Mth = $_POST['ret_Mth'];
				$ret_Yr = $_POST['ret_Yr'];
				
				$query = "select * from tbbookissemp where bookID = '$SelBookNo' and ReturnDate=''";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$SelbookID = $dbarray['bookID'];
				$SelEmpID = $dbarray['EmpID'];
				
				$issDate=explode ('-', $dbarray['IssDate']);
				$iss_Day = $issDate[2];
				$iss_mth = $issDate[1];
				$iss_yr = $arrDate[0];
			
				$dueDate=explode ('-', $dbarray['DueDate']);
				$due_day = $dueDate[2];
				$due_mth = $dueDate[1];
				$due_yr = $dueDate[0];

				$q = "update tbbookissemp set ReturnDate='$RETDate' where bookID = '$SelbookID' And EmpID = '$SelEmpID'";
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
width:350px;
}

.b{
overflow:auto;
width:auto;
height:250px;
}
.b2{
overflow:auto;
width:auto;
height:250px;
}
.a thead tr{
position:absolute;
top:0px;
}
.style25 {color: #FFFFFF; font-weight: bold; }
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
					  <TD height="55" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Book Issued to Student Log") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarybook.php?subpg=Book Issued to Student Log">
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
				<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="34%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <strong>Search Book </strong>
					    <TABLE width="100%" align="center" cellpadding="5">
							<TBODY>
							<TR>
							  <TD width="47%"  align="left">
							  		<input name="isBookSearch" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkAll; ?>> All
							   </TD>
							  <TD width="53%"  align="left" valign="top">&nbsp;</TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="Author" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkAuthor; ?>> Author</TD>
							  <TD width="53%"  align="left" valign="top">
							  <select name="OptAuthor" style="width:100px;" <?PHP echo $lockAuthor; ?>>
                                <option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								$query = "select ID,AuthorName from tbauthormaster order by AuthorName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$AuthID = $row["ID"];
										$AuthorName = $row["AuthorName"];
										
										if($OptAuthor =="$AuthID"){
?>
                                			<option value="<?PHP echo $AuthID; ?>" selected="selected"><?PHP echo $AuthorName; ?></option>
<?PHP
										}else{
?>
                                			<option value="<?PHP echo $AuthID; ?>"><?PHP echo $AuthorName; ?></option>
 <?PHP
										}
									}
								}
?>
                              </select></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="Category" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkCat; ?>> Category</TD>
							  <TD width="53%"  align="left" valign="top">
							  <select name="OptCat" style="width:100px;" <?PHP echo $lockCat; ?>>
                                <option value="0" selected="selected"></option>
<?PHP
									$counter = 0;
									$query = "select ID,CatName from tblibcategorymst order by CatName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$counter = $counter+1;
											$CatID = $row["ID"];
											$CatName = $row["CatName"];
											
											if($OptCat =="$CatID"){
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
                              </select></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="SubCategory" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkSubCat; ?>>
							    Sub Category</TD>
							  <TD width="53%"  align="left" valign="top">
							  <select name="OptSubCat" style="width:100px;" <?PHP echo $lockSubCat; ?>>
                                <option value="0" selected="selected"></option>
<?PHP
									$counter = 0;
									$query = "select * from tblibsubcatmst order by SubCatName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$counter = $counter+1;
											$sCatID = $row["ID"];
											$sCatName = $row["SubCatName"];
								
											if($OptSubCat =="$sCatID"){
?>
                                				<option value="<?PHP echo $sCatID; ?>" selected="selected"><?PHP echo $sCatName; ?></option>
<?PHP
											}else{
?>
                                				<option value="<?PHP echo $sCatID; ?>"><?PHP echo $sCatName; ?></option>
<?PHP
											}
										}
									}
?>
                              </select></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="Title" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkTitle; ?>>Book Title</TD>
							  <TD width="53%"  align="left" valign="top">
							  <input name="Title" type="text" size="16" value="<?PHP echo $Title; ?>" <?PHP echo $lockTitle; ?>></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="AccessionNo" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkAccNo; ?>> Acc. No</TD>
							  <TD width="53%"  align="left" valign="top">
							  <input name="BookNo" type="text"value="<?PHP echo $BookNo; ?>" size="16" <?PHP echo $lockAccNo; ?>/></TD>
							</TR>
							<TR>
							    <TD colspan="2"  align="left">
							      <div align="right">
							    	  List of Available Books 
							    	    <input name="SubmitSearch" type="submit" id="Search" value="Go">
							      </div>
							   </TD>
							</TR>
							<TR bgcolor="#999999">
							  <TD width="47%"  align="left"><span class="style25">Acc. No</span></TD>
							  <TD width="53%"  align="left" valign="top"><span class="style25">Book Title</span></TD>
							</TR>
<?PHP
							$counter_reg = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$isBookSearch = $_POST['isBookSearch'];
								if($isBookSearch == "All"){
									$query3 = "select ID,Title,BookNo from tbbookmst where ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
								}elseif($isBookSearch == "Author"){
									$OptAuthor = $_POST['OptAuthor'];
									$query3 = "select ID,Title,BookNo from tbbookmst where ID IN (Select BookID from tbbookauthorlist where AuthorID = '$OptAuthor') And ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
								}elseif($isBookSearch == "Category"){
									$OptCat = $_POST['OptCat'];
									$query3 = "select ID,Title,BookNo from tbbookmst where CatID = '$OptCat' And ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
								}elseif($isBookSearch == "SubCategory"){
									$OptSubCat = $_POST['OptSubCat'];
									$query3 = "select ID,Title,BookNo from tbbookmst where SubCatID = '$OptSubCat' And ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
								}elseif($isBookSearch == "Title"){
									$Title = $_POST['Title'];
									$query3 = "select ID,Title,BookNo from tbbookmst where INSTR(Title,'$Title') And ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
								}elseif($isBookSearch == "AccessionNo"){
									$selBookNo = $_POST['BookNo'];
									$query3 = "select ID,Title,BookNo from tbbookmst where INSTR(BookNo,'$selBookNo') And ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
								}else{
									$query3 = "select ID,Title,BookNo from tbbookmst where ID = '0' And ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
								}
							}else{
								$query3 = "select ID,Title,BookNo from tbbookmst where ID = '0' And ID NOT IN (select bookID from TbbookIssEmp where ReturnDate='') order by ID";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$BookNo = $row["BookNo"];
									
									//Check if the selected book has been issued to student
									$numrows = 0;
									$query4   = "SELECT COUNT(*) AS numrows FROM tbbookissstd Where bookID = '$bookID' And ReturnDate=''";
									$result4  = mysql_query($query4,$conn) or die('Error, query failed');
									$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
									$rsNo = $row4['numrows'];
									if($rsNo > 0){
?>
										<TR bgcolor="#FFFFCC">
										  <TD width="47%"  align="left"><a href="librarybook.php?subpg=Book Issue to Student&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&AdmNo=<?PHP echo $SelAdmNo; ?>&src=<?PHP echo $isStudSearch; ?>&cls=<?PHP echo $OptClass; ?>&adm=<?PHP echo $AdmissionNo; ?>&nm=<?PHP echo $StudName; ?>" onClick="if (!confirm('This book has been issued to student, would you like to edit the record!')) {return false;}"><?PHP echo $BookNo; ?></a></TD>
										  <TD width="53%"  align="left" valign="top"><a href="librarybook.php?subpg=Book Issue to Student&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&AdmNo=<?PHP echo $SelAdmNo; ?>&src=<?PHP echo $isStudSearch; ?>&cls=<?PHP echo $OptClass; ?>&adm=<?PHP echo $AdmissionNo; ?>&nm=<?PHP echo $StudName; ?>" onClick="if (!confirm('This book has been issued to student, would you like to edit the record!')) {return false;}"><?PHP echo $Title; ?></a></TD>
										</TR>
<?PHP
									}else{
?>
										<TR bgcolor="#E8E8E8">
										  <TD width="47%"  align="left"><a href="librarybook.php?subpg=Book Issue to Student&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&AdmNo=<?PHP echo $SelAdmNo; ?>&src=<?PHP echo $isStudSearch; ?>&cls=<?PHP echo $OptClass; ?>&adm=<?PHP echo $AdmissionNo; ?>&nm=<?PHP echo $StudName; ?>"><?PHP echo $BookNo; ?></a></TD>
										  <TD width="53%"  align="left" valign="top"><a href="librarybook.php?subpg=Book Issue to Student&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&AdmNo=<?PHP echo $SelAdmNo; ?>&src=<?PHP echo $isStudSearch; ?>&cls=<?PHP echo $OptClass; ?>&adm=<?PHP echo $AdmissionNo; ?>&nm=<?PHP echo $StudName; ?>"><?PHP echo $Title; ?></a></TD>
										</TR>
<?PHP
									}
								 }
							 }
?>
						</TBODY>
						</TABLE>
						</TD>
					  <TD width="34%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <strong>Search Student</strong>
					    <TABLE width="100%" align="center" cellpadding="5">
                        <TBODY>
                          <TR>
                            <TD width="36%"  align="left"><input name="isStudSearch" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isStudSearch\',\'\')', 0)" <?PHP echo $chkAlls; ?>>
                              All</TD>
                            <TD width="64%"  align="left" valign="top">&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD width="36%"  align="left"><input name="isStudSearch" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'isStudSearch\',\'\')', 0)" <?PHP echo $chkClass; ?>>
                              Class</TD>
                            <TD width="64%"  align="left" valign="top"><select name="OptClass" <?PHP echo $lockclass; ?>>
                              <option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm') order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster order by Class_Name";
								}
								
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
                            </select></TD>
                          </TR>
                          <TR>
                            <TD width="36%"  align="left"><input name="isStudSearch" type="radio" value="AdmnNo" onClick="javascript:setTimeout('__doPostBack(\'isStudSearch\',\'\')', 0)" <?PHP echo $chkAdmnNo; ?>>
                              Admn No </TD>
                            <TD width="64%"  align="left" valign="top">
							  <input name="AdmNo" type="text"value="<?PHP echo $AdmNo; ?>" size="7" <?PHP echo $lockAdmnNo; ?>/>
                              <input name="AdmNo2" type="text" value="<?PHP echo $AdmNo2; ?>" size="5" <?PHP echo $lockAdmnNo; ?>/></TD>
                          </TR>
                          <TR>
                            <TD width="36%"  align="left"><input name="isStudSearch" type="radio" value="Name" onClick="javascript:setTimeout('__doPostBack(\'isStudSearch\',\'\')', 0)" <?PHP echo $chkName; ?>>
                            Name  </TD>
                            <TD width="64%"  align="left" valign="top">
							 <input name="StudName" type="text" size="16" value="<?PHP echo $StudName; ?>" <?PHP echo $lockName; ?>>
                              <input name="SubmitSearch" type="submit" id="SubmitSearch" value="Go"></TD>
                          </TR>

                          <TR>
                            <TD colspan="2"  align="left">&nbsp;</TD>
                          </TR>
                          <TR bgcolor="#999999">
                            <TD width="36%"  align="left"><span class="style25">Admn No. </span></TD>
                            <TD width="64%"  align="left" valign="top"><span class="style25">Student Name </span></TD>
                          </TR>
<?PHP
							$counter_reg = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$isStudSearch = $_POST['isStudSearch'];
								if($isStudSearch == "All"){
									$query3 = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class !='' order by Stu_Full_Name";
								}elseif($isStudSearch == "Class"){
									$OptClass = $_POST['OptClass'];
									$query3 = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class ='$OptClass' order by Stu_Full_Name";
								}elseif($isStudSearch == "AdmnNo"){
									$query3 = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class !='' And AdmissionNo = '$AdmissionNo' order by Stu_Full_Name";
								}elseif($isStudSearch == "Name"){
									$StudName = $_POST['StudName'];
									$query3 = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class !='' And INSTR(Stu_Full_Name,'$StudName') order by Stu_Full_Name";
								}else{
									$query3 = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class !='' And ID = '0' order by Stu_Full_Name";
								}
							}else{
								$query3 = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class !='' And ID = '0' order by Stu_Full_Name";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$Stu_Full_Name = $row["Stu_Full_Name"];
									$sAdmissionNo = $row["AdmissionNo"];
?>
									<TR bgcolor="#E8E8E8">
									  <TD width="36%"  align="left"><a href="librarybook.php?subpg=Book Issue to Student&book_id=<?PHP echo $Selbookid; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&AdmNo=<?PHP echo $sAdmissionNo; ?>&src=<?PHP echo $isStudSearch; ?>&cls=<?PHP echo $OptClass; ?>&adm=<?PHP echo $AdmissionNo; ?>&nm=<?PHP echo $StudName; ?>"><?PHP echo $sAdmissionNo; ?></a></TD>
									  <TD width="64%"  align="left" valign="top"><a href="librarybook.php?subpg=Book Issue to Student&book_id=<?PHP echo $Selbookid; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&AdmNo=<?PHP echo $sAdmissionNo; ?>&src=<?PHP echo $isStudSearch; ?>&cls=<?PHP echo $OptClass; ?>&adm=<?PHP echo $AdmissionNo; ?>&nm=<?PHP echo $StudName; ?>"><?PHP echo $Stu_Full_Name; ?></a></TD>
									</TR>
<?PHP
								 }
							 }
?>
                        </TBODY>
                      </TABLE></TD>
					  <TD width="32%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <strong>Other Details					    </strong>
					    <TABLE width="100%" align="center" cellpadding="5">
                        <TBODY>
                          <TR>
                            <TD width="32%"  align="left"> Acc. No</TD>
                            <TD width="68%"  align="left" valign="top"><input name="AccessionNo" type="text" size="20" value="<?PHP echo $AccessionNo; ?>" disabled="disabled"></TD>
                          </TR>
                          <TR>
                            <TD width="32%"  align="left"> Book Title                             </TD>
                            <TD width="68%"  align="left" valign="top"><input name="BookTitle" type="text" size="20" value="<?PHP echo $BookTitle; ?>" disabled="disabled"></TD>
                          </TR>
                          <TR>
                            <TD width="32%"  align="left">  Category                             </TD>
                            <TD width="68%"  align="left" valign="top"><input name="Category" type="text"value="<?PHP echo $Category; ?>" size="20" disabled="disabled"/></TD>
                          </TR>
                          <TR>
                            <TD width="32%"  align="left">Name </TD>
                            <TD width="68%"  align="left" valign="top"><input name="StudentName" type="text" size="20" value="<?PHP echo $StudentName; ?>" disabled="disabled"></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left">Class </TD>
                            <TD width="68%"  align="left" valign="top"><input name="sClass" type="text" size="20" value="<?PHP echo $sClass; ?>" disabled="disabled"></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left">Issue Date </TD>
                            <TD width="68%"  align="left" valign="top">
							<select name="iss_Day" style="width:40px" disabled="disabled">
                              <option value="0">Dy</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($iss_Day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurDay == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                            </select>
                              <select name="iss_mth" style=" width:40px" disabled="disabled">
                                <option value="0">Mth</option>
 <?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $iss_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}elseif($CurMth == $i){
										if($Found=="False"){
											echo "<option value=$i selected=selected>$i</option>";
										}
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                              </select>
                              <select name="iss_yr" style=" width:55px" disabled="disabled">
                                <option value="0">Yr</option>
<?PHP
									$CurYear = date('Y');
									$Found="False";
									for($i=2009; $i<=$CurYear; $i++){
										if($iss_yr == $i){
											echo "<option value=$i selected=selected>$i</option>";
											$Found="True";
										}elseif($CurYear == $i){
											if($Found=="False"){
												echo "<option value=$i selected=selected>$i</option>";
											}
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
                              </select></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left"> Due Date                             </TD>
                            <TD width="68%"  align="left" valign="top">
							<select name="due_day" style=" width:40px">
                              <option value="0">Dy</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($due_day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurDay == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                            </select>
                              <select name="due_mth" style=" width:40px">
                                <option value="0">Mth</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $due_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}elseif($CurMth == $i){
										if($Found=="False"){
											echo "<option value=$i selected=selected>$i</option>";
										}
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                              </select>
                              <select name="due_yr" style=" width:55px">
                                <option value="0">Yr</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($due_yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurYear == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                              </select></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left">&nbsp;</TD>
                            <TD width="68%"  align="left" valign="top">&nbsp;</TD>
                          </TR>
                        </TBODY>
                      </TABLE></TD>
					</TR>
					<TR>
						 <TD colspan="3">
						  <div align="center"><br>
							 <input type="hidden" name="SelBookID" value="<?PHP echo $Selbookid; ?>">
							 <input type="hidden" name="SelAdmnNo" value="<?PHP echo $SelAdmNo; ?>">
							 <input name="Issmaster" type="submit" id="Issmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="Issmaster" type="submit" id="Issmaster" value="Update">
						  </div>						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Book Issued to Employee") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarybook.php?subpg=Book Issued to Employee">
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
				<TABLE width="100%" style="WIDTH: 100%">
					<TBODY>
					<TR>
					  <TD width="48%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <strong>Search Book </strong>
					    <TABLE width="97%" align="center" cellpadding="5">
							<TBODY>
							<TR>
							  <TD width="47%"  align="left">
							  		<input name="isBookSearch" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkAll; ?>> All
							   </TD>
							  <TD width="53%"  align="left" valign="top">&nbsp;</TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="Author" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkAuthor; ?>> Author</TD>
							  <TD width="53%"  align="left" valign="top">
							  <select name="OptAuthor" style="width:140px;" <?PHP echo $lockAuthor; ?>>
                                <option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								$query = "select ID,AuthorName from tbauthormaster order by AuthorName";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$AuthID = $row["ID"];
										$AuthorName = $row["AuthorName"];
										
										if($OptAuthor =="$AuthID"){
?>
                                			<option value="<?PHP echo $AuthID; ?>" selected="selected"><?PHP echo $AuthorName; ?></option>
<?PHP
										}else{
?>
                                			<option value="<?PHP echo $AuthID; ?>"><?PHP echo $AuthorName; ?></option>
 <?PHP
										}
									}
								}
?>
                              </select></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="Category" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkCat; ?>> Category</TD>
							  <TD width="53%"  align="left" valign="top">
							  <select name="OptCat" style="width:140px;" <?PHP echo $lockCat; ?>>
                                <option value="0" selected="selected"></option>
<?PHP
									$counter = 0;
									$query = "select ID,CatName from tblibcategorymst order by CatName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$counter = $counter+1;
											$CatID = $row["ID"];
											$CatName = $row["CatName"];
											
											if($OptCat =="$CatID"){
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
                              </select></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="SubCategory" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkSubCat; ?>>
							    Sub Category</TD>
							  <TD width="53%"  align="left" valign="top">
							  <select name="OptSubCat" style="width:140px;" <?PHP echo $lockSubCat; ?>>
                                <option value="0" selected="selected"></option>
<?PHP
									$counter = 0;
									$query = "select * from tblibsubcatmst order by SubCatName";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$counter = $counter+1;
											$sCatID = $row["ID"];
											$sCatName = $row["SubCatName"];
								
											if($OptSubCat =="$sCatID"){
?>
                                				<option value="<?PHP echo $sCatID; ?>" selected="selected"><?PHP echo $sCatName; ?></option>
<?PHP
											}else{
?>
                                				<option value="<?PHP echo $sCatID; ?>"><?PHP echo $sCatName; ?></option>
<?PHP
											}
										}
									}
?>
                              </select></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="Title" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkTitle; ?>>Book Title</TD>
							  <TD width="53%"  align="left" valign="top">
							  <input name="Title" type="text" size="16" value="<?PHP echo $Title; ?>" <?PHP echo $lockTitle; ?>></TD>
							</TR>
							<TR>
							  <TD width="47%"  align="left"><input name="isBookSearch" type="radio" value="AccessionNo" onClick="javascript:setTimeout('__doPostBack(\'isBookSearch\',\'\')', 0)" <?PHP echo $chkAccNo; ?>> Acc. No</TD>
							  <TD width="53%"  align="left" valign="top">
							  <input name="BookNo" type="text"value="<?PHP echo $BookNo; ?>" size="16" <?PHP echo $lockAccNo; ?>/>
							  <input name="SubmitSearch" type="submit" id="SubmitSearch" value="Go"></TD>
							</TR>
							<TR>
							    <TD colspan="2"  align="left">List of Available Books  </TD>
							</TR>
							<TR bgcolor="#999999">
							  <TD width="47%"  align="left"><span class="style25">Acc. No</span></TD>
							  <TD width="53%"  align="left" valign="top"><span class="style25">Book Title</span></TD>
							</TR>
<?PHP
							$counter_reg = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$isBookSearch = $_POST['isBookSearch'];
								if($isBookSearch == "All"){
									$query3 = "select ID,Title,BookNo from tbbookmst Where ID NOT IN (Select bookID from tbbookissstd where ReturnDate='') order by ID";
								}elseif($isBookSearch == "Author"){
									$OptAuthor = $_POST['OptAuthor'];
									$query3 = "select ID,Title,BookNo from tbbookmst where ID IN (Select BookID from tbbookauthorlist where AuthorID = '$OptAuthor') And ID NOT IN (Select bookID from tbbookissstd where ReturnDate='')  order by ID";
								}elseif($isBookSearch == "Category"){
									$OptCat = $_POST['OptCat'];
									$query3 = "select ID,Title,BookNo from tbbookmst where CatID = '$OptCat' And ID NOT IN (Select bookID from tbbookissstd where ReturnDate='') order by ID";
								}elseif($isBookSearch == "SubCategory"){
									$OptSubCat = $_POST['OptSubCat'];
									$query3 = "select ID,Title,BookNo from tbbookmst where SubCatID = '$OptSubCat' And ID NOT IN (Select bookID from tbbookissstd where ReturnDate='') order by ID";
								}elseif($isBookSearch == "Title"){
									$Title = $_POST['Title'];
									$query3 = "select ID,Title,BookNo from tbbookmst where INSTR(Title,'$Title') And ID NOT IN (Select bookID from tbbookissstd where ReturnDate='') order by ID";
								}elseif($isBookSearch == "AccessionNo"){
									$selBookNo = $_POST['BookNo'];
									$query3 = "select ID,Title,BookNo from tbbookmst where INSTR(BookNo,'$selBookNo') And ID NOT IN (Select bookID from tbbookissstd where ReturnDate='') order by ID";
								}else{
									$query3 = "select ID,Title,BookNo from tbbookmst where ID = '0' order by ID";
								}
							}else{
								$query3 = "select ID,Title,BookNo from tbbookmst where ID = '0' order by ID";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$bookID = $row["ID"];
									$Title = $row["Title"];
									$BookNo = $row["BookNo"];
									
									//Check if the selected book has been issued to student
									$numrows = 0;
									$query4   = "SELECT COUNT(*) AS numrows FROM tbbookissemp Where bookID = '$bookID' and ReturnDate=''";
									$result4  = mysql_query($query4,$conn) or die('Error, query failed');
									$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
									$rsNo = $row4['numrows'];
									if($rsNo > 0){
?>
										<TR bgcolor="#FFFFCC">
										  <TD width="47%"  align="left"><a href="librarybook.php?subpg=Book Issue to Employee&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&EmpID=<?PHP echo $SelEmpNo; ?>&src=<?PHP echo $isEmpSearch; ?>&dept=<?PHP echo $OptDept; ?>&deig=<?PHP echo $OptDesign; ?>&empname=<?PHP echo $EmpName; ?>" onClick="if (!confirm('This book has been issued to employee, would you like to edit the record!')) {return false;}"><?PHP echo $BookNo; ?></a></TD>
										  <TD width="53%"  align="left" valign="top"><a href="librarybook.php?subpg=Book Issue to Employee&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&EmpID=<?PHP echo $eID; ?>&src=<?PHP echo $isEmpSearch; ?>&dept=<?PHP echo $OptDept; ?>&deig=<?PHP echo $OptDesign; ?>&empname=<?PHP echo $EmpName; ?>" onClick="if (!confirm('This book has been issued to employee, would you like to edit the record!')) {return false;}"><?PHP echo $Title; ?></a></TD>
										</TR>
<?PHP
									}else{
?>
										<TR bgcolor="#E8E8E8">
										  <TD width="47%"  align="left"><a href="librarybook.php?subpg=Book Issue to Employee&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&EmpID=<?PHP echo $SelEmpNo; ?>&src=<?PHP echo $isEmpSearch; ?>&dept=<?PHP echo $OptDept; ?>&deig=<?PHP echo $OptDesign; ?>&empname=<?PHP echo $EmpName; ?>"><?PHP echo $BookNo; ?></a></TD>
										  <TD width="53%"  align="left" valign="top"><a href="librarybook.php?subpg=Book Issue to Employee&book_id=<?PHP echo $bookID; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&EmpID=<?PHP echo $SelEmpNo; ?>&src=<?PHP echo $isEmpSearch; ?>&dept=<?PHP echo $OptDept; ?>&deig=<?PHP echo $OptDesign; ?>&empname=<?PHP echo $EmpName; ?>"><?PHP echo $Title; ?></a></TD>
										</TR>
<?PHP
									}
								 }
							 }
?>
						</TBODY>
						</TABLE>
						</TD>
					  <TD width="52%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <strong>Search Employee </strong>
					    <TABLE width="100%" align="center" cellpadding="5">
                        <TBODY>
                          <TR>
                            <TD width="36%"  align="left"><input name="isEmpSearch" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'isEmpSearch\',\'\')', 0)" <?PHP echo $chkAlls; ?>>
                              All</TD>
                            <TD width="64%"  align="left" valign="top">&nbsp;</TD>
                          </TR>
                          <TR>
                            <TD width="36%"  align="left">
							<input name="isEmpSearch" type="radio" value="Department" onClick="javascript:setTimeout('__doPostBack(\'isEmpSearch\',\'\')', 0)" <?PHP echo $chkDept; ?>>
                              Department Wise </TD>
                            <TD width="64%"  align="left" valign="top">
							<select name="OptDept" <?PHP echo $lockdept; ?>>
                              <option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								$query = "select ID,DeptName from tbdepartments order by DeptName";
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
										$DeptID = $row["ID"];
										$DeptName = $row["DeptName"];
										
										if($OptDept =="$DeptID"){
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
                            </select></TD>
                          </TR>
                          <TR>
                            <TD width="36%"  align="left"><input name="isEmpSearch" type="radio" value="Designation" onClick="javascript:setTimeout('__doPostBack(\'isEmpSearch\',\'\')', 0)" <?PHP echo $chkDesig; ?>>
                              Designation Wise </TD>
                            <TD width="64%"  align="left" valign="top">
							<select name="OptDesign" <?PHP echo $lockDesig; ?>>
                              <option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								$query = "select ID,DesignName from tbdesignations order by DesignName";
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
										$DesigID = $row["ID"];
										$DesignName = $row["DesignName"];
										
										if($OptDesign =="$DesigID"){
?>
                             			 	<option value="<?PHP echo $DesigID; ?>" selected="selected"><?PHP echo $DesignName; ?></option>
<?PHP
										}else{
?>
                              				<option value="<?PHP echo $DesigID; ?>"><?PHP echo $DesignName; ?></option>
<?PHP
										}
									}
								}
?>
                            </select></TD>
                          </TR>
                          <TR>
                            <TD width="36%"  align="left"><input name="isEmpSearch" type="radio" value="Name" onClick="javascript:setTimeout('__doPostBack(\'isEmpSearch\',\'\')', 0)" <?PHP echo $chkName; ?>>
                            Name Wise </TD>
                            <TD width="64%"  align="left" valign="top">
							 <input name="EmpName" type="text" size="16" value="<?PHP echo $EmpName; ?>" <?PHP echo $lockName; ?>>
                              <input name="SubmitSearch" type="submit" id="SubmitSearch" value="Go"></TD>
                          </TR>

                          <TR>
                            <TD colspan="2"  align="left">&nbsp;</TD>
                          </TR>
						  </TBODY>
						  </TABLE>
						  <div class="a" align="left">
						  <div class="b" align="left">
						<TABLE width="100%" align="left" cellpadding="5">
                        <TBODY>
                          <TR bgcolor="#999999">
                            <TD width="50%"  align="left"><span class="style25">Employee  Name </span></TD>
                            <TD width="50%"  align="left" valign="top"><span class="style25">Department </span></TD>
                          </TR>
<?PHP
							$counter_reg = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$isEmpSearch = $_POST['isEmpSearch'];
								if($isEmpSearch == "All"){
									$query3 = "select ID,EmpName,EmpDept from tbemployeemasters order by EmpName";
								}elseif($isEmpSearch == "Department"){
									$OptDept = $_POST['OptDept'];
									$query3 = "select ID,EmpName,EmpDept from tbemployeemasters where EmpDept ='$OptDept' order by EmpName";
								}elseif($isEmpSearch == "Designation"){
									$OptDesign = $_POST['OptDesign'];
									$query3 = "select ID,EmpName,EmpDept from tbemployeemasters where EmpDesig ='$OptDesign' order by EmpName";
								}elseif($isEmpSearch == "Name"){
									$EmpName = $_POST['EmpName'];
									$query3 = "select ID,EmpName,EmpDept from tbemployeemasters where INSTR(EmpName,'$EmpName') order by EmpName";
								}else{
									$query3 = "select ID,EmpName,EmpDept from tbemployeemasters where ID = '0' order by EmpName";
								}
							}else{
								$query3 = "select ID,EmpName,EmpDept from tbemployeemasters where ID = '0' order by EmpName";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_reg = $counter_reg+1;
									$counter = $counter+1;
									$eID = $row["ID"];
									$SelEmpName = $row["EmpName"];
									$EmpDept = $row["EmpDept"];
									
									$query2 = "select DeptName from tbdepartments where ID='$EmpDept'";
									$result2 = mysql_query($query2,$conn);
									$dbarray2 = mysql_fetch_array($result2);
									$SelDeptName  = $dbarray2['DeptName'];
?>
									<TR bgcolor="#E8E8E8">
									  <TD width="50%"  align="left"><a href="librarybook.php?subpg=Book Issue to Employee&book_id=<?PHP echo $Selbookid; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&EmpID=<?PHP echo $eID; ?>&src=<?PHP echo $isEmpSearch; ?>&dept=<?PHP echo $OptDept; ?>&deig=<?PHP echo $OptDesign; ?>&empname=<?PHP echo $EmpName; ?>"><?PHP echo $SelEmpName; ?></a></TD>
									  <TD width="50%"  align="left" valign="top"><a href="librarybook.php?subpg=Book Issue to Employee&book_id=<?PHP echo $Selbookid; ?>&isSrch=<?PHP echo $isBookSearch; ?>&ath=<?PHP echo $OptAuthor; ?>&cat=<?PHP echo $OptCat; ?>&scat=<?PHP echo $OptSubCat; ?>&title=<?PHP echo $Title; ?>&bkno=<?PHP echo $selBookNo; ?>&EmpID=<?PHP echo $eID; ?>&src=<?PHP echo $isEmpSearch; ?>&dept=<?PHP echo $OptDept; ?>&deig=<?PHP echo $OptDesign; ?>&empname=<?PHP echo $EmpName; ?>"><?PHP echo $SelDeptName; ?></a></TD>
									</TR>
<?PHP
								 }
							 }
?>
                        </TBODY>
                      </TABLE>
					  </div></div>
					  
					  
					  
					  <TABLE width="100%" align="center" cellpadding="5" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
                        <TBODY>
                          <TR>
                            <TD width="32%"  align="left"> Accession  No</TD>
                            <TD width="68%"  align="left" valign="top"><input name="AccessionNo" type="text" size="20" value="<?PHP echo $AccessionNo; ?>" disabled="disabled"></TD>
                          </TR>
                          <TR>
                            <TD width="32%"  align="left"> Book Title                             </TD>
                            <TD width="68%"  align="left" valign="top"><input name="BookTitle" type="text" size="20" value="<?PHP echo $BookTitle; ?>" disabled="disabled"></TD>
                          </TR>
                          <TR>
                            <TD width="32%"  align="left">  Category                             </TD>
                            <TD width="68%"  align="left" valign="top"><input name="Category" type="text"value="<?PHP echo $Category; ?>" size="20" disabled="disabled"/></TD>
                          </TR>
                          <TR>
                            <TD width="32%"  align="left">Employee Name </TD>
                            <TD width="68%"  align="left" valign="top"><input name="EmployeeName" type="text" size="20" value="<?PHP echo $EmployeeName; ?>" disabled="disabled"></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left">Department </TD>
                            <TD width="68%"  align="left" valign="top"><input name="sClass" type="text" size="20" value="<?PHP echo $Department; ?>" disabled="disabled"></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left">Issue Date </TD>
                            <TD width="68%"  align="left" valign="top">
							<select name="iss_Day" style="width:40px" disabled="disabled">
                              <option value="0">Dy</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($iss_Day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurDay == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                            </select>
                              <select name="iss_mth" style=" width:40px" disabled="disabled">
                                <option value="0">Mth</option>
 <?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $iss_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}elseif($CurMth == $i){
										if($Found=="False"){
											echo "<option value=$i selected=selected>$i</option>";
										}
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                              </select>
                              <select name="iss_yr" style=" width:55px" disabled="disabled">
                                <option value="0">Yr</option>
<?PHP
									$CurYear = date('Y');
									$Found="False";
									for($i=2009; $i<=$CurYear; $i++){
										if($iss_yr == $i){
											echo "<option value=$i selected=selected>$i</option>";
											$Found="True";
										}elseif($CurYear == $i){
											if($Found=="False"){
												echo "<option value=$i selected=selected>$i</option>";
											}
										}else{
											echo "<option value=$i>$i</option>";
										}
									}
?>
                              </select></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left"> Due Date                             </TD>
                            <TD width="68%"  align="left" valign="top">
							<select name="due_day" style=" width:40px">
                              <option value="0">Dy</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($due_day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurDay == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                            </select>
                              <select name="due_mth" style=" width:40px">
                                <option value="0">Mth</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $due_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}elseif($CurMth == $i){
										if($Found=="False"){
											echo "<option value=$i selected=selected>$i</option>";
										}
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                              </select>
                              <select name="due_yr" style=" width:55px">
                                <option value="0">Yr</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($due_yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurYear == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                              </select></TD>
                          </TR>
						  <TR>
                            <TD width="32%"  align="left">&nbsp;</TD>
                            <TD width="68%"  align="left" valign="top">&nbsp;</TD>
                          </TR>
                        </TBODY>
                      </TABLE>
					  
					  
					  
					  
					  
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center"><br>
							 <input type="hidden" name="SelBookID" value="<?PHP echo $Selbookid; ?>">
							 <input type="hidden" name="SelEmpID" value="<?PHP echo $SelEmpNo; ?>">
							 <input name="IssEmployee" type="submit" id="IssEmployee" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="IssEmployee" type="submit" id="IssEmployee" value="Update">
						  </div>						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Book Return from Student") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarybook.php?subpg=Book Return from Student">
				<TABLE width="90%" style="WIDTH: 90%" cellpadding="4" cellspacing="4">
					<TBODY>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Accession No. </TD>
					  <TD width="31%" valign="top"  align="left">
					  <input name="AccessionNo" type="text"value="<?PHP echo $AccessionNo; ?>" size="20"/>
					    <input name="GetIssueBook" type="submit" id="GetIssueBook" value="Go"></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Issued To  </TD>
					  <TD width="31%" valign="top"  align="left"><input name="IssuedTo" type="text"value="<?PHP echo $IssuedTo; ?>" size="30" disabled="disabled"/></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Class Name </TD>
					  <TD width="31%" valign="top"  align="left"><input name="ClassName" type="text"value="<?PHP echo $ClassName; ?>" size="20" disabled="disabled"/></TD>
					  <TD width="18%" valign="top"  align="left" > Term / Section </TD>
					  <TD width="37%" valign="top"  align="left"><input name="Section" type="text"value="<?PHP echo $Section; ?>" size="20" disabled="disabled"/></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Issue Date </TD>
					  <TD width="31%" valign="top"  align="left">
					  <select name="iss_Day" style=" width:40px" disabled="disabled">
                        <option value="0">&nbsp;</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($iss_Day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                      </select>
                        <select name="iss_mth" style=" width:40px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $iss_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                        </select>
                        <select name="iss_yr" style=" width:55px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($iss_yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                        </select></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Due Date  </TD>
					  <TD width="31%" valign="top"  align="left">
					  <select name="due_day" style=" width:40px" disabled="disabled">
                        <option value="0">&nbsp;</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($due_day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                      </select>
                        <select name="due_mth" style=" width:40px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $due_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                        </select>
                        <select name="due_yr" style=" width:55px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($due_yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                        </select></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Return Date </TD>
					  <TD width="31%" valign="top"  align="left">
					  <select name="ret_Day" style=" width:40px">
                        <option value="0">Dy</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($ret_Day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurDay == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                      </select>
                        <select name="ret_Mth" style=" width:40px">
                          <option value="0">Mth</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $ret_Mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}elseif($CurMth == $i){
										if($Found=="False"){
											echo "<option value=$i selected=selected>$i</option>";
										}
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                        </select>
                        <select name="ret_Yr" style=" width:55px">
                          <option value="0">Yr</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($ret_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurYear == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                        </select></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Total Fine </TD>
					  <TD width="31%" valign="top"  align="left"><input name="TotalFine" type="text"value="<?PHP echo $TotalFine; ?>" size="10"/>
					    Counter = [<?PHP echo $FinePerDay; ?> *<?PHP echo $FineCounter; ?>]</TD>
					  <TD width="18%" valign="top"  align="left" >Amount Received </TD>
					  <TD width="37%" valign="top"  align="left"><input name="Amount" type="text"value="<?PHP echo $Amount; ?>" size="20"/></TD>
					</TR>
				 </TBODY>
				 </TABLE>
				 <TABLE width="90%" cellpadding="4" cellspacing="4" style="WIDTH: 90%; BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					<TBODY>
					<TR>
					  <TD colspan="2" valign="top"  align="left" ><strong>Book Details</strong></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Book Title</TD>
					  <TD width="31%" valign="top"  align="left"><input name="BookTitle" type="text"value="<?PHP echo $BookTitle; ?>" size="30"/></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Category</TD>
					  <TD width="31%" valign="top"  align="left"><input name="Category" type="text"value="<?PHP echo $Category; ?>" size="30"/></TD>
					  <TD width="18%" valign="top"  align="left" ></TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Sub Category</TD>
					  <TD width="31%" valign="top"  align="left"><input name="subCategory" type="text"value="<?PHP echo $subCategory; ?>" size="30"/></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					</TBODY>
				 </TABLE>
				 <TABLE width="90%" cellpadding="4" cellspacing="4">
				 <TBODY>
					<TR>
						 <TD colspan="4">
						  <div align="center"><br>
							 <input type="hidden" name="SelBookID" value="<?PHP echo $Selbookid; ?>">
							 <input type="hidden" name="SelAdmNo" value="<?PHP echo $SelAdmNo; ?>">
							 <input name="retStudent" type="submit" id="retStudent" value="Return" <?PHP echo $disabled; ?>>
						  </div>						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Book Return from Employee") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarybook.php?subpg=Book Return from Employee">
				<TABLE width="90%" style="WIDTH: 90%" cellpadding="4" cellspacing="4">
					<TBODY>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Accession No. </TD>
					  <TD width="31%" valign="top"  align="left">
					  <input name="AccessionNo" type="text"value="<?PHP echo $AccessionNo; ?>" size="20"/>
					    <input name="GetIssueBookEmp" type="submit" id="GetIssueBookEmp" value="Go"></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Issued To  </TD>
					  <TD width="31%" valign="top"  align="left"><input name="IssuedTo" type="text"value="<?PHP echo $IssuedTo; ?>" size="30" disabled="disabled"/></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Department </TD>
					  <TD width="31%" valign="top"  align="left"><input name="Department" type="text"value="<?PHP echo $Department; ?>" size="20" disabled="disabled"/></TD>
					  <TD width="18%" valign="top"  align="left" > Designation </TD>
					  <TD width="37%" valign="top"  align="left"><input name="Designation" type="text"value="<?PHP echo $Designation; ?>" size="20" disabled="disabled"/></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Issue Date </TD>
					  <TD width="31%" valign="top"  align="left">
					  <select name="iss_Day" style=" width:40px" disabled="disabled">
                        <option value="0">&nbsp;</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($iss_Day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                      </select>
                        <select name="iss_mth" style=" width:40px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $iss_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                        </select>
                        <select name="iss_yr" style=" width:55px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($iss_yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                        </select></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Due Date  </TD>
					  <TD width="31%" valign="top"  align="left">
					  <select name="due_day" style=" width:40px" disabled="disabled">
                        <option value="0">&nbsp;</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($due_day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                      </select>
                        <select name="due_mth" style=" width:40px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $due_mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                        </select>
                        <select name="due_yr" style=" width:55px" disabled="disabled">
                          <option value="0">&nbsp;</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($due_yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                        </select></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Return Date </TD>
					  <TD width="31%" valign="top"  align="left">
					  <select name="ret_Day" style=" width:40px">
                        <option value="0">&nbsp;</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($ret_Day == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurDay == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                      </select>
                        <select name="ret_Mth" style=" width:40px">
                          <option value="0">&nbsp;</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $ret_Mth){
										echo "<option value=$i selected='selected'>$i</option>";
										$Found="True";
									}elseif($CurMth == $i){
										if($Found=="False"){
											echo "<option value=$i selected=selected>$i</option>";
										}
									}else{
										echo "<option value=$i>$i</option>";
									}
								}
?>
                        </select>
                        <select name="ret_Yr" style=" width:55px">
                          <option value="0">&nbsp;</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($ret_Yr == $i){
									echo "<option value=$i selected=selected>$i</option>";
									$Found="True";
								}elseif($CurYear == $i){
									if($Found=="False"){
										echo "<option value=$i selected=selected>$i</option>";
									}
								}else{
									echo "<option value=$i>$i</option>";
								}
							}
?>
                        </select></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
				 </TBODY>
				 </TABLE>
				 <TABLE width="90%" cellpadding="4" cellspacing="4" style="WIDTH: 90%; BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					<TBODY>
					<TR>
					  <TD colspan="2" valign="top"  align="left" ><strong>Book Details</strong></TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Book Title</TD>
					  <TD width="31%" valign="top"  align="left"><input name="BookTitle" type="text"value="<?PHP echo $BookTitle; ?>" size="30"/></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Category</TD>
					  <TD width="31%" valign="top"  align="left"><input name="Category" type="text"value="<?PHP echo $Category; ?>" size="30"/></TD>
					  <TD width="18%" valign="top"  align="left" ></TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					<TR>
					  <TD width="14%" valign="top"  align="left" >Sub Category</TD>
					  <TD width="31%" valign="top"  align="left"><input name="subCategory" type="text"value="<?PHP echo $subCategory; ?>" size="30"/></TD>
					  <TD width="18%" valign="top"  align="left" >&nbsp;</TD>
					  <TD width="37%" valign="top"  align="left">&nbsp;</TD>
					</TR>
					</TBODY>
				 </TABLE>
				 <TABLE width="90%" cellpadding="4" cellspacing="4">
				 <TBODY>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input name="retEmployee" type="submit" id="retEmployee" value="Return" <?PHP echo $disabled; ?>>
						  </div>						  </TD>
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
