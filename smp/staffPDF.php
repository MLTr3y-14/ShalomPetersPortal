<?php


require_once('pdf/config/lang/eng.php');
require_once('pdf/tcpdf.php');
require_once('pdf_manager.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header($EmpID) {
		include '../library/config.php';
		include '../library/opendb.php';
		//$query = "select EmpName,EmpCity,EmpState,EmpPhone,EmpOtherContact,EmpDept,Photo,Email from tbemployeemasters where ID='$EmpID'";
		$query = "select Photo from tbemployeemasters where ID='$EmpID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$StaffPhoto  = $dbarray['Photo'];
		
		// Logo
		$this->Image('http://localhost/sols/ad/Images/logo.jpg', 10, 8, 15);
		$this->Image('http://localhost/sols/ad/Images/address.jpg', 60, 8, 85);
		// Set font
		$this->SetFont('helvetica', 'B', 6);
		// Move to the right 
		$this->Cell(40);
		// Logo
		$this->Image('http://localhost/sols/ad/Images/uploads/'.$StaffPhoto, 180, 8, 15);
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
	
    public function app_name($EmpID) {
		include '../library/config.php';
		include '../library/opendb.php';
		//$query = "select EmpName,EmpCity,EmpState,EmpPhone,EmpOtherContact,EmpDept,Photo,Email from tbemployeemasters where ID='$EmpID'";
		$query = "select EmpName from tbemployeemasters where ID='$EmpID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);	
		$EmpName  = $dbarray['EmpName'];
		
        $this->SetLineStyle(array('width'=>0.5, 'cap'=>'butt', 'join'=>'miter', 'dash'=>4, 'color'=>array(255, 0, 0)));
        $this->SetFillColor(255, 255, 128);
        $this->SetTextColor(0, 0, 128);
        
        $text = "STAFF RECORDS";
		$this->SetFont('times', 'I', 27);
        
        $this->Cell(0, 0, $EmpName, 1, 1, 'C', 1, 0);
        
        $this->Ln();
    }
	
	function details($EmpID){
		
		include '../library/config.php';
		include '../library/opendb.php';
		include '../formatstring.php';
		include '../function.php';
		$query = "select EmpName,EmpCity,EmpState,EmpPhone,EmpOtherContact,EmpDept,Photo,Email from tbemployeemasters where ID='$EmpID'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		
		//$this->Ln();
		//$this->Ln();
		
		//Background color
        $this->SetFillColor(255, 255, 255);

		$this->Cell(30);
		$this->SetFont('times', 'B', 11);
		$this->Cell(40, 6, "Staff Name", 0, 0, 'L', true);
		$this->SetFont('times', '', 11);
		$this->Cell(70, 6, $dbarray['EmpName'], 0, 0, 'L', true);
		$this->Ln();
		
		$this->Cell(30);
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
		$this->Ln();
		
		
		
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

$appl = getSingleApplication($_GET['eid']);

$pdf->Header($_GET['eid']);
// print a line using Cell()
//$pdf->Image('img/');
$pdf->SetFont('times', 'B', 11);
$pdf->Cell(0, 0, 'STAFF RECORDS', 0, 3, 'C');

$pdf->Ln();

$pdf->app_name($_GET['eid']);

$pdf->details($_GET['eid']);






// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('LEARNER\'S PERMIT.pdf', 'I');

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
