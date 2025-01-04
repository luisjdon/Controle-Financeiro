<?php
session_start();
include('db_connection.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Deletar todas as entradas e saídas
$sql1 = "DELETE FROM entradas WHERE id_usuario = ?";
$sql2 = "DELETE FROM saidas WHERE id_usuario = ?";

$stmt1 = $pdo->prepare($sql1);
$stmt1->execute([$id_usuario]);

$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([$id_usuario]);

// Exibe a mensagem de confirmação e o botão de voltar
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançamentos Apagados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Todas as transações foram apagadas com sucesso!</h2>
        <form action="dashboard.php" method="get">
            <button type="submit" class="button-voltar">Voltar para o Início</button>
        </form>
    </div>
</body>
</html>
