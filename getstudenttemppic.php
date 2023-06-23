<?PHP 
include 'library/config.php';
include 'library/opendb.php';
                         $query = "select * from tbstudenttemppic";
							$result = mysql_query($query,$conn);
							$row = mysql_fetch_array($result);
							$content = $row["Content"];
							header("Content-type: image/jpeg"); 
							echo $content;

?>