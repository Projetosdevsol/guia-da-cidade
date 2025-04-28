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
       isset($_POST['description']) &&
       isset($_POST['company_id'])){

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
        $company_id = $_POST['company_id'];

        if(empty($name)){
            $em = "Nome da empresa é obrigatório"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }else if(empty($cnpj)){
            $em = "CNPJ é obrigatório"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }else if(empty($address)){
            $em = "Endereço é obrigatório"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }else if(empty($phone)){
            $em = "Telefone é obrigatório"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }else if(empty($email)){
            $em = "Email é obrigatório"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }else if(empty($category)){
            $em = "Categoria é obrigatória"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }else if(empty($size)){
            $em = "Porte da empresa é obrigatório"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }else if(empty($description)){
            $em = "Descrição é obrigatória"; 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }

        try {
            $sql = "UPDATE companies 
                    SET name=?, cnpj=?, address=?, phone=?, email=?, website=?, 
                        category=?, size=?, description=? 
                    WHERE company_id=?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$name, $cnpj, $address, $phone, $email, $website, 
                                 $category, $size, $description, $company_id]);

            if ($res) {
                $sm = "Empresa atualizada com sucesso!"; 
                header("Location: ../company-edit.php?success=$sm&company_id=$company_id");
                exit;
            }else {
                $em = "Erro ao atualizar empresa"; 
                header("Location: ../company-edit.php?error=$em&company_id=$company_id");
                exit;
            }
        } catch (PDOException $e) {
            $em = "Erro ao atualizar empresa: " . $e->getMessage(); 
            header("Location: ../company-edit.php?error=$em&company_id=$company_id");
            exit;
        }

    }else {
        header("Location: ../companies.php");
        exit;
    }

}else {
    header("Location: ../admin-login.php");
    exit;
} 