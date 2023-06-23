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
	//session_start();
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
	if($SubPage == "Fee Defaulter Details"){
		$Page = "Fees Report";
	}elseif($SubPage == "Student Fee Details"){
		$Page = "Fees Report";
	}elseif($SubPage == "Fee Defaulter List"){
		$Page = "Fees Report";
	}elseif($SubPage == "Total Fees Paid"){
		$Page = "Fees Report";
	}elseif($SubPage == "Refundable amount details"){
		$Page = "Fees Report";
	}elseif($SubPage == "Charge Summary"){
		$Page = "Fees Report";
	}else{
		$Page = "Fees";
	}
	$audit=update_Monitory('Login','Administrator',$Page);
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	$PageHasError = 0;
	
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
		$rend = 10;
	}
	if(isset($_POST['GetStudent']) or isset($_POST['OptStudent']))
	{
		$OptStudent = $_POST['OptStudent'];
		if ($OptStudent ==""){
			$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
		}else{
			$AdmissionNo = $OptStudent;
			$arrAdm=explode ('-', $AdmissionNo);
			$_POST['AdmNo'] = $arrAdm[0];
			$_POST['AdmNo2']= $arrAdm[1];
		}
		$AdmNo = $_POST['AdmNo'];
		$AdmNo2 = $_POST['AdmNo2'];
		if(!$_POST['AdmNo']){
			$errormsg = "<font color = red size = 1>Admission No is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$query = "select Stu_Regist_No,Stu_Class,Stu_Full_Name,Stu_Type from tbstudentmaster where AdmissionNo='$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);	
			$RegNo  = $dbarray['Stu_Regist_No'];
			$StudClass  = $dbarray['Stu_Class'];
			$StudName  = $dbarray['Stu_Full_Name'];
			$StudType  = $dbarray['Stu_Type'];
			
			$query = "select Gr_Name_Mr from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);		
			$FatName  = $dbarray['Gr_Name_Mr'];
			
			$query3 = "select * from tbreceipt where AdmnNo='$AdmissionNo' And Term ='$Activeterm' And Status ='0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result3 = mysql_query($query3,$conn);
			$dbarray3 = mysql_fetch_array($result3);
			$ReceiptNo  = $dbarray3['ID'];
			$FeeDate  = $dbarray3['FeeDate'];
			$FeeDate=explode ('-', $dbarray3['FeeDate']);
			$fee_Dy = $FeeDate[2];
			$fee_Mth = $FeeDate[1];
			$fee_Yr = $FeeDate[0];
		
			$PaymentMode  = $dbarray3['PayMode'];
			$Remark  = $dbarray3['Remarks'];
			$FinedPayable  = $dbarray3['FinePayable'];
			$FinedPaid  = $dbarray3['FinePaid'];

		}
	}
	if(isset($_POST['SaveReceipt']))
	{
		$PageHasError = 0;
		$ReceiptNo = $_POST['SelReceiptID'];
		$fee_Dy = $_POST['fee_Dy'];
		$fee_Mth = $_POST['fee_Mth'];
		$fee_Yr = $_POST['fee_Yr'];
		$RepDate = $fee_Yr."-".$fee_Mth."-".$fee_Dy;
		$AdmNo = $_POST['AdmNo'];
		$AdmNo2 = $_POST['AdmNo2'];
		$AdmissionNo = $_POST['AdmNo']."-".$_POST['AdmNo2'];
		$StudClass = $_POST['StudClass'];
		$StudName = $_POST['StudName'];
		$StudClass = $_POST['StudClass'];
		$FatName = $_POST['FatName'];
		$PaymentMode = $_POST['PaymentMode'];
		$Remark = $_POST['Remark'];
		$OptStudent = $_POST['OptStudent'];
		
		$FinedPayable = $_POST['FinedPayable'];
		$FinedPaid = $_POST['FinedPaid'];
		if ($FinedPayable == ""){
			$FinedPayable = 0;
		}
		if ($FinedPaid == ""){
			$FinedPaid = 0;
		}
		if(!$_POST['fee_Dy']){
			$errormsg = "<font color = red size = 1>Select Date [Day].</font>";
			$PageHasError = 1;
		}
		if(!$_POST['fee_Mth']){
			$errormsg = "<font color = red size = 1>Select Date [Month].</font>";
			$PageHasError = 1;
		}
		if(!$_POST['fee_Yr']){
			$errormsg = "<font color = red size = 1>Select Date [Year].</font>";
			$PageHasError = 1;
		}
		if(!$_POST['AdmNo']){
			$errormsg = "<font color = red size = 1>Admission No is empty.</font>";
			$PageHasError = 1;
		}
		$Total = $_POST['TotalBox'];
		for($i=1;$i<=$Total;$i++){
			if(isset($_POST['chgID'.$i]))
			{
				$chargID = $_POST['chgID'.$i];
				$Amount = $_POST['Amt'.$i];
				$Payable = $_POST['Pay'.$i];
				$paid = $_POST['paid'.$i];
				if(!is_numeric($Amount)){
					$errormsg = "<font color = red size = 1>Invalid Amount</font>";
					$PageHasError = 1;
				}
				if(!is_numeric($Payable)){
					$errormsg = "<font color = red size = 1>Invalid Amount</font>";
					$PageHasError = 1;
				}
				if(!is_numeric($paid)){
					$errormsg = "<font color = red size = 1>Invalid Paid Amount</font>";
					$PageHasError = 1;
				}
			}
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SaveReceipt'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbreceipt where AdmnNo = '$AdmissionNo' And Term = '$Activeterm' And Status ='0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The receipt you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbreceipt(FeeDate,AdmnNo,PayMode,Remarks,FinePayable,FinePaid,Term,Session_ID,Term_ID) Values ('$RepDate','$AdmissionNo','$PaymentMode','$Remark','$FinedPayable','$FinedPaid','$Activeterm','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					
					
					// GET Receipt ID
					$SelAmount = "";
					$query2 = "select ID from tbreceipt where AdmnNo='$AdmissionNo' And Term ='$Activeterm' And Status = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					
					$result2 = mysql_query($query2,$conn);
					$dbarray2 = mysql_fetch_array($result2);
					$RepID  = $dbarray2['ID'];
					
					$Total = $_POST['TotalBox'];
					for($i=1;$i<=$Total;$i++){
						if(isset($_POST['chgID'.$i]))
						{
							$chargID = $_POST['chgID'.$i];
							$Amount = $_POST['Amt'.$i];
							$Payable = $_POST['Pay'.$i];
							$paid = $_POST['paid'.$i];
							if(!is_numeric($paid)){
								$errormsg = "<font color = red size = 1>Invalid Paid Amount</font>";
								$PageHasError = 1;
							}else{
								$q = "Insert into tbfeepayment(ChargeID,Amount,Payable,PaidAmount,ReceiptNo,Session_ID,Term_ID) Values ('$chargID','$Amount','$Payable','$paid','$RepID','$Session_ID','$Term_ID')";
								$result = mysql_query($q,$conn);
							}
							
						}
					}
					$errormsg = "<font color = blue size = 1>Saved Successfully.</font>";
				}
			}elseif ($_POST['SaveReceipt'] =="Update"){
				$q = "update tbreceipt set FeeDate = '$RepDate',AdmnNo='$AdmissionNo',PayMode='$PaymentMode',Remarks='$Remark',FinePayable='$FinedPayable',FinePaid='$FinedPaid', Term='$Activeterm'  where ID = '$ReceiptNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				
				$Total = $_POST['TotalBox'];
				for($i=1;$i<=$Total;$i++){
					if(isset($_POST['chgID'.$i]))
					{
						$chargID = $_POST['chgID'.$i];
						$Amount = $_POST['Amt'.$i];
						$Payable = $_POST['Pay'.$i];
						$paid = $_POST['paid'.$i];
						if(!is_numeric($paid)){
							$errormsg = "<font color = red size = 1>Invalid Paid Amount</font>";
							$PageHasError = 1;
						}else{
							$query = "select ID from tbfeepayment where ReceiptNo = '$ReceiptNo' And ChargeID = '$chargID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$num_rows = mysql_num_rows($result);
							if ($num_rows > 0 ) 
							{
								$q = "update tbfeepayment set Amount='$Amount',Payable='$Payable',PaidAmount='$paid' where ReceiptNo = '$ReceiptNo' And ChargeID = '$chargID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result2 = mysql_query($q,$conn);
							}else {
								$q = "Insert into tbfeepayment(ChargeID,Amount,Payable,PaidAmount,ReceiptNo,Session_ID,Term_ID) Values ('$chargID','$Amount','$Payable','$paid','$ReceiptNo','$Session_ID','$Term_ID')";
								$result = mysql_query($q,$conn);
							}
						}
						
					}
				}
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
			}
			$query3 = "select FinePayable,FinePaid from tbreceipt where AdmnNo='$AdmissionNo' And Term ='$Activeterm' And Status ='0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result3 = mysql_query($query3,$conn);
			$dbarray3 = mysql_fetch_array($result3);	
			$FinedPayable  = $dbarray3['FinePayable'];
			$FinedPaid  = $dbarray3['FinePaid'];
		}
		$query = "select Stu_Regist_No,Stu_Class,Stu_Full_Name,Stu_Type from tbstudentmaster where AdmissionNo='$AdmissionNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$RegNo  = $dbarray['Stu_Regist_No'];
		$StudClass  = $dbarray['Stu_Class'];
		$StudName  = $dbarray['Stu_Full_Name'];
		$StudType  = $dbarray['Stu_Type'];
		
		$query = "select Gr_Name_Mr from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);		
		$FatName  = $dbarray['Gr_Name_Mr'];
	}
	if(isset($_POST['SaveReceipt_delete']))
	{
		$ReceiptNo = $_POST['SelReceiptID'];
		$curDate= date('Y')."-".date('m')."-".date('d');
		$q = "update tbreceipt set Status = '2', FeeDate = '$curDate' where ID = '$ReceiptNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);
	}
	if(isset($_POST['SaveReceipt_Refund']))
	{
		$ReceiptNo = $_POST['SelReceiptID'];
		$curDate= date('Y')."-".date('m')."-".date('d');
		$q = "update tbreceipt set Status = '1', FeeDate = '$curDate' where ID = '$ReceiptNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($q,$conn);
	}
	if(isset($_POST['PrintReceipt']))
	{
		$ReceiptNo = $_POST['SelReceiptID'];
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Fees Receipt&rid=$ReceiptNo\">";
		exit;
	}
	if(isset($_POST['SubmitUpdate']))
	{
		$Total = $_POST['Totalchg'];
		for($i=1;$i<=$Total;$i++){
			if(isset($_POST['chg'.$i]))
			{
				$chgeID = $_POST['chg'.$i];
				$FrmMonth = $_POST['OptFrm'.$i];
				$ToMonth = $_POST['OptTo'.$i];
				if($FrmMonth !="" And $ToMonth !=""){
					$query = "select ID from tbmonthlyCharges where ChargeID = '$chgeID' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) 
					{
						$q = "update tbmonthlyCharges set FromMonth = '$FrmMonth',Tomonth='$ToMonth' where ChargeID = '$chgeID' And Term = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'
";
						$result = mysql_query($q,$conn);
						
					}else {
						$q = "Insert into tbmonthlyCharges(ChargeID,FromMonth,Tomonth,Term,Session_ID,Term_ID) Values ('$chgeID','$FrmMonth','$ToMonth','$Activeterm','$Session_ID','$Term_ID')";
						$result = mysql_query($q,$conn);
						echo $q."<br>";

						
					}
				}
			}
		}
	}
	if(isset($_POST['ischecked']))
	{	
		$ischecked = $_POST['ischecked'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Name"){
			$chkname = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}
	}
	if(isset($_POST['ischecked2']))
	{	
		$ischecked = $_POST['ischecked2'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Name"){
			$chkname = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}
	}
	if(isset($_POST['ischecked3']))
	{	
		$ischecked = $_POST['ischecked3'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Name"){
			$chkname = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}
	}
	if(isset($_POST['ischecked4']))
	{	
		$ischecked = $_POST['ischecked4'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Name"){
			$chkname = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}
	}
	
	if(isset($_POST['GetStudentFee']))
	{	
		$ischecked = $_POST['ischecked'];
		$OptClass = $_POST['OptClass'];
		$StuName = $_POST['StuName'];
		if($ischecked =="All"){
			$chkAll = "checked='checked'";
			$lockclass = "disabled='disabled'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Class"){
			$chkclass = "checked='checked'";
			$lockname = "disabled='disabled'";
		}elseif($ischecked =="Name"){
			$chkname = "checked='checked'";
			$lockclass = "disabled='disabled'";
		}
	}
	if(isset($_POST['SubmitPrint']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		$OptClass = $_POST['OptClass'];
		/*if(isset($_POST['1stTerm']))
		{
			$Term1 = $_POST['1stTerm'];
		}
		if(isset($_POST['2ndTerm']))
		{
			$Term2 = $_POST['2ndTerm'];
		}
		if(isset($_POST['3rdTerm']))
		{
			$Term3 = $_POST['3rdTerm'];
		}*/
		if(!isset($_POST['ischecked']))
		{
			$errormsg = "<font color = red size = 1>Select Filter Criteria.</font>";
			$PageHasError = 1;
		}
		/*if(!isset($_POST['1stTerm']) and !isset($_POST['2ndTerm']) and !isset($_POST['3rdTerm']))
		{
			$errormsg = "<font color = red size = 1>Select at least a term to display</font>";
			$PageHasError = 1;
		}*/
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Fee Defaulter Details&src=$ischecked&cls=$OptClass&t1=$Term1&t2=$Term2&t3=$Term3&mth=Display\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrint2']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked2'];
		$OptClass = $_POST['OptClass2'];
		/*if(isset($_POST['1stTerm']))
		{
			$Term1 = $_POST['1stTerm'];
		}
		if(isset($_POST['2ndTerm']))
		{
			$Term2 = $_POST['2ndTerm'];
		}
		if(isset($_POST['3rdTerm']))
		{
			$Term3 = $_POST['3rdTerm'];
		}*/
		if(!isset($_POST['ischecked2']))
		{
			$errormsg = "<font color = red size = 1>Select Filter Criteria.</font>";
			$PageHasError = 1;
		}
		/*if(!isset($_POST['1stTerm']) and !isset($_POST['2ndTerm']) and !isset($_POST['3rdTerm']))
		{
			$errormsg = "<font color = red size = 1>Select at least a term to display</font>";
			$PageHasError = 1;
		}*/
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Fee Defaulter List&src=$ischecked&cls=$OptClass&t1=$Term1&t2=$Term2&t3=$Term3&mth=Display\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrint3']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked3'];
		$OptClass = $_POST['OptClass3'];
		/*if(isset($_POST['1stTerm']))
		{
			$Term1 = $_POST['1stTerm'];
		}
		if(isset($_POST['2ndTerm']))
		{
			$Term2 = $_POST['2ndTerm'];
		}
		if(isset($_POST['3rdTerm']))
		{
			$Term3 = $_POST['3rdTerm'];
		}*/
		if(!isset($_POST['ischecked3']))
		{
			$errormsg = "<font color = red size = 1>Select Filter Criteria.</font>";
			$PageHasError = 1;
		}
		/*if(!isset($_POST['1stTerm']) and !isset($_POST['2ndTerm']) and !isset($_POST['3rdTerm']))
		{
			$errormsg = "<font color = red size = 1>Select at least a term to display</font>";
			$PageHasError = 1;
		}*/
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Total Fees Paid&src=$ischecked&cls=$OptClass&t1=$Term1&t2=$Term2&t3=$Term3&mth=Display\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrint4']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked4'];
		$OptClass = $_POST['OptClass4'];
		/*if(isset($_POST['1stTerm']))
		{
			$Term1 = $_POST['1stTerm'];
		}
		if(isset($_POST['2ndTerm']))
		{
			$Term2 = $_POST['2ndTerm'];
		}
		if(isset($_POST['3rdTerm']))
		{
			$Term3 = $_POST['3rdTerm'];
		}*/
		if(!isset($_POST['ischecked4']))
		{
			$errormsg = "<font color = red size = 1>Select Filter Criteria.</font>";
			$PageHasError = 1;
		}
		/*if(!isset($_POST['1stTerm']) and !isset($_POST['2ndTerm']) and !isset($_POST['3rdTerm']))
		{
			$errormsg = "<font color = red size = 1>Select at least a term to display</font>";
			$PageHasError = 1;
		}*/
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Charge Summary&src=$ischecked&cls=$OptClass&t1=$Term1&t2=$Term2&t3=$Term3&mth=Display\">";
			exit;
		}
	}
	if(isset($_POST['NotifyFeeDef']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		$OptClass = $_POST['OptClass'];
		if(isset($_POST['1stTerm']))
		{
			$Term1 = $_POST['1stTerm'];
		}
		if(isset($_POST['2ndTerm']))
		{
			$Term2 = $_POST['2ndTerm'];
		}
		if(isset($_POST['3rdTerm']))
		{
			$Term3 = $_POST['3rdTerm'];
		}
		if(!isset($_POST['ischecked']))
		{
			$errormsg = "<font color = red size = 1>Select Filter Criteria.</font>";
			$PageHasError = 1;
		}
		if(!isset($_POST['1stTerm']) and !isset($_POST['2ndTerm']) and !isset($_POST['3rdTerm']))
		{
			$errormsg = "<font color = red size = 1>Select at least a term to display</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Fee Defaulter&src=$ischecked&cls=$OptClass&t1=$Term1&t2=$Term2&t3=$Term3&mth=SMS\">";
			exit;
		}
	}
	if(isset($_POST['SubmitPrintStu_Wise']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		$OptClass = $_POST['className5'];
		$OptStudent = $_POST['StudentName5'];
		//$StuName = $_POST['StuName'];
		//$_POST['GetStudentFee'] = "Go";
		if($ischecked == "")
		{
			$errormsg = "<font color = red size = 1>Please Select A Filter</font>";
			$PageHasError = 1;
		}
		if($ischecked == "All")
		{
			//$errormsg = "<font color = red size = 1>Select A Class</font>";
			$PageHasError = 0;
		}
		elseif($ischecked == "Class" and $OptClass == "")
		{
			$errormsg = "<font color = red size = 1>Select A Class</font>";
			$PageHasError = 1;
		}
		elseif($ischecked == "Name" and $OptStudent == "")
		{
			$errormsg = "<font color = red size = 1>Select Student Name</font>";
			$PageHasError = 1;
		}
		
		
		
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Student fee&adm=$OptStudent&t1=$Term1&t2=$Term2&class=$OptClass&checked=$ischecked\">";
			exit;
		}
	}
	if(isset($_POST['NotifyParent2']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		$OptClass = $_POST['OptClass'];
		$OptStudent = $_POST['OptStudent'];
		$StuName = $_POST['StuName'];
		$_POST['GetStudentFee'] = "Go";
		if(isset($_POST['1stTerm']))
		{
			$Term1 = $_POST['1stTerm'];
		}
		if(isset($_POST['2ndTerm']))
		{
			$Term2 = $_POST['2ndTerm'];
		}
		if(isset($_POST['3rdTerm']))
		{
			$Term3 = $_POST['3rdTerm'];
		}
		if(!isset($_POST['OptStudent']))
		{
			$errormsg = "<font color = red size = 1>Select Student Name</font>";
			$PageHasError = 1;
		}
		if(!isset($_POST['1stTerm']) and !isset($_POST['2ndTerm']) and !isset($_POST['3rdTerm']))
		{
			$errormsg = "<font color = red size = 1>Select at least a term to display</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Student fee&adm=$OptStudent&t1=$Term1&t2=$Term2&t3=$Term3&sms=notify\">";
			exit;
		}
	}
	//
	if(isset($_POST['NotifyParent']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		$OptClass = $_POST['OptClass'];
		$OptStudent = $_POST['OptStudent'];
		$StuName = $_POST['StuName'];
		$isSend_Status="False";
		$_POST['GetStudentFee'] = "Go";
		if(!isset($_POST['OptStudent']))
		{
			$errormsg = "<font color = red size = 1>Select Student Name</font>";
			$PageHasError = 1;
		}
		
		if(!isset($_POST['1stTerm']) and !isset($_POST['2ndTerm']) and !isset($_POST['3rdTerm']))
		{
			$errormsg = "<font color = red size = 1>Select at least a term to display</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			//adm=$OptStudent
			$query2 = "select Stu_Regist_No,Stu_Type,Stu_Full_Name from tbstudentmaster where AdmissionNo = '$OptStudent' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query2,$conn);
			$dbarray = mysql_fetch_array($result);
			$Stu_AdmnNo  = $OptStudent;
			$RegNo  = $dbarray['Stu_Regist_No'];
			$StuType = $dbarray['Stu_Type'];
			$StudentName  = $dbarray['Stu_Full_Name'];
			
			$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$Contact  = $dbarray['Gr_Ph'];
			if(isset($_POST['1stTerm']))
			{
				$Term1 = "1st Term";
				$BalFined = 0;
				$FinedPaid = 0;
				$FinedPayable = 0;
				
				$counter = 0;
				$TotalPay = 0;
				$TotalPaid= 0;
				$TotalBal= 0;
				$GrandPayable = 0;
				$GrandPaid = 0;
				$GrandBal = 0;
				
				$query3 = "select * from tbreceipt where AdmnNo='$Stu_AdmnNo' And Term ='1st Term' And Status ='0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$dbarray3 = mysql_fetch_array($result3);
				$ReceiptNo  = $dbarray3['ID'];
				$FinedPayable  = $dbarray3['FinePayable'];
				$FinedPaid  = $dbarray3['FinePaid'];
				$BalFined = $FinedPayable - $FinedPaid;
									 
				$query3 = "select chargeID from tbstudentcharges where sType = '$StuType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$num_rows3 = mysql_num_rows($result3);
				if ($num_rows3 > 0 ) {
					while ($row3 = mysql_fetch_array($result3)) 
					{
						$counter = $counter+1;
						$chargeID = $row3["chargeID"];
							
						
						$query4 = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$Stu_AdmnNo' And Term = '1st Term' And Status = '0') And ChargeID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result4 = mysql_query($query4,$conn);
						$dbarray4 = mysql_fetch_array($result4);
						$AmtPayable  = $dbarray4['Payable'];
						echo $query4."<br>";
						$PaidAmount  = $dbarray4['PaidAmount'];
						if($PaidAmount ==""){
							$PaidAmount  = "0";
						}
						$Balance = 0;
						
						$Balance = $AmtPayable - $PaidAmount;
						$TotalPay = $TotalPay + $AmtPayable;
						$TotalPaid = $TotalPaid +$PaidAmount;
						$TotalBal = $TotalBal +$Balance;
					 }
				 }
				 $GrandPayable = number_format($TotalPay + $FinedPayable,0);
				 $GrandPaid = number_format($TotalPaid + $FinedPaid,0);
				 $GrandBal = number_format($TotalBal + $BalFined,0);
				 
				// echo $Stu_AdmnNo.",".$StudentName.",".$Term1.",".$GrandPayable.",".$GrandPaid.",".$GrandBal.",".$Contact;
				 //$isSend_Status = sendFees($Stu_AdmnNo,$StudentName,$Term1,$GrandPayable,$GrandPaid,$GrandBal,$Contact);
			}
			if(isset($_POST['2ndTerm']))
			{
				$Term2 = "2nd Term";
				$BalFined = 0;
				$FinedPaid = 0;
				$FinedPayable = 0;
				
				$counter = 0;
				$TotalPay = 0;
				$TotalPaid= 0;
				$TotalBal= 0;
				$GrandPayable = 0;
				$GrandPaid = 0;
				$GrandBal = 0;
				
				$query3 = "select * from tbreceipt where AdmnNo='$Stu_AdmnNo' And Term ='2nd Term' And Status ='0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$dbarray3 = mysql_fetch_array($result3);
				$ReceiptNo  = $dbarray3['ID'];
				$FinedPayable  = $dbarray3['FinePayable'];
				$FinedPaid  = $dbarray3['FinePaid'];
				$BalFined = $FinedPayable - $FinedPaid;
									 
				$query3 = "select chargeID from tbstudentcharges where sType = '$StuType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$num_rows3 = mysql_num_rows($result3);
				if ($num_rows3 > 0 ) {
					while ($row3 = mysql_fetch_array($result3)) 
					{
						$counter = $counter+1;
						$chargeID = $row3["chargeID"];
							
						
						$query4 = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$Stu_AdmnNo' And Term = '2nd Term' And Status = '0') And ChargeID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result4 = mysql_query($query4,$conn);
						$dbarray4 = mysql_fetch_array($result4);
						$AmtPayable  = $dbarray4['Payable'];
						$PaidAmount  = $dbarray4['PaidAmount'];
						if($PaidAmount ==""){
							$PaidAmount  = "0";
						}
						
						$Balance = 0;
						
						$Balance = $AmtPayable - $PaidAmount;
						$TotalPay = $TotalPay + $AmtPayable;
						$TotalPaid = $TotalPaid +$PaidAmount;
						$TotalBal = $TotalBal +$Balance;
					 }
				 }
				 $GrandPayable = number_format($TotalPay + $FinedPayable,0);
				 $GrandPaid = number_format($TotalPaid + $FinedPaid,0);
				 $GrandBal = number_format($TotalBal + $BalFined,0);
				 
				 //echo $Stu_AdmnNo.",".$StudentName.",".$Term1.",".$GrandPayable.",".$GrandPaid.",".$GrandBal.",".$Contact;
				 $isSend_Status = sendFees($Stu_AdmnNo,$StudentName,$Term2,$GrandPayable,$GrandPaid,$GrandBal,$Contact);
			}
			if(isset($_POST['3rdTerm']))
			{
				$Term3 = "3rd Term";
				$BalFined = 0;
				$FinedPaid = 0;
				$FinedPayable = 0;
				
				$counter = 0;
				$TotalPay = 0;
				$TotalPaid= 0;
				$TotalBal= 0;
				$GrandPayable = 0;
				$GrandPaid = 0;
				$GrandBal = 0;
				
				$query3 = "select * from tbreceipt where AdmnNo='$Stu_AdmnNo' And Term ='3rd Term' And Status ='0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$dbarray3 = mysql_fetch_array($result3);
				$ReceiptNo  = $dbarray3['ID'];
				$FinedPayable  = $dbarray3['FinePayable'];
				$FinedPaid  = $dbarray3['FinePaid'];
				$BalFined = $FinedPayable - $FinedPaid;
									 
				$query3 = "select chargeID from tbstudentcharges where sType = '$StuType' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$num_rows3 = mysql_num_rows($result3);
				if ($num_rows3 > 0 ) {
					while ($row3 = mysql_fetch_array($result3)) 
					{
						$counter = $counter+1;
						$chargeID = $row3["chargeID"];
							
						
						$query4 = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$Stu_AdmnNo' And Term = '3rd Term' And Status = '0') And ChargeID = '$chargeID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						$result4 = mysql_query($query4,$conn);
						$dbarray4 = mysql_fetch_array($result4);
						$AmtPayable  = $dbarray4['Payable'];
						$PaidAmount  = $dbarray4['PaidAmount'];
						if($PaidAmount ==""){
							$PaidAmount  = "0";
						}
						
						$Balance = 0;
						
						$Balance = $AmtPayable - $PaidAmount;
						$TotalPay = $TotalPay + $AmtPayable;
						$TotalPaid = $TotalPaid +$PaidAmount;
						$TotalBal = $TotalBal +$Balance;
					 }
				 }
				 $GrandPayable = number_format($TotalPay + $FinedPayable,0);
				 $GrandPaid = number_format($TotalPaid + $FinedPaid,0);
				 $GrandBal = number_format($TotalBal + $BalFined,0);
				 
				 //echo $Stu_AdmnNo.",".$StudentName.",".$Term1.",".$GrandPayable.",".$GrandPaid.",".$GrandBal.",".$Contact;
				 $isSend_Status = sendFees($Stu_AdmnNo,$StudentName,$Term3,$GrandPayable,$GrandPaid,$GrandBal,$Contact);
			}
			if($isSend_Status == "False"){
				$errormsg = "<font color = red size = 1>SMS messages not sent completely.</font>";
			}elseif($isSend_Status == "True"){
				$errormsg = "<font color = blue size = 1> SMS messages sent Successfully.</font>";
			}			
		}
	}
	if(isset($_POST['SubmitRefundables']))
	{
		$PageHasError = 0;
		$ischecked = $_POST['ischecked'];
		if($ischecked == "Class"){
			$OptClass = $_POST['OptClass'];
			if($_POST['OptClass'] =="0")
			{
				$errormsg = "<font color = red size = 1>Select Class</font>";
				$PageHasError = 1;
			}
		}				
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=fees_rpt.php?pg=Refundable amount details&ty=$ischecked&cls=$OptClass\">";
			exit;
		}
	}

$stmt = $pdo->prepare("SELECT * FROM tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varclass = json_encode($json_row);

$stmt = $pdo->prepare("SELECT * FROM tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row2 = array ('items'=>$row2);             
$varstudent2 = json_encode($json_row2);
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
.style22 {
	font-weight: bold;
	color: #FFFFFF;
}
.style24 {color: #FFFFFF}
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
<script type="text/javascript">
 
dojo.addOnLoad(function() {
		document.getElementById('divLoading').style.display = 'none';
		//document.getElementById('divLoading').style.display = 'block';				
		formDlg = dijit.byId("formDialog");					
        
 var dataJson = <?php echo $varclass; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson
            }),
            //autoComplete: true,
			searchAttr: "Class_Name",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectclass",
            onChange: function setstudent(){
	
	//alert('im good');
	setstudentinput();
      studentselect1();	               },
                   
		     },"selectclass");
		
	});	
</script>


<script type="text/javascript">

function setstudentinput(){
	//alert('im good');
	document.getElementById('divLoading').style.display = 'block';
	dojo.xhrGet({
         url: 'selectstudent.php',
		 handleAs: 'json',
         load: setstudentinput3,
         error: helloError,
         content: {name: dijit.byId('selectclass').attr("value")}
      });
	 }
function setstudentfee(){
	document.getElementById('divLoading').style.display = 'block';
	var studentnameadmissonnumber = document.getElementById('studentname3').value;
	/*var values = studentnameadmissionnumber.split('/');
	var studentnamenew = values[0];
	var admissionnumber = values[1];*/
	var studentnametxt = dijit.byId("studentname"); 
       studentnametxt.attr("value",studentnameadmissonnumber);

	alert('View/Update Fee Payment for ' + studentnameadmissonnumber);
	//alert('im good');
	dojo.xhrGet({
         url: 'selectstudentfeeupdate.php',
		 handleAs: 'json',
         load: setstudentinput2,
         error: helloError,
         content: {name: dijit.byId('selectclass').attr("value"), name2: studentnameadmissonnumber }
      });
	 }
function setstudentinput2(data,ioArgs) {
        
	         
		               
					   
						var chargelength = data.ChargeName.length;
						var entrydate = data.EntryDate;
						var listBox = document.getElementById('feedisplay');
						listBox.innerHTML = '';
						//listBox.innerHTML = 'Bello Kolade';//clear the names on the page
				          
					     var totalChargeValue = 0;
						 var totalPaymentValue = 0;
						 var totalBalanceValue = 0;
						 var studentChargeFinalValueTotal = 0;
						 var j = 0;
						//iterate over retrieved entries and display them on the page
						for ( var i = 0; i < chargelength; i++ )
						{    
						    var entryRow = document.createElement( 'tr' );
						    //var j = chargelength;
							var studentChargeName = data.ChargeName[i];
							var studentChargeValue = data.Amount[i];
							var studentPaymentValue = data.StudentPayment[i];
							var studentDiscountValue = data.StudentDiscount[i];

							var studentBalanceValue = data.StudentBalance[i];
							studentChargeValue = parseInt(studentChargeValue);
							if(isNaN(studentChargeValue)){
							studentChargeValue = 0;
							}
							 else{
							   totalChargeValue += studentChargeValue;
							 }
							 studentPaymentValue = parseInt(studentPaymentValue);
							if(isNaN(studentPaymentValue)){
							studentPaymentValue = 0;
							}
							 else{
							   totalPaymentValue += studentPaymentValue;
							 }
							 studentBalanceValue = parseInt(studentBalanceValue);
							if(isNaN(studentBalanceValue)){
							studentBalanceValue = 0;
							}
							 else{
							   totalBalanceValue += studentBalanceValue;
							 }

							 studentDiscountValue = parseInt(studentDiscountValue);
							if(isNaN(studentDiscountValue)){
							studentDiscountValue = 0;
							}
							var studentChargeFinalValue = parseInt(studentChargeValue) - parseInt(studentDiscountValue);
							studentChargeFinalValueTotal+=studentChargeFinalValue;

							 //var studentDiscountValue = 0;
							 //var studentChargeFinalValue = 0;
							//dynamically create a div element for each entry and a fieldset element to place it in
							 j++;
							var l = 0;
							if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 24;
							var txtNode1 = document.createTextNode(j);
							entryCell.appendChild(txtNode1);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 129;
							entryCell.id = i;
							var txtNode2 = document.createTextNode(studentChargeName);
							entryCell.appendChild(txtNode2);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 82;
							var entryInput = document.createElement('input');
							entryInput.value = studentChargeValue;
							entryInput.id = 'StudentCharge' + j;
							entryInput.type = 'text';
							entryInput.disabled = 'true';
							entryCell.appendChild(entryInput);
							entryRow.appendChild(entryCell);
							l++;
							   }
							if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 82;
							var entryInput4 = document.createElement('input');
							entryInput4.value = studentDiscountValue;
							entryInput4.id = 'StudentDiscount' + j;
							entryInput4.name = 'StudentDiscountA' + j;
							entryInput4.type = 'text';
							//entryInput.disabled = 'true';
							entryInput4.onchange = AddStudentPayment;
							entryCell.appendChild(entryInput4);
							entryRow.appendChild(entryCell);
							l++;
							   }
							if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 82;
							var entryInput5 = document.createElement('input');
							entryInput5.value = studentChargeFinalValue;
							entryInput5.id = 'StudentChargeFinal' + j;
							entryInput5.type = 'text';
							entryInput5.disabled = 'true';
							entryCell.appendChild(entryInput5);
							entryRow.appendChild(entryCell);
							l++;
							   }
							  
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 87;
							var entryInput2 = document.createElement('input');
							entryInput2.id = 'StudentPayment' + j;
							entryInput2.name = 'StudentPaymentA' + j;
							entryInput2.type = 'text';
							entryInput2.value = studentPaymentValue;
							entryInput2.onchange = AddStudentPayment;
							entryCell.appendChild(entryInput2);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 103;
							var entryInput3 = document.createElement('input');
							entryInput3.id = 'StudentBalance' + j;
							entryInput3.name = 'StudentBalanceA' + j;
							entryInput3.type = 'text';
							entryInput3.value = studentBalanceValue;
							entryInput3.disabled = 'true';
							entryCell.appendChild(entryInput3);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 0;
							var entryInput = document.createElement('input');
							entryInput.value = studentChargeValue;
							entryInput.name = 'StudentChargeA' + j;
							entryInput.type = 'hidden';
							entryCell.appendChild(entryInput);
							entryRow.appendChild(entryCell);
							l++;
							   }
							//j++;
						    listBox.appendChild(entryRow);
						
						} 
						chargelength = parseInt(chargelength);
						var i = 1;//end for
					var entryRow = document.createElement( 'tr' );
					var entryCell = document.createElement('td');
					var ChargeInputLength = document.createElement('input');
					      ChargeInputLength.id = 'chargeinputlength' + i;
						  ChargeInputLength.name = 'chargeinputlength' + i;
							ChargeInputLength.value = chargelength;
							ChargeInputLength.type = 'hidden';
					entryCell.appendChild(ChargeInputLength);
					entryRow.appendChild(entryCell);
					listBox.appendChild(entryRow);
					
					var totalAmount = document.getElementById('TotalAmount');
				     totalAmount.value = totalChargeValue;
					 var totalPayment = document.getElementById('TotalAmount2');
				     totalPayment.value = totalPaymentValue;
					 var totalBalance = document.getElementById('TotalAmount3');
				     totalBalance.value = totalBalanceValue;
					 
					 var studentclass = dijit.byId("selectclass").value;
					 var studenttxt = dijit.byId("studentclass"); 
		             studenttxt.attr("value",studentclass);
					 
					 var grandpaid = 0;
                     var grandbalance = 0;
                     var fineAmount = 0;
                     var finePaidAmount = 0;
                     var fineBalance = 0;

                     var fineAmount1 = document.getElementById('FinedPayable');
                     fineAmount1.value = fineAmount ;
                     var finePaidAmount1 = document.getElementById('FinedPayable2');
                     finePaidAmount1.value = finePaidAmount ;
                     var Finebalance1 = document.getElementById('Finebalance');
                     Finebalance1.value = fineBalance;

                     var GrandPaid= document.getElementById('GrandPaid');
                     GrandPaid.value = totalPaymentValue;
                     var GrandBalance = document.getElementById('GrandBalance');
                     GrandBalance.value = totalBalanceValue;

					AddStudentPayment();
					document.getElementById('divLoading').style.display = 'none';
					alert('Fee For Student Was Updated Last On ' + entrydate + '  ' + 'yy/mm/dd');
			}
			
