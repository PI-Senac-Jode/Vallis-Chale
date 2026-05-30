<?php require_once '../config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Reserva — Vallis Chalé</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="../frontEnd/styles/tokens.css" />
    <link rel="stylesheet" href="../frontEnd/styles/global.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/nav.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/hero.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/footer.css" />
    <link rel="stylesheet" href="../frontEnd/styles/sections/login.css">

    <style>
        .booking-container {
            max-width: 700px;
            margin: var(--space-10) auto;
            padding: var(--space-8);
            background-color: var(--surface-bright);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-soft);
            font-family: var(--font-body);
        }
        .booking-title {
            font-family: var(--font-display);
            color: var(--primary);
            font-size: var(--display-sm);
            margin-bottom: var(--space-2);
            text-align: center;
            font-weight: 700;
        }
        .booking-subtitle {
            color: var(--on-surface-muted);
            font-size: var(--body-sm);
            text-align: center;
            margin-bottom: var(--space-8);
        }
        .booking-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-5);
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: span 2;
        }
        .form-group label {
            font-size: var(--body-xs);
            font-weight: 600;
            color: var(--primary-container);
            margin-bottom: var(--space-2);
            text-transform: uppercase;
        }
        .form-group input {
            padding: var(--space-4);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: var(--body-md);
            color: var(--on-surface);
            background-color: var(--surface-container-lowest);
            outline: none;
        }
        .form-group input:focus {
            border-color: var(--secondary);
            background-color: var(--surface-bright);
            box-shadow: 0 0 0 3px rgba(213, 147, 46, 0.15);
        }
        .btn-submit {
            width: 100%;
            padding: var(--space-4) var(--space-8);
            background: var(--primary);
            color: var(--on-primary);
            font-family: var(--font-body);
            font-size: var(--body-sm);
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            border-radius: var(--radius-2xl);
            cursor: pointer;
            margin-top: var(--space-4);
            transition: transform 180ms ease, background-color 180ms ease;
        }
        .btn-submit:hover { background-color: var(--primary-container); }
        @media (max-width: 600px) {
            .booking-form { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .booking-container { margin: var(--space-4); padding: var(--space-5); }
        }
    </style>
</head>

<body>

    <?php include "../frontEnd/includes/nav.inc.php"; ?>

    <div class="booking-container">
        <h2 class="booking-title">Confirmar Reserva</h2>
        <p class="booking-subtitle">Preencha seus dados para confirmar a reserva.</p>
        
        <form action="processar-reserva.php" method="POST" class="booking-form">
            <div class="form-group full-width">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
            </div>

            <input type="hidden" name="id_chale" value="1"> 

            <div class="form-group">
                <label for="data_inicio">Data de Entrada</label>
                <input type="date" id="data_inicio" name="data_inicio" readonly required>
            </div>

            <div class="form-group">
                <label for="data_fim">Data de Saída</label>
                <input type="date" id="data_fim" name="data_fim" readonly required>
            </div>

            <div class="form-group full-width">
                <button type="submit" class="btn-submit">Confirmar e Pagar</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Busca a string salva pelo calendário
            const dadosSalvos = localStorage.getItem('vallisChaleReserva');
            
            if (dadosSalvos) {
                const reserva = JSON.parse(dadosSalvos);
                
                // Converte as datas salvas (ISO) para o padrão 'AAAA-MM-DD' exigido pelo input date
                if (reserva.checkin) {
                    document.getElementById('data_inicio').value = reserva.checkin.split('T')[0];
                }
                if (reserva.checkout) {
                    document.getElementById('data_fim').value = reserva.checkout.split('T')[0];
                }
            }
        });
    </script>

    <script src="../frontEnd/js/reservar.js"></script>
</body>
</html>