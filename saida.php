<?php
session_start();
include('db_connection.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $valor = $_POST['valor'];
    $data = $_POST['data'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];

    // Insere os dados de saída no banco de dados
    $sql = "INSERT INTO saidas (id_usuario, valor, data, categoria, descricao) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // Verifica se a execução foi bem-sucedida
    if ($stmt->execute([$id_usuario, $valor, $data, $categoria, $descricao])) {
        echo "Saída registrada com sucesso!";
        header('Location: dashboard.php'); // Redireciona para o dashboard após o registro
    } else {
        echo "Erro ao registrar saída.";
    }
}
?>