function setstudentinput3(data,ioArgs) {
        
	         
		               
					   
						var chargelength = data.ChargeName.length;
						var entrydate = data.EntryDate;
						var listBox = document.getElementById('feedisplay');
						listBox.innerHTML = '';
						//listBox.innerHTML = 'Bello Kolade';//clear the names on the page
				          
					     var totalChargeValue = 0;
						 var totalPaymentValue = 0;
						 var totalBalanceValue = 0;
						 var j = 0;
						//iterate over retrieved entries and display them on the page
						for ( var i = 0; i < chargelength; i++ )
						{    
						    var entryRow = document.createElement( 'tr' );
						    //var j = chargelength;
							var studentChargeName = data.ChargeName[i];
							var studentChargeValue = data.Amount[i];
							var studentPaymentValue = data.StudentPayment[i];
							//var studentDiscountValue = data.StudentDiscount[i];
							//if(isNaN(studentDiscountValue)){
							//studentDiscountValue = 0;
							//}
							//var studentChargeFinalValue = parseInt(studentChargeValue) - parseInt(studentDiscountValue);
							var studentBalanceValue = data.StudentBalance[i];
							studentChargeValue = parseInt(studentChargeValue);
							if(isNaN(studentChargeValue)){
							studentChargeValue = 0;
							}
							 else{
							   totalChargeValue += studentChargeValue;
							 }
							 studentPaymentValue = parseInt(studentPaymentValue);
							if(isNaN(studentPaymentValue)){
							studentPaymentValue = 0;
							}
							 else{
							   totalPaymentValue += studentPaymentValue;
							 }
							 studentBalanceValue = parseInt(studentBalanceValue);
							if(isNaN(studentBalanceValue)){
							studentBalanceValue = 0;
							}
							 else{
							   totalBalanceValue += studentBalanceValue;
							 }
							 
							 var studentDiscountValue = 0;
							var studentChargeFinalValue = 0;
							 
							//dynamically create a div element for each entry and a fieldset element to place it in
							 j++;
							var l = 0;
							if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 24;
							var txtNode1 = document.createTextNode(j);
							entryCell.appendChild(txtNode1);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 80;
							entryCell.id = i;
							var txtNode2 = document.createTextNode(studentChargeName);
							entryCell.appendChild(txtNode2);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 82;
							var entryInput = document.createElement('input');
							entryInput.value = studentChargeValue;
							//entryInput.value = 10;
							entryInput.id = 'StudentCharge' + j;
							entryInput.type = 'text';
							//entryInput.size = '10';
							entryInput.disabled = 'true';
							entryCell.appendChild(entryInput);
							entryRow.appendChild(entryCell);
							l++;
							   }
							if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 50;
							var entryInput4 = document.createElement('input');
							entryInput4.value = studentDiscountValue;
							entryInput4.id = 'StudentDiscount' + j;
							entryInput4.name = 'StudentDiscountA' + j;
							entryInput4.type = 'text';
							//entryInput.disabled = 'true';
							entryInput4.onchange = AddStudentPayment;
							entryCell.appendChild(entryInput4);
							entryRow.appendChild(entryCell);
							l++;
							   }
							if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 50;
							var entryInput5 = document.createElement('input');
							entryInput5.value = studentChargeFinalValue;
							entryInput5.id = 'StudentChargeFinal' + j;
							entryInput5.name = 'StudentChargeFinalA' + j;
							entryInput5.type = 'text';
							entryInput5.disabled = 'true';
							entryCell.appendChild(entryInput5);
							entryRow.appendChild(entryCell);
							l++;
							   }
							  
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 87;
							var entryInput2 = document.createElement('input');
							entryInput2.id = 'StudentPayment' + j;
							entryInput2.name = 'StudentPaymentA' + j;
							entryInput2.type = 'text';
							entryInput2.value = studentPaymentValue;
							entryInput2.onchange = AddStudentPayment;
							entryCell.appendChild(entryInput2);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 103;
							var entryInput3 = document.createElement('input');
							entryInput3.id = 'StudentBalance' + j;
							entryInput3.name = 'StudentBalanceA' + j;
							entryInput3.type = 'text';
							entryInput3.value = studentBalanceValue;
							entryInput3.disabled = 'true';
							entryCell.appendChild(entryInput3);
							entryRow.appendChild(entryCell);
							l++;
							   }
							 if(l < 8){
							var entryCell = document.createElement('td');
							entryCell.width = 0;
							var entryInput = document.createElement('input');
							entryInput.value = studentChargeValue;
							entryInput.name = 'StudentChargeA' + j;
							entryInput.type = 'hidden';
							entryCell.appendChild(entryInput);
							entryRow.appendChild(entryCell);
							l++;
							   }
							//j++;
						    listBox.appendChild(entryRow);
						
						} 
						chargelength = parseInt(chargelength);
						var i = 1;//end for
					var entryRow = document.createElement( 'tr' );
					var entryCell = document.createElement('td');
					var ChargeInputLength = document.createElement('input');
					      ChargeInputLength.id = 'chargeinputlength' + i;
						  ChargeInputLength.name = 'chargeinputlength' + i;
							ChargeInputLength.value = chargelength;
							ChargeInputLength.type = 'hidden';
					entryCell.appendChild(ChargeInputLength);
					entryRow.appendChild(entryCell);
					listBox.appendChild(entryRow);
					
					var totalAmount = document.getElementById('TotalAmount');
				     totalAmount.value = totalChargeValue;
					 var totalPayment = document.getElementById('TotalAmount2');
				     totalPayment.value = totalPaymentValue;
					 var totalBalance = document.getElementById('TotalAmount3');
				     totalBalance.value = totalBalanceValue;
					 
					 var studentclass = dijit.byId("selectclass").value;
					 var studenttxt = dijit.byId("studentclass"); 
		             studenttxt.attr("value",studentclass);
					 
					 var grandpaid = 0;
                     var grandbalance = 0;
                     var fineAmount = 0;
                     var finePaidAmount = 0;
                     var fineBalance = 0;

                     var fineAmount1 = document.getElementById('FinedPayable');
                     fineAmount1.value = fineAmount ;
                     var finePaidAmount1 = document.getElementById('FinedPayable2');
                     finePaidAmount1.value = finePaidAmount ;
                     var Finebalance1 = document.getElementById('Finebalance');
                     Finebalance1.value = fineBalance;

                     var GrandPaid= document.getElementById('GrandPaid');
                     GrandPaid.value = totalPaymentValue;
                     var GrandBalance = document.getElementById('GrandBalance');
                     GrandBalance.value = totalBalanceValue;

					//AddStudentPayment();
					//alert('Fee For Student Was Updated Last On ' + entrydate);
					document.getElementById('divLoading').style.display = 'none';
			}
	
	
	function studentselect1(){
	//alert('im good');
	dojo.xhrGet({
         url: 'selectstudent1.php',
		 handleAs: 'json',
         load: studentselect,
         error: helloError1,
         content: {name1: dijit.byId('selectclass').attr("value") }
      });
	 }
			
	function studentselect(data,ioArgs){
		var StudentName = document.getElementById('studentname2');
		  StudentName.innerHTML = "";
		 var studentnamelength = data.studentname.length
		  var studentnameselect ='<select id = "studentname3" onchange = "setstudentfee();" ><option >Student Name</option>';
		 for ( var i = 0; i < studentnamelength; i++ ){
			 var studentname = data.studentname[i];
			 var admission_number = data.admission_number[i];
			 studentnameselect += '<option >' + studentname + '_' + admission_number + '</option>';		
			 }
			 studentnameselect+='</select>';
		
		 StudentName.innerHTML = studentnameselect; 	  
		 //var studentinput = document.createElement('select');
		 //studentinput.id = 'studentname';
		// studentinput.value = studentname;
		// StudentName.appendChild(studentinput);
		   var studentname = '';
			var studentnametxt = dijit.byId("studentname"); 
		     studentnametxt.attr("value",studentname);
		 }
	 
	 
	 
