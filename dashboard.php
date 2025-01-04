<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT SUM(valor) AS total_entradas FROM entradas WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$total_entradas = $stmt->fetchColumn();

$sql = "SELECT SUM(valor) AS total_saidas FROM saidas WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$total_saidas = $stmt->fetchColumn();

$sql_contas_pendentes = "SELECT SUM(valor) AS total_pendente FROM contas_pagar WHERE id_usuario = ? AND status = 'Pendente'";
$stmt_contas_pendentes = $pdo->prepare($sql_contas_pendentes);
$stmt_contas_pendentes->execute([$id_usuario]);
$contas_pendentes = $stmt_contas_pendentes->fetch();


$saldo_atual = $total_entradas - $total_saidas;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Controle Financeiro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo ao Controle Financeiro</h2>
        <div class="dashboard">
            <h3>Resumo Financeiro</h3>
            <p>Saldo Atual: R$ <?= number_format($saldo_atual, 2, ',', '.') ?></p>
            <p>Total de Entradas: R$ <?= number_format($total_entradas, 2, ',', '.') ?></p>
            <p>Total de Saídas: R$ <?= number_format($total_saidas, 2, ',', '.') ?></p>
            <p><strong>Valor das Contas Pendentes:</strong> R$ <?php echo number_format($contas_pendentes['total_pendente'], 2, ',', '.'); ?></p>
        </div>
        <a href="entrada_form.html">Adicionar Entrada</a> | 
        <a href="saida_form.html">Adicionar Saída</a> | 
        <a href="historico.php">Ver Histórico</a>
        <a href="contas_pagar.php">Ver Contas a Pagar</a>
    </div>
</body>
</html>
