<?php
require_once '../config.php';

unset($_SESSION['admin_logado']);

header('Location: ' . BASE_URL . '/pages/login.php');
exit;
