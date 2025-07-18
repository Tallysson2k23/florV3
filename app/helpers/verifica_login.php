<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /florV3/public/index.php?rota=login");
    exit;
}

$usuarioModel = new Usuario();
$usuario = $usuarioModel->buscarPorId($_SESSION['usuario_id']);

if (!$usuario || !$usuario['ativo']) {
    session_destroy();
    echo "<script>alert('Seu usuário foi inativado. Contate o administrador.'); window.location.href='/florV3/public/index.php?rota=login';</script>";
    exit;
}
?>
