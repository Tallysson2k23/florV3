<?php
        require_once __DIR__ . '/../models/PedidoEntrega.php';
        require_once __DIR__ . '/../models/PedidoRetirada.php';
        require_once __DIR__ . '/../../config/database.php'; 


class PedidoController {
    public function cadastrar() {
        require_once __DIR__ . '/../views/pedidos/cadastrar.php';


    }

    public function salvar() {
        // A lógica de salvar virá depois
    }

public function formEntrega() {
    require_once __DIR__ . '/../models/Vendedor.php';
    $vendedorModel = new Vendedor(Database::conectar());
    $vendedores = $vendedorModel->listarTodos();
    require __DIR__ . '/../views/pedidos/cadastrar_entrega.php';
}

public function formRetirada() {
    require_once __DIR__ . '/../models/Vendedor.php';
    $vendedorModel = new Vendedor(Database::conectar());
    $vendedores = $vendedorModel->listarTodos();
    require __DIR__ . '/../views/pedidos/cadastrar_retirada.php';
}




public function salvarEntrega() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model = new PedidoEntrega();
        $id = $model->criar($_POST);

        $desejaImprimir = isset($_POST['imprimir']) && $_POST['imprimir'] == '1';

        if ($desejaImprimir) {
            // Redireciona para o novo cupom de cliente
            header("Location: /florV3/public/index.php?rota=imprimir-cupom-cliente&id={$id}&tipo=entrega");
        } else {
            header('Location: /florV3/public/index.php?rota=painel&sucesso=1');
        }

        exit;
    }
}



public function salvarRetirada() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model = new PedidoRetirada();
        $id = $model->criar($_POST);

        $desejaImprimir = isset($_POST['imprimir']) && $_POST['imprimir'] == '1';

        if ($desejaImprimir) {
            // Redireciona para o novo cupom de cliente RETIRADA
            header("Location: /florV3/public/index.php?rota=imprimir-cupom-cliente&id={$id}&tipo=retirada");
        } else {
            header('Location: /florV3/public/index.php?rota=painel&sucesso=1');
        }

        exit;
    }
}


public function historico() {
    $entregaModel = new PedidoEntrega();
    $retiradaModel = new PedidoRetirada();

    $busca = $_GET['busca'] ?? '';
    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $porPagina = 20;

    // Buscar dados combinados
    $resultadosEntrega = $entregaModel->buscar($busca);
    $resultadosRetirada = $retiradaModel->buscar($busca);
    $todos = array_merge($resultadosEntrega, $resultadosRetirada);

    // Ordenar por data decrescente
   usort($todos, fn($a, $b) => ($b['ordem_fila'] ?? 0) <=> ($a['ordem_fila'] ?? 0));

    // Paginação
    $total = count($todos);
    $inicio = ($pagina - 1) * $porPagina;
    $resultados = array_slice($todos, $inicio, $porPagina);
    $totalPaginas = ceil($total / $porPagina);

    require __DIR__ . '/../views/pedidos/historico.php';
}


public function detalhesPedido() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $id = $_GET['id'] ?? null;
    $tipo = strtolower($_GET['tipo'] ?? '');

    $dados = null;

    if ($id && ($tipo === 'entrega' || $tipo === '1-entrega')) {
        $model = new PedidoEntrega();
        $dados = $model->buscarPorId($id);
    } elseif ($id && ($tipo === 'retirada' || $tipo === '2-retirada')) {
        $model = new PedidoRetirada();
        $dados = $model->buscarPorId($id);
    }

    require __DIR__ . '/../views/pedidos/detalhes.php';
}


public function acompanharPedidos() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $entregaModel = new PedidoEntrega();
    $retiradaModel = new PedidoRetirada();

    $busca = $_GET['busca'] ?? '';
    $dataSelecionada = $_GET['data'] ?? date('Y-m-d');

    // Buscar apenas status Pendente, Produção, Pronto e por data
    $statusFiltro = ['Pendente', 'Produção', 'Pronto'];

    // Buscar com filtro de data e busca
    $pedidosEntrega = $entregaModel->buscarPorStatusEData($statusFiltro, $dataSelecionada, $busca);
    $pedidosRetirada = $retiradaModel->buscarPorStatusEData($statusFiltro, $dataSelecionada, $busca);

    // Juntar e ordenar por ordem_fila
    $todosPedidos = array_merge($pedidosEntrega, $pedidosRetirada);
    usort($todosPedidos, function ($a, $b) {
        return ($b['ordem_fila'] ?? 0) <=> ($a['ordem_fila'] ?? 0);
    });

    require __DIR__ . '/../views/pedidos/acompanhamento.php';
}


