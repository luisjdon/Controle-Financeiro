<?php
session_start();
include('db_connection.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id = $_GET['id'];  // ID da transação a ser excluída
$tipo = $_GET['tipo'];  // Tipo da transação (entrada ou saída)

if ($tipo == 'entrada') {
    // Excluir entrada
    $sql = "DELETE FROM entradas WHERE id_entrada = ? AND id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $id_usuario]);
} elseif ($tipo == 'saida') {
    // Excluir saída
    $sql = "DELETE FROM saidas WHERE id_saida = ? AND id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $id_usuario]);
}

// Redireciona para o histórico após a exclusão
header('Location: historico.php');
exit;
?>
