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
	session_start();
	global $userNames;
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
	
	$Page = "Time Table";
	if($_SESSION['module'] == "Teacher"){
		$Login = "Log in Teacher: ".$_SESSION['username']; 
		$bg="#420434";
		$usrnam = $_SESSION['username'];
		$query = "select EmpID from tbusermaster where UserName='$usrnam'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Teacher_EmpID  = $dbarray['EmpID'];
		$audit=update_Monitory('Login','Teacher',$Page);
	}else{
		$Login = "Log in Administrator: ".$_SESSION['username']; 
		$bg="maroon";
		$audit=update_Monitory('Login','Administrators',$Page);
	}
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	
	$PageHasError = 0;
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 10;
	}
	if(isset($_POST['SubmitSubj']))
	{
		$PageHasError = 0;
		$OptSubj = $_POST['OptSubj'];
		$SubjectName = $_POST['SubjectName'];
		$ShortName = $_POST['ShortName'];
		$SubjectType = $_POST['SubjectType'];
		$TTPriority = $_POST['TTPriority'];
		
		if(!$_POST['SubjectName']){
			$errormsg = "<font color = red size = 1>Subject name is empty.</font>";
			$PageHasError = 1;
		}
		
		if ($PageHasError == 0)
		{
			if ($_POST['SubmitSubj'] =="Create New"){
				$num_rows = 0;
				$query = "select ID from tbsubjectmaster where Subj_name = '$SubjectName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($query,$conn);
				$num_rows = mysql_num_rows($result);
				if ($num_rows > 0 ) 
				{
					$errormsg = "<font color = red size = 1>The subject you are trying to add already exist.</font>";
					$PageHasError = 1;
				}else {
					$q = "Insert into tbsubjectmaster(Subj_name,ShortName,Sub_Type,Subj_Priority,Session_ID,Term_ID) Values ('$SubjectName','$ShortName','$SubjectType','$TTPriority','$Session_ID','$Term_ID')";
					$result = mysql_query($q,$conn);
					$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
					
					$SubjectName = "";
					$ShortName = "";
					$SubjectType = "";
					$Priority = "";
				}
			}elseif ($_POST['SubmitSubj'] =="Update"){
				$q = "update tbsubjectmaster set Subj_name='$SubjectName',ShortName='$ShortName',Sub_Type='$SubjectType',Subj_Priority='$TTPriority' where ID = '$OptSubj' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Updated Successfully.</font>";
				
				$SubjectName = "";
				$ShortName = "";
				$SubjectType = "";
				$TTPriority = "";
			}elseif ($_POST['SubmitSubj'] =="Delete"){
				$q = "Delete From tbsubjectmaster where ID = '$OptSubj' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
				
				$SubjectName = "";
				$ShortName = "";
				$SubjectType = "";
				$TTPriority = "";
			}
		}
	}
	if(isset($_POST['OptSubj']))
	{	
		$OptSubj = $_POST['OptSubj'];
		$query = "select * from tbsubjectmaster where ID='$OptSubj' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$SubjectName  = $dbarray['Subj_name'];
		$ShortName  = $dbarray['ShortName'];
		$SubjectType  = $dbarray['Sub_Type'];
		$TTPriority  = $dbarray['Subj_Priority'];
	}
	//Assign Compulsory Subject
	if(isset($_POST['AddCompulsory']))
	{	
		$SelSubjID = "";
		$OptClass = $_POST['OptClass'];
		$SelSubjID = $_POST['UnCompSubj'];
		if(!$_POST['UnCompSubj']){
			$errormsg = "<font color = red size = 1>Select Compulsory Subject to assign.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$q = "Insert into tbclasssubjectrelation(ClassId,SubjectId,Session_ID,Term_ID) Values ('$OptClass','$SelSubjID','$Session_ID','$Term_ID')";
			$result = mysql_query($q,$conn);
			$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
		}
	}
	//Assign Optional Subject
	if(isset($_POST['AddOptional']))
	{	
		$SelSubjID = "";
		$OptClass = $_POST['OptClass'];
		$SelSubjID = $_POST['UnOptSubj'];
		if(!$_POST['UnOptSubj']){
			$errormsg = "<font color = red size = 1>Select Optional Subject to assign.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$q = "Insert into tbclasssubjectrelation(ClassId,SubjectId,Session_ID,Term_ID) Values ('$OptClass','$SelSubjID','$Session_ID','$Term_ID')";
			$result = mysql_query($q,$conn);
			$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
		}
	}
	
	
	
	//UnAssign Compulsory Subject
	if(isset($_POST['RemCompulsory']))
	{	
		$SelSubjID = "";
		$OptClass = $_POST['OptClass'];
		$SelSubjID = $_POST['AsCompSubj'];
		if(!$_POST['AsCompSubj']){
			$errormsg = "<font color = red size = 1>Select Compulsory Subject to delete.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$q = "Delete From tbclasssubjectrelation where ClassId = '$OptClass' and SubjectId = '$SelSubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($q,$conn);
			$errormsg = "<font color = blue size = 1>Deleted Successfully.</font>";
		}
	}
	//UnAssign Optional Subject
	if(isset($_POST['RemOptional']))
	{	
		$SelSubjID = "";
		$OptClass = $_POST['OptClass'];
		$SelSubjID = $_POST['AsOptSubj'];
		if(!$_POST['AsOptSubj']){
			$errormsg = "<font color = red size = 1>Select Optional Subject to delete.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$q = "Delete From tbclasssubjectrelation where ClassId = '$OptClass' and SubjectId = '$SelSubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($q,$conn);
			$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
		}
	}
	//Assign All Compulsory Subject
	if(isset($_POST['AddAllCompulsory']))
	{
		$OptClass = $_POST['OptClass'];
		$SubjID = "";
		$query3 = "select ID from tbsubjectmaster where Sub_Type = '0' and ID NOT IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$counter = $counter+1;
				$SubjID = $row["ID"];
				$q = "Insert into tbclasssubjectrelation(ClassId,SubjectId,Session_ID,Term_ID) Values ('$OptClass','$SubjID','$Session_ID','$Term_ID')";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
			}
		}
	}
	//Assign All Optional Subject
	if(isset($_POST['AddAllOptional']))
	{
		$OptClass = $_POST['OptClass'];
		$SubjID = "";
		$query3 = "select ID from tbsubjectmaster where Sub_Type = '1' and ID NOT IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$counter = $counter+1;
				$SubjID = $row["ID"];
				$q = "Insert into tbclasssubjectrelation(ClassId,SubjectId,Session_ID,Term_ID) Values ('$OptClass','$SubjID','$Session_ID','$Term_ID')";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Added Successfully.</font>";
			}
		}
	}
	
	//UnAssign All Compulsory Subject
	if(isset($_POST['RemAllCompulsory']))
	{
		$OptClass = $_POST['OptClass'];
		$SubjID = "";
		$query3 = "select ID from tbsubjectmaster where Sub_Type = '0' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$counter = $counter+1;
				$SubjID = $row["ID"];
				$q = "Delete From tbclasssubjectrelation where ClassId = '$OptClass' and SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Delete Successfully.</font>";
			}
		}
	}
	//Assign All Optional Subject
	if(isset($_POST['RemAllOptional']))
	{
		$OptClass = $_POST['OptClass'];
		$SubjID = "";
		$query3 = "select ID from tbsubjectmaster where Sub_Type = '1' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$counter = $counter+1;
				$SubjID = $row["ID"];
				$q = "Delete From tbclasssubjectrelation where ClassId = '$OptClass' and SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result = mysql_query($q,$conn);
				$errormsg = "<font color = blue size = 1>Delete Successfully.</font>";
			}
		}
	}
	
	if(isset($_POST['OptClass']))
	{	
		$OptClass = $_POST['OptClass'];
		//Compulsory Subject
		$AS=0;
		$UN=0;
		$query3 = "select ID from tbsubjectmaster where Sub_Type = '0' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$counter = $counter+1;
				$SubjID = $row["ID"];
				//Check if subject has been assigned to class
				$numrows = 0;
				$query4   = "SELECT COUNT(*) AS numrows FROM tbclasssubjectrelation Where ClassId = '$OptClass' and SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result4  = mysql_query($query4,$conn) or die('Error, query failed');
				$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
				$numrows = $row4['numrows'];
				if($numrows > 0){
					$arr_Comp_Assign[$AS] = $SubjID;
					$AS=$AS+1;
				}else{
					$arr_Comp_UnAssign[$UN] = $SubjID;
					$UN=$UN+1;
				}
			}
		}
		//Optional Subject
		$AS=0;
		$UN=0;
		$query3 = "select ID from tbsubjectmaster where Sub_Type = '1' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$counter = $counter+1;
				$SubjID = $row["ID"];
				//Check if subject has been assigned to class
				$numrows = 0;
				$query4   = "SELECT COUNT(*) AS numrows FROM tbclasssubjectrelation Where ClassId = '$OptClass' and SubjectId = '$SubjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result4  = mysql_query($query4,$conn) or die('Error, query failed');
				$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
				$numrows = $row4['numrows'];
				if($numrows>0){
					$arr_Opt_Assign[$AS] = $SubjID;
					$AS=$AS+1;
				}else{
					$arr_Opt_UnAssign[$UN] = $SubjID;
					$UN=$UN+1;
				}
			}
		}
	}
	if(isset($_POST['OptEmp']))
	{	
		$OptEmp = $_POST['OptEmp'];
		$n = 0;
		$sub = 0;
		$opt = 0;
		$TotStudent = 0;
		$TotBoys = 0;
		$TotGirls = 0;
		$query3 = "select Distinct ClassID,SubjectID from tbclassteachersubj where EmpId = '$OptEmp' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
		$result3 = mysql_query($query3,$conn);
		$num_rows = mysql_num_rows($result3);
		if ($num_rows > 0 ) {
			while ($row = mysql_fetch_array($result3)) 
			{
				$found=0;
				$ClassID = $row["ClassID"];
				$SubjectID = $row["SubjectID"];
				$arr_Class_Check[$n] = $ClassID;
				$arr_Subj_Check[$n] = $SubjectID;
				if($n > 0){
					for($y=0;$y<$n;$y++){//2
						if($arr_Class_Check[$y] == $ClassID){
							$found=1;
						}
					}
					if($found == 0){
						$TotStudent = $TotStudent + GET_TOTAL_STUDENT($ClassID);
						$TotBoys = $TotBoys + GET_TOTAL_BOY($ClassID);
						$TotGirls = $TotGirls + GET_TOTAL_GIRL($ClassID);
					}
				}else{
					$TotStudent = $TotStudent + GET_TOTAL_STUDENT($ClassID);
					$TotBoys = $TotBoys + GET_TOTAL_BOY($ClassID);
					$TotGirls = $TotGirls + GET_TOTAL_GIRL($ClassID);
				}
				$query4 = "select ID from tbsubjectmaster where Sub_Type = '0' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result4 = mysql_query($query4,$conn);
				$num_rows4 = mysql_num_rows($result4);
				if ($num_rows4 > 0 ) {
					while ($row4 = mysql_fetch_array($result4)) 
					{
						$found=0;
						$SubjID = $row4["ID"];
						//echo $SubjID." ,";
						if($sub > 0){
							for($y=0;$y<$sub;$y++){//2
								if($arr_Comp_Subj[$y] == $SubjID){
									$found=1;
								}
							}
							if($found == 0){
								$arr_Comp_Subj[$sub] = $SubjID;
								$sub=$sub+1;
							}
						}else{
							$arr_Comp_Subj[$sub] = $SubjID;
							$sub=$sub+1;
						}
						
					}
				}
				$query5 = "select ID from tbsubjectmaster where Sub_Type = '1' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$ClassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result5 = mysql_query($query5,$conn);
				$num_rows5 = mysql_num_rows($result5);
				if ($num_rows5 > 0 ) {
					while ($row5 = mysql_fetch_array($result5)) 
					{
						$found=0;
						$SubjID = $row5["ID"];
						//echo $SubjID." ,";
						if($opt > 0){
							for($y=0;$y<$opt;$y++){//2
								if($arr_Opt_Subj[$y] == $SubjID){
									$found=1;
								}
							}
							if($found == 0){
								$arr_Opt_Subj[$opt] = $SubjID;
								$opt=$opt+1;
							}
						}else{
							$arr_Opt_Subj[$opt] = $SubjID;
							$opt=$opt+1;
						}
						
					}
				}
				//$arr_Class_Assign[$i] = $ClassID;
				$n=$n+1;
				//echo $ClassID." , ";
			}
		}
	}
	if(isset($_POST['Submitview']))
	{
		$OptEmp = $_POST['OptEmp'];
		$Total = $_POST['Totalclass'];
		$n = 0;
		$sub = 0;
		$opt = 0;
		$TotStudent = 0;
		$TotBoys = 0;
		$TotGirls = 0;
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chkclassID'.$i]))
			{
				$chkclassID = $_POST['chkclassID'.$i];
				$arr_Class_Check[$n] = $chkclassID;
				$TotStudent = $TotStudent + GET_TOTAL_STUDENT($chkclassID);
				$TotBoys = $TotBoys + GET_TOTAL_BOY($chkclassID);
				$TotGirls = $TotGirls + GET_TOTAL_GIRL($chkclassID);
				$query3 = "select ID from tbsubjectmaster where Sub_Type = '0' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$chkclassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$found=0;
						$SubjID = $row["ID"];
						if($sub > 0){
							for($y=0;$y<$sub;$y++){//2
								if($arr_Comp_Subj[$y] == $SubjID){
									$found=1;
								}
							}
							if($found == 0){
								$arr_Comp_Subj[$sub] = $SubjID;
								$sub=$sub+1;
							}
						}else{
							$arr_Comp_Subj[$sub] = $SubjID;
							$sub=$sub+1;
						}
						
					}
				}
				$query3 = "select ID from tbsubjectmaster where Sub_Type = '1' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$chkclassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
				$result3 = mysql_query($query3,$conn);
				$num_rows = mysql_num_rows($result3);
				if ($num_rows > 0 ) {
					while ($row = mysql_fetch_array($result3)) 
					{
						$found=0;
						$SubjID = $row["ID"];
						if($opt > 0){
							for($y=0;$y<$opt;$y++){//2
								if($arr_Opt_Subj[$y] == $SubjID){
									$found=1;
								}
							}
							if($found == 0){
								$arr_Opt_Subj[$opt] = $SubjID;
								$opt=$opt+1;
							}
						}else{
							$arr_Opt_Subj[$opt] = $SubjID;
							$opt=$opt+1;
						}
						
					}
				}
				$n = $n +1;
			}
		}
	}
	if(isset($_POST['UpdateSubj']))
	{
		//$Activeterm
		$OptEmp = $_POST['OptEmp'];
		$Total = $_POST['Totalclass'];
		$TotalCompSubj = $_POST['TotalCompSubj'];
		$TotalOptSubj = $_POST['TotalOptSubj'];
		if(!$_POST['OptEmp']){
			$errormsg = "<font color = red size = 1>Select Teacher to update.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			$q = "Delete From tbclassteachersubj where EmpId = '$OptEmp' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
			$result = mysql_query($q,$conn);
			$arr_Class_Check = "";
			$arr_Subj_Check = "";
			$arr_Opt_Subj = "";
			$arr_Comp_Subj = "";
			for($i=1;$i<=$Total;$i++){
				if(isset( $_POST['chkclassID'.$i]))
				{
					$chkclassID = $_POST['chkclassID'.$i];
					$query3 = "select ID from tbsubjectmaster where Sub_Type = '0' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$chkclassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$found=0;
							$SubjID = $row["ID"];
							//echo $SubjID."  ,  ";
							for($n=1;$n<=$TotalCompSubj;$n++){
								if(isset( $_POST['chkCompSubj'.$n]))
								{
									//Selected compulsory subject
									$chkCompSubj = $_POST['chkCompSubj'.$n];
									if($SubjID == $chkCompSubj){
										//Insert
										$q = "Insert into tbclassteachersubj(EmpId,ClassID,SecID,SubjectID,Session_ID,Term_ID) Values ('$OptEmp','$chkclassID','$Activeterm','$chkCompSubj','$Session_ID','$Term_ID')";
										$result = mysql_query($q,$conn);
									}
								}
							}
						}
					}
					$query3 = "select ID from tbsubjectmaster where Sub_Type = '1' and ID IN(Select SubjectId from tbclasssubjectrelation where ClassId = '$chkclassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
					$result3 = mysql_query($query3,$conn);
					$num_rows = mysql_num_rows($result3);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result3)) 
						{
							$found=0;
							$SubjID = $row["ID"];
							//echo $SubjID."  ,  ";
							for($n=1;$n<=$TotalOptSubj;$n++){
								if(isset( $_POST['chkOptSubj'.$n]))
								{
									//Selected compulsory subject
									$chkOptSubj = $_POST['chkOptSubj'.$n];
									if($SubjID == $chkOptSubj){
										//Insert
										$q = "Insert into tbclassteachersubj(EmpId,ClassID,SecID,SubjectID,Session_ID,Term_ID) Values ('$OptEmp','$chkclassID','$Activeterm','$chkOptSubj','$Session_ID','$Term_ID')";
										$result = mysql_query($q,$conn);
									}
								}
							}
						}
					}
					$errormsg = "<font color = blue size = 1>Record updated successfully.</font>";
				}
			}
		}
	}
	if(isset($_POST['SubmitPrint']))
	{
		$OptEmp = $_POST['OptEmp'];
		if(!$_POST['OptEmp']){
			$errormsg = "<font color = red size = 1>Select Teacher.</font>";
			$PageHasError = 1;
		}
		if ($PageHasError == 0)
		{
			echo "<meta http-equiv=\"Refresh\" content=\"0;url=subject_rpt.php?pg=View Teacher Subject&eid=$OptEmp\">";
			exit;
		}
	}
	$stmt = $pdo->prepare("select ID,EmpName from tbemployeemasters where isTeaching = '1' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by EmpName");
                         // do something
                         $stmt->execute();
                        $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$varteacher = json_encode($row2);

