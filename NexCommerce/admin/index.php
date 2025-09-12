<?php
include 'auth.php'; // Segurança em primeiro lugar!
include '../db_connect.php';
include 'templates/header.php';

// Busca estatísticas
$total_pedidos = $conn->query("SELECT COUNT(*) as total FROM pedidos")->fetch_assoc()['total'];
$total_vendas = $conn->query("SELECT SUM(valor_total) as total FROM pedidos")->fetch_assoc()['total'];
$total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE is_admin = 0")->fetch_assoc()['total'];
$total_produtos = $conn->query("SELECT COUNT(*) as total FROM produtos")->fetch_assoc()['total'];
?>

<div class="admin-header">
    <h1>Dashboard</h1>
    <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?>!</span>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total de Vendas</h3>
        <p class="stat-number">R$ <?php echo number_format($total_vendas ?? 0, 2, ',', '.'); ?></p>
    </div>
    <div class="stat-card">
        <h3>Pedidos Recebidos</h3>
        <p class="stat-number"><?php echo $total_pedidos ?? 0; ?></p>
    </div>
    <div class="stat-card">
        <h3>Clientes Cadastrados</h3>
        <p class="stat-number"><?php echo $total_usuarios ?? 0; ?></p>
    </div>
    <div class="stat-card">
        <h3>Produtos na Loja</h3>
        <p class="stat-number"><?php echo $total_produtos ?? 0; ?></p>
    </div>
</div>

<?php $conn->close(); ?>
    </div> </body>
</html>
<?php include 'templates/footer.php'; ?>