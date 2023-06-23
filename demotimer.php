<?php
$StartDate = 20110517;
$dat = date('Y'.'m'.'d');
$end = $dat-$StartDate;
if($end > 101)
{
echo "<meta http-equiv=\"Refresh\" content=\"0;url=Logout.php\">";
		exit;
} 
else{
	//echo $end;
}


?>