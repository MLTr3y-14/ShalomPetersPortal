<?php
/*$StartDate = 20121028;
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
*/
function Date_Comparison($frDate,$toDate)
{
	$fr_datearr = explode("-",DB_date($frDate));
	$to_datearr = explode("-",DB_date($toDate));
	$fr_dateInt = mktime(0,0,0,$fr_datearr[1],$fr_datearr[2],$fr_datearr[0]);
	$to_dateInt = mktime(0,0,0,$to_datearr[1],$to_datearr[2],$to_datearr[0]);
	if($to_dateInt > $fr_dateInt){
		return "true";
	}else{
		return "false";
	}
}
		
//function Get_Active_Session()
//{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select ID from session where Status = '1'";
	$result = mysql_query($query,$conn);
	$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];
	//$dbarray = mysql_fetch_array($result);
	//$SessionID = $dbarray['ID'];
	//return $SessionID;	
//}

function Get_Active_Term()
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select ID from section where Active = '1'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$TermID = $dbarray['ID'];
	return $TermID;	
}

function DB_date($Sel_Date)
{
	$arrDate=explode ('-', $Sel_Date);
	$day = $arrDate[0];
	$mth = $arrDate[1];
	$yr = $arrDate[2];
	$sDate = $yr."-".$mth."-".$day;
	return $sDate;	
}
function User_date($Sel_Date)
{
	$arrDate=explode ('-', $Sel_Date);
	$day = $arrDate[2];
	$mth = $arrDate[1];
	$yr = $arrDate[0];
	$sDate = $day."-".$mth."-".$yr;
	return $sDate;
}
function Long_date($Sel_Date)
{
	$arrDate=explode ('-', $Sel_Date);
	if($arrDate[2] == "1"){
		$arrDay = "01";
	}elseif($arrDate[2] == "2"){
		$arrDay = "02";
	}elseif($arrDate[2] == "3"){
		$arrDay = "03";
	}elseif($arrDate[2] == "4"){
		$arrDay = "04";
	}elseif($arrDate[2] == "5"){
		$arrDay = "05";
	}elseif($arrDate[2] == "6"){
		$arrDay = "06";
	}elseif($arrDate[2] == "7"){
		$arrDay = "07";
	}elseif($arrDate[2] == "8"){
		$arrDay = "08";
	}elseif($arrDate[2] == "9"){
		$arrDay = "09";
	}elseif($arrDate[2] == "10"){
		$arrDay = "10";
	}elseif($arrDate[2] == "11"){
		$arrDay = "11";
	}elseif($arrDate[2] == "12"){
		$arrDay = "12";
	}elseif($arrDate[2] == "13"){
		$arrDay = "13";
	}elseif($arrDate[2] == "14"){
		$arrDay = "14";
	}elseif($arrDate[2] == "15"){
		$arrDay = "15th";
	}elseif($arrDate[2] == "16"){
		$arrDay = "16";
	}elseif($arrDate[2] == "17"){
		$arrDay = "17";
	}elseif($arrDate[2] == "18"){
		$arrDay = "18";
	}elseif($arrDate[2] == "19"){
		$arrDay = "19";
	}elseif($arrDate[2] == "20"){
		$arrDay = "20";
	}elseif($arrDate[2] == "21"){
		$arrDay = "21";
	}elseif($arrDate[2] == "22"){
		$arrDay = "22";
	}elseif($arrDate[2] == "23"){
		$arrDay = "23";
	}elseif($arrDate[2] == "24"){
		$arrDay = "24";
	}elseif($arrDate[2] == "25"){
		$arrDay = "25";
	}elseif($arrDate[2] == "26"){
		$arrDay = "26";
	}elseif($arrDate[2] == "27"){
		$arrDay = "27";
	}elseif($arrDate[2] == "28"){
		$arrDay = "28";
	}elseif($arrDate[2] == "29"){
		$arrDay = "29";
	}elseif($arrDate[2] == "30"){
		$arrDay = "30";
	}elseif($arrDate[2] == "31"){
		$arrDay = "31";
	}
	
	if($arrDate[1] == "01"){
		$arrMonth = "JAN";
	}elseif($arrDate[1] == "02"){
		$arrMonth = "FEB";
	}elseif($arrDate[1] == "03"){
		$arrMonth = "MAR";
	}elseif($arrDate[1] == "04"){
		$arrMonth = "APR";
	}elseif($arrDate[1] == "05"){
		$arrMonth = "MAY";
	}elseif($arrDate[1] == "06"){
		$arrMonth = "JUN";
	}elseif($arrDate[1] == "07"){
		$arrMonth = "JUL";
	}elseif($arrDate[1] == "08"){
		$arrMonth = "AUG";
	}elseif($arrDate[1] == "09"){
		$arrMonth = "SEP";
	}elseif($arrDate[1] == "10"){
		$arrMonth = "OCT";
	}elseif($arrDate[1] == "11"){
		$arrMonth = "NOV";
	}elseif($arrDate[1] == "12"){
		$arrMonth = "DEC";
	}
	$Dateformat = $arrDay." ".$arrMonth.", ".$arrDate[0];
	return $Dateformat;	
}
function Get_Month_Name($Sel_Date)
{
	if($Sel_Date == "1"){
		$arrMonth = "JANUARY";
	}elseif($Sel_Date == "2"){
		$arrMonth = "FEBRUARY";
	}elseif($Sel_Date == "3"){
		$arrMonth = "MARCH";
	}elseif($Sel_Date == "4"){
		$arrMonth = "APRIL";
	}elseif($Sel_Date == "5"){
		$arrMonth = "MAY";
	}elseif($Sel_Date == "6"){
		$arrMonth = "JUNE";
	}elseif($Sel_Date == "07"){
		$arrMonth = "JULY";
	}elseif($Sel_Date == "8"){
		$arrMonth = "AUGUST";
	}elseif($Sel_Date == "9"){
		$arrMonth = "SEPTEMBER";
	}elseif($Sel_Date == "10"){
		$arrMonth = "OCTOBER";
	}elseif($Sel_Date == "11"){
		$arrMonth = "NOVEMBER";
	}elseif($Sel_Date == "12"){
		$arrMonth = "DECEMBER";
	}
	return $arrMonth;	
}
function Get_Days_In_Month($Mth){
   $Total_Days = 0;
	if($Mth == 1){
		$Total_Days=31;
	}elseif($Mth == 2){
		$Total_Days=29;
	}elseif($Mth == 3){
		$Total_Days=31;
	}elseif($Mth == 4){
		$Total_Days=30;
	}elseif($Mth == 5){
		$Total_Days=31;
	}elseif($Mth == 6){
		$Total_Days=30;
	}elseif($Mth == 7){
		$Total_Days=31;
	}elseif($Mth == 8){
		$Total_Days=31;
	}elseif($Mth == 9){
		$Total_Days=30;
	}elseif($Mth == 10){
		$Total_Days=31;
	}elseif($Mth == 11){
		$Total_Days=30;
	}elseif($Mth == 12){
		$Total_Days=31;
	}
	return $Total_Days;
}	

