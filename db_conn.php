<?php 

$sName = "mysql://root:ckHWhRZIxHJEDlenalzhFFhTCWGTnoNv@yamabiko.proxy.rlwy.net:29688/railway";
$uName = "root";
$pass = "ckHWhRZIxHJEDlenalzhFFhTCWGTnoNv";
$db_name = "railway";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}