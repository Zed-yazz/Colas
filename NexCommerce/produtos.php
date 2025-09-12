<?php
include 'templates/header.php';
include 'db_connect.php';
$produtos_por_pagina = 12;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;
$offset = ($pagina_atual - 1) * $produtos_por_pagina;
$category_filter = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$ofertas_filter = isset($_GET['ofertas']) && $_GET['ofertas'] == '1';
$sql_total = "SELECT COUNT(*) as total FROM produtos";
$conditions = []; $params = []; $types = "";
if (!empty($category_filter)) { $conditions[] = "categoria = ?"; $params[] = $category_filter; $types .= "s"; }
if ($ofertas_filter) { $conditions[] = "em_oferta = 1"; }
if (!empty($conditions)) { $sql_total .= " WHERE " . implode(" AND ", $conditions); }
$stmt_total = $conn->prepare($sql_total);
if (!empty($params)) { $stmt_total->bind_param($types, ...$params); }
$stmt_total->execute();
$total_produtos = $stmt_total->get_result()->fetch_assoc()['total'];
$total_paginas = ceil($total_produtos / $produtos_por_pagina);
$sql_produtos = "SELECT id, nome, preco, preco_antigo, imagem_url, em_oferta FROM produtos";
if (!empty($conditions)) { $sql_produtos .= " WHERE " . implode(" AND ", $conditions); }
$sql_produtos .= " ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $produtos_por_pagina; $params[] = $offset; $types .= "ii";
$stmt_produtos = $conn->prepare($sql_produtos);
$stmt_produtos->bind_param($types, ...$params);
$stmt_produtos->execute();
$result = $stmt_produtos->get_result();
?>
<div class="content-wrapper">
    <div class="container page-title">
        <?php
        $page_title = 'Nossos Produtos';
        if (!empty($category_filter)) { $page_title = 'Categoria: ' . htmlspecialchars($category_filter); } 
        elseif ($ofertas_filter) { $page_title = 'Produtos em Oferta'; }
        echo "<h2>$page_title</h2>";
        ?>
    </div>
    <div class="container">
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="product-card"><a href="produto.php?id=' . $row["id"] . '"><div class="product-image-container"><img src="' . htmlspecialchars($row["imagem_url"]) . '" alt="' . htmlspecialchars($row["nome"]) . '">';
                    if (!empty($row["em_oferta"]) && $row["em_oferta"] == 1) { echo '<span class="sale-badge">Oferta!</span>'; }
                    echo '</div><h3>' . htmlspecialchars($row["nome"]) . '</h3>';
                    if (!empty($row["preco_antigo"]) && !empty($row["em_oferta"]) && $row["em_oferta"] == 1) { echo '<p class="price-old">R$ ' . number_format($row["preco_antigo"], 2, ',', '.') . '</p>'; }
                    echo '<p class="price">R$ ' . number_format($row["preco"], 2, ',', '.') . '</p></a><form action="carrinho_acoes.php" method="POST" style="margin-top: 10px;"><input type="hidden" name="action" value="add"><input type="hidden" name="produto_id" value="' . $row["id"] . '"><button type="submit" class="btn"><i class="fas fa-cart-plus"></i> Adicionar</button></form></div>';
                }
            } else { echo "<p>Nenhum produto encontrado.</p>"; }
            ?>
        </div>
        <nav class="pagination">
            <?php if ($total_paginas > 1): ?>
                <?php
                $query_params = [];
                if (!empty($category_filter)) $query_params['categoria'] = $category_filter;
                if ($ofertas_filter) $query_params['ofertas'] = '1';
                ?>
                <?php if ($pagina_atual > 1): ?><a href="?pagina=<?php echo $pagina_atual - 1; ?>&<?php echo http_build_query($query_params); ?>">&laquo;</a><?php endif; ?>
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?><a href="?pagina=<?php echo $i; ?>&<?php echo http_build_query($query_params); ?>" class="<?php echo ($i == $pagina_atual) ? 'active' : ''; ?>"><?php echo $i; ?></a><?php endfor; ?>
                <?php if ($pagina_atual < $total_paginas): ?><a href="?pagina=<?php echo $pagina_atual + 1; ?>&<?php echo http_build_query($query_params); ?>">&raquo;</a><?php endif; ?>
            <?php endif; ?>
        </nav>
    </div>
</div>
<?php
$stmt_total->close(); $stmt_produtos->close(); $conn->close();
include 'templates/footer.php';
?>