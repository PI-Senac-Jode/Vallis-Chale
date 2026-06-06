<?php
require_once '../config.php';

// Esta area pertence ao painel administrativo e exige login.
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}

$feedback = $_SESSION['feedback_chale'] ?? null;
unset($_SESSION['feedback_chale']);

function redirect_chale_feedback(string $message, string $type = 'success'): void
{
    $_SESSION['feedback_chale'] = ['message' => $message, 'type' => $type];
    header('Location: criar-chale.php');
    exit;
}

function parse_money_value(string $value): float
{
    // Aceita valores digitados no formato brasileiro, como "R$ 350,00",
    // e converte para float antes de salvar no campo DECIMAL do MySQL.
    $value = trim($value);
    $value = str_replace(['R$', ' '], '', $value);

    if (str_contains($value, ',') && str_contains($value, '.')) {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
    } else {
        $value = str_replace(',', '.', $value);
    }

    return (float) $value;
}

function normalize_available_dates(string $dates): ?string
{
    // Recebe datas separadas por linha ou virgula e salva como JSON.
    // O banco possui a coluna datas_disponiveis do tipo JSON.
    $items = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $dates)));
    $validDates = [];

    foreach ($items as $item) {
        $timestamp = strtotime($item);

        if ($timestamp) {
            $validDates[] = date('Y-m-d', $timestamp);
        }
    }

    return $validDates ? json_encode(array_values(array_unique($validDates))) : null;
}

function dates_to_text(?string $json): string
{
    if (!$json) {
        return '';
    }

    $dates = json_decode($json, true);

    if (!is_array($dates)) {
        return '';
    }

    return implode("\n", $dates);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create' || $action === 'update') {
            // Fluxo compartilhado para cadastro e edicao de chales.
            // Depois da validacao, o action define se sera INSERT ou UPDATE.
            $nome = trim($_POST['nome'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $precoDiaria = parse_money_value($_POST['preco_diaria'] ?? '0');
            $categoriaId = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
            $disponibilidade = ($_POST['disponibilidade'] ?? '1') === '1' ? 1 : 0;
            $datasDisponiveis = normalize_available_dates($_POST['datas_disponiveis'] ?? '');

            if (!$nome || $precoDiaria <= 0 || !$categoriaId) {
                redirect_chale_feedback('Preencha nome, valor e categoria corretamente.', 'danger');
            }

            if ($action === 'create') {
                // Insere um novo chale com categoria, diaria, status e datas disponiveis.
                $stmt = $pdo->prepare(
                    'INSERT INTO chale (nome, descricao, preco_diaria, datas_disponiveis, disponibilidade, categoria_id)
                     VALUES (:nome, :descricao, :preco_diaria, :datas_disponiveis, :disponibilidade, :categoria_id)'
                );
                $stmt->execute([
                    ':nome' => $nome,
                    ':descricao' => $descricao,
                    ':preco_diaria' => $precoDiaria,
                    ':datas_disponiveis' => $datasDisponiveis,
                    ':disponibilidade' => $disponibilidade,
                    ':categoria_id' => $categoriaId,
                ]);

                redirect_chale_feedback('Chale cadastrado com sucesso.');
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            // Atualiza um chale existente identificado pelo ID enviado no formulario.
            if (!$id) {
                redirect_chale_feedback('Chale nao encontrado para edicao.', 'danger');
            }

            $stmt = $pdo->prepare(
                'UPDATE chale
                 SET nome = :nome,
                     descricao = :descricao,
                     preco_diaria = :preco_diaria,
                     datas_disponiveis = :datas_disponiveis,
                     disponibilidade = :disponibilidade,
                     categoria_id = :categoria_id
                 WHERE id = :id'
            );
            $stmt->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco_diaria' => $precoDiaria,
                ':datas_disponiveis' => $datasDisponiveis,
                ':disponibilidade' => $disponibilidade,
                ':categoria_id' => $categoriaId,
            ]);

            redirect_chale_feedback('Chale atualizado com sucesso.', 'edit');
        }

        if ($action === 'delete') {
            // Tenta excluir o chale. Se houver reserva vinculada, a chave estrangeira
            // impede a exclusao fisica; nesse caso o chale fica apenas inativo.
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (!$id) {
                redirect_chale_feedback('Chale nao encontrado.', 'danger');
            }

            try {
                $stmt = $pdo->prepare('DELETE FROM chale WHERE id = :id');
                $stmt->execute([':id' => $id]);
                redirect_chale_feedback('Chale excluido com sucesso.', 'danger');
            } catch (PDOException $e) {
                $stmt = $pdo->prepare('UPDATE chale SET disponibilidade = 0 WHERE id = :id');
                $stmt->execute([':id' => $id]);
                redirect_chale_feedback('Este chale possui reservas. Ele foi marcado como inativo.', 'danger');
            }
        }
    } catch (Exception $e) {
        redirect_chale_feedback('Erro ao processar o chale: ' . $e->getMessage(), 'danger');
    }
}

