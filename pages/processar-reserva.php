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
        'Codigo da reserva: #' . $dadosReserva['id_reserva'],
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
    die('Erro: dados informados sao invalidos ou incompletos.');
}

if (strtotime($data_inicio) >= strtotime($data_fim)) {
    die('Erro: a data de saida deve ser maior que a data de entrada.');
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

    // Mostra o codigo da reserva e abre o WhatsApp com a mensagem pronta.
    $alertMessage = 'Reserva efetuada com sucesso! Guarde o código da sua reserva: ' . $id_reserva_gerado . '. O WhatsApp será aberto com a mensagem pronta.';
    echo "<script>
            alert(" . json_encode($alertMessage, JSON_UNESCAPED_SLASHES) . ");
            window.location.href = " . json_encode($linkWhatsapp, JSON_UNESCAPED_SLASHES) . ";
          </script>";
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($e->getMessage() === 'Este e-mail ja esta cadastrado com outro CPF.') {
        die('Erro: ' . $e->getMessage());
    }

    error_log('Erro ao salvar reserva: ' . $e->getMessage());
    die('Erro ao salvar a reserva. Tente novamente mais tarde.');
}
