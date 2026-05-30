<?php require_once '../config.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
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
  <link rel="stylesheet" href="../frontEnd/styles/sections/editargaleria.css">
</head>
<body>
  
<?php include "../frontEnd/includes/nav.inc.php"; ?>


      <div class="group-gallery container">
        <h1 class="page-title">GALERIA</h1>
        <div class="gallery-grid" id="galleryGrid">
            <div class="img-placeholder"><img src="../assets/img/placeholder.png " alt=""></div>
            <div class="img-placeholder"><img src="../assets/img/placeholder.png " alt=""></div>
            <div class="img-placeholder"><img src="../assets/img/placeholder.png " alt=""></div>
            <div class="img-placeholder"><img src="../assets/img/placeholder.png " alt=""></div>
            <div class="img-placeholder"><img src="../assets/img/placeholder.png " alt=""></div>
            <div class="img-placeholder"><img src="../assets/img/placeholder.png " alt=""></div>
        </div>
        <div class="action-buttons">
            <button class="btn btn-edit btn-reserve-now" onclick="editarImagem()">Editar</button>
            <button class="btn btn-remove btn-reserve-now" onclick="removerImagem()">Remover</button>
            <button class="btn btn-add btn-reserve-now" onclick="adicionarImagem()">Adicionar</button>
        </div>
        </div>

    <?php include "../frontEnd/includes/footer.inc.php"; ?>

</body>
</html>