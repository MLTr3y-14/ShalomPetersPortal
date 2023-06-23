<?php 
ob_start(); 
session_start();

global $userNames;
if (isset($_SESSION['username']))
{
	$userNames=$_SESSION['username'];
} else {
	echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
	exit;
}
include 'library/config.php';
include 'library/opendb.php';
include 'formatstring.php';
global $Page,$errorMsg,$SubLink,$EventDate,$EventItem,$ItemLink,$PhotoPageNo;
global $DisplayMsg,$DisplayMsg1,$DisplayMsg2,$DisplayMsg3,$FName,$UserName,$Password,$Email,$oldpwd,$Newpwd,$confirmpwd;
if(isset($_GET['pg']))
{
	$FormPage = $_GET['pg'];
}
if(isset($_GET['pgNo']))
{
	$PhotoPageNo =  $_GET['pgNo'];
}
// filename: photo.php 

// first let's set some variables 

// make a note of the current working directory relative to root. 
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

// make a note of the location of the upload handler script 
$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'browse.processor.php'; 

// set a max file size for the html upload form 
$max_file_size = 100000; // size in bytes OR 30kb
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SkoolNET Manager</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-size: 11px}
-->
</style>

<style type="text/css">
<!--
.style5 {
	color: #000099;
	font-weight: bold;
}

.style17 {color: #000000; font-weight: bold; }
.style20 {font-size: 12px}
-->
</style>
<script language="JavaScript">
<!--
	function openWin( windowURL, windowName, windowFeatures ) {
		return window.open( windowURL, windowName, windowFeatures ) ;
	}
// -->
</script>
</head>

<body>
<center>
<TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
<TBODY>
<TR>
	<TD width="100%">
		<form id="Upload2" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post"> 
			<TABLE cellSpacing=5 cellPadding=5 border=0 width="100%">
			<TBODY>
			<TR bgcolor="#CCCCCC">
				<TD colspan="2" align="left" style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: maroon; FONT-FAMILY: Georgia; HEIGHT: 53px; FONT-VARIANT: small-caps">Upload Image 
				  <p><input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"></p></TD>
			</TR>
			<TR>
				<TD width=26%><label for="file">Select File:</label>  </TD>
				<TD width=74% align="left"><input id="file" type="file" name="file"> </TD>
			</TR>
			<TR>
				<TD width=26%><label for="submit">Press to...</label></TD>
				<TD width=74% align="left"><input id="submit" type="submit" name="submit" value="Upload"> </TD>
			</TR>
			<TR>
				<TD colspan="2">&nbsp;</TD>
			</TR>
		  </TBODY></TABLE>
		</form> 
	</TD>
</TR>
</TBODY></TABLE>
</center>
</body>

</html>
