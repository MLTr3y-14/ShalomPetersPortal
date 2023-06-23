
<?PHP 
session_start();
global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'library/dsndatabase.php';
	include 'formatstring.php';
	include 'function.php';
	//require_once 'excel_reader2.php';
	//include 'excel_reader2.php';
// filename: upload.processor.php 
$_SESSION['ResultUploadmsg']= '';
// first let's set some variables 

// make a note of the current working directory, relative to root. 
//include 'formatstring.php';
//require_once 'excel_reader2.php';
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

$query = "select ID from session where Status='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Session_ID  = $dbarray['ID'];
	
	$query = "select ID from section where Active='1'";
		$result = mysql_query($query,$conn);
		$dbarray = mysql_fetch_array($result);
		$Term_ID  = $dbarray['ID'];


// make a note of the directory that will recieve the uploaded file 
//$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self ; 
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'results/';

// make a note of the location of the upload form in case we need it 
$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'updatestudentresult.php?subpg=Update%20Student%20Result'; 
  
// fieldname used within the file <input> of the HTML form 
$fieldname = 'file';

// make a note of the location of the success page 
$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'updatestudentresult.php?subpg=Update%20Student%20Result'; 


// Now let's deal with the upload 

// possible PHP upload errors 
$errors = array(1 => 'php.ini max file size exceeded', 
				2 => 'html form max file size exceeded', 
				3 => 'file upload was only partial', 
				4 => 'no file was attached'); 

// check the upload form was actually submitted else print the form 
$fieldname = 'file';
/*if(isset($_POST['SchID']))
{
	$SchID = formatHTMLStr($_POST['SchID']);
}*/


isset($_POST['excel_data_upload']) 
	or error('the upload form is needed', $uploadForm); 

// check for PHP's built-in uploading errors 
($_FILES[$fieldname]['error'] == 0) 
	or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 
	 
// check that the file we are working on really was the subject of an HTTP upload 
@is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
	or error('not an HTTP upload', $uploadForm); 
	 
// validation... since this is an image upload script we should run a check   
// to make sure the uploaded file is in fact an image. Here is a simple check: 
// getimagesize() returns false if the file tested is not an image. 
//@getimagesize($_FILES[$fieldname]['tmp_name']) 
//	or error('only image uploads are allowed', $uploadForm); 
	 
// make a unique filename for the uploaded file and check it is not already 
// taken... if it is already taken keep trying until we find a vacant one 
// sample filename: 1140732936-filename.jpg 
$now = time(); 
while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['name'])) 
{ 
	$now++; 
} 

// now let's move the file to its final location and allocate the new filename to it
//include 'library/config.php';
//include 'library/opendb.php';
$FullFileName = $now.'-'.$_FILES[$fieldname]['name'];

@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
	or error('receiving directory insuffiecient permission', $uploadForm);
// Save File Full Name to Database
// Save File Full Name to Database
//$instr = fopen($uploadFilename,"r");
//$image = addslashes(fread($instr,filesize($uploadFilename)));
//fclose($instr);
if(!get_magic_quotes_gpc())
{
    $FullFileName = addslashes($FullFileName);
	$_SESSION['fullfilename']= $FullFileName;
}else{
	
	$_SESSION['fullfilename']= $FullFileName;
}

