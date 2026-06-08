<?php
require_once '../config.php';

// A autenticacao do administrador acontece nesta pagina.
// O config.php ja iniciou a sessao e carregou ADMIN_USER/ADMIN_PASS.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Compara os dados digitados com as constantes definidas em config.php.
    // Se estiverem corretos, grava uma flag na sessao para liberar o painel.
    if ($usuario === ADMIN_USER && $senha === ADMIN_PASS) {
        $_SESSION['admin_logado'] = true;
        header("Location: gerenciamento.php");
        exit;
    } else {
        $erro = "Usuario ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Vallis Chale</title>
  <meta name="description" content="Acesso administrativo do projeto Vallis Chale." />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="../frontEnd/styles/tokens.css" />
  <link rel="stylesheet" href="../frontEnd/styles/global.css" />
  <link rel="stylesheet" href="../frontEnd/styles/sections/nav.css" />
  <link rel="stylesheet" href="../frontEnd/styles/sections/hero.css" />
  <link rel="stylesheet" href="../frontEnd/styles/sections/footer.css" />
  <link rel="stylesheet" href="../frontEnd/styles/sections/reservas.css">
  <link rel="stylesheet" href="../frontEnd/styles/sections/login.css">
</head>
<body>
  <?php include "../frontEnd/includes/nav.inc.php"; ?>

  <div class="container login-group">
        <section class="left-side">
            <div class="login-box">
                <h1>LOGIN</h1>
                <?php if (!empty($erro)): ?>
                    <p class="login-error"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="input-group">
                        <label>Usuario:</label>
                        <input type="text" name="usuario" required>
                    </div>

                    <div class="input-group">
                        <label>Senha:</label>
                        <input type="password" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-reserve-now">Login</button>
                </form>
            </div>
        </section>
         <div class="right-side">
            <div class="image-container">
                <img src="../src/assets/img/chale-interior-3.png" alt="Fachada Vallis Chale">
            </div>
          </div>
</div>

  <?php include "../frontEnd/includes/footer.inc.php"; ?>
</body>
</html>