function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }
function helloError1(data, ioArgs) {
        alert('Error when retrieving data from the server2!');
		//var listBox = document.getElementById('Names');
     }
	function AddStudentPayment() {
		var StudentTotalPayment = 0 ;
		var StudentTotalBalance = 0 ;
		var StudentTotalChargeFinal = 0;
		var chargelength = document.getElementById('chargeinputlength1').value;
		for ( var i = 1; i <= chargelength; i++ ){
			var StudentPayment = document.getElementById('StudentPayment' + i).value;
			StudentPayment = parseInt(StudentPayment);
			if(isNaN(StudentPayment)){
							StudentPayment = 0;
							}
							 else{
							   StudentTotalPayment += StudentPayment;
							 }
			var StudentCharge = document.getElementById('StudentCharge' + i).value;
			
			var StudentDiscount = document.getElementById('StudentDiscount'+ i).value;
			
			 StudentDiscount = parseInt(StudentDiscount);
			
			if(isNaN(StudentDiscount)){
							StudentDiscount = 0;
							}
			
			//var StudentChargeFinal = document.getElementById('StudentChargeFinal'+ i).value;
			
			//StudentChargeFinal = parseInt(StudentChargeFinal);
			
			
			
	        var StudentBalance = parseInt(StudentCharge) - parseInt(StudentDiscount) - StudentPayment;
			 var balance = document.getElementById('StudentBalance' + i);
			 balance.value = StudentBalance;
			 //alert(balance);
			 StudentBalance = parseInt(StudentBalance);
			if(isNaN(StudentBalance)){
							StudentBalance = 0;
							}
							 else{
							   StudentTotalBalance += StudentBalance;
							 }
							 
			var StudentChargeFinal2 = parseInt(StudentCharge) - parseInt(StudentDiscount);
		
		 var StudentChargeFinal = document.getElementById('StudentChargeFinal' + i);
		  //alert(StudentChargeFinal);
		
		//document.getElementById('StudentChargeFinal' + i).value = 3;
			StudentChargeFinal.value = StudentChargeFinal2;
			 
			 StudentChargeFinal2 = parseInt(StudentChargeFinal2);
			 if(isNaN(StudentChargeFinal2)){
							StudentChargeFinal2 = 0;
							}
							else{
							   StudentTotalChargeFinal += StudentChargeFinal2;
							 }
		}
		
		
		
		var StudentTotal = document.getElementById('TotalAmount2');
		     StudentTotal.value = StudentTotalPayment;
		var StudentBalance2 = document.getElementById('TotalAmount3');
		StudentBalance2.value = StudentTotalBalance;
		
		var StudentChargeFinal3 = document.getElementById('ChildsBill');
		StudentChargeFinal3.value = StudentTotalChargeFinal;
		
		
		var fineAmount = document.getElementById('FinedPayable').value;
		 fineAmount = parseInt(fineAmount);
		 if(isNaN(fineAmount)){
							fineAmount = 0;
							}
		
		var finePaidAmount = document.getElementById('FinedPayable2').value;
		 finePaidAmount = parseInt(finePaidAmount);
		   if(isNaN(finePaidAmount)){
							finePaidAmount = 0;
							}
		   var fineBalance = fineAmount - finePaidAmount;
		    // fineBalance = 45;
		   
		   var Finebalance = document.getElementById('Finebalance');
		       Finebalance.value = fineBalance;
		//alert(StudentBalance);
		var grandpaid = 0;
		grandpaid = StudentTotalPayment  + finePaidAmount;
		var GrandPaid= document.getElementById('GrandPaid');
		if(finePaidAmount == 0)
		{
		   GrandPaid.value = StudentTotalPayment;
		}else{
			GrandPaid.value = grandpaid;
		}
		var grandbalance = 0;
		grandbalance = StudentTotalBalance + fineBalance
		var GrandBalance = document.getElementById('GrandBalance');
		if(fineAmount == 0){
		GrandBalance.value = StudentTotalBalance;
		}else{
			
		GrandBalance.value = grandbalance;
		}
	 }
	 
	 //function updateresult(){
		 // alert('im good');
		 // }
