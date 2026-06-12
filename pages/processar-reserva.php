<?php

require_once '../config.php';

function normalize_cpf(string $cpf): string
{
    return preg_replace('/\D/', '', $cpf);
}

function format_date_br(string $date): string
{
    return date('d/m/Y', strtotime($date));
}

function format_money_br(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function montar_mensagem_whatsapp_reserva(array $dadosReserva): string
{
    $linhas = [
        'Ola! Quero confirmar minha solicitacao de reserva.',
        '',
        'Código da reserva: #' . $dadosReserva['id_reserva'],
        'Nome: ' . $dadosReserva['nome'],
        'E-mail: ' . $dadosReserva['email'],
        'Chale: ' . $dadosReserva['chale_nome'],
        'Check-in: ' . format_date_br($dadosReserva['data_inicio']),
        'Check-out: ' . format_date_br($dadosReserva['data_fim']),
        'Noites: ' . $dadosReserva['noites'],
        'Valor estimado: ' . format_money_br($dadosReserva['valor_total']),
        '',
        'Aguardo o retorno para finalizar a reserva.',
    ];

    return implode("\n", $linhas);
}

function montar_link_whatsapp(string $mensagem): string
{
    $numeroWhatsapp = '5511950308510';

    return 'https://wa.me/' . $numeroWhatsapp . '?text=' . rawurlencode($mensagem);
}

function render_reservation_feedback(string $status, string $title, string $message, array $options = []): void
{
    $isSuccess = $status === 'success';
    $reservationCode = $options['reservation_code'] ?? null;
    $whatsappLink = $options['whatsapp_link'] ?? '';
    $details = $options['details'] ?? [];
    $primaryHref = $isSuccess && $whatsappLink ? $whatsappLink : BASE_URL . '/pages/reservar.php';
    $primaryLabel = $isSuccess ? 'Abrir WhatsApp' : 'Tentar novamente';
    $pageTitle = $isSuccess ? 'Reserva enviada | Vallis Chalé' : 'Não foi possível concluir | Vallis Chalé';
    $statusLabel = $isSuccess ? 'solicitação enviada' : 'atenção necessária';
    $icon = $isSuccess ? '&#10003;' : '!';
    $safeWhatsappLink = json_encode($whatsappLink, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    ?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Montserrat:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?= BASE_URL ?>/frontEnd/styles/tokens.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/frontEnd/styles/global.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/frontEnd/styles/sections/nav.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/frontEnd/styles/sections/footer.css" />
    <style>
      .reservation-result {
        min-height: calc(100vh - 220px);
        display: grid;
        place-items: center;
        padding: clamp(3rem, 8vw, 6rem) var(--container-gutter);
        background:
          linear-gradient(135deg, rgba(45, 62, 51, 0.08), rgba(213, 147, 46, 0.12)),
          var(--surface);
      }

      .reservation-result__panel {
        width: min(100%, 760px);
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(45, 62, 51, 0.12);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-ambient);
        padding: clamp(1.5rem, 5vw, 3rem);
        text-align: center;
      }

      .reservation-result__icon {
        width: 4.5rem;
        height: 4.5rem;
        display: inline-grid;
        place-items: center;
        margin-bottom: var(--space-5);
        border-radius: 50%;
        background: <?= $isSuccess ? 'var(--primary)' : '#8f2f2f' ?>;
        color: var(--on-primary);
        font-size: 2rem;
        font-weight: 700;
        box-shadow: 0 16px 30px rgba(45, 62, 51, 0.18);
      }

      .reservation-result__eyebrow {
        display: block;
        margin-bottom: var(--space-3);
        color: var(--secondary);
        font-size: var(--label-md);
        font-weight: 700;
        letter-spacing: 0;
        text-transform: uppercase;
      }

      .reservation-result__title {
        margin-bottom: var(--space-4);
        font-size: clamp(2rem, 4vw, 3rem);
        letter-spacing: 0;
      }

      .reservation-result__message {
        max-width: 58ch;
        margin: 0 auto var(--space-6);
        color: var(--on-surface-variant);
        font-size: var(--body-lg);
      }

      .reservation-result__code {
        display: inline-flex;
        align-items: center;
        gap: var(--space-3);
        margin-bottom: var(--space-6);
        padding: var(--space-3) var(--space-5);
        border: 1px solid var(--outline-variant);
        border-radius: var(--radius-md);
        background: var(--surface-container-low);
        color: var(--primary);
        font-weight: 700;
      }

      .reservation-result__details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: var(--space-3);
        margin-bottom: var(--space-6);
        text-align: left;
      }

      .reservation-result__detail {
        padding: var(--space-4);
        border-radius: var(--radius-md);
        background: var(--surface-container-low);
      }

      .reservation-result__detail span {
        display: block;
        margin-bottom: var(--space-1);
        color: var(--on-surface-muted);
        font-size: var(--label-md);
        font-weight: 700;
        text-transform: uppercase;
      }

      .reservation-result__detail strong {
        color: var(--on-surface);
        font-size: var(--body-sm);
      }

      .reservation-result__actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: var(--space-3);
      }

      .reservation-result__button {
        display: inline-flex;
        min-height: 3rem;
        align-items: center;
        justify-content: center;
        padding: 0.85rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        transition: transform 180ms ease, box-shadow 180ms ease, background-color 180ms ease;
      }

      .reservation-result__button--primary {
        background: var(--primary);
        color: var(--on-primary);
      }

      .reservation-result__button--secondary {
        border: 1px solid var(--outline-variant);
        background: var(--surface-bright);
        color: var(--primary);
      }

      .reservation-result__button:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-soft);
      }

      .reservation-result__hint {
        margin: var(--space-5) auto 0;
        color: var(--on-surface-muted);
        font-size: var(--body-sm);
      }

      @media (max-width: 560px) {
        .reservation-result__actions,
        .reservation-result__button {
          width: 100%;
        }
      }
    </style>
  </head>
  <body>
    <?php include __DIR__ . '/../frontEnd/includes/nav.inc.php'; ?>

    <main class="reservation-result">
      <section class="reservation-result__panel" aria-labelledby="reservation-result-title">
        <div class="reservation-result__icon" aria-hidden="true"><?= $icon ?></div>
        <span class="reservation-result__eyebrow"><?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?></span>
        <h1 id="reservation-result-title" class="reservation-result__title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="reservation-result__message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

        <?php if ($reservationCode): ?>
          <div class="reservation-result__code">
            <span>Código da reserva</span>
            <strong>#<?= htmlspecialchars((string) $reservationCode, ENT_QUOTES, 'UTF-8') ?></strong>
          </div>
        <?php endif; ?>

        <?php if ($details): ?>
          <div class="reservation-result__details">
            <?php foreach ($details as $label => $value): ?>
              <div class="reservation-result__detail">
                <span><?= htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') ?></span>
                <strong><?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="reservation-result__actions">
          <a class="reservation-result__button reservation-result__button--primary" href="<?= htmlspecialchars($primaryHref, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($primaryLabel, ENT_QUOTES, 'UTF-8') ?>
          </a>
          <a class="reservation-result__button reservation-result__button--secondary" href="<?= BASE_URL ?>/index.php">
            Voltar ao início
          </a>
        </div>

        <?php if ($isSuccess): ?>
          <p class="reservation-result__hint">O WhatsApp será aberto automaticamente com a mensagem pronta.</p>
        <?php endif; ?>
      </section>
    </main>

    <?php include __DIR__ . '/../frontEnd/includes/footer.inc.php'; ?>

    <?php if ($isSuccess && $whatsappLink): ?>
      <script>
        const whatsappLink = <?= $safeWhatsappLink ?>;

        window.setTimeout(() => {
          window.location.href = whatsappLink;
        }, 2500);
      </script>
    <?php endif; ?>
  </body>
