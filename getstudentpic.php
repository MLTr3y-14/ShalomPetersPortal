<?PHP 
include 'library/config.php';
include 'library/opendb.php';
    if(isset($_GET['image_id']) && is_numeric($_GET['image_id'])) { 
       
  
        // get the image from the db 

        $query = "select * from tbemployeemasters where ID=".$_GET['image_id'];
							$result = mysql_query($query,$conn);
							$row = mysql_fetch_array($result);
							$content = $row["Content"];
							header("Content-type: image/jpeg"); 
							echo $content;
    } 
    else { 
        echo 'Please use a real id number'; 
    } 
                        

?>