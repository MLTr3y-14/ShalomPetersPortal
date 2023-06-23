
<?PHP

global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
	include 'library/dsndatabase.php';
	include 'formatstring.php';
	include 'function.php';
	
	$RegNo = '789';
	$Session_ID = '1';
	$Term_ID = '2';

$query = "Insert into tbstudentdetail (Stu_Regist_No,Session_ID,Term_ID) Values	('$RegNo','$Session_ID','$Term_ID')";
					$result = mysql_query($query,$conn);

/*$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';
							
							$query = "Update allowancemaster set Term_ID='1' and Session_ID = '1'";
                            $result = mysql_query($query,$conn);
							echo 'update sucessfull';*/
							
							
							
							
							
					
					
					?>