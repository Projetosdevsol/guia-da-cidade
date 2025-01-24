<?php
// Garante que o caminho base está disponível
$base_path = isset($base_path) ? $base_path : '';
?>

<!-- Adicione estes links no head de todas as páginas -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<nav class="business-nav">
    <div class="nav-container">
        <!-- Logo e Busca -->
        <div class="nav-left">
            <a href="index.php" class="nav-brand">
                <b>Arujá<span class="brand-highlight">Guia</span></b>
            </a>
            <div class="nav-search">
                <form action="blog.php" method="GET">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           name="search" 
                           placeholder="Pesquisar..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </form>
            </div>
        </div>

        <!-- Menu Mobile -->
        <button class="nav-toggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Links de Navegação -->
        <div class="nav-links">
            <a href="blog.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>">
                <i class="fas fa-newspaper"></i>
                <span>Notícias</span>
            </a>
            <a href="https://www.prefeituradearuja.sp.gov.br/" class="nav-link" target="_blank">
                <i class="fas fa-building"></i>
                <span>Prefeitura</span>
            </a>
            <a href="https://www.arujatransporte.com.br/" class="nav-link" target="_blank">
                <i class="fas fa-bus"></i>
                <span>Transporte</span>
            </a>
            <a href="empresas.php" class="nav-link">
                <i class="fas fa-briefcase"></i>
                <span>Empresas</span>
            </a>
            <a href="https://servicosonline.prefeituradearuja.sp.gov.br/servicosonline/arujaemprega/" class="nav-link" target="_blank">
                <i class="fas fa-user-tie"></i>
                <span>Empregos</span>
            </a>
            <a href="sobre.php" class="nav-link">
                <i class="fas fa-info-circle"></i>
                <span>Sobre</span>
            </a>
            <a href="category.php" class="nav-link">
                <i class="fas fa-tags"></i>
                <span>Categorias</span>
            </a>

            <?php if (isset($_SESSION['user_id'])) { ?>
                <div class="nav-user">
                    <button class="user-menu-btn">
                        <img src="img/user-default.png" alt="User" class="user-avatar">
                        <span>@<?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="user-dropdown">
                        <a href="profile.php">
                            <i class="fas fa-user"></i>
                            Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="logout-link">
                            <i class="fas fa-sign-out-alt"></i>
                            Sair
                        </a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="nav-auth">
                    <a href="login.php" class="btn btn-outline-light">Entrar</a>
                    <a href="signup.php" class="btn btn-light">Cadastrar</a>
                </div>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Espaçador para compensar a navbar fixa -->
<div class="nav-spacer"></div>

<style>
/* Reset Universal para a Navbar */
html body .business-nav,
html body .business-nav *,
html body .business-nav *::before,
html body .business-nav *::after,
html body .nav-spacer {
    all: unset !important;
    box-sizing: border-box !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    font-family: 'Inter', sans-serif !important;
    line-height: 1 !important;
    text-decoration: none !important;
    list-style: none !important;
    background: none !important;
    min-width: 0 !important;
    min-height: 0 !important;
    max-width: none !important;
    max-height: none !important;
    width: auto !important;
    height: auto !important;
    flex: none !important;
    transform: none !important;
    transition: none !important;
    position: static !important;
    float: none !important;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    overflow: visible !important;
    text-align: left !important;
    white-space: normal !important;
    word-wrap: normal !important;
    font-size: 16px !important;
    font-weight: normal !important;
    pointer-events: auto !important;
}

/* Navbar Principal - Visual Ultra Moderno */
html body .business-nav {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100vw !important;
    height: 70px !important;
    min-height: 70px !important;
    max-height: 70px !important;
    background: rgba(255, 255, 255, 0.98) !important;
    backdrop-filter: blur(10px) !important;
    -webkit-backdrop-filter: blur(10px) !important;
    box-shadow: 0 1px 0 rgba(0,0,0,0.08) !important;
    z-index: 9999 !important;
    border-bottom: 1px solid rgba(0,0,0,0.1) !important;
}

/* Container */
html body .business-nav .nav-container {
    width: 100% !important;
    max-width: 1400px !important;
    height: 70px !important;
    padding: 0 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}

/* Seção Esquerda */
html body .business-nav .nav-left {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    min-width: 380px !important;
}

/* Logo */
html body .business-nav .nav-brand {
    display: flex !important;
    align-items: center !important;
    height: 70px !important;
    padding: 0 !important;
    margin: 0 !important;
    font-size: 28px !important;
    font-weight: 800 !important;
    letter-spacing: -0.5px !important;
    background: linear-gradient(135deg, #0061ff, #60efff) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    min-width: 140px !important;
}

html body .business-nav .brand-highlight {
    color: #1877f2 !important;
    background: linear-gradient(45deg, #1877f2, #2851E3) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
}

/* Busca */
html body .business-nav .nav-search {
    position: relative !important;
    width: 220px !important;
    min-width: 220px !important;
    margin: 0 !important;
}

html body .business-nav .nav-search input {
    width: 100% !important;
    height: 44px !important;
    padding: 0 44px !important;
    border-radius: 12px !important;
    background: #f3f4f6 !important;
    border: 1px solid transparent !important;
    color: #111827 !important;
    font-size: 15px !important;
    transition: all 0.2s ease !important;
}

/* Links */
html body .business-nav .nav-links {
    display: flex !important;
    align-items: center !important;
    height: 70px !important;
    gap: 1px !important;
    margin: 0 !important;
    padding: 0 !important;
    flex: 1 !important;
    justify-content: center !important;
}

html body .business-nav .nav-link {
    height: 44px !important;
    padding: 0 10px !important;
    display: flex !important;
    align-items: center !important;
    gap: 4px !important;
    color: #4b5563 !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    border-radius: 10px !important;
    transition: all 0.2s ease !important;
}

html body .business-nav .nav-link:hover {
    background: #f3f4f6 !important;
    color: #1d4ed8 !important;
}

html body .business-nav .nav-link.active {
    background: #1d4ed8 !important;
    color: white !important;
}

html body .business-nav .nav-link i {
    font-size: 18px !important;
}

/* Botões de Auth */
html body .business-nav .nav-auth {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    margin-left: 6px !important;
    min-width: 185px !important;
    justify-content: flex-end !important;
}

html body .business-nav .nav-auth .btn {
    height: 44px !important;
    padding: 0 14px !important;
    border-radius: 10px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
    white-space: nowrap !important;
}

html body .business-nav .nav-auth .btn-outline-light {
    color: #1d4ed8 !important;
    border: 2px solid #1d4ed8 !important;
    background: transparent !important;
    min-width: 85px !important;
}

html body .business-nav .nav-auth .btn-light {
    background: #1d4ed8 !important;
    color: white !important;
    border: 2px solid #1d4ed8 !important;
    min-width: 85px !important;
}

/* Menu do Usuário */
html body .business-nav .user-menu-btn {
    height: 42px !important;
    padding: 0 8px 0 4px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    background: #f3f4f6 !important;
    transition: all 0.2s ease !important;
}

html body .business-nav .user-menu-btn:hover {
    background: #e5e7eb !important;
}

html body .business-nav .user-avatar {
    width: 34px !important;
    height: 34px !important;
    border-radius: 10px !important;
    object-fit: cover !important;
}

/* Dropdown do Usuário */
html body .business-nav .user-dropdown {
    position: absolute !important;
    top: calc(100% + 8px) !important;
    right: 0 !important;
    background: white !important;
    border-radius: 14px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    padding: 8px !important;
    min-width: 220px !important;
    display: none !important;
    border: 1px solid rgba(0,0,0,0.08) !important;
}

html body .business-nav .nav-user:hover .user-dropdown {
    display: block !important;
}

html body .business-nav .user-dropdown a {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 8px 16px !important;
    color: #050505 !important;
    font-size: 14px !important;
    border-radius: 6px !important;
}

html body .business-nav .user-dropdown a:hover {
    background: #f0f2f5 !important;
}

html body .business-nav .dropdown-divider {
    height: 1px !important;
    background: #e4e6eb !important;
    margin: 8px 0 !important;
}

/* Espaçador */
html body .nav-spacer {
    display: block !important;
    width: 100% !important;
    height: 70px !important;
    min-height: 70px !important;
    max-height: 70px !important;
}

/* Responsividade */
@media (max-width: 1200px) {
    html body .business-nav .nav-search {
        width: 180px !important;
        min-width: 180px !important;
    }
    
    html body .business-nav .nav-left {
        min-width: 340px !important;
    }
}

@media (max-width: 992px) {
    html body .business-nav .nav-link span {
        display: none !important;
    }
    
    html body .business-nav .nav-link {
        padding: 0 8px !important;
    }
    
    html body .business-nav .nav-left {
        min-width: 320px !important;
    }
}

@media (max-width: 768px) {
    html body .business-nav .nav-search,
    html body .business-nav .nav-links {
        display: none !important;
    }
    
    html body .business-nav .nav-left {
        min-width: 0 !important;
    }
}
</style>

<script>
// Garantir consistência do espaçador
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.business-nav');
    const spacer = document.querySelector('.nav-spacer');
    
    function setSpacerHeight() {
        requestAnimationFrame(() => {
            const height = '60px';
            spacer.style.setProperty('height', height, 'important');
            spacer.style.setProperty('min-height', height, 'important');
            spacer.style.setProperty('max-height', height, 'important');
        });
    }
    
    setSpacerHeight();
    window.addEventListener('resize', setSpacerHeight);
    window.addEventListener('load', setSpacerHeight);
    setTimeout(setSpacerHeight, 100);
});

document.querySelector('.nav-toggle')?.addEventListener('click', function() {
    document.querySelector('.nav-links').classList.toggle('active');
});
</script>