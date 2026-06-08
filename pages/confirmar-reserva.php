<?php
require_once '../config.php';

// Esta pagina pertencia ao fluxo antigo de reserva.
// O fluxo atual confirma os dados no modal de reservar.php, usando o chale correto.
header('Location: ' . BASE_URL . '/pages/reservar.php');
exit;
