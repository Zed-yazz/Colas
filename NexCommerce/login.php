<?php
include 'db_connect.php';
session_start();

$error = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT id, nome, senha, foto_perfil FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['senha'])) {
            // Login bem-sucedido, armazena os dados na sessão
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_nome'] = $row['nome'];
            $_SESSION['usuario_foto'] = $row['foto_perfil']; // Linha Adicionada
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Senha incorreta.";
        }
    } else {
        $error = "Nenhum usuário encontrado com este email.";
    }
}
include 'templates/header.php';
?>

<div class="container form-container">
    <form method="POST" action="login.php" class="auth-form">
        <h2>Login</h2>
        <?php if(isset($error)) { echo "<p class='error' style='color: #ef4444; background: rgba(239,68,68,0.1); padding: 10px; border-radius: 5px;'>".$error."</p>"; } ?>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <button type="submit" class="btn">Entrar</button>
        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </form>
</div>

<?php include 'templates/footer.php'; ?>