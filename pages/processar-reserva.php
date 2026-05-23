<?php

require_once '../config.php'; 

// Garante que só aceita dados se vierem via formulário POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validação e higienização dos dados
    $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_UNSAFE_RAW));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? ''); // Remove pontos e traços
    $id_chale = filter_input(INPUT_POST, 'id_chale', FILTER_VALIDATE_INT);
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim = $_POST['data_fim'] ?? '';

    if (!$nome || !$email || strlen($cpf) !== 11 || !$id_chale) {
        die("Erro: Dados informados são inválidos ou incompletos.");
    }

    if (strtotime($data_inicio) >= strtotime($data_fim)) {
        die("Erro: A data de saída deve ser maior que a data de entrada.");
    }

    // Inserção segura no banco de dados
    try {
        $pdo->beginTransaction();

        // 1. Insere ou atualiza o Cliente 
        $stmtCliente = $pdo->prepare("INSERT INTO cliente (cpf, nome, email) 
                                       VALUES (:cpf, :nome, :email) 
                                       ON DUPLICATE KEY UPDATE nome = :nome, email = :email");
        $stmtCliente->execute([':cpf' => $cpf, ':nome' => $nome, ':email' => $email]);

        // 2. Cria a Reserva com status Pendente
        $stmtReserva = $pdo->prepare("INSERT INTO reserva (id_chale, id_cliente, data_inicio, data_fim, status) 
                                       VALUES (:id_chale, :id_cliente, :data_inicio, :data_fim, 'Pendente')");
        $stmtReserva->execute([
            ':id_chale' => $id_chale, 
            ':id_cliente' => $cpf, 
            ':data_inicio' => $data_inicio, 
            ':data_fim' => $data_fim
        ]);

        // Captura o ID auto-incremental gerado para esta reserva
        $id_reserva_gerado = $pdo->lastInsertId();

        $pdo->commit();
        
        // Alerta de sucesso e redirecionamento limpo
        echo "<script>
                alert('Reserva efetuada com sucesso! Guarde o código da sua reserva: " . $id_reserva_gerado . "');
                window.location.href = '../index.php';
              </script>";
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao salvar a reserva no banco de dados: " . $e->getMessage());
    }
} else {
    // Se alguém tentar aceder ao arquivo diretamente pela URL, manda de volta para a home
    header("Location: ../index.php");
    exit;
}
?>