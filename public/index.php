
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

require_once __DIR__ . '/../app/controllers/PedidoController.php';

$controller = new PedidoController();

switch ($_GET['rota'] ?? 'painel') {
    case 'painel':
        require_once __DIR__ . '/../app/views/painel.php';
        break;
    case 'escolher-tipo':
        require_once __DIR__ . '/../app/views/pedidos/escolher_tipo.php';
        break;
    case 'cadastrar-entrega':
        $controller->formEntrega();
        break;
    case 'cadastrar-retirada':
        $controller->formRetirada();
        break;
    case 'salvar-entrega':
        $controller->salvarEntrega();
        break;
    case 'salvar-retirada':
        $controller->salvarRetirada();
        break;
    default:
        echo "Rota não encontrada.";
}
