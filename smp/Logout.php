<?php 
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SkoolNET Manager :: School Management System</title>
</head>

<body>
<?PHP
	unset($_SESSION['username']);
	unset($_SESSION['password']);
	echo "<meta http-equiv=\"Refresh\" content=\"0;url=login.php\">";
	session_destroy(); 
?>
</body>
</html>