</html>
    <?php
    exit;
}

// Este arquivo recebe o formulario final da reserva e grava os dados no banco.
// Ele aceita somente POST para evitar criacao de reservas por acesso direto via URL.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Valida e higieniza os dados recebidos do formulario.
// O CPF fica somente com numeros para padronizar a chave do cliente.
$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_UNSAFE_RAW) ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$cpf = normalize_cpf($_POST['cpf'] ?? '');
$id_chale = filter_input(INPUT_POST, 'id_chale', FILTER_VALIDATE_INT);
$data_inicio = $_POST['data_inicio'] ?? '';
$data_fim = $_POST['data_fim'] ?? '';

if (!$nome || !$email || strlen($cpf) !== 11 || !$id_chale || !$data_inicio || !$data_fim) {
    render_reservation_feedback(
        'error',
        'Dados incompletos',
        'Revise as informações da reserva e tente enviar novamente.'
    );
}

if (strtotime($data_inicio) >= strtotime($data_fim)) {
    render_reservation_feedback(
        'error',
        'Período inválido',
        'A data de saída precisa ser maior que a data de entrada.'
    );
}

// A transacao garante que cliente e reserva sejam salvos juntos.
// Se uma das operacoes falhar, o rollBack desfaz tudo.
try {
    $pdo->beginTransaction();

    $stmtChale = $pdo->prepare(
        'SELECT nome, preco_diaria
         FROM chale
         WHERE id = :id
         LIMIT 1'
    );
    $stmtChale->execute([':id' => $id_chale]);
    $chaleReserva = $stmtChale->fetch();

    if (!$chaleReserva) {
        throw new Exception('Chale nao encontrado.');
    }

    // Antes de criar cliente, o banco verifica se ja existe o mesmo CPF ou e-mail.
    // Assim a reserva fica ligada ao cliente correto e evita cadastro duplicado.
    $stmtClienteExistente = $pdo->prepare(
        'SELECT cpf
         FROM cliente
         WHERE email = :email OR cpf = :cpf
         LIMIT 1'
    );
    $stmtClienteExistente->execute([
        ':email' => $email,
        ':cpf' => $cpf,
    ]);
    $clienteExistente = $stmtClienteExistente->fetch();

    if ($clienteExistente) {
        $cpfCliente = $clienteExistente['cpf'];

        // Mesmo e-mail com CPF diferente indica conflito de cadastro.
        if (normalize_cpf($cpfCliente) !== $cpf) {
            throw new Exception('Este e-mail ja esta cadastrado com outro CPF.');
        }

        // Cliente ja existe: atualiza os dados antes de salvar a nova reserva.
        $stmtCliente = $pdo->prepare(
            'UPDATE cliente
             SET nome = :nome, email = :email
             WHERE cpf = :cpf'
        );
        $stmtCliente->execute([
            ':cpf' => $cpfCliente,
            ':nome' => $nome,
            ':email' => $email,
        ]);
    } else {
        $cpfCliente = $cpf;

        // Cliente novo: primeiro cria o cliente para depois usar o CPF como referencia.
        $stmtCliente = $pdo->prepare(
            'INSERT INTO cliente (cpf, nome, email)
             VALUES (:cpf, :nome, :email)'
        );
        $stmtCliente->execute([
            ':cpf' => $cpfCliente,
            ':nome' => $nome,
            ':email' => $email,
        ]);
    }

    // Cria a reserva ligando o chale escolhido ao cliente encontrado ou recem-criado.
    // O status inicial fica como Pendente para o administrador confirmar depois.
    $stmtReserva = $pdo->prepare(
        "INSERT INTO reserva (id_chale, id_cliente, data_inicio, data_fim, status)
         VALUES (:id_chale, :id_cliente, :data_inicio, :data_fim, 'Pendente')"
    );
    $stmtReserva->execute([
        ':id_chale' => $id_chale,
        ':id_cliente' => $cpfCliente,
        ':data_inicio' => $data_inicio,
        ':data_fim' => $data_fim,
    ]);

    // Captura o ID auto-incremental gerado para mostrar o codigo ao cliente.
    $id_reserva_gerado = $pdo->lastInsertId();

    $pdo->commit();

    $inicio = new DateTimeImmutable($data_inicio);
    $fim = new DateTimeImmutable($data_fim);
    $noites = (int) $inicio->diff($fim)->days;
    $valorTotal = $noites * (float) $chaleReserva['preco_diaria'];
    $mensagemWhatsapp = montar_mensagem_whatsapp_reserva([
        'id_reserva' => $id_reserva_gerado,
        'nome' => $nome,
        'email' => $email,
        'chale_nome' => $chaleReserva['nome'],
        'data_inicio' => $data_inicio,
        'data_fim' => $data_fim,
        'noites' => $noites,
        'valor_total' => $valorTotal,
    ]);
    $linkWhatsapp = montar_link_whatsapp($mensagemWhatsapp);

    render_reservation_feedback(
        'success',
        'Reserva solicitada com sucesso',
        'Recebemos seus dados. Guarde o código abaixo e envie a mensagem pelo WhatsApp para finalizar a confirmação.',
        [
            'reservation_code' => $id_reserva_gerado,
            'whatsapp_link' => $linkWhatsapp,
            'details' => [
                'Chalé' => $chaleReserva['nome'],
                'Check-in' => format_date_br($data_inicio),
                'Check-out' => format_date_br($data_fim),
                'Noites' => $noites,
                'Valor estimado' => format_money_br($valorTotal),
            ],
        ]
    );

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($e->getMessage() === 'Este e-mail ja esta cadastrado com outro CPF.') {
        render_reservation_feedback(
            'error',
        'Cadastro já existente',
        'Este e-mail já está cadastrado com outro CPF. Confira seus dados ou fale com a equipe da pousada.'
        );
    }

    error_log('Erro ao salvar reserva: ' . $e->getMessage());
    render_reservation_feedback(
        'error',
        'Não conseguimos salvar sua reserva',
        'Tente novamente em alguns instantes. Se o problema continuar, entre em contato com a equipe da Vallis Chalé.'
    );
}
