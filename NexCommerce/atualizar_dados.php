<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['usuario_id'];
    
    // Coleta todos os dados do formulário
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = $_POST['cpf'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    // Prepara o SQL para atualizar todos os campos
    $sql = "UPDATE usuarios SET 
                nome = ?, telefone = ?, data_nascimento = ?, cpf = ?, 
                cep = ?, endereco = ?, numero = ?, bairro = ?, 
                cidade = ?, estado = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    // 's' para string, 'i' para integer
    $stmt->bind_param("ssssssssssi", 
        $nome, $telefone, $data_nascimento, $cpf, 
        $cep, $endereco, $numero, $bairro, 
        $cidade, $estado, $user_id
    );

    if ($stmt->execute()) {
        $_SESSION['usuario_nome'] = $nome; // Atualiza o nome na sessão
        header("Location: minha-conta.php?status=dados_ok");
    } else {
        header("Location: minha-conta.php?status=erro");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: minha-conta.php");
}
exit();