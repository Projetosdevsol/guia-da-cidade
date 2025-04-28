<?php 

$sName = "mysql://root:ckHWhRZIxHJEDlenalzhFFhTCWGTnoNv@mysql.railway.internal:3306/railway";
$uName = "railway";
$pass = "ckHWhRZIxHJEDlenalzhFFhTCWGTnoNv";
$db_name = "railway";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}