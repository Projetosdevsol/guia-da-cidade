<?php 
session_start();
$logged = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
	 $logged = true;
	 $user_id = $_SESSION['user_id'];
}

if (isset($_GET['post_id'])) {

   	  include_once("admin/data/Post.php");
        include_once("admin/data/Comment.php");
        include_once("db_conn.php");
        $id = $_GET['post_id'];
        $post = getById($conn, $id);
        $comments = getCommentsByPostID($conn, $id);
        $categories = get5Categoies($conn); 

     if ($post == 0) {
     	  header("Location: blog.php");
	     exit;
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Blog - <?=$post['post_title']?></title>
	
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- Seu CSS customizado -->
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<?php 
        include 'inc/NavBar.php';
      ?>
    
    <div class="container mt-5">
        <div class="social-layout">
            <!-- Coluna Principal -->
            <main class="main-content">
                <!-- Card do Post -->
                <div class="social-card">
                    <!-- Cabeçalho do Post -->
                    <div class="post-header">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div>
                                <div class="user-name">Admin</div>
                                <div class="post-meta">
                                    <span class="post-date">
                                        <i class="far fa-clock me-1"></i>
                                        <?=date("d M Y", strtotime($post['crated_at']))?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo do Post -->
                    <div class="post-content">
                        <h1 class="post-title"><?=$post['post_title']?></h1>
                        
                        <?php if($post['cover_url']): ?>
                            <div class="post-image">
                                <img src="upload/blog/<?=$post['cover_url']?>" alt="<?=$post['post_title']?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-text">
                            <?=$post['post_text']?>
                        </div>
                    </div>

                    <!-- Barra de Interações -->
                    <div class="interaction-bar">
                        <div class="interaction-buttons">
                            <div class="react-btns">
                                <?php 
                                    $post_id = $post['post_id'];
                                    if ($logged) {
                                        $liked = isLikedByUserID($conn, $post_id, $user_id);
                                        if($liked){
                                ?>
                                    <button class="interaction-btn like-btn liked" 
                                            post-id="<?=$post_id?>"
                                            liked="1">
                                        <i class="fa fa-thumbs-up"></i>
                                        <span class="count"><?=likeCountByPostID($conn, $post['post_id'])?></span>
                                    </button>
                                <?php } else { ?>
                                    <button class="interaction-btn like-btn" 
                                            post-id="<?=$post_id?>"
                                            liked="0">
                                        <i class="fa fa-thumbs-up"></i>
                                        <span class="count"><?=likeCountByPostID($conn, $post['post_id'])?></span>
                                    </button>
                                <?php } 
                                    } else { ?>
                                    <button class="interaction-btn" disabled>
                                        <i class="fa fa-thumbs-up"></i>
                                        <span class="count"><?=likeCountByPostID($conn, $post['post_id'])?></span>
                                    </button>
                                <?php } ?>
                                
                                <button class="interaction-btn">
                                    <i class="fa fa-comment"></i>
                                    <span class="count"><?=CountByPostID($conn, $post['post_id'])?></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Seção de Comentários -->
                    <div class="comments-section">
                        <h3 class="section-title">Comentários</h3>
                        
                        <!-- Formulário de Comentário -->
                        <form action="php/comment.php" method="post" class="comment-form">
                            <?php if(isset($_GET['error'])): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <?=htmlspecialchars($_GET['error'])?>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($_GET['success'])): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?=htmlspecialchars($_GET['success'])?>
                                </div>
                            <?php endif; ?>

                            <div class="comment-input-wrapper">
                                <div class="user-avatar small">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           name="comment" 
                                           placeholder="Escreva um comentário..."
                                           <?=!$logged ? 'disabled' : ''?>>
                                    <input type="hidden" name="post_id" value="<?=$id?>">
                                    <button type="submit" class="btn btn-primary" <?=!$logged ? 'disabled' : ''?>>
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <?php if(!$logged): ?>
                                <div class="login-prompt">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Faça <a href="login.php">login</a> para comentar
                                </div>
                            <?php endif; ?>
                        </form>

                        <!-- Lista de Comentários -->
                        <div class="comments-list">
                            <?php if($comments != 0): 
                                foreach ($comments as $comment):
                                    $u = getUserByID($conn, $comment['user_id']);
                            ?>
                                <div class="comment-item">
                                    <div class="user-avatar small">
                                        <img src="img/user-default.png" alt="<?=$u['username']?>">
                                    </div>
                                    <div class="comment-bubble">
                                        <div class="comment-user">@<?=$u['username']?></div>
                                        <div class="comment-text"><?=$comment['comment']?></div>
                                        <div class="comment-meta">
                                            <?=date("d M Y", strtotime($comment['crated_at']))?>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            else: ?>
                                <div class="no-comments">
                                    <i class="far fa-comments"></i>
                                    <p>Seja o primeiro a comentar!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="trending-card">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Categorias em Destaque
                    </h3>
                    <div class="trending-list">
                        <?php foreach ($categories as $category): ?>
                            <a href="category.php?category_id=<?=intval($category['id'])?>" 
                               class="trending-item">
                                <span class="trend-hashtag">#</span>
                                <span class="trend-name"><?=htmlspecialchars($category['category'])?></span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        $(".like-btn").click(function(){
            var post_id = $(this).attr('post-id');
            var liked = $(this).attr('liked');

            if (liked == 1) {
                $(this).attr('liked', '0');
                $(this).removeClass('liked');
            } else {
                $(this).attr('liked', '1');
                $(this).addClass('liked');
            }
            $(this).find('.count').load("ajax/like-unlike.php",
                {
                    post_id: post_id
                });
        });
    });
    </script>

    <style>
    /* Layout Principal */
    .social-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Cards e Elementos Comuns */
    .social-card, .trending-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Cabeçalho do Post */
    .post-header {
        padding: 16px;
        border-bottom: 1px solid #f0f2f5;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar.small {
        width: 32px;
        height: 32px;
    }

    .user-avatar i {
        font-size: 24px;
        color: #65676b;
    }

    .user-avatar.small i {
        font-size: 16px;
    }

    .user-name {
        font-weight: 600;
        color: #050505;
    }

    .post-meta {
        font-size: 13px;
        color: #65676b;
    }

    /* Conteúdo do Post */
    .post-content {
        padding: 20px;
    }

    .post-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
    }

    .post-image {
        margin: 0 -20px;
    }

    .post-image img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
    }

    .post-text {
        font-size: 16px;
        line-height: 1.6;
        color: #050505;
        margin-top: 20px;
    }

    /* Barra de Interações */
    .interaction-bar {
        padding: 12px 20px;
        border-top: 1px solid #f0f2f5;
        border-bottom: 1px solid #f0f2f5;
    }

    .interaction-buttons {
        display: flex;
        gap: 20px;
    }

    .interaction-btn {
        background: none;
        border: none;
        color: #65676b;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 20px;
        transition: all 0.2s;
    }

    .interaction-btn:hover {
        background: #f0f2f5;
    }

    .interaction-btn.active {
        color: #e41e3f;
    }

    .interaction-btn i {
        font-size: 18px;
    }

    /* Seção de Comentários */
    .comments-section {
        padding: 20px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #050505;
        margin-bottom: 20px;
    }

    .comment-input-wrapper {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }

    .comment-input-wrapper .input-group {
        flex: 1;
    }

    .comment-input-wrapper .form-control {
        border-radius: 20px;
        border: 1px solid #f0f2f5;
        padding: 8px 16px;
    }

    .comment-input-wrapper .btn {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-prompt {
        text-align: center;
        color: #65676b;
        font-size: 14px;
        margin-top: 12px;
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 24px;
    }

    .comment-item {
        display: flex;
        gap: 12px;
    }

    .comment-bubble {
        background: #f0f2f5;
        border-radius: 18px;
        padding: 12px 16px;
        flex: 1;
    }

    .comment-user {
        font-weight: 600;
        color: #050505;
        margin-bottom: 4px;
    }

    .comment-text {
        color: #050505;
        font-size: 14px;
    }

    .comment-meta {
        font-size: 12px;
        color: #65676b;
        margin-top: 4px;
    }

    .no-comments {
        text-align: center;
        padding: 40px 0;
        color: #65676b;
    }

    .no-comments i {
        font-size: 48px;
        margin-bottom: 12px;
    }

    /* Sidebar */
    .trending-card {
        padding: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #050505;
        margin-bottom: 16px;
    }

    .trending-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .trending-item {
        display: flex;
        align-items: center;
        padding: 12px;
        text-decoration: none;
        color: #050505;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .trending-item:hover {
        background: #f0f2f5;
    }

    .trend-hashtag {
        color: #1877f2;
        font-weight: 600;
        margin-right: 4px;
    }

    .trend-name {
        flex: 1;
    }

    /* Responsividade */
    @media (max-width: 992px) {
        .social-layout {
            grid-template-columns: 1fr;
        }
        
        .sidebar {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .post-title {
            font-size: 20px;
        }
        
        .post-content {
            padding: 16px;
        }
    }

    /* Estilo específico para o botão de like */
    .interaction-btn.like-btn {
        cursor: pointer;
    }

    .interaction-btn.liked {
        color: #e41e3f;
    }

    .interaction-btn.liked i {
        color: #e41e3f;
    }

    .interaction-btn:hover {
        background: #f0f2f5;
    }
    </style>
</body>
</html>
<?php }else {
	header("Location: blog.php");
	exit;
} ?>