</main>
    
    <footer class="footer-main">
        <div class="container footer-grid">
            <div class="footer-col">
                <h4>NexCommerce</h4>
                <p>Sua loja de referência em tecnologia e móveis para escritório, oferecendo produtos de alta qualidade para impulsionar sua produtividade e conforto.</p>
            </div>

            <div class="footer-col">
                <h4>Links Rápidos</h4>
                <ul>
                    <li><a href="index.php">Página Inicial</a></li>
                    <li><a href="produtos.php">Todos os Produtos</a></li>
                    <li><a href="sobre-nos.php">Sobre Nós</a></li>
                    <li><a href="fale-conosco.php">Fale Conosco</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contato</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> R. Força Pública, 89 - Centro, Guarulhos - SP</li>
                    <li><i class="fas fa-envelope"></i> contato@nexcommerce.com.br</li>
                    <li><i class="fas fa-phone"></i> (11) 2440-1234</li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Siga-nos</h4>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> NexCommerce. Todos os direitos reservados.</p>
        </div>
    </footer>

    <a href="https://wa.me/5511999999999?text=Olá!%20Gostaria%20de%20ajuda%20com%20uma%20compra." class="whatsapp-button" target="_blank">
        <img src="https://i.postimg.cc/g2DmZbCq/zap.png" alt="WhatsApp"> </a>
        <div id="shipping-modal" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close-btn">&times;</button>
            <h3>Calcular Frete e Prazo</h3>
            <p>Digite seu CEP abaixo para ver as opções de entrega.</p>
            <div class="shipping-form">
                <input type="text" id="cep-input" placeholder="00000-000">
                <button id="cep-submit-btn" class="btn">Calcular</button>
            </div>
            <div id="shipping-results" class="shipping-results">
                </div>
        </div>
    </div>
 <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>
</html>