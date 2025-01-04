<?php
session_start();
include('db_connection.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Buscar total de contas pendentes
$sql_contas_pendentes = "SELECT SUM(valor) AS total_pendente FROM contas_pagar WHERE id_usuario = ? AND status = 'Pendente'";
$stmt_contas_pendentes = $pdo->prepare($sql_contas_pendentes);
$stmt_contas_pendentes->execute([$id_usuario]);
$contas_pendentes = $stmt_contas_pendentes->fetch();

// Buscar total de entradas e saídas (opcional, já implementado)
$sql_saldo = "SELECT SUM(valor) AS total_entradas FROM entradas WHERE id_usuario = ?";
$stmt_saldo = $pdo->prepare($sql_saldo);
$stmt_saldo->execute([$id_usuario]);
$total_entradas = $stmt_saldo->fetch()['total_entradas'];

$sql_saidas = "SELECT SUM(valor) AS total_saidas FROM saidas WHERE id_usuario = ?";
$stmt_saidas = $pdo->prepare($sql_saidas);
$stmt_saidas->execute([$id_usuario]);
$total_saidas = $stmt_saidas->fetch()['total_saidas'];

$saldo_atual = $total_entradas - $total_saidas;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>
        <p><strong>Saldo Atual:</strong> R$ <?php echo number_format($saldo_atual, 2, ',', '.'); ?></p>

        <!-- Exibir valor das contas pendentes -->
        <p><strong>Valor das Contas Pendentes:</strong> R$ <?php echo number_format($contas_pendentes['total_pendente'], 2, ',', '.'); ?></p>

        <!-- Outras informações do dashboard -->
        <a href="contas_pagar.php">Ver Contas a Pagar</a>
    </div>
</body>
</html>
