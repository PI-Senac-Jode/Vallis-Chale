<?php

require_once '../config.php';

function normalize_cpf(string $cpf): string
{
    return preg_replace('/\D/', '', $cpf);
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

        if (normalize_cpf($cpfCliente) !== $cpf) {
            throw new Exception('Este e-mail ja esta cadastrado com outro CPF.');
        }

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

    // Mostra o codigo da reserva e retorna o visitante para a pagina inicial.
    echo "<script>
            alert('Reserva efetuada com sucesso! Guarde o codigo da sua reserva: " . $id_reserva_gerado . "');
            window.location.href = '../index.php';
          </script>";
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    die('Erro ao salvar a reserva no banco de dados: ' . $e->getMessage());
}
