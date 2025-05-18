// public/index.php
<?php

require_once __DIR__ . '/../app/controllers/PedidoController.php';

$rota = $_GET['rota'] ?? 'painel';

switch ($rota) {
    case 'painel':
        require_once __DIR__ . '/../app/views/painel.php';
        break;
    case 'cadastrar-pedido':
        $controller = new PedidoController();
        $controller->cadastrar();
        break;
    case 'salvar-pedido':
        $controller = new PedidoController();
        $controller->salvar();
        break;
    default:
        echo "Página não encontrada.";
}
