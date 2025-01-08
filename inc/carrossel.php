 <!-- Link do Bootstrap -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

<style>
    /* Estilo personalizado para o carrossel */
    .carousel-inner img {
        width: 100%;
        height: 30vh; /* Ajusta a altura para ocupar toda a tela */
        object-fit: cover; /* Mantém o aspecto da imagem */
    }
    .carousel-caption h5 {
        font-size: 3rem; /* Tamanho do título */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Sombra para melhorar a legibilidade */
    }
    .carousel-caption p {
        font-size: 1.25rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Sombra para o texto */
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: #000; /* Cor de fundo dos ícones de navegação */
    }
</style>
</head>
<body>

<!-- Carrossel -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="path/to/image1.jpg" class="d-block w-100" alt="Slide 1">
            <div class="carousel-caption d-none d-md-block">
                <h5>Title 1</h5>
                <p>Description for the first slide.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="path/to/image2.jpg" class="d-block w-100" alt="Slide 2">
            <div class="carousel-caption d-none d-md-block">
                <h5>Title 2</h5>
                <p>Description for the second slide.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="path/to/image3.jpg" class="d-block w-100" alt="Slide 3">
            <div class="carousel-caption d-none d-md-block">
                <h5>Title 3</h5>
                <p>Description for the third slide.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
