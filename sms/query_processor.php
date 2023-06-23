<?php
global $conn;
global $dbname;
include_once 'sms_sender.php';

 $sender = $_REQUEST['sender'];
 $msg = $_REQUEST['msg'];

 if(isSenderValid($sender)){ // if the phone number is validated
     $msgOpts = explode(" ", $msg);

     $keyword = $msgOpts[1];
     $adminNum = $msgOpts[2];

     if($keyword == 'ATT')
         processATT($adminNum, $sender);
     else if($keyword == 'FEE')
         processFEE($adminNum, $sender);
     else if($keyword == 'RST'){
         if($msgOpts.length() > 3)
             processRST_SUBJ($adminNum, $sender, $msgOpts[3]);
         else
             processRST($adminNum, $sender);
     }
 }
 
 function isSenderValid($sender){
	//Check if the phone no. is valid, is valid return true else return false
     return false;
 }

 function processATT($adminNum, $sender){
	include '../library/config.php';
	include '../library/opendb.php';
     //query here to be supplied by Johnson...
     $selDate;       //Current Date
     $class;         //Student Date
     $studentName;   //Student Name
     $status;        //Student Attendance Status in full (e.g Present, Absent, half day present etc)
     $total;   		 //Total Present (Format : Total days present / total school days
     $recipient;	 // Parent Phone No.
     $sender = 'Edmins';		// 
     

     // getting template string...

     $query = "SELECT tpl FROM templTable WHERE tpl_type = 'ATT'";
     $result = mysqli_query($query);
     $tpl;
     while($row = mysqli_fetch_array($result)) {
            $tpl = $row['tpl'];

     }

     $tpl = str_replace('<date>', $selDate, $tpl);
     $tpl = str_replace('<class>', $class, $tpl);
     $tpl = str_replace('<name>', $name, $tpl);
     $tpl = str_replace('<status>', $status, $tpl);
     $tpl = str_replace('<total>', $total, $tpl);
     $tpl = str_replace('<admin_num>', $adminNum, $tpl);

     send_sms($sender, $tpl, $recipient, 'ATT');
 }

 function processRST($adminNum, $sender, $term){
	include '../library/config.php';
	include '../library/opendb.php';
     //The information contain here should not be changed
	 if($term =="1st Term"){
	 	$ExamId = 1;
		$exam = "First Term Exam";
	 }elseif($term =="2nd Term";
	 	$ExamId = 2;
		$exam = "Second Term Exam";
	 }elseif($term =="3rd Term";
	 	$ExamId = 3;
		$exam = "Third Term Exam";
	 }
	 
	 $query = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo='$adminNum'";
	 $result = mysqli_query($conn,$query);
	 $dbarray = mysqli_fetch_array($result);
	 $RegNo  = $dbarray['Stu_Regist_No'];
	 $studentName  = strtoupper($dbarray['Stu_Full_Name']);

	 $query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo'";
	 $result = mysqli_query($conn,$query);
	 $dbarray = mysqli_fetch_array($result);
	 $recipient  = $dbarray['Gr_Ph'];
				
     $total = Get_Student_Avg_Score($adminNum, $ExamId, $Term);    //Total Average Score
     $sender = 'Edmins';
	
     $query = "SELECT tpl FROM templTable WHERE tpl_type = 'RST'";
     $result = mysqli_query($query);
     $tpl;
     while($row = mysqli_fetch_array($result)) {
         $tpl = $row['tpl'];
     }

     $tpl = str_replace('<exam_name>', $exam, $tpl);
     $tpl = str_replace('<total>', $total, $tpl);
     $tpl = str_replace('<name>', $studentName, $tpl);
     $tpl = str_replace('<admin_num>', $adminNum, $tpl);

     send_sms($sender, $tpl, $recipient, 'RST');
 }

 function processRST_SUBJ($adminNum, $subject_code, $sender, $term){
	include '../library/config.php';
	include '../library/opendb.php';
     
	 if($term =="1st Term"){
	 	$ExamId = 1;
		$exam = "First Term Exam";
	 }elseif($term =="2nd Term";
	 	$ExamId = 2;
		$exam = "Second Term Exam";
	 }elseif($term =="3rd Term";
	 	$ExamId = 3;
		$exam = "Third Term Exam";
	 }
	 
	 $query = "select Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo='$adminNum'";
	 $result = mysqli_query($conn,$query);
	 $dbarray = mysqli_fetch_array($result);
	 $RegNo  = $dbarray['Stu_Regist_No'];
	 $studentName  = strtoupper($dbarray['Stu_Full_Name']);

	 $query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo'";
	 $result = mysqli_query($conn,$query);
	 $dbarray = mysqli_fetch_array($result);
	 $recipient  = $dbarray['Gr_Ph'];
	 
	 $query = "select ID,Subj_name from tbsubjectmaster where ShortName='$subject_code'";
	 $result = mysqli_query($conn,$query);
	 $dbarray = mysqli_fetch_array($result);
	 $SubjID  = $dbarray['ID'];
	 $subject  = $dbarray['Subj_name'];
	
     $avg =  Get_Subject_Avg_Score($adminNum, $ExamId, $SubjID, $Term);  	//Average Score
     $sender = 'Edmins';

     // getting template string...

     $query = "SELECT tpl FROM templTable WHERE tpl_type = 'RST'";
     $result = mysqli_query($query);
     $tpl;
     while($row = mysqli_fetch_array($result)) {
            $tpl = $row['tpl'];

     }

     $tpl = str_replace('<exam_name>', $exam, $tpl);
     $tpl = str_replace('<average>', $avg, $tpl);
     $tpl = str_replace('<subject>', $subject, $tpl);
     $tpl = str_replace('<name>', $studentName, $tpl);
     $tpl = str_replace('<admin_num>', $adminNum, $tpl);

     send_sms($sender, $tpl, $recipient, 'RST');
 }

 function processFEE($adminNum, $sender){
 	include '../library/config.php';
	include '../library/opendb.php';
	
	//GET ACTIVE TERM
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysqli_query($conn,$query2);
	$dbarray2 = mysqli_fetch_array($result2);
	$term  = $dbarray2['Section'];
	
	$query2 = "select Stu_Class from tbstudentmaster where AdmissionNo ='$adminNum'";
	$result2 = mysqli_query($conn,$query2);
	$dbarray2 = mysqli_fetch_array($result2);
	$ClassId  = $dbarray2['Stu_Class'];
	
	$query = "select Stu_Type,Stu_Regist_No,Stu_Full_Name from tbstudentmaster where AdmissionNo='$adminNum'";
	$result = mysqli_query($conn,$query);
	$dbarray = mysqli_fetch_array($result);
	$RegNo  = $dbarray['Stu_Regist_No'];
	$StuType = $dbarray['Stu_Type'];
	$studentName  = strtoupper($dbarray['Stu_Full_Name']);

	$query = "select Gr_Ph from tbstudentdetail where Stu_Regist_No='$RegNo'";
	$result = mysqli_query($conn,$query);
	$dbarray = mysqli_fetch_array($result);
	$recipient  = $dbarray['Gr_Ph'];
	
	$BalFined = 0;
	$FinedPaid = 0;
	$FinedPayable = 0;
	
	$query3 = "select * from tbreceipt where AdmnNo='$adminNum' And Term ='$term' And Status ='0'";
	$result3 = mysqli_query($conn,$query3);
	$dbarray3 = mysqli_fetch_array($result3);
	$ReceiptNo  = $dbarray3['ID'];
	$FeeDate  = $dbarray3['FeeDate'];
	$PayMode  = $dbarray3['PayMode'];
	$Remarks  = $dbarray3['Remarks'];
	$FinedPayable  = $dbarray3['FinePayable'];
	$FinedPaid  = $dbarray3['FinePaid'];
	$BalFined = $FinedPayable - $FinedPaid;

	$counter = 0;
	$TotalAmt = 0;
	$TotalPay = 0;
	$TotalPaid= 0;
	$TotalBal= 0;
	$GrandPayable = 0;
	$GrandPaid = 0;
	$GrandBal = 0;
										 
	$query3 = "select chargeID from tbstudentcharges where sType = '$StuType'";
	$result3 = mysqli_query($conn,$query3);
	$num_rows3 = mysqli_num_rows($result3);
	if ($num_rows3 > 0 ) {
		while ($row3 = mysqli_fetch_array($result3)) 
		{
			$counter = $counter+1;
			$chargeID = $row3["chargeID"];
			
			$query4 = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$adminNum' And Term = '$term' And Status = '0') And ChargeID = '$chargeID'";
			$result4 = mysqli_query($conn,$query4);
			$dbarray4 = mysqli_fetch_array($result4);
			$AmtPayable  = $dbarray4['Payable'];
			$PaidAmount  = $dbarray4['PaidAmount'];
			if($PaidAmount ==""){
				$PaidAmount  = "0";
			}
			$Balance = 0;
			$query5 = "select ChargeName, Amount from tbclasscharges where ClassID='$ClassId' And ChargeName IN (Select ChargeName from tbchargemaster where ID ='$chargeID' )";
			$result5 = mysqli_query($conn,$query5);
			$dbarray5 = mysqli_fetch_array($result5);
			$Amount  = $dbarray5['Amount'];	
			$ChargeName  = $dbarray5['ChargeName'];
						
			if($AmtPayable ==""){
				$AmtPayable  = $dbarray5['Amount'];
				
			}
			$Balance = $AmtPayable - $PaidAmount;
			$TotalAmt = $TotalAmt +$Amount;
			$TotalPay = $TotalPay +$AmtPayable;
			$TotalPaid = $TotalPaid +$PaidAmount;
			$TotalBal = $TotalBal +$Balance;

			$GrandPayable = $TotalPay + $FinedPayable;
			$GrandPaid = $TotalPaid + $FinedPaid;
			$GrandBal = $TotalBal + $BalFined;
		}
	 }
	 
     $totalFee = $GrandPayable	// Total Fee
     $Paid = $GrandPaid	// Amount paid
     $bal = $GrandBal		// Balance
	 
     $sender = "iSchoolM";


     // getting template string...

     $query = "SELECT tpl FROM templTable WHERE tpl_type = 'FEE'";
     $result = mysqli_query($query);
     $tpl;
     while($row = mysqli_fetch_array($result)) {
            $tpl = $row['tpl'];

     }

     $tpl = str_replace('<term>', $term, $tpl);
     $tpl = str_replace('<name>', $studentName, $tpl);
     $tpl = str_replace('<total_fee>', $totalFee, $tpl);
     $tpl = str_replace('<paid>', $paid, $tpl);
     $tpl = str_replace('<balance>', $bal, $tpl);
     $tpl = str_replace('<admin_num>', $adminNum, $tpl);

     send_sms($sender, $tpl, $recipient, 'FEE');
 }

?>