function updatesuccessful(data,ioArgs) {
	//var studentname = data.studentname;
	//var chargelength = data.chargelength;
	//var studentcharge = data.studentcharge;
	//var studentpayment = data.studentpayment;
	//var studenttotal = data.studenttotal;
	
	document.getElementById('divLoading').style.display = 'none';
	//alert(chargelength + studentcharge + studentpayment + studentname );
	alert('Fee updated successfully');
	 
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
			  <TD width="219" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="859" align="center" valign="top">
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
		if ($SubPage == "Update Student Fee") {
?>
				<?PHP echo $errormsg; ?>
				<form id="updatestudentresult" method="post">
				<div>
					<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
					<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
					<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
					</div>
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
					</script>
				
			 <TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="16%" valign="top"  align="left"> Select Entry Date:</TD>
					  <TD width="30%" valign="top" align="left"> <input dojoType="dijit.form.DateTextBox" name="receiptdate" id="receiptdate" size="40"> *Month/Day/Year </TD>
					  <TD width="31%" valign="top"  align="left">Term: <?PHP $query = "select * from section where Active='1'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	 $TermName = $dbarray['Section']; 
	 echo $TermName;?>
					 
						</TD>
					</TR>
					<TR>
					  <TD width="16%" valign="top"  align="left"> Student Class:</TD>
					 
					  <TD width="23%" valign="top"  align="left"><input dojoType="dijit.form.TextBox" name="studentclass" id="studentclass" size="40" style="background-color:#FFFF99"/> </TD>
					  <TD width="31%" valign="top"  align="left"></TD>
					</TR>
					<TR>
					  <TD width="16%" valign="top"  align="left">Student Name:  </TD>
					  <TD width="30%" valign="top"  align="left"><input dojoType="dijit.form.TextBox" name="studentname" id="studentname" size="40" style="background-color:#FFFF99"/></TD>
					  <TD width="23%" valign="top"  align="left">Parent/Guardian Name:  </TD>
					  <TD width="31%" valign="top"  align="left"><input dojoType="dijit.form.TextBox" name="fathername" id="fathername" size="40" style="background-color:#FFFF99"/></TD>
					</TR>
					<TR>
					  <TD width="16%" valign="top"  align="left">Payment Mode:   </TD>
					  <TD width="30%" valign="top"  align="left"><input dojoType="dijit.form.TextBox" name="paymentmode" id="paymentmode" size="40" style="background-color:#FFFF99"/></TD>
					  <TD width="23%" valign="top"  align="left">Remark  </TD>
					  <TD width="31%" valign="top"  align="left"><textarea name="Remark" cols="35" rows="3"><?PHP echo $Remark; ?></textarea></TD>
					</TR>
					<TR>
					  <TD colspan="3" valign="top"  align="left">Please don't leave any blank space (paid amount should be in figures only)
					  	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
						  <tr bgcolor="#ECE9D8">
							<td width="10"><strong>Sr.</strong></td>
							<td width="40"><strong>Particulars</strong></td>
							<td width="117"><div align="center"><strong>Amount Charged</strong></div></td>
                            <td width="50"><div align="center"><strong>Discount</strong></div></td>
                            <td width="137"><div align="center"><strong>Child's Bill</strong></div></td>
							<td width="121"><div align="center"><strong>Paid Amount</strong></div></td>
							<td width="139"><div align="center"><strong>Balance</strong></div></td>
						  </tr>
                             <tr><td colspan="7" align="center"><table id="feedisplay" align="center"></table></td></tr>
							  

						   <tr>
							<td width="10">&nbsp;</td>
							<td width="40"><div align="right"><strong>TOTAL</strong></div></td>
							<td width="117"><input name="TotalAmount" id="TotalAmount" type="text" size="13"  style="text-align:right; background-color:#FFFFCC" disabled="disabled"></td>
                            <td width="50"><input name="Discount" id="Discount" type="text" size="13"  style="text-align:right; background-color:#FFFFCC" disabled="disabled"></td>
                            <td width="137"><input name="ChildsBill" id="ChildsBill" type="text" size="13"  style="text-align:right; background-color:#FFFFCC" disabled="disabled"></td>
							<td width="121"><input name="TotalPayable" id="TotalAmount2" type="text" size="13"  style="text-align:right; background-color:#FFFFCC" disabled="disabled"></td>
							<td width="139"><input name="TotalPaid" id="TotalAmount3" type="text" size="13"  style="text-align:right; background-color:#FFFFCC" disabled="disabled"></td>
						  </tr>
						  <tr>
							<td width="10">&nbsp;</td>
							<td width="40"><strong><div align="right">FINE</div></strong></td>
							<td width="137"><input name="FinedPayable" id="FinedPayable" type="text" size="13" onChange="AddStudentPayment();" style="text-align:right;"/></td>
							<td width="121"><input name="FinedPayable2" id="FinedPayable2" type="text" size="13" onChange="AddStudentPayment();" style="text-align:right;"/></td>
                            <td width="117"><input name="FinedPayable3" id="FinedPayable3" type="text" size="13" onChange="AddStudentPayment();" style="text-align:right;"/></td>
							<td width="121"><input name="FinedPayable4" id="FinedPayable4" type="text" size="13" onChange="AddStudentPayment();" style="text-align:right;"/></td>
							<td width="139"><input name="Finebalance" id="Finebalance" type="text" size="13" disabled ="true" style="text-align:right;"/></td>
						  </tr>
						  <tr>
							<td width="10">&nbsp;</td>
							<td width="40">&nbsp;</td>
                            <td width="30">&nbsp;</td>
							<td width="118">&nbsp;</td>
							<td width="137"><div align="right"><strong>GRAND TOTAL</strong></div></td>
							<td width="121"><input name="GrandPaid" id="GrandPaid" type="text" size="13"  style="text-align:right; background-color:#FFFFCC" disabled="disabled"></td>
							<td width="139"><input name="GrandBalance" id="GrandBalance" type="text" size="13"  style="text-align:right; background-color:#FFFFCC" disabled="disabled"></td>
						  </tr>
					  </table>
					  </TD>
					  <TD width="31%" valign="top"  align="left"><table><tr><td><b>Select Student Class</b></td></tr><tr><td><input id="selectclass"></td></tr>
                      <tr><td ><b>Select Student Name</b></td></tr><tr><td id="studentname2" > </td></tr></table>
					 
					  </TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
                             
							 <button dojoType="dijit.form.Button" name="updatereceipt" id="updatereceipt">Update Payment <script type="dojo/method" event="onClick">
	
	//if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) 
	   // {return false;}
		var EntryDate = document.getElementById('entrydate');		
				     var datentry = dijit.byId("receiptdate").value;
					 EntryDate.value = datentry;
				
		 var datElementValue = document.getElementById("entrydate");
	    if ( datElementValue.value.length == 0){
		   alert('Please Select A Date');
		   datElementValue.focus();
		   return false;
	      }	
		 // var datElementValue1 = document.getElementById("entrydate").value;
		  //alert(datElementValue1 + datentry);
		  document.getElementById('divLoading').style.display = 'block';
       dojo.xhrPost({
      url: 'updatestudentfee.php',
	  //handleAs: 'json',
      load: updatesuccessful,
      error: helloError,
      form: 'updatestudentresult'
                  });        
		
		 // alert('im good');
			
			 </script>
                <button dojoType="dijit.form.Button" name="printreceipt" id="printreceipt">Print Receipt <script type="dojo/method" event="onClick">
				window.print() ;
                     
                </script></button>
                <button dojoType="dijit.form.Button" name="checklatestbalance" id="checklatestbalance">Check Latest Balance <script type="dojo/method" event="onClick">
				   AddStudentPayment();
                     </script></button>
						     
						     
				
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
                <input type="hidden" name="entrydate" id="entrydate" />
                </form>
				
<?PHP
		}elseif ($SubPage == "Fee structure") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="fees.php?subpg=Fee structure">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD align="left" valign="top">
					  <p style="margin-left:100px"><strong>Monthly Fee Structure :</strong></p>
						<TABLE align="center" cellpadding="10" style="WIDTH: 80%;">
						<TBODY>
						<TR bgcolor="#00CCFF">
						  <TD width="161"  align="center" valign="top" bgcolor="#00CCFF"><span class="style24 style3"><strong>Charge Name</strong></span></TD>
						  <TD width="104"  align="center" valign="top" bgcolor="#00CCFF"><span class="style24 style3"><strong>From Month</strong></span></TD>
						  <TD width="119"  align="left" valign="top" bgcolor="#00CCFF"><span class="style3 style22">To Month</span></TD>
						</TR>
<?PHP
						$Count_Charge = 0;
						$query = "select ID,ChargeName from tbchargemaster where ChargeApplicable = '1' where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ChargeName";
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
								
								$query2 = "select * from tbmonthlyCharges where ChargeID='$chargeID' And Term = '$Activeterm' where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
								$result2 = mysql_query($query2,$conn);
								$dbarray2 = mysql_fetch_array($result2);
								$frMonth  = $dbarray2['FromMonth'];
								$toMonth  = $dbarray2['Tomonth'];
?>
								  <TR>
									<TD width="161" bgcolor="#F4F4F4"><?PHP echo $ChargeName; ?></td>
									<TD width="104" align="center">
									 <input type="hidden" name="chg<?PHP echo $Count_Charge; ?>" value="<?PHP echo $chargeID; ?>">
									  <select name="OptFrm<?PHP echo $Count_Charge; ?>">
									    <option value="">Select Month</option>
<?PHP
										if($frMonth == 1){
									    	echo"<option value='01' selected='selected'>January</option>";
										}else{
											echo"<option value='01'>January</option>";
										}
										
										if($frMonth == 2){
									    	echo"<option value='02' selected='selected'>February</option>";
										}else{
											echo"<option value='02'>February</option>";
										}
										
										if($frMonth == 3){
									    	echo"<option value='03' selected='selected'>May</option>";
										}else{
											echo"<option value='03'>May</option>";
										}
										
										if($frMonth == 4){
									    	echo"<option value='04' selected='selected'>April</option>";
										}else{
											echo"<option value='04'>April</option>";
										}
										
										if($frMonth == 5){
									    	echo"<option value='05' selected='selected'>May</option>";
										}else{
											echo"<option value='05'>May</option>";
										}
										
										if($frMonth == 6){
									    	echo"<option value='06' selected='selected'>June</option>";
										}else{
											echo"<option value='06'>June</option>";
										}
										
										if($frMonth == 7){
									    	echo"<option value='07' selected='selected'>July</option>";
										}else{
											echo"<option value='07'>July</option>";
										}
										
										if($frMonth == 8){
									    	echo"<option value='08' selected='selected'>August</option>";
										}else{
											echo"<option value='08'>August</option>";
										}
										
										if($frMonth == 9){
									    	echo"<option value='09' selected='selected'>September</option>";
										}else{
											echo"<option value='09'>September</option>";
										}
										
										if($frMonth == 10){
									    	echo"<option value='10' selected='selected'>October</option>";
										}else{
											echo"<option value='10'>October</option>";
										}
										
										if($frMonth == 11){
									    	echo"<option value='11' selected='selected'>November</option>";
										}else{
											echo"<option value='11'>November</option>";
										}
										
										if($frMonth == 12){
									    	echo"<option value='12' selected='selected'>December</option>";
										}else{
											echo"<option value='12'>December</option>";
										}
?>
									    </select>
									</td>
									<TD width="119" align="center">
									<label>
									  <select name="OptTo<?PHP echo $Count_Charge; ?>">
									    <option value="">Select Month</option>
<?PHP
										if($toMonth == 1){
									    	echo"<option value='01' selected='selected'>January</option>";
										}else{
											echo"<option value='01'>January</option>";
										}
										
										if($toMonth == 2){
									    	echo"<option value='02' selected='selected'>February</option>";
										}else{
											echo"<option value='02'>February</option>";
										}
										
										if($toMonth == 3){
									    	echo"<option value='03' selected='selected'>May</option>";
										}else{
											echo"<option value='03'>May</option>";
										}
										
										if($toMonth == 4){
									    	echo"<option value='04' selected='selected'>April</option>";
										}else{
											echo"<option value='04'>April</option>";
										}
										
										if($toMonth == 5){
									    	echo"<option value='05' selected='selected'>May</option>";
										}else{
											echo"<option value='05'>May</option>";
										}
										
										if($toMonth == 6){
									    	echo"<option value='06' selected='selected'>June</option>";
										}else{
											echo"<option value='06'>June</option>";
										}
										
										if($toMonth == 7){
									    	echo"<option value='07' selected='selected'>July</option>";
										}else{
											echo"<option value='07'>July</option>";
										}
										
										if($toMonth == 8){
									    	echo"<option value='08' selected='selected'>August</option>";
										}else{
											echo"<option value='08'>August</option>";
										}
										
										if($toMonth == 9){
									    	echo"<option value='09' selected='selected'>September</option>";
										}else{
											echo"<option value='09'>September</option>";
										}
										
										if($toMonth == 10){
									    	echo"<option value='10' selected='selected'>October</option>";
										}else{
											echo"<option value='10'>October</option>";
										}
										
										if($toMonth == 11){
									    	echo"<option value='11' selected='selected'>November</option>";
										}else{
											echo"<option value='11'>November</option>";
										}
										
										if($toMonth == 12){
									    	echo"<option value='12' selected='selected'>December</option>";
										}else{
											echo"<option value='12'>December</option>";
										}
?>
									    </select>
									</label>
									</td>
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
							<TD>
							<div align="center">
							  <input type="hidden" name="Totalchg" value="<?PHP echo $Count_Charge; ?>">
							  <input type="submit" name="SubmitUpdate" value="Update">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Fee Defaulter Details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="fees.php?subpg=Fee Defaulter Details">
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
					  <TD align="left" valign="top"><p style="margin-left:170px;"><strong>Filter Criteria </strong></p>
					  	<table width="357" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
							if($_SESSION['module'] != "Teacher"){
?>
								<label>
								  <input name="ischecked" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAll; ?>>
								</label>All
<?PHP
							}
?>
							</td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					  </select>
							  
							  </td>
                          </tr>
						 <!-- <tr>
                            <td width="324"><strong>Select Term or Section to display </strong></td>
							</tr>
						  <tr>
                            <td width="324" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><label>
                              <input type="checkbox" name="1stTerm" value="1">
                            </label>
                              First Term</td>
                          </tr>
						  <tr>
                            <td width="324" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="2ndTerm" value="2">
                              Second Term</td>
                          </tr>
						  <tr>
                            <td width="324" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="3rdTerm" value="3">
                              Third Term</td>
                          </tr>-->
                        </table>					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrint" value="Print">
							  <input type="submit" name="NotifyFeeDef" value="Notify Parent" disabled>
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Student Fee Details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="fees.php?subpg=Student Fee Details">
				<div>
					<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
					<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
					<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
					</div>
					<script type="text/javascript">
					<!--
					dojo.addOnLoad(function() {
		//formDlg = dijit.byId("formDialog");					
        
 var dataJson = <?php echo $varclass; ?>;				
        new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson
            }),
            //autoComplete: true,
			searchAttr: "Class_Name",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectclass2",
            onChange: function setinput(){
	
	//alert('im good');
	setstudentinput();
      //studentselect1();	              
	    },
                   
		     },"selectclass2");
		
		 new dijit.form.ComboBox({
            store: new dojo.data.ItemFileReadStore({
                data: dataJson
            }),
            //autoComplete: true,
			searchAttr: "Class_Name",
            //query: {
               // state: "*"
            //},
            style: "width: 150px;",
           // required: true,
            id: "selectclass3",
            onChange: function setinput2(){
	
	//alert('im good');
     // setstudentinput2();
      setstudentnameinput();	              
	       },
                   
		     },"selectclass3");
		
					   
		
	});	
					
					
					function setstudentinput(){
					 // alert('im good');
					 var classname = dijit.byId("selectclass2").value; 
		             var classhiddeninput = document.getElementById('className5');
					 classhiddeninput.value = classname;
					 //alert(classname);
					}
					
					function setstudentinput5(){
					  var studentname = document.getElementById('studentname3').value; 
		             var classhiddeninput2 = document.getElementById('StudentName5');
					 classhiddeninput2.value = studentname;
					 //alert(studentname);
					}
					
				function setstudentnameinput(){
						//alert('im good');
						document.getElementById('divLoading').style.display = 'block';
						dojo.xhrGet({
                            url: 'selectstudent1.php',
		                    handleAs: 'json',
                            load: studentselectfee,
                            error: helloError3,
                             content: {name1: dijit.byId('selectclass3').attr("value")}
                               });
					}
					function setdisplay1(){
				    document.getElementById('selectclass5').style.display = 'none';
					  document.getElementById('selectstudent5').style.display = 'none';
					  document.getElementById('studentName5').style.display = 'none';
					   }
					function setdisplay2(){
					  //alert('im good');
					  document.getElementById('selectclass5').style.display = 'block';
					  document.getElementById('selectstudent5').style.display = 'none';
					  document.getElementById('studentName5').style.display = 'none';
					}
					function setdisplay3(){
						 document.getElementById('selectstudent5').style.display = 'block';
						  document.getElementById('studentName5').style.display = 'block';
						  document.getElementById('selectclass5').style.display = 'none';
					  //alert('im good');
					}
					
					function studentselectfee(data,ioArgs){
			//alert('im good');
			document.getElementById('divLoading').style.display = 'none';
		var StudentName = document.getElementById('studentName5');
		  StudentName.innerHTML = "";
		 var studentnamelength = data.studentname.length
	      var studentnameselect ='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Student Name:<select id = "studentname3" onchange = "setstudentinput5();" ><option >Student Name</option>';
		 for ( var i = 0; i < studentnamelength; i++ ){
			 var studentname = data.studentname[i];
			 studentnameselect += '<option >' + studentname + '</option>';		
			 }
			 studentnameselect+='</select>';
		
		               StudentName.innerHTML = studentnameselect; 	  
		 
		 }
		 
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
					
		
	 function helloError3(data, ioArgs) {
        alert('Error when retrieving data from the server3!');
		//var listBox = document.getElementById('Names');
     }
					// -->
				</script>
				<TABLE width="83%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="81%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="625" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr><td width="143"> 1. All Student</td>
                            <td width="61">
