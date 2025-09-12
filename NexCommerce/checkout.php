<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. VERIFICA SE O USUÁRIO ESTÁ LOGADO
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?redirect=carrinho.php"); // Redireciona para o login e depois volta ao carrinho
    exit();
}

// 2. VERIFICA SE O CARRINHO NÃO ESTÁ VAZIO
if (empty($_SESSION['carrinho'])) {
    header("Location: carrinho.php");
    exit();
}

include 'templates/header.php';
include 'db_connect.php';

// Lógica para buscar os produtos do carrinho
$carrinho = $_SESSION['carrinho'];
$produtos_checkout = [];
$subtotal = 0;

$produto_ids = implode(',', array_keys($carrinho));
$sql = "SELECT id, nome, preco, imagem_url FROM produtos WHERE id IN ($produto_ids)";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $quantidade = $carrinho[$row['id']];
    $row['quantidade'] = $quantidade;
    $subtotal += $row['preco'] * $quantidade;
    $produtos_checkout[] = $row;
}
?>

<div class="container checkout-page">
    <div class="checkout-grid">
        <div class="checkout-form-container">
            <h2>Informações da Compra</h2>

            <form id="checkout-form" action="processa_pedido.php" method="POST">
                <section>
                    <h3><i class="fas fa-user-circle"></i> Contato e Entrega</h3>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço Completo</label>
                        <input type="text" id="endereco" name="endereco" placeholder="Rua, Av, etc." required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="numero">Número</label>
                            <input type="text" id="numero" name="numero" required>
                        </div>
                        <div class="form-group">
                            <label for="bairro">Bairro</label>
                            <input type="text" id="bairro" name="bairro" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" id="estado" name="estado" maxlength="2" placeholder="SP" required>
                        </div>
                    </div>
                </section>

                <section>
                    <h3><i class="fas fa-credit-card"></i> Forma de Pagamento</h3>
                    <div class="payment-method-selector">
                        <label><input type="radio" name="payment_method" value="cartao" checked> Cartão de Crédito</label>
                        <label><input type="radio" name="payment_method" value="pix"> PIX</label>
                        <label><input type="radio" name="payment_method" value="boleto"> Boleto Bancário</label>
                    </div>

                    <div id="cartao-form" class="payment-form active">
                        <div class="form-group">
                            <label for="card-number">Número do Cartão</label>
                            <input type="text" id="card-number" placeholder="0000 0000 0000 0000">
                        </div>
                        <div class="form-group">
                            <label for="card-name">Nome no Cartão</label>
                            <input type="text" id="card-name" placeholder="Como está escrito no cartão">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card-expiry">Validade (MM/AA)</label>
                                <input type="text" id="card-expiry" placeholder="MM/AA">
                            </div>
                            <div class="form-group">
                                <label for="card-cvv">CVV</label>
                                <input type="text" id="card-cvv" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <div id="pix-form" class="payment-form">
                        <p>Escaneie o QR Code abaixo com o app do seu banco para pagar:</p>
                        <div id="pix-qrcode" class="code-container"></div>
                        <p>Ou use o PIX Copia e Cola:</p>
                        <div class="copy-paste-field">
                            <input type="text" id="pix-key" value="000201265802br.gov.bcb.pix0136123e4567-e89b-12d3-a456-4266554400005303986540550.005802BR5913NEXCOMMERCE6009SAO PAULO62070503***6304E9C1" readonly>
                            <button type="button" id="copy-pix-btn"><i class="fas fa-copy"></i></button>
                        </div>
                    </div>

                    <div id="boleto-form" class="payment-form">
                        <p>Abaixo está a linha digitável e o código de barras para pagamento:</p>
                        <div class="copy-paste-field">
                            <input type="text" id="boleto-key" value="23793.38128 60073.748394 83920.000010 1 98230000055000" readonly>
                            <button type="button" id="copy-boleto-btn"><i class="fas fa-copy"></i></button>
                        </div>
                        <div class="code-container">
                            <img id="boleto-barcode"/>
                        </div>
                    </div>
                </section>
            </form>
        </div>

        <div class="checkout-summary-container">
            <h3>Resumo do Pedido</h3>
            <div class="summary-items">
                <?php foreach ($produtos_checkout as $produto): ?>
                <div class="summary-item">
                    <img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="">
                    <div class="item-details">
                        <span><?php echo htmlspecialchars($produto['nome']); ?> (x<?php echo $produto['quantidade']; ?>)</span>
                        <strong>R$ <?php echo number_format($produto['preco'] * $produto['quantidade'], 2, ',', '.'); ?></strong>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="summary-totals">
                <p>Subtotal <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></p>
                <p>Frete <span>Grátis</span></p>
                <hr>
                <h4>Total <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></h4>
            </div>
            <button type="submit" form="checkout-form" class="btn btn-primary btn-block">Finalizar Compra e Pagar</button>
        </div>
    </div>
</div>

<?php
if ($conn) { $conn->close(); }
include 'templates/footer.php';
?>