<?php

//  Configurações de Sessão e Fuso Horário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Sao_Paulo');


define('BASE_URL', '/vallis-chale');

// Define os dados de acesso do administrador
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'vallis2026'); // Senha temporaária para testes


// Conexão Centralizada com o Banco de Dados (PDO)
$db_host = 'localhost';
$db_name = 'sistema_chales';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    // Ativa os erros para o PHP avisar se houver algo errado no SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Se o banco estiver desligado no XAMPP, ele avisa aqui
    die("Erro crítico na conexão com o banco de dados: " . $e->getMessage());
}

?>

