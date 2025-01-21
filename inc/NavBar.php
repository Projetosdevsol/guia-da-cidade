<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="blog.php">
	    	<b>Arujá<span style="color:rgb(212, 209, 0);">Guia</span>
	    	</b>
	    </a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarSupportedContent">
	      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
	        <li class="nav-item">
	          <a class="nav-link" href="blog.php">Noticias</a>
	        </li>
			<li class="nav-item">
                    <a class="nav-link" href="https://www.prefeituradearuja.sp.gov.br/">Prefeitura</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.arujatransporte.com.br/">Transporte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="empresas.php">Empresas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://servicosonline.prefeituradearuja.sp.gov.br/servicosonline/arujaemprega/">Empregos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sobre.php">Sobre</a>
                </li>
	        <li class="nav-item">
	          <a class="nav-link" 
	             href="category.php">
	             Category</a>
	        </li>
	         <?php 
               if ($logged) {
	         ?>
	        <li class="nav-item dropdown">
	          <a class="nav-link dropdown-toggle" 
	             href="profile.php" 
	             role="button" 
	             data-bs-toggle="dropdown" 
	             aria-expanded="false">
	             <i class="fa fa-user" 
	                aria-hidden="true"></i> 
	            @<?=$_SESSION['username']?>
	          </a>
	          <ul class="dropdown-menu">
	            <li><a class="dropdown-item" 
	            	   href="logout.php">
	            	   Logout</a></li>
	          </ul>
	        </li>
	        <?php 
               } else {
	         ?>
	         <li class="nav-item">
	          <a class="nav-link" href="login.php">Login | Cadastro</a>
	        </li>
	         <?php 
               }
	         ?>
	      </ul>
	      <form class="d-flex" 
	             role="search"
	             method="GET"
	             action="blog.php">
	        <input class="form-control me-2" 
	               type="search"
	               name="search" 
	               placeholder="Search" 
	               aria-label="Search">

	        <button class="btn btn-outline-success" 
	                type="submit">
	                Search</button>
	      </form>
	    </div>
	  </div>
	</nav>