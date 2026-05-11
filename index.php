 <?php require_once 'config.php'; ?>
<!DOCTYPE html>

<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vallis Chalé — Refúgio de luxo na montanha</title>
  <meta name="description" content="Vallis Chalé — chalés exclusivos que unem conforto moderno e natureza preservada." />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="./frontEnd//styles/tokens.css" />
  <link rel="stylesheet" href="./frontEnd/styles/global.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/nav.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/hero.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/about.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/gallery.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/hospedagens.css">
  <link rel="stylesheet" href="./frontEnd/styles/sections/diferenciais.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/avaliacoes.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/map.css" />
  <link rel="stylesheet" href="./frontEnd/styles/sections/footer.css" />
  <script src="./scripts/script.js" defer></script>
</head>
<body>

  <!-- Navigation -->

  <?php include "./frontEnd/includes/nav.inc.php"; ?>

  <!-- Hero -->
<section class="hero" id="home">
  <img class="hero__image" src="./src/assets/img/chale-hero.svg" alt="Vista noturna do chalé A-frame iluminado" />
  
  <div class="container hero__wrapper">
    <div class="hero__card">
      <h1 class="hero__title">VALLIS CHALÉ</h1>
      <p class="hero__tagline">Paz, silêncio e o aconchego que você merece.</p>
      <a href="#hospedagens" class="btn btn-accent">Saiba mais</a>
    </div>
  </div>
</section>

  <!-- About -->
  <section class="about" id="sobre">
    <div class="container about__grid">
      <div>
        <span class="eyebrow">sobre nós</span>
        <h2 class="about__title">No Vallis Chalé, o refúgio perfeito une exclusividade e natureza incomparável.</h2>
        <p class="about__body">
          O Vallis Chalé redefine o luxo ao fundir design sofisticado com o silêncio da mata. Diferente de hotéis comuns, oferecemos um refúgio de privacidade absoluta, onde a arquitetura se integra à paisagem para proporcionar uma desconexão real e uma imersão sensorial única na natureza.
        </p>
        <ul class="about__list">
          <li>Imersão completa na natureza com arquitetura pensada para o relaxamento.</li>
          <li>Atendimentos personalizados que antecipam desejos e garantem total conforto.</li>
          <li>Curadoria de atividades exclusivas que tornam cada estadia memorável.</li>
        </ul>
      </div>
      <img class="about__image" src="./src/assets/img/chale-interior.png" />
    </div>
  </section>

  <!-- Gallery -->
<section class="gallery">
  <div class="container">
    <h2 class="section-title">GALERIA</h2>
    <p class="section-subtitle">Veja nossas fotos aqui na galeria</p>

    <div class="gallery__grid">
      <!-- Esquerda -->
      <div class="gallery__item">
        <img
          src="./src/assets/img/chale-com-piscina.png"
          alt="Interior iluminado do chalé"
        />
      </div>

      <div class="gallery__item">
        <img
          src="./src/assets/img/chale-interior-2.png"
          alt="Chalé com piscina"
        />
      </div>

      <div class="gallery__item">
        <img
          src="./src/assets/img/chale-interior-3.png"
          alt="Fogueira noturna"
        />
      </div>

      <!-- CENTRO -->
      <div class="gallery__item gallery__item--center">
        <img
          id="mainImage"
          src="./src/assets/img/interior-chale.png"
          alt="Chalé A-frame entre árvores"
        />
      </div>

      <!-- Direita -->
      <div class="gallery__item">
        <img
          src="./src/assets/img/chale-maravilha.png"
          alt="Chalé iluminado à noite"
        />
      </div>

      <div class="gallery__item">
        <img
          src="./src/assets/img/chale-saudade.png"
          alt="Chalé iluminado à noite"
        />
      </div>

      <div class="gallery__item">
        <img
          src="./src/assets/img/chale-paraiso.png"
          alt="Chalé iluminado à noite"
        />
      </div>
    </div>
  </div>
