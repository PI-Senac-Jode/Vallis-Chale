<?php 
require_once '../config.php'; 

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Valida com as constantes do config.phps
    if ($usuario === ADMIN_USER && $senha === ADMIN_PASS) {
        $_SESSION['admin_logado'] = true; // Cria a "chave" de acesso
        header("Location: criar-chale.php"); // Redireciona para o painel
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vallis Chalé — Refúgio de luxo na montanha</title>
  <meta name="description" content="Vallis Chalé — chalés exclusivos que unem conforto moderno e natureza preservada." />

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
  <!-- Navbar -->
  
  <?php include "../frontEnd/includes/nav.inc.php"; ?>
  <!--  -->

  <div class="container login-group">
        <section class="left-side">
            <div class="login-box">
                <h1>LOGIN</h1>
                <form action="login.php" method="POST">
                    <div class="input-group">
                        <label>Usuário:</label>
                        <input type="text" name="usuario" required>
                    </div>

                    <div class="input-group">
                        <label>Senha:</label>
                        <input type="password" name="senha" required>
                    </div>

                    <div class="options-container">
                        <div class="option-item">
                            <p>Lembrar -me</p>
                            <div class="circle-select"></div>
                        </div>
                        <div class="option-item">
                            <p>Esqueci a senha</p>
                            <div class="circle-select"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-reserve-now">Login</button>
                </form>
            </div>
        </section>
         <div class="right-side">
            <div class="image-container">
                <img src="../src/assets/img/chale-interior-3.png" alt="Fachada Vallis Chalé">
            </div>
          </div>
</div>
   <!-- footer -->
  
  <?php include "../frontEnd/includes/footer.inc.php"; ?>
  <!--  -->

</body>
</html>