<?PHP
							if($_SESSION['module'] != "Teacher"){
?>
								<label>
                              	<input name="ischecked" type="radio" value="All" onClick="setdisplay1();" <?PHP echo $chkAll; ?>>
                            	</label>All
<?PHP
							}
?>
							
							
							</td><td width="371"></td>
                          </tr>
						  <tr>
                            <td> 2. By Class</td><td width="61"><input name="ischecked" type="radio" value="Class" onClick="setdisplay2();" <?PHP echo $chkclass; ?>></td> 
                              <td><div id="selectclass5" style="display:none">Select Class: <input id="selectclass2" > </div></td>
							<!--<select name="OptClass" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					  </select>-->
							  
							  
                          </tr>
						  <tr><td> 3. By Individual Student</td>
                            <td width="61"><input name="ischecked" type="radio" value="Name" onClick="setdisplay3();" <?PHP echo $chkname; ?>> </td>
                              <td align="left"><div  id="selectstudent5" style="display:none">Select Student Class: <input id="selectclass3" ></div>						  
                                
                                <!--<input name="GetStudentFee" type="submit" id="GetStudentFee" value="Go"></td>-->
                         </td> </tr>
                         <tr><td></td><td></td><td align="left"><div  id="studentName5" style="display:none"> </div></td></tr>
						  <!--<tr>
                            <td width="60"><strong>Select Term or Section to display </strong></td>
							</tr>
						  <tr>
                            <td width="60" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><label>
                              <input type="checkbox" name="1stTerm" value="1">
                            </label>
                              First Term</td>
                          </tr>
						  <tr>
                            <td width="60" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="2ndTerm" value="2">
                              Second Term</td>
                          </tr>
						  <tr>
                            <td width="60" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="3rdTerm" value="3">
                              Third Term</td>
                          </tr>-->
                        </table>
					  </TD>
					  <TD width="10%" valign="top">
					  <select name="OptStudent" size="20" multiple style="width:32px; background:#66FFFF;">
