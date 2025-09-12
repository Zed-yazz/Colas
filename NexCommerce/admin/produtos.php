<?php
include 'auth.php'; include '../db_connect.php'; include 'templates/header.php';
$itens_por_pagina = 30;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $itens_por_pagina;
$total_produtos = $conn->query("SELECT COUNT(*) as total FROM produtos")->fetch_assoc()['total'];
$total_paginas = ceil($total_produtos / $itens_por_pagina);
$sql = "SELECT id, nome, preco, estoque, imagem_url FROM produtos ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $itens_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="admin-header"><h1>Gerenciar Produtos</h1><a href="produto_form.php" class="btn">Adicionar Novo Produto</a></div>
<table class="admin-table">
    <thead><tr><th>Imagem</th><th>ID</th><th>Nome</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr></thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
            <tr>
                <td data-label="Imagem"><img src="../<?php echo htmlspecialchars($row['imagem_url'] ?? ''); ?>" class="table-thumb"></td>
                <td data-label="ID"><?php echo $row['id']; ?></td>
                <td data-label="Nome"><?php echo htmlspecialchars($row['nome']); ?></td>
                <td data-label="Preço">R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></td>
                <td data-label="Estoque"><?php echo $row['estoque']; ?></td>
                <td data-label="Ações" class="actions">
                    <a href="produto_form.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit"></i></a>
                    <a href="produto_apagar.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="6" style="text-align: center;">Nenhum produto encontrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php include 'templates/footer.php'; ?>