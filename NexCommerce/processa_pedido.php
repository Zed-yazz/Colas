<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrinho'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['usuario_id'];
$carrinho = $_SESSION['carrinho'];
$subtotal = 0;

$conn->begin_transaction();

try {
    // 1. Calcula o total e busca preços atualizados
    $produto_ids = implode(',', array_keys($carrinho));
    $sql_produtos = "SELECT id, preco FROM produtos WHERE id IN ($produto_ids)";
    $result_produtos = $conn->query($sql_produtos);
    $produtos_db = [];
    while($row = $result_produtos->fetch_assoc()) {
        $produtos_db[$row['id']] = $row['preco'];
    }

    foreach ($carrinho as $produto_id => $quantidade) {
        $subtotal += $produtos_db[$produto_id] * $quantidade;
    }

    // 2. Insere o pedido na tabela `pedidos`
    $sql_pedido = "INSERT INTO pedidos (usuario_id, valor_total) VALUES (?, ?)";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("id", $user_id, $subtotal);
    $stmt_pedido->execute();
    $pedido_id = $conn->insert_id; // Pega o ID do pedido que acabamos de criar

    // 3. Insere cada item do carrinho na tabela `pedido_items`
    $sql_items = "INSERT INTO pedido_items (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
    $stmt_items = $conn->prepare($sql_items);
    foreach ($carrinho as $produto_id => $quantidade) {
        $preco_unitario = $produtos_db[$produto_id];
        $stmt_items->bind_param("iiid", $pedido_id, $produto_id, $quantidade, $preco_unitario);
        $stmt_items->execute();
    }

    // 4. Se tudo deu certo, confirma as operações e limpa o carrinho
    $conn->commit();
    unset($_SESSION['carrinho']);
    
    // Redireciona para a página de 'meus pedidos' com sucesso
    header("Location: minha-conta.php?secao=pedidos&status=pedido_ok");

} catch (Exception $e) {
    // 5. Se algo deu errado, desfaz tudo
    $conn->rollback();
    header("Location: checkout.php?status=erro");
}

$stmt_pedido->close();
$stmt_items->close();
$conn->close();
exit();