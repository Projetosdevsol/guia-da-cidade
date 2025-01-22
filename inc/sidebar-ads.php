<!-- Coluna de anúncios lateral -->
<aside class="ads-sidebar">
    <div class="ad-card">
        <span class="ad-label">Publicidade</span>
        <a href="#" class="ad-link">
            <img src="path/to/ad1.jpg" alt="Anúncio">
        </a>
    </div>
    
    <div class="ad-card">
        <span class="ad-label">Publicidade</span>
        <a href="#" class="ad-link">
            <img src="path/to/ad2.jpg" alt="Anúncio">
        </a>
    </div>
    
    <div class="ad-card">
        <span class="ad-label">Publicidade</span>
        <a href="#" class="ad-link">
            <img src="path/to/ad3.jpg" alt="Anúncio">
        </a>
    </div>
</aside>

<style>
/* Sidebar de anúncios */
.ads-sidebar {
    position: sticky;
    top: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Cards de anúncio */
.ad-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    position: relative;
    height: 250px;
}

.ad-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s;
}

.ad-card:hover img {
    transform: scale(1.05);
}

/* Label de publicidade */
.ad-label {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    z-index: 1;
}

.ad-link {
    display: block;
    width: 100%;
    height: 100%;
}

/* Responsividade */
@media (max-width: 1024px) {
    .ads-sidebar {
        width: 250px;
    }
}

@media (max-width: 768px) {
    .ads-sidebar {
        display: none; /* Oculta anúncios em telas menores */
    }
}
</style> 