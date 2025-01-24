<!-- Carrossel de Anúncios em Cards -->
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Anúncios</h5>
        <div id="carouselAds" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <!-- Anúncio 1 -->
                <div class="carousel-item active">
                    <div class="card">
                        <img src="https://scontent.fgru6-1.fna.fbcdn.net/v/t39.30808-6/440240138_932620602048294_5967216369978896034_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=niKiqvXzLHcQ7kNvgGYyQeT&_nc_zt=23&_nc_ht=scontent.fgru6-1.fna&_nc_gid=AtWdPijM-CMHOerTjLiW_PM&oh=00_AYCXwn5UCCLGlk5M68DhZJ2Z_XMlQLXwXHH9Zs0kOThRfQ&oe=67999EB8" class="card-img-top" alt="Anúncio 1">
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
            <!-- Indicadores do carrossel -->
            <div class="carousel-indicators" style="bottom: -20px;">
                <button type="button" data-bs-target="#carouselAds" data-bs-slide-to="0" class="active bg-primary" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselAds" data-bs-slide-to="1" class="bg-primary" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselAds" data-bs-slide-to="2" class="bg-primary" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>
</div>

<style>
#carouselAds .carousel-item {
    padding-bottom: 25px;
}

#carouselAds .card {
    border: none;
    transition: transform 0.2s;
}

#carouselAds .card:hover {
    transform: translateY(-3px);
}

#carouselAds .card-img-top {
    height: 150px;
    object-fit: cover;
}

#carouselAds .carousel-indicators {
    margin-bottom: 0;
}

#carouselAds .carousel-indicators button {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

#carouselAds .card-body {
    padding: 0.5rem;
}

#carouselAds .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style> 