$chales = [];
$categorias = [];
$databaseWarning = null;

try {
    // Carrega categorias para o select e chales para a tabela de gerenciamento.
    $categorias = $pdo->query('SELECT id, nome FROM categorias_chale ORDER BY nome')->fetchAll();
    $chales = $pdo->query(
        'SELECT
            ch.id,
            ch.nome,
            ch.descricao,
            ch.preco_diaria,
            ch.datas_disponiveis,
            ch.disponibilidade,
            ch.categoria_id,
            cat.nome AS categoria_nome
         FROM chale ch
         LEFT JOIN categorias_chale cat ON ch.categoria_id = cat.id
         ORDER BY ch.id DESC'
    )->fetchAll();
} catch (PDOException $e) {
    $databaseWarning = 'As tabelas de chales ainda nao foram encontradas. Importe database/sistema_chales.sql e database/insert.sql.';
}

if (!$feedback && $databaseWarning) {
    $feedback = ['message' => $databaseWarning, 'type' => 'danger'];
}
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerenciamento de Chales | Vallis Chale</title>
    <meta name="description" content="Painel administrativo para gerenciar os chales da Vallis Chale." />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />

    <link rel="stylesheet" href="../frontEnd/styles/tokens.css" />
    <link rel="stylesheet" href="../frontEnd/styles/global.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/nav.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/footer.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/gerenciar-chales.css" />
  </head>
  <body class="chale-admin-page">
    <?php include '../frontEnd/includes/nav.inc.php'; ?>

    <div class="toast-container" id="toastContainer" data-message="<?= htmlspecialchars($feedback['message'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-type="<?= htmlspecialchars($feedback['type'] ?? 'success', ENT_QUOTES, 'UTF-8') ?>"></div>

    <main class="chale-admin-main container">
      <header class="chale-admin-header">
        <div>
          <h1>Gerenciamento de Chales</h1>
          <p>Visualize, edite e gerencie todos os chales.</p>
        </div>
        <a class="admin-link" href="gerenciamento.php">Reservas</a>
      </header>

      <section class="chale-table-card">
        <div class="chale-table-actions">
          <div class="search-box">
            <span class="material-symbols-outlined icon-search" aria-hidden="true">search</span>
            <input id="searchInput" type="search" placeholder="Buscar por nome ou descricao..." aria-label="Buscar chales" />
          </div>
          <button class="btn-primary" type="button" onclick="openCreateModal()" <?= $categorias ? '' : 'disabled title="Cadastre categorias antes de criar chales"' ?>>
            <span aria-hidden="true">+</span>
            Novo Chale
          </button>
        </div>

        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Nome</th>
                <th>Valor</th>
                <th>Descricao</th>
                <th>Tags</th>
                <th>Status</th>
                <th class="text-right">Acoes</th>
              </tr>
            </thead>
            <tbody id="chalesTableBody">
              <?php foreach ($chales as $chale): ?>
                <?php
                  $isActive = (int) $chale['disponibilidade'] === 1;
                  $searchText = strtolower($chale['nome'] . ' ' . $chale['descricao'] . ' ' . $chale['categoria_nome'] . ' ' . ($isActive ? 'ativo' : 'inativo'));
                ?>
                <tr data-search="<?= htmlspecialchars($searchText, ENT_QUOTES, 'UTF-8') ?>">
                  <td><strong><?= htmlspecialchars($chale['nome'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                  <td>R$ <?= number_format((float) $chale['preco_diaria'], 2, ',', '.') ?></td>
                  <td class="description-cell"><?= htmlspecialchars($chale['descricao'] ?: '-', ENT_QUOTES, 'UTF-8') ?></td>
                  <td>
                    <div class="tag-list">
                      <?php if ($chale['categoria_nome']): ?>
                        <span class="tag-chip"><?= htmlspecialchars($chale['categoria_nome'], ENT_QUOTES, 'UTF-8') ?></span>
                      <?php endif; ?>
                      <span class="tag-chip"><?= $chale['datas_disponiveis'] ? 'Datas cadastradas' : 'Sem datas' ?></span>
                    </div>
                  </td>
                  <td><span class="status-chip <?= $isActive ? 'active' : 'inactive' ?>"><?= $isActive ? 'Ativo' : 'Inativo' ?></span></td>
                  <td class="actions-cell">
                    <button
                      class="icon-button"
                      type="button"
                      title="Editar"
                      onclick='openEditModal(<?= json_encode($chale, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                    >
                      <span class="material-symbols-outlined" aria-hidden="true">edit</span>
                    </button>
                    <button
                      class="icon-button"
                      type="button"
                      title="Excluir"
                      onclick='openDeleteModal(<?= (int) $chale['id'] ?>, <?= json_encode($chale['nome'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                    >
                      <span class="material-symbols-outlined" aria-hidden="true">delete</span>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (!$chales): ?>
                <tr class="empty-row">
                  <td colspan="6">Nenhum chale encontrado.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <footer class="table-footer">
          <p>Mostrando <span id="chaleCounter"><?= count($chales) ?></span> de <?= count($chales) ?> chales</p>
        </footer>
      </section>
    </main>

    <div class="modal-overlay" id="modalChale" role="dialog" aria-modal="true" aria-labelledby="modalChaleTitle">
      <div class="modal-card">
        <button class="modal-close-btn" type="button" onclick="closeModal('modalChale')" aria-label="Fechar">
          <span class="material-symbols-outlined" aria-hidden="true">close</span>
        </button>
        <h2 class="modal-title" id="modalChaleTitle">Novo Chale</h2>
        <p class="modal-subtitle">Preencha as informacoes do chale.</p>

        <form class="modal-form" method="POST">
          <input type="hidden" id="form-action" name="action" value="create" />
          <input type="hidden" id="chale-id" name="id" />

          <div class="input-container">
            <label for="chale-nome">Nome</label>
            <input type="text" id="chale-nome" name="nome" placeholder="Ex: Chale Vista Real" required />
          </div>

          <div class="input-grid-2">
            <div class="input-container">
              <label for="chale-preco">Valor da diaria</label>
              <input type="text" id="chale-preco" name="preco_diaria" placeholder="R$ 350,00" required />
            </div>
            <div class="input-container">
              <label for="chale-categoria">Categoria</label>
              <select id="chale-categoria" name="categoria_id" required>
                <option value="" disabled selected>Selecione</option>
                <?php foreach ($categorias as $categoria): ?>
                  <option value="<?= (int) $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="input-container">
            <label for="chale-descricao">Descricao</label>
            <textarea id="chale-descricao" name="descricao" rows="4" placeholder="Descreva o chale"></textarea>
          </div>

          <div class="input-container">
            <label for="chale-datas">Datas disponiveis</label>
            <textarea id="chale-datas" name="datas_disponiveis" rows="3" placeholder="2026-06-01&#10;2026-06-02"></textarea>
          </div>

          <div class="input-container">
            <label for="chale-status">Status</label>
            <select id="chale-status" name="disponibilidade" required>
              <option value="1">Ativo</option>
              <option value="0">Inativo</option>
            </select>
          </div>

          <div class="modal-footer-actions">
            <button type="button" class="btn-secondary" onclick="closeModal('modalChale')">Cancelar</button>
            <button type="submit" class="btn-primary-dark">Salvar</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal-overlay" id="modalExcluirChale" role="dialog" aria-modal="true" aria-labelledby="deleteChaleTitle">
      <div class="modal-card modal-alert-layout">
        <h2 class="modal-title" id="deleteChaleTitle">Excluir chale</h2>
        <p class="modal-alert-text">
          Tem certeza que deseja excluir <strong id="deleteChaleName"></strong>?
        </p>
        <form method="POST">
          <input type="hidden" name="action" value="delete" />
          <input type="hidden" id="delete-chale-id" name="id" />
          <div class="modal-footer-actions">
            <button type="button" class="btn-secondary" onclick="closeModal('modalExcluirChale')">Cancelar</button>
            <button type="submit" class="btn-primary-dark btn-danger">Excluir</button>
          </div>
        </form>
      </div>
    </div>

    <?php include '../frontEnd/includes/footer.inc.php'; ?>

    <script src="../scripts/gerenciar-chales.js"></script>
  </body>
</html>
