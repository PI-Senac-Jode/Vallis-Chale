function openModal(id) {
  // Exibe o modal informado adicionando a classe usada pelo CSS.
  document.getElementById(id).classList.add('active');
}

function closeModal(id) {
  // Fecha o modal removendo a classe ativa.
  document.getElementById(id).classList.remove('active');
}

function formatMoneyForInput(value) {
  // Formata o valor vindo do banco para o padrao visual brasileiro.
  const numberValue = Number(value || 0);

  return numberValue.toLocaleString('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}

function datesToTextareaValue(value) {
  // Converte o JSON de datas salvo no banco em uma lista editavel no textarea.
  if (!value) {
    return '';
  }

  try {
    const dates = JSON.parse(value);
    return Array.isArray(dates) ? dates.join('\n') : '';
  } catch (error) {
    return '';
  }
}

function openCreateModal() {
  // Limpa o formulario para cadastrar um novo chale.
  document.getElementById('modalChaleTitle').textContent = 'Novo Chale';
  document.getElementById('form-action').value = 'create';
  document.getElementById('chale-id').value = '';
  document.getElementById('chale-nome').value = '';
  document.getElementById('chale-preco').value = '';
  document.getElementById('chale-categoria').value = '';
  document.getElementById('chale-descricao').value = '';
  document.getElementById('chale-datas').value = '';
  document.getElementById('chale-status').value = '1';
  document.getElementById('chale-imagem').value = '';
  document.getElementById('chale-imagem-atual').textContent = 'Escolha uma foto para cadastrar o chale.';
  openModal('modalChale');
}

function openEditModal(chale) {
  // Preenche o formulario com os dados do chale selecionado na tabela.
  document.getElementById('modalChaleTitle').textContent = 'Editar Chale';
  document.getElementById('form-action').value = 'update';
  document.getElementById('chale-id').value = chale.id;
  document.getElementById('chale-nome').value = chale.nome || '';
  document.getElementById('chale-preco').value = formatMoneyForInput(chale.preco_diaria);
  document.getElementById('chale-categoria').value = chale.categoria_id || '';
  document.getElementById('chale-descricao').value = chale.descricao || '';
  document.getElementById('chale-datas').value = datesToTextareaValue(chale.datas_disponiveis);
  document.getElementById('chale-status').value = String(Number(chale.disponibilidade || 0));
  document.getElementById('chale-imagem').value = '';
  document.getElementById('chale-imagem-atual').textContent = chale.imagem_url
    ? `Foto atual: ${chale.imagem_url}. Envie outra apenas se quiser trocar.`
    : 'Este chale ainda nao tem foto. Envie uma imagem para exibi-lo corretamente.';
  openModal('modalChale');
}

function openDeleteModal(id, nome) {
  // Guarda o ID do chale no formulario de exclusao e mostra o nome na confirmacao.
  document.getElementById('delete-chale-id').value = id;
  document.getElementById('deleteChaleName').textContent = nome;
  openModal('modalExcluirChale');
}

function showToast(message, type = 'success') {
  // Mostra mensagens vindas do PHP apos criar, editar ou excluir um registro.
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
const rows = Array.from(document.querySelectorAll('#chalesTableBody tr[data-search]'));
const counter = document.getElementById('chaleCounter');

if (searchInput && counter) {
  searchInput.addEventListener('input', () => {
    // Filtro local: usa o texto preparado no atributo data-search de cada linha.
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
}

const toastContainer = document.getElementById('toastContainer');

if (toastContainer) {
  showToast(toastContainer.dataset.message, toastContainer.dataset.type);
}
