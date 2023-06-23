
<?PHP
	session_start();
	global $conn;
	global $dbname;
	global $errorMsg, $PPContent;
	include 'library/config.php';
	include 'library/opendb.php';
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
	include 'mysql2json.class.php'; 
	$num = 0;



$query = "select EmpName from tbemployeemasters";
		$result = mysql_query($query,$conn);
		$num=mysql_affected_rows();
        $row = mysql_fetch_array($result);?>
        
	                <select name="employee" id = "employee" dojoType="dijit.form.ComboBox">
                    <option>Select Employee</option>
                 <?php while($row = mysql_fetch_array($result)) {
					 $EmpName = $row['EmpName'];?>
                   <option value><?php echo $EmpName;  ?></option>
                        <?php } ?>
                             </select>
                   <?php //echo json_encode($result);
				   $objJSON= new mysql2json(); 
                 print(trim($objJSON->getJSON($result,$num))); 
?> 
                             
                            


