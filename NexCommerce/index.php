<?php 
include 'templates/header.php';
include 'db_connect.php'; 
?>
<div class="content-wrapper">
    <section class="hero-slider-section">
        <div class="swiper hero-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="assets/img/banner1.jpg" alt="Banner promocional 1"></div>
                <div class="swiper-slide"><img src="assets/img/banner2.jpg" alt="Banner promocional 2"></div>
                <div class="swiper-slide"><img src="assets/img/banner3.jpg" alt="Banner promocional 3"></div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card"><i class="fas fa-credit-card"></i><h4>Pagamento Facilitado</h4><p>Pague com segurança no Cartão de Crédito, PIX ou Boleto.</p></div>
                <div class="feature-card"><i class="fas fa-truck-fast"></i><h4>Entrega Rápida</h4><p>Receba seus produtos com agilidade e segurança em todo o Brasil.</p></div>
                <div class="feature-card"><i class="fas fa-headset"></i><h4>Suporte Especializado</h4><p>Nossa equipe de especialistas está pronta para te ajudar a fazer a escolha certa.</p></div>
            </div>
        </div>
    </section>

    <section class="category-banners-section">
        <div class="container">
            <div class="banners-grid">
                <a href="produtos.php?categoria=Notebooks" class="category-banner-card">
                    <img src="assets/img/banner_notebooks.jpg" alt="Notebooks de Alta Performance">
                    <div class="banner-text-overlay">
                        <h2>Notebooks de Alta Performance</h2>
                        <span>Conferir Agora</span>
                    </div>
                </a>
                <a href="produtos.php?categoria=Tecnologia" class="category-banner-card">
                    <img src="assets/img/banner_computadores.jpg" alt="Computadores e Setups">
                    <div class="banner-text-overlay">
                        <h2>PCs e Setups Completos</h2>
                        <span>Montar o Seu</span>
                    </div>
                </a>
                <a href="produtos.php?categoria=Móveis" class="category-banner-card">
                    <img src="assets/img/banner_moveis.jpg" alt="Móveis para Escritório">
                    <div class="banner-text-overlay">
                        <h2>Conforto e Ergonomia</h2>
                        <span>Ver Móveis</h2>
                        <span>Ver Móveis</span>
                    </div>
                </a>
            </div>
        </div>
    </section>
    
    <section class="offers-section">
        <div class="container">
            <h2 class="section-title-highlight">🔥 Produtos em Oferta</h2>
            <div class="product-grid">
                <?php
                $sql_ofertas = "SELECT * FROM produtos WHERE em_oferta = 1 LIMIT 4";
                $result_ofertas = $conn->query($sql_ofertas);
                if ($result_ofertas && $result_ofertas->num_rows > 0) {
                    while($row = $result_ofertas->fetch_assoc()) {
                        echo '<div class="product-card"><a href="produto.php?id=' . $row["id"] . '"><div class="product-image-container"><img src="' . htmlspecialchars($row["imagem_url"]) . '" alt="' . htmlspecialchars($row["nome"]) . '"><span class="sale-badge">Oferta!</span></div><h3>' . htmlspecialchars($row["nome"]) . '</h3>';
                        if (!empty($row["preco_antigo"])) { echo '<p class="price-old">R$ ' . number_format($row["preco_antigo"], 2, ',', '.') . '</p>'; }
                        echo '<p class="price">R$ ' . number_format($row["preco"], 2, ',', '.') . '</p></a><form action="carrinho_acoes.php" method="POST" style="margin-top: 10px;"><input type="hidden" name="action" value="add"><input type="hidden" name="produto_id" value="' . $row["id"] . '"><button type="submit" class="btn"><i class="fas fa-cart-plus"></i> Adicionar</button></form></div>';
                    }
                } else {
                    echo "<p>Nenhuma oferta encontrada no momento.</p>";
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="produtos.php?ofertas=1" class="btn btn-secondary">Ver Todas as Ofertas <i class="fas fa-arrow-right ml-2"></i></a>
            </div>
        </div>
    </section>
    
    <section id="delivery-section" class="delivery-section">
        <div class="container delivery-grid">
            <div class="delivery-text">
                <h2>Nossa Entrega é Pensada em Você</h2>
                <p>Sabemos que a ansiedade para receber um produto novo é grande. Por isso, na NexCommerce, o processo de entrega é uma de nossas prioridades máximas.</p>
                <p>Temos parceria com as principais transportadoras do país, o que nos permite oferecer fretes competitivos e prazos de entrega reduzidos para todo o Brasil.</p>
                <button id="calculate-shipping-btn" class="btn btn-primary">Calcular Frete</button>
            </div>
            <div class="delivery-image">
                <img src="https://rastreio-correios.r3ck.com.br/assets/img/caminhao-correios.png" alt="Caixa sendo preparada para entrega">
            </div>
        </div>
    </section>

    <section class="carousel-section">
        <div class="container">
            <h2 class="section-title-highlight">✨ Recomendados para Você</h2>
            <div class="product-carousel-swiper swiper">
                <div class="swiper-wrapper">
                    <?php
                    $sql_carousel = "SELECT id, nome, preco, imagem_url FROM produtos ORDER BY RAND() LIMIT 10";
                    $result_carousel = $conn->query($sql_carousel);
                    if ($result_carousel && $result_carousel->num_rows > 0) {
                        while($row_carousel = $result_carousel->fetch_assoc()) {
                            echo '<div class="swiper-slide"><a href="produto.php?id=' . $row_carousel["id"] . '" class="product-card-small"><img src="' . htmlspecialchars($row_carousel["imagem_url"]) . '" alt="' . htmlspecialchars($row_carousel["nome"]) . '"><h3>' . htmlspecialchars($row_carousel["nome"]) . '</h3><p class="price">R$ ' . number_format($row_carousel["preco"], 2, ',', '.') . '</p></a></div>';
                        }
                    } else {
                        echo '<div class="swiper-slide"><p>Nenhum produto recomendado encontrado.</p></div>';
                    }
                    ?>
                </div>
                <div class="swiper-button-next product-carousel-next"></div>
                <div class="swiper-button-prev product-carousel-prev"></div>
                <div class="swiper-pagination product-carousel-pagination"></div>
            </div>
            <div class="text-center mt-4">
                <a href="produtos.php" class="btn btn-secondary">Ver Mais Produtos <i class="fas fa-arrow-right ml-2"></i></a>
            </div>
        </div>
    </section>
</div>
<?php 
if ($conn) { $conn->close(); }
include 'templates/footer.php'; 
?>
