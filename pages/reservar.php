<?php require_once '../config.php'; ?>

<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendário de Reserva | Chalé Paraíso</title>
    <meta
      name="description"
      content="Calendário funcional para reservas do Chalé Paraíso, com seleção de check-in, check-out, cálculo de noites e visual premium."
    />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Montserrat:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../frontEnd/styles/tokens.css" />
    <link rel="stylesheet" href="../frontEnd/styles/global.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/nav.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/footer.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/reservar.css" />
  </head>
  <body class="page-calendar">
  
  <!-- Navbar -->
  <?php include "../frontEnd/includes/nav.inc.php"; ?>
  <!--  -->

    <main class="calendar-page">
      <section class="calendar-page__hero container">
        <span class="calendar-page__eyebrow">reserva online</span>
        <h1 class="calendar-page__title">Chalé Paraíso</h1>
      </section>

      <section class="calendar-booking container" aria-label="Reserva do Chalé Paraíso">
        <article class="calendar-card" aria-labelledby="calendar-title">
          <div class="calendar-card__header">
            <button id="prev" class="calendar-nav" type="button" aria-label="Mês anterior">
              &#8249;
            </button>
            <h2 id="mesAno" class="calendar-card__title">Abril 2026</h2>
            <button id="next" class="calendar-nav" type="button" aria-label="Próximo mês">
              &#8250;
            </button>
          </div>

          <div class="calendar-weekdays" aria-hidden="true">
            <span>DOM</span>
            <span>SEG</span>
            <span>TER</span>
            <span>QUA</span>
            <span>QUI</span>
            <span>SEX</span>
            <span>SÁB</span>
          </div>

          <div id="dias" class="calendar-grid" role="grid" aria-labelledby="calendar-title"></div>
        </article>

        <aside class="booking-panel">
          <div class="price-card">
            <label class="price-card__label" for="precoNoite">Preço por Noite</label>
            <div class="price-card__field">
              <span>R$</span>
              <input
                id="precoNoite"
                type="number"
                min="0"
                step="10"
                value="450"
                aria-label="Preço por noite"'
                readonly
              />
            </div>
          </div>

          <div class="booking-panel__summary">
            <div>
              <span>Check-in</span>
              <strong id="checkin">15 de abr. de 2026</strong>
            </div>
            <div>
              <span>Noites</span>
              <strong id="noites">15</strong>
            </div>
            <div>
              <span>Check-out</span>
              <strong id="checkout">30 de abr. de 2026</strong>
            </div>
          </div>

          <button id="confirmarReserva" class="booking-panel__button" type="button">
            Confirmar Reserva
          </button>

          <button id="limparDatas" class="booking-panel__link" type="button">Limpar seleção</button>

          <p id="resumoTotal" class="booking-panel__total">Total estimado: R$ 6.750</p>
          <p id="mensagemReserva" class="booking-panel__message" aria-live="polite"></p>
        </aside>
      </section>

      <div id="modalReserva" class="booking-modal" aria-hidden="true">
        <div class="booking-modal__overlay" data-fechar-modal></div>
        <section class="booking-modal__card" role="dialog" aria-modal="true" aria-labelledby="modalReservaTitulo">
          <button class="booking-modal__close" type="button" data-fechar-modal aria-label="Fechar modal">
            &times;
          </button>

          <span class="booking-modal__eyebrow">dados do cliente</span>
          <h2 id="modalReservaTitulo" class="booking-modal__title">Confirmar reserva</h2>
          <p class="booking-modal__subtitle">Preencha seus dados para concluir a solicita&ccedil;&atilde;o.</p>

          <form action="<?= BASE_URL ?>/pages/processar-reserva.php" method="POST" class="booking-modal__form">
            <input type="hidden" name="id_chale" value="1">
            <input type="hidden" id="data_inicio" name="data_inicio" required>
            <input type="hidden" id="data_fim" name="data_fim" required>

            <label class="booking-modal__field" for="nome">
              <span>Nome completo</span>
              <input type="text" id="nome" name="nome" autocomplete="name" required>
            </label>

            <label class="booking-modal__field" for="email">
              <span>E-mail</span>
              <input type="email" id="email" name="email" autocomplete="email" required>
            </label>

            <label class="booking-modal__field" for="cpf">
              <span>CPF</span>
              <input type="text" id="cpf" name="cpf" inputmode="numeric" autocomplete="off" placeholder="000.000.000-00" maxlength="14" required>
            </label>

            <div class="booking-modal__dates">
              <div>
                <span>Check-in</span>
                <strong id="modalCheckin">--</strong>
              </div>
              <div>
                <span>Check-out</span>
                <strong id="modalCheckout">--</strong>
              </div>
            </div>

            <button class="booking-modal__submit" type="submit">Confirmar reserva</button>
          </form>
        </section>
      </div>
    </main>

<?php include "../frontEnd/includes/footer.inc.php"; ?>
    <script src="<?= BASE_URL ?>/scripts/reservar.js"></script>
  </body>
</html>
