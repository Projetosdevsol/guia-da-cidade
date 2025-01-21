<!-- Anúncio Skyscraper Esquerdo -->
<div class="ad-skyscraper ad-skyscraper-left">
    <p>Anúncio</p>
    <img src="./src/img/ANUNCIE AQUI.png" alt="Anúncio Lateral">
</div>

<!-- Anúncio Skyscraper Direito -->
<div class="ad-skyscraper ad-skyscraper-right">
    <p>Anúncio</p>
    <img src="./src/img/ANUNCIE AQUI.png" alt="Anúncio Lateral">
</div>

<style>
    /* Estilo para os anúncios */
    .ad-skyscraper {
        position: fixed;
        top: 100px;
        width: 100px;
        height: 500px;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        text-align: center;
        padding-top: 10px;
        z-index: 1000; /* Garante que os anúncios fiquem acima de outros elementos */
    }

    .ad-skyscraper-left {
        left: 0; /* Posiciona na lateral esquerda */
    }

    .ad-skyscraper-right {
        right: 0; /* Posiciona na lateral direita */
    }

    .ad-skyscraper img {
        max-width: 100%;
        max-height: 100%;
    }

    /* Responsividade: Oculta os anúncios em dispositivos móveis */
    @media (max-width: 768px) {
        .ad-skyscraper {
            display: none;
        }
    }
</style>
