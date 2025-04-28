<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Link do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <style>
        /* ============================
           Estilo Geral do Carrossel
           ============================ */
        #carouselExample {
            margin: 20px auto; /* Espaçamento externo para centralizar o carrossel */
            max-width: 100%; /* Largura máxima para responsividade */
        }

        /* ============================
           Configurações das Imagens
           ============================ */
        .carousel-inner img {
            width: 100%; /* Garante que a imagem ocupe toda a largura do contêiner */
            height: auto; /* Altura automática para manter a proporção original */
            max-height: 60vh; /* Altura máxima para evitar imagens muito grandes */
            object-fit: contain; /* Redimensiona a imagem para caber inteira no espaço */
            border-radius: 10px; /* Borda arredondada para suavizar as bordas */
        }

        /* ============================
           Botões de Navegação
           ============================ */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.7); /* Fundo semi-transparente */
            border-radius: 50%; /* Formato circular */
            padding: 10px; /* Espaçamento interno para aumentar o tamanho dos ícones */
        }

        /* ============================
           Indicadores (Pontinhos)
           ============================ */
        .carousel-indicators {
            bottom: 15px; /* Ajusta a posição vertical dos indicadores */
        }

        .carousel-indicators [data-bs-target] {
            width: 12px; /* Tamanho dos pontinhos */
            height: 12px;
            border-radius: 50%; /* Formato circular */
            background-color: #ccc; /* Cor padrão dos pontinhos */
            margin: 0 5px; /* Espaçamento entre os pontinhos */
        }

        .carousel-indicators .active {
            background-color: #000; /* Cor do pontinho ativo */
        }

        /* ============================
           Ajustes Responsivos
           ============================ */
        @media (max-width: 768px) {
            .carousel-inner img {
                max-height: 40vh; /* Reduz a altura máxima em telas menores */
            }
        }

        @media (max-width: 576px) {
            .carousel-inner img {
                max-height: 30vh; /* Reduz ainda mais a altura em dispositivos móveis */
            }
        }
    </style>
</head>
<body>

<!-- Carrossel Principal -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <!-- Indicadores (pontinhos) -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="3" aria-label="Slide 4"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="4" aria-label="Slide 5"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://www.rayflex.com.br/wp-content/uploads/2025/02/rayflex-thumb-video.png" class="d-block w-100" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="https://media.licdn.com/dms/image/v2/C4E1BAQHxTYuGcZASuQ/company-background_10000/company-background_10000/0/1643897213758/solution_sp_cover?e=2147483647&v=beta&t=6V_b8W8shL2n31wAcivY2ry7qC_J1ZsTHbrlPmgX5vA" class="d-block w-100" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="https://media.licdn.com/dms/image/v2/C4D1BAQFACeMucZ7qAw/company-background_10000/company-background_10000/0/1583353333346/massfix_reciclagem_de_vidros_cover?e=1745323200&v=beta&t=86y3C91ClQjQq-xmabNbqMVRWrowdiBNpiiK_x0Brn4" class="d-block w-100" alt="Slide 3">
        </div>
        <div class="carousel-item">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRcJtVQw7oZysQMOyE7_R7AqG2nKo85mgo6Tg&s" class="d-block w-100" alt="Slide 4">
        </div>
        <div class="carousel-item">
            <img src="https://castroseguranca.com.br/wp-content/uploads/2024/11/logo_alterado_1_1.webp" class="d-block w-100" alt="Slide 5">
        </div>
    </div>
</div>

<!-- Script do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>