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
    $posts = serach($conn, $key);
    if ($posts == 0) {
        $notFound = 1;
    }
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
                    echo "As notícias que conectam você à sua região!";
                }
                ?>
            </h2>

            <!-- Loop dos Posts com Estilo de Rede Social -->
            <div class="posts-container">
                <?php if ($posts != 0) { ?>
                    <?php foreach ($posts as $post) { ?>
                        <div class="post-card">
                            <!-- Cabeçalho do Post -->
                            <div class="post-header">
                                <div class="post-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="author-info">
                                        <h6 class="author-name">Admin</h6>
                                        <span class="post-date">
                                            <i class="far fa-clock"></i>
                                            <?= htmlspecialchars(date("d/m/Y", strtotime($post['crated_at']))) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Conteúdo do Post -->
                            <div class="post-content">
                                <?php if ($post['cover_url']) { ?>
                                    <div class="post-image">
                                        <img src="upload/blog/<?= htmlspecialchars($post['cover_url']) ?>" 
                                             alt="<?= htmlspecialchars($post['post_title']) ?>"
                                             class="img-fluid rounded">
                                    </div>
                                <?php } ?>
                                <h2 class="post-title">
                                    <a href="blog-view.php?post_id=<?= $post['post_id'] ?>">
                                        <?= htmlspecialchars($post['post_title']) ?>
                                    </a>
                                </h2>
                                <div class="post-excerpt">
                                    <?= substr(strip_tags($post['post_text']), 0, 100) ?>...
                                </div>
                            </div>

                            <!-- Rodapé do Post -->
                            <div class="post-footer">
                                <div class="post-stats">
                                    <span class="stat-item">
                                        <i class="far fa-heart"></i>
                                        <?= likeCountByPostID($conn, $post['post_id']) ?>
                                    </span>
                                    <span class="stat-item">
                                        <i class="far fa-comment"></i>
                                        <?= CountByPostID($conn, $post['post_id']) ?>
                                    </span>
                                </div>
                                <a href="blog-view.php?post_id=<?= $post['post_id'] ?>" 
                                   class="btn btn-link read-more">
                                    Ler mais
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="no-posts">
                        <i class="fas fa-newspaper"></i>
                        <p><?php echo $notFound ? "Nenhum resultado encontrado para '".htmlspecialchars($key)."'" : "Nenhum post disponível."; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Sidebar de Categorias Populares -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="trending-title">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Categorias em Destaque
                    </h5>
                    <div class="trending-topics">
                        <?php foreach ($categories5 as $category) { ?>
                            <a href="category.php?category_id=<?= intval($category['id']) ?>" class="trending-item">

                                <span class="trend-name">
                                    <span class="trend-hashtag">#</span>
                                    <?php echo htmlspecialchars($category['category']); ?>
                                </span>
                                <i class="fas fa-chevron-right trend-arrow"></i>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <?php include 'inc/ads-carousel.php'; ?>
        </div>
    </div>
</div>

<style>
/* Estilo dos Posts */
.posts-container {
    max-width: 800px;
    margin: 0 auto;
}

.post-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    margin-bottom: 16px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.post-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Cabeçalho do Post */
.post-header {
    display: flex;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid #f0f2f5;
}

.post-author {
    display: flex;
    align-items: center;
    gap: 8px;
}

.author-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f0f2f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.author-avatar i {
    font-size: 18px;
    color: #65676b;
}

.author-info {
    line-height: 1.2;
}

.author-name {
    margin: 0;
    color: #050505;
    font-weight: 600;
    font-size: 14px;
}

.post-date {
    font-size: 12px;
    color: #65676b;
}

/* Conteúdo do Post */
.post-content {
    padding: 12px;
}

.post-title {
    font-size: 16px;
    margin: 8px 0;
}

.post-title a {
    color: #050505;
    text-decoration: none;
}

.post-title a:hover {
    color: #1877f2;
}

.post-image {
    margin: 8px -12px;
    max-height: 200px;
    overflow: hidden;
}

.post-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.post-excerpt {
    color: #65676b;
    font-size: 13px;
    line-height: 1.4;
    margin-top: 8px;
}

/* Rodapé do Post */
.post-footer {
    padding: 8px 12px;
    border-top: 1px solid #f0f2f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-stats {
    display: flex;
    gap: 12px;
}

.stat-item {
    color: #65676b;
    font-size: 13px;
}

.stat-item i {
    margin-right: 4px;
}

.read-more {
    color: #1877f2;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    padding: 0;
}

.read-more:hover {
    text-decoration: underline;
    color: #1877f2;
}

/* Mensagem de Nenhum Post */
.no-posts {
    text-align: center;
    padding: 32px 16px;
    background: #fff;
    border-radius: 8px;
    color: #65676b;
}

.no-posts i {
    font-size: 32px;
    margin-bottom: 12px;
}

/* Responsividade */
@media (max-width: 768px) {
    .posts-container {
        padding: 0 12px;
    }
}

/* Estilo Categorias em Destaque */
.trending-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #2c3e50;
    border-bottom: 2px solid #eef2f7;
    padding-bottom: 10px;
}

.trending-topics {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.trending-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    text-decoration: none;
    color: #2c3e50;
    background-color: #f8fafc;
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
    font-size: 14px;
}

.trending-item:hover {
    background-color: #fff;
    border-left-color: #0d6efd;
    color: #0d6efd;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.trend-icon {
    color: #64748b;
    margin-right: 12px;
    font-size: 16px;
}

.trending-item:hover .trend-icon {
    color: #0d6efd;
}

.trend-name {
    font-weight: 500;
    flex: 1;
}

.trend-hashtag {
    color: #0d6efd;
    font-weight: 600;
    margin-right: 2px;
}

.trending-item:hover .trend-hashtag {
    color: #0d6efd;
}

.trend-arrow {
    color: #64748b;
    font-size: 12px;
    opacity: 0;
    transform: translateX(-10px);
    transition: all 0.2s ease;
}

.trending-item:hover .trend-arrow {
    opacity: 1;
    transform: translateX(0);
    color: #0d6efd;
}

/* Responsividade */
@media (max-width: 768px) {
    .trending-topics {
        margin: 0 -8px;
    }
    
    .trending-item {
        margin: 0 8px;
    }
}
</style>

<?php include 'inc/footer.php'; ?>
