<?php
session_start();
include 'db_connect.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto_perfil"]) && $_FILES["foto_perfil"]["error"] == 0) {
    $user_id = $_SESSION['usuario_id'];
    $target_dir = "assets/img/avatars/";
    
    $file = $_FILES["foto_perfil"];
    $file_tmp_name = $file["tmp_name"];

    // --- VERIFICAÇÃO ROBUSTA ---
    // 1. Verifica o MIME Type do arquivo para garantir que é uma imagem
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file_tmp_name);
    finfo_close($finfo);

    $allowed_mime_types = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    if (!in_array($mime_type, $allowed_mime_types)) {
        header("Location: minha-conta.php?status=nao_e_imagem");
        exit();
    }
    
    // 2. Verifica se o arquivo é realmente uma imagem com getimagesize
    if (getimagesize($file_tmp_name) === false) {
        header("Location: minha-conta.php?status=nao_e_imagem");
        exit();
    }

    // 3. Verifica o tamanho do arquivo (limite de 2MB)
    if ($file["size"] > 2000000) {
        header("Location: minha-conta.php?status=tamanho_excedido");
        exit();
    }

    // Cria um nome de arquivo único
    $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . "user_" . $user_id . "_" . time() . "." . $file_extension;
    
    // Tenta mover o arquivo para a pasta de uploads
    if (move_uploaded_file($file_tmp_name, $target_file)) {
        // Se mover com sucesso, atualiza o banco de dados
        $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $target_file, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['usuario_foto'] = $target_file; // Atualiza a foto na sessão
            header("Location: minha-conta.php?status=foto_ok");
        } else {
            header("Location: minha-conta.php?status=erro_db");
        }
        $stmt->close();
    } else {
        header("Location: minha-conta.php?status=erro_upload");
    }
    $conn->close();

} else {
    // Adiciona uma verificação para erros de upload mais específicos
    $error_code = isset($_FILES["foto_perfil"]["error"]) ? $_FILES["foto_perfil"]["error"] : -1;
    header("Location: minha-conta.php?status=erro_upload&code=" . $error_code);
}
exit();