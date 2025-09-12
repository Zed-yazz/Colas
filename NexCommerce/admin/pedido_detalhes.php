<?php
include 'auth.php';
include '../db_connect.php';

$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($pedido_id == 0) {
    header("Location: pedidos.php");
    exit();
}

// Lógica para atualizar o status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $novo_status = $_POST['status'];
    $sql_update = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $novo_status, $pedido_id);
    $stmt_update->execute();
    $stmt_update->close();
}

// Busca os detalhes do pedido
$sql_pedido = "SELECT p.*, u.nome, u.email, u.telefone, u.endereco, u.numero, u.bairro, u.cidade, u.estado, u.cep
               FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?";
$stmt_pedido = $conn->prepare($sql_pedido);
$stmt_pedido->bind_param("i", $pedido_id);
$stmt_pedido->execute();
$pedido = $stmt_pedido->get_result()->fetch_assoc();

// Busca os itens do pedido
$sql_items = "SELECT pi.*, pr.nome as produto_nome FROM pedido_items pi JOIN produtos pr ON pi.produto_id = pr.id WHERE pi.pedido_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $pedido_id);
$stmt_items->execute();
$items = $stmt_items->get_result();

include 'templates/header.php';
?>

<div class="admin-header">
    <h1>Detalhes do Pedido #<?php echo $pedido['id']; ?></h1>
    <a href="pedidos.php" class="btn">&larr; Voltar para Pedidos</a>
</div>

<div class="details-grid">
    <div class="details-card">
        <h3>Informações do Cliente</h3>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($pedido['nome']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($pedido['telefone']); ?></p>
    </div>
    <div class="details-card">
        <h3>Endereço de Entrega</h3>
        <p><?php echo htmlspecialchars($pedido['endereco'] . ', ' . $pedido['numero']); ?></p>
        <p><?php echo htmlspecialchars($pedido['bairro'] . ', ' . $pedido['cidade'] . ' - ' . $pedido['estado']); ?></p>
        <p><strong>CEP:</strong> <?php echo htmlspecialchars($pedido['cep']); ?></p>
    </div>
    <div class="details-card">
        <h3>Status do Pedido</h3>
        <form action="pedido_detalhes.php?id=<?php echo $pedido_id; ?>" method="POST">
            <select name="status" class="form-control">
                <option value="Processando" <?php echo ($pedido['status'] == 'Processando') ? 'selected' : ''; ?>>Processando</option>
                <option value="Enviado" <?php echo ($pedido['status'] == 'Enviado') ? 'selected' : ''; ?>>Enviado</option>
                <option value="Entregue" <?php echo ($pedido['status'] == 'Entregue') ? 'selected' : ''; ?>>Entregue</option>
                <option value="Cancelado" <?php echo ($pedido['status'] == 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
            </select>
            <button type="submit" class="btn" style="margin-top: 10px; width: 100%;">Atualizar Status</button>
        </form>
    </div>
</div>

<h3>Itens do Pedido</h3>
<table class="admin-table">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php while($item = $items->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
                <td><?php echo $item['quantidade']; ?></td>
                <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                <td>R$ <?php echo number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">VALOR TOTAL</td>
            <td style="font-weight: bold;">R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></td>
        </tr>
    </tfoot>
</table>

<?php
$stmt_pedido->close();
$stmt_items->close();
$conn->close(); // Fechando a conexão APENAS UMA VEZ, no final.
?>
    </div> </body>
</html>