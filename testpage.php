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


echo date('h:i:s') . "<br>";
?>
<html>
<form name="form1" id="form1" method="post" action="">
<input type="button" value="Display alert box in 3 seconds" />

</form>

</html>
<?php 
//sleep for 5 seconds
sleep(5);

//start again
echo date('h:i:s');
?>
<script type="text/javascript">
document.getElementById("form1").submit(); // Here formid is the id of your form
 </script>



