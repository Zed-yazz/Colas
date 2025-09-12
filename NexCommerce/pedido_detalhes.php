<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Se o usuário não estiver logado, redireciona
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
include 'templates/header.php';

$user_id = $_SESSION['usuario_id'];
$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pedido_encontrado = false;

if ($pedido_id > 0) {
    // --- VERIFICAÇÃO DE SEGURANÇA ---
    // Busca o pedido SOMENTE se o ID do pedido e o ID do usuário baterem
    $sql_pedido = "SELECT p.*, u.nome, u.email, u.endereco, u.numero, u.bairro, u.cidade, u.estado, u.cep
                   FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id 
                   WHERE p.id = ? AND p.usuario_id = ?";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("ii", $pedido_id, $user_id);
    $stmt_pedido->execute();
    $result_pedido = $stmt_pedido->get_result();
    
    if ($result_pedido->num_rows > 0) {
        $pedido = $result_pedido->fetch_assoc();
        $pedido_encontrado = true;

        // Busca os itens do pedido
        $sql_items = "SELECT pi.*, pr.nome as produto_nome, pr.imagem_url 
                      FROM pedido_items pi JOIN produtos pr ON pi.produto_id = pr.id 
                      WHERE pi.pedido_id = ?";
        $stmt_items = $conn->prepare($sql_items);
        $stmt_items->bind_param("i", $pedido_id);
        $stmt_items->execute();
        $items_result = $stmt_items->get_result();
    }
}
?>

<div class="container order-details-page">

    <?php if ($pedido_encontrado): ?>
        <h2>Detalhes do Pedido #<?php echo str_pad($pedido['id'], 6, "0", STR_PAD_LEFT); ?></h2>
        
        <div class="order-details-header">
            <div><strong>Data do Pedido:</strong> <?php echo date('d/m/Y', strtotime($pedido['data_pedido'])); ?></div>
            <div><strong>Valor Total:</strong> R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></div>
            <div><strong>Status:</strong> <span class="status-<?php echo strtolower(htmlspecialchars($pedido['status'])); ?>"><?php echo htmlspecialchars($pedido['status']); ?></span></div>
        </div>

        <h3>Itens Comprados</h3>
        <div class="order-items-list">
            <?php while($item = $items_result->fetch_assoc()): ?>
            <div class="summary-item">
                <img src="<?php echo htmlspecialchars($item['imagem_url']); ?>" alt="<?php echo htmlspecialchars($item['produto_nome']); ?>">
                <div class="item-details">
                    <span><?php echo htmlspecialchars($item['produto_nome']); ?></span>
                    <small>Quantidade: <?php echo $item['quantidade']; ?></small>
                </div>
                <strong>R$ <?php echo number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?></strong>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="order-details-grid">
            <div class="details-card">
                <h3>Endereço de Entrega</h3>
                <p><?php echo htmlspecialchars($pedido['endereco'] . ', ' . $pedido['numero']); ?></p>
                <p><?php echo htmlspecialchars($pedido['bairro'] . ', ' . $pedido['cidade'] . ' - ' . $pedido['estado']); ?></p>
                <p><strong>CEP:</strong> <?php echo htmlspecialchars($pedido['cep']); ?></p>
            </div>
            <div class="details-card">
                <h3>Informações do Cliente</h3>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($pedido['nome']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
            </div>
        </div>

    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-exclamation-circle"></i>
            <h3>Pedido não encontrado</h3>
            <p>Não foi possível encontrar os detalhes deste pedido ou você não tem permissão para visualizá-lo.</p>
            <a href="meus-pedidos.php" class="btn">Voltar para Meus Pedidos</a>
        </div>
    <?php endif; ?>

</div>

<?php
if (isset($stmt_pedido)) $stmt_pedido->close();
if (isset($stmt_items)) $stmt_items->close();
$conn->close();
include 'templates/footer.php';
?>