public function acompanhamentoAtendente() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $pedidoEntrega = new PedidoEntrega();
    $pedidoRetirada = new PedidoRetirada();

    $data = $_GET['data'] ?? date('Y-m-d'); // se não vier data, usa hoje

    // buscar apenas os status Pronto e Entregue para a data selecionada
    $entregas = $pedidoEntrega->buscarPorStatusEData(['Pronto', 'Entregue'], $data);
    $retiradas = $pedidoRetirada->buscarPorStatusEData(['Pronto', 'Entregue'], $data);

    // garantir que sejam array (evita o erro do usort)
    $entregas = is_array($entregas) ? $entregas : [];
    $retiradas = is_array($retiradas) ? $retiradas : [];

    // juntar tudo
    $todosPedidos = array_merge($entregas, $retiradas);

    // ordenar por ordem_fila
    usort($todosPedidos, function($a, $b) {
        return ($b['ordem_fila'] ?? 0) <=> ($a['ordem_fila'] ?? 0);
    });

    // envia para a view
    require __DIR__ . '/../views/pedidos/acompanhamento_atendente.php';
}




public function atualizarStatus() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $id       = $_POST['id']       ?? null;
    $tipo     = $_POST['tipo']     ?? null;   // 'entrega' ou 'retirada'
    $status   = $_POST['status']   ?? null;   // 'Pronto', 'Entregue'…
    $mensagem = $_POST['mensagem'] ?? null;   // pode vir null

    if (!$id || !$tipo || !$status) {
        http_response_code(400);
        echo 'Dados incompletos';
        return;
    }

    // Escolhe o model uma única vez
    $model = ($tipo === 'entrega') ? new PedidoEntrega()
                                   : new PedidoRetirada();

    // Passa sempre $mensagem (pode ser null, o model decide o que fazer)
    $model->atualizarStatus($id, $status, $mensagem);

    echo 'OK';
}


public function imprimirPedido() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $id = $_GET['id'] ?? null;
    $tipo = $_GET['tipo'] ?? null;

    if ($id && $tipo) {
        if ($tipo === 'entrega') {
            $model = new PedidoEntrega();
        } else {
            $model = new PedidoRetirada();
        }

        // Atualiza status para Produção
        $model->atualizarStatus($id, 'Produção');
        $dados = $model->buscarPorId($id);
        require __DIR__ . '/../views/pedidos/imprimir_ordem.php';
    } else {
        echo "Pedido não encontrado.";
    }
}

public function imprimirOrdem() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $id = $_GET['id'] ?? null;
    $tipo = $_GET['tipo'] ?? null;

    if ($id && $tipo) {
        if ($tipo === 'entrega') {
            $model = new PedidoEntrega();
        } elseif ($tipo === 'retirada') {
            $model = new PedidoRetirada();
        } else {
            echo "Tipo inválido.";
            return;
        }

        $dados = $model->buscarPorId($id);

        if (!$dados) {
            echo "Pedido não encontrado.";
            return;
        }

        require __DIR__ . '/../views/pedidos/imprimir_ordem.php';
    } else {
        echo "Dados inválidos.";
    }
}

public function imprimirCupomCliente() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $id = $_GET['id'] ?? null;
    $tipo = $_GET['tipo'] ?? null;

    if ($id && $tipo) {
        if ($tipo === 'entrega') {
            $model = new PedidoEntrega();
        } else {
            $model = new PedidoRetirada();
        }

        $dados = $model->buscarPorId($id);

        if (!$dados) {
            echo "Pedido não encontrado.";
            return;
        }

        require __DIR__ . '/../views/pedidos/imprimir_cupom_cliente.php';
    } else {
        echo "Dados inválidos.";
    }
}