<?PHP
						if(isset($_POST['GetStudentFee']))
						{
							if($ischecked =="All"){
								$Search_Key = $_POST['Search_Key'];
								$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
							}elseif($ischecked =="Class"){
								$OptClass = $_POST['OptClass'];
								$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
							}elseif($ischecked =="Name"){
								$StuName = $_POST['StuName'];
								$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where INSTR(Stu_Full_Name,'$StuName') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
							}
						}else{
							$query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where ID = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Stu_Full_Name";
						}
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$Stu_Full_Name = $row["Stu_Full_Name"];
								$AdmnNo = $row["AdmissionNo"];
								
								if($OptStudent =="$AdmnNo"){
?>
                       					 	<option value="<?PHP echo $AdmnNo; ?>" selected="selected">&nbsp;&nbsp;&nbsp;<?PHP echo $Stu_Full_Name; ?></option>
<?PHP
										}else{
?>
                        					<option value="<?PHP echo $AdmnNo; ?>">&nbsp;&nbsp;&nbsp;<?PHP echo $Stu_Full_Name; ?></option>
<?PHP
										}
									}
								}
?>
                      </select>
					  </TD>
					</TR>
					<TR>
							<TD colspan="2">
							<div align="center">
							  <input type="submit" name="SubmitPrintStu_Wise" value="Print">
							  <input type="submit" name="NotifyParent2" value="Notify Parent" disabled>
                              <input type="hidden" name="className5" id="className5">
							  <input type="hidden" name="StudentName5" id="StudentName5">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Refundable amount details") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="fees.php?subpg=Refundable amount details">
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
					  <TD width="54%" align="left" valign="top"><p><strong>Filter Criteria </strong></p>
					  	<table width="503" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
							if($_SESSION['module'] != "Teacher"){
?>
								<label>
                              <input name="ischecked" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkAll; ?>>
                            	</label>
                              All Students
<?PHP
							}
