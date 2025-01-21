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
        }
        .card-text, .card-title, p, h1, h5 {
            color: black !important;
        }
        .list-group-item:not(.active) {
            color: black !important;
        }
    </style>
    
    <!-- Links de estilos e bibliotecas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <div class="container mt-5">
        <section class="d-flex">
            
            <!-- Verifica se há posts disponíveis -->
            <?php if ($posts != 0) { ?>
            <main class="main-blog">
                <!-- Exibe título da seção -->
                <h1 class="display-4 mb-4 fs-3">
                    <?php 
                    if (isset($_GET['search'])) { 
                        echo "Search <b>'".htmlspecialchars($_GET['search'])."'</b>"; 
                    }
                    ?>
                </h1>

                <!-- Lista de posts -->
                <?php foreach ($posts as $post) { ?>
                <div class="card main-blog-card mb-5">
                    <!-- Imagem do post -->
                    <img src="upload/blog/<?=$post['cover_url']?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <!-- Título do post -->
                        <h5 class="card-title"><?=$post['post_title']?></h5>
                        
                        <!-- Resumo do texto do post -->
                        <?php 
                        $p = strip_tags($post['post_text']); 
                        $p = substr($p, 0, 200);               
                        ?>
                        <p class="card-text"><?=$p?>...</p>

                        <!-- Botão para ler mais -->
                        <a href="blog-view.php?post_id=<?=$post['post_id']?>" class="btn btn-primary">Read more</a>
                        
                        <hr>
                        <!-- Área de curtidas e comentários -->
                        <div class="d-flex justify-content-between">
                            <div class="react-btns">
                                <?php 
                                $post_id = $post['post_id'];
                                if ($logged) { // Se o usuário estiver logado
                                    $liked = isLikedByUserID($conn, $post_id, $user_id); // Verifica se o post foi curtido
                                    
                                    if ($liked) { ?>
                                        <!-- Ícone de curtida ativo -->
                                        <i class="fa fa-thumbs-up liked like-btn" 
                                           post-id="<?=$post_id?>"
                                           liked="1"
                                           aria-hidden="true"></i>
                                    <?php } else { ?>
                                        <!-- Ícone de curtida inativo -->
                                        <i class="fa fa-thumbs-up like like-btn"
                                           post-id="<?=$post_id?>"
                                           liked="0"
                                           aria-hidden="true"></i>
                                    <?php } 
                                } else { ?>
                                    <!-- Ícone de curtida para usuários não logados -->
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                <?php } ?>

                                <!-- Contador de curtidas -->
                                Likes (
                                <span>
                                    <?php 
                                    echo likeCountByPostID($conn, $post['post_id']); 
                                    ?>
                                </span>
                                )

                                <!-- Link para comentários -->
                                <a href="blog-view.php?post_id=<?=$post['post_id']?>#comments">    
                                    <i class="fa fa-comment" aria-hidden="true"></i> Comments (
                                    <?php 
                                    echo CountByPostID($conn, $post['post_id']); 
                                    ?>
                                    )
                                </a>	
                            </div>	
                            <small class="text-body-secondary"><?=$post['crated_at']?></small>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </main>
            <?php } else { ?>
            <!-- Exibição de mensagens caso não haja posts -->
            <main class="main-blog p-2">
                <?php if ($notFound) { ?>
                <div class="alert alert-warning"> 
                    No search results found 
                    <?php echo " - <b>key = '".htmlspecialchars($_GET['search'])."'</b>" ?>
                </div>
                <?php } else { ?>
                <div class="alert alert-warning"> 
                    No posts yet.
                </div>
                <?php } ?>
            </main>
            <?php } ?>

              <!-- Substitui toda a seção da barra lateral (aside) -->
            <aside class="aside-main">
                <!-- Primeiro Card de Anúncio -->
                <div class="card mb-4">
                    <div id="carouselAd1" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="ads/ad1-1.jpg" class="d-block w-100" alt="Anúncio 1">
                            </div>
                            <div class="carousel-item">
                                <img src="ads/ad1-2.jpg" class="d-block w-100" alt="Anúncio 2">
                            </div>
                            <div class="carousel-item">
                                <img src="ads/ad1-3.jpg" class="d-block w-100" alt="Anúncio 3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Segundo Card de Anúncio -->
                <div class="card mb-4">
                    <div id="carouselAd2" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="ads/ad2-1.jpg" class="d-block w-100" alt="Anúncio 1">
                            </div>
                            <div class="carousel-item">
                                <img src="ads/ad2-2.jpg" class="d-block w-100" alt="Anúncio 2">
                            </div>
                            <div class="carousel-item">
                                <img src="ads/ad2-3.jpg" class="d-block w-100" alt="Anúncio 3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terceiro Card de Anúncio -->
                <div class="card mb-4">
                    <div id="carouselAd3" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="ads/ad3-1.jpg" class="d-block w-100" alt="Anúncio 1">
                            </div>
                            <div class="carousel-item">
                                <img src="ads/ad3-2.jpg" class="d-block w-100" alt="Anúncio 2">
                            </div>
                            <div class="carousel-item">
                                <img src="ads/ad3-3.jpg" class="d-block w-100" alt="Anúncio 3">
                            </div>
                        </div>
                    </div>
                </div>
            </aside>



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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

   

</body>
 <!-- Rodapé -->
 <?php include 'inc/rodape.php'; ?>
</html>
