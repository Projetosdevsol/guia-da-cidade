<?php 
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['username'])) {
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Adicionar Empresa</title>
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
        <h3 class="mb-3">Adicionar Nova Empresa
            <a href="companies.php" class="btn btn-secondary">Voltar</a>
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
              action="req/company-create.php" 
              method="post">
            
            <div class="mb-3">
                <label class="form-label">Nome da Empresa</label>
                <input type="text" 
                       class="form-control"
                       name="name"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">CNPJ</label>
                <input type="text" 
                       class="form-control"
                       name="cnpj"
                       placeholder="XX.XXX.XXX/XXXX-XX"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Endereço</label>
                <input type="text" 
                       class="form-control"
                       name="address"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" 
                       class="form-control"
                       name="phone"
                       placeholder="(XX) XXXXX-XXXX"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" 
                       class="form-control"
                       name="email"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Website</label>
                <input type="url" 
                       class="form-control"
                       name="website"
                       placeholder="https://www.example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select name="category" class="form-control" required>
                    <option value="">Selecione a Categoria</option>
                    <option value="Restaurante">Restaurante</option>
                    <option value="Comércio">Comércio</option>
                    <option value="Serviços">Serviços</option>
                    <option value="Indústria">Indústria</option>
                    <option value="Tecnologia">Tecnologia</option>
                    <option value="Saúde">Saúde</option>
                    <option value="Educação">Educação</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Porte da Empresa</label>
                <select name="size" class="form-control" required>
                    <option value="">Selecione o Porte</option>
                    <option value="Pequena">Pequena</option>
                    <option value="Média">Média</option>
                    <option value="Grande">Grande</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" 
                          name="description" 
                          rows="4"
                          required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar Empresa</button>
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