<?php
require_once '../config.php';
require_once '../frontEnd/includes/chale-images.inc.php';

// Pagina de detalhes de um chale.
// O ID vem pela URL; se nao vier, a pagina mostra o primeiro chale ativo.
$chaleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$chale = null;

try {
    if ($chaleId) {
        // prepare cria uma consulta segura com marcador :id.
        // O valor real entra no execute, evitando misturar texto digitado com SQL.
        $stmt = $pdo->prepare(
            'SELECT
                ch.*,
                cat.nome AS categoria_nome
             FROM chale ch
             LEFT JOIN categorias_chale cat ON ch.categoria_id = cat.id
             WHERE ch.id = :id'
        );
        $stmt->execute([':id' => $chaleId]);
        $chale = $stmt->fetch();
    }

    if (!$chale) {
        // Se o ID nao existir, busca o primeiro chale disponivel para nao deixar a pagina vazia.
        $chale = $pdo->query(
            'SELECT
                ch.*,
                cat.nome AS categoria_nome
             FROM chale ch
             LEFT JOIN categorias_chale cat ON ch.categoria_id = cat.id
             WHERE ch.disponibilidade = 1
             ORDER BY ch.id ASC
             LIMIT 1'
        )->fetch();
    }
} catch (PDOException $e) {
    // Se houver erro no banco, os textos padrao abaixo evitam quebra visual da pagina.
    $chale = null;
}

// Variaveis preparadas para a camada HTML.
// Todo texto vindo do banco e exibido com htmlspecialchars no template.
$chaleName = $chale['nome'] ?? 'Chale indisponivel';
$chaleDescription = $chale['descricao'] ?? 'Este chale ainda nao esta disponivel para exibicao.';
$chaleImage = $chale ? get_chale_image($chale, '../') : '../src/assets/img/placeholder.png';
$chalePrice = $chale ? number_format((float) ($chale['preco_diaria'] ?? 0), 2, ',', '.') : null;
$chaleCategory = $chale['categoria_nome'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($chaleName, ENT_QUOTES, 'UTF-8') ?> | Vallis Chalé</title>
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
        <h2 class="hospedagens__title"><?= htmlspecialchars($chaleName, ENT_QUOTES, 'UTF-8') ?></h2>
      </div>
      <p class="hospedagens__text">
        <?= htmlspecialchars($chaleDescription, ENT_QUOTES, 'UTF-8') ?>
      </p>
      <?php if ($chalePrice || $chaleCategory): ?>
        <p class="hospedagens__text">
          <?php if ($chaleCategory): ?>
            Categoria: <?= htmlspecialchars($chaleCategory, ENT_QUOTES, 'UTF-8') ?>.
          <?php endif; ?>
          <?php if ($chalePrice): ?>
            Diaria: R$ <?= $chalePrice ?>.
          <?php endif; ?>
        </p>
      <?php endif; ?>
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
     <a href="<?= BASE_URL ?>/pages/reservar.php<?= $chale ? '?id_chale=' . (int) $chale['id'] : '' ?>">
       <button class="btn btn-reserve-now">RESERVE AGORA</button>
     </a>
    </div>

    <div class="hospedagens__visual">
      <img src="<?= htmlspecialchars($chaleImage, ENT_QUOTES, 'UTF-8') ?>" alt="Vista externa do <?= htmlspecialchars($chaleName, ENT_QUOTES, 'UTF-8') ?>" class="hospedagens__main-image">
    </div>
  </div>

<?php include "../frontEnd/includes/footer.inc.php"; ?>
</body>
</html>
