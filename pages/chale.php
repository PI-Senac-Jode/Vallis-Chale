<?php require_once '../config.php'; ?>
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
</head>
<body>

<?php include "../frontEnd/includes/nav.inc.php"; ?>


  <div class="container hospedagens__grid">
    <div class="hospedagens__content">
      <div class="hospedagens__header">
        <img src="../src/assets/img/bi_house.png" class="hospedagens__icon-title" alt="">
        <h2 class="hospedagens__title">Chalé Paraíso</h2>
      </div>
      <p class="hospedagens__text">
        Perfeito para casais que querem tranquilidade e contato com a natureza.
      </p>
      <div class="hospedagens__amenities">
        <div class="amenity-item">
          <img src="../src//assets/img/bx_area.png" alt=""> <span>Área reservada</span>
        </div>
        <div class="amenity-item">
          <img src="../src/assets/img/ph_bathtub.png" alt=""> <span>Hidromassagem</span>
        </div>
        <div class="amenity-item">
          <img src="../src//assets/img/mynaui_tv.png" alt=""> <span>Smart TV</span>
        </div>
        <div class="amenity-item">
          <img src="../src//assets/img/material-symbols-light_outdoor-grill-outline.png" alt=""> <span>Churrasqueira</span>
        </div>
        <div class="amenity-item">
          <img src="../src/assets/img/proicons_wi-fi.png" alt=""> <span>Wi-fi</span>
        </div>
      </div>
     <a href="<?= BASE_URL ?>/pages/reservar.php">
       <button class="btn btn-reserve-now">RESERVE AGORA</button>
     </a>
    </div>

    <div class="hospedagens__visual">
      <img src="../src/assets/img/chale-paraiso.png" alt="Vista externa do Chalé Paraíso" class="hospedagens__main-image">
    </div>
  </div>

<?php include "../frontEnd/includes/footer.inc.php"; ?>
</body>
</html>