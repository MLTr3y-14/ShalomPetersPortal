<?php
session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'formatstring.php';
	include 'function.php';
	//session_start();
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}

       $query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];


        $i = 1;
		$entrydate = $_POST['receiptdate'];
		//$entrydate ='2011-09-01';
       $studentname = $_POST['studentname'];

       $pieces = explode("_", $studentname);
       //$studentname = "OBAFEMI MARTINS";
	   $query = "select AdmissionNo from tbstudentmaster where Stu_Full_Name = '$studentname' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                          $result = mysql_query($query,$conn);
						   $row = mysql_fetch_array($result);
						   //$AdmnNo = $row["AdmissionNo"];
						    //$AdmnNo = '56372'.'-' .'104';
						   $AdmnNo = $pieces[1];
	   $studentclass = $_POST['studentclass'];
	      $query = "select ID from tbclassmaster where Class_Name = '$studentclass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                          $result = mysql_query($query,$conn);
						   $row = mysql_fetch_array($result);
						   $studentclassID = $row["ID"];
		$query = "select ChargeName from tbclasscharges where ClassID = '$studentclassID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID' order by ChargeName";
		                 $j = 1;
                          $result = mysql_query($query,$conn);
	                          $num_rows = mysql_num_rows($result);
	                                    if ($num_rows > 0 ) {
					           while ($row = mysql_fetch_array($result))
							   {
							  $ChargeName = $row["ChargeName"];
							     $ChargeName_array[$j] = $ChargeName;
								    $j++;
							                     }
										}
        $chargelength = $_POST['chargeinputlength'.$i];
	   for( $i = 1; $i <= $chargelength; $i++ ){
		   if(isset( $_POST['StudentChargeA'.$i]))
			{
				$studentcharge = $_POST['StudentChargeA'.$i];
				 $studentpayment = $_POST['StudentPaymentA'.$i];
				  $studentdiscount = $_POST['StudentDiscountA'.$i];
				if($studentpayment == ''){
					 $studentpayment = 0;
			} 
			    if($studentdiscount == ''){
					 $studentdiscount = 0;
			} 
			   $studentbalance = $studentcharge - $studentpayment;
							  $ChargeName = $ChargeName_array[$i];
							   $query = "select ID from tbchargemaster where ChargeName = '$ChargeName' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											    $result = mysql_query($query,$conn);
												$row = mysql_fetch_array($result);
												$ChargeID = $row["ID"];
									 //$query = "select EntryDate from entrydate where ID = '1'";
									 $query = "select EntryDate from entrydate where AdmissionNo ='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
											    $result = mysql_query($query,$conn);
												$row = mysql_fetch_array($result);
												$EntryDate = $row["EntryDate"];

														//$query = "select ID from tbfeepayment where ChargeID = '$ChargeID' and ReceiptNo ='$AdmnNo' and LastEntryDate='$Entrydate'";
														$query = "select ID from tbfeepayment2 where ChargeID = '$ChargeID' and ReceiptNo ='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														$result = mysql_query($query,$conn);
	                                                       $num_rows = mysql_num_rows($result);
	                                                         if ($num_rows > 0 ) {
														  $query = "update tbfeepayment2 set PaidAmount ='$studentpayment',Balance ='$studentbalance',Discount ='$studentdiscount',LastEntryDate ='$entrydate' where ChargeID = '$ChargeID' and ReceiptNo ='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														  $result = mysql_query($query,$conn);
														  $query = "update entrydate set EntryDate ='$entrydate' where AdmissionNo='$AdmnNo' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
														   $result = mysql_query($query,$conn);
														 //  $query = "insert into tbfeepayment2(ChargeID,ReceiptNo) Values ('$ChargeID','$AdmnNo')";
				                           //$result = mysql_query($query,$conn);
															        }
															     else {
	$query = "insert into tbfeepayment2(ChargeID,Amount,Balance,Discount,PaidAmount,ReceiptNo,LastEntryDate,Session_ID,Term_ID) Values ('$ChargeID','$studentcharge','$studentbalance','$studentdiscount','$studentpayment','$AdmnNo','$entrydate','$Session_ID','$Term_ID')";
				//$query = "insert into tbfeepayment2(ChargeID,ReceiptNo) Values ('$ChargeID','$AdmnNo')";
				                           $result = mysql_query($query,$conn);
				
				                           // $query = "select * from entrydate where AdmissionNo ='$AdmnNo'";
											  //  $result = mysql_query($query,$conn);
											  //  $num_rows = mysql_num_rows($result);
	                                                        // if ($num_rows > 0 ) {
						$query = "insert into entrydate(EntryDate,AdmissionNo,Session_ID,Term_ID) Values ('$entrydate','$AdmnNo','$Session_ID','$Term_ID')";
											    $result = mysql_query($query,$conn);
											               // }
												
															                                 //}
																                    }
														                      // }
															              // }
							                                             //}
	                                                                  }
			    }
																	   
?>