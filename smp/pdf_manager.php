<?php
    require "classes/Application.php";


	
	function getSingleApplication($admNo) {
        include '../library/config.php';
		include '../library/opendb.php';
        $query = "SELECT * FROM tbstudentmaster WHERE AdmissionNo = '".$admNo."'";
        $result = mysql_query($query,$conn);
        // or die ("Query Failed ".mysql_error());
		$appl = null;

        while($row = mysql_fetch_array($result)){

            $appl = new Application();
            $appl->AdmisDate = $row['Stu_Date_of_Admis'];
			$appl->Stu_Class = $row['Stu_Class'];
            $appl->Stu_Sec = $row['Stu_Sec'];
            $appl->Stu_Full_Name = $row['Stu_Full_Name'];
			$appl->Stu_DOB = $row['Stu_DOB'];
            $appl->Stu_Gender = $row['Stu_Gender'];
            $appl->Stu_Address = $row['Stu_Address'];
			$appl->Stu_City = $row['Stu_City'];
			$appl->Stu_State = $row['Stu_State'];
			$appl->Stu_Phone = $row['Stu_Phone'];
			$appl->Stu_Email = $row['Stu_Email'];
			$appl->Stu_Reg_Fee = $row['Stu_Reg_Fee'];
			$appl->MaritalStatus = $row['MaritalStatus'];
			$appl->Stu_Photo = $row['Stu_Photo'];
			$appl->AdmissionNo = $row['AdmissionNo'];
			$appl->Country = $row['Country'];
			$appl->Nationality = $row['Nationality'];
			$appl->LocalGov = $row['LocalGov'];
			$appl->IdenType = $row['IdenType'];
			$appl->IdenNo = $row['IdenNo'];
			$appl->IssueDate = $row['IssueDate'];
			$appl->IssuePlace = $row['IssuePlace'];
			$appl->IssueAuth = $row['IssueAuth'];
			$appl->Emergency = $row['Emergency'];
			
           
        }
        closeDB($conn);
		
		$query2 = "SELECT Stu_Regist_No FROM tbstudentmaster WHERE AdmissionNo = '".$admNo."'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$RegNo  = $dbarray['Stu_Regist_No'];
		
		$query = "SELECT * FROM tbstudentdetail WHERE Stu_Regist_No = '".$RegNo."'";
        $result = mysql_query($query,$conn);
        // or die ("Query Failed ".mysql_error());
		$appl = null;

        while($row = mysql_fetch_array($result)){

            $appl = new Application();
            $appl->Programme = $row['Programme'];
			$appl->Level = $row['Level'];
			$appl->PayMode = $row['PayMode'];
            $appl->PayTerms = $row['PayTerms'];
            $appl->Occupation = $row['Occupation'];
			$appl->EduLevel = $row['EduLevel'];
            $appl->isEmploy = $row['isEmploy'];
            $appl->Employer = $row['Employer'];
			$appl->BibleSchool = $row['BibleSchool'];
			$appl->WhenGive_Life = $row['WhenGive_Life'];
			$appl->WhereGive_Life = $row['WhereGive_Life'];
			$appl->WhenBaptized = $row['WhenBaptized'];
			$appl->WhereBaptized = $row['WhereBaptized'];
			$appl->HowLong_mem = $row['HowLong_mem'];
			$appl->Hobbies = $row['Hobbies'];
			$appl->Interest = $row['Interest'];
			$appl->RefName = $row['RefName'];
			$appl->RefProf = $row['RefProf'];
			$appl->RefeAcq = $row['RefeAcq'];
			$appl->RefTelNo = $row['RefTelNo'];
			$appl->RefAddress = $row['RefAddress'];
        }
        closeDB($conn);
        return $appl;
    }	

       /*
DB Closing method.
Pass the conn variable
obtained through initDB().
*/
    function closeDB($conn){
    	
        mysql_close($conn);
    }
	
	function recontructDate($fmrDate){
		$arr = explode("/", $fmrDate);
		
		$newDate = $arr[1].'/'.$arr[0].'/'.$arr[2];
		
		return $newDate;
		
	}
	
?>
