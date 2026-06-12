<?php
require_once '../config.php';

unset($_SESSION['admin_logado'], $_SESSION['mostrar_login_admin']);

header('Location: ' . BASE_URL . '/index.php');
exit;
