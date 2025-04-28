<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Link do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Fontes do Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ============================
           Estilo Geral do Carrossel
           ============================ */
        #carouselAds {
            margin-top: 20px;
        }

        /* ============================
           Cards dos Anúncios
           ============================ */
        #carouselAds .card {
            border: none;
            border-radius: 10px; /* Bordas arredondadas */
            overflow: hidden; /* Garante que as imagens não ultrapassem os limites do card */
            transition: transform 0.2s ease-in-out; /* Efeito suave ao passar o mouse */
        }

        #carouselAds .card:hover {
            transform: translateY(-5px); /* Eleva o card levemente ao passar o mouse */
        }

        #carouselAds .card-img-top {
            height: 150px; /* Altura fixa para as imagens */
            object-fit: cover; /* Garante que as imagens sejam redimensionadas sem distorção */
        }

        #carouselAds .card-body {
            padding: 0.75rem; /* Espaçamento interno reduzido */
        }

        #carouselAds .card-title {
            font-size: 0.875rem; /* Tamanho reduzido para caber melhor no card */
            font-weight: 500; /* Peso da fonte para destacar o título */
            margin-bottom: 0.5rem; /* Espaçamento entre o título e o texto */
        }

        #carouselAds .btn-sm {
            font-size: 0.75rem; /* Botão menor para adequação ao espaço */
            padding: 0.25rem 0.5rem;
        }

        /* ============================
           Indicadores (Pontinhos)
           ============================ */
        #carouselAds .carousel-indicators {
            bottom: -15px; /* Ajusta a posição dos indicadores abaixo do carrossel */
        }

        #carouselAds .carousel-indicators button {
            width: 8px;
            height: 8px;
            border-radius: 50%; /* Formato circular */
            background-color: #ccc; /* Cor padrão dos pontinhos */
            margin: 0 4px;
        }

        #carouselAds .carousel-indicators .active {
            background-color: #1d4ed8; /* Cor do pontinho ativo */
        }

        /* ============================
           Responsividade
           ============================ */
        @media (max-width: 768px) {
            #carouselAds .card-img-top {
                height: 120px; /* Reduz a altura das imagens em telas menores */
            }

            #carouselAds .card-title {
                font-size: 0.75rem; /* Reduz o tamanho do título */
            }

            #carouselAds .btn-sm {
                font-size: 0.65rem; /* Reduz o tamanho do botão */
                padding: 0.2rem 0.4rem;
            }
        }
    </style>
</head>
<body>

<!-- Carrossel de Anúncios em Cards -->
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Anúncios</h5>
            <div id="carouselAds" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <!-- Slides -->
                <div class="carousel-inner">
                    <!-- Anúncio 1 -->
                    <div class="carousel-item active">
                        <div class="card">
                            <img src="https://media.licdn.com/dms/image/v2/C4E0BAQGcEsgv8abuVw/company-logo_200_200/company-logo_200_200/0/1643897278931/solution_sp_logo?e=1750291200&v=beta&t=BiGXST9UUPdW5DerL1Kg-fU5GWtn_ALjtipqByKMYSo" class="card-img-top" alt="Anúncio 1">
                            <div class="card-body p-2">
                                <h6 class="card-title small mb-1">Especialistas em Service Desk e infraestrutura, focamos nas necessidades do seu negócio para otimizar resultados. Invista com quem entende.</h6>
                                <a href="#" class="btn btn-primary btn-sm">Saiba mais</a>
                            </div>
                        </div>
                    </div>
                    <!-- Anúncio 2 -->
                    <div class="carousel-item">
                        <div class="card">
                            <img src="https://itauara.com.br/wp-content/uploads/2023/10/fresh.jpg" class="card-img-top" alt="Anúncio 2">
                            <div class="card-body p-2">
                                <h6 class="card-title small mb-1">Qualidade, precisão, resistência e entregas pontuais. Confie em quem entende!</h6>
                                <a href="#" class="btn btn-primary btn-sm">Saiba mais</a>
                            </div>
                        </div>
                    </div>
                    <!-- Anúncio 3 -->
                    <div class="carousel-item">
                        <div class="card">
                            <img src="https://revistadestaquemais.com.br/wp-content/uploads/2024/03/DJI_0077.jpg" class="card-img-top" alt="Anúncio 3">
                            <div class="card-body p-2">
                                <h6 class="card-title small mb-1">Há quase uma década, o Colégio Hipercubo se dedica a formar agentes de mudança, unindo excelência acadêmica, valores humanos e um ensino bilíngue pioneiro em Arujá.</h6>
                                <a href="#" class="btn btn-primary btn-sm">Saiba mais</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Indicadores do Carrossel -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselAds" data-bs-slide-to="0" class="active bg-primary" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselAds" data-bs-slide-to="1" class="bg-primary" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselAds" data-bs-slide-to="2" class="bg-primary" aria-label="Slide 3"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>