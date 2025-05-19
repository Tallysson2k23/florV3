<?php
require_once __DIR__ . '/../app/controllers/PedidoController.php';
require_once __DIR__ . '/../app/controllers/UsuarioController.php';

$rota = $_GET['rota'] ?? 'painel';
$controller = new PedidoController();
$usuarioController = new UsuarioController();

switch ($rota) {
  case 'painel':
    $controller->painelComPedidos(); // ✅ importante estar esse nome
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

    case 'acompanhamento':
        $controller->acompanharPedidos();
        break;

    case 'atualizar-status':
        $controller->atualizarStatus();
        break;

    // 🔄 Acesso à lista de usuários
    case 'usuarios':
        $usuarioController->listarUsuarios(); // agora vai direto para a lista
        break;

    // ✅ Cadastro de novo usuário
    case 'novo-usuario':
        $usuarioController->formularioCadastro();
        break;

    case 'salvar-usuario':
        $usuarioController->salvarCadastro();
        break;

    case 'login':
        $usuarioController->login();
        break;

    case 'autenticar':
        $usuarioController->autenticar();
        break;

    case 'logout':
        $usuarioController->logout();
        break;

    case 'imprimir-pedido':
    $controller->imprimirPedido();
    break;

    case 'imprimir-ordem':
    $controller->imprimirOrdem();
    break;

        case 'imprimir-cupom-cliente':
    $controller->imprimirCupomCliente();
    break;

 





    default:
        echo "Página não encontrada.";
        break;
}
