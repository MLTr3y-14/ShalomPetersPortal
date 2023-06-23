<?php
session_start();

include_once 'sms_sender.php';

  function sendAttendance($currDate, $studClass, $studentName, $status, $total, $admiNum, $rec){
 	$tpl;
 	if(!isset($_SESSION['att_tpl'])){
 		// att_tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'ATT'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['att_tpl'] = $tpl; 

 	}else{
 		$tpl = $_SESSION['att_tpl'];
 	}

	$tpl = str_replace('<date>', $selDate, $tpl);
     $tpl = str_replace('<class>', $class, $tpl);
     $tpl = str_replace('<name>', $name, $tpl);
     $tpl = str_replace('<status>', $status, $tpl);
     $tpl = str_replace('<total>', $total, $tpl);
     $tpl = str_replace('<admin_num>', $adminNum, $tpl);

     send_sms($sender, $tpl, $rec, 'ATT');

 }

 function sendPerformanceBySubject($adminNum, $studentName, $exam, $total, $subject, $rec){
 	$tpl;
 	if(!isset($_SESSION['rst_subj_tpl'])){
 		// att_tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'RST_SUBJ'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['rst_subj_tpl'] = $tpl;

 	}else{
 		$tpl = $_SESSION['rst_subj_tpl'];
 	}

	$tpl = str_replace('<admin_num>', $adminNum, $tpl);
     $tpl = str_replace('<stud_name>', $studentName, $tpl);
     $tpl = str_replace('<exam_name>', $exam, $tpl);
     $tpl = str_replace('<total>', $total, $tpl);
     $tpl = str_replace('<subject>', $subject, $tpl);

     send_sms($sender, $tpl, $rec, 'RST_SUBJ');

 }

 function sendPerformanceOverall($adminNum, $studentName, $exam, $avg, $subject, $rec){
 	$tpl;
 	if(!isset($_SESSION['rst_tpl'])){
 		// att_tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'RST'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['rst_tpl'] = $tpl;

 	}else{
 		$tpl = $_SESSION['rst_tpl'];
 	}

	$tpl = str_replace('<admin_num>', $adminNum, $tpl);
     $tpl = str_replace('<stud_name>', $studentName, $tpl);
     $tpl = str_replace('<exam_name>', $exam, $tpl);
     $tpl = str_replace('<average>', $avg, $tpl);
     $tpl = str_replace('<subject>', $subject, $tpl);

     send_sms($sender, $tpl, $rec, 'RST');

 }

 function sendFeesDefaulter($adminNum, $studentName, $term, $totalDebt, $rec){
 	$tpl;
 	if(!isset($_SESSION['fees_default_tpl'])){
 		// att_tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'FEES_DEFAULTER'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['fees_default_tpl'] = $tpl;

 	}else{
 		$tpl = $_SESSION['fees_default_tpl'];
 	}

	$tpl = str_replace('<admin_num>', $adminNum, $tpl);
     $tpl = str_replace('<stud_name>', $studentName, $tpl);
     $tpl = str_replace('<term>', $term, $tpl);
     $tpl = str_replace('<total_debt>', $totalDebt, $tpl);

     send_sms($sender, $tpl, $rec, 'FEES_DEFAULTER');

 }


 function sendFees($adminNum, $studentName, $term, $totalFees, $paid, $bal, $rec){
 	$tpl;
 	if(!isset($_SESSION['fees_tpl'])){
 		// att_tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'FEE'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['fees_tpl'] = $tpl;

 	}else{
 		$tpl = $_SESSION['fees_tpl'];
 	}

	$tpl = str_replace('<admin_num>', $adminNum, $tpl);
     $tpl = str_replace('<stud_name>', $studentName, $tpl);
     $tpl = str_replace('<term>', $term, $tpl);
     $tpl = str_replace('<total_fees>', $totalFees, $tpl);
     $tpl = str_replace('<paid>', $paid, $tpl);
	 $tpl = str_replace('<balance>', $bal, $tpl);

     send_sms($sender, $tpl, $rec, 'FEE');

 }


 function sendRollCall($adminNum, $studentName, $hostelNo, $status, $date, $rec){
 	$tpl;
 	if(!isset($_SESSION['roll_tpl'])){
 		// tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'ROLL_CALL'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['roll_tpl'] = $tpl;

 	}else{
 		$tpl = $_SESSION['roll_tpl'];
 	}

	$tpl = str_replace('<admin_num>', $adminNum, $tpl);
     $tpl = str_replace('<stud_name>', $studentName, $tpl);
     $tpl = str_replace('<hostel_no>', $hostelNo, $tpl);
     $tpl = str_replace('<status>', $status, $tpl);
     $tpl = str_replace('<date>', $date, $tpl);

     send_sms($sender, $tpl, $rec, 'ROLL_CALL');

 }


 function sendExeat($adminNum, $studentName, $hostelNo, $exeatDate, $returnDate, $rec){
 	$tpl;
 	if(!isset($_SESSION['exeat_tpl'])){
 		// att_tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'EXEAT'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['exeat_tpl'] = $tpl;

 	}else{
 		$tpl = $_SESSION['exeat_tpl'];
 	}

	$tpl = str_replace('<admin_num>', $adminNum, $tpl);
     $tpl = str_replace('<stud_name>', $studentName, $tpl);
     $tpl = str_replace('<hostel_no>', $hostelNo, $tpl);
     $tpl = str_replace('<exeat_date>', $exeatDate, $tpl);
     $tpl = str_replace('<return_date>', $returnDate, $tpl);

     send_sms($sender, $tpl, $rec, 'EXEAT');

 }


 function sendClinic($adminNum, $studentName, $status, $rec){
 	$tpl;
 	if(!isset($_SESSION['clinic_tpl'])){
 		// att_tpl does not exist in session ... retreive it.

		$query = "SELECT tpl FROM templTable WHERE tpl_type = 'CLINIC'";
     	$result = mysql_query($query);

     	while($row = mysql_fetch_array($result)) {
            $tpl = $row['tpl'];
     	}

		$_SESSION['clinic_tpl'] = $tpl;

 	}else{
 		$tpl = $_SESSION['clinic_tpl'];
 	}

	$tpl = str_replace('<admin_num>', $adminNum, $tpl);
     $tpl = str_replace('<stud_name>', $studentName, $tpl);
     $tpl = str_replace('<status>', $status, $tpl);


     send_sms($sender, $tpl, $rec, 'CLINIC');

 }
?>
