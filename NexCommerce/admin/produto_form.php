<?php
include 'auth.php';
include '../db_connect.php';

// Inicializa as variáveis para evitar erros
$produto = [
    'id' => '', 'nome' => '', 'descricao' => '', 'preco' => '', 'preco_antigo' => '',
    'categoria' => '', 'marca' => '', 'estoque' => '', 'em_oferta' => 0, 'imagem_url' => '', 'imagens_galeria' => ''
];
$page_title = "Adicionar Novo Produto";
$error = null;

// Se um ID for passado na URL, busca os dados do produto para edição
if (isset($_GET['id'])) {
    $page_title = "Editar Produto";
    $id = intval($_GET['id']);
    $sql_fetch = "SELECT * FROM produtos WHERE id = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();
    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    }
    $stmt_fetch->close();
}

// Lógica para salvar os dados quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ? intval($_POST['id']) : null;
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $preco_antigo = !empty($_POST['preco_antigo']) ? $_POST['preco_antigo'] : null;
    $categoria = $_POST['categoria'];
    $marca = $_POST['marca'];
    $estoque = intval($_POST['estoque']);
    $em_oferta = isset($_POST['em_oferta']) ? 1 : 0;
    $imagem_atual = $_POST['imagem_atual'];
    $galeria_atual = $_POST['galeria_atual'];
    
    $imagem_url = $imagem_atual;
    $imagens_galeria_array = !empty($galeria_atual) ? explode(',', $galeria_atual) : [];

    // Processa a imagem principal
    if (isset($_FILES['imagem_principal']) && $_FILES['imagem_principal']['error'] == 0) {
        $target_dir = "../assets/img/products/";
        $image_name = time() . '_' . basename($_FILES["imagem_principal"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["imagem_principal"]["tmp_name"], $target_file)) {
            $imagem_url = 'assets/img/products/' . $image_name;
        }
    }

    // Processa as imagens da galeria
    if (isset($_FILES['imagens_galeria']) && !empty($_FILES['imagens_galeria']['name'][0])) {
        $total_files = count($_FILES['imagens_galeria']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['imagens_galeria']['error'][$i] == 0) {
                $target_dir = "../assets/img/products/";
                $gallery_image_name = time() . '_gallery_' . $i . '_' . basename($_FILES["imagens_galeria"]["name"][$i]);
                $target_file_gallery = $target_dir . $gallery_image_name;

                if (move_uploaded_file($_FILES["imagens_galeria"]["tmp_name"][$i], $target_file_gallery)) {
                    $imagens_galeria_array[] = 'assets/img/products/' . $gallery_image_name;
                }
            }
        }
    }
    
    $imagens_galeria_string = implode(',', array_filter($imagens_galeria_array));

    if ($id) { // UPDATE
        $sql_save = "UPDATE produtos SET nome=?, descricao=?, preco=?, preco_antigo=?, categoria=?, marca=?, estoque=?, em_oferta=?, imagem_url=?, imagens_galeria=? WHERE id=?";
        $stmt_save = $conn->prepare($sql_save);
        $stmt_save->bind_param("ssddssiissi", $nome, $descricao, $preco, $preco_antigo, $categoria, $marca, $estoque, $em_oferta, $imagem_url, $imagens_galeria_string, $id);
    } else { // INSERT
        $sql_save = "INSERT INTO produtos (nome, descricao, preco, preco_antigo, categoria, marca, estoque, em_oferta, imagem_url, imagens_galeria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_save = $conn->prepare($sql_save);
        $stmt_save->bind_param("ssddssiiss", $nome, $descricao, $preco, $preco_antigo, $categoria, $marca, $estoque, $em_oferta, $imagem_url, $imagens_galeria_string);
    }

    if ($stmt_save->execute()) {
        header("Location: produtos.php");
        exit();
    } else {
        $error = "Erro ao salvar o produto: " . $stmt_save->error;
    }
    $stmt_save->close();
}

include 'templates/header.php';
?>

<div class="admin-header">
    <h1><?php echo htmlspecialchars($page_title); ?></h1>
    <a href="produtos.php" class="btn">&larr; Voltar para a Lista</a>
</div>

<?php if (isset($error)): ?>
    <p class="error-msg"><?php echo $error; ?></p>
<?php endif; ?>

<form action="produto_form.php<?php echo isset($produto['id']) && $produto['id'] ? '?id='.$produto['id'] : ''; ?>" method="POST" class="admin-form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
    <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($produto['imagem_url']); ?>">
    <input type="hidden" name="galeria_atual" value="<?php echo htmlspecialchars($produto['imagens_galeria']); ?>">

    <div class="form-section">
        <h3>Informações Básicas</h3>
        <div class="form-group">
            <label for="nome">Nome do Produto</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="6"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
        </div>
    </div>

    <div class="form-section">
        <h3>Detalhes e Preços</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="categoria">Categoria</label>
                <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($produto['categoria']); ?>">
            </div>
            <div class="form-group">
                <label for="marca">Marca</label>
                <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($produto['marca']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="preco">Preço (ex: 1299.90)</label>
                <input type="text" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
            </div>
            <div class="form-group">
                <label for="preco_antigo">Preço Antigo (Opcional)</label>
                <input type="text" id="preco_antigo" name="preco_antigo" value="<?php echo htmlspecialchars($produto['preco_antigo']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="estoque">Estoque</label>
                <input type="number" id="estoque" name="estoque" value="<?php echo htmlspecialchars($produto['estoque']); ?>">
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" id="em_oferta" name="em_oferta" value="1" <?php echo ($produto['em_oferta'] == 1) ? 'checked' : ''; ?>>
                <label for="em_oferta"> Marcar como Oferta</label>
            </div>
        </div>
    </div>
    
    <div class="form-section">
        <h3>Mídia do Produto</h3>
        <div class="form-group">
            <label for="imagem_principal">Imagem Principal</label>
            <input type="file" id="imagem_principal" name="imagem_principal" class="file-input" accept="image/*">
            <?php if (!empty($produto['imagem_url'])): ?>
                <div class="image-preview"><img src="../<?php echo htmlspecialchars($produto['imagem_url']); ?>" alt="Imagem atual"></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="imagens_galeria">Imagens da Galeria (máx. 10, pode selecionar várias)</label>
            <input type="file" id="imagens_galeria" name="imagens_galeria[]" class="file-input" multiple accept="image/*">
            <?php if (!empty($produto['imagens_galeria'])): ?>
                <div class="gallery-preview">
                    <p>Imagens Atuais na Galeria:</p>
                    <?php
                    $galeria_array = explode(',', $produto['imagens_galeria']);
                    foreach ($galeria_array as $img_path) {
                        if (!empty(trim($img_path))) {
                            echo '<div class="gallery-thumb"><img src="../' . htmlspecialchars(trim($img_path)) . '" alt="Imagem da galeria"></div>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Salvar Produto</button>
    </div>
</form>

<?php $conn->close(); ?>
</div> </body>
</html>
<?php include 'templates/footer.php'; ?>