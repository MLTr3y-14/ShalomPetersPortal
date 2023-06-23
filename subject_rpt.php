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
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
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
	if(isset($_GET['eid']))
	{
		$Empid = $_GET['eid'];
		$query = "select EmpName from tbemployeemasters where ID='$Empid'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$EmpName  = $dbarray['EmpName'];
		
		//GET ACTIVE TERM
		$query2 = "select Section from section where Active = '1'";
		$result2 = mysql_query($query2,$conn);
		$dbarray2 = mysql_fetch_array($result2);
		$Activeterm  = $dbarray2['Section'];
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
	if ($HeaderPic == ""){
		$HeaderPic = "empty_r2_c2.jpg";
	}
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
	  <TR>
	  	<TD><div align="center"><img src="images/uploads/<?PHP echo $HeaderPic; ?>" width="725" height="144"></div></TD>
	  </TR>
<?PHP
		if ($Page == "View Teacher Subject") {
?>
		  <TR>
			<TD>
				<div align="left" style="BORDER-RIGHT: #ccc 1px solid; BORDER-TOP: #ccc 1px solid; BORDER-BOTTOM: #ccc 1px solid; BORDER-LEFT: #ccc 1px solid; height:30px;"><strong><a href="welcome.php?pg=System%20Setup&mod=admin">Home</a> &gt; <a href="timetable.php?subpg=View time table">Time Table</a> &gt; <a href="subject.php?subpg=Teacher%20Subject">Teacher Subject</a> &gt; View Teacher Subject</strong></div>
				<div align="center" style="FONT-WEIGHT: bold; FONT-SIZE: 14pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 33px; FONT-VARIANT: small-caps"><strong>View Teacher Subject</strong></div>
				<table  width="67%" cellpadding="8" cellspacing="0" border="0">
				  <tbody>
					<tr>
					  <td align="left" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-LEFT: #CCCCCC 1px solid; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Teacher Name  : <?PHP echo $EmpName; ?> </strong></td>
					  <td align="right" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: right; BORDER-BOTTOM: #CCCCCC 1px solid; border-right:CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><strong>Section : <?PHP echo $Activeterm; ?></strong> </td>
					</tr>		
					<tr>
					  <td width="23%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Class Name</strong></font></td>
					  <td width="77%" bgcolor="#666666" align="center"><font color="#FFFFFF"><strong>Subject</strong></font></td>
					</tr>
					<tr>
					  <td width="23%" align="center">&nbsp;</td>
					  <td width="77%" align="left">
					  		<table  width="90%" cellpadding="8" cellspacing="0" border="0">
							  <tbody>
								<tr>
									  <td width="50%" align="left"><em><strong>Compulsory Subjects</strong></em></td>
									  <td width="50%" align="left"><em><strong>Optional Subjects</strong></em></td>
								</tr>
							  </tbody>
							</table>
					  </td>
					</tr>
<?PHP
					$OptEmp = $_POST['OptEmp'];
					$n = 0;
					$query = "select Distinct ClassID from tbclassteachersubj where EmpId = '$Empid' and SecID = '$Activeterm'";
					$result = mysql_query($query,$conn);
					$num_rows = mysql_num_rows($result);
					if ($num_rows > 0 ) {
						while ($row = mysql_fetch_array($result)) 
						{
							$ClassID = $row["ClassID"];
							$sub = 0;
							$opt = 0;
							$query3 = "select SubjectId from tbclasssubjectrelation where SubjectId IN(Select SubjectID from tbclassteachersubj where ClassID = '$ClassID' and EmpId = '$Empid' and SecID = '$Activeterm')";
							$result3 = mysql_query($query3,$conn);
							$num_rows3 = mysql_num_rows($result3);
							if ($num_rows3 > 0 ) {
								while ($row3 = mysql_fetch_array($result3)) 
								{
									$SubjID = $row3["SubjectId"];
									if (CHK_SUBJ_TYPE($SubjID)== 0){
										$Comp_Subj[$sub] = $SubjID;
										$sub = $sub+1;
									}else{
										$Opt_Subj[$opt] = $SubjID;
										$opt = $opt+1;
									}
								}
							}
							$sub = 0;
?>
							<tr>
					  			<td width="23%" align="center" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: center; BORDER-BOTTOM: #CCCCCC 1px solid; border-top:CCCCCC 1px solid;"><?PHP echo GetClassName($ClassID); ?>				  			    </td>
								<td width="77%" align="center" style="HEIGHT: 123px VERTICAL-ALIGN: top; TEXT-ALIGN: left; BORDER-BOTTOM: #CCCCCC 1px solid; border-top:CCCCCC 1px solid;">
									<table  width="90%" cellpadding="8" cellspacing="0" border="0">
									  <tbody>
<?PHP
										while(isset($Comp_Subj[$sub]) or isset($Opt_Subj[$sub])){
?>
											<tr>
											  <td width="50%" align="left"><em><?PHP echo GetSubjectName($Comp_Subj[$sub]); ?></em></td>
											  <td width="50%" align="left"><em><?PHP echo GetSubjectName($Opt_Subj[$sub]); ?></em></td>
											</tr>
<?PHP
											$sub=$sub+1;
										}
?>
									  </tbody>
									</table>
						      </td>
							</tr>
<?PHP
						}
					}
?>
			      </tbody>
			  </table>
			</TD>
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