function date_range($Sel_frDate,$Sel_toDate)
{
	$arrFR=explode ('-', $Sel_frDate);
	$arrTO=explode ('-', $Sel_toDate);
	$CountRow=0;
	if($arrFR[0] == $arrTO[0]){
		if($arrFR[1] == $arrTO[1]){
			if($arrFR[2] <= $arrTO[2]){
				$day_Diff=$arrTO[2] - $arrFR[2];
				$Sel_day=$arrFR[2];
				$Sel_day = $Sel_day -1;
				for($i=0;$i<=$day_Diff;$i++){
					$Sel_day = $Sel_day + 1;
					if($Sel_day<10){
						$Sel_day = '0'.$Sel_day;
					}
					$sel_date = $arrFR[0]."-".$arrFR[1]."-".$Sel_day;
					$arrDateList[]=$sel_date;
					$CountRow = $CountRow+1;
				}
			}
		}elseif($arrFR[1] < $arrTO[1]){
			$mth_Diff=$arrTO[1] - $arrFR[1];
			$Sel_mth=$arrFR[1];
			$Sel_mth = $Sel_mth -1;
			for($i=0;$i<=$mth_Diff;$i++){
				$Sel_mth = $Sel_mth + 1;
				if($Sel_mth <10){
					$Sel_mth ='0'.$Sel_mth;
				}
				//Get the No. of days on the selected month
				$total_day=Get_Days_In_Month($Sel_mth);
				if($Sel_mth < $arrTO[1]){
					$Sel_day  = $arrFR[2]-1;
					$total_day = $total_day - $Sel_day;
				}else{
					$Sel_day = 0;
					$total_day = $arrTO[2];
				}
				for($n=1;$n<=$total_day;$n++){
					$Sel_day = $Sel_day + 1;
					if($Sel_day <10){
						$Sel_day ='0'.$Sel_day;
					}
					$sel_date = $arrFR[0]."-".$Sel_mth."-".$Sel_day;
					$arrDateList[]=$sel_date;
					$CountRow = $CountRow+1;
				}
			}
		}
	}elseif($arrFR[0] < $arrTO[0]){
		$yr_Diff=$arrTO[0] - $arrFR[0];
		$Sel_yr=$arrFR[0];
		$Sel_yr = $Sel_yr -1;
		for($i=0;$i<=$yr_Diff;$i++){
			$Sel_yr = $Sel_yr + 1;
			if($Sel_yr < $arrTO[0]){
				$Sel_mth  = $arrFR[1]-1;
			}else{
				$Sel_mth = 0;
			}
			for($n=1;$n<=12;$n++){
				$Sel_mth = $Sel_mth + 1;
				if($Sel_mth <10){
					$Sel_mth ='0'.$Sel_mth;
				}
				//Get the No. of days on the selected month
				$total_day=Get_Days_In_Month($Sel_mth);
				if($Sel_mth < $arrTO[1] && $Sel_yr < $arrTO[0]){
					$Sel_day  = $arrFR[2]-1;
					$total_day = $total_day - $Sel_day;
				}else{
					$Sel_day = 0;
				}
				for($y=1;$y<=$total_day;$y++){
					$Sel_day = $Sel_day + 1;
					if($Sel_day <10){
						$Sel_day ='0'.$Sel_day;
					}
					$sel_date = $Sel_yr."-".$Sel_mth."-".$Sel_day;
					$arrDateList[]=$sel_date;
					$CountRow = $CountRow+1;
				}
			}
		}
	}
	return $arrDateList;
}
function AllDatabaseName($id){
	$Tablename[0]= "allowancemaster";
	$Tablename[1]= "allowancesetup";
	$Tablename[2]= "bookcondition";
	$Tablename[3]= "calendar";
	$Tablename[4]= "mcity";
	$Tablename[5]= "salary";
	$Tablename[6]= "salarydetail";
	$Tablename[7]= "section";
	$Tablename[8]= "session";
	$Tablename[9]= "tb_slcertificate";
	$Tablename[10]= "tballowancecateg";
	$Tablename[11]= "tbassignduty";
	$Tablename[12]= "tbattendanceemployee";
	$Tablename[13]= "tbattendancestudent";
	$Tablename[14]= "tbauthorlist";
	$Tablename[15]= "tbauthormaster";
	$Tablename[16]= "tbbinding";
	$Tablename[17]= "tbbookauthorlist";
	$Tablename[18]= "tbbookissemp";
	$Tablename[19]= "tbbookissstd";
	$Tablename[20]= "tbbookmst";
	$Tablename[21]= "tbcategorymaster";
	$Tablename[22]= "tbchargedetail";
	$Tablename[23]= "tbchargemaster";
	$Tablename[24]= "tbclasscharges";
	$Tablename[25]= "tbclassexamsetup";
	$Tablename[26]= "tbclassmaster";
	$Tablename[27]= "tbclasssection";
	$Tablename[28]= "tbclasssubjectrelation";
	$Tablename[29]= "tbclassteachersubj";
	$Tablename[30]= "tbclasstimetable";
	$Tablename[31]= "tbclinic";
	$Tablename[32]= "tbcountry";
	$Tablename[33]= "tbcurrency";
	$Tablename[34]= "tbdepartments";
	$Tablename[35]= "tbdesignations";
	$Tablename[36]= "tbdocumentmaster";
	$Tablename[37]= "tbemployeemasters";
	$Tablename[38]= "tbenquiry";
	$Tablename[39]= "tbenquiryeducation";
	$Tablename[40]= "tbenquiryfollowup";
	$Tablename[41]= "tbexaminationmaster";
	$Tablename[42]= "tbexammarkssetup";
	$Tablename[43]= "tbexeathostel";
	$Tablename[44]= "tbfatheroccupation";
	$Tablename[45]= "tbfeepayment";
	$Tablename[46]= "tbgradedetail";
	$Tablename[47]= "tbhostelroom";
	$Tablename[48]= "tbhouse";
	$Tablename[49]= "tblanguage";
	$Tablename[50]= "tbleaveapplication";
	$Tablename[51]= "tbleavemaster";
	$Tablename[52]= "tblectureattendance";
	$Tablename[53]= "tblibcategorymst";
	$Tablename[54]= "tblibfinemst";
	$Tablename[55]= "tblibsubcatmst";
	$Tablename[56]= "tbmonitory";
	$Tablename[57]= "tbmonthlycharges";
	$Tablename[58]= "tbpopupmessage";
	$Tablename[59]= "tbprofile_right";
	$Tablename[60]= "tbprogheader";
	$Tablename[61]= "tbprogreport";
	$Tablename[62]= "tbproskills";
	$Tablename[63]= "tbpublicationplace";
	$Tablename[64]= "tbpublisher";
	$Tablename[65]= "tbreceipt";
	$Tablename[66]= "tbregistration";
	$Tablename[67]= "tbrelation";
	$Tablename[68]= "tbreligionmaster";
	$Tablename[69]= "tbremark";
	$Tablename[70]= "tbrollcall";
	$Tablename[71]= "tbroommaster";
	$Tablename[72]= "tbschool";
	$Tablename[73]= "tbseluserprofile";
	$Tablename[74]= "tbseries";
	$Tablename[75]= "tbstudentcharges";
	$Tablename[76]= "tbstudentdetail";
	$Tablename[77]= "tbstudentmaster";
	$Tablename[78]= "tbstudentperformance";
	$Tablename[79]= "tbstudentroom";
	$Tablename[80]= "tbstudentsubmitedcharges";
	$Tablename[81]= "tbstudentsubmiteddoc";
	$Tablename[82]= "tbsubheader";
	$Tablename[83]= "tbsubjectmaster";
	$Tablename[84]= "tbsupplier";
	$Tablename[85]= "tbteachersduty";
	$Tablename[86]= "tbteachertimetable";
	$Tablename[87]= "tbteacherworkload";
	$Tablename[88]= "tbusermaster";
	$Tablename[89]= "tbuserprofile";
	$Tablename[90]= "templtable";
	$Tablename[91]= "typemaster";
	
	return $Tablename;
}

