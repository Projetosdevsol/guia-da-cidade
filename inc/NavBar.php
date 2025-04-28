<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Link do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Fontes do Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Ícones do Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ============================
           Estilo Geral da Navbar
           ============================ */
        .navbar-brand {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.75rem;
            color: #0061ff !important;
            background: linear-gradient(135deg, #0061ff, #60efff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-highlight {
            background: linear-gradient(45deg, #1877f2, #2851E3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ============================
           Links de Navegação
           ============================ */
        .nav-link {
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            color: #4b5563 !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #1d4ed8 !important;
        }

        .nav-link.active {
            color: #1d4ed8 !important;
            border-bottom: 2px solid #1d4ed8;
        }

    /* ============================
       Botões de Autenticação (Responsivo)
       ============================ */
       .nav-auth {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        margin-left: 6px !important;
        min-width: 185px !important;
        justify-content: flex-end !important;
    }

    @media (max-width: 768px) {
        /* Oculta apenas os links principais, mas mantém os botões de autenticação */
        .nav-links {
            display: none !important;
        }

        .nav-auth {
            display: flex !important;
            position: absolute !important;
            top: 100% !important; /* Posiciona abaixo da navbar */
            left: 0 !important;
            right: 0 !important;
            background: white !important;
            padding: 10px !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
            border-bottom: 1px solid #ddd !important;
        }

        .nav-toggle.active + .nav-links {
            display: flex !important;
            flex-direction: column !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: 0 !important;
            background: white !important;
            padding: 10px !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
            z-index: 999 !important;
        }
    }

    /* ============================
       Botão de Toggle (Responsivo)
       ============================ */
    .nav-toggle {
        display: none !important;
    }

    @media (max-width: 768px) {
        .nav-toggle {
            display: block !important;
            font-size: 24px !important;
            color: #1d4ed8 !important;
            cursor: pointer !important;
        }
    }

        /* ============================
           Barra de Pesquisa
           ============================ */
        .search-form {
            position: relative;
        }

        .search-form input {
            width: 100%;
            padding: 0.5rem 2.5rem 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .search-form input:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 0.2rem rgba(29, 78, 216, 0.25);
        }

        .search-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        /* ============================
           Responsividade
           ============================ */
        @media (max-width: 992px) {
            .navbar-expand-lg .navbar-nav {
                flex-direction: row !important;
            }

            .nav-link span {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .search-form {
                display: none;
            }

            .navbar-nav {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
    <script>
    // Adiciona funcionalidade ao botão de toggle
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.querySelector('.nav-toggle');
        const navLinks = document.querySelector('.nav-links');

        if (toggleButton && navLinks) {
            toggleButton.addEventListener('click', function () {
                navLinks.classList.toggle('active');
            });
        }
    });
</script>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <b>Arujá<span class="brand-highlight">Guia</span></b>
        </a>

        <!-- Botão de Toggle para Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Conteúdo da Navbar -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="d-flex flex-grow-1 justify-content-between align-items-center">
                <!-- Barra de Pesquisa -->
                <form class="search-form d-none d-lg-block w-25" action="blog.php" method="GET">
                    <input type="text" name="search" placeholder="Pesquisar..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <i class="fas fa-search search-icon"></i>
                </form>

                <!-- Links de Navegação -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="blog.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>">
                            <i class="fas fa-newspaper me-1"></i>
                            <span>Notícias</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.prefeituradearuja.sp.gov.br/" class="nav-link" target="_blank">
                            <i class="fas fa-building me-1"></i>
                            <span>Prefeitura</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.arujatransporte.com.br/" class="nav-link" target="_blank">
                            <i class="fas fa-bus me-1"></i>
                            <span>Transporte</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="empresas.php" class="nav-link">
                            <i class="fas fa-briefcase me-1"></i>
                            <span>Empresas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="https://servicosonline.prefeituradearuja.sp.gov.br/servicosonline/arujaemprega/" class="nav-link" target="_blank">
                            <i class="fas fa-user-tie me-1"></i>
                            <span>Empregos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sobre.php" class="nav-link">
                            <i class="fas fa-info-circle me-1"></i>
                            <span>Sobre</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="category.php" class="nav-link">
                            <i class="fas fa-tags me-1"></i>
                            <span>Categorias</span>
                        </a>
                    </li>
                </ul>

                <!-- Botões de Autenticação -->
                <div class="auth-buttons d-none d-lg-flex">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="img/user-default.png" alt="User" class="rounded-circle me-2" style="width: 24px; height: 24px;">
                                @<?php echo htmlspecialchars($_SESSION['username']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                            </ul>
                        </div>
                    <?php } else { ?>
                        <a href="login.php" class="btn btn-outline-primary">Entrar</a>
                        <a href="signup.php" class="btn btn-primary">Cadastrar</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Script do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>