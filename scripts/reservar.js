// ====== ELEMENTOS DO DOM ======
const diasEl = document.getElementById('dias'); // area onde os dias do calendario sao renderizados
const mesAnoEl = document.getElementById('mesAno'); // titulo com mes e ano exibidos

const checkinEl = document.getElementById('checkin'); // texto da data de entrada
const checkoutEl = document.getElementById('checkout'); // texto da data de saida
const noitesEl = document.getElementById('noites'); // quantidade de noites calculadas

const precoNoiteEl = document.getElementById('precoNoite'); // diaria carregada do banco pelo PHP
const resumoTotalEl = document.getElementById('resumoTotal'); // valor total estimado

const mensagemReservaEl = document.getElementById('mensagemReserva'); // mensagens de validacao para o visitante

const btnConfirmar = document.getElementById('confirmarReserva'); // botao que salva a pre-reserva
const btnLimpar = document.getElementById('limparDatas'); // botao que limpa o intervalo selecionado

const btnPrev = document.getElementById('prev'); // navegacao para o mes anterior
const btnNext = document.getElementById('next'); // navegacao para o proximo mes

const modalReserva = document.getElementById('modalReserva');
const modalCheckinEl = document.getElementById('modalCheckin');
const modalCheckoutEl = document.getElementById('modalCheckout');
const cpfEl = document.getElementById('cpf');

// ====== LISTA DE MESES ======
const meses = [
  'Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho',
  'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro',
];

// Formatador usado para exibir datas no padrao brasileiro.
const localeFormat = new Intl.DateTimeFormat('pt-BR', {
  day: 'numeric',
  month: 'short',
  year: 'numeric',
});

// ====== ESTADO DO CALENDARIO ======
let viewDate = new Date(2026, 3, 1); // mes exibido inicialmente: abril de 2026
let checkin = new Date(2026, 3, 15); // data inicial selecionada
let checkout = new Date(2026, 3, 30); // data final selecionada

// ====== FUNCOES UTILITARIAS ======

function normalizarData(data) {
  // Remove horas, minutos e segundos para comparar apenas o dia.
  return new Date(data.getFullYear(), data.getMonth(), data.getDate());
}

function dataIgual(a, b) {
  // Compara duas datas ja normalizadas.
  return a && b && a.getTime() === b.getTime();
}

function formatarData(data) {
  // Retorna um texto amigavel para o painel lateral da reserva.
  if (!data) return '--';
  return localeFormat.format(data);
}

function formatarDataInput(data) {
  // Os inputs de data enviados ao PHP precisam estar no formato AAAA-MM-DD.
  // Esse formato e o que o MySQL espera para campos DATE.
  if (!data) return '';

  const ano = data.getFullYear();
  const mes = String(data.getMonth() + 1).padStart(2, '0');
  const dia = String(data.getDate()).padStart(2, '0');

  return `${ano}-${mes}-${dia}`;
}

function formatarMoeda(valor) {
  // Exibe valores em reais.
  return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

function diferencaEmNoites(inicio, fim) {
  // Calcula quantas noites existem entre check-in e check-out.
  if (!inicio || !fim) return 0;
  return Math.round((fim - inicio) / (1000 * 60 * 60 * 24));
}

function obterPrecoNoite() {
  // Lendo do input, o PHP consegue preencher o preco vindo do banco de dados.
  const valor = Number(precoNoiteEl?.value);
  return Number.isFinite(valor) && valor > 0 ? valor : 0;
}

// ====== RESUMO DA RESERVA ======
function atualizarResumo() {
  const inputInicio = document.getElementById('data_inicio');
  const inputFim = document.getElementById('data_fim');

  // Atualiza o texto visivel e, quando a pagina tiver formulario PHP,
  // tambem preenche os inputs enviados para processar-reserva.php.
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

// ====== RENDERIZACAO DO CALENDARIO ======
function renderCalendar() {
  if (!diasEl || !mesAnoEl) return;

  diasEl.innerHTML = '';
  mesAnoEl.textContent = `${meses[viewDate.getMonth()]} ${viewDate.getFullYear()}`;

  const ano = viewDate.getFullYear();
  const mes = viewDate.getMonth();
  const primeiroDiaSemana = new Date(ano, mes, 1).getDay();
  const ultimoDiaMes = new Date(ano, mes + 1, 0).getDate();
  const hoje = normalizarData(new Date());

  // Preenche espacos vazios antes do primeiro dia do mes.
  for (let i = 0; i < primeiroDiaSemana; i++) {
    const empty = document.createElement('div');
    empty.className = 'calendar-day--empty';
    diasEl.appendChild(empty);
  }

  // Cria um botao para cada dia do mes exibido.
  for (let dia = 1; dia <= ultimoDiaMes; dia++) {
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

// ====== SELECAO DE DATAS ======
function selecionarData(dataSelecionada) {
  mensagemReservaEl.textContent = '';

  // Primeiro clique define check-in. Se ja existe intervalo completo,
  // um novo clique reinicia a selecao.
  if (!checkin || (checkin && checkout)) {
    checkin = dataSelecionada;
    checkout = null;
  } else if (dataSelecionada.getTime() <= checkin.getTime()) {
    // Clicar em uma data anterior ao check-in reinicia o intervalo.
    checkin = dataSelecionada;
    checkout = null;
  } else {
    // Data posterior ao check-in vira check-out.
    checkout = dataSelecionada;
  }

  atualizarResumo();
  renderCalendar();
}

function limparSelecao() {
  // Remove o intervalo atual e volta o resumo para o estado vazio.
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

  // Copia as datas escolhidas no calendario para campos hidden do formulario.
  // Quando o usuario envia, processar-reserva.php recebe esses valores por POST.
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
  // A mascara ajuda o usuario a digitar, mas o PHP remove a pontuacao antes de salvar.
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
    idChale: document.querySelector('input[name="id_chale"]')?.value || null,
    chale: document.body.dataset.chaleName || 'Chale',
    checkin: formatarDataInput(checkin),
    checkout: formatarDataInput(checkout),
    noites,
    precoNoite: obterPrecoNoite(),
    total: noites * obterPrecoNoite(),
    criadoEm: new Date().toISOString(),
  };

  // Guarda uma copia local apenas para apoio da interface.
  // A gravacao oficial no banco acontece quando o formulario PHP e enviado.
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
