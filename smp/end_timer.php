<?php
$Seconds." Seconds " ;
$time_out=10;
if($Minutes<$time_out){
    $_SESSION['time_start']=$end;
}else{
	$today = time();
	$event = mktime(0,0,0,12,25,2010);
	$countdown = round(($event - $today)/86400);
	$TimerOut = "True";
    include("Logout.php");
}
?>