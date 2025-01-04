<?php
$host = 'localhost'; // Ou o endereço do seu servidor MySQL
$dbname = 'db_name'; // Nome do banco de dados
$username = 'db_user'; // Usuário do banco de dados
$password = 'db_password'; // Senha do banco de dados

try {
    // Criando uma instância PDO para conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Definindo o modo de erro para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Caso ocorra erro na conexão, uma mensagem de erro será exibida
    echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
    exit;
}
?>
