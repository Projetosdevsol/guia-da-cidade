<?php 
session_start();
$logged = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
	 $logged = true;
	 $user_id = $_SESSION['user_id'];
    }

  include_once("db_conn.php");
  include_once("admin/data/Post.php");
  include_once("admin/data/Comment.php");
  
  $categories = getAllCategories($conn);
  $categories5 = get5Categoies($conn); 
  $category = 0;
 ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php 
           if (isset($_GET['category_id'])){
           	   $c_id =$_GET['category_id'];
           	   $category = getCategoryById($conn, $c_id); 
               if ($category == 0) {
                 echo "Blog Category Page";
               }else {
               	echo "Blog | ".$category['category'];
               }
           }else {
              echo "Blog Category Page";
           }
		 ?>
	</title>
	
	<!-- Fontes -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Navbar CSS (depois do Bootstrap para sobrescrever estilos se necessário) -->
	<link rel="stylesheet" href="css/navbar.css">
	
	<!-- Seus estilos customizados por último -->
	<link rel="stylesheet" href="css/style.css">
	<style>
	/* Remova qualquer estilo que afete a navbar e mantenha apenas os específicos da página */
	.container {
	    margin-top: 60px; /* Ajustado para considerar a altura fixa da navbar */
	    /* ... outros estilos do container ... */
	}

	/* ... outros estilos específicos da página ... */
	</style>
</head>
<body>
 <?php 
     include 'inc/NavBar.php';
  ?>
 <div class="container mt-5">
 <h1 class="display-4 mb-4 fs-3">
 			<?php if ($category != 0)
 			  echo "Noticias sobre '".$category['category']."'";  
 			else echo "Principais Categorias"; ?>
 	
</h1>
  
  <section class="d-flex">
  	<?php if (!isset($_GET['category_id'])) { ?>
  	   <main class="main-blog p-2">
  	   	  <div class="list-group category-aside">
  	   	  	 <?php foreach ($categories as $category) {?>
			  <a href="category.php?category_id=<?=$category['id']?>" 
			     class="list-group-item list-group-item-action">
			  	<?php echo $category['category']; ?>
			  </a>
			<?php } ?>
		   </div>
  	   </main>
  	<?php }else{
  	 $cId = $_GET['category_id'];
  	 $posts = getAllPostsByCategory($conn, $cId);
  	?>
	<?php if ($posts != 0) { ?>
   <main class="main-blog">
   	<?php foreach ($posts as $post) { ?>
   	   <div class="card main-blog-card mb-5">
	  <img src="upload/blog/<?=$post['cover_url']?>" class="card-img-top" alt="...">
	  <div class="card-body">
	    <h5 class="card-title"><?=$post['post_title']?></h5>
	    <?php 
            $p = strip_tags($post['post_text']); 
            $p = substr($p, 0, 200);               
	     ?>
	    <p class="card-text"><?=$p?>...</p>
	    <a href="blog-view.php?post_id=<?=$post['post_id']?>" class="btn btn-primary">Read more</a>
	    <hr>
        <div class="d-flex justify-content-between">
        	<div class="react-btns">
    		<?php 
    		$post_id = $post['post_id'];
    		if ($logged) {
    			$liked = isLikedByUserID($conn, $post_id, $user_id);
    		
            
            if($liked){
    		 ?>
        	<i class="fa fa-thumbs-up liked like-btn" 
		   	   post-id="<?=$post_id?>"
		   	   liked="1"
		   	   aria-hidden="true"></i>
		       <?php }else{ ?>
		    <i class="fa fa-thumbs-up like like-btn"
		        post-id="<?=$post_id?>"
		   	    liked="0"
		        aria-hidden="true"></i>
		    <?php } } else{ ?>
		    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
		    <?php } ?>
		   Likes (
    <span><?php 
   echo likeCountByPostID($conn, $post['post_id']);
     ?></span> )
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
  	<?php }else {?> 
  		<main class="main-blog p-2">
	  		<div class="alert alert-warning">
	  			Nenhuma noticia encontrada.
	  		</div>
  	    </main>
  	<?php } } ?>
       <aside class="aside-main">
       	   <div class="list-group category-aside">
			  <a href="#" 
			     class="list-group-item list-group-item-action active" 
			     aria-current="true">
			    Categorias
			  </a>
			  <?php foreach ($categories5 as $category ) { ?>
			  <a href="category.php?category_id=<?=$category['id']?>" 
			  	 class="list-group-item list-group-item-action">
			  	<?php echo $category['category']; ?>
			  </a>
			  <?php } ?>
			</div>
       </aside>
  </section>

    </div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>