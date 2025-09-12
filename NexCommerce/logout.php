<?php
// Inicia a sessão para poder acessá-la
session_start();

// Remove todas as variáveis da sessão (como usuario_id, nome, etc.)
session_unset();

// Destrói completamente a sessão do servidor
session_destroy();

// Redireciona o usuário de volta para a página inicial
header("Location: index.php");
exit(); // Garante que o script pare de ser executado após o redirecionamento
?>