<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="blog.php">ArujáGuia</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="blog.php">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sobre.php">Sobre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="empresas.php">Empresas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contato</a>
                </li>
                <?php if ($logged) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Entrar</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<style>
body {
    padding-top: 56px; /* Espaço para a navbar fixa */
}

.navbar {
    height: 56px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
    font-weight: bold;
    color: #333;
}

.nav-link {
    color: #555;
    transition: color 0.2s;
}

.nav-link:hover {
    color: #0d6efd;
}

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