$fullfilenamedirectory = $uploadsDirectory.$FullFileName;
chmod($fullfilenamedirectory,0755);
//$query = "update tbschool set Logo = '$FullFileName' where ID = '3'";
	//mysql_query($query) or die('Error, query failed');
	//$errormsg = "<font color = blue size = 1>Image uploaded successfully.</font>";
	//header('Location: ' . $uploadSuccess);

	
		
		 $OptSelExams= $_SESSION['OptSelExams'];
		 $OptClass = $_SESSION['OptClass'];
		 $OptSelSubject = $_SESSION['OptSelSubject'];
		// $FullFileName = $_SESSION['fullfilename'];
		

		 require_once 'excel_reader2.php';
         //$data = new Spreadsheet_Excel_Reader($handle);
		$data = new Spreadsheet_Excel_Reader("results/".$FullFileName);
		//$data = new Spreadsheet_Excel_Reader("results/Excel_1999-2003_Data_Upload_Test.xls");
		// $data = new Spreadsheet_Excel_Reader("results/1484311597-Result_Upload_Test.xls");
		 
		//echo $Subj_name;
		 
		   $x = 3;
         // $query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$SelectedClassID'";
		  $query = "select Stu_Full_Name,AdmissionNo from tbstudentmaster where Stu_Class = '$OptClass' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
									$result = mysql_query($query,$conn);
									$num_rows = mysql_num_rows($result);
									if ($num_rows > 0 ) {
										while ($row = mysql_fetch_array($result)) 
										{
											$FullName = $row["Stu_Full_Name"];
										     $StuID   = $row["AdmissionNo"];
											 $x++;
											//echo $x;
						                     $counter1 = 0;
						  
						 // $query = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$ClassId' And ExamId = '$ExamId' And SubjectId = '$SubjID'";
						  $query5 = "select ID,ResultType,Percentage from tbclassexamsetup where ClassId ='$OptClass' And ExamId = '$OptSelExams' And SubjectId = '$OptSelSubject' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
						  $result5 = mysql_query($query5,$conn);
									$num_rows5 = mysql_num_rows($result5);
									if ($num_rows5 > 0 ) {
										while ($row5 = mysql_fetch_array($result5)) 
										{
											
											$SetupId = $row5["ID"];
											$ResultType = $row5["ResultType"];
											$Percentage = $row5["Percentage"];
											$TotalPer = $TotalPer +$Percentage;
											$SetupId2[$counter1] = $SetupId;
											$counter1 = $counter1 +1;
											
										}
									             }
									
								 
								 $ClassId= $OptClass;
						         $ExamId = $OptSelExams;
								 //$StuID = $OptSelStudent;
								 $SubjID = $OptSelSubject;
						          $TotalPer1 = 0;
								  $TotalMark = 0;
								   $y = 1;
								  
						  for($i=0; $i < sizeof($SetupId2); $i++) {
							  $ResultTypeId = $SetupId2[$i]; 
							      $y++;
								 // $w=0;
								//echo $ResultTypeId.' ';
								  								//   }
                   for($w=0;$w<count($data->sheets);$w++) // Loop to get all sheets in a file.
					
                            { 
                                   
								   if(count($data->sheets[$w][cells])>0) // checking sheet not empty
                                      {
									   
                                          // echo "hi";
										   $marks1 = $data->sheets[$w][cells][$x][$y];
										   	
											//echo $marks1.' '.$OptClass.' '.$OptSelExams.' '.$OptSelSubject.' '.$StuID.'-';
											//echo $ResultTypeId.' '.$marks1.'-';
											//echo $marks1.' ';
                                            //echo $OptClass;
											//echo $OptSelExams;
											//echo $OptSelSubject;
											 // echo $StuID;
                                                     }
 
  $query9 = "select ID from tbstudentperformance where ResultTypeId ='$ResultTypeId' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
	      $result9 = mysql_query($query9,$conn);
	          $num_rows9 = mysql_num_rows($result9);
									if ($num_rows9 > 0 ) {
						$query10 = "Update tbstudentperformance set Marks='$marks1' where ResultTypeId ='$ResultTypeId
						' and AdmnNo ='$StuID' and Session_ID = '$Session_ID' and Term_ID = '$Term_ID'";
                            $result10 = mysql_query($query10,$conn);
									}
									else{							
											$query11 = "Insert into tbstudentperformance(class_id,AdmnNo,ExamId,SubjectId,ResultTypeId,Marks,Session_ID,Term_ID) Values ('$ClassId','$StuID','$ExamId','$SubjID','$ResultTypeId','$marks1','$Session_ID','$Term_ID')";
										
										$result11 = mysql_query($query11,$conn);
										
									            
									                                       }
							                                           }
										                          }
															//echo $OptClass;
											//echo $OptSelExams;
											//echo $OptSelSubject;
											 // echo $StuID;
	  
									                        }

										          }

		

	
	$ResultUploadmsg = "<font color = blue size = 1>Result uploaded successfully.</font>";
	$_SESSION['ResultUploadmsg']= $ResultUploadmsg;
	header('Location: ' . $uploadSuccess);	
	
	exit;
// If you got this far, everything has worked and the file has been successfully saved. 
// We are now going to redirect the client to a success page. 


// The following function is an error handler which is used 
// to output an HTML error page if the file upload fails 
function error($error, $location, $seconds = 5) 
{ 
	header("Refresh: $seconds; URL=\"$location\""); 
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'."\n". 
	'"http://www.w3.org/TR/html4/strict.dtd">'."\n\n". 
	'<html lang="en">'."\n". 
	'    <head>'."\n". 
	'        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">'."\n\n". 
	'        <link rel="stylesheet" type="text/css" href="stylesheet.css">'."\n\n". 
	'    <title>Upload error</title>'."\n\n". 
	'    </head>'."\n\n". 
	'    <body>'."\n\n". 
	'    <div id="Upload">'."\n\n". 
	'        <h1>Upload failure</h1>'."\n\n". 
	'        <p>An error has occured: '."\n\n". 
	'        <span class="red">' . $error . '...</span>'."\n\n". 
	'         The upload form is reloading</p>'."\n\n". 
	'     </div>'."\n\n". 
	'</html>'; 
	     exit; 
    } // end error handler	

?>