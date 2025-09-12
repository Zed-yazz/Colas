<?php
include 'db_connect.php';
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta dos dados do formulário
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $data_nascimento = $conn->real_escape_string($_POST['data_nascimento']);
    $cpf = $conn->real_escape_string($_POST['cpf']);

    // Prepara e executa a inserção
    $sql = "INSERT INTO usuarios (nome, email, senha, telefone, data_nascimento, cpf) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nome, $email, $senha, $telefone, $data_nascimento, $cpf);

    if ($stmt->execute()) {
        header("Location: login.php?status=cadastro_ok");
        exit();
    } else {
        if ($conn->errno == 1062) { // Código de erro para entrada duplicada
            $error = "Este e-mail já está cadastrado. Tente fazer login.";
        } else {
            $error = "Ocorreu um erro ao realizar o cadastro. Tente novamente.";
        }
    }
    $stmt->close();
    $conn->close();
}
include 'templates/header.php';
?>

<div class="container form-container">
    <form method="POST" action="cadastro.php" class="auth-form">
        <h2>Crie sua Conta</h2>
        <?php if(isset($error)) { echo "<p class='error' style='color: #ef4444; background: rgba(239,68,68,0.1); padding: 10px; border-radius: 5px; text-align: center;'>".$error."</p>"; } ?>
        
        <h3>Dados de Acesso</h3>
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>

        <h3>Dados Pessoais</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="telefone">Telefone / Celular:</label>
                <input type="tel" id="telefone" name="telefone" placeholder="(11) 99999-8888">
            </div>
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento">
            </div>
        </div>
        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00">
        </div>
        
        <button type="submit" class="btn">Cadastrar</button>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </form>
</div>

<?php include 'templates/footer.php'; ?>