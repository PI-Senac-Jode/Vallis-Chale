<?php
// Inicia a sessão
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Exemplo de validação (Em um sistema real, use consulta ao Banco de Dados)
    if ($usuario === "admin" && $senha === "1234") {
        $_SESSION['logado'] = true;
        echo "Login realizado com sucesso! Bem-vindo ao Vallis Chalé.";
        // header("Location: dashboard.php"); 
    } else {
        echo "Usuário ou senha incorretos.";
    }
}
?>