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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Configurações básicas do documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Título da página dinâmico -->
    <title>
        <?php 
        if (isset($_GET['search'])) { 
            echo "search '".htmlspecialchars($_GET['search'])."'"; // Mostra o termo buscado no título
        } else {
            echo "Blog Page"; // Título padrão
        } 
        ?>
    </title>
    <style>
        body {
            color: black;
            background: #f5f7fa;
            min-height: 100vh;
            padding-top: 80px; /* Espaço para a navbar fixa */
        }
        .card-text, .card-title, p, h1, h5 {
            color: black !important;
        }
        .list-group-item:not(.active) {
            color: black !important;
        }
        /* Seção de anúncios */
        .ad-section {
            margin: 30px 0;
            width: 100%;
        }

        /* Banner principal */
        .ad-banner {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
            background: #f8f8f8;
            border: 1px solid #eee;
        }

        .ad-banner img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        /* Grid de anúncios */
        .ad-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .ad-item {
            position: relative;
            background: #f8f8f8;
            border: 1px solid #eee;
            transition: transform 0.2s;
        }

        .ad-item:hover {
            transform: translateY(-2px);
        }

        .ad-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        /* Label de publicidade */
        .ad-label {
            position: absolute;
            top: 5px;
            left: 5px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 2px 6px;
            font-size: 11px;
            border-radius: 3px;
            z-index: 1;
        }

        .ad-link {
            display: block;
            width: 100%;
            height: 100%;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .ad-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .ad-banner img {
                height: 120px;
            }
            
            .ad-item img {
                height: 100px;
            }
        }

        @media (max-width: 480px) {
            .ad-grid {
                grid-template-columns: 1fr;
            }
            
            .ad-banner img {
                height: 100px;
            }
        }

        /* Separador */
        .ad-section::before,
        .ad-section::after {
            content: '';
            display: block;
            height: 1px;
            background: #eee;
            margin: 20px 0;
        }

        .main-content {
            padding: 30px 0;
        }

        /* Layout grid principal */
        .main-content-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Coluna dos posts */
        .blog-posts {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .blog-post-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .blog-post-card:hover {
            transform: translateY(-5px);
        }

        /* Sidebar de anúncios */
        .ads-sidebar {
            position: sticky;
            top: 110px; /* 80px da navbar + 30px de espaço */
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ad-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            height: 250px;
        }

        .ad-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.2s;
        }

        .ad-card:hover img {
            transform: scale(1.05);
        }

        .ad-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 1;
        }

        /* Responsividade */
        @media (max-width: 1200px) {
            .main-content-grid {
                padding: 0 20px;
            }
        }

        @media (max-width: 991px) {
            .main-content-grid {
                grid-template-columns: 1fr;
            }
            
            .ads-sidebar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 0;
            }
            
            .main-content-grid {
                padding: 0 15px;
            }
        }
    </style>
    
    <!-- Links de estilos e bibliotecas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
    // Inclui componentes necessários
    include 'inc/NavBar.php'; // Barra de navegação
    include_once("admin/data/Post.php"); // Funções relacionadas a posts
    include_once("admin/data/Comment.php"); // Funções relacionadas a comentários
    include_once("db_conn.php"); // Conexão com o banco de dados

    // Verifica se há uma busca ativa
    if (isset($_GET['search'])) {
        $key = $_GET['search']; // Termo da busca
        $posts = serach($conn, $key); // Busca os posts no banco de dados
        if ($posts == 0) { 
            $notFound = 1; // Indica que não há resultados
        }
    } else {
        $posts = getAll($conn); // Caso não haja busca, recupera todos os posts
    }

    // Recupera as categorias para exibição na barra lateral
    $categories = get5Categoies($conn); 
    ?>
  
    <!-- Carrosel -->
    <?php include 'inc/carrossel.php'; ?>   

    <!-- Hero Content -->
    <?php include 'inc/hero.php'; ?>   

    <!-- Conteúdo principal da página -->
    <div class="main-content">
        <div class="container">
            <!-- Container principal com grid -->
            <div class="main-content-grid">
                <!-- Coluna dos posts -->
                <div class="blog-posts">
                    <?php 
                    if ($posts != 0) {
                        $post_count = 0;
                        foreach ($posts as $post) {
                            // Exibe o post
                            ?>
                            <article class="blog-post-card">
                                <div class="post-image">
                                    <img src="upload/blog/<?=$post['cover_url']?>" alt="<?=$post['post_title']?>">
                                </div>
                                <div class="post-content">
                                    <h3 class="post-title"><?=$post['post_title']?></h3>
                                    <p class="post-excerpt">
                                        <?=substr(strip_tags($post['post_text']), 0, 100)?>...
                                    </p>
                                    <div class="post-meta">
                                        <div class="interactions">
                                            <span><i class="fa fa-thumbs-up"></i> <?=likeCountByPostID($conn, $post['post_id'])?></span>
                                            <span><i class="fa fa-comment"></i> <?=CountByPostID($conn, $post['post_id'])?></span>
                                        </div>
                                        <a href="blog-view.php?post_id=<?=$post['post_id']?>" class="read-more">Ler mais</a>
                                    </div>
                                </div>
                            </article>
                            <?php
                            $post_count++;
                            
                            // Insere anúncio após cada 3 posts
                            if ($post_count % 3 === 0) {
                                ?>
                                <div class="ad-section">
                                    <!-- Anúncio horizontal grande -->
                                    <div class="ad-banner">
                                        <span class="ad-label">Publicidade</span>
                                        <a href="#" class="ad-link">
                                            <img src="path/to/ad-banner.jpg" alt="Anúncio">
                                        </a>
                                    </div>
                                    
                                    <!-- Grid de anúncios menores -->
                                    <div class="ad-grid">
                                        <div class="ad-item">
                                            <span class="ad-label">Publicidade</span>
                                            <a href="#" class="ad-link">
                                                <img src="path/to/ad1.jpg" alt="Anúncio">
                                            </a>
                                        </div>
                                        <div class="ad-item">
                                            <span class="ad-label">Publicidade</span>
                                            <a href="#" class="ad-link">
                                                <img src="path/to/ad2.jpg" alt="Anúncio">
                                            </a>
                                        </div>
                                        <div class="ad-item">
                                            <span class="ad-label">Publicidade</span>
                                            <a href="#" class="ad-link">
                                                <img src="path/to/ad3.jpg" alt="Anúncio">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } else { ?>
                        <div class="no-posts">
                            <p>Nenhum post encontrado</p>
                        </div>
                    <?php } ?>
                </div>

                <!-- Coluna de anúncios lateral -->
                <aside class="ads-sidebar">
                    <div class="ad-card">
                        <span class="ad-label">Publicidade</span>
                        <a href="#" class="ad-link">
                            <img src="path/to/ad1.jpg" alt="Anúncio">
                        </a>
                    </div>
                    
                    <div class="ad-card">
                        <span class="ad-label">Publicidade</span>
                        <a href="#" class="ad-link">
                            <img src="path/to/ad2.jpg" alt="Anúncio">
                        </a>
                    </div>
                    
                    <div class="ad-card">
                        <span class="ad-label">Publicidade</span>
                        <a href="#" class="ad-link">
                            <img src="path/to/ad3.jpg" alt="Anúncio">
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- Adicione este script antes do fechamento do body -->
    <script>
        // Configura os carrosséis
        document.addEventListener('DOMContentLoaded', function() {
            const carouselOptions = {
                interval: 3000, // Tempo em milissegundos para cada transição
                ride: 'carousel',
                wrap: true
            };

            new bootstrap.Carousel(document.getElementById('carouselAd1'), carouselOptions);
            new bootstrap.Carousel(document.getElementById('carouselAd2'), carouselOptions);
            new bootstrap.Carousel(document.getElementById('carouselAd3'), carouselOptions);
        });
    </script>

    <!-- Scripts para funcionalidades da página -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Lógica de curtidas usando AJAX
            $(".like-btn").click(function() {
                var post_id = $(this).attr('post-id');
                var liked = $(this).attr('liked');

                if (liked == 1) {
                    $(this).attr('liked', '0');
                    $(this).removeClass('liked');
                } else {
                    $(this).attr('liked', '1');
                    $(this).addClass('liked');
                }
                $(this).next().load("ajax/like-unlike.php", {
                    post_id: post_id
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
 <!-- Rodapé -->
 <?php include 'inc/rodape.php'; ?>
</html>
