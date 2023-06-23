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
	$query = "select EmpID from tbusermaster where UserName='$userNames'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$Online_EmpID  = $dbarray['EmpID'];
	$dat = date('Y'.'-'.'m'.'-'.'d');
	if(isset($_GET['pg']))
	{
		$Page = $_GET['pg'];
	}
	
	if(isset($_POST['OptEmp']))
	{	
		$Subject = $_POST['Subject'];
		$Message = $_POST['Message'];
		$OptEmps = $_POST['OptEmp'];
		$EmpList = $_POST['EmpList'];
		$EmpList = $EmpList.",".$OptEmps;
	}
	if($EmpList !="")
	{
		$arrEmpList=explode (',', $EmpList);
	}
	if(isset($_POST['SubmitMessage']))
	{
		$Subject = $_POST['Subject'];
		$Message = $_POST['Message'];
		if(!$_POST['Subject']){
			$errormsg = "<font color = red size = 1>Subject Name is empty.</font>";
			$PageHasError = 1;
		}
		if(!$_POST['Message']){
			$errormsg = "<font color = red size = 1>Message Name is empty.</font>";
			$PageHasError = 1;
		}
		$Total = $_POST['Total'];
		$Count_Selected = 0;
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['chk'.$i]))
			{
				$Count_Selected = $Count_Selected+1;
				$SelEmpID = $_POST['chk'.$i];
				$q = "Insert into tbpopupmessage(Subject,EmpID,mDate,Message,FromID) Values ('$Subject','$SelEmpID','$dat','$Message','$Online_EmpID')";
				$result = mysql_query($q,$conn);
			}
		}
		if($Count_Selected == 0 ){
			$errormsg = "<font color = red size = 1>No staff is selected.</font>";
			$PageHasError = 1;
		}else{
			$errormsg = "<font color = blue size = 1>Sent Successfully.</font>";
			$Subject = "";
			$Message = "";
			$arrEmpList = "";
			$EmpList = "";
			
		}
	}
	if(isset($_POST['TotalMsg']))
	{
		$Total = $_POST['TotalMsg'];
		for($i=1;$i<=$Total;$i++){
			if(isset( $_POST['Delete'.$i]))
			{
				$MsgID = $_POST['MsgID'.$i];
				$q = "Delete From tbpopupmessage where ID = '$MsgID'";
				$result = mysql_query($q,$conn);
			}
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>SkoolNet Manager</TITLE>
<META content="Viodvla.com is the official website of the Directorate for Road Traffic Services in Nigeria." name="Short Title">
<META content="Nigeria Centre for Road Traffic" name=AGLS.Function>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
<LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/menu.css" type=text/css rel=stylesheet>
<style type="text/css">
td img {display: block;}.style21 {color: #FFFFFF}
</style>

<SCRIPT 
src="css/jquery-1.2.3.min.js" 
type=text/javascript></SCRIPT>

<SCRIPT 
src="css/menu.js" 
type=text/JavaScript></SCRIPT>
</HEAD>
<BODY background=Images/news-background.jpg>
<?PHP
	if ($Page == "Send Message") {
?>
		<form name="form1" method="post" action="popup.php?pg=Send Message">
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
		
		<TABLE width="341" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
		  <TBODY>
		  <TR>
			<TD colspan="2" align="center" valign="top"><strong><?PHP echo $errormsg; ?></strong></TD></TD>
		  </TR>
			<TR bgcolor="#666666">
			<TD colspan="2" align="center" valign="top"><div align="center" class="style21"><strong>POP UP MESSAGE</strong></div></TD>
		  </TR>
		  <TR>
			<TD width="116" align="right" valign="top"><strong>Subject</strong></TD>
			<TD width="213" valign="top"><label>
			  <input type="text" name="Subject" value="<?PHP echo $Subject; ?>">
			</label></TD>
		  </TR>
		  <TR>
			<TD width="116" align="right" valign="top"><strong>Send To</strong></TD>
			<TD width="213" valign="top"><label>
			  <select name="OptEmp" style="width:200px; background:#66FFFF;" onChange="javascript:setTimeout('__doPostBack(\'OptEmp\',\'\')', 0)">
			  <option value="">&nbsp;</option>
<?PHP								
					$counter = 0;
					$query = "select ID,EmpName from tbemployeemasters where ID IN (Select EmpID from tbusermaster) order by EmpName";
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
							$EmpID = $row["ID"];
							$EmpName = $row["EmpName"];
							
							if($OptEmp =="$EmpID"){
?>
								<option value="<?PHP echo $EmpID; ?>" selected="selected"><?PHP echo $EmpName; ?></option>
<?PHP
							}else{
?>
								<option value="<?PHP echo $EmpID; ?>"><?PHP echo $EmpName; ?></option>
<?PHP
							}
						}
					}
?>
  			</select><br>
<?PHP
				$n=0;
				$Counter = 0;
				while(isset($arrEmpList[$n])){
					if($arrEmpList[$n] !=""){
						$Counter = $Counter+1;
						$query = "select EmpName from tbemployeemasters where ID='$arrEmpList[$n]'";
						$result = mysql_query($query,$conn);
						$dbarray = mysql_fetch_array($result);
						$EmpName  = $dbarray['EmpName'];
				
						echo "<input type='checkbox' name='chk".$Counter."' value='".$arrEmpList[$n]."'>&nbsp;&nbsp;&nbsp;&nbsp;".$EmpName."<br>";
					}
					$n = $n+1;
				}
?>
			</label></TD>
		  </TR>
		  <TR>
			<TD width="116" align="right" valign="top"><strong>Message</strong></TD>
			<TD width="213" valign="top"><label>
			  <textarea name="Message" cols="30" rows="5"> <?PHP echo $Message; ?></textarea>
			</label></TD>
		  </TR>
		  <TR>
			<TD colspan="2" align="Center" valign="top"><label>
			  <input type="hidden" name="Total" value="<?PHP echo $Counter; ?>">
			  <input type="hidden" name="EmpList" value="<?PHP echo $EmpList; ?>">
			  <input type="submit" name="SubmitMessage" value="Send Message">
			</label></TD>
		  </TR>
		  </TBODY>
		</TABLE>
		</form>
<?PHP
	}elseif ($Page == "Display") {
?>
		<form name="form1" method="post" action="popup.php?pg=Display">
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
		
		<TABLE width="341" style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid;">
		  <TBODY>
<?PHP
			$CountMsg = 0;
			$query2 = "select * from tbpopupmessage where mDate = '$dat' and EmpID = '$Online_EmpID'";
			$result2 = mysql_query($query2,$conn);
			$num_rows2 = mysql_num_rows($result2);
			if ($num_rows2 > 0 ) {
				while ($row2 = mysql_fetch_array($result2)) 
				{
					$CountMsg = $CountMsg+1;
					$SetupID = $row2["ID"];
					$Subject = $row2["Subject"];
					$Message = $row2["Message"];
					$FromID = $row2["FromID"];
					
?>
				  <TR bgcolor="#666666">
					<TD colspan="2" align="center" valign="top"><div align="center" class="style21"><strong> MESSAGE FROM : <?PHP echo GET_EMP_NAME($FromID); ?></strong></div></TD>
				  </TR>
				  <TR>
					<TD width="116" align="right" valign="top"><strong>SUBJECT : </strong></TD>
					<TD width="213" valign="top"><?PHP echo $Subject; ?></TD>
				  </TR>
				  <TR>
					<TD width="116" align="right" valign="top"><strong>MESSAGE  :</strong></TD>
					<TD width="213" valign="top"><?PHP echo $Message; ?></TD>
				  </TR>
				  <TR>
					<TD colspan="2" align="Center" valign="top"><label>
					  <input type="hidden" name="MsgID<?PHP echo $CountMsg; ?>" value="<?PHP echo $SetupID; ?>">
					  <input type="submit" name="Delete<?PHP echo $CountMsg; ?>" value="Delete Message">
					</label></TD>
				  </TR>
<?PHP
				}
			}
?>
				<TR><TD colspan="2"><input type="hidden" name="TotalMsg" value="<?PHP echo $CountMsg; ?>"></TD></TR>
		  </TBODY>
		</TABLE>
		</form>
		<center>
		</center>
<?PHP
	}
?>

</BODY></HTML>
