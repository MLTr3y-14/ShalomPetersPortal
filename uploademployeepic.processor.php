<?php 
session_start();
function copyemz($file1,$file2){ 
          $contentx =@file_get_contents($file1); 
                   $openedfile = fopen($file2, "w"); 
                   fwrite($openedfile, $contentx); 
                   fclose($openedfile); 
                    if ($contentx === FALSE) { 
                    $status=false; 
                    }else $status=true; 
                    
                    return $status; 
    } 

// filename: upload.processor.php 

// first let's set some variables 

// make a note of the current working directory, relative to root. 
include 'formatstring.php';
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

// make a note of the directory that will recieve the uploaded file 
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'images/upload/'; 

// make a note of the location of the upload form in case we need it 
$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'uploademployeepic.php'; 
// make a note of the location of the success page 
$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'uploademployeepic.php';
// fieldname used within the file <input> of the HTML form 

$fieldname = 'file';
if(isset($_POST['EmpID']))
{
	$EmpID = $_POST['EmpID'];
	 $_SESSION['EmpID'] = $EmpID;
}

 

// Now let's deal with the upload 

// possible PHP upload errors 
$errors = array(1 => 'php.ini max file size exceeded', 
				2 => 'html form max file size exceeded', 
				3 => 'file upload was only partial', 
				4 => 'no file was attached'); 

// check the upload form was actually submitted else print the form 

isset($_POST['submit']) 
	or error('the upload form is needed', $uploadForm); 
	$tmpName = $_FILES['file']['tmp_name'];

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
include 'library/config.php';
include 'library/opendb.php';
$FullFileName = $now.'-'.$_FILES[$fieldname]['name'];

@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
	or error('receiving directory insuffiecient permission', $uploadForm);
// Save File Full Name to Database
// Save File Full Name to Database

$instr = fopen($uploadFilename,"r");
$image = addslashes(fread($instr,filesize($uploadFilename)));
fclose($instr);
if(!get_magic_quotes_gpc())
{
    $FullFileName = addslashes($FullFileName);
}
                             
							 
							$file1 = 'images/upload/'.$FullFileName;  //header and logo transfer from images/uploads file to images 
							$file2 = 'images/'.$FullFileName;
							$file3 =  copyemz($file1,$file2);
         $_SESSION['picname1'] = $FullFileName;
         // $_SESSION['status'] = 1;

	
	//$query = "update tbschool set HeaderPic = '$FullFileName', Content = '$image'";
	//mysql_query($query) or die('Error, query failed');
	//$errormsg = "<font color = blue size = 1>Image uploaded successfully.</font>";

//echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
header('Location: ' . $uploadSuccess);
exit;
// If you got this far, everything has worked and the file has been successfully saved. 
// We are now going to redirect the client to a success page. 

 

// The following function is an error handler which is used 
// to output an HTML error page if the file upload fails 
function error($error, $location, $seconds = 2) 
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