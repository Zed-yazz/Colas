<?php
include 'auth.php'; include '../db_connect.php'; include 'templates/header.php';
$sql = "SELECT p.id, p.data_pedido, p.valor_total, p.status, u.nome as nome_cliente FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.data_pedido DESC";
$result = $conn->query($sql);
?>
<div class="admin-header">
    <h1>Gerenciar Pedidos</h1>
</div>
<table class="admin-table">
    <thead>
        <tr><th>Pedido ID</th><th>Cliente</th><th>Data</th><th>Valor Total</th><th>Status</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="Pedido ID">#<?php echo $row['id']; ?></td>
                    <td data-label="Cliente"><?php echo htmlspecialchars($row['nome_cliente']); ?></td>
                    <td data-label="Data"><?php echo date('d/m/Y H:i', strtotime($row['data_pedido'])); ?></td>
                    <td data-label="Valor Total">R$ <?php echo number_format($row['valor_total'], 2, ',', '.'); ?></td>
                    <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td data-label="Ações" class="actions">
                        <a href="pedido_detalhes.php?id=<?php echo $row['id']; ?>"><i class="fas fa-eye"></i> Ver Detalhes</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhum pedido encontrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $conn->close(); ?>
</div></body></html>
<?php include 'templates/footer.php'; ?>