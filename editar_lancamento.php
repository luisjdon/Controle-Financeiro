<?php
session_start();
include('db_connection.php'); // Conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id = $_GET['id'];
$tipo = $_GET['tipo'];

// Buscar o lançamento a ser editado
if ($tipo == 'entrada') {
    $sql = "SELECT * FROM entradas WHERE id_entrada = ? AND id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $id_usuario]);
    $transacao = $stmt->fetch();
} elseif ($tipo == 'saida') {
    $sql = "SELECT * FROM saidas WHERE id_saida = ? AND id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $id_usuario]);
    $transacao = $stmt->fetch();
}

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor = $_POST['valor'];
    $data = $_POST['data'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];

    // Atualizar a transação no banco de dados
    if ($tipo == 'entrada') {
        $sql = "UPDATE entradas SET valor = ?, data = ?, categoria = ?, descricao = ? WHERE id_entrada = ? AND id_usuario = ?";
    } elseif ($tipo == 'saida') {
        $sql = "UPDATE saidas SET valor = ?, data = ?, categoria = ?, descricao = ? WHERE id_saida = ? AND id_usuario = ?";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$valor, $data, $categoria, $descricao, $id, $id_usuario]);

    // Redireciona para o histórico após a edição
    header('Location: historico.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Lançamento</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Editar Lançamento</h2>
        <form action="editar_lancamento.php?id=<?php echo $id; ?>&tipo=<?php echo $tipo; ?>" method="POST">
            <label for="valor">Valor</label>
            <input type="number" id="valor" name="valor" value="<?php echo $transacao['valor']; ?>" required step="0.01">
            
            <label for="data">Data</label>
            <input type="date" id="data" name="data" value="<?php echo $transacao['data']; ?>" required>
            
            <label for="categoria">Categoria</label>
            <input type="text" id="categoria" name="categoria" value="<?php echo $transacao['categoria']; ?>" required>
            
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao"><?php echo $transacao['descricao']; ?></textarea>
            
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
