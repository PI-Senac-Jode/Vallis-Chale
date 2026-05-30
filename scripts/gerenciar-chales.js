function openModal(id) {
  document.getElementById(id).classList.add('active');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('active');
}

function formatMoneyForInput(value) {
  const numberValue = Number(value || 0);

  return numberValue.toLocaleString('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}

function datesToTextareaValue(value) {
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
  document.getElementById('modalChaleTitle').textContent = 'Novo Chale';
  document.getElementById('form-action').value = 'create';
  document.getElementById('chale-id').value = '';
  document.getElementById('chale-nome').value = '';
  document.getElementById('chale-preco').value = '';
  document.getElementById('chale-categoria').value = '';
  document.getElementById('chale-descricao').value = '';
  document.getElementById('chale-datas').value = '';
  document.getElementById('chale-status').value = '1';
  openModal('modalChale');
}

function openEditModal(chale) {
  document.getElementById('modalChaleTitle').textContent = 'Editar Chale';
  document.getElementById('form-action').value = 'update';
  document.getElementById('chale-id').value = chale.id;
  document.getElementById('chale-nome').value = chale.nome || '';
  document.getElementById('chale-preco').value = formatMoneyForInput(chale.preco_diaria);
  document.getElementById('chale-categoria').value = chale.categoria_id || '';
  document.getElementById('chale-descricao').value = chale.descricao || '';
  document.getElementById('chale-datas').value = datesToTextareaValue(chale.datas_disponiveis);
  document.getElementById('chale-status').value = String(Number(chale.disponibilidade || 0));
  openModal('modalChale');
}

function openDeleteModal(id, nome) {
  document.getElementById('delete-chale-id').value = id;
  document.getElementById('deleteChaleName').textContent = nome;
  openModal('modalExcluirChale');
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
const rows = Array.from(document.querySelectorAll('#chalesTableBody tr[data-search]'));
const counter = document.getElementById('chaleCounter');

if (searchInput && counter) {
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
}

const toastContainer = document.getElementById('toastContainer');

if (toastContainer) {
  showToast(toastContainer.dataset.message, toastContainer.dataset.type);
}
