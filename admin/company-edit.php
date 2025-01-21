<?php 
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['username']) && isset($_GET['company_id'])) {
    include "../db_conn.php";
    
    // Função getCompanyById diretamente no arquivo
    function getCompanyById($conn, $id){
        $sql = "SELECT * FROM companies WHERE company_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
    
        if($stmt->rowCount() >= 1){
            return $stmt->fetch();
        }else {
            return false;
        }
    }
    
    $company_id = $_GET['company_id'];
    $company = getCompanyById($conn, $company_id);
    
    if(!$company) {
        header("Location: companies.php?error=Empresa não encontrada");
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Edit Company</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/side-bar.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php 
      $key = "hhdsfs1263z";
      include "inc/side-nav.php"; 
    ?>
               
    <div class="main-table">
        <h3 class="mb-3">Edit Company
            <a href="companies.php" class="btn btn-secondary">Back to Companies</a>
        </h3>

        <?php if (isset($_GET['error'])) { ?>    
        <div class="alert alert-warning">
            <?=htmlspecialchars($_GET['error'])?>
        </div>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>    
        <div class="alert alert-success">
            <?=htmlspecialchars($_GET['success'])?>
        </div>
        <?php } ?>

        <form class="shadow p-4" 
              action="req/company-edit.php" 
              method="post">
            <input type="hidden" name="company_id" value="<?=$company['company_id']?>">
            
            <div class="mb-3">
                <label class="form-label">Nome da Empresa</label>
                <input type="text" 
                       class="form-control"
                       name="name"
                       value="<?=htmlspecialchars($company['name'])?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">CNPJ</label>
                <input type="text" 
                       class="form-control"
                       name="cnpj"
                       value="<?=htmlspecialchars($company['cnpj'])?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Endereço</label>
                <input type="text" 
                       class="form-control"
                       name="address"
                       value="<?=htmlspecialchars($company['address'])?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" 
                       class="form-control"
                       name="phone"
                       value="<?=htmlspecialchars($company['phone'])?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" 
                       class="form-control"
                       name="email"
                       value="<?=htmlspecialchars($company['email'])?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Website</label>
                <input type="url" 
                       class="form-control"
                       name="website"
                       value="<?=htmlspecialchars($company['website'] ?? '')?>"
                       placeholder="https://www.example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select name="category" class="form-control" required>
                    <option value="">Selecione a Categoria</option>
                    <option value="Restaurante" <?=$company['category'] == 'Restaurante' ? 'selected' : ''?>>Restaurante</option>
                    <option value="Comércio" <?=$company['category'] == 'Comércio' ? 'selected' : ''?>>Comércio</option>
                    <option value="Serviços" <?=$company['category'] == 'Serviços' ? 'selected' : ''?>>Serviços</option>
                    <option value="Indústria" <?=$company['category'] == 'Indústria' ? 'selected' : ''?>>Indústria</option>
                    <option value="Tecnologia" <?=$company['category'] == 'Tecnologia' ? 'selected' : ''?>>Tecnologia</option>
                    <option value="Saúde" <?=$company['category'] == 'Saúde' ? 'selected' : ''?>>Saúde</option>
                    <option value="Educação" <?=$company['category'] == 'Educação' ? 'selected' : ''?>>Educação</option>
                    <option value="Outros" <?=$company['category'] == 'Outros' ? 'selected' : ''?>>Outros</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Porte da Empresa</label>
                <select name="size" class="form-control" required>
                    <option value="">Selecione o Porte</option>
                    <option value="Pequena" <?=$company['size'] == 'Pequena' ? 'selected' : ''?>>Pequena</option>
                    <option value="Média" <?=$company['size'] == 'Média' ? 'selected' : ''?>>Média</option>
                    <option value="Grande" <?=$company['size'] == 'Grande' ? 'selected' : ''?>>Grande</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" 
                          name="description" 
                          rows="4"
                          required><?=htmlspecialchars($company['description'])?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar Empresa</button>
        </form>
    </div>
    </section>
    </div>

    <script>
        var navList = document.getElementById('navList').children;
        navList.item(4).classList.add("active");
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php }else {
    header("Location: ../admin-login.php");
    exit;
} ?> 