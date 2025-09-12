<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Se o usuário não estiver logado, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?redirect=meus-pedidos.php"); // Pede para logar e depois voltar
    exit();
}

include 'db_connect.php';
include 'templates/header.php';

$user_id = $_SESSION['usuario_id'];
?>

<div class="container account-page">
    <h2>Meus Pedidos</h2>
    <p>Acompanhe aqui o histórico de todas as suas compras na NexCommerce.</p>

    <div class="account-content" style="background: none; padding: 0; margin-top: 30px;">
        <?php
        // Busca todos os pedidos do usuário, do mais recente para o mais antigo
        $sql_pedidos = "SELECT id, valor_total, data_pedido, status FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC";
        $stmt_pedidos = $conn->prepare($sql_pedidos);
        $stmt_pedidos->bind_param("i", $user_id);
        $stmt_pedidos->execute();
        $result_pedidos = $stmt_pedidos->get_result();

        if ($result_pedidos->num_rows > 0) {
            // Se encontrou pedidos, mostra a tabela
            echo '<table class="orders-table">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Data</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($pedido = $result_pedidos->fetch_assoc()) {
                echo '<tr>';
                echo '<td>#' . str_pad($pedido['id'], 6, "0", STR_PAD_LEFT) . '</td>';
                echo '<td>' . date('d/m/Y', strtotime($pedido['data_pedido'])) . '</td>';
                echo '<td>R$ ' . number_format($pedido['valor_total'], 2, ',', '.') . '</td>';
                echo '<td><span class="status-' . strtolower(htmlspecialchars($pedido['status'])) . '">' . htmlspecialchars($pedido['status']) . '</span></td>';
                
                // LINK CORRIGIDO AQUI
                echo '<td><a href="pedido_detalhes.php?id=' . $pedido['id'] . '" class="btn btn-sm">Ver Detalhes</a></td>';
                
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            // Se não encontrou pedidos, mostra uma mensagem amigável
            echo '<div class="empty-state">';
            echo '  <i class="fas fa-box-open"></i>';
            echo '  <h3>Você ainda não fez nenhum pedido.</h3>';
            echo '  <p>Que tal explorar nossos produtos e encontrar a oferta perfeita para você?</p>';
            echo '  <a href="produtos.php" class="btn btn-primary">Ver Produtos</a>';
            echo '</div>';
        }
        $stmt_pedidos->close();
        ?>
    </div>
</div>

<?php
$conn->close();
include 'templates/footer.php';
?>