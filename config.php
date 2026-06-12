<?php

/**
 * Arquivo central de configuracao do projeto Vallis Chale.
 *
 * Todas as paginas PHP importam este arquivo com require_once para reaproveitar:
 * - a sessao do usuario;
 * - o fuso horario do sistema;
 * - as constantes globais;
 * - a conexao PDO com o banco MySQL.
 */

// Inicia a sessao apenas se ela ainda nao estiver ativa.
// Essa sessao guarda, por exemplo, se o administrador ja fez login.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controla a exibicao discreta do icone de login na navbar.
// Ex.: ?acesso=admin libera o icone ate o administrador fazer logout.
if (isset($_GET['acesso'])) {
    $acessoNavbar = strtolower(trim((string) $_GET['acesso']));

    if ($acessoNavbar === 'admin') {
        $_SESSION['mostrar_login_admin'] = true;
    }
}

// Define o fuso horario usado por datas, reservas e mensagens do sistema.
date_default_timezone_set('America/Sao_Paulo');

// Caminho base do projeto dentro do XAMPP/Apache.
// Usado para montar links absolutos sem repetir o mesmo texto em varias paginas.
define('BASE_URL', '/vallis-chale');

// Credenciais simples do painel administrativo.
// Em um sistema real, a senha deveria ficar criptografada no banco de dados.
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'vallis2026');

// Dados da conexao MySQL local usada pelo XAMPP.
// O banco precisa ser criado/importado a partir de database/sistema_chales.sql.
$db_host = 'localhost';
$db_name = 'sistema_chales';
$db_user = 'root';
$db_pass = '';

try {
    // Cria uma unica conexao PDO para ser reutilizada pelas paginas do projeto.
    // charset=utf8mb4 evita problemas com acentos e caracteres especiais no banco.
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);

    // Faz o PDO lancar excecoes quando uma consulta SQL falhar.
    // Isso facilita identificar erros de tabela, coluna ou dados invalidos.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retorna os resultados como arrays associativos: $linha['nome'].
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Interrompe a execucao caso o MySQL esteja desligado, o banco nao exista
    // ou as credenciais estejam incorretas.
    die("Erro critico na conexao com o banco de dados: " . $e->getMessage());
}

?>
