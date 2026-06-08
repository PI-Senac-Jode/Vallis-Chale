const diasEl = document.getElementById('dias');
const mesAnoEl = document.getElementById('mesAno');

const checkinEl = document.getElementById('checkin');
const checkoutEl = document.getElementById('checkout');
const noitesEl = document.getElementById('noites');

const precoNoiteEl = document.getElementById('precoNoite');
const resumoTotalEl = document.getElementById('resumoTotal');
const mensagemReservaEl = document.getElementById('mensagemReserva');

const btnConfirmar = document.getElementById('confirmarReserva');
const btnLimpar = document.getElementById('limparDatas');
const btnPrev = document.getElementById('prev');
const btnNext = document.getElementById('next');

const modalReserva = document.getElementById('modalReserva');
const modalCheckinEl = document.getElementById('modalCheckin');
const modalCheckoutEl = document.getElementById('modalCheckout');
const cpfEl = document.getElementById('cpf');

const meses = [
  'Janeiro',
  'Fevereiro',
  'Mar\u00e7o',
  'Abril',
  'Maio',
  'Junho',
  'Julho',
  'Agosto',
  'Setembro',
  'Outubro',
  'Novembro',
  'Dezembro',
];

const localeFormat = new Intl.DateTimeFormat('pt-BR', {
  day: 'numeric',
  month: 'short',
  year: 'numeric',
});

let viewDate = new Date(2026, 3, 1);
let checkin = new Date(2026, 3, 15);
let checkout = new Date(2026, 3, 30);

function normalizarData(data) {
  return new Date(data.getFullYear(), data.getMonth(), data.getDate());
}

function dataIgual(a, b) {
  return a && b && a.getTime() === b.getTime();
}

function formatarData(data) {
  if (!data) return '--';
  return localeFormat.format(data);
}

function formatarDataInput(data) {
  if (!data) return '';

  const ano = data.getFullYear();
  const mes = String(data.getMonth() + 1).padStart(2, '0');
  const dia = String(data.getDate()).padStart(2, '0');

  return `${ano}-${mes}-${dia}`;
}

