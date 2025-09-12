<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexCommerce - Sua Loja de Tecnologia e Escritório</title>
    
    <link rel="icon" href="favicon.png" type="image/png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="assets/css/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container navbar-container">
                <button id="menu-toggle" class="menu-toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                
                <a href="index.php" class="logo-container">
                    <img src="assets/img/logo.png" alt="NexCommerce Logo" class="header-logo" style="height: 50px !important; width: auto !important;">
                    <span class="logo-text">NexCommerce</span>
                </a>
                
                <div class="search-bar">
                    <form action="busca.php" method="GET">
                        <input type="search" name="q" placeholder="Buscar produtos..." required>
                        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                    </form>
                </div>

                <div class="nav-actions">
                    <a href="produtos.php" class="nav-link-item">Produtos</a>
                    
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <a href="meus-pedidos.php" class="nav-link-item">Meus Pedidos</a>
                        
                        <div class="user-menu">
                            <img src="<?php echo htmlspecialchars($_SESSION['usuario_foto'] ?? 'assets/img/avatars/default.png'); ?>" alt="Foto de Perfil" id="user-menu-toggle" class="profile-pic">
                            <div id="user-dropdown-menu" class="dropdown-menu">
                                <a href="minha-conta.php">Minha Conta</a>
                                <a href="logout.php">Sair</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="nav-link-item">Login</a>
                    <?php endif; ?>
                    
                    <a href="carrinho.php" class="cart-icon" aria-label="Carrinho de Compras">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        $cart_count = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0;
                        if ($cart_count > 0) {
                            echo '<span class="cart-count">' . $cart_count . '</span>';
                        }
                        ?>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <?php include 'sidebar.php'; ?>
    <main>