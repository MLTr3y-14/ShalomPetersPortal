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
	if(isset($_GET['st2']))
	{
		$rstart = $_GET['st2'];
		$rend = $_GET['ed2'];
		$_SESSION['hideTopMenu'] = 'true';
		
	}else{
		$rstart = 0;
		$rend = 10;
		
	}
	if(isset($_POST['Catmaster']))
	{
		$PageHasError = 0;
		$CategoryID = $_POST['SelCatID'];
		$CatName = $_POST['CatName'];
		
		if(!$_POST['CatName']){
			$errormsg = "<font color = red size = 1>Category is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Catmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tblibcategorymst where CatName = '$CatName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The category name you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tblibcategorymst(CatName) Values ('$CatName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CatName = "";
				}
			}elseif ($_POST['Catmaster'] =="Update"){
				$q = "update tblibcategorymst set CatName = '$CatName' where ID = '$CategoryID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CatName = "";
				$CategoryID = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['cat_id']))
	{
		$CategoryID = $_GET['cat_id'];
		$query = "select * from tblibcategorymst where ID='$CategoryID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CatName  = $dbarray['CatName'];
	}
	if(isset($_GET['cat_id2']))
	{    
	     $_SESSION['hideTopMenu'] = 'true';
		$CategoryID = $_GET['cat_id2'];
		$query = "select * from tblibcategorymst where ID='$CategoryID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CatName  = $dbarray['CatName'];
	}
	if(isset($_POST['Catmasterr_delete']))
	{
		$Total = $_POST['Totalcat'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCatID'.$i]))
			{
				$chkCatID = $_POST['chkCatID'.$i];
				$q = "Delete From tblibcategorymst where ID = '$chkCatID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = red size = 1>Deleted Successfully.</font>";
			}
		}
		
	}
	
	if(isset($_POST['Catmasterr_delete2']))
	{    
	    $_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['Totalcat'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCatID2'.$i]))
			{
				$chkCatID = $_POST['chkCatID2'.$i];
				$q = "Delete From tblibcategorymst where ID = '$chkCatID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = red size = 1>Deleted Successfully.</font>";
			}
		}
	}
	if(isset($_POST['OptCat']))
	{
		$OptCat = $_POST['OptCat'];
	}
	if(isset($_POST['OptCat2']))
	{   
	    $_SESSION['hideTopMenu'] = 'true';
		$OptCat = $_POST['OptCat2'];
	}
	if(isset($_POST['Catmaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$CategoryID = $_POST['SelCatID'];
		$CatName = $_POST['CatName'];
		
		if(!$_POST['CatName']){
			$errormsg = "<font color = red size = 1>Category is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Catmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tblibcategorymst where CatName = '$CatName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The category name you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tblibcategorymst(CatName) Values ('$CatName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CatName = "";
				}
			}elseif ($_POST['Catmaster2'] =="Update"){
				$q = "update tblibcategorymst set CatName = '$CatName' where ID = '$CategoryID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CatName = "";
				$CategoryID = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_POST['sCatmaster']))
	{    
	    
		$PageHasError = 0;
		$subCatID = $_POST['SelsCatID'];
		$OptCat = $_POST['OptCat'];
		$subCatName = $_POST['subCatName'];
		
		if(!$_POST['OptCat']){
			$errormsg = "<font color = red size = 1>Select Category.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['subCatName']){
			$errormsg = "<font color = red size = 1>Sub Category is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['sCatmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tblibsubcatmst where SubCatName = '$subCatName' and CatID ='$OptCat'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The sub category you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tblibsubcatmst(CatID,SubCatName) Values ('$OptCat','$subCatName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$subCatName = "";
				}
			}elseif ($_POST['sCatmaster'] =="Update"){
				$q = "update tblibsubcatmst set CatID = '$OptCat',SubCatName = '$subCatName' where ID = '$subCatID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$subCatName = "";
			}
		}
	}
	if(isset($_POST['sCatmaster2']))
	{
		 $_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$subCatID = $_POST['SelsCatID'];
		$OptCat = $_POST['OptCat2'];
		$subCatName = $_POST['subCatName'];
		
		if(!$_POST['OptCat2']){
			$errormsg = "<font color = red size = 1>Select Category.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['subCatName']){
			$errormsg = "<font color = red size = 1>Sub Category is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['sCatmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tblibsubcatmst where SubCatName = '$subCatName' and CatID ='$OptCat'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The sub category you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tblibsubcatmst(CatID,SubCatName) Values ('$OptCat','$subCatName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$subCatName = "";
				}
			}elseif ($_POST['sCatmaster2'] =="Update"){
				$q = "update tblibsubcatmst set CatID = '$OptCat',SubCatName = '$subCatName' where ID = '$subCatID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$subCatName = "";
			}
		}
	}
	if(isset($_GET['scat_id']))
	{
		$subCatID = $_GET['scat_id'];
		$_POST['OptCat'] = $_GET['catid'];
		$query = "select * from tblibsubcatmst where ID='$subCatID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptCat  = $dbarray['CatID'];
		$subCatName  = $dbarray['SubCatName'];
	}
	if(isset($_GET['scat_id2']))
	{     $_SESSION['hideTopMenu'] = 'true';
		$subCatID = $_GET['scat_id2'];
		$_POST['OptCat'] = $_GET['catid2'];
		$query = "select * from tblibsubcatmst where ID='$subCatID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$OptCat  = $dbarray['CatID'];
		$subCatName  = $dbarray['SubCatName'];
	}
	if(isset($_POST['sCatmaster_delete']))
	{
		$Total = $_POST['Totalscat'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkSubcatID'.$i]))
			{
				$chkSubcatID = $_POST['chkSubcatID'.$i];
				$q = "Delete From tblibsubcatmst where ID = '$chkSubcatID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['sCatmaster_delete2']))
	{   
	     $_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['Totalscat'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkSubcatID2'.$i]))
			{
				$chkSubcatID = $_POST['chkSubcatID2'.$i];
				$q = "Delete From tblibsubcatmst where ID = '$chkSubcatID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Bindmaster']))
	{    
	     
		$PageHasError = 0;
		$BindID = $_POST['SelBindID'];
		$BindName = $_POST['BindName'];
		
		if(!$_POST['BindName']){
			$errormsg = "<font color = red size = 1>Binding Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Bindmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbbinding where Binding = '$BindName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Binding you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbbinding(Binding) Values ('$BindName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$Code = "";
					$BindName = "";
				}
			}elseif ($_POST['Bindmaster'] =="Update"){
				$q = "update tbbinding set Binding = '$BindName' where ID = '$BindID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$Code = "";
				$BindName = "";
			}
		}
	}
	if(isset($_POST['Bindmaster2']))
	{   
	    $_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$BindID = $_POST['SelBindID'];
		$BindName = $_POST['BindName'];
		
		if(!$_POST['BindName']){
			$errormsg = "<font color = red size = 1>Binding Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Bindmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbbinding where Binding = '$BindName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Binding you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbbinding(Binding) Values ('$BindName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$Code = "";
					$BindName = "";
				}
			}elseif ($_POST['Bindmaster2'] =="Update"){
				$q = "update tbbinding set Binding = '$BindName' where ID = '$BindID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$Code = "";
				$BindName = "";
			}
		}
	}
	if(isset($_GET['bind_id']))
	{   
	     
		$BindID = $_GET['bind_id'];
		$query = "select * from tbbinding where ID='$BindID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$BindName  = $dbarray['Binding'];
	}
	if(isset($_GET['bind_id2']))
	{   
	    $_SESSION['hideTopMenu'] = 'true';
		$BindID = $_GET['bind_id2'];
		$query = "select * from tbbinding where ID='$BindID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$BindName  = $dbarray['Binding'];
	}
	if(isset($_POST['Bindmaster_delete']))
	{
		$Total = $_POST['TotalBind'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkbindID'.$i]))
			{
				$chkbindID = $_POST['chkbindID'.$i];
				$q = "Delete From tbbinding where ID = '$chkbindID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Bindmaster_delete2']))
	{   
	     $_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalBind'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkbindID2'.$i]))
			{
				$chkbindID = $_POST['chkbindID2'.$i];
				$q = "Delete From tbbinding where ID = '$chkbindID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Countmaster']))
	{
		$PageHasError = 0;
		$CountryID = $_POST['SelCountryID'];
		$CountryName = $_POST['CountryName'];
		
		if(!$_POST['CountryName']){
			$errormsg = "<font color = red size = 1>Country name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Countmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbcountry where Country = '$CountryName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Country you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbcountry(Country) Values ('$CountryName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CountryName = "";
				}
			}elseif ($_POST['Countmaster'] =="Update"){
				$q = "update tbcountry set Country = '$CountryName' where ID = '$CountryID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CountryName = "";
			}
		}
	}
	if(isset($_POST['Countmaster2']))
	{    
	     $_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$CountryID = $_POST['SelCountryID'];
		$CountryName = $_POST['CountryName'];
		
		if(!$_POST['CountryName']){
			$errormsg = "<font color = red size = 1>Country name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Countmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbcountry where Country = '$CountryName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Country you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbcountry(Country) Values ('$CountryName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CountryName = "";
				}
			}elseif ($_POST['Countmaster2'] =="Update"){
				$q = "update tbcountry set Country = '$CountryName' where ID = '$CountryID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CountryName = "";
			}
		}
	}
	if(isset($_GET['country_id']))
	{
		$CountryID = $_GET['country_id'];
		$query = "select * from tbcountry where ID='$CountryID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CountryName  = $dbarray['Country'];
	}
	if(isset($_GET['country_id2']))
	{    
	     $_SESSION['hideTopMenu'] = 'true';
		$CountryID = $_GET['country_id2'];
		$query = "select * from tbcountry where ID='$CountryID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CountryName  = $dbarray['Country'];
	}
	if(isset($_POST['Countmasterr_delete']))
	{
		$Total = $_POST['TotalCountry'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCountryID'.$i]))
			{
				$chkCountryID = $_POST['chkCountryID'.$i];
				$q = "Delete From tbcountry where ID = '$chkCountryID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Countmasterr_delete2']))
	{   
	     $_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalCountry'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCountryID2'.$i]))
			{
				$chkCountryID = $_POST['chkCountryID2'.$i];
				$q = "Delete From tbcountry where ID = '$chkCountryID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Authormaster']))
	{
		$PageHasError = 0;
		$AuthorID = $_POST['SelAuthorID'];
		$AuthName = $_POST['AuthName'];
		
		if(!$_POST['AuthName']){
			$errormsg = "<font color = red size = 1>Auhtor name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Authormaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbauthormaster where AuthorName = '$AuthName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Author you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbauthormaster(AuthorName) Values ('$AuthName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$AuthName = "";
				}
			}elseif ($_POST['Authormaster'] =="Update"){
				$q = "update tbauthormaster set AuthorName = '$AuthName' where ID = '$AuthorID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$AuthName = "";
			}
		}
	}
	if(isset($_POST['Authormaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$AuthorID = $_POST['SelAuthorID'];
		$AuthName = $_POST['AuthName'];
		
		if(!$_POST['AuthName']){
			$errormsg = "<font color = red size = 1>Auhtor name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Authormaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbauthormaster where AuthorName = '$AuthName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Author you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbauthormaster(AuthorName) Values ('$AuthName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$AuthName = "";
				}
			}elseif ($_POST['Authormaster2'] =="Update"){
				$q = "update tbauthormaster set AuthorName = '$AuthName' where ID = '$AuthorID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$AuthName = "";
			}
		}
	}
	if(isset($_GET['author_id']))
	{
		$AuthorID = $_GET['author_id'];
		$query = "select * from tbauthormaster where ID='$AuthorID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$AuthName  = $dbarray['AuthorName'];
	}
	if(isset($_GET['author_id2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$AuthorID = $_GET['author_id2'];
		$query = "select * from tbauthormaster where ID='$AuthorID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$AuthName  = $dbarray['AuthorName'];
	}
	if(isset($_POST['Authormaster_delete']))
	{
		$Total = $_POST['TotalAuthor'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkAuthorID'.$i]))
			{
				$chkAuthorID = $_POST['chkAuthorID'.$i];
				$q = "Delete From tbauthormaster where ID = '$chkAuthorID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Authormaster_delete2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalAuthor'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkAuthorID2'.$i]))
			{
				$chkAuthorID = $_POST['chkAuthorID2'.$i];
				$q = "Delete From tbauthormaster where ID = '$chkAuthorID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Pubmaster']))
	{
		$PageHasError = 0;
		$PublisherID = $_POST['SelPubID'];
		$PublisherName = $_POST['PublisherName'];
		
		if(!$_POST['PublisherName']){
			$errormsg = "<font color = red size = 1>Publisher name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Pubmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbpublisher where PubName = '$PublisherName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Publisher you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbpublisher(PubName) Values ('$PublisherName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$PublisherName = "";
				}
			}elseif ($_POST['Pubmaster'] =="Update"){
				$q = "update tbpublisher set PubName = '$PublisherName' where ID = '$PublisherID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$PublisherName = "";
			}
		}
	}
	if(isset($_POST['Pubmaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$PublisherID = $_POST['SelPubID'];
		$PublisherName = $_POST['PublisherName'];
		
		if(!$_POST['PublisherName']){
			$errormsg = "<font color = red size = 1>Publisher name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Pubmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbpublisher where PubName = '$PublisherName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Publisher you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbpublisher(PubName) Values ('$PublisherName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$PublisherName = "";
				}
			}elseif ($_POST['Pubmaster2'] =="Update"){
				$q = "update tbpublisher set PubName = '$PublisherName' where ID = '$PublisherID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$PublisherName = "";
			}
		}
	}
	if(isset($_GET['pub_id']))
	{
		$PublisherID = $_GET['pub_id'];
		$query = "select * from tbpublisher where ID='$PublisherID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$PublisherName  = $dbarray['PubName'];
	}
	if(isset($_GET['pub_id2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PublisherID = $_GET['pub_id2'];
		$query = "select * from tbpublisher where ID='$PublisherID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$PublisherName  = $dbarray['PubName'];
	}
	if(isset($_POST['Pubmaster_delete']))
	{
		$Total = $_POST['TotalPub'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkPubID'.$i]))
			{
				$chkPubID = $_POST['chkPubID'.$i];
				$q = "Delete From tbpublisher where ID = '$chkPubID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Pubmaster_delete2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalPub'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkPubID2'.$i]))
			{
				$chkPubID = $_POST['chkPubID2'.$i];
				$q = "Delete From tbpublisher where ID = '$chkPubID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Pubplacemaster']))
	{
		$PageHasError = 0;
		$PubliPlaceID = $_POST['SelPubplaceID'];
		$PubPlaceName = $_POST['PubPlaceName'];
		
		if(!$_POST['PubPlaceName']){
			$errormsg = "<font color = red size = 1>Publication place name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Pubplacemaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbpublicationplace where PubPlace = '$PubPlaceName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Publication place you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbpublicationplace(PubPlace) Values ('$PubPlaceName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$PubPlaceName = "";
				}
			}elseif ($_POST['Pubplacemaster'] =="Update"){
				$q = "update tbpublicationplace set PubPlace = '$PubPlaceName' where ID = '$PubliPlaceID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$PubPlaceName = "";
			}
		}
	}
	if(isset($_POST['Pubplacemaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$PubliPlaceID = $_POST['SelPubplaceID'];
		$PubPlaceName = $_POST['PubPlaceName'];
		
		if(!$_POST['PubPlaceName']){
			$errormsg = "<font color = red size = 1>Publication place name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Pubplacemaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbpublicationplace where PubPlace = '$PubPlaceName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Publication place you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbpublicationplace(PubPlace) Values ('$PubPlaceName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$PubPlaceName = "";
				}
			}elseif ($_POST['Pubplacemaster2'] =="Update"){
				$q = "update tbpublicationplace set PubPlace = '$PubPlaceName' where ID = '$PubliPlaceID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$PubPlaceName = "";
			}
		}
	}
	if(isset($_GET['pubplace_id']))
	{
		$PubliPlaceID = $_GET['pubplace_id'];
		$query = "select * from tbpublicationplace where ID='$PubliPlaceID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$PubPlaceName  = $dbarray['PubPlace'];
	}
	if(isset($_GET['pubplace_id2']))
	{ 
	    $_SESSION['hideTopMenu'] = 'true';
		$PubliPlaceID = $_GET['pubplace_id2'];
		$query = "select * from tbpublicationplace where ID='$PubliPlaceID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$PubPlaceName  = $dbarray['PubPlace'];
	}
	if(isset($_POST['Pubplacemaster_delete']))
	{
		$Total = $_POST['TotalPubplace'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkPubPlaceID'.$i]))
			{
				$chkPubPlaceID = $_POST['chkPubPlaceID'.$i];
				$q = "Delete From tbpublicationplace where ID = '$chkPubPlaceID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Pubplacemaster_delete2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalPubplace'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkPubPlaceID2'.$i]))
			{
				$chkPubPlaceID = $_POST['chkPubPlaceID2'.$i];
				$q = "Delete From tbpublicationplace where ID = '$chkPubPlaceID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['landmaster']))
	{
		$PageHasError = 0;
		$landID = $_POST['SellandID'];
		$landName = $_POST['landName'];
		
		if(!$_POST['landName']){
			$errormsg = "<font color = red size = 1>language name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['landmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tblanguage where lang = '$landName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Language you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tblanguage(lang) Values ('$landName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$landName = "";
				}
			}elseif ($_POST['landmaster'] =="Update"){
				$q = "update tblanguage set lang = '$landName' where ID = '$landID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$landName = "";
			}
		}
	}
	if(isset($_POST['landmaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$landID = $_POST['SellandID'];
		$landName = $_POST['landName'];
		
		if(!$_POST['landName']){
			$errormsg = "<font color = red size = 1>language name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['landmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tblanguage where lang = '$landName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Language you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tblanguage(lang) Values ('$landName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$landName = "";
				}
			}elseif ($_POST['landmaster2'] =="Update"){
				$q = "update tblanguage set lang = '$landName' where ID = '$landID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$landName = "";
			}
		}
	}
	if(isset($_GET['lang_id']))
	{
		$landID = $_GET['lang_id'];
		$query = "select * from tblanguage where ID='$landID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$landName  = $dbarray['lang'];
	}
	if(isset($_GET['lang_id2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$landID = $_GET['lang_id2'];
		$query = "select * from tblanguage where ID='$landID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$landName  = $dbarray['lang'];
	}
	if(isset($_POST['landmaster_delete']))
	{
		$Total = $_POST['Totalland'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chklangID'.$i]))
			{
				$chklangID = $_POST['chklangID'.$i];
				$q = "Delete From tblanguage where ID = '$chklangID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['landmaster_delete2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['Totalland'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chklangID2'.$i]))
			{
				$chklangID = $_POST['chklangID2'.$i];
				$q = "Delete From tblanguage where ID = '$chklangID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Supmaster']))
	{
		$PageHasError = 0;
		$SupplierID = $_POST['SelSupID'];
		$SupName = $_POST['SupName'];
		$Address = $_POST['Address'];
		$Contact = $_POST['Contact'];
		
		if(!$_POST['SupName']){
			$errormsg = "<font color = red size = 1>Supplier name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Supmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbsupplier where sup_name = '$SupName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The supplier you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbsupplier(sup_name,sup_addr,sup_phone) Values ('$SupName','$Address','$Contact')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$SupName = "";
					$Address = "";
					$Contact = "";
				}
			}elseif ($_POST['Supmaster'] =="Update"){
				$q = "update tbsupplier set sup_name = '$SupName',sup_addr = '$Address',sup_phone = '$Contact' where ID = '$SupplierID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$SupName = "";
				$Address = "";
				$Contact = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_POST['Supmaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$SupplierID = $_POST['SelSupID'];
		$SupName = $_POST['SupName'];
		$Address = $_POST['Address'];
		$Contact = $_POST['Contact'];
		
		if(!$_POST['SupName']){
			$errormsg = "<font color = red size = 1>Supplier name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Supmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbsupplier where sup_name = '$SupName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The supplier you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbsupplier(sup_name,sup_addr,sup_phone) Values ('$SupName','$Address','$Contact')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$SupName = "";
					$Address = "";
					$Contact = "";
				}
			}elseif ($_POST['Supmaster2'] =="Update"){
				$q = "update tbsupplier set sup_name = '$SupName',sup_addr = '$Address',sup_phone = '$Contact' where ID = '$SupplierID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$SupName = "";
				$Address = "";
				$Contact = "";
				$disabled = " disabled='disabled'";
			}
		}
	}
	if(isset($_GET['sup_id']))
	{
		$SupplierID = $_GET['sup_id'];
		$query = "select * from tbsupplier where ID='$SupplierID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SupName  = $dbarray['sup_name'];
		$Address  = $dbarray['sup_addr'];
		$Contact  = $dbarray['sup_phone'];
	}
	if(isset($_GET['sup_id2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$SupplierID = $_GET['sup_id2'];
		$query = "select * from tbsupplier where ID='$SupplierID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SupName  = $dbarray['sup_name'];
		$Address  = $dbarray['sup_addr'];
		$Contact  = $dbarray['sup_phone'];
	}
	if(isset($_POST['Supplier_delete']))
	{
		$Total = $_POST['Totalsup'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkSupID'.$i]))
			{
				$chkSupID = $_POST['chkSupID'.$i];
				$q = "Delete From tbsupplier where ID = '$chkSupID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Supplier_delete2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['Totalsup'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkSupID2'.$i]))
			{
				$chkSupID = $_POST['chkSupID2'.$i];
				$q = "Delete From tbsupplier where ID = '$chkSupID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['condmaster']))
	{
		$PageHasError = 0;
		$ConditionID = $_POST['SelCondID'];
		$CondName = $_POST['CondName'];
		
		if(!$_POST['CondName']){
			$errormsg = "<font color = red size = 1>Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['condmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from bookcondition where BCond = '$CondName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The book condition you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into bookcondition(BCond) Values ('$CondName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CondName = "";
				}
			}elseif ($_POST['condmaster'] =="Update"){
				$q = "update bookcondition set BCond = '$CondName' where ID = '$ConditionID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CondName = "";
			}
		}
	}
	if(isset($_POST['condmaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$ConditionID = $_POST['SelCondID'];
		$CondName = $_POST['CondName'];
		
		if(!$_POST['CondName']){
			$errormsg = "<font color = red size = 1>Name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['condmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from bookcondition where BCond = '$CondName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The book condition you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into bookcondition(BCond) Values ('$CondName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CondName = "";
				}
			}elseif ($_POST['condmaster2'] =="Update"){
				$q = "update bookcondition set BCond = '$CondName' where ID = '$ConditionID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CondName = "";
			}
		}
	}
	if(isset($_GET['cond_id']))
	{
		$ConditionID = $_GET['cond_id'];
		$query = "select * from bookcondition where ID='$ConditionID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CondName  = $dbarray['BCond'];
	}
	if(isset($_GET['cond_id2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$ConditionID = $_GET['cond_id2'];
		$query = "select * from bookcondition where ID='$ConditionID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CondName  = $dbarray['BCond'];
	}
	if(isset($_POST['condmaster_delete']))
	{
		$Total = $_POST['TotalCond'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkcondID'.$i]))
			{
				$chkcondID = $_POST['chkcondID'.$i];
				$q = "Delete From bookcondition where ID = '$chkcondID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['condmaster_delete2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalCond'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkcondID2'.$i]))
			{
				$chkcondID = $_POST['chkcondID2'.$i];
				$q = "Delete From bookcondition where ID = '$chkcondID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['seriesmaster']))
	{
		$PageHasError = 0;
		$SeriesID = $_POST['SelSeriesID'];
		$SeriesName = $_POST['SeriesName'];
		
		if(!$_POST['SeriesName']){
			$errormsg = "<font color = red size = 1>Series name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['seriesmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbseries where Series = '$SeriesName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Series you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbseries(Series) Values ('$SeriesName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$SeriesName = "";
				}
			}elseif ($_POST['seriesmaster'] =="Update"){
				$q = "update tbseries set Series = '$SeriesName' where ID = '$SeriesID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$SeriesName = "";
			}
		}
	}
	if(isset($_POST['seriesmaster2']))
	{   
	    $_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$SeriesID = $_POST['SelSeriesID'];
		$SeriesName = $_POST['SeriesName'];
		
		if(!$_POST['SeriesName']){
			$errormsg = "<font color = red size = 1>Series name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['seriesmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbseries where Series = '$SeriesName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Series you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbseries(Series) Values ('$SeriesName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$SeriesName = "";
				}
			}elseif ($_POST['seriesmaster2'] =="Update"){
				$q = "update tbseries set Series = '$SeriesName' where ID = '$SeriesID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$SeriesName = "";
			}
		}
	}
	if(isset($_GET['series_id']))
	{
		$SeriesID = $_GET['series_id'];
		$query = "select * from tbseries where ID='$SeriesID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SeriesName  = $dbarray['Series'];
	}
	if(isset($_GET['series_id2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$SeriesID = $_GET['series_id2'];
		$query = "select * from tbseries where ID='$SeriesID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SeriesName  = $dbarray['Series'];
	}
	if(isset($_POST['seriesmaster_delete']))
	{
		$Total = $_POST['TotalSeries'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkseriesID'.$i]))
			{
				$chkseriesID = $_POST['chkseriesID'.$i];
				$q = "Delete From tbseries where ID = '$chkseriesID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['seriesmaster_delete2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalSeries'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkseriesID2'.$i]))
			{
				$chkseriesID = $_POST['chkseriesID2'.$i];
				$q = "Delete From tbseries where ID = '$chkseriesID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Currmaster']))
	{
		$PageHasError = 0;
		$CurrencyID = $_POST['SelCurrID'];
		$CurrName = $_POST['CurrName'];
		
		if(!$_POST['CurrName']){
			$errormsg = "<font color = red size = 1>Currrency name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Currmaster'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbcurrency where Curr = '$CurrName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Currency you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbcurrency(Curr) Values ('$CurrName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CurrName = "";
				}
			}elseif ($_POST['Currmaster'] =="Update"){
				$q = "update tbcurrency set Curr = '$CurrName' where ID = '$CurrencyID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CurrName = "";
			}
		}
	}
	if(isset($_POST['Currmaster2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$PageHasError = 0;
		$CurrencyID = $_POST['SelCurrID'];
		$CurrName = $_POST['CurrName'];
		
		if(!$_POST['CurrName']){
			$errormsg = "<font color = red size = 1>Currrency name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['Currmaster2'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbcurrency where Curr = '$CurrName'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The Currency you are trying to save already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbcurrency(Curr) Values ('$CurrName')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$CurrName = "";
				}
			}elseif ($_POST['Currmaster2'] =="Update"){
				$q = "update tbcurrency set Curr = '$CurrName' where ID = '$CurrencyID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$CurrName = "";
			}
		}
	}
	if(isset($_GET['Curr_id']))
	{
		$CurrencyID = $_GET['Curr_id'];
		$query = "select * from tbcurrency where ID='$CurrencyID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CurrName  = $dbarray['Curr'];
	}
	if(isset($_GET['Curr_id2']))
	{
		$_SESSION['hideTopMenu'] = 'true';
		$CurrencyID = $_GET['Curr_id2'];
		$query = "select * from tbcurrency where ID='$CurrencyID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$CurrName  = $dbarray['Curr'];
	}
	if(isset($_POST['Currmaster_delete']))
	{
		$Total = $_POST['TotalCurr'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCurrID'.$i]))
			{
				$chkCurrID = $_POST['chkCurrID'.$i];
				$q = "Delete From tbcurrency where ID = '$chkCurrID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['Currmaster_delete2']))
	{    
	     $_SESSION['hideTopMenu'] = 'true';
		$Total = $_POST['TotalCurr'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkCurrID2'.$i]))
			{
				$chkCurrID = $_POST['chkCurrID2'.$i];
				$q = "Delete From tbcurrency where ID = '$chkCurrID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
	if(isset($_POST['SubmitSearch2'])){
		$_SESSION['hideTopMenu'] = 'true';
	}
	if(isset($_POST['SubmitSupp2'])){
		$_SESSION['hideTopMenu'] = 'true';
	}
	if(isset($_POST['BackToBookMaster'])){
		
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=library.php?subpg=Book Master\">";
		exit;
		
	}
	$Title =$_SESSION['Title'];
		
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
			<?PHP
			if(!$_SESSION['hideTopMenu'] == 'true')
			{
			include 'topmenu.php';
			 }
			 
			?>
           
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
			  <TD width="220" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP 
					if(!$_SESSION['hideTopMenu'] == 'true')
			           {
				         include 'sidemenu.php';
						 
				         }
						 
				?>
			  </TD>
			  <TD width="858" align="center" valign="top">
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
		if ($SubPage == "Category Master") {
?>
				<?PHP echo $errormsg.$Title; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Category Master">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="12%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="17%" valign="top"  align="left">&nbsp;</TD>
					  <TD width="21%" valign="top"  align="left">Category Name </TD>
					  <TD width="50%" valign="top"  align="left"><input name="CatName" type="text" size="55" value="<?PHP echo $CatName; ?>"></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
                          <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                          {?>
							 <input type="hidden" name="SelHeadID" value="<?PHP echo $ProgHeadID; ?>">
							 <input name="Catmaster" type="submit" id="Catmaster" value="Create New">
						     <input name="Catmaster" type="submit" id="Catmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP } else{
								 
								 ?>
								 <input type="hidden" name="SelHeadID" value="<?PHP echo $ProgHeadID; ?>">
							 <input name="Catmaster2" type="submit" id="Catmaster" value="Create New">
						     <input name="Catmaster2" type="submit" id="Catmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset"> 
                             <?PHP
							        }
							 ?>
                             
					   
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4"><p>&nbsp;</p><hr>
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                             <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                          {?>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            <?PHP } else{
								 
								 ?>
                                 <input name="SubmitSearch2" type="submit" id="Search" value="Go">
                             <?PHP
							        }
							 ?>
                            </label>
					    </p>
					    <table width="535" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="74" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="153" bgcolor="#F4F4F4"><div align="center" class="style21">Sr.</div></td>
                            <td width="278" bgcolor="#F4F4F4"><div align="left"><strong>Category Name </strong></div></td>
                          </tr>
<?PHP
							$counter_Cat = 0;
							if(isset($_POST['SubmitSearch'])){
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tblibcategorymst where INSTR(CatName,'$Search_Key') order by CatName ";
							}else{
								$query2 = "select * from tblibcategorymst order by CatName";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_Cat = $rstart;
							}else{
								$counter_Cat = $rstart;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tblibcategorymst where INSTR(CatName,'$Search_Key') order by CatName LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tblibcategorymst order by CatName LIMIT $rstart,$rend";
							}
							
							if(isset($_POST['SubmitSearch2'])){
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tblibcategorymst where INSTR(CatName,'$Search_Key') order by CatName ";
							}else{
								$query2 = "select * from tblibcategorymst order by CatName";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_Cat = $rstart;
							}else{
								$counter_Cat = $rstart;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch2']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tblibcategorymst where INSTR(CatName,'$Search_Key') order by CatName LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tblibcategorymst order by CatName LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_Cat = $counter_Cat+1;
									$counter = $counter+1;
									$CatID = $row["ID"];
									$CatName = $row["CatName"];
									 if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
?>
									  <tr>
										<td><div align="center">
										<input name="chkCatID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $CatID; ?>"></div></td>
										<td><div align="center"><a href="librarysetup.php?subpg=Category Master&cat_id=<?PHP echo $CatID; ?>"><?PHP echo $counter_Cat; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Category Master&cat_id=<?PHP echo $CatID; ?>"><?PHP echo $CatName; ?></a></div></td>
									  </tr>
<?PHP                          
									  }
									  else {
		?>
         <tr>
										<td><div align="center">
										<input name="chkCatID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $CatID; ?>"></div></td>
										<td><div align="center"><a href="librarysetup.php?subpg=Category Master&cat_id2=<?PHP echo $CatID; ?>"><?PHP echo $counter_Cat; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Category Master&cat_id2=<?PHP echo $CatID; ?>"><?PHP echo $CatName; ?></a></div></td>
									  </tr>
<?PHP                          
									  }
        
								 }
							 }
?>
                        </table>
                        <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                          {?>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Category Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Category Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Category Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                        <?PHP } else
						{
						?> 
                        <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Category Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Category Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Category Master&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>
                        <?PHP
						}
						?>
                        
                       </TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
                          <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                          {?>
							 <input type="hidden" name="Totalcat" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCatID" value="<?PHP echo $CategoryID; ?>">
						     <input name="Catmasterr_delete" type="submit" id="Catmasterr_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
                             <?PHP } else 
		                              {
							  ?>
                              <input type="hidden" name="Totalcat" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCatID" value="<?PHP echo $CategoryID; ?>">
						     <input name="Catmasterr_delete2" type="submit" id="Catmasterr_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
                             <?PHP 
									  }
									  unset($_SESSION['hideTopMenu']);
							  ?>		  
                              
								
						   </div>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Sub Category Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Sub Category Master">
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
					  <TD width="19%" valign="top"  align="left">Select Category  </TD>
					  <TD width="50%" valign="top"  align="left">
                      <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                          {?>
					  <select name="OptCat" onChange="javascript:setTimeout('__doPostBack(\'OptHeader\',\'\')', 0)">
                        <option value="" selected="selected">Select</option>
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
				    }else{
?>
             <select name="OptCat2" onChange="javascript:setTimeout('__doPostBack(\'OptHeader2\',\'\')', 0)">
                        <option value="" selected="selected">Select</option>
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
					}
?>
                      </select></TD>
					</TR>
					<TR>
					  <TD colspan="4" valign="top"  align="left"><p>Sub Category Name 
					    <input name="subCatName" type="text" size="65" value="<?PHP echo $subCatName; ?>"></p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
                          <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                          {?>
							 <input type="hidden" name="SelsHeadID" value="<?PHP echo $ProgSubCatID; ?>">
							 <input name="sCatmaster" type="submit" id="sCatmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="sCatmaster" type="submit" id="sCatmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                           <?PHP } else{
							   ?>
                               <input type="hidden" name="SelsHeadID" value="<?PHP echo $ProgSubCatID; ?>">
							 <input name="sCatmaster2" type="submit" id="sCatmaster" value="Create New" <?PHP echo $disabled; ?>>
						     <input name="sCatmaster2" type="submit" id="sCatmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP 
						          }
								 ?> 
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="4"><p>&nbsp;</p><hr>
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Key" type="text" size="25" value="<?PHP echo $Search_Key; ?>">
                            <label>
                            <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                          {?>
                            <input name="SubmitSearch" type="submit" id="Search" value="Go">
                            <?PHP } else{
							   ?>
                                <input name="SubmitSearch2" type="submit" id="Search" value="Go">
                                <?PHP 
						          }
								 ?> 
                            </label>
					    </p>
					    <table width="602" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="45" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="269" bgcolor="#F4F4F4"><div align="center" class="style21">Sr.No</div></td>
                            <td width="258" bgcolor="#F4F4F4"><div align="left"><strong>Sub Category Name</strong></div></td>
                          </tr>
<?PHP                       
                           if(!$_SESSION['hideTopMenu'] == 'true')
			                     {
							$counter_subcat = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tblibsubcatmst where INSTR(SubCatName,'$Search_Key') order by SubCatName ";
							}elseif($_POST['OptCat'] != ""){
								$query2 = "select * from tblibsubcatmst where CatID = '$OptCat' order by SubCatName";
							}else{
								$query2 = "select * from tblibsubcatmst order by SubCatName";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_subcat = $rstart;
							}else{
								$counter_subcat = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tblibsubcatmst where INSTR(SubCatName,'$Search_Key') order by SubCatName LIMIT $rstart,$rend";
							}elseif($_POST['OptCat'] != ""){
								$query3 = "select * from tblibsubcatmst where CatID = '$OptCat' order by SubCatName LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tblibsubcatmst order by SubCatName LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_subcat = $counter_subcat+1;
									$counter = $counter+1;
									$SubCatID = $row["ID"];
									$CatID = $row["CatID"];
									$SubCatName = $row["SubCatName"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkSubcatID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SubCatID; ?>"></div></td>
										<td><div align="center"><a href="librarysetup.php?subpg=Sub Category Master&scat_id=<?PHP echo $SubCatID; ?>&catid=<?PHP echo $CatID; ?>"><?PHP echo $counter_subcat; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Sub Category Master&scat_id=<?PHP echo $SubCatID; ?>&catid=<?PHP echo $CatID; ?>"><?PHP echo $SubCatName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
						}else{	
					$counter_subcat = 0;
							if(isset($_POST['SubmitSearch2']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query2 = "select * from tblibsubcatmst where INSTR(SubCatName,'$Search_Key') order by SubCatName ";
							}elseif($_POST['OptCat'] != ""){
								$query2 = "select * from tblibsubcatmst where CatID = '$OptCat' order by SubCatName";
							}else{
								$query2 = "select * from tblibsubcatmst order by SubCatName";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_subcat = $rstart;
							}else{
								$counter_subcat = $rstart-1;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch2']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tblibsubcatmst where INSTR(SubCatName,'$Search_Key') order by SubCatName LIMIT $rstart,$rend";
							}elseif($_POST['OptCat2'] != ""){
								$query3 = "select * from tblibsubcatmst where CatID = '$OptCat' order by SubCatName LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tblibsubcatmst order by SubCatName LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_subcat = $counter_subcat+1;
									$counter = $counter+1;
									$SubCatID = $row["ID"];
									$CatID = $row["CatID"];
									$SubCatName = $row["SubCatName"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkSubcatID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SubCatID; ?>"></div></td>
										<td><div align="center"><a href="librarysetup.php?subpg=Sub Category Master&scat_id2=<?PHP echo $SubCatID; ?>&catid2=<?PHP echo $CatID; ?>"><?PHP echo $counter_subcat; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Sub Category Master&scat_id2=<?PHP echo $SubCatID; ?>&catid2=<?PHP echo $CatID; ?>"><?PHP echo $SubCatName; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
						}
?>                    
                       
                        </table>
                        <?PHP                       
                           if(!$_SESSION['hideTopMenu'] == 'true')
			                     {?>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Sub Category Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Sub Category Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Sub Category Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                        <?PHP 
								 }else{
						   ?>
                           <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Sub Category Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Sub Category Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Sub Category Master&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>
                           <?PHP 
								 }
						      ?>
                           </TD>
					</TR>
					<TR>
						 <TD colspan="4">
                         
						  <div align="center">
                         <?PHP if(!$_SESSION['hideTopMenu'] == 'true')
			                     {?>
							 <input type="hidden" name="Totalscat" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelsCatID" value="<?PHP echo $subCatID;  ?>">
						     <input name="sCatmaster_delete" type="submit" id="sCatmasterr_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
                             <?PHP }else{
							   ?>
                               <input type="hidden" name="Totalscat" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelsCatID" value="<?PHP echo $subCatID;  ?>">
						     <input name="sCatmaster_delete2" type="submit" id="sCatmasterr_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
                             <?PHP 
							     }
							  ?>
                               	 
						   </div>
                           <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
						  </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Binding Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Binding Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>BINDING NAME</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter = 0;
								$query = "select * from tbbinding order by Binding ";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows <= 0 ) {
									echo "No Binding Found.";
								}
								else 
								{
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelBindID = $row["ID"];
										$BindingName = $row["Binding"];
										  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkbindID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelBindID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Binding Master&bind_id=<?PHP echo $SelBindID; ?>"><?PHP echo $BindingName; ?></a></div></td>
										  </tr>
<?PHP                              }else{
	?>
    <tr>
											<td>
											   <div align="center">
											     <input name="chkbindID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelBindID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Binding Master&bind_id2=<?PHP echo $SelBindID; ?>"><?PHP echo $BindingName; ?></a></div></td>
										  </tr>
                                   <?PHP }
									 }
								 }
?>
							</table>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="104">Code :</td>
								<td width="276"><input name="Code" type="text" size="5" value="<?PHP echo $BindID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Binding Name :</td>
								<td><input name="BindName" type="text" size="45" value="<?PHP echo $BindName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
                        <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
						  <div align="center">
						   	 <input type="hidden" name="TotalBind" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelBindID" value="<?PHP echo $BindID; ?>">
						     <input name="Bindmaster" type="submit" id="Bindmaster" value="Create New">
						     <input name="Bindmaster_delete" type="submit" id="Bindmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Bindmaster" type="submit" id="Bindmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
                           <?PHP }else {
							  ?>
                              <div align="center">
						   	 <input type="hidden" name="TotalBind" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelBindID" value="<?PHP echo $BindID; ?>">
						     <input name="Bindmaster2" type="submit" id="Bindmaster" value="Create New">
						     <input name="Bindmaster_delete2" type="submit" id="Bindmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Bindmaster2" type="submit" id="Bindmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
						   </div>
                           <?PHP }
						       ?>
                              
						  </TD>
					</TR>
                    <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Country Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Country Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>COUNTRY NAME</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter_Country = 0;
								$query2 = "select * from tbcountry order by Country";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Country = $rstart;
								}else{
									$counter_Country = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbcountry order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelCounID = $row["ID"];
										$CountName = $row["Country"];
									 if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkCountryID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelCounID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Country Master&country_id=<?PHP echo $SelCounID; ?>"><?PHP echo $CountName; ?></a></div></td>
										  </tr>
<?PHP                                    }else{
	?>
                                        <tr>
											<td>
											   <div align="center">
											     <input name="chkCountryID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelCounID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Country Master&country_id2=<?PHP echo $SelCounID; ?>"><?PHP echo $CountName; ?></a></div></td>
										  </tr>
                 <?PHP                  
				                         }
									 }
								 }
?>
							</table>
                             <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Country Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Country Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Country Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                 <?PHP }else{
					 ?>
                     <p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Country Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Country Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Country Master&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>
                     <?PHP
				           }
				      ?>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $CountryID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Country Name :</td>
								<td><input name="CountryName" type="text" size="45" value="<?PHP echo $CountryName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
                          <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
						   	 <input type="hidden" name="TotalCountry" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCountryID" value="<?PHP echo $CountryID; ?>">
						     <input name="Countmaster" type="submit" id="Countmaster" value="Create New">
						     <input name="Countmasterr_delete" type="submit" id="Countmasterr_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Countmaster" type="submit" id="Countmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP } else{
								 ?>
                                 <input type="hidden" name="TotalCountry" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCountryID" value="<?PHP echo $CountryID; ?>">
						     <input name="Countmaster2" type="submit" id="Countmaster" value="Create New">
						     <input name="Countmasterr_delete2" type="submit" id="Countmasterr_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Countmaster2" type="submit" id="Countmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
                             <?PHP 
							      }
						       ?>
                                 
						   </div>
						  </TD>
					</TR>
                    <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Author Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Author Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>AUTHOR NAME</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter_Country = 0;
								$query2 = "select * from tbauthormaster order by AuthorName";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Country = $rstart;
								}else{
									$counter_Country = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbauthormaster order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelAuthorID = $row["ID"];
										$AuthorName = $row["AuthorName"];
										if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkAuthorID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelAuthorID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Author Master&author_id=<?PHP echo $SelAuthorID; ?>"><?PHP echo $AuthorName; ?></a></div></td>
										  </tr>
<?PHP                        }else{
	?>                         
                                     <tr>
											<td>
											   <div align="center">
											     <input name="chkAuthorID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelAuthorID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Author Master&author_id2=<?PHP echo $SelAuthorID; ?>"><?PHP echo $AuthorName; ?></a></div></td>
										  </tr>
        <?PHP                            }
									 }
								 }
?>
							</table>
                            <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Author Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Author Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Author Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                   <?PHP 
						}else{
					?>
                       <p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Author Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Author Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Author Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                       <?PHP 
						   }
						  ?>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $AuthorID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Author Name :</td>
								<td><input name="AuthName" type="text" size="45" value="<?PHP echo $AuthName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
                          <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
						   	 <input type="hidden" name="TotalAuthor" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelAuthorID" value="<?PHP echo $AuthorID; ?>">
						     <input name="Authormaster" type="submit" id="Authormaster" value="Create New">
						     <input name="Authormaster_delete" type="submit" id="Authormaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Authormaster" type="submit" id="Authormaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP 
									  }else{
							  ?>
                              <input type="hidden" name="TotalAuthor" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelAuthorID" value="<?PHP echo $AuthorID; ?>">
						     <input name="Authormaster2" type="submit" id="Authormaster" value="Create New">
						     <input name="Authormaster_delete2" type="submit" id="Authormaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Authormaster2" type="submit" id="Authormaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP 
									  }
							   ?>
						   </div>
						  </TD>
					</TR>
                    <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Publisher Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Publisher Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>PUBLISHER NAME</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter_Country = 0;
								$query2 = "select * from tbpublisher order by PubName";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Country = $rstart;
								}else{
									$counter_Country = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbpublisher order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelPubID = $row["ID"];
										$PubName = $row["PubName"];
										if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkPubID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelPubID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Publisher Master&pub_id=<?PHP echo $SelPubID; ?>"><?PHP echo $PubName; ?></a></div></td>
										  </tr>
<?PHP                                  }else{
	?>                                       <tr>
											<td>
											   <div align="center">
											     <input name="chkPubID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelPubID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Publisher Master&pub_id2=<?PHP echo $SelPubID; ?>"><?PHP echo $PubName; ?></a></div></td>
										  </tr>
                               <?PHP   
                                         }
									 }
								 }
?>
							</table>
                              <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publisher Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publisher Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publisher Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>               
                 <?PHP }else{
				   ?>
                       <p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publisher Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publisher Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publisher Master&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>               
                       <?PHP
				            }
						?>
                   	 
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $PublisherID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Publisher Name :</td>
								<td><input name="PublisherName" type="text" size="45" value="<?PHP echo $PublisherName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
                          <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
						   	 <input type="hidden" name="TotalPub" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelPubID" value="<?PHP echo $PublisherID; ?>">
						     <input name="Pubmaster" type="submit" id="Pubmaster" value="Create New">
						     <input name="Pubmaster_delete" type="submit" id="Pubmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Pubmaster" type="submit" id="Pubmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP }else{
							  ?>
                              <input type="hidden" name="TotalPub" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelPubID" value="<?PHP echo $PublisherID; ?>">
						     <input name="Pubmaster2" type="submit" id="Pubmaster" value="Create New">
						     <input name="Pubmaster_delete2" type="submit" id="Pubmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Pubmaster2" type="submit" id="Pubmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
                             <?PHP 
							    }
								?>
                              
						   </div>
						  </TD>
					</TR>
                    <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Publication Place Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Publication Place Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>PUBLICATION PLACE</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter_Place = 0;
								$query2 = "select * from tbpublicationplace order by PubPlace";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Place = $rstart;
								}else{
									$counter_Place = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbpublicationplace order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelPubPlaceID = $row["ID"];
										$PubPlace = $row["PubPlace"];
										 if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkPubPlaceID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelPubPlaceID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Publication Place Master&pubplace_id=<?PHP echo $SelPubPlaceID; ?>"><?PHP echo $PubPlace; ?></a></div></td>
										  </tr>
<?PHP                                   }else{
	?>                                        <tr>
											<td>
											   <div align="center">
											     <input name="chkPubPlaceID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelPubPlaceID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Publication Place Master&pubplace_id2=<?PHP echo $SelPubPlaceID; ?>"><?PHP echo $PubPlace; ?></a></div></td>
										  </tr>
                               <?PHP       
                                        }
									 }
								 }
?>
							</table>
                            <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>


							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publication Place Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publication Place Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publication Place Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                       <?PHP }else{
						 ?>
                         <p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publication Place Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publication Place Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed=2<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Publication Place Master&st2=<?PHP echo $rstart+$rend; ?>&ed=2<?PHP echo $rend; ?>">Next</a> </p>
                        <?PHP 
					         }
							?> 
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $PubliPlaceID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Country Name :</td>
								<td><input name="PubPlaceName" type="text" size="45" value="<?PHP echo $PubPlaceName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
                          <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
                             <input type="hidden" name="TotalPubplace" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelPubplaceID" value="<?PHP echo $PubliPlaceID; ?>">
						     <input name="Pubplacemaster" type="submit" id="Pubplacemaster" value="Create New">
						     <input name="Pubplacemaster_delete" type="submit" id="Pubplacemaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Pubplacemaster" type="submit" id="Pubplacemaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                            <?PHP }else{
							  ?>
                              <input type="hidden" name="TotalPubplace" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelPubplaceID" value="<?PHP echo $PubliPlaceID; ?>">
						     <input name="Pubplacemaster2" type="submit" id="Pubplacemaster" value="Create New">
						     <input name="Pubplacemaster_delete2" type="submit" id="Pubplacemaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Pubplacemaster2" type="submit" id="Pubplacemaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
                             <?PHP 
							       }
								?>
                              
						   </div>
						  </TD>
					</TR>
                    <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Language") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Language">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>LANGUAGE</strong></td>
							  </tr>
							  </thead>
<?PHP
								$counter_lang = 0;
								$query2 = "select * from tblanguage order by lang";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_lang = $rstart;
								}else{
									$counter_lang = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tblanguage order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SellangID = $row["ID"];
										$lang = $row["lang"];
										if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chklangID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SellangID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Language&lang_id=<?PHP echo $SellangID; ?>"><?PHP echo $lang; ?></a></div></td>
										  </tr>
<?PHP                                  
									  }else{
							?>    
                                           <tr>
											<td>
											   <div align="center">
											     <input name="chklangID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SellangID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Language&lang_id2=<?PHP echo $SellangID; ?>"><?PHP echo $lang; ?></a></div></td>
										  </tr>
                        <?PHP        
									     }
                            
									 }
								 }
?>
							</table>
                             <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Language&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Language&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Language&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
                     <?PHP }else {
						?>
                        <p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Language&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Language&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Language&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>
                     <?PHP
					       }
					?>
                        
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $landID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Language Name :</td>
								<td><input name="landName" type="text" size="45" value="<?PHP echo $landName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
                          <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
						   	 <input type="hidden" name="Totalland" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SellandID" value="<?PHP echo $landID; ?>">
						     <input name="landmaster" type="submit" id="landmaster" value="Create New">
						     <input name="landmaster_delete" type="submit" id="landmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="landmaster" type="submit" id="landmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP 
							  }else{
							   ?>
                                <input type="hidden" name="Totalland" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SellandID" value="<?PHP echo $landID; ?>">
						     <input name="landmaster2" type="submit" id="landmaster" value="Create New">
						     <input name="landmaster_delete2" type="submit" id="landmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="landmaster2" type="submit" id="landmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
                             <?PHP 
							      }
							    ?>
							  
						   </div>
						  </TD>
					</TR>
                    <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Supplier Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Supplier Master">
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="21%" valign="top"  align="left"><div align="right">Supplier Name</div></TD>
					  <TD width="50%" valign="top"  align="left"><input name="SupName" type="text" size="55" value="<?PHP echo $SupName; ?>"></TD>
					</TR>
					<TR>
					  <TD width="21%" valign="top"  align="left"><div align="right">Address</div></TD>
					  <TD width="50%" valign="top"  align="left"><textarea name="Address" cols="55"><?PHP echo $Address; ?></textarea></TD>
					</TR>
					<TR>
					  <TD width="21%" valign="top"  align="left"><div align="right">Contact No.</div></TD>
					  <TD width="50%" valign="top"  align="left"><input name="Contact" type="text" size="55" value="<?PHP echo $Contact; ?>"></TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
                          <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
							 <input type="hidden" name="SelSupID" value="<?PHP echo $SupplierID; ?>">
							 <input name="Supmaster" type="submit" id="Supmaster" value="Create New">
						     <input name="Supmaster" type="submit" id="Supmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP
									  }else{
							   ?>
                               <input type="hidden" name="SelSupID" value="<?PHP echo $SupplierID; ?>">
							 <input name="Supmaster2" type="submit" id="Supmaster" value="Create New">
						     <input name="Supmaster2" type="submit" id="Supmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <?PHP 
									  }
							    ?>
                               
						   </div>
						  </TD>
					</TR>
					<TR>
					   <TD align="left" colspan="2"><p>&nbsp;</p><hr>
					    <p style="margin-left:150px;">Search :&nbsp;&nbsp;&nbsp;
                            <input name="Search_Supp" type="text" size="25" value="<?PHP echo $Search_Supp; ?>">
                            <label>
                            <?PHP  if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 ?>
                            <input name="SubmitSupp" type="submit" id="SubmitSupp" value="Go">
                             <?PHP
									  }else{
							   ?>
                                <input name="SubmitSupp2" type="submit" id="SubmitSupp" value="Go">
                                <?PHP 
									  }
									?>
                            
                            </label>
					    </p>
					    <table width="535" border="0" align="center" cellpadding="3" cellspacing="3">
                          <tr>
                            <td width="74" bgcolor="#F4F4F4"><div align="center" class="style21">Tick</div></td>
                            <td width="153" bgcolor="#F4F4F4"><div align="center" class="style21">Name</div></td>
                            <td width="278" bgcolor="#F4F4F4"><div align="left"><strong>Address</strong></div></td>
							<td width="278" bgcolor="#F4F4F4"><div align="left"><strong>Contact No.</strong></div></td>
                          </tr>
<?PHP 
                           if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 
							$counter_sup = 0;
							if(isset($_POST['SubmitSupp'])){
								$Search_Supp = $_POST['Search_Supp'];
								$query2 = "select * from tbsupplier where INSTR(sup_name,'$Search_Supp') order by sup_name ";
							}else{
								$query2 = "select * from tbsupplier order by sup_name";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_sup = $rstart;
							}else{
								$counter_sup = $rstart;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbsupplier where INSTR(sup_name,'$Search_Supp') order by sup_name LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tbsupplier order by sup_name LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_sup = $counter_sup+1;
									$counter = $counter+1;
									$supID = $row["ID"];
									$supName = $row["sup_name"];
									$supAdddress = $row["sup_addr"];
									$supPhone = $row["sup_phone"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkSupID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $supID; ?>"></div></td>
										<td><div align="center"><a href="librarysetup.php?subpg=Supplier Master&sup_id=<?PHP echo $supID; ?>"><?PHP echo $supName; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Supplier Master&sup_id=<?PHP echo $supID; ?>"><?PHP echo $supAdddress; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Supplier Master&sup_id=<?PHP echo $supID; ?>"><?PHP echo $supPhone; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Supplier Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Supplier Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Supplier Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalsup" value="<?PHP echo $counter; ?>">
						     <input name="Supplier_delete" type="submit" id="Supplier_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						   </div>
						  </TD>
					</TR>
                  <?PHP }else{
					 
                     $counter_sup = 0;
							if(isset($_POST['SubmitSupp2'])){
								$Search_Supp = $_POST['Search_Supp'];
								$query2 = "select * from tbsupplier where INSTR(sup_name,'$Search_Supp') order by sup_name ";
							}else{
								$query2 = "select * from tbsupplier order by sup_name";
							}
							
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							
							if($rstart==0){
								$counter_sup = $rstart;
							}else{
								$counter_sup = $rstart;
							}
							$counter = 0;
							if(isset($_POST['SubmitSearch2']))
							{
								$Search_Key = $_POST['Search_Key'];
								$query3 = "select * from tbsupplier where INSTR(sup_name,'$Search_Supp') order by sup_name LIMIT $rstart,$rend";
							}else{
								$query3 = "select * from tbsupplier order by sup_name LIMIT $rstart,$rend";
							}
							$result3 = mysql_query($query3,$conn);
							$num_rows = mysql_num_rows($result3);
							if ($num_rows > 0 ) {
								while ($row = mysql_fetch_array($result3)) 
								{
									$counter_sup = $counter_sup+1;
									$counter = $counter+1;
									$supID = $row["ID"];
									$supName = $row["sup_name"];
									$supAdddress = $row["sup_addr"];
									$supPhone = $row["sup_phone"];
?>
									  <tr>
										<td><div align="center">
										<input name="chkSupID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $supID; ?>"></div></td>
										<td><div align="center"><a href="librarysetup.php?subpg=Supplier Master&sup_id2=<?PHP echo $supID; ?>"><?PHP echo $supName; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Supplier Master&sup_id2=<?PHP echo $supID; ?>"><?PHP echo $supAdddress; ?></a></div></td>
										<td><div align="left"><a href="librarysetup.php?subpg=Supplier Master&sup_id2=<?PHP echo $supID; ?>"><?PHP echo $supPhone; ?></a></div></td>
									  </tr>
<?PHP
								 }
							 }
?>
                        </table>
					    <p style="margin-left:150px;">Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Supplier Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Supplier Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Supplier Master&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p></TD>
					</TR>
					<TR>
						 <TD colspan="4">
						  <div align="center">
							 <input type="hidden" name="Totalsup" value="<?PHP echo $counter; ?>">
						     <input name="Supplier_delete2" type="submit" id="Supplier_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
						   </div>
						  </TD>
					</TR>
                    <?PHP 
				         }
						?>
                  
				</TBODY>
                <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Book Condition") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Book Condition">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>NAME</strong></td>
							  </tr>
							  </thead>
<?PHP                            if(!$_SESSION['hideTopMenu'] == 'true')
			                      {
                                 
								$counter_BCond = 0;
								$query2 = "select * from bookcondition order by BCond";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_BCond = $rstart;
								}else{
									$counter_BCond = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from bookcondition order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelBCondID = $row["ID"];
										$BCond = $row["BCond"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkcondID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelBCondID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Book Condition&cond_id=<?PHP echo $SelBCondID; ?>"><?PHP echo $BCond; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Book Condition&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Book Condition&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Book Condition&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $ConditionID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Name :</td>
								<td><input name="CondName" type="text" size="45" value="<?PHP echo $CondName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalCond" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCondID" value="<?PHP echo $ConditionID; ?>">
						     <input name="condmaster" type="submit" id="condmaster" value="Create New">
						     <input name="condmaster_delete" type="submit" id="condmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="condmaster" type="submit" id="condmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
                    <?PHP }else{
						
                                 
								$counter_BCond = 0;
								$query2 = "select * from bookcondition order by BCond";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_BCond = $rstart;
								}else{
									$counter_BCond = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from bookcondition order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelBCondID = $row["ID"];
										$BCond = $row["BCond"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkcondID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelBCondID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Book Condition&cond_id2=<?PHP echo $SelBCondID; ?>"><?PHP echo $BCond; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Book Condition&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Book Condition&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Book Condition&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $ConditionID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Name :</td>
								<td><input name="CondName" type="text" size="45" value="<?PHP echo $CondName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalCond" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCondID" value="<?PHP echo $ConditionID; ?>">
						     <input name="condmaster2" type="submit" id="condmaster" value="Create New">
						     <input name="condmaster_delete2" type="submit" id="condmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="condmaster2" type="submit" id="condmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
						   </div>
						  </TD>
					</TR>
                    <?PHP
						  }
						?>
					
				</TBODY>
                <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Series Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Series Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>SERIES NAME</strong></td>
							  </tr>
							  </thead>
<?PHP                            
                                if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 
								$counter_Series= 0;
								$query2 = "select * from tbseries order by Series";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Series = $rstart;
								}else{
									$counter_Series = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbseries order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelSeriesID = $row["ID"];
										$bSeries = $row["Series"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkseriesID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelSeriesID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Series Master&series_id=<?PHP echo $SelSeriesID; ?>"><?PHP echo $bSeries; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Series Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Series Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Series Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $SeriesID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Name :</td>
								<td><input name="SeriesName" type="text" size="45" value="<?PHP echo $SeriesName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalSeries" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelSeriesID" value="<?PHP echo $SeriesID; ?>">
						     <input name="seriesmaster" type="submit" id="seriesmaster" value="Create New">
						     <input name="seriesmaster_delete" type="submit" id="seriesmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="seriesmaster" type="submit" id="seriesmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
                    <?PHP } else{
						$counter_Series= 0;
								$query2 = "select * from tbseries order by Series";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Series = $rstart;
								}else{
									$counter_Series = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbseries order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelSeriesID = $row["ID"];
										$bSeries = $row["Series"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkseriesID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelSeriesID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Series Master&series_id2=<?PHP echo $SelSeriesID; ?>"><?PHP echo $bSeries; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Series Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Series Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Series Master&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $SeriesID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Name :</td>
								<td><input name="SeriesName" type="text" size="45" value="<?PHP echo $SeriesName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalSeries" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelSeriesID" value="<?PHP echo $SeriesID; ?>">
						     <input name="seriesmaster2" type="submit" id="seriesmaster" value="Create New">
						     <input name="seriesmaster_delete2" type="submit" id="seriesmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="seriesmaster2" type="submit" id="seriesmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
						   </div>
						  </TD>
					</TR>
                    <?PHP
					   }
					  ?>
						
				</TBODY>
                <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Currency Master") {
?>
				<?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="librarysetup.php?subpg=Currency Master">
				<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="39%" align="left" valign="top">
							<table width="231" border="0" align="center" cellpadding="3" cellspacing="3">
							  <thead>
							  <tr bgcolor="#ECE9D8">
								<td width="28"><strong>TICK</strong></td>
								<td width="182"><strong>CURRENCY NAME</strong></td>
							  </tr>
							  </thead>
<?PHP                        
                                if(!$_SESSION['hideTopMenu'] == 'true')
			                          {
                                 
								$counter_Series= 0;
								$query2 = "select * from tbcurrency order by Curr";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Series = $rstart;
								}else{
									$counter_Series = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbcurrency order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelCurrID = $row["ID"];
										$bCurr = $row["Curr"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkCurrID<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelCurrID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Currency Master&Curr_id=<?PHP echo $SelCurrID; ?>"><?PHP echo $bCurr; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Currency Master&st=0&ed=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Currency Master&st=<?PHP echo checkprevious($rstart-$rend); ?>&ed=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Currency Master&st=<?PHP echo $rstart+$rend; ?>&ed=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $CurrencyID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Name :</td>
								<td><input name="CurrName" type="text" size="45" value="<?PHP echo $CurrName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalCurr" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCurrID" value="<?PHP echo $CurrencyID; ?>">
						     <input name="Currmaster" type="submit" id="Currmaster" value="Create New">
						     <input name="Currmaster_delete" type="submit" id="Currmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Currmaster" type="submit" id="Currmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
						   </div>
						  </TD>
					</TR>
                    <?PHP } else{
						$counter_Series= 0;
								$query2 = "select * from tbcurrency order by Curr";
								$result2 = mysql_query($query2,$conn);
								$num_rows2 = mysql_num_rows($result2);
								
								if($rstart==0){
									$counter_Series = $rstart;
								}else{
									$counter_Series = $rstart-1;
								}
							
							
								$counter = 0;
								$query = "select * from tbcurrency order by ID Desc LIMIT $rstart,$rend";
								$result = mysql_query($query,$conn);
								$num_rows = mysql_num_rows($result);
								if ($num_rows > 0 ) {
									while ($row = mysql_fetch_array($result)) 
									{
										$counter = $counter+1;
										$SelCurrID = $row["ID"];
										$bCurr = $row["Curr"];
?>
										  <tr>
											<td>
											   <div align="center">
											     <input name="chkCurrID2<?PHP echo $counter; ?>" type="checkbox" value="<?PHP echo $SelCurrID; ?>">
									           </div></td>
											<td><div align="left"><a href="librarysetup.php?subpg=Currency Master&Curr_id2=<?PHP echo $SelCurrID; ?>"><?PHP echo $bCurr; ?></a></div></td>
										  </tr>
<?PHP
									 }
								 }
?>
							</table>
							<p>Records <?PHP echo $rstart+1; ?>-<?PHP echo $rend+$rstart; ?>&nbsp;&nbsp;of <?PHP echo $num_rows2; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Currency Master&st2=0&ed2=<?PHP echo $rend; ?>">First&nbsp;&nbsp;&nbsp;</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Currency Master&st2=<?PHP echo checkprevious($rstart-$rend); ?>&ed2=<?PHP echo $rend; ?>">Previous</a> | &nbsp;&nbsp;&nbsp;<a href="librarysetup.php?subpg=Currency Master&st2=<?PHP echo $rstart+$rend; ?>&ed2=<?PHP echo $rend; ?>">Next</a> </p>
					  </TD>
					  <TD width="61%" valign="top"  align="left">
					  		<table width="401" border="0" align="center" cellpadding="3" cellspacing="3">
							  <tr>
								<td width="106">Code :</td>
								<td width="274"><input name="Code" type="text" size="5" value="<?PHP echo $CurrencyID; ?>" disabled="disabled"></td>
							  </tr>
							  <tr>
								<td>Name :</td>
								<td><input name="CurrName" type="text" size="45" value="<?PHP echo $CurrName; ?>"></td>
							  </tr>
							</table>
					  </TD>
					</TR>
					<TR>
						 <TD colspan="2">
						  <div align="center">
						   	 <input type="hidden" name="TotalCurr" value="<?PHP echo $counter; ?>">
							 <input type="hidden" name="SelCurrID" value="<?PHP echo $CurrencyID; ?>">
						     <input name="Currmaster2" type="submit" id="Currmaster" value="Create New">
						     <input name="Currmaster_delete2" type="submit" id="Currmaster_delete" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						     <input name="Currmaster2" type="submit" id="Currmaster" value="Update">
						     <input type="reset" name="Reset" value="Reset">
                             <input name="BackToBookMaster" type="submit" id="BackToBookMaster" value=" Back To Book Master">
						   </div>
						  </TD>
					</TR>
                    <?PHP 
					   }
					   ?>
					 
				</TBODY>
                <?PHP 
						   unset($_SESSION['hideTopMenu']);
						   ?>
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
