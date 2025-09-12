<?php
include 'templates/header.php';
include 'db_connect.php';

$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
$produtos_carrinho = [];
$subtotal = 0;

if (!empty($carrinho)) {
    // Pega os IDs dos produtos para buscar no banco de uma só vez
    $produto_ids = implode(',', array_keys($carrinho));
    $sql = "SELECT id, nome, preco, imagem_url FROM produtos WHERE id IN ($produto_ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $quantidade = $carrinho[$row['id']];
        $row['quantidade'] = $quantidade;
        $row['total_item'] = $row['preco'] * $quantidade;
        $subtotal += $row['total_item'];
        $produtos_carrinho[] = $row;
    }
}
?>

<div class="container cart-page">
    <h2>Seu Carrinho de Compras</h2>

    <?php if (empty($produtos_carrinho)): ?>
        <p>Seu carrinho está vazio.</p>
        <a href="produtos.php" class="btn">Continuar Comprando</a>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos_carrinho as $produto): ?>
                <tr>
                    <td>
                        <div class="cart-product-info">
                            <img src="<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <span><?php echo htmlspecialchars($produto['nome']); ?></span>
                        </div>
                    </td>
                    <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                    <td>
                        <form action="carrinho_acoes.php" method="POST">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                            <input type="number" name="quantidade" value="<?php echo $produto['quantidade']; ?>" min="1" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td>R$ <?php echo number_format($produto['total_item'], 2, ',', '.'); ?></td>
                    <td>
                        <form action="carrinho_acoes.php" method="POST">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                            <button type="submit" class="btn-remove">&times;</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Resumo do Pedido</h3>
            <p>Subtotal: <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></p>
            <p>Frete: <span>A calcular</span></p>
            <h4>Total: <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span></h4>
            <a href="checkout.php" class="btn btn-primary">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</div>

<?php
if ($conn) { $conn->close(); }
include 'templates/footer.php';
?>