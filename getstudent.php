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
	session_start();
	global $userNames;
	if (isset($_SESSION['username']))
	{
		$userNames=$_SESSION['username'];
	} else {
		echo "<meta http-equiv=\"Refresh\" content=\"0;url=index.php\">";
		exit;
	}
	
	
	//$query = "select Stu_Full_Name from tbstudentmaster";
	//$result = mysql_query($query,$conn);?>
                   <!-- <select name="student" id="student">
                 	      <option>Select Student</option>
                 <?php //while($row = mysql_fetch_array($result)) {
					// $StudentName = $row['Stu_Full_Name'];?>
                   <option value><?php //echo $StudentName; ?></option>
                        <?php // } ?>
                        </select>-->
<?PHP 

$stmt = $pdo->prepare("SELECT * FROM tbstudentmaster WHERE Stu_Class = ?");
// do something
$stmt->execute(array("18"));
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
             



// do something else
echo json_encode($row);


?>
                        
                             
	

