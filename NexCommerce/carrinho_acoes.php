<?php
session_start();

// Inicializa o carrinho se ele não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'add' && isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;

    // Se o produto já está no carrinho, soma a quantidade
    if (isset($_SESSION['carrinho'][$produto_id])) {
        $_SESSION['carrinho'][$produto_id] += $quantidade;
    } else {
        // Se não, adiciona ao carrinho
        $_SESSION['carrinho'][$produto_id] = $quantidade;
    }
}

if ($action == 'update' && isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);

    if ($quantidade > 0) {
        $_SESSION['carrinho'][$produto_id] = $quantidade;
    } else {
        // Remove se a quantidade for 0 ou menor
        unset($_SESSION['carrinho'][$produto_id]);
    }
}

if ($action == 'remove' && isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    unset($_SESSION['carrinho'][$produto_id]);
}

// Redireciona de volta para a página do carrinho
header('Location: carrinho.php');
exit();