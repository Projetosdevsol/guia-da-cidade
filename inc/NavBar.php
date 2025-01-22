<nav class="navbar navbar-expand-lg fixed-navbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="blog.php">
            <b>Arujá<span class="brand-highlight">Guia</span></b>
        </a>

        <!-- Botão mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Menu principal -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Links principais -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="blog.php">
                        <i class="fas fa-home"></i> Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="category.php">
                        <i class="fas fa-newspaper"></i> Notícias
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="empresas.php">
                        <i class="fas fa-store"></i> Comércio
                    </a>
                </li>
            </ul>

            <!-- Busca -->
            <form class="d-flex search-form" role="search" method="GET" action="blog.php">
                <div class="input-group">
                    <input class="form-control" type="search" name="search" placeholder="Buscar no site...">
                    <button class="btn btn-search" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Menu do usuário -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-compass"></i> Serviços
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="https://www.prefeituradearuja.sp.gov.br/">
                                <i class="fas fa-building"></i> Prefeitura
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="https://www.arujatransporte.com.br/">
                                <i class="fas fa-bus"></i> Transporte
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="https://servicosonline.prefeituradearuja.sp.gov.br/servicosonline/arujaemprega/">
                                <i class="fas fa-briefcase"></i> Empregos
                            </a>
                        </li>
                    </ul>
                </li>

                <?php if ($logged) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link user-menu" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?=$_SESSION['username']?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user"></i> Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link login-btn" href="login.php">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<style>
.fixed-navbar {
    background: white;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    height: 80px; /* Altura fixa */
    padding: 0;
}

.navbar-brand {
    font-size: 26px;
    font-weight: 700;
    color: #333;
    padding: 0;
    height: 80px; /* Mesma altura da navbar */
    display: flex;
    align-items: center;
}

.brand-highlight {
    color: #0d6efd;
    margin-left: 2px;
}

/* Links principais */
.navbar-nav .nav-link {
    color: #555;
    font-size: 15px;
    padding: 10px 16px;
    margin: 0 2px;
    border-radius: 8px;
    transition: all 0.2s;
    height: 44px; /* Altura fixa para os links */
    display: flex;
    align-items: center;
}

/* Container principal */
.navbar > .container {
    height: 80px; /* Mesma altura da navbar */
}

/* Collapse container */
.navbar-collapse {
    height: 80px; /* Mesma altura da navbar */
    align-items: center;
}

/* Busca */
.search-form {
    width: 300px;
    margin: 0 20px;
}

.search-form .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px 0 0 8px;
    padding: 10px 15px;
    font-size: 14px;
}

.search-form .form-control:focus {
    border-color: #0d6efd;
    box-shadow: none;
}

.btn-search {
    background: #0d6efd;
    color: white;
    border: none;
    padding: 0 20px;
    border-radius: 0 8px 8px 0;
}

/* Dropdown */
.dropdown-menu {
    border: none;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border-radius: 12px;
    padding: 8px;
    min-width: 200px;
}

.dropdown-item {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
}

.dropdown-item:hover {
    background: #f0f4ff;
    color: #0d6efd;
}

.dropdown-item i {
    margin-right: 8px;
    width: 16px;
    text-align: center;
}

/* Botão de login */
.login-btn {
    background: #0d6efd;
    color: white !important;
    padding: 8px 24px !important;
    border-radius: 8px;
    margin-left: 10px;
    font-weight: 500;
    border: none;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
}

.login-btn:hover {
    background: linear-gradient(45deg, #0143a3, #0d6efd) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.login-btn i {
    margin-right: 6px;
}

/* Menu do usuário */
.user-menu {
    font-weight: 500;
}

/* Mobile */
@media (max-width: 991px) {
    .navbar-collapse {
        height: auto; /* Remove altura fixa no mobile */
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-top: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        position: absolute;
        top: 80px; /* Posiciona abaixo da navbar */
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .navbar-toggler {
        height: 44px; /* Altura fixa para o botão mobile */
    }

    .search-form {
        width: 100%;
        margin: 15px 0;
        height: 44px; /* Altura fixa para o formulário */
    }

    .login-btn {
        margin: 10px 0;
        text-align: center;
        height: 44px; /* Altura fixa para o botão de login */
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
</style>