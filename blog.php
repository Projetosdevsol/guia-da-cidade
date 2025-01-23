<?php 
// Inicia a sessão para verificar informações do usuário logado
session_start();
$logged = false; // Variável para indicar se o usuário está logado

// Verifica se as variáveis de sessão do usuário estão definidas
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $logged = true; // Usuário está logado
    $user_id = $_SESSION['user_id']; // Armazena o ID do usuário logado
}

// Variável para indicar se resultados de busca não foram encontrados
$notFound = 0;

// Inclui componentes necessários
include_once("db_conn.php");
include_once("admin/data/Post.php");
include_once("admin/data/Comment.php");

$categories = getAllCategories($conn);
$categories5 = get5Categoies($conn); 
$category = 0;

$pageTitle = 'Blog - ArujáGuia';
include 'inc/NavBar.php';
include 'inc/header.php';


// Inclui o carrossel de anúncios
include 'inc/carrossel.php';

// Busca de Posts
if (isset($_GET['search'])) {
    $key = trim($_GET['search']);
    $posts = search($conn, $key);
    $notFound = ($posts == 0) ? 1 : 0;
} else {
    $posts = getAll($conn);
    $notFound = 0;
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Lista de Posts -->
        <div class="col-lg-8">
            <h2 class="mb-4">
                <?php 
                if (isset($_GET['search'])) { 
                    echo "Resultados da busca para '".htmlspecialchars($key)."'";
                } else {
                    echo "Últimos Artigos";
                }
                ?>
            </h2>

            <?php if ($posts != 0) { ?>
                <div class="row">
                    <?php foreach ($posts as $post) { ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="upload/blog/<?= htmlspecialchars($post['cover_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['post_title']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($post['post_title']) ?></h5>
                                    <p class="card-text"><?= substr(strip_tags($post['post_text']), 0, 100) ?>...</p>
                                    <a href="blog-view.php?post_id=<?= intval($post['post_id']) ?>" class="btn btn-outline-primary">Ler mais</a>
                                </div>
                                <div class="card-footer bg-white border-0 text-muted d-flex justify-content-between align-items-center">
                                    <small><?= htmlspecialchars(date("d/m/Y", strtotime($post['crated_at']))) ?></small>
                                    <div>
                                        <i class="fas fa-heart me-1"></i> <?= likeCountByPostID($conn, $post['post_id']) ?>
                                        <i class="fas fa-comment ms-3 me-1"></i> <?= CountByPostID($conn, $post['post_id']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="alert alert-warning"> 
                    <?php 
                    if ($notFound) {
                        echo "Nenhum resultado encontrado para '<strong>".htmlspecialchars($key)."</strong>'.";
                    } else {
                        echo "Nenhum post disponível no momento.";
                    }
                    ?>
                </div>
            <?php } ?>
        </div>

        <!-- Sidebar de Categorias Populares -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Categorias Populares</h5>
                    <div class="list-group">
                        <?php foreach ($categories5 as $category) { ?>
                            <a href="category.php?category_id=<?= intval($category['id']) ?>" class="list-group-item list-group-item-action">
                                <i class="fas fa-folder me-2"></i> <?php echo htmlspecialchars($category['category']); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
