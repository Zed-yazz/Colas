<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['usuario_id'];
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_nova_senha = $_POST['confirma_nova_senha'];

    // Validações
    if (empty($senha_atual) || empty($nova_senha) || empty($confirma_nova_senha)) {
        header("Location: minha-conta.php?secao=senha&status=campos_vazios");
        exit();
    }
    if ($nova_senha !== $confirma_nova_senha) {
        header("Location: minha-conta.php?secao=senha&status=senhas_nao_conferem");
        exit();
    }

    // Verifica a senha atual
    $sql = "SELECT senha FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($senha_atual, $user['senha'])) {
        // Senha atual está correta, atualiza para a nova
        $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql_update = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $novo_hash, $user_id);
        if ($stmt_update->execute()) {
            header("Location: minha-conta.php?secao=senha&status=senha_ok");
        } else {
            header("Location: minha-conta.php?secao=senha&status=erro_db");
        }
        $stmt_update->close();
    } else {
        // Senha atual incorreta
        header("Location: minha-conta.php?secao=senha&status=senha_invalida");
    }
    $stmt->close();
    $conn->close();
}
exit();