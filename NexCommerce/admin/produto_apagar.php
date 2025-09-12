<?php
include 'auth.php';
include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Sucesso
    } else {
        // Erro
    }
    $stmt->close();
}

header("Location: produtos.php");
exit();