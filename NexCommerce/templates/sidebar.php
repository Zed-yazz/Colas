<aside class="sidebar">
    <button class="sidebar-close-btn" id="sidebar-close">&times;</button>
    <h3 class="sidebar-title">Categorias</h3>
    <ul class="sidebar-menu">
        <?php
        // Usamos @include_once para evitar erros se o arquivo já foi incluído em outro lugar
        @include_once __DIR__ . '/../db_connect.php';

        if ($conn) {
            // Pega a categoria que está ativa na URL (se houver)
            $active_category = isset($_GET['categoria']) ? $_GET['categoria'] : '';

            // Busca todas as categorias distintas do banco de dados
            $sql = "SELECT DISTINCT categoria FROM produtos WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria ASC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $category_name = htmlspecialchars($row['categoria']);
                    // Verifica se a categoria atual é a que está ativa
                    $class = ($active_category == $category_name) ? 'active' : '';
                    echo '<li><a href="produtos.php?categoria=' . urlencode($category_name) . '" class="' . $class . '">' . $category_name . '</a></li>';
                }
            }
        }
        ?>
    </ul>
</aside>