</section> 

  <!-- Hospedagens -->
  <section class="hospedagens" id="hospedagens">
    <div class="container">
      <h2 class="section-title">HOSPEDAGENS</h2>
      <p class="section-subtitle">Chalés exclusivos que unem conforto moderno e total privacidade em meio à natureza preservada</p>

      <div class="hospedagens__grid">
        <article class="chale-card">
          <img class="chale-card__image" src="./src/assets/img/chale-maravilha.png" alt="Chalé Paraíso" />
          <div class="chale-card__body">
            <h3 class="chale-card__title">Chalé Paraíso</h3>
            <div class="chale-card__meta">
              
            </div>
            <p class="chale-card__text">Recarregue suas energias sob o luar. Um refúgio místico com teto de vidro para dormir observando as constelações.</p>
            <a href="./pages/chale.php" class="btn btn-accent">Saiba mais</a>
          </div>
        </article>

        <article class="chale-card">
          <img class="chale-card__image" src="./src/assets/img/chale-paraiso.png" alt="Chalé Paraíso" />
          <div class="chale-card__body">
            <h3 class="chale-card__title">Chalé Maravilha</h3>
            <div class="chale-card__meta">
              
            </div>
            <p class="chale-card__text">Desconecte do mundo e reconecte-se no Chalé Paraíso. O conforto que você merece, em meio à natureza.</p>
            <a href="#" class="btn btn-accent">Saiba mais</a>
          </div>
        </article>

        <article class="chale-card">
          <img class="chale-card__image" src="./src/assets/img/chale-sossego.png" alt="Chalé Paraíso" />
          <div class="chale-card__body">
            <h3 class="chale-card__title">Chalé Sossego</h3>
            <div class="chale-card__meta">
              
            </div>
            <p class="chale-card__text">Intimista e acolhedor. Um espaço pensado para o descanso, com banheira de hidromassagem integrada.</p>
            <a href="#" class="btn btn-accent">Saiba mais</a>
          </div>
        </article>
      </div>

    </div>
  </section>

  <!-- Diferenciais -->
  <section class="diferenciais">
    <div class="container diferenciais__grid">
      <div>
        <h2 class="diferenciais__title">DIFERENCIAIS</h2>
        <p class="diferenciais__text">
          Mais que uma reserva, uma experiência sob medida: use nosso sistema exclusivo para adicionar mimos e serviços personalizados ao seu chalé de forma rápida e simples.
        </p>
      </div>
      <div class="diferenciais__collage">
        <img src="./src/assets/img/cama-com-rosas.png" alt="Detalhe romântico com rosas" />
        <img src="./src/assets/img/piscina-vista-acima.png" alt="Piscina aquecida do chalé" />
        <img src="./src/assets/img/casal-piscina.png" alt="Mesa posta com café da manhã" />
        <img src="./src/assets/img/mesa-posta.png" alt="Mesa posta com café da manhã" />
      </div>
    </div>
  </section>

  <!-- Avaliações -->
  <section class="avaliacoes">
    <div class="container">
      <h2 class="section-title">AVALIAÇÕES</h2>
      <p class="section-subtitle">Depoimentos de quem viveu a experiência Vallis</p>
      <div class="avaliacoes__grid">
        <article class="review">
          <div class="review__header">
            <div class="review__avatar"><img src="./src/assets/img/homem.jpg" alt=""></div>
            <div>
              <div class="review__name">Pedro H.</div>
              <div class="review__stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
            </div>
          </div>
          <p class="review__text">O chalé é exatamente como nas fotos, super aconchegante. O processo de reserva pelo site foi muito intuitivo e rápido. Com certeza voltaremos!</p>
        </article>
        <article class="review">
          <div class="review__header">
            <div class="review__avatar"><img src="./src/assets/img/mulher.jpg" alt=""></div>
            <div>
              <div class="review__name">Juliana M.</div>
              <div class="review__stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
            </div>
          </div>
          <p class="review__text">Alugamos dois chalés vizinhos. A área de churrasqueira é excelente e a cozinha tinha todos os utensílios necessários para prepararmos nossas refeições. </p>
        </article>
        <article class="review">
          <div class="review__header">
            <div class="review__avatar"><img src="./src/assets/img/mulher-2.jpg" alt=""></div>
            <div>
              <div class="review__name">Marina A.</div>
              <div class="review__stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
            </div>
          </div>
          <p class="review__text">Já usei vários sites de reserva, mas esse é o mais rápido. Os filtros de 'hidromassagem' funcionam de verdade e não perdi tempo com opções que não me interessavam</p>
        </article>
      </div>
    </div>
  </section>

  <!-- Map -->
  <section class="map" aria-label="Localização">
    <iframe class="map__embed"
      src="https://www.google.com/maps?q=Rua+das+Alegrias+123&output=embed"
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"
      allowfullscreen
      title="Mapa da localização do Vallis Chalé"></iframe>
    <div class="map__card">
      <div class="map__address">Rua das Alegrias, 123...</div>
      <div class="map__sub">Rua das Alegrias, 123</div>
      <div class="map__rating"><strong>4.9</strong> &#9733; (163)</div>
    </div>
  </section>

  <!-- Footer -->
 
  <?php include "./frontEnd/includes/footer.inc.php"; ?>

</body>
</html>