function GetClassID($ClassName)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select ID from tbclassmaster where Class_Name = '$ClassName'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$f_ClassID = $dbarray['ID'];
	return $f_ClassID;
}
function GetClassName($ClassID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select Class_Name from tbclassmaster where ID = '$ClassID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$Class_Name = $dbarray['Class_Name'];
	return $Class_Name;
}
function GET_DEPT_NAME($DeptID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select Deptname from tbdepartments where ID = '$DeptID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$Deptname = $dbarray['Deptname'];
	return $Deptname;
}
function GET_EMP_NAME($EmpID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select EmpName from tbemployeemasters where ID = '$EmpID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$EmpName = $dbarray['EmpName'];
	return $EmpName;
}

function checkprevious($PreValue)
{
	if($PreValue < 0){
		return 0;
	}else{
		return $PreValue;
	}
}
function GET_TOTAL_STUDENT($ClassID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$numrows = 0;
	$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster Where Stu_Class = '$ClassID'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$numrows = $row4['numrows'];
	return $numrows;
}
function GET_TOTAL_BOY($ClassID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$numrows = 0;
	$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster Where Stu_Class = '$ClassID' And Stu_Gender = 'M'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$numrows = $row4['numrows'];
	return $numrows;
}
function GET_TOTAL_GIRL($ClassID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$numrows = 0;
	$query4   = "SELECT COUNT(*) AS numrows FROM tbstudentmaster Where Stu_Class = '$ClassID' And Stu_Gender = 'F'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$numrows = $row4['numrows'];
	return $numrows;
}
function CHK_SUBJ_TYPE($SubjectID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select Sub_Type from tbsubjectmaster where ID = '$SubjectID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$SubType = $dbarray['Sub_Type'];
	if($SubType == 0){
		return 0;
	}else{
		return 1;
	}
}
function GetSubjectName($SubjID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select Subj_name from tbsubjectmaster where ID = '$SubjID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$Subj_name = $dbarray['Subj_name'];
	return $Subj_name;
}
function GetSubjectID($SubjectName)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select ID from tbsubjectmaster where Subj_name = '$SubjectName'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$SubjectID = $dbarray['ID'];
	return $SubjectID;
}
function GetSubject_SName($SubjID)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select ShortName from tbsubjectmaster where ID = '$SubjID'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$ShortName = $dbarray['ShortName'];
	return $ShortName;
}
function GetTotalAtt($Admno)
{
	include 'library/config.php';
	include 'library/opendb.php';
	
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	$numrows = 0;
	$query4   = "SELECT COUNT(ID) AS numrows FROM tbattendancestudent where AdmnNo = '$Admno' And Status = 'P'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotP = $row4['numrows'];
	
	/*$numrows = 0;
	$query4   = "SELECT COUNT(ID) AS numrows FROM tbattendancestudent where AdmnNo = '$Admno' And Term = '$Activeterm' And Status = 'A.5'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotA5 = $row4['numrows'];
	$TotA5 = $TotA5 /2;
	
	$numrows = 0;
	$query4   = "SELECT COUNT(ID) AS numrows FROM tbattendancestudent where AdmnNo = '$Admno' And Term = '$Activeterm' And Status = 'L.5'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotL5 = $row4['numrows'];
	$TotL5 = $TotL5/2;*/
	$TotalAtt = 0;
	$TotalAtt = $TotP;
	
	return $TotalAtt;
}
function GetTotalAttPresent($Admno,$startdate,$enddate)
{
	include 'library/config.php';
	include 'library/opendb.php';
	$query = "select COUNT(ID) AS numrows from tbattendancestudent where AdmnNo = '$Admno' and Att_date between '$startdate' and '$enddate' And Status = 'P'";
	$result4  = mysql_query($query,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotL5 = $row4['numrows'];
	return $TotL5;
	
}
function GetTotalAttAbsent($Admno,$startdate,$enddate)
{
	include 'library/config.php';
	include 'library/opendb.php';
	$query = "select COUNT(ID) AS numrows from tbattendancestudent where AdmnNo = '$Admno' and Att_date between '$startdate' and '$enddate' And Status = 'A'";
	$result4  = mysql_query($query,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotL5 = $row4['numrows'];
	return $TotL5;
}
function GetTotalEmpAtt($EmpNo)
{
	include 'library/config.php';
	include 'library/opendb.php';
	
	$query2 = "select Section from section where Active = '1'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$Activeterm  = $dbarray2['Section'];
	
	$numrows = 0;
	$query4   = "SELECT COUNT(ID) AS numrows FROM TbAttendanceEmployee where EmpId = '$EmpNo' And Status = 'P'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotP = $row4['numrows'];
	
	/*$numrows = 0;
	$query4   = "SELECT COUNT(ID) AS numrows FROM TbAttendanceEmployee where EmpId = '$EmpNo' And Term = '$Activeterm' And Status = 'A.5'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotA5 = $row4['numrows'];
	$TotA5 = $TotA5 /2;
	
	$numrows = 0;
	$query4   = "SELECT COUNT(ID) AS numrows FROM TbAttendanceEmployee where EmpId = '$EmpNo' And Term = '$Activeterm' And Status = 'L.5'";
	$result4  = mysql_query($query4,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotL5 = $row4['numrows'];
	$TotL5 = $TotL5/2;*/
	$TotalAtt = 0;
	$TotalAtt = $TotP;
	
	return $TotalAtt;
}
function GetTotalAttPresent2($EmpNo,$startdate,$enddate){
include 'library/config.php';
	include 'library/opendb.php';
	$query = "select COUNT(ID) AS numrows from tbattendanceemployee where EmpId = '$EmpNo' and Att_date between '$startdate' and '$enddate' And Status = 'P'";
	$result4  = mysql_query($query,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotL5 = $row4['numrows'];
	return $TotL5;
}
function GetTotalAttAbsent2($EmpNo,$startdate,$enddate)
{
	include 'library/config.php';
	include 'library/opendb.php';
	$query = "select COUNT(ID) AS numrows from tbattendanceemployee where EmpId = '$EmpNo' and Att_date between '$startdate' and '$enddate' And Status = 'A'";
	$result4  = mysql_query($query,$conn) or die('Error, query failed');
	$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
	$TotL5 = $row4['numrows'];
	return $TotL5;
}
function GetExamName($ExamId)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select ExamName from tbexaminationmaster where ExamID = '$ExamId'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$ExamName = $dbarray['ExamName'];
	return $ExamName;
}
function GetSubjClass($SubjId)
{
	include 'library/config.php';
	include 'library/opendb.php';

	$query = "select ClassId from tbclasssubjectrelation where SubjectId = '$SubjId'";
	$result = mysql_query($query,$conn);
	$dbarray = mysql_fetch_array($result);
	$ClassId = $dbarray['ClassId'];
	return $ClassId;
}
function ChkDefaulters($ClassID,$StuType)
{
	include 'library/config.php';
	include 'library/opendb.php';
	
	//$query3 = "select FinePayable,FinePaid from tbreceipt where AdmnNo='$AdmissionNo' And Status ='0'";
	//$result3 = mysql_query($query3,$conn);
	//$dbarray3 = mysql_fetch_array($result3);
	//$FinedPayable  = $dbarray3['FinePayable'];
	//$FinedPaid  = $dbarray3['FinePaid'];
	//$BalFined = $FinedPayable - $FinedPaid;
      $stu = $StuType;
	$counter = 0;
	$TotalAmt = 0;
	$TotalPay = 0;
	$TotalPaid= 0;
	$TotalBal= 0;
	
	$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' order by Stu_Full_Name";
	$result2 = mysql_query($query2,$conn);
			$num_rows2 = mysql_num_rows($result2);
				if ($num_rows2 > 0 ) {
				while ($row2 = mysql_fetch_array($result2)) 
					{
						$StuType = "";
						$RegNo = $row2["Stu_Regist_No"];
						$Stu_Sec = $row2["Stu_Sec"];
						$Stu_Full_Name = $row2["Stu_Full_Name"];
					    $AdmissionNo = $row2["AdmissionNo"];
						
						$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
	
	                           $query9 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
															$Amount = $row9["Amount"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															$Balance = $Amount - $PaidAmount;
															$AmtPayable = $Amount;
			/*$query5 = "select ChargeName, Amount from tbclasscharges where ClassID='$ClassID' And ChargeName IN (Select ChargeName from tbchargemaster where ID ='$chargeID' )";
			$result5 = mysql_query($query5,$conn);
			$dbarray5 = mysql_fetch_array($result5);
			$Amount  = $dbarray5['Amount'];	
			$ChargeName  = $dbarray5['ChargeName'];
			$isDisplay = "false";
						
			if($AmtPayable ==""){
				$AmtPayable  = $dbarray5['Amount'];
				//$Balance = $AmtPayable - $PaidAmount;
			}
			if($PaidAmount < $AmtPayable){
				$Balance = $AmtPayable - $PaidAmount;
			}*/
			//$TotalAmt = $TotalAmt +$Amount;
			$TotalPay = $TotalPay +$AmtPayable;
			$TotalPaid = $TotalPaid +$PaidAmount;
			$TotalBal = $TotalBal +$Balance;
		}
	}
					}
				}
		
	 if($TotalBal > 0){
	 	return $TotalBal;
	 }
	 else{
	 	return $TotalBal = 0;
	 }
	 
}
function ChkClassTotalFeePaid($ClassID)
{
	include 'library/config.php';
	include 'library/opendb.php';
	
	
      //$stu = $StuType;
	  $StuType = 0;
	$counter = 0;
	$TotalAmt = 0;
	$TotalPay = 0;
	$TotalPaid= 0;
	$TotalBal= 0;
	
	$query2 = "select Stu_Regist_No,Stu_Sec,Stu_Full_Name,AdmissionNo,Stu_Type from tbstudentmaster where Stu_Class = '$ClassID' order by Stu_Full_Name";
	$result2 = mysql_query($query2,$conn);
			$num_rows2 = mysql_num_rows($result2);
				if ($num_rows2 > 0 ) {
				while ($row2 = mysql_fetch_array($result2)) 
					{
						$StuType = "";
						$RegNo = $row2["Stu_Regist_No"];
						$Stu_Sec = $row2["Stu_Sec"];
						$Stu_Full_Name = $row2["Stu_Full_Name"];
					    $AdmissionNo = $row2["AdmissionNo"];
						
						$query3 = "select EntryDate from entrydate where AdmissionNo = '$AdmissionNo'";
										$result3 = mysql_query($query3,$conn);
										$row3 = mysql_fetch_array($result3);
												$EntryDate = $row3["EntryDate"];
	
	                           $query9 = "select ChargeName,Amount from tbclasscharges where ClassID = '$ClassID'";
	                                            $result9 = mysql_query($query9,$conn);
	                                         $num_rows9 = mysql_num_rows($result9);
	                                                         if ($num_rows9 > 0) {
		                                         while ($row9 = mysql_fetch_array($result9)) 
	                                              	{
		                                                	$counter = $counter+1;
			
			                                                $ChargeName = $row9["ChargeName"];
															$Amount = $row9["Amount"];
			                                                $query8 = "select ID from tbchargemaster where ChargeName = '$ChargeName'";
			                                                  $result8 = mysql_query($query8,$conn);
				                                               $dbarray8 = mysql_fetch_array($result8);
				                                                  $chargeID  = $dbarray8['ID'];
			
			          $query7 = "select Amount,Balance,PaidAmount from tbfeepayment2 where ReceiptNo = '$AdmissionNo' and ChargeID = '$chargeID' and LastEntryDate='$EntryDate'";
															$result7 = mysql_query($query7,$conn);
															$dbarray7 = mysql_fetch_array($result7);
															$AmtPayable  = $dbarray7['Amount'];
															$PaidAmount  = $dbarray7['PaidAmount'];
															$Balance  = $dbarray7['Balance'];
															//$chargeID  = $dbarray4['ChargeID'];
															if($PaidAmount ==""){
																$PaidAmount  = "0";
															}
															$Balance = $Amount - $PaidAmount;
															$AmtPayable = $Amount;
			
			$TotalPay = $TotalPay +$AmtPayable;
			$TotalPaid = $TotalPaid +$PaidAmount;
			$TotalBal = $TotalBal +$Balance;
		}
	}
					}
				}
		
	 	return $TotalPaid;
	
}
function CalFeeAmount($ReceiptNo)
{
	include 'library/config.php';
	include 'library/opendb.php';
	
	$counter = 0;
	$TotalAmt = 0;
	$TotalPay = 0;
	$TotalPaid= 0;
	$TotalBal= 0;
	
	$query3 = "select PaidAmount from tbfeepayment where ReceiptNo = '$ReceiptNo'";
	$result3 = mysql_query($query3,$conn);
	$num_rows3 = mysql_num_rows($result3);
	if ($num_rows3 > 0) {
		while ($row3 = mysql_fetch_array($result3)) 
		{
			$counter = $counter+1;
			$PaidAmount = $row3["PaidAmount"];
	 		$TotalPaid = $TotalPaid + $PaidAmount;
		}
	}
	return $TotalPaid;
}
function update_Monitory($Status,$platform,$sysmod)
{
	session_start();
	include 'library/config.php';
	include 'library/opendb.php';
	$dat = date_default_timezone_set('Y'.'-'.'m'.'-'.'d');
	
	//$usrname = $_SESSION['username'];
	//$Ddpass = $_SESSION['password'];
	//$q = "select EmpID from tbusermaster where UserName = '$usrname' and UserPassword = '$Ddpass'";
	//$result = mysql_query($q,$conn);
	//$dbarray = mysql_fetch_array($result);
	//$sEmpID  = $dbarray['EmpID'];
	
	
	$Validation = "false";
	if($Status!="" and $platform!="" and $sysmod!=""){
		$Validation = "true";
	}
	if($Validation == "true"){						
		$q = "update tbmonitory set Status = '$Status', Platform = '$platform', Module = '$sysmod' where EmpID = '$sEmpID' and sDate = '$dat' and Platform !='-' order by ID desc";
		$result = mysql_query($q,$conn);									
		return "true";
	}else{
		return "false";
	}
}
function Get_Student_Avg_Score($AdmNo, $ExamId, $Activeterm)
{
	include 'library/config.php';
	include 'library/opendb.php';
			
	$query2 = "select Stu_Class from tbstudentmaster where AdmissionNo ='$AdmNo'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$ClassId  = $dbarray2['Stu_Class'];
		
	$SumFinalMarks = 0;
	$SumMaxMarks = 0;
	$SumPercent = 0;
	$SumMaxPercent = 0;
	$countsubj = 0;
	$query0 = "select ID from tbsubjectmaster where ID IN (select SubjectId from tbclasssubjectrelation where ClassId = '$ClassId') order by Subj_Priority";
	$result0 = mysql_query($query0,$conn);
	$num_rows0 = mysql_num_rows($result0);
	if ($num_rows0 > 0 ) {
		while ($row0 = mysql_fetch_array($result0)) 
		{
			$subjID = $row0["ID"];
			
			$FinalMarks = 0;
			$numrows = 0;
			$query4   = "SELECT COUNT(*) AS numrows FROM tbclassexamsetup where ClassId='$ClassId' and SubjectId='$subjID' and ExamId='$ExamId'";
			$result4  = mysql_query($query4,$conn) or die('Error, query failed');
			$row4     = mysql_fetch_array($result4, MYSQL_ASSOC);
			$Tot_TD = $row4['numrows'];
											
			$i=0;
			$query3 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId='$ClassId' and SubjectId='$subjID' and ExamId='$ExamId'";
			$result3 = mysql_query($query3,$conn);
			$num_rows = mysql_num_rows($result3);
			if ($num_rows > 0 ) {
				while ($row = mysql_fetch_array($result3)) 
				{
					$arr_Exam_Id[$i] = $row["ID"];
					$arr_Exam_Setup[$i][1] = $row["ResultType"];
					$arr_Exam_Setup[$i][2] = $row["Percentage"];
					$i = $i+1;
				}
			}
			$query = "select MaxMark from tbexammarkssetup where ClassID='$ClassId' And ExamID='$ExamId' And SubjectID='$subjID'";
			$result = mysql_query($query,$conn);
			$dbarray = mysql_fetch_array($result);
			$MaxPercent  = $dbarray['MaxMark'];		
			
			$SubjMaxMark = 0;
			$sMaxMark = 0;
			for($i=1;$i<=$Tot_TD;$i++)
			{
				$query = "select Marks from tbstudentperformance where class_id = '$ClassId' and AdmnNo = '$AdmNo' and ExamId = '$ExamId' And SubjectId = '$subjID' And ResultTypeId = '".$arr_Exam_Id[$i-1]."' And Term = '$Activeterm'";
				
				$result = mysql_query($query,$conn);
				$dbarray = mysql_fetch_array($result);
				$sMarks  = $dbarray['Marks'];
				if($sMarks == 0){
					$sMarks = '';
				}
				if($sMarks !=""){
					$FinalMarks = $FinalMarks + $sMarks;
					$SubjMaxMark = $SubjMaxMark +$arr_Exam_Setup[$i-1][2];
					$FinalPercentage = ($FinalMarks / $SubjMaxMark) * 100;
					$FinalPercentage = $FinalPercentage;
				}
			}
			
			if($FinalMarks == 0){
				$SubjMaxMark = "-";
				$FinalMarks = "";
				$FinalPercentage = "";
			}else{
				$sMaxMark = $SubjMaxMark;
				$SubjMaxMark = " / ".$SubjMaxMark;
				$SumPercent = $SumPercent + $FinalPercentage;
				$SumMaxPercent = $SumMaxPercent +$MaxPercent; 
			}
			$SumFinalMarks = $SumFinalMarks +$FinalMarks;
			$SumMaxMarks = $SumMaxMarks + $sMaxMark;
		}
	}
	$AvgActualMark= 0;
	$AvgMark = ($SumPercent / $SumMaxPercent) * 100;
	$AvgActualMark = ($SumFinalMarks / $SumMaxMarks) * 100;
	return number_format($AvgActualMark,2);
}
function Get_Subject_Avg_Score($AdmNo, $ExamId, $SubjID, $Activeterm)
{
	include 'library/config.php';
	include 'library/opendb.php';
			
	$query2 = "select Stu_Class from tbstudentmaster where AdmissionNo ='$AdmNo'";
	$result2 = mysql_query($query2,$conn);
	$dbarray2 = mysql_fetch_array($result2);
	$ClassId  = $dbarray2['Stu_Class'];
	
	//Get Records

	$FinalMarks = 0;
	$query1 = "select Marks from tbstudentperformance where class_id = '$ClassId' and AdmnNo = '$AdmNo' and ExamId = '$ExamId' And SubjectId = '$SubjID' And Term = '$Activeterm'";
	$result1 = mysql_query($query1,$conn);
	$num_rows1 = mysql_num_rows($result1);
	if ($num_rows1 > 0 ) {
		while ($row1 = mysql_fetch_array($result1)) 
		{
			$sMarks = $row1["Marks"];
			$FinalMarks = $FinalMarks + $sMarks;
		}
	}
	//Get Total Mark Allocated
	$TotalPer = 0;
	$query5 = "select Percentage from tbclassexamsetup where ClassId ='$ClassId' And SubjectId ='$SubjID' And ExamId = '$ExamId'";
	$result5 = mysql_query($query5,$conn);
	$num_rows5 = mysql_num_rows($result5);
	if ($num_rows5 > 0 ) {
		while ($row5 = mysql_fetch_array($result5)) 
		{
			$Percentage = $row5["Percentage"];
			$TotalPer = $TotalPer +$Percentage;
			//$FinalMarks = $TotalPer;
		}
	}
	if($FinalMarks == 0){
		$FinalMarks = "";
		$TotalPer = "";
	}else{
		$FinalPercentage = ($FinalMarks / $TotalPer) * 100;
		$TotalPer = " / ".$TotalPer;
	}
	return $FinalPercentage;
}
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

function ConvertToNo($string)
{
	

$arrKey = array();
$str = explode("-",$string);
$lengthofnum = strlen($str[0]);
$arr1 = str_split($str[0]);

for($i = 0; $i < $lengthofnum; $i++){
$arrKey[$i]= 2;	
if($arr1[$i] == "A"){
		$arr1[$i] = "1.";
	}elseif($arr1[$i] == "B"){
		$arr1[$i] = "2.";
	}
	elseif($arr1[$i] == "C"){
		$arr1[$i] = "3.";
	}
	elseif($arr1[$i] == "D"){
		$arr1[$i] = "4.";
	}
	elseif($arr1[$i] == "E"){
		$arr1[$i] = "5.";
	}elseif($arr1[$i] == "F"){
		$arr1[$i] = "6.";
	}elseif($arr1[$i] == "G"){
		$arr1[$i] = "7.";
	}elseif($arr1[$i] == "H"){
		$arr1[$i] = "8.";
	}elseif($arr1[$i] == "I"){
		$arr1[$i] = "9.";
	}elseif($arr1[$i] == "J"){
		$arr1[$i] = "10.";
	}elseif($arr1[$i] == "K"){
		$arr1[$i] = "11.";
	}elseif($arr1[$i] == "L"){
		$arr1[$i] = "12.";
	}elseif($arr1[$i] == "M"){
		$arr1[$i] = "13.";
	}elseif($arr1[$i] == "N"){
		$arr1[$i] = "14.";
	}elseif($arr1[$i] == "O"){
		$arr1[$i] = "15.";
	}elseif($arr1[$i] == "P"){
		$arr1[$i] = "16.";
	}elseif($arr1[$i] == "Q"){
		$arr1[$i] = "17.";
	}elseif($arr1[$i] == "R"){
		$arr1[$i] = "18.";
	}elseif($arr1[$i] == "S"){
		$arr1[$i] = "19.";
	}elseif($arr1[$i] == "T"){
		$arr1[$i] = "20.";
	}elseif($arr1[$i] == "U"){
		$arr1[$i] = "21.";
	}elseif($arr1[$i] == "V"){
		$arr1[$i] = "22.";
	}elseif($arr1[$i] == "W"){
		$arr1[$i] = "23.";
	}elseif($arr1[$i] == "X"){
		$arr1[$i] = "24.";
	}elseif($arr1[$i] == "Y"){
		$arr1[$i] = "25.";
	}elseif($arr1[$i] == "Z"){
		$arr1[$i] = "26.";
	}
	else{
		  $arr1[$i] = $arr1[$i].'.';
		  $arrKey[$i]= 1;
		  }
}
$admNO = '';
for($i = 0; $i < $lengthofnum; $i++){
$admNO = $admNO.$arr1[$i];
}
$admNO2 = explode(".",$admNO);
$admNO3 = '';
for($i = 0; $i < $lengthofnum; $i++){
$admNO3 = $admNO3.$admNO2[$i];
}
$admNO3 = $admNO3.'-'.$str[1];
return $admNO3;	
}

function ConvertToNoKey($string)
{
$str = explode("-",$string);
$lengthofnum = strlen($str[0]);
$arr1 = str_split($str[0]);

for($i = 0; $i < $lengthofnum; $i++){
$arrKey[$i]= 2;	
if($arr1[$i] == "A"){
		$arr1[$i] = "1.";
	}elseif($arr1[$i] == "B"){
		$arr1[$i] = "2.";
	}
	elseif($arr1[$i] == "C"){
		$arr1[$i] = "3.";
	}
	elseif($arr1[$i] == "D"){
		$arr1[$i] = "4.";
	}
	elseif($arr1[$i] == "E"){
		$arr1[$i] = "5.";
	}elseif($arr1[$i] == "F"){
		$arr1[$i] = "6.";
	}elseif($arr1[$i] == "G"){
		$arr1[$i] = "7.";
	}elseif($arr1[$i] == "H"){
		$arr1[$i] = "8.";
	}elseif($arr1[$i] == "I"){
		$arr1[$i] = "9.";
	}elseif($arr1[$i] == "J"){
		$arr1[$i] = "10.";
	}elseif($arr1[$i] == "K"){
		$arr1[$i] = "11.";
	}elseif($arr1[$i] == "L"){
		$arr1[$i] = "12.";
	}elseif($arr1[$i] == "M"){
		$arr1[$i] = "13.";
	}elseif($arr1[$i] == "N"){
		$arr1[$i] = "14.";
	}elseif($arr1[$i] == "O"){
		$arr1[$i] = "15.";
	}elseif($arr1[$i] == "P"){
		$arr1[$i] = "16.";
	}elseif($arr1[$i] == "Q"){
		$arr1[$i] = "17.";
	}elseif($arr1[$i] == "R"){
		$arr1[$i] = "18.";
	}elseif($arr1[$i] == "S"){
		$arr1[$i] = "19.";
	}elseif($arr1[$i] == "T"){
		$arr1[$i] = "20.";
	}elseif($arr1[$i] == "U"){
		$arr1[$i] = "21.";
	}elseif($arr1[$i] == "V"){
		$arr1[$i] = "22.";
	}elseif($arr1[$i] == "W"){
		$arr1[$i] = "23.";
	}elseif($arr1[$i] == "X"){
		$arr1[$i] = "24.";
	}elseif($arr1[$i] == "Y"){
		$arr1[$i] = "25.";
	}elseif($arr1[$i] == "Z"){
		$arr1[$i] = "26.";
	}
	else{
		  $arr1[$i] = $arr1[$i].'.';
		  $arrKey[$i]= 1;
		  
	}

}
$admNO = '';
for($i = 0; $i < $lengthofnum; $i++){

$admNO = $admNO.$arr1[$i];

}
return $admNO;
}

function NoKeyFlag($string)
{
$arrKey = array();
$str = explode("-",$string);
$lengthofnum = strlen($str[0]);
$arr1 = str_split($str[0]);
for($i = 0; $i < $lengthofnum; $i++){
$arrKey[$i]= 2;	
if($arr1[$i] == "A"){
		$arr1[$i] = "1.";
	}elseif($arr1[$i] == "B"){
		$arr1[$i] = "2.";
	}
	elseif($arr1[$i] == "C"){
		$arr1[$i] = "3.";
	}
	elseif($arr1[$i] == "D"){
		$arr1[$i] = "4.";
	}
	elseif($arr1[$i] == "E"){
		$arr1[$i] = "5.";
	}elseif($arr1[$i] == "F"){
		$arr1[$i] = "6.";
	}elseif($arr1[$i] == "G"){
		$arr1[$i] = "7.";
	}elseif($arr1[$i] == "H"){
		$arr1[$i] = "8.";
	}elseif($arr1[$i] == "I"){
		$arr1[$i] = "9.";
	}elseif($arr1[$i] == "J"){
		$arr1[$i] = "10.";
	}elseif($arr1[$i] == "K"){
		$arr1[$i] = "11.";
	}elseif($arr1[$i] == "L"){
		$arr1[$i] = "12.";
	}elseif($arr1[$i] == "M"){
		$arr1[$i] = "13.";
	}elseif($arr1[$i] == "N"){
		$arr1[$i] = "14.";
	}elseif($arr1[$i] == "O"){
		$arr1[$i] = "15.";
	}elseif($arr1[$i] == "P"){
		$arr1[$i] = "16.";
	}elseif($arr1[$i] == "Q"){
		$arr1[$i] = "17.";
	}elseif($arr1[$i] == "R"){
		$arr1[$i] = "18.";
	}elseif($arr1[$i] == "S"){
		$arr1[$i] = "19.";
	}elseif($arr1[$i] == "T"){
		$arr1[$i] = "20.";
	}elseif($arr1[$i] == "U"){
		$arr1[$i] = "21.";
	}elseif($arr1[$i] == "V"){
		$arr1[$i] = "22.";
	}elseif($arr1[$i] == "W"){
		$arr1[$i] = "23.";
	}elseif($arr1[$i] == "X"){
		$arr1[$i] = "24.";
	}elseif($arr1[$i] == "Y"){
		$arr1[$i] = "25.";
	}elseif($arr1[$i] == "Z"){
		$arr1[$i] = "26.";
	}else{
		  $arr1[$i] = $arr1[$i].'.';
		  $arrKey[$i]= 1;
		  }
}
//$admNO = '';
$keyNO = '';
for($i = 0; $i < $lengthofnum; $i++){
//$admNO = $admNO.$arr1[$i];
$keyNO = $keyNO.$arrKey[$i];
}
return($keyNO);
}

function ReturnPart2($string)
{
$str = explode("-",$string);
$AdmnNOPart2 = str_split($str[1]);
return $AdmnNOPart2;
}

function ConvertBack2AdmNO($admNO,$keyNO)
{
//$arr12 = explode("-",$admNO);
$str = explode("-",$admNO);
$lengthofnum = strlen($str[0]);
$arr12 = str_split($str[0]);
$lengthofnum = sizeof($arr12);
//$keyNO2 = array();
$keyNO2 = str_split($keyNO);

for($i = 0; $i < $lengthofnum; $i++){
if($keyNO2[$i] == 2){ 

if($arr12[$i] == "1"){
		$arr12[$i] = "A";
	}elseif($arr12[$i] == "2"){
		$arr12[$i] = "B";
	}
	elseif($arr12[$i] == "3"){
		$arr12[$i] = "C";
	}
	elseif($arr12[$i] == "4"){
		$arr12[$i] = "D";
	}
	elseif($arr12[$i] == "5"){
		$arr12[$i] = "E";
	}elseif($arr12[$i] == "6"){
		$arr12[$i] = "F";
	}elseif($arr12[$i] == "7"){
		$arr12[$i] = "G";
	}elseif($arr12[$i] == "8"){
		$arr12[$i] = "H";
	}elseif($arr12[$i] == "9"){
		$arr12[$i] = "I";
	}elseif($arr12[$i] == "10"){
		$arr12[$i] = "J";
	}elseif($arr12[$i] == "11"){
		$arr12[$i] = "K";
	}elseif($arr12[$i] == "12"){
		$arr12[$i] = "L";
	}elseif($arr12[$i] == "13"){
		$arr12[$i] = "M";
	}elseif($arr12[$i] == "14"){
		$arr12[$i] = "N";
	}elseif($arr12[$i] == "15"){
		$arr12[$i] = "O";
	}elseif($arr12[$i] == "16"){
		$arr12[$i] = "P";
	}elseif($arr12[$i] == "17"){
		$arr12[$i] = "Q";
	}elseif($arr12[$i] == "18"){
		$arr12[$i] = "R";
	}elseif($arr12[$i] == "19"){
		$arr12[$i] = "S";
	}elseif($arr12[$i] == "20"){
		$arr12[$i] = "T";
	}elseif($arr12[$i] == "21"){
		$arr12[$i] = "U";
	}elseif($arr12[$i] == "22"){
		$arr12[$i] = "V";
	}elseif($arr12[$i] == "23"){
		$arr12[$i] = "W";
	}elseif($arr12[$i] == "24"){
		$arr12[$i] = "X";
	}elseif($arr12[$i] == "25"){
		$arr12[$i] = "Y";
	}elseif($arr12[$i] == "26"){
		$arr12[$i] = "Z";
	}
}
}

$RadmNO = '';
for($i = 0; $i < $lengthofnum; $i++){

$RadmNO = $RadmNO.$arr12[$i];
}
$RadmNO = $RadmNO.$str[1];
return($RadmNO); 
}
	
?>