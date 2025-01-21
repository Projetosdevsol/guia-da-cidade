<?php 
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['username'])) {

    if(isset($_POST['name']) && 
       isset($_POST['cnpj']) && 
       isset($_POST['address']) && 
       isset($_POST['phone']) && 
       isset($_POST['email']) && 
       isset($_POST['category']) && 
       isset($_POST['size']) &&
       isset($_POST['description'])){

        include "../../db_conn.php";
        
        $name = $_POST['name'];
        $cnpj = $_POST['cnpj'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $website = $_POST['website'] ?? '';
        $category = $_POST['category'];
        $size = $_POST['size'];
        $description = $_POST['description'];

        if(empty($name)){
            $em = "Nome da empresa é obrigatório"; 
            header("Location: ../company-add.php?error=$em");
            exit;
        }else if(empty($cnpj)){
            $em = "CNPJ é obrigatório"; 
            header("Location: ../company-add.php?error=$em");
            exit;
        }

        $sql = "INSERT INTO companies (name, cnpj, address, phone, email, website, category, size, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$name, $cnpj, $address, $phone, $email, $website, $category, $size, $description]);

        if ($res) {
            $sm = "Empresa cadastrada com sucesso!"; 
            header("Location: ../company-add.php?success=$sm");
            exit;
        }else {
            $em = "Erro ao cadastrar empresa"; 
            header("Location: ../company-add.php?error=$em");
            exit;
        }

    }else {
        header("Location: ../company-add.php");
        exit;
    }

}else {
    header("Location: ../admin-login.php");
    exit;
} 