public function painelComPedidos() {
    $entregaModel = new PedidoEntrega();
    $retiradaModel = new PedidoRetirada();

    $pedidosEntrega = $entregaModel->listarTodos();
    $pedidosRetirada = $retiradaModel->listarTodos();

    $todosPedidos = array_merge($pedidosEntrega, $pedidosRetirada);

    $agrupados = [
        'Pendente' => [],
        'Produção' => [],
        'Pronto' => [],
    ];

    foreach ($todosPedidos as $pedido) {
        $status = $pedido['status'];
        if (isset($agrupados[$status])) {
            $agrupados[$status][] = $pedido;
        }
    }

    require __DIR__ . '/../views/painel.php';
}

/*public function verRetiradas() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $entregaModel = new PedidoEntrega();
    $retiradaModel = new PedidoRetirada();

    $entregas = $entregaModel->buscarPorStatus('Entregue');
    $retiradas = $retiradaModel->buscarPorStatus('Entregue');

    $todosEntregues = array_merge($entregas, $retiradas);

    require __DIR__ . '/../views/pedidos/retiradas.php';
}*/

public function retiradas() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $pagina = $_GET['pagina'] ?? 1;
    $limite = 10;
    $offset = ($pagina - 1) * $limite;

    $entregasModel = new PedidoEntrega();
    $retiradasModel = new PedidoRetirada();

    $todosEntregues = array_merge(
        $entregasModel->buscarPorStatus('Entregue'),
        $retiradasModel->buscarPorStatus('Entregue')
    );

    // Ordena pela data
    usort($todosEntregues, function($a, $b) {
        return strtotime($b['data_abertura']) <=> strtotime($a['data_abertura']);
    });

    $total = count($todosEntregues);
    $pedidosPaginados = array_slice($todosEntregues, $offset, $limite);

    require __DIR__ . '/../views/pedidos/retiradas.php';
}

public function cadastrarVendedor() {
    require __DIR__ . '/../views/vendedores/cadastrar_vendedor.php';
}

public function salvarVendedor() {
    $nome = $_POST['nome'] ?? '';

    if ($nome) {
        $pdo = Database::conectar();

        // Buscar o último código
        $stmt = $pdo->query("SELECT codigo FROM vendedores ORDER BY id DESC LIMIT 1");
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);
        $proximoCodigo = "V01";

        if ($ultimo && isset($ultimo['codigo'])) {
            $numero = (int)substr($ultimo['codigo'], 1);
            $proximoNumero = str_pad($numero + 1, 2, "0", STR_PAD_LEFT);
            $proximoCodigo = "V" . $proximoNumero;
        }

        // Inserir novo vendedor com código
        $stmt = $pdo->prepare("INSERT INTO vendedores (nome, codigo) VALUES (?, ?)");
        $stmt->execute([$nome, $proximoCodigo]);
    }

    header('Location: /florV3/public/index.php?rota=lista-vendedores');
    exit;
}


public function listaVendedores() {
    $pdo = Database::conectar();
    $stmt = $pdo->query("SELECT * FROM vendedores ORDER BY nome ASC");
    $vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require __DIR__ . '/../views/vendedores/lista_vendedores.php';
}

public function cadastrarProduto() {
    require __DIR__ . '/../views/produtos/cadastrar_produto.php';
}

public function salvarProduto() {
    require_once __DIR__ . '/../models/Produto.php';

    $nome = $_POST['nome'] ?? '';
    $valor = $_POST['valor'] ?? '';
    $codigo = $_POST['codigo'] ?? '';

    if ($nome && $valor && $codigo) {
        $pdo = Database::conectar();
        $produtoModel = new Produto($pdo);
        $produtoModel->salvar($nome, $valor, $codigo);
    }

    header('Location: /florV3/public/index.php?rota=lista-produtos');
    exit;
}



public function listaProdutos() {
    $pdo = Database::conectar();
    $produtoModel = new Produto($pdo);
    $produtos = $produtoModel->listarTodos();
    require __DIR__ . '/../views/produtos/lista_produtos.php';
}

public function cancelados() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $entregaModel = new PedidoEntrega();
    $retiradaModel = new PedidoRetirada();

    $entregasCanceladas = $entregaModel->buscarPorStatus('Cancelado');
    $retiradasCanceladas = $retiradaModel->buscarPorStatus('Cancelado');

    $cancelados = array_merge($entregasCanceladas, $retiradasCanceladas);

    require __DIR__ . '/../views/pedidos/cancelados.php';
}



}


