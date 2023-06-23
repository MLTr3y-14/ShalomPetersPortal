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
		$audit=update_Monitory('Login','Administrator',$Page);
	}
	if(isset($_GET['st']))
	{
		$rstart = $_GET['st'];
		$rend = $_GET['ed'];
	}else{
		$rstart = 0;
		$rend = 20;
	}
	//Get School Report Header
	$query = "select ID,SchName,HeaderPic from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpCode  = $dbarray['ID'];
	$SchName  = $dbarray['SchName'];
	$HeaderPic  = $dbarray['HeaderPic'];
	
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	//Get School Report Header
	$query = "select * from tbschool";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$sName  = $dbarray['SchName'];
	$sAddress  = $dbarray['SchAddress'];
	$sState  = $dbarray['SchState'];
	$sCountry  = $dbarray['SchCountry'];
	$sPhone  = $dbarray['SchPhone'];
	$SchEmail1  = $dbarray['SchEmail1'];
	$sEmail2  = $dbarray['SchEmail2'];
	if(isset($_GET['src']))
	{
		$ischecked = $_GET['src'];
		if($ischecked =="Cat"){
			$OptCat = $_GET['cat'];
			$query2 = "select * from tbbookmst where CatID ='$OptCat'";
			
		}elseif($ischecked =="SubCat"){
			$OptSubCat = $_GET['subcat'];
			$query2 = "select * from tbbookmst where SubCatID = '$OptSubCat'";
		}
		elseif($ischecked =="Author"){
			$OptAuthor = $_GET['ath'];
			$query2 = "select * from tbbookmst where ID IN (Select BookID from tbbookauthorlist where AuthorID = '$OptAuthor')";
		}elseif($ischecked =="Publisher"){
			$OptPublisher = $_GET['pub'];
			$query2 = "select * from tbbookmst where PubID ='$OptPublisher'";
		}
		elseif($ischecked =="Condition"){
			$isCond = $_GET['isc'];
			 $query2 = "select * from tbbookmst where Status = '$isCond'";
		}elseif($ischecked =="BookCondition"){
			$OptCondition = $_GET['cond'];
			$query2 = "select * from tbbookmst where BookCondID ='$OptCondition'";
		}
		elseif($ischecked =="Accession"){
			$FrBookNo = $_GET['frm'];
			$ToBookNo = $_GET['to'];
			if($FrBookNo !="" And $ToBookNo !=""){
				$query2 = "select * from tbbookmst where BookNo >='$FrBookNo' And BookNo <= '$ToBookNo'";
			}elseif($FrBookNo !="" And $ToBookNo ==""){
				$query2 = "select * from tbbookmst where BookNo ='$FrBookNo' ";
			}elseif($FrBookNo =="" And $ToBookNo !=""){
				$query2 = "select * from tbbookmst where BookNo = '$ToBookNo'";
			}
		}elseif($ischecked =="Price"){
			$FrPrice = $_GET['frp'];
			$ToPrice = $_GET['top'];
			if($FrPrice !="" And $ToPrice !=""){
				$query2 = "select * from tbbookmst where Price >='$FrPrice' And Price <= '$ToPrice'";
			}elseif($FrPrice !="" And $ToPrice ==""){
				$query2 = "select * from tbbookmst where Price ='$FrPrice' ";
			}elseif($FrPrice =="" And $ToPrice !=""){
				$query2 = "select * from tbbookmst where Price = '$ToPrice'";
			}
		}
	}
	if(isset($_GET['src_stud']))
	{
		$isChkStudent = $_GET['src_stud'];
		$isClass = $_GET['isClass'];
		$isChkStudentName = $_GET['isAdm'];

           }
	if(isset($_GET['src_emp']))
	{
		$isChkEmp = $_GET['src_emp'];
		$OptDept = $_GET['isDept'];
		
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
<TABLE width="100%" bgcolor="#f4f4f4">
  <TBODY>
  <TR>
    <TD height="37" align=middle style="BACKGROUND-COLOR: transparent" valign="top"><br>
	  <TABLE width="1100px" border="1" cellPadding=3 cellSpacing=0 bgcolor="#FFFFFF" align="center">
<?PHP
		if ($Page == "Books in Library") {
?>
		  <TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="libreport.php?subpg=Books in Library">Library</a> &gt; Books in Library</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Books in Library</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="50" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Acc No/Name.</strong></font></td>
					  <td width="116" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Title</strong></font></td>
					  <td width="231" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Author(s)</strong></font></td>
					  <td width="132" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Publisher</strong></font></td>
					  <td width="128" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Category</strong></font></td>
					  <td width="86" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Edition</strong></font></td>
					  <td width="63" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Price</strong></font></td>
					  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Book Condition</strong></font></td>
					</tr>
<?PHP
					$result2 = mysql_query($query2,$conn);
					$num_rows2 = mysql_num_rows($result2);
					if ($num_rows2 > 0 ) {
						while ($row2 = mysql_fetch_array($result2)) 
						{
							$BookID = $row2["ID"];
							$BookNo = $row2["BookNo"];
							$Title = $row2["Title"];
							
							$ArrayAuthorName = "";
							$query3 = "select AuthorID from tbbookauthorlist where BookID = '$BookID'";
							$result3 = mysql_query($query3,$conn);
							$num_rows3 = mysql_num_rows($result3);
							if ($num_rows3 > 0 ) {
								while ($row3 = mysql_fetch_array($result3)) 
								{
									$AuthorID = $row3["AuthorID"];
									
									$query4 = "select * from tbauthormaster where ID='$AuthorID'";
									$result4 = mysql_query($query4,$conn);
									$dbarray4 = mysql_fetch_array($result4);
									$ArrayAuthorName  = $ArrayAuthorName."<br>".$dbarray4['AuthorName'];
								}
							}
							$PublisherID = $row2["PubID"];
							$query4 = "select PubName from tbpublisher where ID='$PublisherID'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$PubName  = $dbarray4['PubName'];
							
							$CatID = $row2["CatID"];
							$query4 = "select CatName from tblibcategorymst where ID='$CatID'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$CatName  = $dbarray4['CatName'];
							
							$Edition = $row2["Edition"];
							$BookRate = $row2["BookRate"];
							$BookPrice = $row2["Price"];
							
							$Condition = $row2["BookCondID"];
							$query4 = "select BCond from bookcondition where ID='$Condition'";
							$result4 = mysql_query($query4,$conn);
							$dbarray4 = mysql_fetch_array($result4);
							$BCond  = $dbarray4['BCond'];
?>
						  <tr>
							<td width="50" align="center"><?PHP echo $BookNo; ?></td>
							<td width="116" align="center"><?PHP echo $Title; ?></td>
							<td width="231" align="center"><?PHP echo $ArrayAuthorName; ?></td>
							<td width="132" align="center"><?PHP echo $PubName; ?></td>
							<td width="128" align="center"><?PHP echo $CatName; ?></td>
							<td width="86" align="center"><?PHP echo $Edition; ?></td>
							<td width="63" align="center"><?PHP echo number_format($BookPrice,2) ?></td>
							<td width="98" align="center"><?PHP echo $BCond; ?></td>
						  </tr>
<?PHP
						}
					}
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Books Issued to Student") {
?>
		  <TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="libreport.php?subpg=Books Issued to Student">Library</a> &gt; Books Issued to Student</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Books Issued to Student</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="96" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Acc No./Name</strong></font></td>
					  <td width="219" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Title</strong></font></td>
					  <td width="128" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Issue Date</strong></font></td>
					  <td width="145" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Due Date</strong></font></td>
					  
					  <td width="81" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Fine Amount</strong></font></td>
					  <td width="98" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Fine Received</strong></font></td>
					</tr>
<?PHP
					if($isChkStudent=="Class"){
						//if($AdmisNo==""){
							//By Selected Class Only
							$OptClass = $_GET['isClass'];
							 $query = "select ID from tbclassmaster where Class_Name = '$OptClass'";
							 $result = mysql_query($query,$conn);
							 $row = mysql_fetch_array($result);
							 $OptClassID = $row["ID"];
							$query2 = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$OptClassID' order by Stu_Full_Name";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							if ($num_rows2 > 0 ) {
								while ($row2 = mysql_fetch_array($result2)) 
								{
									$Stu_Full_Name = $row2["Stu_Full_Name"];
									$AdmissionNo = $row2["AdmissionNo"];
									$Display = "False";
									$query3 = "select * from tbbookissstd where AdmnNo = '$AdmissionNo' order by AdmnNo";
									$result3 = mysql_query($query3,$conn);
									$num_rows3 = mysql_num_rows($result3);
									if ($num_rows3 > 0 ) {
										while ($row3 = mysql_fetch_array($result3)) 
										{
											$bookID = $row3["bookID"];
											$IssDate = $row3["IssDate"];
											$DueDate = $row3["DueDate"];
											$TotalFine = $row3["TotalFine"];
											$FineRec = $row3["FineRec"];
											$ReturnDate = $row3["ReturnDate"];
											
											$query = "select Title,BookNo from tbbookmst where ID='$bookID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$BookTitle = $dbarray['Title'];
											$AccessionNo = $dbarray['BookNo'];
											if($Display=="False"){
												$Display ="True";
?>
											  <tr>
												<td colspan="7"><p style="margin-left:10px;"><strong><?PHP echo $Stu_Full_Name; ?></strong></p></td>
											  </tr>
<?PHP
											}
?>
										  <tr>
											<td width="96" align="center"><?PHP echo $AccessionNo; ?></td>
											<td width="219" align="center"><?PHP echo $BookTitle; ?></td>
											<td width="128" align="center"><?PHP echo $IssDate; ?></td>
											<td width="145" align="center"><?PHP echo $DueDate; ?></td>
											
											<td width="81" align="center"><?PHP echo number_format($TotalFine,2); ?></td>
											<td width="98" align="center"><?PHP echo number_format($FineRec,2); ?></td>
										  </tr>
<?PHP
										}
									}
								}
							}
						}
						elseif($isChkStudent=="Name"){
							//By Selected Admission No.
							//$Display = "False";
							//$ArrAdmnNo=explode (',', $AdmisNo);
							//$i=0;
							//while(isset($ArrAdmnNo[$i])){
								$isChkStudentName = $_GET['isAdm'];
								//echo $isChkStudentName.'Im Here';
								 
								$query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name ='$isChkStudentName'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$AdmissionNo = $dbarray['AdmissionNo'];
								//$AdmissionNo = $ArrAdmnNo[$i];			
								$Display = "False";
								
								$query3 = "select * from tbbookissstd where AdmnNo = '$AdmissionNo'";
								$result3 = mysql_query($query3,$conn);
								$num_rows3 = mysql_num_rows($result3);
								if ($num_rows3 > 0 ) {
									while ($row3 = mysql_fetch_array($result3)) 
									{
										$bookID = $row3["bookID"];
										$IssDate = $row3["IssDate"];
										$DueDate = $row3["DueDate"];
										$TotalFine = $row3["TotalFine"];
										$FineRec = $row3["FineRec"];
										$ReturnDate = $row3["ReturnDate"];
										
										$query = "select Title,BookNo from tbbookmst where ID='$bookID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$BookTitle = $dbarray['Title'];
										$AccessionNo = $dbarray['BookNo'];
										if($Display=="False"){
											$Display ="True";
?>
										  <tr>
											<td colspan="7"><p style="margin-left:10px;"><strong><?PHP echo $isChkStudentName; ?></strong></p></td>
										  </tr>
<?PHP
										}
?>
									  <tr>
										<td width="96" align="center"><?PHP echo $AccessionNo; ?></td>
										<td width="219" align="center"><?PHP echo $BookTitle; ?></td>
										<td width="128" align="center"><?PHP echo $IssDate; ?></td>
										<td width="145" align="center"><?PHP echo $DueDate; ?></td>
										
										<td width="81" align="center"><?PHP echo number_format($TotalFine,2); ?></td>
										<td width="98" align="center"><?PHP echo number_format($FineRec,2); ?></td>
									  </tr>
<?PHP
									}
								}		
						
							}
						//}
						elseif($isChkStudent=="All"){
							//By Selected Admission No.
							//$Display = "False";
							//$ArrAdmnNo=explode (',', $AdmisNo);
							//$i=0;
							//while(isset($ArrAdmnNo[$i])){
								
								/*$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo ='$ArrAdmnNo[$i]'";
								$result = mysql_query($query,$conn);
								$dbarray = mysql_fetch_array($result);
								$Stu_Full_Name = $dbarray['Stu_Full_Name'];
								$AdmissionNo = $ArrAdmnNo[$i];			
								$Display = "False";*/
								
								$query3 = "select * from tbbookissstd";
								$result3 = mysql_query($query3,$conn);
								$num_rows3 = mysql_num_rows($result3);
								if ($num_rows3 > 0 ) {
									while ($row3 = mysql_fetch_array($result3)) 
									{
										$bookID = $row3["bookID"];
										$IssDate = $row3["IssDate"];
										$DueDate = $row3["DueDate"];
										$TotalFine = $row3["TotalFine"];
										$FineRec = $row3["FineRec"];
										$ReturnDate = $row3["ReturnDate"];
										$AdmissionNo = $row3["AdmnNo"];
										
										$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo ='$AdmissionNo'";
								               $result = mysql_query($query,$conn);
								                $dbarray = mysql_fetch_array($result);
								                 $Stu_Full_Name = $dbarray['Stu_Full_Name'];
								                  $Display ="False";
								//$AdmissionNo = $ArrAdmnNo[$i];	
										
										$query = "select Title,BookNo from tbbookmst where ID='$bookID'";
										$result = mysql_query($query,$conn);
										$dbarray = mysql_fetch_array($result);
										$BookTitle = $dbarray['Title'];
										$AccessionNo = $dbarray['BookNo'];
										if($Display=="False"){
											$Display ="True";
?>
										  <tr>
											<td colspan="7"><p style="margin-left:10px;"><strong><?PHP echo $Stu_Full_Name; ?></strong></p></td>
										  </tr>
<?PHP
										}
?>
									  <tr>
										<td width="96" align="center"><?PHP echo $AccessionNo; ?></td>
										<td width="219" align="center"><?PHP echo $BookTitle; ?></td>
										<td width="128" align="center"><?PHP echo $IssDate; ?></td>
										<td width="145" align="center"><?PHP echo $DueDate; ?></td>
										
										<td width="81" align="center"><?PHP echo number_format($TotalFine,2); ?></td>
										<td width="98" align="center"><?PHP echo number_format($FineRec,2); ?></td>
									  </tr>
<?PHP
									}
								}		
						
							}
						//}
					//}
						
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}elseif ($Page == "Books Issued to Employee") {
?>
		  <TR>
			<TD><div align="center"><img src="images/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
		  </TR>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=Master%20Setup&mod=admin">Home</a> &gt; <a href="libreport.php?subpg=Books in Library">Report</a> &gt; <a href="libreport.php?subpg=Books Issued to Employee">Library</a> &gt; Books Issued to Employee</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong></strong></div>
				
				
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: <?PHP echo $bg; ?>; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>Books Issued to Employee</strong>s</div>
				</div>
				<div align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 12pt; COLOR: #666666; FONT-FAMILY: Georgia; FONT-VARIANT: inherit;"></div>
				<table  width="100%" cellpadding="5" cellspacing="0" style="HEIGHT: 123px VERTICAL-ALIGN: top; WIDTH: 100%; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;" >
				  <tbody>
					<tr>
					  <td width="96" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Acc No./Employee Name</strong></font></td>
					  <td width="219" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Title</strong></font></td>
					  <td width="128" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Issue Date</strong></font></td>
					  <td width="145" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Due Date</strong></font></td>
					  <td width="147" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Return Date</strong></font></td>
					  <td width="81" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Due Days</strong></font></td>
					</tr>
<?PHP
					if($isChkEmp=="Dept"){
						
							//By Selected Department Only
							$OptDept = $_GET['isDept'];
							$query2 = "select ID,EmpName from tbemployeemasters where EmpDept = '$OptDept' order by EmpName";
							$result2 = mysql_query($query2,$conn);
							$num_rows2 = mysql_num_rows($result2);
							if ($num_rows2 > 0 ) {
								while ($row2 = mysql_fetch_array($result2)) 
								{
									$SelEmpID = $row2["ID"];
									$EmpName = $row2["EmpName"];
									$Display = "False";
									$query3 = "select * from tbbookissemp where EmpID = '$SelEmpID' order by EmpID";
									$result3 = mysql_query($query3,$conn);
									$num_rows3 = mysql_num_rows($result3);
									if ($num_rows3 > 0 ) {
										while ($row3 = mysql_fetch_array($result3)) 
										{
											$bookID = $row3["bookID"];
											$IssDate = $row3["issDate"];
											$DueDate = $row3["DueDate"];
											$ReturnDate = $row3["ReturnDate"];
											$FineCounter = 0;
											
											$query = "select Title,BookNo from tbbookmst where ID='$bookID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$BookTitle = $dbarray['Title'];
											$AccessionNo = $dbarray['BookNo'];
											if($Display=="False"){
												$Display ="True";
?>
											  <tr>
												<td colspan="7"><p style="margin-left:10px;"><strong><?PHP echo $EmpName; ?></strong></p></td>
											  </tr>
<?PHP
											}
?>
										  <tr>
											<td width="96" align="center"><?PHP echo $AccessionNo; ?></td>
											<td width="219" align="center"><?PHP echo $BookTitle; ?></td>
											<td width="128" align="center"><?PHP echo $IssDate; ?></td>
											<td width="145" align="center"><?PHP echo $DueDate; ?></td>
											<td width="147" align="center"><?PHP echo $ReturnDate; ?></td>
											<td width="98" align="center"><?PHP echo $FineCounter ?></td>
										  </tr>
<?PHP
										}
									}
								}
							}
						}elseif($isChkEmp=="All"){
							        
									$query3 = "select * from tbbookissemp";
									$result3 = mysql_query($query3,$conn);
									$num_rows3 = mysql_num_rows($result3);
									if ($num_rows3 > 0 ) {
										while ($row3 = mysql_fetch_array($result3)) 
										{
											$bookID = $row3["bookID"];
											$IssDate = $row3["issDate"];
											$DueDate = $row3["DueDate"];
											$ReturnDate = $row3["ReturnDate"];
											$EmpID = $row3["EmpID"];
											//$FineCounter = 0;
											$query2 = "select EmpName from tbemployeemasters where ID = '$EmpID'";
							                  $result2 = mysql_query($query2,$conn);
											  $dbarray2 = mysql_fetch_array($result2);
											    $EmpName = $dbarray2['EmpName'];
												$Display = "False";
											
											$query = "select Title,BookNo from tbbookmst where ID='$bookID'";
											$result = mysql_query($query,$conn);
											$dbarray = mysql_fetch_array($result);
											$BookTitle = $dbarray['Title'];
											$AccessionNo = $dbarray['BookNo'];
											if($Display=="False"){
												$Display ="True";
?>
											  <tr>
												<td colspan="7"><p style="margin-left:10px;"><strong><?PHP echo $EmpName; ?></strong></p></td>
											  </tr>
<?PHP
											}
?>
										  <tr>
											<td width="96" align="center"><?PHP echo $AccessionNo; ?></td>
											<td width="219" align="center"><?PHP echo $BookTitle; ?></td>
											<td width="128" align="center"><?PHP echo $IssDate; ?></td>
											<td width="145" align="center"><?PHP echo $DueDate; ?></td>
											<td width="147" align="center"><?PHP echo $ReturnDate; ?></td>
											<td width="98" align="center"><?PHP echo $FineCounter ?></td>
										  </tr>
<?PHP
										}
									}
								}
							//}
						//}
							
?>
			      </tbody>
			  </table>
			  <br><br></TD>
		  </TR>
<?PHP
		}
?>
	 </TABLE></TD>
  </TR></TBODY></TABLE>
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
