<?php

require_once __DIR__ . '/../app/controllers/PedidoController.php';
require_once __DIR__ . '/../app/controllers/UsuarioController.php';
require_once __DIR__ . '/../app/controllers/ProdutoController.php';
require_once __DIR__ . '/../app/controllers/VendedorController.php';
require_once __DIR__ . '/../app/controllers/OperadorController.php';

$rota = $_GET['rota'] ?? 'painel';
$controller = new PedidoController();
$usuarioController = new UsuarioController();
$produtoController = new ProdutoController();
$vendedorController = new VendedorController();

switch ($rota) {
    case 'painel':
        $controller->painelComPedidos();
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
        $id = $_GET['id'] ?? null;
        $tipo = $_GET['tipo'] ?? null;

        if ($id && $tipo) {
            $controller->detalhesPedido($id, $tipo);
        } else {
            echo "❌ Pedido não encontrado.";
        }
        break;

    case 'acompanhamento':
        $controller->acompanharPedidos();
        break;

    case 'acompanhamento-atendente':
        $controller->acompanhamentoAtendente();
        break;

    case 'atualizar-status':
        $controller->atualizarStatus();
        break;

    case 'usuarios':
        $usuarioController->listarUsuarios();
        break;

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

    case 'retiradas':
        $controller->retiradas();
        break;

    case 'cadastrar-vendedor':
        $vendedorController->cadastrar();
        break;

    case 'salvar-vendedor':
        $vendedorController->salvar();
        break;

    case 'lista-vendedores':
        $vendedorController->listar();
        break;

    case 'cadastrar-produto':
        $produtoController->formularioCadastro();
        break;

    case 'salvar-produto':
        $produtoController->salvarProduto();
        break;

    case 'lista-produtos':
        $produtoController->listaProdutos();
        break;

    case 'cancelados':
        $controller->cancelados();
        break;

    case 'acesso-negado':
        require_once __DIR__ . '/../app/views/usuarios/acesso_negado.php';
        break;

    case 'agenda':
        require_once __DIR__ . '/../app/views/agenda.php';
        break;

    case 'cadastrar-operador':
        (new OperadorController())->cadastrarOperador();
        break;

    case 'salvar-operador':
        (new OperadorController())->salvarOperador();
        break;

    case 'lista-operadores':
        (new OperadorController())->listaOperadores();
        break;

    case 'relatorio-operadores':
        (new OperadorController())->relatorioOperadores();
        break;

    case 'registrar-responsavel':
        $controller->registrarResponsavel();
        break;

    case 'editar-operador':
        $controller->editarOperador();
        break;

    case 'atualizar-operador':
        $controller->atualizarOperador();
        break;

    case 'excluir-operador':
        $controller->excluirOperador();
        break;

    case 'permissoes':
        require_once __DIR__ . '/../app/views/permissoes/permissoes.php';
        break;

    case 'editar-numero-pedido':
        require_once __DIR__ . '/../app/views/configuracoes/editar_numero_pedido.php';
        break;

    case 'atualizar-status-usuario':
        $usuarioController->atualizarStatusUsuario();
        break;

    case 'cadastrar-grupo':
        $produtoController->formularioCadastrarGrupo();
        break;

    case 'salvar-grupo':
        $produtoController->salvarGrupo();
        break;

    case 'formulario-cadastro':
        $produtoController->formularioCadastro();
        break;

    case 'lista-grupos':
        $produtoController->listarGrupos();
        break;

    case 'inativar-grupo':
        $produtoController->inativarGrupo();
        break;

    case 'excluir-grupo':
        $produtoController->excluirGrupo();
        break;

    case 'ativar-grupo':
        $produtoController->ativarGrupo();
        break;

    case 'notificacoes-futuras':
        require_once __DIR__ . '/../app/controllers/NotificacaoController.php';
        (new NotificacaoController())->listarPedidosFuturos();
        break;

    case 'marcar-notificacao-lida':
        require_once __DIR__ . '/../app/controllers/NotificacaoController.php';
        (new NotificacaoController())->marcarComoLido();
        break;

case 'editar-produto':
    $produtoController->editarProduto();
    break;

case 'salvar-edicao-produto':
    $produtoController->salvarEdicao();
    break;

case 'ativar-produto':
    $produtoController->ativarProduto();
    break;

case 'inativar-produto':
    $produtoController->inativarProduto();
    break;

case 'buscar-pedidos-produto':
    require_once __DIR__ . '/../app/controllers/PedidoController.php';
    $controller = new PedidoController();
    $controller->buscarPedidosPorProdutoAjax();
    break;








    default:
        echo "Página não encontrada.";
        break;
}
