<?php
// Inclui o cabeçalho e a conexão com o banco
include 'templates/header.php';
include 'db_connect.php';

// Verifica se o parâmetro 'q' (de query) foi enviado na URL
$search_query = isset($_GET['q']) ? $_GET['q'] : '';

// Sanitiza a entrada para evitar injeção de SQL
$sanitized_query = $conn->real_escape_string($search_query);
?>

<div class="container">
    <h2 class="search-results-title">
        Resultados da busca por: <span>"<?php echo htmlspecialchars($search_query); ?>"</span>
    </h2>
</div>

<div class="container">
    <div class="product-grid">
        <?php
        if (!empty($sanitized_query)) {
            // SQL para buscar produtos cujo nome OU descrição contenham o termo pesquisado
            $sql = "SELECT id, nome, preco, imagem_url FROM produtos WHERE nome LIKE '%$sanitized_query%' OR descricao LIKE '%$sanitized_query%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Loop para exibir cada produto encontrado
                while($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<a href="produto.php?id=' . $row["id"] . '">';
                    echo '<img src="' . htmlspecialchars($row["imagem_url"]) . '" alt="' . htmlspecialchars($row["nome"]) . '">';
                    echo '<h3>' . htmlspecialchars($row["nome"]) . '</h3>';
                    echo '<p class="price">R$ ' . number_format($row["preco"], 2, ',', '.') . '</p>';
                    echo '</a>';
                    echo '<a href="produto.php?id=' . $row["id"] . '" class="btn">Ver Detalhes</a>';
                    echo '</div>';
                }
            } else {
                // Mensagem se nenhum produto for encontrado
                echo "<p>Nenhum produto encontrado com o termo pesquisado.</p>";
            }
        } else {
            echo "<p>Por favor, digite um termo para buscar.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>