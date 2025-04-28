<?php 

$sName = getenv("DB_HOST");
$uName = getenv("DB_USER");
$pass = getenv("DB_PASS");
$db_name = getenv("DB_NAME");

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}
?>
