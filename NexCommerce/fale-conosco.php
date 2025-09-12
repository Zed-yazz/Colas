<?php include 'templates/header.php'; ?>

<div class="container" style="padding-top: 40px; padding-bottom: 40px;">
    <div style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;">
        <h1>Entre em Contato</h1>
        <p style="font-size: 1.2rem; color: #ccc;">Estamos aqui para ajudar! Sua dúvida, sugestão ou feedback é muito importante para nós. Escolha o melhor canal para você.</p>
    </div>

    <div class="footer-grid" style="gap: 50px;"> <div class="footer-col">
            <h4>Nossos Canais</h4>
            <ul style="font-size: 1.1rem;">
                <li><i class="fas fa-phone"></i> <strong>Telefone:</strong><br>(11) 2440-1234<br><small>(Seg. a Sex. das 9h às 18h)</small></li>
                <li style="margin-top:20px;"><i class="fab fa-whatsapp"></i> <strong>WhatsApp:</strong><br>(11) 99999-8888</li>
                <li style="margin-top:20px;"><i class="fas fa-envelope"></i> <strong>E-mail:</strong><br>contato@nexcommerce.com.br</li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Envie uma Mensagem</h4>
            <form action="#" method="POST" class="contact-form">
                <div class="form-group">
                    <label for="nome">Seu Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">Seu E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="mensagem">Sua Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn">Enviar Mensagem</button>
            </form>
            <p style="font-size: 0.8rem; margin-top: 10px; text-align: center;"><em>(Este formulário é apenas visual por enquanto.)</em></p>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>