?>
							  </td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					  </select>
							  
							  </td>
                          </tr>
						  <tr>
                            <td width="324">&nbsp;</td>
							</tr>
                        </table>
					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitRefundables" value="Print">
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
}elseif ($SubPage == "Fee Defaulter List") {
?>

<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="fees.php?subpg=Fee Defaulter List">
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
					  <TD align="left" valign="top"><p style="margin-left:170px;"><strong>Filter Criteria </strong></p>
					  	<table width="357" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
							if($_SESSION['module'] != "Teacher"){
?>
								<label>
								  <input name="ischecked2" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked2\',\'\')', 0)" <?PHP echo $chkAll; ?>>
								</label>All
<?PHP
							}
?>
							</td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked2" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked2\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass2" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					  </select>
							  
							  </td>
                          </tr>
						 <!-- <tr>
                            <td width="324"><strong>Select Term or Section to display </strong></td>
							</tr>
						  <tr>
                            <td width="324" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><label>
                              <input type="checkbox" name="1stTerm" value="1">
                            </label>
                              First Term</td>
                          </tr>
						  <tr>
                            <td width="324" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="2ndTerm" value="2">
                              Second Term</td>
                          </tr>
						  <tr>
                            <td width="324" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;" align="left"><input type="checkbox" name="3rdTerm" value="3">
                              Third Term</td>
                          </tr>-->
                        </table>					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrint2" value="Print">
							  <input type="submit" name="NotifyFeeDef" value="Notify Parent" disabled>
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
}elseif ($SubPage == "Total Fees Paid") {
?>
<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="fees.php?subpg=Total Fees Paid">
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
					  <TD align="left" valign="top"><p style="margin-left:170px;"><strong>Filter Criteria </strong></p>
					  	<table width="357" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
							if($_SESSION['module'] != "Teacher"){
?>
								<label>
								  <input name="ischecked3" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked2\',\'\')', 0)" <?PHP echo $chkAll; ?>>
								</label>All
<?PHP
							}
