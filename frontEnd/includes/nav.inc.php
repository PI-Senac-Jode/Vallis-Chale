<header class="nav">
    <div class="container nav__inner">
      <a href="<?= BASE_URL ?>/index.php" class="nav__brand">
        <span class="nav__brand-mark" aria-hidden="true"><img src="<?= BASE_URL ?>/src/assets/img/logo-vetor-comp.svg" alt=""></span>
        <span>Vallis <em style="font-style: normal; color: var(--secondary);">Chalé</em></span>
      </a>
      <nav aria-label="Principal">
        <ul class="nav__links">
          <li><a href="#sobre">Sobre nós</a></li>
          <li><a href="#hospedagens">Chalés</a></li>
        </ul>
      </nav>
      <?php if (!empty($_SESSION['admin_logado'])): ?>
        <details class="nav__account-dropdown">
          <summary class="nav__account" aria-label="Abrir menu administrativo">
            <img src="<?= BASE_URL ?>/src/assets/img/login.svg" alt="">
          </summary>
          <div class="nav__account-menu">
            <span class="nav__account-title">Admin</span>
            <a href="<?= BASE_URL ?>/pages/criar-chale.php">
              <span class="nav__account-icon" aria-hidden="true">C</span>
              Chalés
            </a>
            <a href="<?= BASE_URL ?>/pages/gerenciamento.php">
              <span class="nav__account-icon" aria-hidden="true">R</span>
              Reservas
            </a>
            <a href="<?= BASE_URL ?>/pages/logout.php">
              <span class="nav__account-icon" aria-hidden="true">L</span>
              Logout
            </a>
          </div>
        </details>
      <?php elseif (!empty($_SESSION['mostrar_login_admin'])): ?>
        <a href="<?= BASE_URL ?>/pages/login.php" class="nav__account" aria-label="Minha conta"><img src="<?= BASE_URL ?>/src/assets/img/login.svg" alt=""></a>
      <?php endif; ?>
    </div>
  </header>
