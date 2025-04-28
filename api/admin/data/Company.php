<?php 

// Get All Companies
function getAllCompanies($conn){
   $sql = "SELECT * FROM companies ORDER BY company_id DESC";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if($stmt->rowCount() >= 1){
          $data = $stmt->fetchAll();
          return $data;
   }else {
     return 0;
   }
}

// Get Company by ID
function getCompanyById($conn, $id){
   $sql = "SELECT * FROM companies WHERE company_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$id]);

   if($stmt->rowCount() >= 1){
         $data = $stmt->fetch();
         return $data;
   }else {
       return 0;
   }
}

// Delete Company By ID
function deleteCompanyById($conn, $id){
   $sql = "DELETE FROM companies WHERE company_id=?";
   $stmt = $conn->prepare($sql);
   $res = $stmt->execute([$id]);

   if($res){
          return 1;
   }else {
       return 0;
   }
} 