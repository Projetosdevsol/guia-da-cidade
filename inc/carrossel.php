<!-- Link do Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

<style>
    /* Estilo personalizado para o carrossel */
    .carousel-inner img {
        width: 100%;
        height: 30vh;
        object-fit: cover;
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: #000;
    }
</style>
</head>
<body>

<!-- Carrossel -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://is1-ssl.mzstatic.com/image/thumb/Purple211/v4/cb/11/4b/cb114bac-9210-9127-a8eb-c088ec3bd81b/AppIcon-0-1x_U007emarketing-0-7-0-85-220-0.png/1200x600wa.png" class="d-block w-100" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="https://media.licdn.com/dms/image/v2/C4E1BAQHxTYuGcZASuQ/company-background_10000/company-background_10000/0/1643897213758/solution_sp_cover?e=2147483647&v=beta&t=6V_b8W8shL2n31wAcivY2ry7qC_J1ZsTHbrlPmgX5vA" class="d-block w-100" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="https://revistadestaquemais.com.br/wp-content/uploads/2018/12/hiperCubo-1.jpg" class="d-block w-100" alt="Slide 3">
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

<!-- Carrossel de Anúncios
<div id="carouselAnuncios" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://via.placeholder.com/1200x300?text=Anuncio+1" class="d-block w-100" alt="Anúncio 1">
        </div>
        <div class="carousel-item">
            <img src="https://via.placeholder.com/1200x300?text=Anuncio+2" class="d-block w-100" alt="Anúncio 2">
        </div>
        <div class="carousel-item">
            <img src="https://via.placeholder.com/1200x300?text=Anuncio+3" class="d-block w-100" alt="Anúncio 3">
        </div>
    </div>
</div> -->

<style>
    /* Estilo personalizado para o carrossel */
    #carouselAnuncios .carousel-inner img {
        width: 100%;
        height: 20vh; /* Altura ajustada para não ser muito grande */
        object-fit: cover;
    }
</style>
