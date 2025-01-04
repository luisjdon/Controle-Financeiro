<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor = $_POST['valor'];
    $data = $_POST['data'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO entradas (id_usuario, valor, data, categoria, descricao) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$id_usuario, $valor, $data, $categoria, $descricao])) {
        echo "Entrada registrada com sucesso!";
        header('Location: dashboard.php');
    } else {
        echo "Erro ao registrar entrada.";
    }
}
?>
