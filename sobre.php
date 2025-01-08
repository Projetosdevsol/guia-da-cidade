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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arujá Guia</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo do Cabeçalho */
        header nav a {
            text-decoration: none;
            font-weight: bold;
        }

        header nav a:hover {
            color: #007bff;
        }

        /* Hero Section */
        .hero {
            background-image: url('https://i.ytimg.com/vi/kqs78nQVHg0/hq720.jpg?sqp=-oaymwE7CK4FEIIDSFryq4qpAy0IARUAAAAAGAElAADIQj0AgKJD8AEB-AH-CYAC0AWKAgwIABABGFYgXyhlMA8=&rs=AOn4CLDfQQbklcDUoYfk6WI6j4SI3gsB2Q');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 5rem 0;
        }

        .hero h2 {
            font-size: 2.5rem;
        }

        /* Conteúdo Principal */
        main img {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Rodapé */
        .footer {
            background-color: #2f3031;
            color: white;
            padding: 1rem;
        }

        .footer a {
            color: #ffcb05;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Barra de Navegação Dinâmica -->
    <?php include 'inc/NavBar.php'; ?>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <h2 class="display-5 mb-3">Descubra Arujá com o Arujá Guia</h2>
            <p class="lead">O blog que conecta você às melhores informações sobre a cidade, comércio, indústria e serviços locais.</p>
        </div>
    </section>

    <!-- Conteúdo Principal -->
    <main class="container my-5">
        <!-- Sobre a Plataforma -->
        <div class="row mb-5">
            <div class="col-md-6">
                <h3 class="mb-3">O que é o Arujá Guia?</h3>
                <p>
                    O Arujá Guia é uma plataforma digital dedicada a informar, conectar e inspirar. Nosso objetivo é oferecer um espaço onde moradores, visitantes e empreendedores possam descobrir o melhor de Arujá — de notícias locais até oportunidades no comércio e na indústria.
                </p>
            </div>
            <div class="col-md-6">
                <img src="https://www.visitearuja.com.br/uploads/946blobid1666029735434.jpg" alt="Imagem ilustrativa sobre o Arujá Guia" class="img-fluid rounded">
            </div>
        </div>

        <!-- Comércio e Indústria -->
        <div class="row">
            <div class="col-md-6 order-md-2">
                <h3 class="mb-3">Comércio, Indústria e Serviços</h3>
                <p>
                    Acreditamos que o desenvolvimento econômico de Arujá passa pelo fortalecimento de seus setores privados. No Arujá Guia, você encontra informações sobre empresas locais, oportunidades de negócio e serviços que movimentam a economia da cidade.
                </p>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="https://www.advtecnologia.com.br/wp-content/uploads/2024/06/planejamento-de-producao-1080x675.jpg" alt="Imagem ilustrativa do setor privado de Arujá" class="img-fluid rounded">
            </div>
        </div>

        <!-- Por que Escolher o Arujá Guia -->
        <div class="row my-5">
            <div class="col-md-12 text-center">
                <h3 class="mb-3">Por que usar o Arujá Guia?</h3>
                <p>
                    Somos apaixonados pela cidade de Arujá e comprometidos em promover sua essência. Navegue em nosso blog para explorar artigos sobre economia, cultura, eventos e muito mais. Este é o lugar onde você se conecta com tudo que Arujá tem a oferecer.
                </p>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'inc/rodape.php';?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Exemplo de interatividade
        document.querySelectorAll('header nav a').forEach(link => {
            link.addEventListener('click', event => {
                event.preventDefault();
                alert('Você clicou em um link de navegação!');
            });
        });
    </script>
</body>
</html>
