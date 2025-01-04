<?php
session_start();

// Verifica se o usuário está logado (se a sessão existe)
if (isset($_SESSION['usuario_id'])) {
    // Se o usuário estiver logado, redireciona para o dashboard
    header('Location: dashboard.php');
    exit;
} else {
    // Caso contrário, redireciona para a página de login
    header('Location: login.html');
    exit;
}
?>
