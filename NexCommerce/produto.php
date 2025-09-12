<?php include 'templates/header.php'; ?>
<?php include 'db_connect.php'; ?>

<div class="container product-page-container">
    <?php
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($product_id > 0) {
        $sql = "SELECT nome, descricao, preco, imagem_url, imagens_galeria, estoque, marca, especificacoes FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $main_image = htmlspecialchars($row["imagem_url"]);
            $gallery_images = [$main_image];
            if (!empty($row["imagens_galeria"])) {
                $gallery_images = array_merge($gallery_images, explode(',', $row["imagens_galeria"]));
            }
    ?>
    <div class="product-page-grid">
        <div class="product-gallery">
            <div class="main-image-container">
                <img src="<?php echo $main_image; ?>" alt="<?php echo htmlspecialchars($row["nome"]); ?>" id="mainImage">
            </div>
            <div class="thumbnail-container">
                <?php foreach($gallery_images as $img): ?>
                <img src="<?php echo htmlspecialchars(trim($img)); ?>" alt="Thumbnail" class="thumbnail-image">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="product-purchase-info">
            <h1 class="product-title"><?php echo htmlspecialchars($row["nome"]); ?></h1>
            <p class="product-brand">Marca: <strong><?php echo htmlspecialchars($row["marca"] ?? 'Não informada'); ?></strong></p>
            
            <div class="star-rating">
                <span>★★★★☆</span> (4.5 de 5)
            </div>

            <p class="product-price">R$ <?php echo number_format($row["preco"], 2, ',', '.'); ?></p>
            
            <p class="stock-info">
                <?php echo $row["estoque"] > 0 ? 'Em estoque' : 'Fora de estoque'; ?>
            </p>
            
            <form action="carrinho_acoes.php" method="POST">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="produto_id" value="<?php echo $product_id; ?>">
                
                <div class="quantity-selector">
                    <label for="quantity">Quantidade:</label>
                    <input type="number" id="quantity" name="quantidade" value="1" min="1" max="<?php echo $row["estoque"]; ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-buy">
                    <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                </button>
            </form>
            </div>
    </div>

    <div class="product-details-tabs">
        <nav class="tab-nav">
            <button class="tab-link active" data-tab="descricao">Descrição</button>
            <button class="tab-link" data-tab="especificacoes">Especificações</button>
            <button class="tab-link" data-tab="avaliacoes">Avaliações</button>
        </nav>
        <div class="tab-content-container">
            <div id="descricao" class="tab-content active">
                <h3>Descrição do Produto</h3>
                <p><?php echo nl2br(htmlspecialchars($row["descricao"])); ?></p>
            </div>
            <div id="especificacoes" class="tab-content">
                <h3>Especificações Técnicas</h3>
                <?php
                if (!empty($row["especificacoes"])) {
                    echo '<div class="spec-table">';
                    $specs = explode('|', $row["especificacoes"]);
                    foreach ($specs as $spec_item) {
                        $item_parts = explode(':', $spec_item, 2);
                        if (count($item_parts) == 2) {
                            $key = htmlspecialchars(trim($item_parts[0]));
                            $value = htmlspecialchars(trim($item_parts[1]));
                            echo '<div class="spec-row"><span class="spec-key">' . $key . '</span><span class="spec-value">' . $value . '</span></div>';
                        }
                    }
                    echo '</div>';
                } else {
                    echo '<p>Nenhuma especificação técnica disponível.</p>';
                }
                ?>
            </div>
            <div id="avaliacoes" class="tab-content">
                <h3>Avaliações de Clientes</h3>
                <p>Nenhuma avaliação ainda.</p>
            </div>
        </div>
    </div>

    <?php
        } else {
            echo "<p>Produto não encontrado.</p>";
        }
    } else {
        echo "<p>ID de produto inválido.</p>";
    }
    $stmt->close();
    $conn->close();
    ?>
</div>

<?php include 'templates/footer.php'; ?>