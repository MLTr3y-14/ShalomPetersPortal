<?php 

// filename: upload.processor.php 

// first let's set some variables 

// make a note of the current working directory, relative to root. 
include 'formatstring.php';
$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

// make a note of the directory that will recieve the uploaded file 
$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'images/uploads/'; 
if(isset($_GET['pg']))
{
	$Page = $_GET['pg'];
}
if($Page =="Personal Details"){
	// make a note of the location of the upload form in case we need it 
	$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'empreport.php?subpg=Personal Details'; 
	
	// make a note of the location of the success page 
	$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'empreport.php?subpg=Personal Details&filename=' ; 
}else{
	// make a note of the location of the upload form in case we need it 
	$uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'payroll.php?subpg=Employee Master'; 
	
	// make a note of the location of the success page 
	$uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'payroll.php?subpg=Employee Master' ; 
}

// fieldname used within the file <input> of the HTML form 
$fieldname = 'file';
// Now let's deal with the upload 

// possible PHP upload errors 
$errors = array(1 => 'php.ini max file size exceeded', 
				2 => 'html form max file size exceeded', 
				3 => 'file upload was only partial', 
				4 => 'no file was attached'); 

// check the upload form was actually submitted else print the form 

isset($_POST['submit']) 
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
}?>


     <input type="hidden" name="EmpPhoto" value="<?PHP echo $FullFileName; ?>">	
	<?PHP $query = "update tbemployeetemppic set PicName = '$FullFileName', Content = '$image', Status = '1'";
	mysql_query($query) or die('Error, query failed');
	 $errormsg = "<font color = blue size = 1>Image uploaded successfully.</font>";
	 
	 $kolade = "good";

//echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
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