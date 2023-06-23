<?PHP 
	session_start();
	$end=time();
	if(!isset($_POST['SubmitContinue']))
	{
		 include("start_timer.php");
    	 include("end_timer.php");
		 $_SESSION['time_start']=$end;
		 $TimerOut ="false";
	}
?>
<html>
<LINK media=all href="css/common.css" type=text/css rel=stylesheet>
<LINK media=all href="css/render.css" type=text/css rel=stylesheet>
<LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/menu.css" type=text/css rel=stylesheet>
<body>
<center>
<form name="redirect" method="post" action="count.php"> 
		<font face="Arial"><b>Your request could not be completed because your session has timed out. <br>
		Click continue to return to web kiosk.</b></font><br>
		<br>
		<form name="form1" method="post" action="">
			You will be redirected in <input type="text" size="3" name="redirect2"> <font face="Arial"><b>seconds</b></font>
		    <label>
		    <INPUT name="SubmitContinue" onclick=javascript:window.close(); type="submit" value="Continue">
		    </label>
		</form> 
		 
</form>
</center>
<?PHP 
	if(!isset($_POST['SubmitContinue']))
	{
?>
		<script> 
		<!-- 
		//change below target URL to your own 
		var targetURL="timeout.php" 
		//change the second to start counting down from 
		var countdownfrom=10 
		
		var currentsecond=document.redirect.redirect2.value=countdownfrom+1 
		function countredirect(){ 
		if (currentsecond!=1){ 
		currentsecond-=1 
		document.redirect.redirect2.value=currentsecond 
		} 
		else{ 
		window.location=targetURL 
		return 
		} 
		setTimeout("countredirect()",1000) 
		} 
		
		countredirect() 
		//--> 
		</script> 
<?PHP 
	}else{
		echo "<center>Browser not supported, close the window to continue</center>";
	}
?>
</body>   