function formatarMoeda(valor) {
  return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

function diferencaEmNoites(inicio, fim) {
  if (!inicio || !fim) return 0;
  return Math.round((fim - inicio) / (1000 * 60 * 60 * 24));
}

function obterPrecoNoite() {
  const valor = Number(precoNoiteEl?.value);
  return Number.isFinite(valor) && valor > 0 ? valor : 0;
}

function atualizarResumo() {
  const inputInicio = document.getElementById('data_inicio');
  const inputFim = document.getElementById('data_fim');

  if (checkin) {
    checkinEl.textContent = formatarData(checkin);
    if (inputInicio) inputInicio.value = formatarDataInput(checkin);
  } else {
    checkinEl.textContent = '-';
    if (inputInicio) inputInicio.value = '';
  }

  if (checkout) {
    checkoutEl.textContent = formatarData(checkout);
    if (inputFim) inputFim.value = formatarDataInput(checkout);
  } else {
    checkoutEl.textContent = '-';
    if (inputFim) inputFim.value = '';
  }

  const noites = diferencaEmNoites(checkin, checkout);

  if (noites > 0) {
    noitesEl.textContent = noites;
    resumoTotalEl.textContent = `Total estimado: ${formatarMoeda(noites * obterPrecoNoite())}`;
  } else {
    noitesEl.textContent = '--';
    resumoTotalEl.textContent = 'Selecione check-in e check-out para calcular o total';
  }

  if (btnConfirmar) {
    btnConfirmar.disabled = noites <= 0;
  }
}

function renderCalendar() {
  if (!diasEl || !mesAnoEl) return;

  diasEl.innerHTML = '';
  mesAnoEl.textContent = `${meses[viewDate.getMonth()]} ${viewDate.getFullYear()}`;

  const ano = viewDate.getFullYear();
  const mes = viewDate.getMonth();
  const primeiroDiaSemana = new Date(ano, mes, 1).getDay();
  const ultimoDiaMes = new Date(ano, mes + 1, 0).getDate();
  const hoje = normalizarData(new Date());

  for (let i = 0; i < primeiroDiaSemana; i += 1) {
    const empty = document.createElement('div');
    empty.className = 'calendar-day--empty';
    diasEl.appendChild(empty);
  }

  for (let dia = 1; dia <= ultimoDiaMes; dia += 1) {
    const data = normalizarData(new Date(ano, mes, dia));
    const button = document.createElement('button');

    button.type = 'button';
    button.className = 'calendar-day';
    button.textContent = dia;
    button.setAttribute('aria-label', formatarData(data));

    if (dataIgual(data, hoje)) {
      button.classList.add('is-today');
    }

    if (checkin && checkout && data > checkin && data < checkout) {
      button.classList.add('is-range');
    }

    if (dataIgual(data, checkin)) {
      button.classList.add('is-start');
    }

    if (dataIgual(data, checkout)) {
      button.classList.add('is-end');
    }

    button.addEventListener('click', () => selecionarData(data));
    diasEl.appendChild(button);
  }
}

function selecionarData(dataSelecionada) {
  mensagemReservaEl.textContent = '';

  if (!checkin || (checkin && checkout)) {
    checkin = dataSelecionada;
    checkout = null;
  } else if (dataSelecionada.getTime() <= checkin.getTime()) {
    checkin = dataSelecionada;
    checkout = null;
  } else {
    checkout = dataSelecionada;
  }

  atualizarResumo();
  renderCalendar();
}

function limparSelecao() {
  checkin = null;
  checkout = null;
  mensagemReservaEl.textContent = '';
  atualizarResumo();
  renderCalendar();
}

function abrirModalReserva() {
  if (!modalReserva) return;

  const inputInicio = document.getElementById('data_inicio');
  const inputFim = document.getElementById('data_fim');

  if (inputInicio) inputInicio.value = formatarDataInput(checkin);
  if (inputFim) inputFim.value = formatarDataInput(checkout);
  if (modalCheckinEl) modalCheckinEl.textContent = formatarData(checkin);
  if (modalCheckoutEl) modalCheckoutEl.textContent = formatarData(checkout);

  modalReserva.classList.add('is-open');
  modalReserva.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';

  document.getElementById('nome')?.focus();
}

function fecharModalReserva() {
  if (!modalReserva) return;

  modalReserva.classList.remove('is-open');
  modalReserva.setAttribute('aria-hidden', 'true');
  document.body.style.overflow = '';
}

function aplicarMascaraCpf(event) {
  const numeros = event.target.value.replace(/\D/g, '').slice(0, 11);
  const partes = [];

  if (numeros.length > 0) partes.push(numeros.slice(0, 3));
  if (numeros.length > 3) partes.push(numeros.slice(3, 6));
  if (numeros.length > 6) partes.push(numeros.slice(6, 9));

  let valor = partes.join('.');

  if (numeros.length > 9) {
    valor += `-${numeros.slice(9, 11)}`;
  }

  event.target.value = valor;
}

function confirmarReserva() {
  const noites = diferencaEmNoites(checkin, checkout);

  if (noites <= 0) {
    mensagemReservaEl.textContent = 'Selecione um per\u00edodo v\u00e1lido para continuar.';
    return;
  }

  const payload = {
    chale: 'Chal\u00e9 Para\u00edso',
    checkin: formatarDataInput(checkin),
    checkout: formatarDataInput(checkout),
    noites,
    precoNoite: obterPrecoNoite(),
    total: noites * obterPrecoNoite(),
    criadoEm: new Date().toISOString(),
  };

  localStorage.setItem('vallisChaleReserva', JSON.stringify(payload));
  mensagemReservaEl.textContent = '';
  abrirModalReserva();
}

btnPrev?.addEventListener('click', () => {
  viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() - 1, 1);
  renderCalendar();
});

btnNext?.addEventListener('click', () => {
  viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 1);
  renderCalendar();
});

precoNoiteEl?.addEventListener('input', atualizarResumo);
btnConfirmar?.addEventListener('click', confirmarReserva);
btnLimpar?.addEventListener('click', limparSelecao);
cpfEl?.addEventListener('input', aplicarMascaraCpf);

document.querySelectorAll('[data-fechar-modal]').forEach((elemento) => {
  elemento.addEventListener('click', fecharModalReserva);
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    fecharModalReserva();
  }
});

atualizarResumo();
renderCalendar();
