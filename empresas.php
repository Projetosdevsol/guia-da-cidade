<?php 
// Inicia a sessão para verificar informações do usuário logado
session_start();
include "db_conn.php";
include_once("admin/data/Post.php");
include_once("admin/data/Comment.php");

$logged = false; // Variável para indicar se o usuário está logado

// Verifica se as variáveis de sessão do usuário estão definidas
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $logged = true; // Usuário está logado
    $user_id = $_SESSION['user_id']; // Armazena o ID do usuário logado
}

// Variável para indicar se resultados de busca não foram encontrados
$notFound = 0;

// Buscar empresas do banco de dados
$sql = "SELECT * FROM companies ORDER BY name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas de Arujá</title>
    <!-- Importando o Bootstrap para estilização -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 30px;
        }

        /* Estilo para a seção de filtros */
        .filter-section {
            margin-bottom: 30px;
        }

        .filter-section h3 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        /* Estilo para os campos de filtro (inputs e selects) */
        .filter-section input,
        .filter-section select {
            margin-bottom: 10px;
        }

        /* Estilo para a tabela de empresas */
        .table th,
        .table td {
            vertical-align: middle;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        /* Estilo para o nome da empresa */
        .company-name {
            font-weight: bold;
        }

        /* Estilo para os links de detalhes */
        .details-btn {
            color: #007bff;
            text-decoration: none;
        }

        .details-btn:hover {
            text-decoration: underline;
        }

        /* Estilo para a navegação (paginacao) */
        .pagination {
            justify-content: center;
        }
    </style>
</head>

<body>

    <!-- Barra de Navegação Dinâmica -->
    <?php include 'inc/NavBar.php'; ?>

    <div class="container my-5">
        <!-- Seção de Filtros -->
        <div class="filter-section mb-4">
            <h3>Empresas em Arujá</h3>
            <p>Encontre informações sobre empresas na cidade de Arujá, com filtros por nome, categoria e porte.</p>

            <div class="row">
                <!-- Barra de Pesquisa para o nome da empresa -->
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchName" placeholder="Pesquisar por nome da empresa...">
                </div>
                <!-- Filtro de Categoria (Indústria, Comércio, Serviços) -->
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Selecione a Categoria</option>
                        <option value="Indústria">Indústria</option>
                        <option value="Comércio">Comércio</option>
                        <option value="Serviços">Serviços</option>
                    </select>
                </div>
                <!-- Filtro de Porte (Pequena, Média, Grande) -->
                <div class="col-md-4">
                    <select class="form-select" id="sizeFilter">
                        <option value="">Selecione o Porte</option>
                        <option value="Pequena">Pequena</option>
                        <option value="Média">Média</option>
                        <option value="Grande">Grande</option>
                    </select>
                </div>
            </div>
        </div>

       <!-- Tabela de Empresas -->
<div class="table-responsive">
    <table class="table table-bordered" id="companyTable">
        <thead>
            <tr>
                <!-- Títulos das colunas da tabela -->
                <th>Nome da Empresa</th>
                <th>CNPJ</th>
                <th>Categoria</th>
                <th>Porte</th>
                <th>Informações</th>
            </tr>
        </thead>
        <tbody>
            <!-- As empresas serão inseridas aqui dinamicamente -->
        </tbody>
    </table>
</div>

        <!-- Paginação: Navegação entre as páginas -->
        <nav>
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>
    

    <!-- Importando o Bootstrap JS e suas dependências -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Atualizar a variável companies com os dados do PHP
        const companies = <?php echo json_encode($companies); ?>;

        let currentPage = 1;
        const itemsPerPage = 5;

      // Exibe a lista de empresas com base na página atual
function displayCompanies(companiesToDisplay) {
    const tableBody = document.getElementById('companyTable').querySelector('tbody');
    tableBody.innerHTML = '';
    companiesToDisplay.forEach(company => {
        const row = document.createElement('tr');
        // Verifica se o campo 'website' existe e não está vazio
        const websiteLink = company.website ? `<a href="${company.website}" target="_blank" class="details-btn">Ver Detalhes</a>` : 'Sem link disponível';
        row.innerHTML = `
            <td class="company-name">${company.name}</td>
            <td>${company.cnpj}</td>
            <td>${company.category}</td>
            <td>${company.size}</td>
            <td>${websiteLink}</td>
        `;
        tableBody.appendChild(row);
    });
}

        // Exibe a navegação de páginas
        function setupPagination(totalItems) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const totalPages = Math.ceil(totalItems / itemsPerPage);

            for (let i = 1; i <= totalPages; i++) {
                const pageItem = document.createElement('li');
                pageItem.classList.add('page-item');
                pageItem.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>`;
                pagination.appendChild(pageItem);
            }
        }

        // Vai para a página solicitada
        function goToPage(page) {
            currentPage = page;
            applyFilters();
        }

        // Aplica os filtros de pesquisa
        function applyFilters() {
            const searchValue = document.getElementById('searchName').value.toLowerCase();
            const categoryValue = document.getElementById('categoryFilter').value;
            const sizeValue = document.getElementById('sizeFilter').value;

            let filteredCompanies = companies.filter(company => {
                return (
                    company.name.toLowerCase().includes(searchValue) &&
                    (categoryValue ? company.category === categoryValue : true) &&
                    (sizeValue ? company.size === sizeValue : true)
                );
            });

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const companiesToDisplay = filteredCompanies.slice(startIndex, endIndex);

            displayCompanies(companiesToDisplay);
            setupPagination(filteredCompanies.length);
        }

        // Evento de filtro
        document.getElementById('searchName').addEventListener('input', applyFilters);
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('sizeFilter').addEventListener('change', applyFilters);

        // Inicializa a página com todos os itens
        applyFilters();
    </script>

    <?php include 'inc/footer.php'; ?>
</body>

</html>
