<?php 
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['username'])) {
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Companies</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/side-bar.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php 
      $key = "hhdsfs1263z";
      include "inc/side-nav.php"; 
      include_once("../db_conn.php");

      // Função para buscar todas as empresas
      function getAllCompanies($conn) {
          $sql = "SELECT * FROM companies ORDER BY name";
          $stmt = $conn->prepare($sql);
          $stmt->execute();

          if($stmt->rowCount() >= 1){
              return $stmt->fetchAll();
          }else {
              return 0;
          }
      }

      $companies = getAllCompanies($conn);
    ?>
               
     <div class="main-table">
        <h3 class="mb-3">All Companies 
            <a href="company-add.php" class="btn btn-success">Add New</a></h3>
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

        <?php if ($companies != 0) { ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>CNPJ</th>
              <th>Category</th>
              <th>Size</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($companies as $company) { ?>
            <tr>
              <th scope="row"><?=$company['company_id']?></th>
              <td><?=$company['name']?></td>
              <td><?=$company['cnpj']?></td>
              <td><?=$company['category']?></td>
              <td><?=$company['size']?></td>
              <td>
                <a href="company-edit.php?company_id=<?=$company['company_id']?>" 
                   class="btn btn-warning">Editar</a>
                <a href="company-delete.php?company_id=<?=$company['company_id']?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Tem certeza que deseja excluir esta empresa?')">Excluir</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php }else{ ?>
            <div class="alert alert-warning">
                Nenhuma empresa cadastrada!
            </div>
        <?php } ?>
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