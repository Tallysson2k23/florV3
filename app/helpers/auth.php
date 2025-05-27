<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function verificarAcessoAdmin() {
    if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
        header('Location: /florV3/public/index.php?rota=acesso-negado');
        exit;
    }
}

function verificarAcessoColaborador() {
    if (!isset($_SESSION['usuario_tipo']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'colaborador'])) {
        header('Location: /florV3/public/index.php?rota=acesso-negado');
        exit;
    }
}
