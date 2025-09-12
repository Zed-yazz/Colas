<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }

include 'db_connect.php';
include 'templates/header.php';

$user_id = $_SESSION['usuario_id'];
$secao_ativa = isset($_GET['secao']) ? $_GET['secao'] : 'dados'; // A seção padrão é 'dados'

// Busca os dados do usuário para preencher os formulários
$sql_user = "SELECT * FROM usuarios WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_data = $stmt_user->get_result()->fetch_assoc();

// Lógica para exibir mensagens de status
$status_msg = ''; $status_type = '';
if(isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'dados_ok': $status_msg = "Dados atualizados com sucesso!"; $status_type = 'success'; break;
        case 'senha_ok': $status_msg = "Senha alterada com sucesso!"; $status_type = 'success'; break;
        case 'pedido_ok': $status_msg = "Pedido finalizado com sucesso!"; $status_type = 'success'; break;
        case 'senhas_nao_conferem': $status_msg = "Erro: As novas senhas não são iguais."; $status_type = 'error'; break;
        case 'senha_invalida': $status_msg = "Erro: A sua senha atual está incorreta."; $status_type = 'error'; break;
        case 'erro': $status_msg = "Ocorreu um erro. Tente novamente."; $status_type = 'error'; break;
    }
}
?>

<div class="container account-page">
    <h2>Minha Conta</h2>

    <?php if ($status_msg): ?>
        <div class="status-message <?php echo $status_type; ?>"><?php echo $status_msg; ?></div>
    <?php endif; ?>

    <div class="account-grid">
        <div class="account-sidebar">
            <div class="account-profile-pic">
                <img src="<?php echo htmlspecialchars($user_data['foto_perfil']); ?>" alt="Foto de Perfil">
                <form action="upload_foto.php" method="post" enctype="multipart/form-data">
                    <label for="foto-upload" class="btn">Trocar Foto</label>
                    <input type="file" id="foto-upload" name="foto_perfil" style="display: none;" onchange="this.form.submit()">
                </form>
            </div>
            <nav class="account-nav">
                <a href="minha-conta.php?secao=dados" class="<?php echo $secao_ativa == 'dados' ? 'active' : ''; ?>"><i class="fas fa-user-edit"></i> Meus Dados</a>
                <a href="minha-conta.php?secao=pedidos" class="<?php echo $secao_ativa == 'pedidos' ? 'active' : ''; ?>"><i class="fas fa-box-open"></i> Meus Pedidos</a>
                <a href="minha-conta.php?secao=senha" class="<?php echo $secao_ativa == 'senha' ? 'active' : ''; ?>"><i class="fas fa-key"></i> Alterar Senha</a>
            </nav>
        </div>

        <div class="account-content">
            <?php if ($secao_ativa == 'dados'): ?>
                <form action="atualizar_dados.php" method="POST">
                    <h3>Dados Pessoais</h3>
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user_data['nome']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail (não pode ser alterado)</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefone">Telefone / Celular</label>
                            <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($user_data['telefone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="data_nascimento">Data de Nascimento</label>
                            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($user_data['data_nascimento']); ?>">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($user_data['cpf']); ?>">
                    </div>

                    <hr>

                    <h3>Endereço de Entrega</h3>
                     <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($user_data['cep']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($user_data['endereco']); ?>">
                    </div>
                     <div class="form-row">
                        <div class="form-group">
                            <label for="numero">Número</label>
                            <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($user_data['numero']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="bairro">Bairro</label>
                            <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($user_data['bairro']); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                         <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($user_data['cidade']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" id="estado" name="estado" maxlength="2" value="<?php echo htmlspecialchars($user_data['estado']); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>

            <?php elseif ($secao_ativa == 'pedidos'): ?>
                <h3>Meus Pedidos</h3>
                <?php
                $sql_pedidos = "SELECT id, valor_total, data_pedido, status FROM pedidos WHERE usuario_id = ? ORDER BY data_pedido DESC";
                $stmt_pedidos = $conn->prepare($sql_pedidos);
                $stmt_pedidos->bind_param("i", $user_id);
                $stmt_pedidos->execute();
                $result_pedidos = $stmt_pedidos->get_result();
                if ($result_pedidos->num_rows > 0) {
                    echo '<table class="orders-table"><thead><tr><th>Pedido</th><th>Data</th><th>Valor</th><th>Status</th></tr></thead><tbody>';
                    while ($pedido = $result_pedidos->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>#' . str_pad($pedido['id'], 6, "0", STR_PAD_LEFT) . '</td>';
                        echo '<td>' . date('d/m/Y', strtotime($pedido['data_pedido'])) . '</td>';
                        echo '<td>R$ ' . number_format($pedido['valor_total'], 2, ',', '.') . '</td>';
                        echo '<td><span class="status-' . strtolower($pedido['status']) . '">' . htmlspecialchars($pedido['status']) . '</span></td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else { echo '<p>Você ainda não fez nenhum pedido.</p>'; }
                $stmt_pedidos->close();
                ?>
            
            <?php elseif ($secao_ativa == 'senha'): ?>
                <h3>Alterar Senha</h3>
                <form action="atualizar_senha.php" method="POST">
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" required>
                    </div>
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>
                    </div>
                    <div class="form-group">
                        <label for="confirma_nova_senha">Confirmar Nova Senha</label>
                        <input type="password" id="confirma_nova_senha" name="confirma_nova_senha" required>
                    </div>
                    <button type="submit" class="btn">Alterar Senha</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$stmt_user->close();
$conn->close();
include 'templates/footer.php';
?>