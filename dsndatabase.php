<?PHP
/*function dbPDO_Connect_mySQL($DBUser, $DBPass, $DBName = false, 
                            $DBHost = false, $DBPort = false) 
{ 

    $DBNameEq = empty($DBName) ? '' : ";dbname=$DBName"; 
    
    if (empty($DBHost)) $DBHost = 'localhost'; 
    
    If ($DBHost[0] === '/') 
    { 
        $Connection = "unix_socket=$DBHost"; 
    } 
    else 
    { 
        if (empty($DBPort)) $DBPort = 3306; 
        $Connection = "host=$DBHost;port=$DBPort"; 
    } 
    
    //====================== 
    
    try 
    { 
        $dbh     = new PDO("mysql:$Connection$DBNameEq" 
                                  ,  $DBUser, $DBPass); 
    } 
    catch (PDOException $e) 
    { 
        return $e->getMessage(); 
    } 

    return $dbh; 
} 

//================================ 
//================================ 
// 
//Example of use: 

  //connects to the default (localhost) 

  $pdo = dbPDO_Connect_mySQL('ashton_admin', 'MyAshton2013#', 'skoolnet'); 

  //............................................................ 
  //error handler goes here 

  if (!is_object($pdo)) trigger_error("Failed to connect to 'database' " 
       ." | Error = $pdo", E_USER_ERROR); 
  
 */ 
$dsn = "mysql:host=localhost;dbname=shalompe_skoolnet";
$username = "shalompe_root";
$password = "Tingate200@";

try {

    $pdo = new PDO($dsn, $username, $password);
	//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}

catch(PDOException $e) {

   // die("Could not connect to the database error testing\n");

}



?>