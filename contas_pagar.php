<?php
session_start();
include('db_connection.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Buscar todas as contas a pagar do usuário
$sql = "SELECT * FROM contas_pagar WHERE id_usuario = ? ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$contas = $stmt->fetchAll();

// Adicionar uma nova conta
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $data_vencimento = $_POST['data_vencimento'];
    $valor = $_POST['valor'];
    $status = $_POST['status'];

    // Inserir a nova conta no banco de dados
    $sql_insert = "INSERT INTO contas_pagar (id_usuario, descricao, data_vencimento, valor, status) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([$id_usuario, $descricao, $data_vencimento, $valor, $status]);

    // Redireciona para a página após a inserção
    header('Location: contas_pagar.php');
    exit;
}

// Atualizar o status de uma conta
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id_conta = $_GET['id'];
    $novo_status = $_GET['status'];

    // Atualizar o status da conta
    $sql_update = "UPDATE contas_pagar SET status = ? WHERE id_conta = ? AND id_usuario = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$novo_status, $id_conta, $id_usuario]);

    // Redireciona para a página após a atualização
    header('Location: contas_pagar.php');
    exit;
}

// Excluir conta
if (isset($_GET['excluir_id'])) {
    $id_conta_excluir = $_GET['excluir_id'];

    // Excluir a conta do banco de dados
    $sql_excluir = "DELETE FROM contas_pagar WHERE id_conta = ? AND id_usuario = ?";
    $stmt_excluir = $pdo->prepare($sql_excluir);
    $stmt_excluir->execute([$id_conta_excluir, $id_usuario]);

    // Redireciona para a página após a exclusão
    header('Location: contas_pagar.php');
    exit;
}

// Editar conta
if (isset($_GET['editar_id'])) {
    $id_conta_editar = $_GET['editar_id'];
    $sql_editar = "SELECT * FROM contas_pagar WHERE id_conta = ? AND id_usuario = ?";
    $stmt_editar = $pdo->prepare($sql_editar);
    $stmt_editar->execute([$id_conta_editar, $id_usuario]);
    $conta_editar = $stmt_editar->fetch();

    // Se a conta não for encontrada, redireciona para a página de contas
    if (!$conta_editar) {
        header('Location: contas_pagar.php');
        exit;
    }
}

// Atualizar conta editada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_id_conta'])) {
    $id_conta = $_POST['editar_id_conta'];
    $descricao = $_POST['descricao'];
    $data_vencimento = $_POST['data_vencimento'];
    $valor = $_POST['valor'];
    $status = $_POST['status'];

    // Atualizar a conta no banco de dados
    $sql_update_conta = "UPDATE contas_pagar SET descricao = ?, data_vencimento = ?, valor = ?, status = ? WHERE id_conta = ? AND id_usuario = ?";
    $stmt_update_conta = $pdo->prepare($sql_update_conta);
    $stmt_update_conta->execute([$descricao, $data_vencimento, $valor, $status, $id_conta, $id_usuario]);

    // Redireciona para a página de contas
    header('Location: contas_pagar.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas a Pagar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Contas a Pagar</h2>

        <!-- Formulário para adicionar nova conta -->
        <form action="contas_pagar.php" method="POST">
            <label for="descricao">Descrição</label>
            <input type="text" id="descricao" name="descricao" required>

            <label for="data_vencimento">Data de Vencimento</label>
            <input type="date" id="data_vencimento" name="data_vencimento" required>

            <label for="valor">Valor</label>
            <input type="number" id="valor" name="valor" required step="0.01">

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="Pendente">Pendente</option>
                <option value="Pago">Pago</option>
            </select>

            <button type="submit">Adicionar Conta</button>
        </form>

        <!-- Tabela de contas a pagar -->
        <h3>Minhas Contas</h3>
        <table>
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Data de Vencimento</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contas as $conta): ?>
                    <tr>
                        <td><?php echo $conta['descricao']; ?></td>
                        <td><?php echo $conta['data_vencimento']; ?></td>
                        <td>R$ <?php echo number_format($conta['valor'], 2, ',', '.'); ?></td>
                        <td><?php echo $conta['status']; ?></td>
                        <td>
                            <?php if ($conta['status'] == 'Pendente'): ?>
                                <a href="contas_pagar.php?id=<?php echo $conta['id_conta']; ?>&status=Pago">Marcar como Pago</a>
                            <?php else: ?>
                                <span>Conta já paga</span>
                            <?php endif; ?>
                            <a href="contas_pagar.php?editar_id=<?php echo $conta['id_conta']; ?>">Editar</a>
                            <a href="contas_pagar.php?excluir_id=<?php echo $conta['id_conta']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta conta?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Se o usuário quiser editar uma conta -->
    <?php if (isset($conta_editar)): ?>
        <div class="editar-container">
            <h3>Editar Conta</h3>
            <form action="contas_pagar.php" method="POST">
                <input type="hidden" name="editar_id_conta" value="<?php echo $conta_editar['id_conta']; ?>">

                <label for="descricao">Descrição</label>
                <input type="text" id="descricao" name="descricao" value="<?php echo $conta_editar['descricao']; ?>" required>

                <label for="data_vencimento">Data de Vencimento</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo $conta_editar['data_vencimento']; ?>" required>

                <label for="valor">Valor</label>
                <input type="number" id="valor" name="valor" value="<?php echo $conta_editar['valor']; ?>" required step="0.01">

                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Pendente" <?php echo $conta_editar['status'] == 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                    <option value="Pago" <?php echo $conta_editar['status'] == 'Pago' ? 'selected' : ''; ?>>Pago</option>
                </select>

                <button type="submit">Atualizar Conta</button>
            </form>
        </div>
    <?php endif; ?>
</body>

<body>
    <div class="container">
               <form action="dashboard.php" method="get">
            <button type="submit" class="button-voltar">Voltar para o Início</button>
        </form>
    </div>
</body>

</html>
