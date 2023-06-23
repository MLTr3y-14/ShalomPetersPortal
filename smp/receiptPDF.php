<?php

require_once('pdf/config/lang/eng.php');
require_once('pdf/tcpdf.php');
require_once('pdf_manager.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header($admNo) {
		include '../library/config.php';
		include '../library/opendb.php';
		//$query = "select EmpName,EmpCity,EmpState,EmpPhone,EmpOtherContact,EmpDept,Photo,Email from tbemployeemasters where ID='$EmpID'";
		$query = "select Stu_Photo from tbstudentmaster where AdmissionNo='$admNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$Stu_Photo  = $dbarray['Stu_Photo'];
		
		// Logo
		$this->Image('http://localhost/sols/ad/Images/logo.jpg', 10, 8, 15);
		$this->Image('http://localhost/sols/ad/Images/address.jpg', 60, 8, 85);
		// Set font
		$this->SetFont('helvetica', 'B', 6);
		// Move to the right 
		$this->Cell(40);
		// Logo
		$this->Image('http://localhost/sols/ad/Images/uploads/'.$Stu_Photo, 180, 8, 15);
		// Line break
		$this->Ln(20);
	}
	
	// Page footer
	public function Footer() {
		//Position at 1.5 cm from bottom
        $this->SetY(-15);
		
        //times italic 8
        $this->SetFont('times', 'I', 10);
		
		$this->SetTextColor(73, 124, 0);
		
		//Slogan
        $this->Cell(200, 10, 'SkoolNET::Communion Pastoral & Missions College', 0, 0, 'L');
        
		$this->SetX(-210);
		
		$this->SetTextColor(0, 0, 0);
		
		//Page number
        $this->Cell(200, 10, 'Processed on : '.date('F d, Y'), 0, 0, 'R');
	}
	
    public function app_name($admNo) {
		include '../library/config.php';
		include '../library/opendb.php';
		//$query = "select EmpName,EmpCity,EmpState,EmpPhone,EmpOtherContact,EmpDept,Photo,Email from tbemployeemasters where ID='$EmpID'";
		$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo='$admNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$Stu_Full_Name  = $dbarray['Stu_Full_Name'];
		
        $this->SetLineStyle(array('width'=>0.5, 'cap'=>'butt', 'join'=>'miter', 'dash'=>4, 'color'=>array(255, 0, 0)));
        $this->SetFillColor(255, 255, 128);
        $this->SetTextColor(0, 0, 128);
        
        $text = "STUDENT\'S PAYMENT RECEIPT";
		$this->SetFont('times', 'I', 27);
        
        $this->Cell(0, 0, $Stu_Full_Name, 1, 1, 'C', 1, 0);
        
        $this->Ln();
    }
	
	function details($admNo){
		
		include '../library/config.php';
		include '../library/opendb.php';
		include '../formatstring.php';
		include '../function.php';
				
		$this->SetFillColor(255, 255, 255);
		
		$this->Cell(5);
		$this->SetFont('times', 'B', 11);
		$this->Cell(10, 6, "Sr.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(60, 6, "Particulars", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "Amount", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "Payables", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "Paid Amount", 0, 0, 'L', true);
		$this->Ln();
		 
		$counter = 0;
		$TotalAmt = 0;
		$TotalPay = 0;
		$TotalPaid= 0;
		$GrandPayable = 0;
		$GrandPaid = 0;
		
		$query = "select Stu_Class,Stu_Sec from tbstudentmaster where AdmissionNo='$admNo'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$StuClass  = $dbarray['Stu_Class'];
		$Stu_Section  = $dbarray['Stu_Sec'];
	
		$query1 = "select ChargeName from tbclasscharges where ClassID IN (Select Stu_Class from tbstudentmaster where AdmissionNo = '$admNo')";
		$result1 = mysql_query($query1,$conn);
		$num_rows1 = mysql_num_rows($result1);
		if ($num_rows1 > 0 ) {
			while ($row1 = mysql_fetch_array($result1)) 
			{
				$ChargeName = $row1["ChargeName"];
				$chargeID = GetChargeID($ChargeName);
				
				$query = "select Payable,PaidAmount from tbfeepayment where ReceiptNo IN (Select ID From tbreceipt where AdmnNo = '$AdmissionNo' And Term = '$Activeterm' And Status = '0') And ChargeID = '$chargeID'";
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$AmtPayable  = $dbarray['Payable'];
				$PaidAmount  = $dbarray['PaidAmount'];
				if($PaidAmount ==""){
					$PaidAmount  = "0";
				}
				
				$query3 = "select ChargeName, Amount from tbclasscharges where ClassID='$StuClass' And ChargeName IN (Select ChargeName from tbchargemaster where ID ='$chargeID' )";
				$result3 = mysql_query($query3,$conn);
				$dbarray3 = mysql_fetch_array($result3);	
				$ChargeName  = $dbarray3['ChargeName'];
				if($ChargeName !=""){
					$counter = $counter+1;
					$Amount  = $dbarray3['Amount'];
					if($AmtPayable ==""){
						$AmtPayable  = $dbarray3['Amount'];
					}
					$TotalAmt = $TotalAmt +$Amount;
					$TotalPay = $TotalPay +$AmtPayable;
					$TotalPaid = $TotalPaid +$PaidAmount;
						  
					//$counter;	$ChargeName;	$Amount;	$AmtPayable;	$PaidAmount;
					$this->Cell(5);
					$this->SetFont('times', 'B', 11);
					$this->Cell(10, 6, $counter, 0, 0, 'L', true);
					$this->SetFont('times', '', 11);
					$this->Cell(60, 6, $ChargeName, 0, 0, 'L', true);
					$this->SetFont('times', '', 11);
					$this->Cell(30, 6, $Amount, 0, 0, 'L', true);
					$this->SetFont('times', '', 11);
					$this->Cell(30, 6, $AmtPayable, 0, 0, 'L', true);
					$this->SetFont('times', '', 11);
					$this->Cell(30, 6, $PaidAmount, 0, 0, 'L', true);
					$this->Ln();
					
				}
			 }
		 }
		$GrandPayable = $TotalPay + $FinedPayable;
		$GrandPaid = $TotalPaid + $FinedPaid;
		$this->Ln();
		$this->Cell(5);
		$this->SetFont('times', 'B', 11);
		$this->Cell(10, 6, "", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(60, 6, "TOTAL", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "N".number_format($TotalAmt,2), 1, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "N".number_format($TotalPay,2), 1, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "N".number_format($TotalPaid,2), 1, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(5);
		$this->SetFont('times', 'B', 11);
		$this->Cell(10, 6, "", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(60, 6, "", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "FINE", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "N".number_format($FinedPayable,2), 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "N".number_format($FinedPaid,2), 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(5);
		$this->SetFont('times', 'B', 11);
		$this->Cell(10, 6, "", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(60, 6, "", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "GRAND TOTAL", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "N".number_format($GrandPayable,2), 1, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(30, 6, "N".number_format($GrandPaid,2), 1, 0, 'L', true);
		$this->Ln();

		//TOTAL = number_format($TotalAmt,2);	number_format($TotalPay,2); number_format($TotalPaid,2)
		//FINE = $FinedPayable	$FinedPaid;
		//GRAND TOTAL = number_format($GrandPayable,2); 	number_format($GrandPaid,2);
		
		//$this->Ln();
		//$this->Ln();
		
		//Background color
       

		
		
		/*$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "City", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, GetCityName($dbarray['EmpCity']), 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "State", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['EmpState'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Phone ", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['EmpPhone'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Other Contact", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['EmpOtherContact'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Department", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, GET_DEPT_NAME($dbarray['EmpDept']), 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Email.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Email'], 0, 0, 'L', true);
		$this->Ln();*/
		
		
		
		$this->Ln();
		$this->Ln();
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Generated Date", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, date('F d, Y'), 0, 0, 'L', true);
		$this->Ln();
		
		$this->Ln();
		
	}

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SkoolNET: ADMIN');
$pdf->SetTitle('STUDENT\'S PAYMENT RECEIPT');
$pdf->SetSubject('STUDENT\'S PAYMENT RECEIPT');


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'STUDENT\'S PAYMENT RECEIPT', 'STUDENT\'S PAYMENT RECEIPT');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// remove default header/footer
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l); 

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', 'BI', 12);

// add a page
$pdf->AddPage();

$appl = getSingleApplication($_GET['admNo']);

$pdf->Header($_GET['admNo']);
// print a line using Cell()
//$pdf->Image('img/');
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 0, 'STUDENT\'S PAYMENT RECEIPT', 0, 3, 'C');

$pdf->Ln();

$pdf->app_name($_GET['admNo']);

$pdf->details($_GET['admNo']);






// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('STUDENT\'S RECEIPT.pdf', 'I');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
