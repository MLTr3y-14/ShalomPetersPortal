<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
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
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
	}
	//define session variables for each input entry
	// set session variable
	if($_POST && !empty($_POST['Title'])) {$_SESSION['Title'] = $_POST['Title']; }else{ $Title =$_SESSION['Title']; $_SESSION['Title'] = "";}
	if($_POST && !empty($_POST['OptCat'])) {$_SESSION['OptCat'] = $_POST['OptCat'];}else{ $OptCat=$_SESSION['OptCat'];$_SESSION['OptCat'] = "";}	
	if($_POST && !empty($_POST['reg_Dy'])) {$_SESSION['reg_Dy'] = $_POST['reg_Dy'];}else{ $reg_Dy=$_SESSION['reg_Dy'];$_SESSION['reg_Dy'] = "";}
	if($_POST && !empty($_POST['reg_Mth'])) {$_SESSION['reg_Mth'] = $_POST['reg_Mth'];}else{$reg_Mth=$_SESSION['reg_Mth'];$_SESSION['reg_Mth'] = "";}	
	if($_POST && !empty($_POST['reg_Yr'])) {$_SESSION['reg_Yr'] = $_POST['reg_Yr'];}else{$reg_Yr=$_SESSION['reg_Yr'];$_SESSION['reg_Yr'] = "";}
	if($_POST && !empty($_POST['$AuthorList'])) {$_SESSION['$AuthorList'] = $_POST['$AuthorList'];}else{$AuthorList=$_SESSION['$AuthorList'];$_SESSION['$AuthorList'] = "";}	
	if($_POST && !empty($_POST['OptClass'])) {$_SESSION['OptClass'] = $_POST['OptClass'];}else{$OptClass=$_SESSION['OptClass'];$_SESSION['OptClass'] = "";}
	if($_POST && !empty($_POST['OptSubCat'])) {$_SESSION['OptSubCat'] = $_POST['OptSubCat'];}else{$OptSubCat=$_SESSION['OptSubCat'];$_SESSION['OptSubCat'] = "";}	
	if($_POST && !empty($_POST['SubTitle'])) {$_SESSION['SubTitle'] = $_POST['SubTitle'];}else{$SubTitle=$_SESSION['SubTitle'];$_SESSION['SubTitle'] = "";}
	if($_POST && !empty($_POST['Synopsis'])) {$_SESSION['Synopsis'] = $_POST['Synopsis'];}else{$Synopsis=$_SESSION['Synopsis'];$_SESSION['Synopsis'] = "";}	
	if($_POST && !empty($_POST['isStatus'])) {$_SESSION['isStatus'] = $_POST['isStatus'];}else{$isStatus=$_SESSION['isStatus'];$_SESSION['isStatus'] = "";}
	if($_POST && !empty($_POST['Edition'])) {$_SESSION['Edition'] = $_POST['Edition'];}else{$Edition=$_SESSION['Edition'];$_SESSION['Edition'] = "";}	
	if($_POST && !empty($_POST['OptBinding'])) {$_SESSION['OptBinding'] = $_POST['OptBinding'];}else{$OptBinding=$_SESSION['OptBinding'];$_SESSION['OptBinding'] = "";}
	if($_POST && !empty($_POST['OptLang'])) {$_SESSION['OptLang'] = $_POST['OptLang'];}else{$OptLang=$_SESSION['OptLang'];$_SESSION['OptLang'] = "";}	
	if($_POST && !empty($_POST['OptPublisher'])) {$_SESSION['OptPublisher'] = $_POST['OptPublisher'];}else{$OptPublisher=$_SESSION['OptPublisher'];$_SESSION['OptPublisher'] = "";}
	if($_POST && !empty($_POST['OptPlacePub'])) {$_SESSION['OptPlacePub'] = $_POST['OptPlacePub'];}else{$OptPlacePub=$_SESSION['OptPlacePub'];$_SESSION['OptPlacePub'] = "";}	
	if($_POST && !empty($_POST['ISBN'])) {$_SESSION['ISBN'] = $_POST['ISBN'];}else{$ISBN=$_SESSION['ISBN'];$_SESSION['ISBN'] = "";}
	if($_POST && !empty($_POST['OptCountry'])) {$_SESSION['OptCountry'] = $_POST['OptCountry'];}else{$OptCountry=$_SESSION['OptCountry'];$_SESSION['OptCountry'] = "";}	
	if($_POST && !empty($_POST['OptSeries'])) {$_SESSION['OptSeries'] = $_POST['OptSeries'];}else{$OptSeries=$_SESSION['OptSeries'];$_SESSION['OptSeries'] = "";}
	if($_POST && !empty($_POST['BarCode'])) {$_SESSION['BarCode'] = $_POST['BarCode'];}else{$BarCode=$_SESSION['BarCode'];$_SESSION['BarCode'] = "";}	
	if($_POST && !empty($_POST['BillNo'])) {$_SESSION['BillNo'] = $_POST['BillNo'];}else{$BillNo=$_SESSION['BillNo'];$_SESSION['BillNo'] = "";}
	if($_POST && !empty($_POST['OptCurrency'])) {$_SESSION['OptCurrency'] = $_POST['OptCurrency'];}else{$OptCurrency=$_SESSION['OptCurrency'];$_SESSION['OptCurrency'] = "";}	
	if($_POST && !empty($_POST['Price'])) {$_SESSION['Price'] = $_POST['Price'];}else{$Price=$_SESSION['Price'];$_SESSION['Price'] = "";}
	if($_POST && !empty($_POST['OptCondition'])) {$_SESSION['OptCondition'] = $_POST['OptCondition'];}else{$OptCondition=$_SESSION['OptCondition'];$_SESSION['OptCondition'] = "";}	
	if($_POST && !empty($_POST['pur_day'])) {$_SESSION['pur_day'] = $_POST['pur_day'];}else{$pur_day=$_SESSION['pur_day'];$_SESSION['pur_day'] = "";}
	if($_POST && !empty($_POST['pur_mth'])) {$_SESSION['pur_mth'] = $_POST['pur_mth'];}else{$pur_mth=$_SESSION['pur_mth'];$_SESSION['pur_mth'] = "";}	
	if($_POST && !empty($_POST['pur_yr'])) {$_SESSION['pur_yr'] = $_POST['pur_yr'];}else{$pur_yr=$_SESSION['pur_yr'];$_SESSION['pur_yr'] = "";}
	if($_POST && !empty($_POST['OptSupplier'])) {$_SESSION['OptSupplier'] = $_POST['OptSupplier'];}else{$OptSupplier=$_SESSION['OptSupplier'];$_SESSION['OptSupplier'] = "";}	
	if($_POST && !empty($_POST['BookNo'])) {$_SESSION['BookNo'] = $_POST['BookNo'];}else{$BookNo=$_SESSION['BookNo'];$_SESSION['BookNo'] = "";}
	if($_POST && !empty($_POST['YearPub'])) {$_SESSION['YearPub'] = $_POST['YearPub'];}else{$YearPub=$_SESSION['YearPub'];$_SESSION['YearPub'] = "";}	
	if($_POST && !empty($_POST['BookRate'])) {$_SESSION['BookRate'] = $_POST['BookRate'];}else{$BookRate=$_SESSION['BookRate'];$_SESSION['BookRate'] = "";}
		
		
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
	if(isset($_POST['OptCat']))
	{
		$OptCat = $_POST['OptCat'];
		$Libbook_id = $_POST['SelBookID'];
		$Title = $_POST['Title'];
		$reg_Dy = $_POST['reg_Dy'];
		$reg_Mth = $_POST['reg_Mth'];
		$reg_Yr = $_POST['reg_Yr'];
		$OptCat = $_POST['OptCat'];

		$OptClass = $_POST['OptClass'];
		$OptSubCat = $_POST['OptSubCat'];
		$AuthorName = $_POST['author'];
		
		$SubTitle = $_POST['SubTitle'];
		$Synopsis = $_POST['Synopsis'];
		$isStatus = $_POST['isStatus'];
		if($isStatus = ""){
			$isStatus = 0;
		}
		$Edition = $_POST['Edition'];
		$OptBinding = $_POST['OptBinding'];
		$OptLang = $_POST['OptLang'];
		
		$OptPublisher = $_POST['OptPublisher'];
		$OptPlacePub = $_POST['OptPlacePub'];
		$ISBN = $_POST['ISBN'];
		
		$OptCountry = $_POST['OptCountry'];
		$OptSeries = $_POST['OptSeries'];
		$BarCode = $_POST['BarCode'];
		
		$BillNo = $_POST['BillNo'];
		$OptCurrency = $_POST['OptCurrency'];
		$Price = $_POST['Price'];
		
		$OptCondition = $_POST['OptCondition'];
		$pur_day = $_POST['pur_day'];
		$pur_mth = $_POST['pur_mth'];
		$pur_yr = $_POST['pur_yr'];
		$OptSupplier = $_POST['OptSupplier'];
		
		$BookNo = $_POST['BookNo'];
		$YearPub = $_POST['YearPub'];
		$BookRate = $_POST['BookRate'];
	}
	if(isset($_POST['SubmitCat']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Category Master\">";
		exit;
	}
	if(isset($_POST['SubmitSubCat']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Sub Category Master\">";
		exit;
	}
	if(isset($_POST['SubmitBinding']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Binding Master\">";
		exit;
	}
	if(isset($_POST['SubmitLang']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Language\">";
		exit;
	}
	if(isset($_POST['SubmitPubisher']))
	{
		$hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Publisher Master\">";
		exit;
	}
	if(isset($_POST['SubmitPubPlaceID']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Publisher Master\">";
		exit;
	}
	if(isset($_POST['SubmitCountry']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Country Master\">";
		exit;
	}
	if(isset($_POST['SubmitSeries']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Series Master\">";
		exit;
	}
	if(isset($_POST['SubmitCurr']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Currency Master\">";
		exit;
	}
	if(isset($_POST['SubmitBCond']))
	{    $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Category Master\">";
		exit;
	}
	if(isset($_POST['SubmitSupplier']))
	{   $hideTopMenu = 'true';
		$_SESSION['hideTopMenu'] = $hideTopMenu;
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=librarysetup.php?subpg=Supplier Master\">";
		exit;
	}
	if(isset($_POST['AddAuthor']))
	{
		$OptAuthor = $_POST['OptAuthor'];
		$Libbook_id = $_POST['SelBookID'];
		$OptCat = $_POST['OptCat'];
		$Title = $_POST['Title'];
		$reg_Dy = $_POST['reg_Dy'];
		$reg_Mth = $_POST['reg_Mth'];
		$reg_Yr = $_POST['reg_Yr'];
		$OptCat = $_POST['OptCat'];

		$OptClass = $_POST['OptClass'];
		$OptSubCat = $_POST['OptSubCat'];
		
		$SubTitle = $_POST['SubTitle'];
		$Synopsis = $_POST['Synopsis'];
		$isStatus = $_POST['isStatus'];
		if($isStatus = ""){
			$isStatus = 0;
		}
		$Edition = $_POST['Edition'];
		$OptBinding = $_POST['OptBinding'];
		$OptLang = $_POST['OptLang'];
		
		$OptPublisher = $_POST['OptPublisher'];
		$OptPlacePub = $_POST['OptPlacePub'];
		$ISBN = $_POST['ISBN'];
		
		$OptCountry = $_POST['OptCountry'];
		$OptSeries = $_POST['OptSeries'];
		$BarCode = $_POST['BarCode'];
		
		$BillNo = $_POST['BillNo'];
		$OptCurrency = $_POST['OptCurrency'];
		$Price = $_POST['Price'];
		
		$OptCondition = $_POST['OptCondition'];
		$pur_day = $_POST['pur_day'];
		$pur_mth = $_POST['pur_mth'];
		$pur_yr = $_POST['pur_yr'];
		$OptSupplier = $_POST['OptSupplier'];
		
		$BookNo = $_POST['BookNo'];
		$YearPub = $_POST['YearPub'];
		$BookRate = $_POST['BookRate'];
		$query = "select ID from tbauthorlist where UserName = '$userNames' And AuthorID = '$OptAuthor'";
		$result = mysql_query($query,$conn);
		$num_rows = mysql_num_rows($result);
		if ($num_rows > 0 ) 
		{
			$q = "Delete From tbauthorlist where UserName = '$userNames' And AuthorID = '$OptAuthor'";
			$result = mysql_query($q,$conn);
			
			$q = "Insert into tbauthorlist(AuthorID,UserName) Values ('$OptAuthor','$userNames')";
			$result = mysql_query($q,$conn);
		}else {
			$q = "Insert into tbauthorlist(AuthorID,UserName) Values ('$OptAuthor','$userNames')";
			$result = mysql_query($q,$conn);
		}
	}
	if(isset($_POST['DelAuthor']))
	{
		$OptAuthorList = $_POST['OptAuthorList'];
		$Libbook_id = $_POST['SelBookID'];
		$OptCat = $_POST['OptCat'];
		$Title = $_POST['Title'];
		$reg_Dy = $_POST['reg_Dy'];
		$reg_Mth = $_POST['reg_Mth'];
		$reg_Yr = $_POST['reg_Yr'];
		$OptCat = $_POST['OptCat'];

		$OptClass = $_POST['OptClass'];
		$OptSubCat = $_POST['OptSubCat'];
		
		$SubTitle = $_POST['SubTitle'];
		$Synopsis = $_POST['Synopsis'];
		$isStatus = $_POST['isStatus'];
		if($isStatus = ""){
			$isStatus = 0;
		}
		$Edition = $_POST['Edition'];
		$OptBinding = $_POST['OptBinding'];
		$OptLang = $_POST['OptLang'];
		
		$OptPublisher = $_POST['OptPublisher'];
		$OptPlacePub = $_POST['OptPlacePub'];
		$ISBN = $_POST['ISBN'];
		
		$OptCountry = $_POST['OptCountry'];
		$OptSeries = $_POST['OptSeries'];
		$BarCode = $_POST['BarCode'];
		
		$BillNo = $_POST['BillNo'];
		$OptCurrency = $_POST['OptCurrency'];
		$Price = $_POST['Price'];
		
		$OptCondition = $_POST['OptCondition'];
		$pur_day = $_POST['pur_day'];
		$pur_mth = $_POST['pur_mth'];
		$pur_yr = $_POST['pur_yr'];
		$OptSupplier = $_POST['OptSupplier'];
		
		$BookNo = $_POST['BookNo'];
		$YearPub = $_POST['YearPub'];
		$BookRate = $_POST['BookRate'];
		$q = "Delete From tbauthorlist where UserName = '$userNames' And AuthorID = '$OptAuthorList'";
		$result = mysql_query($q,$conn);
	}
	if(isset($_POST['SaveBookMst']))
	{
		$PageHasError = 0;
		$Libbook_id = $_POST['SelBookID'];
		$Title = $_POST['Title'];
		$reg_Dy = $_POST['reg_Dy'];
		$reg_Mth = $_POST['reg_Mth'];
		$reg_Yr = $_POST['reg_Yr'];
		$RegDate = $reg_Yr."-".$reg_Mth."-".$reg_Dy;
		$OptCat = $_POST['OptCat'];
		
		$AuthorName = $_POST['author'];
		$OptClass = $_POST['OptClass'];
		$OptSubCat = $_POST['OptSubCat'];
		
		$SubTitle = $_POST['SubTitle'];
		$Synopsis = $_POST['Synopsis'];
		$isStatus = $_POST['isStatus'];
		if($isStatus == ""){
			$isStatus = 0;
		}
		$Edition = $_POST['Edition'];
		$OptBinding = $_POST['OptBinding'];
		$OptLang = $_POST['OptLang'];
		
		$OptPublisher = $_POST['OptPublisher'];
		$OptPlacePub = $_POST['OptPlacePub'];
		$ISBN = $_POST['ISBN'];
		
		$OptCountry = $_POST['OptCountry'];
		$OptSeries = $_POST['OptSeries'];
		$BarCode = $_POST['BarCode'];
		
		$BillNo = $_POST['BillNo'];
		$OptCurrency = $_POST['OptCurrency'];
		$Price = $_POST['Price'];
		if($Price==""){
			$Price = 0;
		}
		$OptCondition = $_POST['OptCondition'];
		$pur_day = $_POST['pur_day'];
		$pur_mth = $_POST['pur_mth'];
		$pur_yr = $_POST['pur_yr'];
		$PubDate = $pur_yr."-".$pur_mth."-".$pur_day;
		$OptSupplier = $_POST['OptSupplier'];
		
		$BookNo = $_POST['BookNo'];
		$YearPub = $_POST['YearPub'];
		$BookRate = $_POST['BookRate'];
		
		
		if(!$_POST['Title']){
			$errormsg = "<font color = red size = 1>Title name is empty.</font>";
			$PageHasError = 1;
		}
		
		if(!$_POST['BookNo']){
			$errormsg = "<font color = red size = 1>Accession No. is empty.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			if ($_POST['SaveBookMst'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbbookmst where Title = '$Title' And BookNo = '$BookNo'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Book you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbbookmst(Title,regdate,CatID,AuthorList,ClassID,SubCatID,SubTitle,Synopsis,Edition,BindingID,LangID,Status,PubID,PlaceofPubID,ISBN,CountryID,SeriesID,BarCode,BillNo,CurrID,Price,BookCondID,Pur_Date,SuppID,BookNo,YearofPub,BookRate) Values ('$Title','$RegDate','$OptCat','$AuthorName','$OptClass','$OptSubCat','$SubTitle','$Synopsis','$Edition','$OptBinding','$OptLang','$isStatus','$OptPublisher','$OptPlacePub','$ISBN','$OptCountry','$OptSeries','$BarCode','$BillNo','$OptCurrency','$Price','$OptCondition','$PubDate','$OptSupplier','$BookNo','$YearPub','$BookRate')";
					$result = mysql_query($q,$conn);
					
					$query = "select ID from tbbookmst where Title = '$Title' And BookNo = '$BookNo' order by ID";
					$result = mysql_query($query,$conn);
					$dbarray = mysql_fetch_array($result);
					$TemBookID = $dbarray['ID'];
					
					$query = "select AuthorID from tbauthorlist where UserName = '$userNames' order by ID";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$AuthorList = $row["AuthorID"];
							$q = "Insert into tbbookauthorlist(AuthorID,BookID) Values ('$AuthorList','$TemBookID')";
							$result2 = mysql_query($q,$conn);
						}
					}
					
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$Title = "";
					$AuthorName ="";
					$OptCat = "";
					$OptAuthor = "";
					$OptClass = "";
					$OptSubCat = "";
					
					$OptAuthorList = "";
					$q = "Delete From tbauthorlist where UserName = '$userNames'";
					$result = mysql_query($q,$conn);
					$SubTitle = "";
					$Synopsis = "";
					$isStatus = "";
					$Edition = "";
					$OptBinding = "";
					$OptLang = "";
					$OptPublisher = "";
					$OptPlacePub = "";
					$ISBN = "";
					$OptCountry = "";
					$OptSeries = "";
					$BarCode = "";
					$BillNo = "";
					$OptCurrency = "";
					$Price = "";
					$OptCondition = "";
					$OptSupplier = "";
					$BookNo = "";
					$YearPub = "";
					$BookRate = "";
				}
			}elseif ($_POST['SaveBookMst'] =="Update"){
				$q = "update tbbookmst set Title = '$Title',regdate = '$RegDate',CatID  = '$OptCat',AuthorList = '$AuthorName',ClassID = '$OptClass',SubCatID = '$OptSubCat',SubTitle = '$SubTitle',Synopsis = '$Synopsis',Edition = '$Edition',BindingID = '$OptBinding',LangID = '$OptLang',Status = '$isStatus',PubID = '$OptPublisher',PlaceofPubID = '$OptPlacePub',ISBN = '$ISBN',CountryID = '$OptCountry',SeriesID = '$OptSeries',BarCode = '$BarCode',BillNo = '$BillNo',CurrID = '$OptCurrency',Price = '$Price',BookCondID = '$OptCondition',Pur_Date = '$PubDate',SuppID = '$OptSupplier',BookNo = '$BookNo',YearofPub = '$YearPub',BookRate = '$BookRate' where ID = '$Libbook_id'";
			    $result = mysql_query($q,$conn);
				
				$q = "Delete From tbbookauthorlist where BookID = '$Libbook_id'";
				$result = mysql_query($q,$conn);
				
				$query = "select AuthorID from tbauthorlist where UserName = '$userNames' order by ID";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result)) 
					{
						$AuthorList = $row["AuthorID"];
						$q = "Insert into tbbookauthorlist(AuthorID,BookID) Values ('$AuthorList','$Libbook_id')";
						$result2 = mysql_query($q,$conn);
					}
				}
					
					
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$Title = "";
				$AuthorName = "";
				$OptCat = "";
				$OptAuthor = "";
				$OptClass = "";
				$OptSubCat = "";
				
				$OptAuthorList = "";
				$q = "Delete From tbauthorlist where UserName = '$userNames'";
				$result = mysql_query($q,$conn);
				$SubTitle = "";
				$Synopsis = "";
				$isStatus = "";
				$Edition = "";
				$OptBinding = "";
				$OptLang = "";
				$OptPublisher = "";
				$OptPlacePub = "";
				$ISBN = "";
				$OptCountry = "";
				$OptSeries = "";
				$BarCode = "";
				$BillNo = "";
				$OptCurrency = "";
				$Price = "";
				$OptCondition = "";
				$OptSupplier = "";
				$BookNo = "";
				$YearPub = "";
				$BookRate = "";
			}elseif ($_POST['SaveBookMst'] =="Delete"){
				$q = "Delete From tbbookmst where ID = '$Libbook_id'";
				$result = mysql_query($q,$conn);
				
				$q = "Delete From tbbookauthorlist where BookID = '$Libbook_id'";
				$result = mysql_query($q,$conn);
				
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$Title = "";
				$OptCat = "";
				$OptAuthor = "";
				$OptClass = "";
				$OptSubCat = "";
				
				$OptAuthorList = "";
				$q = "Delete From tbauthorlist where UserName = '$userNames'";
				$result = mysql_query($q,$conn);
				$SubTitle = "";
				$Synopsis = "";
				$isStatus = "";
				$Edition = "";
				$OptBinding = "";
				$OptLang = "";
				$OptPublisher = "";
				$OptPlacePub = "";
				$ISBN = "";
				$OptCountry = "";
				$OptSeries = "";
				$BarCode = "";
				$BillNo = "";
				$OptCurrency = "";
				$Price = "";
				$OptCondition = "";
				$OptSupplier = "";
				$BookNo = "";
				$YearPub = "";
				$BookRate = "";
			}
		}
	}
	if(isset($_GET['book_id']))
	{
		$Libbook_id = $_GET['book_id'];
		$query = "select * from tbbookmst where ID='$Libbook_id'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Title = $dbarray['Title'];
		
		$RegDate=explode ('-', $dbarray['regdate']);
		$reg_Yr = $RegDate[2];
		$reg_Mth = $RegDate[1];
		$reg_Dy = $RegDate[0];
		
		$OptCat = $dbarray['CatID'];
		$AuthorName = $dbarray['AuthorList']; 
		$OptClass = $dbarray['ClassID'];
		$OptSubCat = $dbarray['SubCatID'];
		$SubTitle = $dbarray['SubTitle'];
		$Synopsis = $dbarray['Synopsis'];
		$Edition = $dbarray['Edition'];
		$OptBinding = $dbarray['BindingID'];
		$OptLang = $dbarray['LangID'];
		$isStatus = $dbarray['Status'];
		$OptPublisher = $dbarray['PubID'];
		$OptPlacePub = $dbarray['PlaceofPubID'];
		$ISBN = $dbarray['ISBN'];
		$OptCountry = $dbarray['CountryID'];
		$OptSeries = $dbarray['SeriesID'];
		$BarCode = $dbarray['BarCode'];
		$BillNo = $dbarray['BillNo'];
		$OptCurrency = $dbarray['CurrID'];
		$Price = $dbarray['Price'];
		$OptCondition = $dbarray['BookCondID'];
		
		$PurDate=explode ('-', $dbarray['Pur_Date']);
		$pur_yr = $PurDate[2];
		$pur_mth = $PurDate[1];
		$pur_day = $PurDate[0];
		
		$OptSupplier = $dbarray['SuppID'];
		$BookNo = $dbarray['BookNo'];
		$YearPub = $dbarray['YearofPub'];
		$BookRate = $dbarray['BookRate'];
	}
	
	
	if(isset($_POST['Finemaster']))
	{
		$PageHasError = 0;
		$FineID = $_POST['SelFineID'];
		$FrmDay = $_POST['FrmDay'];
		$ToDay = $_POST['ToDay'];
		$FineAmount = $_POST['FineAmount'];
		if(!is_numeric($FrmDay)){
			$errormsg = "<font color = red size = 1>Invalid From Day.</font>";
			$PageHasError = 1;
		}
		
		if(!is_numeric($ToDay)){
			$errormsg = "<font color = red size = 1>Invalid To Day.</font>";
			$PageHasError = 1;
		}
		if($ToDay < $FrmDay){
			$errormsg = "<font color = red size = 1>Invalid To Day.</font>";
			$PageHasError = 1;
		}
		
		if(!is_numeric($FineAmount)){
			$errormsg = "<font color = red size = 1>Invalid Fine Amount</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Finemaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from TbLibFineMst where Finefrom = '$FrmDay' And Fineto = '$ToDay' And FinePerDay = '$FineAmount'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Fine Policy you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into TbLibFineMst(Finefrom,Fineto,FinePerDay) Values ('$FrmDay','$ToDay','$FineAmount')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$FrmDay = "";
					$ToDay = "";
					$FineAmount = "";
				}
			}elseif ($_POST['Finemaster'] =="Update"){
				$q = "update TbLibFineMst set Finefrom = '$FrmDay', Fineto = '$ToDay', FinePerDay = '$FineAmount' where ID = '$FineID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$FrmDay = "";
				$ToDay = "";
				$FineAmount = "";
			}
		}
	}
	if(isset($_GET['fine_id']))
	{
		$FineID = $_GET['fine_id'];
		$query = "select * from TbLibFineMst where ID='$FineID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$FrmDay  = $dbarray['Finefrom'];
		$ToDay  = $dbarray['Fineto'];
		$FineAmount  = $dbarray['FinePerDay'];
	}
	if(isset($_POST['Finemaster_delete']))
	{
		$Total = $_POST['TotalFine'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkFineID'.$i]))
			{
				$chkFineID = $_POST['chkFineID'.$i];
				$q = "Delete From TbLibFineMst where ID = '$chkFineID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
if(isset($_POST['OptAuthorList']))
	{
		$AuthorID = $_POST['OptAuthorList'];
		$query2 = "select AuthorName from tbauthormaster where ID = '$AuthorID'";
			$result2 = mysql_query($query2,$conn);
			$dbarray2 = mysql_fetch_array($result2);
			$AuthorName  = $dbarray2['AuthorName'];
		
		
	}
	
 $AuthorName2 = $_POST['author'];
 $Optsearch = $_POST['OptSearch'];
  $SubmitSearch = 'false';
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
	if(isset($_GET['Optsearch']))
	{
		 $SubmitSearch = 'true';
		$Optsearch = $_GET['Optsearch'];
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
		if ($SubPage == "Book Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="library.php?subpg=Book Master">
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
				<TABLE width="100%" style="WIDTH: 100%" cellpadding="3" cellspacing="0">
				<TBODY>
					<TR>
					  <TD width="8%" valign="top"  align="left">Title</TD>
					  <TD width="24%" valign="top"  align="left"><input name="Title" type="text" size="20" value="<?PHP echo $Title; ?>"> 
					  * </TD>
					  <TD width="9%" valign="top"  align="left">Reg. Date </TD>
					  <TD width="25%" valign="top"  align="left">
					  <select name="reg_Dy">
					      <option value="0">Day</option>
						  
<?PHP 
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($reg_Dy == $i){
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
						<select name="reg_Mth">
						   <option value="0">Mth</option>
<?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $reg_Mth){
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
						<select name="reg_Yr">
						  <option value="0">Yr</option>
<?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($reg_Yr == $i){
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
						</select>
					 </TD>
					  <TD width="7%" valign="top"  align="left">Category</TD>
					  <TD width="27%" valign="top"  align="left">
					  <select name="OptCat" onChange="javascript:setTimeout('__doPostBack(\'OptHeader\',\'\')', 0)" style="width:100px;">
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
                      </select>
					  <input name="SubmitCat" type="submit" id="SubmitCat" value="..." title="Update Book Category Master"></TD>
					</TR>
					<TR>
					  <TD width="8%" valign="top"  align="left">Author</TD>
					  <TD width="24%" valign="top"  align="left">
					  <input type="text" name="author" value="<?PHP echo $AuthorName; ?>" size="20" style="background:#66FFFF;" />
                        
					  </TD>
					  <TD width="9%" valign="top"  align="left">Class </TD>
					  <TD width="25%" valign="top"  align="left">
						<select name="OptClass"  <?PHP echo $lockclass; ?>>
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
					  </select>
					  </TD>
					  <TD width="7%" valign="top"  align="left">Sub Cat</TD>
					  <TD valign="top"  align="left">
					  <select name="OptSubCat"  style="width:100px;">
                          <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						if($_POST['OptCat'] != ""){
							$query = "select * from tblibsubcatmst where CatID = '$OptCat' order by SubCatName";
						}else{
							$query = "select * from tblibsubcatmst order by SubCatName";
						}
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
                        </select>
					    <input name="SubmitSubCat" type="submit" id="SubmitSubCat" value=".." title="Update Book Sub Category Master"></TD>
					</TR>
					<TR>
					  <TD colspan="2" rowspan="4" valign="top"  align="left">
					     <select name="OptAuthorList" size="10" multiple style="width:190px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)">
<?PHP
        
							//if(!isset($_GET['book_id']))
							//{
							//	$query = "select AuthorID from tbbookauthorlist where BookID = '$Libbook_id' order by ID";
							//}else{
							//	$query = "select AuthorID from tbauthorlist where UserName = '$userNames' order by ID";
							//}
							//$result = mysql_query($query,$conn);
							//$num_rows = mysql_num_rows($result);
							//if ($num_rows > 0 ) {
								//while ($row = mysql_fetch_array($result)) 
								//{
									//$AuthorID = $row["AuthorID"];
									
									$query2 = "select * from tbauthormaster";
									$result2 = mysql_query($query2,$conn);
									$num_rows = mysql_num_rows($result2);
							          if ($num_rows > 0 ) {
										  while ($dbarray2 = mysql_fetch_array($result2))
										  {
									$AuthorName1  = $dbarray2['AuthorName'];
									$AuthorID  = $dbarray2['ID'];
?>
									<option value="<?PHP echo $AuthorID; ?>">&nbsp;&nbsp;&nbsp;<?PHP echo $AuthorName1; ?></option>
<?PHP
			}
        }
										  
							
?>
                     	 </select>
                         
					  </TD>
					  <TD width="9%" valign="top"  align="left">Sub Title </TD>
					  <TD width="25%" valign="top"  align="left"><input name="SubTitle" type="text"value="<?PHP echo $SubTitle; ?>" size="20"/></TD>
					  <TD width="7%" valign="top"  align="left" rowspan="4">Synopsis</TD>
					  <TD colspan="3" valign="top"  align="left" rowspan="4"><textarea name="Synopsis" style="background:#66FFFF;" cols="35" rows="7"><?PHP echo $Synopsis; ?></textarea>
					  <br>Status: 
<?PHP
						if($isStatus == 0){
?>
							<input name="isStatus" type="radio" value="0" checked="checked">Active
							<input name="isStatus" type="radio" value="1">Scrap
							<input name="isStatus" type="radio" value="2">Lost
<?PHP
						}elseif($isStatus == 1){
?>
							<input name="isStatus" type="radio" value="0">Active
							<input name="isStatus" type="radio" value="1" checked="checked">Scrap
							<input name="isStatus" type="radio" value="2">Lost
<?PHP
						}elseif($isStatus == 2){
?>
							<input name="isStatus" type="radio" value="0">Active
							<input name="isStatus" type="radio" value="1">Scrap
							<input name="isStatus" type="radio" value="2" checked="checked">Lost
<?PHP
						}else{
?>
							<input name="isStatus" type="radio" value="0">Active
							<input name="isStatus" type="radio" value="1">Scrap
							<input name="isStatus" type="radio" value="2">Lost
<?PHP
						}
?>
					  </TD>
					</TR>
					<TR>
					  <TD width="9%" valign="top"  align="left"> Edition  </TD>
					  <TD width="25%" valign="top"  align="left"><input name="Edition" type="text"value="<?PHP echo $Edition; ?>" size="20"/></TD>
					</TR>
					<TR>
					  <TD width="9%" valign="top"  align="left">Binding </TD>
					  <TD width="25%" valign="top"  align="left"><select name="OptBinding" style="width:100px;">
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,Binding from tbbinding order by Binding";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$BindingID = $row["ID"];
								$Binding = $row["Binding"];
								
								if($OptBinding =="$BindingID"){
?>
                        			<option value="<?PHP echo $BindingID; ?>" selected="selected"><?PHP echo $Binding; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $BindingID; ?>"><?PHP echo $Binding; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					    <input name="SubmitBinding" type="submit" id="SubmitBinding" value="..." title="Update Book Binding Master"></TD>
					</TR>
					<TR>
					  <TD width="9%" valign="top"  align="left">Language </TD>
					  <TD width="25%" valign="top"  align="left"><select name="OptLang" style="width:100px;">
                        <option value="0" selected="selected"></option>
                        <?PHP
						$counter = 0;
						$query = "select ID,lang from tblanguage order by lang";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$langID = $row["ID"];
								$lang = $row["lang"];
								
								if($OptLang =="$langID"){
?>
                        			<option value="<?PHP echo $langID; ?>" selected="selected"><?PHP echo $lang; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $langID; ?>"><?PHP echo $lang; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					    <input name="SubmitLang" type="submit" id="SubmitLang" value="..." title="Update Book language Master"></TD>
					</TR>
					<TR>
					  <TD width="8%" valign="top"  align="left">Publisher</TD>
					  <TD width="24%" valign="top"  align="left">
					  <select name="OptPublisher" style="width:120px;">
                        <option value="0" selected="selected"></option>
                        <?PHP
						$counter = 0;
						$query = "select ID,PubName from tbpublisher order by PubName";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$PubID = $row["ID"];
								$PubName = $row["PubName"];
								
								if($OptPublisher =="$PubID"){
?>
									<option value="<?PHP echo $PubID; ?>" selected="selected"><?PHP echo $PubName; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $PubID; ?>"><?PHP echo $PubName; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					  <input name="SubmitPubisher" type="submit" id="SubmitPubisher" value="..." title="Update Publisher"></TD>
					  <TD width="9%" valign="top"  align="left">Place of Publication  </TD>
					  <TD width="25%" valign="top"  align="left">
					  <select name="OptPlacePub" style="width:120px;">
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,PubPlace from tbpublicationplace order by PubPlace";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$PubPlaceID = $row["ID"];
								$PubPlace = $row["PubPlace"];
								
								if($OptPlacePub =="$PubPlaceID"){
?>
                        			<option value="<?PHP echo $PubPlaceID; ?>" selected="selected"><?PHP echo $PubPlace; ?></option>
<?PHP
								}else{
?>
                       				<option value="<?PHP echo $PubPlaceID; ?>"><?PHP echo $PubPlace; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					    <input name="SubmitPubPlaceID" type="submit" id="SubmitPubPlaceID" value="..." title="Update Publication Place"></TD>
					  <TD width="7%" valign="top"  align="left">ISBN</TD>
					  <TD valign="top"  align="left"><input name="ISBN" type="text"value="<?PHP echo $ISBN; ?>" size="30"/></TD>
					</TR>
					<TR>
					  <TD width="8%" valign="top"  align="left">Country</TD>
					  <TD width="24%" valign="top"  align="left">
					  <select name="OptCountry" style="width:120px;">
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,Country from tbcountry order by Country";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$CountryID = $row["ID"];
								$Country = $row["Country"];
								
								if($OptCountry =="$CountryID"){
?>
                        			<option value="<?PHP echo $CountryID; ?>" selected="selected"><?PHP echo $Country; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $CountryID; ?>"><?PHP echo $Country; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					    <input name="SubmitCountry" type="submit" id="SubmitCountry" value="..." title="Update Country Information"></TD>
					  <TD width="9%" valign="top"  align="left">Series</TD>
					  <TD width="25%" valign="top"  align="left">
					  <select name="OptSeries"  style="width:120px;">
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,Series from tbseries order by Series";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$SeriesID = $row["ID"];
								$Series = $row["Series"];
								
								if($OptSeries =="$SeriesID"){
?>
                        			<option value="<?PHP echo $SeriesID; ?>" selected="selected"><?PHP echo $Series; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $SeriesID; ?>"><?PHP echo $Series; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					  <input name="SubmitSeries" type="submit" id="SubmitSeries" value="..." title="Update Series"></TD>
					  <TD width="7%" valign="top"  align="left">BarCode</TD>
					  <TD valign="top"  align="left"><input name="BarCode" type="text"value="<?PHP echo $BarCode; ?>" size="30"/></TD>
					</TR>
					<TR>
					  <TD width="8%" valign="top"  align="left">Bill No </TD>
					  <TD width="24%" valign="top"  align="left"><input name="BillNo" type="text"value="<?PHP echo $BillNo; ?>" size="20"/></TD>
					  <TD width="9%" valign="top"  align="left">Currency</TD>
					  <TD width="25%" valign="top"  align="left">
					  <select name="OptCurrency" style="width:120px;">
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,Curr from tbcurrency order by Curr";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$CurrID = $row["ID"];
								$Curr = $row["Curr"];
								
								if($OptCurrency =="$CurrID"){
?>
                        			<option value="<?PHP echo $CurrID; ?>" selected="selected"><?PHP echo $Curr; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $CurrID; ?>"><?PHP echo $Curr; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					  <input name="SubmitCurr" type="submit" id="SubmitCurr" value="..." title="Update Currency"></TD>
					  <TD width="7%" valign="top"  align="left">Price</TD>
					  <TD valign="top"  align="left"><input name="Price" type="text"value="<?PHP echo $Price; ?>" size="30"/></TD>
					</TR>
					<TR>
					  <TD width="8%" valign="top"  align="left">Book Condition.  </TD>
					  <TD width="24%" valign="top"  align="left">
					  <select name="OptCondition" style="width:120px;">
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,BCond from bookcondition order by BCond";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$BCondID = $row["ID"];
								$BCond = $row["BCond"];
								
								if($OptCondition =="$BCondID"){
?>
                        			<option value="<?PHP echo $BCondID; ?>" selected="selected"><?PHP echo $BCond; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $BCondID; ?>"><?PHP echo $BCond; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					    <input name="SubmitBCond" type="submit" id="SubmitBCond" value="..." title="Update Book Condition"></TD>
					  <TD width="9%" valign="top"  align="left">Purchase Date </TD>
					  <TD width="25%" valign="top"  align="left">
					  <select name="pur_day">
                        <option value="0">Day</option>
<?PHP
							$CurDay = date('d');
							$Found="False";
							for($i=1; $i<=31; $i++){
								if($pur_day == $i){
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
                        <select name="pur_mth">
                          <option value="0">Mth</option>
                          <?PHP
								$CurMth = date('m');
								$Found="False";
								for($i=1; $i<=12; $i++){
									if($i == $pur_mth){
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
                        <select name="pur_yr">
                          <option value="0">Yr</option>
                          <?PHP
							$CurYear = date('Y');
							$Found="False";
							for($i=2009; $i<=$CurYear; $i++){
								if($pur_yr == $i){
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
					  <TD width="7%" valign="top"  align="left">Supplier</TD>
					  <TD valign="top"  align="left">
					  <select name="OptSupplier" style="width:120px;">
                        <option value="0" selected="selected"></option>
<?PHP
						$counter = 0;
						$query = "select ID,sup_name from tbsupplier order by sup_name";
						$result = mysql_query($query,$conn);
						$num_rows = mysql_num_rows($result);
						if ($num_rows > 0 ) {
							while ($row = mysql_fetch_array($result)) 
							{
								$counter = $counter+1;
								$SupID = $row["ID"];
								$sup_name = $row["sup_name"];
								
								if($OptSupplier =="$SupID"){
?>
                        			<option value="<?PHP echo $SupID; ?>" selected="selected"><?PHP echo $sup_name; ?></option>
<?PHP
								}else{
?>
                        			<option value="<?PHP echo $SupID; ?>"><?PHP echo $sup_name; ?></option>
<?PHP
								}
							}
						}
?>
                      </select>
					    <input name="SubmitSupplier" type="submit" id="SubmitSupplier" value="..." title="Update Supplier"></TD>
					</TR>
					<TR>
					  <TD width="8%" valign="top"  align="left">Accession No.   </TD>
					  <TD width="24%" valign="top"  align="left"><input name="BookNo" type="text"value="<?PHP echo $BookNo; ?>" size="20"/>
					  *</TD>
					  <TD width="9%" valign="top"  align="left">Year of Publication </TD>
					  <TD width="25%" valign="top"  align="left"><input name="YearPub" type="text"value="<?PHP echo $YearPub; ?>" size="20"/></TD>
					  <TD width="7%" valign="top"  align="left">Book Rate </TD>
					  <TD valign="top"  align="left"><input name="BookRate" type="text"value="<?PHP echo $BookRate; ?>" size="30"/></TD>
					</TR>
					<TR>
						 <TD colspan="6">
						  <div align="center"><p>&nbsp;</p>
							 <input type="hidden" name="SelBookID" value="<?PHP echo $Libbook_id; ?>">
							 <input name="SaveBookMst" type="submit" id="SaveBookMst" value="Create New">
						     <input name="SaveBookMst" type="submit" id="SaveBookMst" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="SaveBookMst" type="submit" id="SaveBookMst" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div><hr>
						  </TD>
					</TR>
					<TR>
					  <TD colspan="6" valign="top"  align="left">					  
						<p style="margin-left:50px;">Search By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <input name="OptSearch" type="radio" value="Title">Book	Title	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <input name="OptSearch" type="radio" value="No"> Accession No. 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		  
						  Search Key :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            </label>
					    </p>
					    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="46" bgcolor="#F4F4F4"><div align="center" class="style21">Status</div></td>
                            <td width="139" bgcolor="#F4F4F4"><div align="center" class="style21">Title</div></td>
                            <td width="94" bgcolor="#F4F4F4"><div align="center"><strong>Class</strong></div></td>
							<td width="123" bgcolor="#F4F4F4"><div align="center"><strong>Edition</strong></div></td>
							<td width="109" bgcolor="#F4F4F4"><div align="center"><strong>ISBN No. </strong></div></td>
							<td width="66" bgcolor="#F4F4F4"><div align="center"><strong>Price</strong></div></td>
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
								if($Optsearch == "Title"){
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(Title,'$Search_Key') order by Title LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							   $query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(Title,'$Search_Key') order by Title";
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
										$Status = "Active";
									}elseif($Status==1){
										$Status = "Scrap";
									}elseif($Status==1){
										$Status = "Lost";
									}else{
										$Status = "Not Select";
									}
?>
									<tr>
										<td bgcolor="#cccccc"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Price; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; ?></a></div></td>
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
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
							<?PHP 	}else{
									$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') order by BookNo LIMIT $rstart,$rend";
									$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst where INSTR(BookNo,'$Search_Key') order by BookNo LIMIT $rstart,$rend";
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
										$Status = "Active";
									}elseif($Status==1){
										$Status = "Scrap";
									}elseif($Status==1){
										$Status = "Lost";
									}else{
										$Status = "Not Select";
									}
?>
									<tr>
										<td bgcolor="#cccccc"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Price; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; ?></a></div></td>
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
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
								<?PHP }
							}else{
								$query3 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst LIMIT $rstart,$rend";
							                                                                       //}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							$query4 = "select ID,Title,ClassID,Edition,Status,ISBN,Price,BookNo from tbbookmst";
							                                                                       //}
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
										$Status = "Active";
									}elseif($Status==1){
										$Status = "Scrap";
									}elseif($Status==1){
										$Status = "Lost";
									}else{
										$Status = "Not Select";
									}
?>
									<tr>
										<td bgcolor="#cccccc"><div align="center"><?PHP echo $Status; ?></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Title; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo GetClassName($ClassID); ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Edition; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $ISBN; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $Price; ?></a></div></td>
										<td bgcolor="#cccccc"><div align="center"><a href="library.php?subpg=Book Master&book_id=<?PHP echo $bookID; ?>"><?PHP echo $BookNo; ?></a></div></td>
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
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=0&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="library.php?subpg=Book Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>&Optsearch=<?PHP echo $Optsearch; ?>&Search_Key=<?PHP echo $Search_Key; ?>">Next</a> </p>
                        <?php
						               }
						  ?>
					  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Library Fine Policy") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="library.php?subpg=Library Fine Policy">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" valign="top"  align="left"><div align="right">Days Between</div></TD>
					  <TD width="50%" valign="top"  align="left"><input name="FrmDay" type="text" size="5" value="<?PHP echo $FrmDay; ?>">
					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  TO &nbsp;&nbsp;
					  <input name="ToDay" type="text" size="5" value="<?PHP echo $ToDay; ?>"></TD>
					</TR>
					<TR>
					  <TD width="21%" valign="top"  align="left"><div align="right">Fine Per Day</div></TD>
					  <TD width="50%" valign="top"  align="left"><input name="FineAmount" type="text" value="<?PHP echo $FineAmount; ?>" size="5"></TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
							 <input type="hidden" name="SelFineID" value="<?PHP echo $FineID; ?>">
							 <input name="Finemaster" type="submit" id="Finemaster" value="Create New">
						     <input name="Finemaster" type="submit" id="Finemaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="2"><p>&nbsp;</p><hr>
					    <table width="535" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="52" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="122" bgcolor="#F4F4F4"><div align="center" class="style21">Fine From</div></td>
                            <td width="109" bgcolor="#F4F4F4"><div align="left"><strong>Fine To</strong></div></td>
							<td width="213" bgcolor="#F4F4F4"><div align="left"><strong>Fine Per Day.</strong></div></td>
                          </tr>
<?PHP
							$counter = 0;
							$query3 = "select * from TbLibFineMst order by Finefrom";
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter = $counter+1;
									$fineID = $row["ID"];
									$Finefrom = $row["Finefrom"];
									$Fineto = $row["Fineto"];
									$FinePerDay = $row["FinePerDay"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkFineID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $fineID; ?>"></div></td>
										<td><div align="center"><a href="library.php?subpg=Library Fine Policy&fine_id=<?PHP echo $fineID; ?>"><?PHP echo $Finefrom; ?></a></div></td>
										<td><div align="left"><a href="library.php?subpg=Library Fine Policy&fine_id=<?PHP echo $fineID; ?>"><?PHP echo $Fineto; ?></a></div></td>
										<td><div align="left"><a href="library.php?subpg=Library Fine Policy&fine_id=<?PHP echo $fineID; ?>"><?PHP echo $FinePerDay; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
						</TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="TotalFine" value="<?PHP echo $counter; ?>">
						     <input name="Finemaster_delete" type="submit" id="Finemaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
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
