// ====== ELEMENTOS DO DOM ======
const diasEl = document.getElementById('dias'); // onde os dias do calendário são renderizados
const mesAnoEl = document.getElementById('mesAno'); // título do mês/ano

const checkinEl = document.getElementById('checkin'); // exibe data de entrada
const checkoutEl = document.getElementById('checkout'); // exibe data de saída
const noitesEl = document.getElementById('noites'); // mostra quantidade de noites

const precoNoiteEl = document.getElementById('precoNoite'); // input do preço por noite
const resumoTotalEl = document.getElementById('resumoTotal'); // mostra valor total calculado

const mensagemReservaEl = document.getElementById('mensagemReserva'); // mensagens para o usuário

const btnConfirmar = document.getElementById('confirmarReserva'); // botão de salvar reserva
const btnLimpar = document.getElementById('limparDatas'); // botão de limpar seleção

const btnPrev = document.getElementById('prev'); // mês anterior
const btnNext = document.getElementById('next'); // próximo mês

// ====== LISTA DE MESES (para exibir no calendário) ======
const meses = [
  'Janeiro',
  'Fevereiro',
  'Março',
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

// formatação de data no padrão brasileiro
const localeFormat = new Intl.DateTimeFormat('pt-BR', {
  day: 'numeric',
  month: 'short',
  year: 'numeric',
});

// ====== ESTADO DO CALENDÁRIO ======
let viewDate = new Date(2026, 3, 1); // mês que está sendo exibido
let checkin = new Date(2026, 3, 15); // data inicial selecionada
let checkout = new Date(2026, 3, 30); // data final selecionada

// ====== FUNÇÕES UTILITÁRIAS ======

// normaliza a data (remove horas/minutos/segundos)
function normalizarData(data) {
  return new Date(data.getFullYear(), data.getMonth(), data.getDate());
}

// compara se duas datas são exatamente iguais
function dataIgual(a, b) {
  return a && b && a.getTime() === b.getTime();
}

// formata data para exibição
function formatarData(data) {
  if (!data) return '--';
  return localeFormat.format(data);
}

// calcula diferença em dias entre duas datas
function diferencaEmNoites(inicio, fim) {
  if (!inicio || !fim) return 0;
  return Math.round((fim - inicio) / (1000 * 60 * 60 * 24));
}

// pega o preço por noite do input
function obterPrecoNoite() {
  const valor = Number(precoNoiteEl.value);
  return Number.isFinite(valor) && valor > 0 ? valor : 0;
}

// ====== ATUALIZA RESUMO DA RESERVA ======
function atualizarResumo() {
if (checkin) {
    checkinEl.textContent = formatarData(checkin);
    
    // === ADICIONE ESTA LINHA: Preenche o input do formulário PHP ===
    document.getElementById('data_inicio').value = checkin.toISOString().split('T')[0];
  } else {
    checkinEl.textContent = '-';
    // Se limpar as datas, limpa o formulário também
    document.getElementById('data_inicio').value = '';
  }
  if (checkout) {
    checkoutEl.textContent = formatarData(checkout);
    
    // === ADICIONE ESTA LINHA: Preenche o input do formulário PHP ===
    document.getElementById('data_fim').value = checkout.toISOString().split('T')[0];
  } else {
    checkoutEl.textContent = '-';
    // Se limpar as datas, limpa o formulário também
    document.getElementById('data_fim').value = '';
  }

  
const noites = diferencaEmNoites(checkin, checkout);
  if (noites > 0) {
    noitesEl.textContent = noites;
    const preco = obterPrecoNoite();
    resumoTotalEl.textContent = formatarMoeda(noites * preco);
  } else {
    noitesEl.textContent = '0';
    resumoTotalEl.textContent = formatarMoeda(0);
  }
  // const noites = diferencaEmNoites(checkin, checkout);
  // noitesEl.textContent = noites || '--';

  // const total = noites * obterPrecoNoite();

  // // mostra total formatado ou mensagem padrão
  // resumoTotalEl.textContent =
  //   total > 0
  //     ? `Total estimado: ${total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`
  //     : 'Selecione check-in e check-out para calcular o total';

  // // só libera botão se tiver período válido
  // btnConfirmar.disabled = noites <= 0;
}

// ====== RENDERIZA O CALENDÁRIO ======
function renderCalendar() {
  diasEl.innerHTML = ''; // limpa calendário anterior

  mesAnoEl.textContent = `${meses[viewDate.getMonth()]} ${viewDate.getFullYear()}`;

  const ano = viewDate.getFullYear();
  const mes = viewDate.getMonth();

  const primeiroDiaSemana = new Date(ano, mes, 1).getDay();
  const ultimoDiaMes = new Date(ano, mes + 1, 0).getDate();

  const hoje = normalizarData(new Date());

  // espaços vazios antes do 1º dia do mês
  for (let i = 0; i < primeiroDiaSemana; i++) {
    const empty = document.createElement('div');
    empty.className = 'calendar-day--empty';
    diasEl.appendChild(empty);
  }

  // cria os dias do mês
  for (let dia = 1; dia <= ultimoDiaMes; dia++) {
    const data = normalizarData(new Date(ano, mes, dia));

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'calendar-day';
    button.textContent = dia;

    // acessibilidade (leitura por leitor de tela)
    button.setAttribute('aria-label', formatarData(data));

    // marca dia atual
    if (dataIgual(data, hoje)) {
      button.classList.add('is-today');
    }

    // marca intervalo entre check-in e check-out
    if (checkin && checkout && data > checkin && data < checkout) {
      button.classList.add('is-range');
    }

    // marca início e fim da seleção
    if (dataIgual(data, checkin)) {
      button.classList.add('is-start');
    }

    if (dataIgual(data, checkout)) {
      button.classList.add('is-end');
    }

    // clique no dia seleciona data
    button.addEventListener('click', () => selecionarData(data));

    diasEl.appendChild(button);
  }
}

// ====== SELEÇÃO DE DATAS ======
function selecionarData(dataSelecionada) {
  mensagemReservaEl.textContent = '';

  // primeira seleção ou reinício
  if (!checkin || (checkin && checkout)) {
    checkin = dataSelecionada;
    checkout = null;

    // se clicou em data anterior, reinicia seleção
  } else if (dataSelecionada.getTime() <= checkin.getTime()) {
    checkin = dataSelecionada;
    checkout = null;

    // define checkout
  } else {
    checkout = dataSelecionada;
  }

  atualizarResumo();
  renderCalendar();
}

// ====== LIMPAR SELEÇÃO ======
function limparSelecao() {
  checkin = null;
  checkout = null;
  mensagemReservaEl.textContent = '';
  atualizarResumo();
  renderCalendar();
}

// ====== SALVAR RESERVA ======
function salvarReserva() {
  const noites = diferencaEmNoites(checkin, checkout);

  if (noites <= 0) {
    mensagemReservaEl.textContent = 'Selecione um período válido para continuar.';
    return;
  }

  // objeto da reserva
  const payload = {
    chale: 'Chalé Paraíso',
    checkin: checkin.toISOString(),
    checkout: checkout.toISOString(),
    noites,
    precoNoite: obterPrecoNoite(),
    total: noites * obterPrecoNoite(),
    criadoEm: new Date().toISOString(),
  };

  // salva no navegador
  localStorage.setItem('vallisChaleReserva', JSON.stringify(payload));

  mensagemReservaEl.textContent = `Pré-reserva salva com sucesso: ${formatarData(checkin)} até ${formatarData(checkout)}.`;
}

// ====== NAVEGAÇÃO DE MESES ======
btnPrev.addEventListener('click', () => {
  viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() - 1, 1);
  renderCalendar();
});

btnNext.addEventListener('click', () => {
  viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 1);
  renderCalendar();
});

// ====== EVENTOS GERAIS ======
precoNoiteEl.addEventListener('input', atualizarResumo);
btnConfirmar.addEventListener('click', salvarReserva);
btnLimpar.addEventListener('click', limparSelecao);

// inicialização
atualizarResumo();
renderCalendar();