$stmt = $pdo->prepare("SELECT * FROM tbclassmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varclass = json_encode($json_row);

$stmt = $pdo->prepare("SELECT * FROM tbsubjectmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID'");
// do something
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo $row;
$json_row = array ('items'=>$row);             
$varsubject = json_encode($json_row);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager</TITLE>
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
width:145px;
}

.b{
overflow:auto;
width:auto;
height:320px;
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
<SCRIPT 
src="js/json/json2.js" 
type=text/JavaScript></SCRIPT>

<script language="JavaScript">
<!--
<!--
	//function openWin( windowURL, windowName, windowFeatures ) {
	//	return window.open( windowURL, windowName, windowFeatures ) ;
	//}
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
<!-- SECTION 1 -->
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
</script>
<script type="text/javascript">
     dojo.addOnLoad(function() {
		                     document.getElementById('divLoading').style.display = 'none';
							 display();
							 
		                    //document.getElementById('divLoading').style.display = 'block';
							 var cityJson = <?php echo $varclass; ?>;
							 formDlg = dijit.byId("formDialog");
							   		
                                      new dijit.form.ComboBox({
                                       store: new dojo.data.ItemFileReadStore({
                                    data: cityJson
                                               }),
                                           //autoComplete: true,
			                          searchAttr: "Class_Name",
                                           //query: {
                                               // state: "*"
                                                        //},
                                      style: "width: 150px;",
                                                 // required: true,
                                                id: "selectclass",
                                 onChange: function(city) {
                                  dijit.byId('state').attr('value', (dijit.byId('city').item || {
                                  state: ''
                                        }).state);
                                                }
                                                 },
                                    "selectclass");				
                         
							   		  var subjectJson = <?php echo $varsubject; ?>;
                                      new dijit.form.ComboBox({
                                       store: new dojo.data.ItemFileReadStore({
                                    data: subjectJson
                                               }),
                                           //autoComplete: true,
			                          searchAttr: "Subj_name",
                                           //query: {
                                               // state: "*"
                                                        //},
                                      style: "width: 150px;",
                                                 // required: true,
                                                id: "selectsubject",
                                 onChange: function(city) {
                                  dijit.byId('state').attr('value', (dijit.byId('city').item || {
                                  state: ''
                                        }).state);
                                                }
                                                 },
                                    "selectsubject");		      
                                     
                                      });
					
</script>					
</HEAD>
<BODY class="tundra" style="TEXT-ALIGN: center" background=Images/news-background.jpg ><div id="divLoading">   </div>
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
			  <TD width="220" style="background:url(images/side-menu.gif) repeat-x;" valign="top" align="left">
			  		<p style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps;margin-left:35px;"><?PHP echo $Page; ?> </p>
					<?PHP include 'sidemenu.php'; ?>
			  </TD>
			  <TD width="858" align="left" valign="top">
			  	<TABLE style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 22pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: 'MV Boli'; FONT-VARIANT: normal" 
					  align=middle></TD></TR>
					<TR>
					  <TD height="20" 
					  align="center" 
					  style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps"><?PHP echo $SubPage; ?></TD>
					</TR>
				    </TBODY>
				</TABLE>
				<BR>
<?PHP
		if ($SubPage == "Subject Master") {
?>
				<p>&nbsp;</p><p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="subject.php?subpg=Subject Master">
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
					  <TD width="34%" valign="top"  align="left">
							<select name="Selectddd" size="1" multiple="multiple" style="width:250px;">
						      <option>
							  SrNo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subject Name</option>
						      </select>
							 <select name="OptSubj" size="20" multiple style="width:250px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptSubj\',\'\')', 0)">
<?PHP
								$counter = 0;
								if(isset($_POST['SubmitSearch']))
								{
									$searchsubj = $_POST['searchsubj'];
									$query = "select ID,Subj_name from tbsubjectmaster where INSTR(Subj_name,'$searchsubj') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_name";
								}else{
									$query = "select ID,Subj_name from tbsubjectmaster where Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Subj_name";
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
										$subjID = $row["ID"];
										$Subjname = $row["Subj_name"];
										if($counter <= 9){
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}else{
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										
										if($OptSubj =="$subjID"){
?>
											<option value="<?PHP echo $subjID; ?>" selected="selected"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Subjname; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $subjID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Subjname; ?></option>
<?PHP
										}
									}
								}
?>
						      <option> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						      </select>
						  <p align="right">Search Subject 
						    <input name="searchsubj" type="text" size="20" value="<?PHP echo $searchsubj; ?>">
						    <input name="SubmitSearch" type="submit" id="Search" value="Go">
						  </p></TD>
					  <TD width="66%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					    <TABLE width="92%" align="center" cellpadding="7">
							<TBODY>
							<TR>
							  <TD width="31%"  align="left"><strong>Subject Information </strong></TD>
							  <TD width="69%"  align="left" valign="top">&nbsp;</TD>
							</TR>
							<TR>
							  <TD width="31%"  align="left">Subject Name  :</TD>
							  <TD width="69%"  align="left" valign="top">
									<input name="SubjectName" type="text" size="35" value="<?PHP echo $SubjectName; ?>">							   </TD>
							</TR>
							<TR>
							  <TD width="31%"  align="left">Short Name </TD>
							  <TD width="69%"  align="left" valign="top"><input name="ShortName" type="text" value="<?PHP echo $ShortName; ?>" size="15" maxlength="5"> 
							  ...<em>No space is allowed </em></TD>
							</TR>
							<TR>		
							  <TD width="31%"  align="left">Subject Type </TD>
							  <TD width="69%"  align="left" valign="top"><label>
<?PHP
								if($SubjectType =="0"){
?>
					 				<input type="radio" name="SubjectType" value="0" checked="checked"/>Compulsory &nbsp; &nbsp; &nbsp; &nbsp;
<?PHP
								}else{
?>
									<input type="radio" name="SubjectType" value="0"/>Compulsory &nbsp; &nbsp; &nbsp; &nbsp;
<?PHP
								}
								if($SubjectType =="1"){
?>
					 				<input type="radio" name="SubjectType" value="1" checked="checked"/>Optional&nbsp; &nbsp; &nbsp; &nbsp;
<?PHP
								}else{
?>
									<input type="radio" name="SubjectType" value="1"/>Optional&nbsp; &nbsp; &nbsp; &nbsp;
<?PHP
								}
?>
							  </label></TD>
							</TR>
							<TR>
							  <TD width="31%"  align="left"> Time Table Priority </TD>
							  <TD width="69%"  align="left" valign="top"><label>
<?PHP
								if($TTPriority =="A"){
?>
					 				<input type="radio" name="TTPriority" value="A" checked="checked"/>High&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<?PHP
								}else{
?>
									<input type="radio" name="TTPriority" value="A"/>High&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<?PHP
								}
								if($TTPriority =="B"){
?>
					 				<input type="radio" name="TTPriority" value="B" checked="checked"/>Normal &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
<?PHP
								}else{
?>
									<input type="radio" name="TTPriority" value="B"/>Normal &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
<?PHP
								}
								if($TTPriority =="C"){
?>
					 				<input type="radio" name="TTPriority" value="C" checked="checked"/>Low &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<?PHP
								}else{
?>
									<input type="radio" name="TTPriority" value="C"/>Low &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<?PHP
								}
?>
							  </label></TD>
							</TR>
						</TBODY>
						</TABLE>
						<p>&nbsp;</p>
						<div align="center">
						  <input type="submit" name="SubmitSubj" value="Create New">
						  <input type="submit" name="SubmitSubj" value="Update">
						  <input type="submit" name="SubmitSubj" value="Delete" onClick="if (!confirm('Are you sure you want to delete this record, click ok to proceed, and cancel to exit!')) {return false;}">
						  <input type="reset" name="reset" value="Reset">
						  </div></TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">					  
						<p style="margin-left:150px;">&nbsp;</p>
					    </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Class Subject") {
?>
				<p>&nbsp;</p><p>&nbsp;</p><?PHP echo $errormsg; ?>
				<form name="form1" method="post" action="subject.php?subpg=Class Subject">
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
					  <TD colspan="3" valign="top"  align="left"><strong>Select Class </strong>					    <select name="OptClass" onChange="javascript:setTimeout('__doPostBack(\'OptClass\',\'\')', 0)">
								<option value="" selected="selected">Select</option>
<?PHP
								$counter = 0;
								if($_SESSION['module'] == "Teacher"){
									$query = "select ID,Class_Name from tbclassmaster where ID IN (select ClassID from tbclassteachersubj where EmpId = '$Teacher_EmpID' And Session_ID = '$Session_ID' and Term_ID = '$Term_ID') and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by Class_Name";
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
										if($counter <= 9){
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}else{
											$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
										}
										
										if($OptClass =="$ClassID"){
?>
											<option value="<?PHP echo $ClassID; ?>" selected="selected"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Classname; ?></option>
<?PHP
										}else{
?>
											<option value="<?PHP echo $ClassID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Classname; ?></option>
<?PHP
										}
									}
								}
?>
						      <option> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						      </select>	
						</TD>
					</TR>
					<TR>
					  <TD width="39%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  Compulsory Subject [Unassign]
					  <select name="UnCompSubj" size="10" multiple style="width:270px; background:#66FFFF;">
<?PHP
						$i = 0;
						$counter = 0;
						while(isset($arr_Comp_UnAssign[$i])){
							$counter = $counter+1;
							$subjID = "";
							$subjID = $arr_Comp_UnAssign[$i];
							
							$query = "select Subj_name from tbsubjectmaster where ID='$subjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Subjname  = $dbarray['Subj_name'];
							
							if($counter <= 9){
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}else{
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
?>
							<option value="<?PHP echo $subjID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Subjname; ?></option>
<?PHP
							$i=$i+1;
						}
?>
					  </select>
					</TD>
				 	 <TD width="22%" valign="top"  align="left">
						<p align="center"><input type="submit" name="AddCompulsory" value="     &gt;   " title="Assign Compulsory Subject"></p>
						<p align="center"><input type="submit" name="RemCompulsory" value="     &lt;   " title="UnAssign Compulsory Subject"></p>
						<p align="center"><input type="submit" name="AddAllCompulsory" value="   &gt;&gt;   " title="Assign All Compulsory Subject"></p>
						<p align="center"><input type="submit" name="RemAllCompulsory" value="   &lt;&lt;   " title="UnAssign All Compulsory Subject"></p>
				  	</TD>
					<TD width="39%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					Compulsory Subject [Assigned]
					  <select name="AsCompSubj" size="10" multiple style="width:270px; background:#66FFFF;">
<?PHP
						$i = 0;
						$counter = 0;
						while(isset($arr_Comp_Assign[$i])){
							$counter = $counter+1;
							$subjID = "";
							$subjID = $arr_Comp_Assign[$i];
							
							$query = "select Subj_name from tbsubjectmaster where ID='$subjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Subjname  = $dbarray['Subj_name'];
							
							if($counter <= 9){
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}else{
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
?>
							<option value="<?PHP echo $subjID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Subjname; ?></option>
<?PHP
							$i=$i+1;
						}
?>
                      </select>
						</TD>
					</TR>
					<TR>
					 	<TD colspan="3" valign="top"  align="left" >&nbsp;<hr></TD>
					</TR>
					<TR>
					  <TD width="39%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					  Optional Subject [Unassign]
					  <select name="UnOptSubj" size="10" multiple style="width:270px; background:#66FFFF;">
<?PHP
						$i = 0;
						$counter = 0;
						while(isset($arr_Opt_UnAssign[$i])){
							$counter = $counter+1;
							$subjID = "";
							$subjID = $arr_Opt_UnAssign[$i];
							
							$query = "select Subj_name from tbsubjectmaster where ID='$subjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Subjname  = $dbarray['Subj_name'];
							
							if($counter <= 9){
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}else{
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
?>
							<option value="<?PHP echo $subjID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Subjname; ?></option>
<?PHP
							$i=$i+1;
						}
?>
					  </select></TD>
					  <TD width="22%" valign="top"  align="left">
					  		<p align="center"><input type="submit" name="AddOptional" value="     &gt;   " title="Assign Optional Subject"></p>
						 	<p align="center"><input type="submit" name="RemOptional" value="     &lt;   " title="UnAssign Optional Subject"></p>
							<p align="center"><input type="submit" name="AddAllOptional" value="   &gt;&gt;   " title="Assign All Optional Subject"></p>
							<p align="center"><input type="submit" name="RemAllOptional" value="   &lt;&lt;   " title="UnAssign All Optional Subject"></p>
					  </TD>
					<TD width="39%" valign="top"  align="left" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
					Optional Subject [Assigned]
					  <select name="AsOptSubj" size="10" multiple style="width:270px; background:#66FFFF;">
<?PHP
						$i = 0;
						$counter = 0;
						while(isset($arr_Opt_Assign[$i])){
							$counter = $counter+1;
							$subjID = "";
							$subjID = $arr_Opt_Assign[$i];
							
							$query = "select Subj_name from tbsubjectmaster where ID='$subjID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
							$result = mysql_query($query,$conn);
							$dbarray = mysql_fetch_array($result);
							$Subjname  = $dbarray['Subj_name'];
							
							if($counter <= 9){
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}else{
								$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
?>
							<option value="<?PHP echo $subjID; ?>"><?PHP echo $counter; ?><?PHP echo $spacer; ?><?PHP echo $Subjname; ?></option>
<?PHP
							$i=$i+1;
						}
?>
                      </select>
						</TD>
					</TR>
					<TR>
					  <TD colspan="2" valign="top"  align="left">					  
						<p style="margin-left:150px;">&nbsp;</p>
					    </TD>
					</TR>
				</TBODY>
				</TABLE>
				</form>
<?PHP
		}elseif ($SubPage == "Teacher Subject") {
?>
				<?PHP echo $errormsg; 
				
				         ?>
		
				<div>
					<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
					<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
					<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
					</div>
					<script type="text/javascript">
					<!--
					//var theForm = document.forms['form1'];
					//if (!theForm) {
						//theForm = document.form1;
				//	}
					//function __doPostBack(eventTarget, eventArgument) {
						//if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
							//theForm.__EVENTTARGET.value = eventTarget;
							//theForm.__EVENTARGUMENT.value = eventArgument;
							//theForm.submit();
					//	}
					//}
					// -->
					</script>
					<script type="text/javascript">
					<!--
					//function WebForm_OnSubmit() {
					//if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
					//return true;
					//}
					dojo.require("dijit.form.Form");
					dojo.require("dijit.Dialog");
                     dojo.require("dijit.form.TextBox");
                      dojo.require("dijit.form.DateTextBox");
                       dojo.require("dijit.form.TimeTextBox");
					    dojo.require("dijit.form.ComboBox");
						dojo.require("dijit.form.Button");
	                     var formDlg;
	                       var secondDlg;
                            
					function handleOnClick2(){
						
						//call with the element content as a parameter
						//eval('this');
						if (!confirm('Are you sure you want to delete this entry, click ok to proceed, and cancel to exit!'))
						{return false;}
						else{
						var teacherid = eval('this.id');
						var teachername = document.getElementById('addclass').innerHTML;
						//var teachername = data;
						//alert(teacherid+teachername + " " + " says i'm here and good");
			//var teachername = document.getElementById('addclass').innerHTML;
         dojo.xhrGet({
         url: 'deleteemployeeClassandSubject.php',
		 handleAs: 'json',
         load: displayClassandSubject,
         error: helloError,
         content: {name: teachername, name2: teacherid
		}
      });
					
					}
					   }
					
					function handleOnClick(){
						document.getElementById('divLoading').style.display = 'block';
						//call with the element content as a parameter
						//eval('this');
						var teachername = eval('this.innerHTML');
						
						dojo.xhrGet({
                          url: 'getemployeeClassandSubject.php',
		                   handleAs: 'json',
                          load: displayClassandSubject,
                             error: helloError,
                            content: {name:teachername }
                                     });
					}
					function displayClassandSubject(data,ioArgs){
						var classlength = data.classname.length;
						var subjectlength = data.subjectname.length;
						var classlistBox = document.getElementById('classtaught');
						classlistBox.innerHTML = '';
						var subjectlistBox = document.getElementById('teachersubject');
						subjectlistBox.innerHTML = '';
						var newcolumn = document.createElement("td");
						var newcolumn2 = document.createElement("td");
						var teacher = data.teachername[0];
						
						for ( var i = 0; i < classlength; i++ ){
						    var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							entry.onclick = handleOnClick2; // set onclick event handler
							entry.id = data.teacherid2[i]; // set the id
							entry.innerHTML = data.classname[i];// +''+ data[i].Last
							field.appendChild( entry ); //insert entry into the field
							newcolumn.appendChild( field);
							classlistBox.appendChild( newcolumn );
						
						
						//alert(classlength + " " + " says i'm here and good");
						}
						for ( var i = 0; i < classlength; i++ ){
						    var entry2 = document.createElement( 'div' );
							var field2 = document.createElement('fieldset');
							entry2.onclick = handleOnClick2; // set onclick event handler
							entry2.id = data.teacherid2[i]; // set the id
							entry2.innerHTML = data.subjectname[i];// +''+ data[i].Last
							field2.appendChild( entry2 ); //insert entry into the field
							newcolumn2.appendChild( field2);
							subjectlistBox.appendChild( newcolumn2 );
						
						
						//alert(classlength + " " + " says i'm here and good");
						}
						var editBox = document.getElementById('edit');
						editBox.innerHTML = '';
						//editBox.onclick = showDialog;
						//var editBox2 = document.createElement( 'div' );
						
						editBox.innerHTML = "Click Here To Add New Class";
						var teacherClass = document.getElementById('addclass');
						  teacherClass.innerHTML = '';
						  teacherClass.innerHTML = teacher;
						  
						
						document.getElementById('divLoading').style.display = 'none'; 
					}
						
					function displaynames(data) {
						//alert("i'm here and good");
						//get the placeholder element from the page
						var listBox = document.getElementById('Names');
						listBox.innerHTML = '';
						//listBox.innerHTML = 'Bello Kolade';//clear the names on the page
						
						//iterate over retrieved entries and display them on the page
						for ( var i = 0; i < data.length; i++ )
						{
							//dynamically create a div element for each entry and a fieldset element to place it in
							var entry = document.createElement( 'div' );
							var field = document.createElement('fieldset');
							entry.onclick = handleOnClick; // set onclick event handler
							entry.id = i; // set the id
							entry.innerHTML = data[i].EmpName;// +''+ data[i].Last
							field.appendChild( entry ); //insert entry into the field
							listBox.appendChild( field ); //display the field
						} //end for
					}//end function displayAll
					// -->
               function display() {
					   var teacherJSON = <?PHP echo $varteacher; ?>;
					               displaynames(teacherJSON);
					 
					 }
		function helloError(data, ioArgs) {
        alert('Error when retrieving data from the server!');
		//var listBox = document.getElementById('Names');
     }
	function showDialog() {
		
		formDlg.show();
		}
	function displaymessage()
{
	var teachername = document.getElementById('addclass').innerHTML;
//alert(teachername);
document.getElementById('divLoading').style.display = 'block';
dojo.xhrGet({
         url: 'getemployeeClassandSubject2.php',
		 handleAs: 'json',
         load: displayClassandSubject,
         error: helloError,
         content: {name: dijit.byId('selectclass').value, name1: dijit.byId('selectsubject').value, name2: teachername
		 }
      });

}
function getClassandSubject(data,ioArgs){
	 
	 //displaynames(teacherJSON);
		//alert(data);
	}
					
					</script>
				
				<TABLE width="99%" style="WIDTH: 98%">
					<TBODY>
					<TR>
					  <TD width="25%" valign="top"  align="left"><div id="Names" style="display:block">  </div></TD><TD valign="top"  align="center">
                  <div id="editinfo"><table width="600px" style=" font-weight: bold;"align="center">
                  <tr><td align="left">CLASS TAUGHT</td><td align="left">ASSIGNED SUBJECT</td></tr>
                  <tr><td><div style="color:#F00; font-weight:300"  >Click On An Entry To Delete</div></td></tr>
                  <tr>
                    <td  align="left"><table id="classtaught" width="150px" style=" font-weight: bold;"align="left"><tr></tr></table></td>
                    <td align="left"><table id="teachersubject" width="150px" style=" font-weight: bold;"align="left"><tr></tr></table></td>
                    </tr>
                    <tr><td><a href="#" onClick="showDialog()" ><div id="edit" style="color:#03C; font-weight:300;"  ></div></a></td></tr></table></div>
                               </TD></TR></TBODY></TABLE>
                 <div id="addclass" style="display:none"></div>
                 
           <div dojoType="dijit.Dialog" id="formDialog" title="EDIT TEACHER CLASS SUBJECT" style="display: none" execute="displaymessage();" >
                
        
		<script type="dojo/event" event="onSubmit" args="e">
              //dojo.stopEvent(e); // prevent the default submit
           // if (!this.isValid()) {
              // window.alert('Please fix fields');
              // return;
            //}

          // window.alert("Would submit here via xhr");
            // dojo.xhrPost( {
            //      url: 'foo.com/handler',
            //      content: { field: 'go here' },
            //      handleAs: 'json'
            //      load: function(data) { .. },
            //      error: function(data) { .. }
            //  });
            
        </script>
    <table>
        <tr>
        
            <td>
                <label for="class">
                    Class:
                </label>
            </td>
            <td>
                <input id="selectclass">
            </td>
        </tr>
       <tr>
            <td>
                <label for="subject">
                    Subject:
                </label>
            </td>
            <td>
               <input id="selectsubject"> 
            </td>
        </tr>
     <tr>
            <td align="center" colspan="2">
                
               <button dojoType="dijit.form.Button" type="submit" onClick="return dijit.byId('formDialog').isValid();" >
                   DONE
                </button>
                <button dojoType="dijit.form.Button" type="button" onClick="dijit.byId('formDialog').hide();">
                    CANCEL
                </button>
            </td>
        </tr>
    </table>
</div>
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
			<TD align="center">Home | About SkoolNet Manager | Contact us | User Agreement | Privacy Policy | Copyright Policy</TD>
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