?>
							</td>
                          </tr>
						  <tr>
                            <td width="324"><input name="ischecked3" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked2\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass3" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					  </select>
							  
							  </td>
                          </tr>
						 
                        </table>					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrint3" value="Print">
							  <input type="submit" name="NotifyFeeDef" value="Notify Parent" disabled>
							</div>
							</TD>
						</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Charge Summary") {
?>
<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="fees.php?subpg=Charge Summary">
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
					  <TD align="left" valign="top"><p style="margin-left:170px;"><strong>Filter Criteria </strong></p>
					  	<table width="357" border="0" align="center" cellpadding="5" cellspacing="5">
                          <tr>
                            <td width="324">
<?PHP
							if($_SESSION['module'] != "Teacher"){
?>
								<label>
								  <input name="ischecked4" type="radio" value="All" onClick="javascript:setTimeout('__doPostBack(\'ischecked4\',\'\')', 0)" <?PHP echo $chkAll; ?>>
								</label>All Class
<?PHP
							}
?>
							</td>
                          </tr>
						  <tr>
                            <td width="324"><!--<input name="ischecked4" type="radio" value="Class" onClick="javascript:setTimeout('__doPostBack(\'ischecked4\',\'\')', 0)" <?PHP echo $chkclass; ?>> 
                              Select Class: 
							<select name="OptClass4" <?PHP echo $lockclass; ?>>
								<option value="0" selected="selected"></option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And SecID = '$Activeterm' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
								}else{
									$query = "select ID,Class_Name from tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
					  </select>-->
							  
							  </td>
                          </tr>
						 
                        </table>					  </TD>
					</TR>
					<TR>
							<TD>
							<div align="center">
							  <input type="submit" name="SubmitPrint4" value="Print">
							  <input type="submit" name="NotifyFeeDef" value="Notify Parent" disabled>
							</div>
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
