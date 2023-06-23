<?php


require_once('pdf/config/lang/eng.php');
require_once('pdf/tcpdf.php');
require_once('pdf_manager.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header($Admno) {
		include '../library/config.php';
		include '../library/opendb.php';
		$query = "select Stu_Photo from tbstudentmaster where AdmissionNo='$Admno'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$StuFilename  = $dbarray['Stu_Photo'];
		
		// Logo
		$this->Image('http://localhost/sols/ad/Images/logo.jpg', 10, 8, 15);
		$this->Image('http://localhost/sols/ad/Images/address.jpg', 60, 8, 85);
		// Set font
		$this->SetFont('helvetica', 'B', 6);
		// Move to the right 
		$this->Cell(40);
		// Logo
		$this->Image('http://localhost/sols/ad/Images/uploads/'.$StuFilename, 180, 8, 15);
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
	
    public function app_name($Admno) {
		include '../library/config.php';
		include '../library/opendb.php';
		$query = "select Stu_Full_Name from tbstudentmaster where AdmissionNo='$Admno'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$Stu_Full_Name  = $dbarray['Stu_Full_Name'];
		
        $this->SetLineStyle(array('width'=>0.5, 'cap'=>'butt', 'join'=>'miter', 'dash'=>4, 'color'=>array(255, 0, 0)));
        $this->SetFillColor(255, 255, 128);
        $this->SetTextColor(0, 0, 128);
        
        $text = "STUDENT ADMISSION RECORDS";
		$this->SetFont('times', 'I', 27);
        
        $this->Cell(0, 0, $Stu_Full_Name, 1, 1, 'C', 1, 0);
        
        $this->Ln();
    }
	
	function details($Admno){
		
		include '../library/config.php';
		include '../library/opendb.php';
		include '../formatstring.php';
		include '../function.php';
		$query = "select * from tbstudentmaster where AdmissionNo='$Admno'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$RegNo  = $dbarray['Stu_Regist_No'];	
		
		//$this->Ln();
		//$this->Ln();
		
		//Background color
        $this->SetFillColor(255, 255, 255);

		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Admission Date", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, long_date($dbarray['Stu_Date_of_Admis']), 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Faculty", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, GetClassName($dbarray['Stu_Class']), 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Section", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Stu_Sec'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Date of Birth", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, long_date($dbarray['Stu_DOB']), 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Gender", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Stu_Gender'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Address", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Stu_Address'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Contact No.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Stu_Phone'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Email.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Stu_Email'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Registration Fee.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, "N".$dbarray['Stu_Reg_Fee'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Marital Status.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['MaritalStatus'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Admission No.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['AdmissionNo'], 0, 0, 'L', true);
		$this->Ln();
		
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Country.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Country'], 0, 0, 'L', true);
		$this->Ln();
		
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Nationality.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['Nationality'], 0, 0, 'L', true);
		$this->Ln();
		$this->Ln();
		$this->Cell(30);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, "..::APPLICATION DETAILS::..", 0, 0, 'L', true);
		$this->Ln();
		$this->Ln();
		$query2 = "select * from tbstudentdetail where Stu_Regist_No='$RegNo'";
		$result2 = mysql_query($query2,$conn);
		$dbarray2 = mysql_fetch_array($result2);
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Programme Applied For .", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['Programme'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Level Applied?.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['Level'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Mode of Payment.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['PayMode'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Payment Terms.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['PayTerms'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Level of Education.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['EduLevel'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(80, 6, "When did you give your life to Christ?.", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['WhenGive_Life'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(80, 6, "Where did you give your life to Christ?", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['WhereGive_Life'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(80, 6, "When were you baptized by immersion?", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['WhenBaptized'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(80, 6, "Where do you currently worship?", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['WhereBaptized'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Languages Spoken", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['Languages'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(80, 6, "Areas of special interest in ministry", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['Interest'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(50, 6, "Relevant Gifts/Talents", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['Talents'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Ln();
		$this->Ln();
		$this->Ln();
		$this->Ln();
		$this->Cell(30);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, "..::REFEREE/SPONSOR::..", 0, 0, 'L', true);
		$this->Ln();
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Referee Name?", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['RefName'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Profession", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['RefProf'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Years of Acquaintance", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['RefeAcq'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Contact address and email", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['RefAddress'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Telephone", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray2['RefTelNo'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Ln();	
	}

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('VIO-DVLA');
$pdf->SetTitle('LEARNER\'S PERMIT');
$pdf->SetSubject('LEARNER\'S PERMIT');


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'FEDERAL REPUBLIC OF NIGERIA', 'PROVISIONAL LEARNER\'S PERMIT');

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
$pdf->Cell(0, 0, 'STUDENT ADMISSION RECORD', 0, 3, 'C');

$pdf->Ln();

$pdf->app_name($_GET['admNo']);

$pdf->details($_GET['admNo']);






// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('LEARNER\'S PERMIT.pdf', 'I');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
