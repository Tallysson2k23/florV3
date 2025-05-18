<?php
require_once __DIR__ . '/../app/controllers/PedidoController.php';

$rota = $_GET['rota'] ?? 'painel';
$controller = new PedidoController();

switch ($rota) {
    case 'painel':
        require_once __DIR__ . '/../app/views/painel.php';
        break;

    case 'escolher-tipo':
        require_once __DIR__ . '/../app/views/pedidos/escolher_tipo.php';
        break;

    case 'cadastrar-pedido':
        $controller->cadastrar();
        break;

    case 'salvar-pedido':
        $controller->salvar();
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

    case 'historico':
        $controller->historico();
        break;

    case 'detalhes':
    $controller->detalhesPedido();
    break;




    default:
        echo "Página não encontrada.";
        break;
}
