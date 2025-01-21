<?php 
session_start();

if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['username']) && 
    isset($_GET['company_id'])) {

    include "../db_conn.php";
    
    $company_id = $_GET['company_id'];

    // Primeiro verifica se a empresa existe
    $sql_check = "SELECT * FROM companies WHERE company_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([$company_id]);

    if($stmt_check->rowCount() == 0) {
        $em = "Empresa não encontrada!"; 
        header("Location: companies.php?error=$em");
        exit;
    }

    try {
        // Deleta a empresa
        $sql = "DELETE FROM companies WHERE company_id = ?";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$company_id]);

        if ($res) {
            $sm = "Empresa excluída com sucesso!"; 
            header("Location: companies.php?success=$sm");
            exit;
        } else {
            $em = "Erro ao excluir empresa"; 
            header("Location: companies.php?error=$em");
            exit;
        }
    } catch (PDOException $e) {
        $em = "Erro ao excluir empresa: " . $e->getMessage(); 
        header("Location: companies.php?error=$em");
        exit;
    }

} else {
    header("Location: ../admin-login.php");
    exit;
} 