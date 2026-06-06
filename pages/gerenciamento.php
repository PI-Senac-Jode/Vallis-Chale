<?php
require_once '../config.php';

// Protege o painel: apenas usuarios autenticados em login.php podem acessar.
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}

$feedback = $_SESSION['feedback'] ?? null;
unset($_SESSION['feedback']);

function redirect_with_feedback(string $message, string $type = 'success'): void
{
    // Centraliza o padrao POST/Redirect/GET para evitar reenvio de formulario.
    $_SESSION['feedback'] = ['message' => $message, 'type' => $type];
    header('Location: gerenciamento.php');
    exit;
}

function normalize_cpf(string $cpf): string
{
    return preg_replace('/\D/', '', $cpf);
}

function format_cpf(string $cpf): string
{
    $digits = normalize_cpf($cpf);

    if (strlen($digits) !== 11) {
        return $cpf;
    }

    return substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2);
}

function format_date_br(?string $date): string
{
    if (!$date) {
        return '-';
    }

    return date('d/m/Y', strtotime($date));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            // Cria uma reserva pelo painel administrativo.
            // Primeiro valida hospede, chale e periodo; depois grava cliente e reserva.
            $nome = trim($_POST['nome'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $cpf = normalize_cpf($_POST['cpf'] ?? '');
            $idChale = filter_input(INPUT_POST, 'id_chale', FILTER_VALIDATE_INT);
            $dataInicio = $_POST['data_inicio'] ?? '';
            $dataFim = $_POST['data_fim'] ?? '';
            $status = $_POST['status'] ?? 'Pendente';

            if (!$nome || !$email || strlen($cpf) !== 11 || !$idChale || !$dataInicio || !$dataFim) {
                redirect_with_feedback('Preencha todos os dados da reserva corretamente.', 'danger');
            }

            if (strtotime($dataInicio) >= strtotime($dataFim)) {
                redirect_with_feedback('A data de saida deve ser maior que a data de entrada.', 'danger');
            }

            $pdo->beginTransaction();

            // O cliente usa CPF como chave primaria. Se ja existir, atualiza nome/e-mail.
            $stmtCliente = $pdo->prepare(
                'INSERT INTO cliente (cpf, nome, email)
                 VALUES (:cpf, :nome, :email)
                 ON DUPLICATE KEY UPDATE nome = :nome, email = :email'
            );
            $stmtCliente->execute([
                ':cpf' => $cpf,
                ':nome' => $nome,
                ':email' => $email,
            ]);

            // A reserva referencia a tabela cliente pelo CPF e a tabela chale pelo ID.
            $stmtReserva = $pdo->prepare(
                'INSERT INTO reserva (id_chale, id_cliente, data_inicio, data_fim, status)
                 VALUES (:id_chale, :id_cliente, :data_inicio, :data_fim, :status)'
            );
            $stmtReserva->execute([
                ':id_chale' => $idChale,
                ':id_cliente' => $cpf,
                ':data_inicio' => $dataInicio,
                ':data_fim' => $dataFim,
                ':status' => $status,
            ]);

            $pdo->commit();
            redirect_with_feedback('Reserva adicionada com sucesso.');
        }

        if ($action === 'update') {
            // Atualiza dados editaveis da reserva: chale, periodo e status.
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $idChale = filter_input(INPUT_POST, 'id_chale', FILTER_VALIDATE_INT);
            $dataInicio = $_POST['data_inicio'] ?? '';
            $dataFim = $_POST['data_fim'] ?? '';
            $status = $_POST['status'] ?? 'Pendente';

            if (!$id || !$idChale || !$dataInicio || !$dataFim || strtotime($dataInicio) >= strtotime($dataFim)) {
                redirect_with_feedback('Nao foi possivel salvar a reserva. Confira os dados.', 'danger');
            }

            $stmt = $pdo->prepare(
                'UPDATE reserva
                 SET id_chale = :id_chale, data_inicio = :data_inicio, data_fim = :data_fim, status = :status
                 WHERE id = :id'
            );
            $stmt->execute([
                ':id' => $id,
                ':id_chale' => $idChale,
                ':data_inicio' => $dataInicio,
                ':data_fim' => $dataFim,
                ':status' => $status,
            ]);

            redirect_with_feedback('Reserva atualizada com sucesso.', 'edit');
        }

        if ($action === 'delete') {
            // Exclusao logica: preserva o historico e apenas troca o status.
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (!$id) {
                redirect_with_feedback('Reserva nao encontrada.', 'danger');
            }

            $stmt = $pdo->prepare('UPDATE reserva SET status = :status WHERE id = :id');
            $stmt->execute([':status' => 'Excluida', ':id' => $id]);

            redirect_with_feedback('Reserva marcada como excluida.', 'danger');
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        redirect_with_feedback('Erro ao processar a reserva: ' . $e->getMessage(), 'danger');
    }
}

$reservas = [];
$chales = [];
$databaseWarning = null;

try {
    // Consulta principal do painel. Os JOINs unem reserva, cliente e chale
    // para exibir tudo em uma unica tabela administrativa.
    $reservas = $pdo->query(
        'SELECT
            r.id,
            r.id_chale,
            r.id_cliente,
            r.data_inicio,
            r.data_fim,
            r.status,
            c.nome AS cliente_nome,
            c.email AS cliente_email,
            ch.nome AS chale_nome,
            ch.preco_diaria,
            DATEDIFF(r.data_fim, r.data_inicio) AS noites
         FROM reserva r
         INNER JOIN cliente c ON r.id_cliente = c.cpf
         INNER JOIN chale ch ON r.id_chale = ch.id
         ORDER BY r.data_inicio DESC, r.id DESC'
    )->fetchAll();

    // Lista usada nos selects dos formularios de nova/editar reserva.
    $chales = $pdo->query('SELECT id, nome, preco_diaria FROM chale ORDER BY nome')->fetchAll();
} catch (PDOException $e) {
    $databaseWarning = 'As tabelas de reservas ainda nao foram encontradas. Importe os arquivos database/banco.sql e database/insert.sql para usar o painel.';
}

if (!$feedback && $databaseWarning) {
    $feedback = ['message' => $databaseWarning, 'type' => 'danger'];
}

$statusOptions = ['Confirmada', 'Pendente', 'Cancelada', 'Excluida'];
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciamento de Reservas | Vallis Chale</title>
    <meta name="description" content="Painel administrativo para gerenciar as reservas da Vallis Chale." />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />

    <link rel="stylesheet" href="../frontEnd/styles/tokens.css" />
    <link rel="stylesheet" href="../frontEnd/styles/global.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/nav.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/footer.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/gerenciamento.css" />
  </head>
  <body class="management-page">
    <?php include '../frontEnd/includes/nav.inc.php'; ?>

    <div class="toast-container" id="toastContainer" data-message="<?= htmlspecialchars($feedback['message'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-type="<?= htmlspecialchars($feedback['type'] ?? 'success', ENT_QUOTES, 'UTF-8') ?>"></div>

    <main class="management-main container">
      <header class="management-header">
        <div>
          <span class="management-eyebrow">Painel administrativo</span>
          <h1>Gerenciamento de Reservas</h1>
          <p>Visualize, edite e acompanhe todas as reservas dos chales.</p>
        </div>
        <a class="management-link" href="criar-chale.php">Gerenciar chales</a>
      </header>

      <section class="table-card" aria-labelledby="reservas-title">
        <div class="table-actions">
          <div class="search-box">
            <span class="material-symbols-outlined icon-search" aria-hidden="true">search</span>
            <input id="searchInput" type="search" placeholder="Buscar por nome, e-mail, CPF ou chale" aria-label="Buscar reservas" />
          </div>
          <button class="btn-primary" type="button" onclick="openModal('modalNovaReserva')" <?= $chales ? '' : 'disabled title="Cadastre ou importe chales antes de criar reservas"' ?>>
            <span class="material-symbols-outlined" aria-hidden="true">add</span>
            Nova Reserva
          </button>
        </div>

        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Hospede</th>
                <th>Contato</th>
                <th>Chale</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Noites</th>
                <th>Valor</th>
                <th>Status</th>
                <th class="text-right">Acoes</th>
              </tr>
            </thead>
            <tbody id="tabelaReservasBody">
              <?php foreach ($reservas as $reserva): ?>
                <?php
                  $noites = max((int) $reserva['noites'], 0);
                  $valorTotal = $noites * (float) $reserva['preco_diaria'];
                  $statusClass = strtolower(str_replace([' ', 'í'], ['', 'i'], $reserva['status'] ?? 'pendente'));
                ?>
                <tr data-search="<?= htmlspecialchars(strtolower($reserva['cliente_nome'] . ' ' . $reserva['cliente_email'] . ' ' . $reserva['id_cliente'] . ' ' . $reserva['chale_nome'] . ' ' . $reserva['status']), ENT_QUOTES, 'UTF-8') ?>">
                  <td>
                    <strong><?= htmlspecialchars($reserva['cliente_nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <span class="table-muted"><?= htmlspecialchars(format_cpf($reserva['id_cliente']), ENT_QUOTES, 'UTF-8') ?></span>
                  </td>
                  <td>
                    <div class="contact-info">
                      <span><?= htmlspecialchars($reserva['cliente_email'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($reserva['chale_nome'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars(format_date_br($reserva['data_inicio']), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars(format_date_br($reserva['data_fim']), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= $noites ?></td>
                  <td><strong>R$ <?= number_format($valorTotal, 2, ',', '.') ?></strong></td>
                  <td><span class="badge <?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($reserva['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td class="actions-cell">
                    <button
                      class="btn-action"
                      type="button"
                      title="Editar"
                      onclick='openEditModal(<?= json_encode($reserva, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                    >
                      <span class="material-symbols-outlined" aria-hidden="true">edit</span>
                    </button>
                    <button
                      class="btn-action"
                      type="button"
                      title="Excluir"
                      onclick='openDeleteModal(<?= (int) $reserva['id'] ?>, <?= json_encode($reserva['cliente_nome'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                    >
                      <span class="material-symbols-outlined" aria-hidden="true">delete</span>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (!$reservas): ?>
                <tr class="empty-row">
                  <td colspan="9">Nenhuma reserva encontrada.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <footer class="table-footer">
          <p>Mostrando <span id="contadorReservas"><?= count($reservas) ?></span> reservas</p>
        </footer>
      </section>
    </main>

    <div class="modal-overlay" id="modalNovaReserva" role="dialog" aria-modal="true" aria-labelledby="novaReservaTitulo">
      <div class="modal-card modal-form-layout">
        <button class="modal-close-btn" type="button" onclick="closeModal('modalNovaReserva')" aria-label="Fechar">
          <span class="material-symbols-outlined" aria-hidden="true">close</span>
        </button>
        <h2 class="modal-title" id="novaReservaTitulo">Nova Reserva</h2>
        <p class="modal-subtitle">Adicione uma nova reserva ao sistema.</p>

        <form class="modal-body-form" method="POST">
          <input type="hidden" name="action" value="create" />
          <div class="input-container">
            <label for="add-nome">Nome do Hospede</label>
            <input type="text" id="add-nome" name="nome" placeholder="Nome completo" required />
          </div>
          <div class="input-grid-2">
            <div class="input-container">
              <label for="add-email">E-mail</label>
              <input type="email" id="add-email" name="email" placeholder="email@exemplo.com" required />
            </div>
            <div class="input-container">
              <label for="add-cpf">CPF</label>
              <input type="text" id="add-cpf" name="cpf" placeholder="000.000.000-00" required />
            </div>
          </div>
          <div class="input-container">
            <label for="add-chale">Chale</label>
            <select class="select-field" id="add-chale" name="id_chale" required>
              <option value="" disabled selected>Selecione um chale</option>
              <?php foreach ($chales as $chale): ?>
                <option value="<?= (int) $chale['id'] ?>">
                  <?= htmlspecialchars($chale['nome'], ENT_QUOTES, 'UTF-8') ?> - R$ <?= number_format((float) $chale['preco_diaria'], 2, ',', '.') ?>/noite
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="input-grid-2">
            <div class="input-container">
              <label for="add-checkin">Check-in</label>
              <input type="date" id="add-checkin" name="data_inicio" required />
            </div>
            <div class="input-container">
              <label for="add-checkout">Check-out</label>
              <input type="date" id="add-checkout" name="data_fim" required />
            </div>
          </div>
          <div class="input-container">
            <label for="add-status">Status</label>
            <select class="select-field" id="add-status" name="status" required>
              <?php foreach ($statusOptions as $status): ?>
                <option value="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="modal-footer-actions">
            <button type="button" class="btn-secondary-dark" onclick="closeModal('modalNovaReserva')">Cancelar</button>
            <button type="submit" class="btn-primary-dark">Adicionar Reserva</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal-overlay" id="modalEditarReserva" role="dialog" aria-modal="true" aria-labelledby="editarReservaTitulo">
      <div class="modal-card modal-form-layout">
        <button class="modal-close-btn" type="button" onclick="closeModal('modalEditarReserva')" aria-label="Fechar">
          <span class="material-symbols-outlined" aria-hidden="true">close</span>
        </button>
        <h2 class="modal-title" id="editarReservaTitulo">Editar Reserva</h2>
        <p class="modal-subtitle" id="editReservaResumo">Atualize as informacoes da reserva.</p>

        <form class="modal-body-form" method="POST">
          <input type="hidden" name="action" value="update" />
          <input type="hidden" id="edit-id" name="id" />
          <div class="input-container">
            <label for="edit-chale">Chale</label>
            <select class="select-field" id="edit-chale" name="id_chale" required>
              <?php foreach ($chales as $chale): ?>
                <option value="<?= (int) $chale['id'] ?>"><?= htmlspecialchars($chale['nome'], ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="input-grid-2">
            <div class="input-container">
              <label for="edit-checkin">Check-in</label>
              <input type="date" id="edit-checkin" name="data_inicio" required />
            </div>
            <div class="input-container">
              <label for="edit-checkout">Check-out</label>
              <input type="date" id="edit-checkout" name="data_fim" required />
            </div>
          </div>
          <div class="input-container">
            <label for="edit-status">Status</label>
            <select class="select-field" id="edit-status" name="status" required>
              <?php foreach ($statusOptions as $status): ?>
                <option value="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="modal-footer-actions">
            <button type="button" class="btn-secondary-dark" onclick="closeModal('modalEditarReserva')">Cancelar</button>
            <button type="submit" class="btn-primary-dark">Salvar Alteracoes</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal-overlay" id="modalConfirmarExclusao" role="dialog" aria-modal="true" aria-labelledby="excluirReservaTitulo">
      <div class="modal-card modal-alert-layout">
        <h2 class="modal-title" id="excluirReservaTitulo">Confirmar exclusao</h2>
        <p class="modal-alert-text">
          Tem certeza que deseja marcar a reserva de <strong id="nomeExclusaoDinamico"></strong> como excluida?
        </p>
        <form method="POST">
          <input type="hidden" name="action" value="delete" />
          <input type="hidden" id="excluir-id" name="id" />
          <div class="modal-footer-actions">
            <button type="button" class="btn-secondary-dark" onclick="closeModal('modalConfirmarExclusao')">Cancelar</button>
            <button type="submit" class="btn-primary-dark btn-alert-dark">Excluir</button>
          </div>
        </form>
      </div>
    </div>

    <?php include '../frontEnd/includes/footer.inc.php'; ?>

    <script>
      function openModal(id) {
        document.getElementById(id).classList.add('active');
      }

      function closeModal(id) {
        document.getElementById(id).classList.remove('active');
      }

      function openEditModal(reserva) {
        document.getElementById('edit-id').value = reserva.id;
        document.getElementById('edit-chale').value = reserva.id_chale;
        document.getElementById('edit-checkin').value = reserva.data_inicio;
        document.getElementById('edit-checkout').value = reserva.data_fim;
        document.getElementById('edit-status').value = reserva.status;
        document.getElementById('editReservaResumo').textContent = `${reserva.cliente_nome} - ${reserva.chale_nome}`;
        openModal('modalEditarReserva');
      }

      function openDeleteModal(id, nomeHospede) {
        document.getElementById('excluir-id').value = id;
        document.getElementById('nomeExclusaoDinamico').textContent = nomeHospede;
        openModal('modalConfirmarExclusao');
      }

      function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');

        if (!message) {
          return;
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
          <span class="material-symbols-outlined" aria-hidden="true">${type === 'danger' ? 'error' : type === 'edit' ? 'published_with_changes' : 'check_circle'}</span>
          <span class="toast-message">${message}</span>
        `;
        container.appendChild(toast);

        setTimeout(() => {
          toast.classList.add('toast-hide');
          setTimeout(() => toast.remove(), 300);
        }, 3500);
      }

      const searchInput = document.getElementById('searchInput');
      const rows = Array.from(document.querySelectorAll('#tabelaReservasBody tr'));
      const counter = document.getElementById('contadorReservas');

      searchInput.addEventListener('input', () => {
        const term = searchInput.value.trim().toLowerCase();
        let visibleRows = 0;

        rows.forEach((row) => {
          const visible = row.dataset.search.includes(term);
          row.hidden = !visible;
          if (visible) {
            visibleRows += 1;
          }
        });

        counter.textContent = visibleRows;
      });

      const toastContainer = document.getElementById('toastContainer');
      showToast(toastContainer.dataset.message, toastContainer.dataset.type);
    </script>
  </body>
</html>
