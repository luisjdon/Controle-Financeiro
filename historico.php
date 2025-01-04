<?php
session_start();
include('db_connection.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Buscar os lançamentos do usuário
$sql = "SELECT * FROM entradas WHERE id_usuario = ? UNION ALL SELECT * FROM saidas WHERE id_usuario = ? ORDER BY data DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario, $id_usuario]);
$transacoes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - Controle Financeiro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Histórico de Lançamentos</h2>

        <!-- Botão para apagar todos os lançamentos -->
        <form action="apagar_tudo.php" method="POST">
            <button type="submit" class="button-apagar">Apagar Todos os Lançamentos</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transacoes as $transacao): ?>
                    <tr>
                        <td><?php echo $transacao['data']; ?></td>
                        <td><?php echo $transacao['categoria']; ?></td>
                        <td><?php echo $transacao['descricao']; ?></td>
                        <td>R$ <?php echo number_format($transacao['valor'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="editar_lancamento.php?id=<?php echo $transacao['id_entrada'] ?? $transacao['id_saida']; ?>&tipo=<?php echo isset($transacao['id_entrada']) ? 'entrada' : 'saida'; ?>">Editar</a> |
                            <a href="excluir_lancamento.php?id=<?php echo $transacao['id_entrada'] ?? $transacao['id_saida']; ?>&tipo=<?php echo isset($transacao['id_entrada']) ? 'entrada' : 'saida'; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

<body>
    <div class="container">
               <form action="dashboard.php" method="get">
            <button type="submit" class="button-voltar">Voltar para o Início</button>
        </form>
    </div>
</body>


</html>
