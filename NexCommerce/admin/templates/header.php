<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - NexCommerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <header class="admin-top-header">
        <div class="header-brand">
            <a href="index.php">
                <img src="../assets/img/logo.png" alt="NexCommerce Logo">
                <span class="brand-text-desktop">NexCommerce Admin</span>
            </a>
        </div>

        <nav class="admin-top-nav desktop-nav">
            <?php $active_page = basename($_SERVER['PHP_SELF']); ?>
            <a href="index.php" class="<?php echo ($active_page == 'index.php') ? 'active' : ''; ?>"><span>Dashboard</span></a>
            <a href="produtos.php" class="<?php echo in_array($active_page, ['produtos.php', 'produto_form.php']) ? 'active' : ''; ?>"><span>Produtos</span></a>
            <a href="pedidos.php" class="<?php echo in_array($active_page, ['pedidos.php', 'pedido_detalhes.php']) ? 'active' : ''; ?>"><span>Pedidos</span></a>
            <a href="usuarios.php" class="<?php echo ($active_page == 'usuarios.php') ? 'active' : ''; ?>"><span>Usuários</span></a>
        </nav>

        <div class="header-actions desktop-actions">
            <a href="../index.php" target="_blank" class="btn btn-secondary">Ver Site</a>
            <a href="logout.php" class="btn btn-logout">Sair</a>
        </div>

        <button id="mobile-menu-toggle" class="mobile-menu-button">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <div id="mobile-menu" class="mobile-menu-overlay">
        <nav class="mobile-nav">
            <a href="index.php">Dashboard</a>
            <a href="produtos.php">Produtos</a>
            <a href="pedidos.php">Pedidos</a>
            <a href="usuarios.php">Usuários</a>
            <hr>
            <a href="../index.php" target="_blank">Ver Site</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>

    <div class="admin-main-content">