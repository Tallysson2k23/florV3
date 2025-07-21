<?php
require_once __DIR__ . '/../models/PedidoEntrega.php';
require_once __DIR__ . '/../models/PedidoRetirada.php';
require_once __DIR__ . '/../models/Vendedor.php';
require_once __DIR__ . '/../../config/database.php';

class PedidoController {

    public function cadastrar() {
        require_once __DIR__ . '/../views/pedidos/cadastrar.php';
    }

    public function salvar() {
        // A lógica de salvar virá depois
    }

    public function formEntrega() {
        $vendedorModel = new Vendedor(Database::conectar());
        $vendedores = $vendedorModel->listarTodos();
        require __DIR__ . '/../views/pedidos/cadastrar_entrega.php';
    }

    public function formRetirada() {
        $vendedorModel = new Vendedor(Database::conectar());
        $vendedores = $vendedorModel->listarTodos();
        require __DIR__ . '/../views/pedidos/cadastrar_retirada.php';
    }

public function salvarEntrega() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dados = $_POST;

        // Corrigir formato dos produtos (nome, qtd, obs)
if (isset($dados['produtos']) && is_array($dados['produtos'])) {
    $itensFormatados = [];

    foreach ($dados['produtos'] as $produto) {
        $nome = $produto['nome'] ?? '';
        $qtd = $produto['quantidade'] ?? '1';
        $obs = trim($produto['observacao'] ?? '');

        $texto = "$qtd x $nome" . ($obs ? " ($obs)" : "");
        $itensFormatados[] = $texto;
    }

    $dados['produtos'] = implode(', ', $itensFormatados);
}

        // Garantir o status conforme seleção do usuário
        $dados['enviar_para'] = $_POST['enviar_para'] ?? null;
        if ($dados['enviar_para'] === 'pronta_entrega') {
            $dados['status'] = 'Pronto';
        } else {
            $dados['status'] = 'Pendente';
        }

        // Salvar no banco
        $model = new PedidoEntrega();
        $id = $model->criar($dados);

        // Impressão opcional
        $desejaImprimir = isset($_POST['imprimir']) && $_POST['imprimir'] == '1';
        if ($desejaImprimir) {
            header("Location: /florV3/public/index.php?rota=imprimir-cupom-cliente&id={$id}&tipo=entrega");
        } else {
            header('Location: /florV3/public/index.php?rota=painel&sucesso=1');
        }
        exit;
    }
}


public function salvarRetirada() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dados = $_POST;

        // Corrigir formato dos produtos (nome, qtd, obs)
       if (isset($dados['produtos']) && is_array($dados['produtos'])) {
    $itensFormatados = [];

    foreach ($dados['produtos'] as $produto) {
        $nome = $produto['nome'] ?? '';
        $qtd = $produto['quantidade'] ?? '1';
        $obs = trim($produto['observacao'] ?? '');

        $texto = "$qtd x $nome" . ($obs ? " ($obs)" : "");
        $itensFormatados[] = $texto;
    }

    $dados['produtos'] = implode(', ', $itensFormatados);
}


        $dados['enviar_para'] = $_POST['enviar_para'] ?? null;

        if ($dados['enviar_para'] === 'pronta_entrega') {
            $dados['status'] = 'Pronto';
        } else {
            $dados['status'] = 'Pendente';
        }

        $model = new PedidoRetirada();
        $id = $model->criar($dados);

        $desejaImprimir = isset($_POST['imprimir']) && $_POST['imprimir'] == '1';

        if ($desejaImprimir) {
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

        $resultadosEntrega = $entregaModel->buscar($busca);
        $resultadosRetirada = $retiradaModel->buscar($busca);
        $todos = array_merge($resultadosEntrega, $resultadosRetirada);

        usort($todos, fn($a, $b) => ($b['ordem_fila'] ?? 0) <=> ($a['ordem_fila'] ?? 0));

        $total = count($todos);
        $inicio = ($pagina - 1) * $porPagina;
        $resultados = array_slice($todos, $inicio, $porPagina);
        $totalPaginas = ceil($total / $porPagina);

        require __DIR__ . '/../views/pedidos/historico.php';
    }

    public function acompanharPedidos() {
        $entregaModel = new PedidoEntrega();
        $retiradaModel = new PedidoRetirada();

$busca = $_GET['produto'] ?? '';
$dataSelecionada = $_GET['data'] ?? date('Y-m-d');
$statusFiltro = ['Pendente', 'Produção', 'Pronto'];

// Busca pedidos com produtos que contenham o termo buscado
$pedidosEntrega = $entregaModel->buscarPorProdutoEData($busca, $dataSelecionada, $statusFiltro);
$pedidosRetirada = $retiradaModel->buscarPorProdutoEData($busca, $dataSelecionada, $statusFiltro);

        $todosPedidos = array_merge($pedidosEntrega, $pedidosRetirada);
        usort($todosPedidos, function ($a, $b) {
            return ($b['ordem_fila'] ?? 0) <=> ($a['ordem_fila'] ?? 0);
        });

        require __DIR__ . '/../views/pedidos/acompanhamento.php';
    }

public function acompanhamentoAtendente() {
    $pedidoEntrega = new PedidoEntrega();
    $pedidoRetirada = new PedidoRetirada();

    $data = $_GET['data'] ?? date('Y-m-d');
    $usuarioTipo = $_SESSION['usuario_tipo'] ?? 'colaborador';

    // Buscar todos os pedidos com esses status (inclusive "Cancelado")
    $entregas = $pedidoEntrega->buscarPorStatusEData(['Pronto', 'Entregue', 'Retorno', 'Cancelado'], $data);
    $retiradas = $pedidoRetirada->buscarPorStatusEData(['Pronto', 'Entregue', 'Retorno', 'Cancelado'], $data);

    // 🔁 Novo filtro: só esconder "Cancelado" ou "Retorno" se NÃO for do dia selecionado
    $entregas = array_filter($entregas, function($pedido) use ($data) {
        if (in_array($pedido['status'], ['Retorno', 'Cancelado'])) {
            return $pedido['data_abertura'] === $data;
        }
        return true;
    });

    $retiradas = array_filter($retiradas, function($pedido) use ($data) {
        if (in_array($pedido['status'], ['Retorno', 'Cancelado'])) {
            return $pedido['data_abertura'] === $data;
        }
        return true;
    });

    $entregas = is_array($entregas) ? $entregas : [];
    $retiradas = is_array($retiradas) ? $retiradas : [];

    $todosPedidos = array_merge($entregas, $retiradas);

    // Ordena por ordem de chegada
    usort($todosPedidos, function($a, $b) {
        return ($b['ordem_fila'] ?? 0) <=> ($a['ordem_fila'] ?? 0);
    });

    require __DIR__ . '/../views/pedidos/acompanhamento_atendente.php';
}



public function atualizarStatus() {
    $id = $_POST['id'] ?? null;
    $tipo = $_POST['tipo'] ?? null;
    $status = $_POST['status'] ?? null;
    $mensagem = $_POST['mensagem'] ?? null;
    $responsavel = $_POST['responsavel'] ?? null;

    if (!$id || !$tipo || !$status) {
        http_response_code(400);
        echo 'Dados incompletos';
        return;
    }

    // Define o model correto
    $model = ($tipo === 'entrega') ? new PedidoEntrega() : new PedidoRetirada();
    $model->atualizarStatus($id, $status, $mensagem, $responsavel);

    // Registra o responsável pela produção (caso aplicável)
    if ($status === 'Produção' && $responsavel) {
        require_once __DIR__ . '/../models/ResponsavelProducao.php';
        $responsavelModel = new ResponsavelProducao();
        $responsavelModel->criar($id, $tipo, $responsavel);
    }

    // ✅ REGISTRAR NO HISTÓRICO DE STATUS
    require_once __DIR__ . '/../models/HistoricoStatus.php';
    $historicoModel = new HistoricoStatus(Database::conectar());
    $historicoModel->registrar($id, $tipo, $status, $mensagem);


    echo 'OK';
}




    public function imprimirPedido() {
        $id = $_GET['id'] ?? null;
        $tipo = $_GET['tipo'] ?? null;

        if ($id && $tipo) {
            $model = ($tipo === 'entrega') ? new PedidoEntrega() : new PedidoRetirada();
            $model->atualizarStatus($id, 'Produção');
            $dados = $model->buscarPorId($id);
            require __DIR__ . '/../views/pedidos/imprimir_ordem.php';
        } else {
            echo "Pedido não encontrado.";
        }
    }

    public function imprimirOrdem() {
        $id = $_GET['id'] ?? null;
        $tipo = $_GET['tipo'] ?? null;

        if ($id && $tipo) {
            $model = ($tipo === 'entrega') ? new PedidoEntrega() : ($tipo === 'retirada' ? new PedidoRetirada() : null);
            if (!$model) {
                echo "Tipo inválido."; return;
            }
            $dados = $model->buscarPorId($id);
            if (!$dados) {
                echo "Pedido não encontrado."; return;
            }
            require __DIR__ . '/../views/pedidos/imprimir_ordem.php';
        } else {
            echo "Dados inválidos.";
        }
    }

    public function imprimirCupomCliente() {
        $id = $_GET['id'] ?? null;
        $tipo = $_GET['tipo'] ?? null;

        if ($id && $tipo) {
            $model = ($tipo === 'entrega') ? new PedidoEntrega() : new PedidoRetirada();
            $dados = $model->buscarPorId($id);
            if (!$dados) {
                echo "Pedido não encontrado."; return;
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

public function detalhesPedido() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';
    require_once __DIR__ . '/../models/HistoricoStatus.php';

    $id = $_GET['id'] ?? null;
    $tipoRaw = strtolower($_GET['tipo'] ?? '');

    $dados = null;
    $historico = [];

    // Limpa o tipo para garantir que será 'entrega' ou 'retirada'
    if (str_contains($tipoRaw, 'entrega')) {
        $tipoLimpo = 'entrega';
        $model = new PedidoEntrega();
        $dados = $model->buscarPorId($id);
    } elseif (str_contains($tipoRaw, 'retirada')) {
        $tipoLimpo = 'retirada';
        $model = new PedidoRetirada();
        $dados = $model->buscarPorId($id);
    }

    // Buscar histórico apenas se tiver dados válidos
    if ($dados && isset($tipoLimpo)) {
        $historicoModel = new HistoricoStatus(Database::conectar());
        $historico = $historicoModel->buscarPorPedido($id, $tipoLimpo);
    }

    require __DIR__ . '/../views/pedidos/detalhes.php';
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

public function cadastrarOperador() {
    require __DIR__ . '/../views/operadores/cadastrar_operador.php';
}

public function salvarOperador() {
    $nome = $_POST['nome'] ?? '';
    if ($nome) {
        $pdo = Database::conectar();
        $stmt = $pdo->prepare("INSERT INTO operadores (nome) VALUES (:nome)");
        $stmt->execute([':nome' => $nome]);
    }
    header('Location: /florV3/public/index.php?rota=lista-operadores');
    exit;
}


public function listarOperadores() {
    $pdo = Database::conectar();
    $stmt = $pdo->query("SELECT * FROM operadores ORDER BY nome ASC");
    $operadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    require __DIR__ . '/../views/operadores/lista_operadores.php';
}

public function registrarResponsavel() {
    require_once __DIR__ . '/../models/ResponsavelProducao.php';
    $pedidoId = $_POST['id'] ?? null;
    $tipo = $_POST['tipo'] ?? null;
    $responsavel = $_POST['responsavel'] ?? null;

    if ($pedidoId && $tipo && $responsavel) {
        $registro = new ResponsavelProducao();
        $registro->criar($pedidoId, $tipo, $responsavel);
        echo 'Registrado';
    } else {
        http_response_code(400);
        echo 'Dados incompletos';
    }
}


public function editarOperador() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        header('Location: /florV3/public/index.php?rota=lista-operadores');
        exit;
    }

    $pdo = Database::conectar();
    $stmt = $pdo->prepare("SELECT * FROM operadores WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $operador = $stmt->fetch(PDO::FETCH_ASSOC);

    require __DIR__ . '/../views/operadores/editar_operador.php';
}

public function atualizarOperador() {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';

    if ($id && $nome) {
        $pdo = Database::conectar();
        $stmt = $pdo->prepare("UPDATE operadores SET nome = :nome WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':id' => $id]);
    }

    header('Location: /florV3/public/index.php?rota=lista-operadores');
    exit;
}

public function excluirOperador() {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $pdo = Database::conectar();
        $stmt = $pdo->prepare("DELETE FROM operadores WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    header('Location: /florV3/public/index.php?rota=lista-operadores');
    exit;
}



public function retiradas() {
    require_once __DIR__ . '/../models/PedidoRetirada.php';
    require_once __DIR__ . '/../models/PedidoEntrega.php';

    $pedidoRetirada = new PedidoRetirada();
    $pedidoEntrega = new PedidoEntrega();

    $retiradas = $pedidoRetirada->buscarPorStatus('Entregue');
    $entregas  = $pedidoEntrega->buscarPorStatus('Entregue');

    // Junta as duas listas
    $todosPedidos = array_merge($retiradas, $entregas);

    // Ordena pela data de abertura (opcional)
    usort($todosPedidos, function($a, $b) {
        return strtotime($b['data_abertura']) - strtotime($a['data_abertura']);
    });

    // Paginação (10 por página)
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $porPagina = 10;
    $total = count($todosPedidos);
    $inicio = ($pagina - 1) * $porPagina;
    $pedidosPaginados = array_slice($todosPedidos, $inicio, $porPagina);

    require __DIR__ . '/../views/pedidos/retiradas.php';
}


public function buscarPedidosPorProdutoAjax()
{
    $busca = $_GET['produto'] ?? '';
    $data = $_GET['data'] ?? date('Y-m-d');

$entregaModel = new PedidoEntrega();
$retiradaModel = new PedidoRetirada();


    $pedidosEntrega = $entregaModel->buscarPorProdutoEData($busca, $data, null);
    $pedidosRetirada = $retiradaModel->buscarPorProdutoEData($busca, $data, null);

    $todosPedidos = array_merge($pedidosEntrega, $pedidosRetirada);

    header('Content-Type: application/json');
    echo json_encode($todosPedidos);
    exit;
}

public function detalhesOperador() {
    $responsavel = $_GET['responsavel'] ?? '';
    $dataInicio = $_GET['data_inicio'] ?? '';
    $dataFim = $_GET['data_fim'] ?? '';

    if (!$responsavel || !$dataInicio || !$dataFim) {
        echo "Parâmetros inválidos.";
        return;
    }

    $pdo = Database::conectar();

    // Busca todos os registros do operador no intervalo informado
$stmt = $pdo->prepare("
    SELECT 
        rp.pedido_id,
        rp.tipo,
        rp.responsavel,
        hs_producao.data_hora AS data_producao,
        hs_pronto.data_hora AS data_pronto,
        COALESCE(p.numero_pedido, pr.numero_pedido) AS numero_pedido,
        COALESCE(p.produtos, pr.produtos) AS produtos
    FROM responsavel_producao rp
    LEFT JOIN historico_status hs_producao 
        ON hs_producao.pedido_id = rp.pedido_id AND hs_producao.status = 'Produção'
    LEFT JOIN historico_status hs_pronto 
        ON hs_pronto.pedido_id = rp.pedido_id AND hs_pronto.status = 'Pronto'
    LEFT JOIN pedidos_entrega p ON rp.tipo = 'entrega' AND p.id = rp.pedido_id
    LEFT JOIN pedidos_retirada pr ON rp.tipo = 'retirada' AND pr.id = rp.pedido_id
    WHERE rp.responsavel = :responsavel
      AND DATE(hs_producao.data_hora) BETWEEN :inicio AND :fim
    ORDER BY hs_producao.data_hora ASC
");


    $stmt->execute([
        ':responsavel' => $responsavel,
        ':inicio' => $dataInicio,
        ':fim' => $dataFim
    ]);

    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require __DIR__ . '/../views/operadores/detalhes_operador.php';
}


public function retornarPedidosPorStatus() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';
    require_once __DIR__ . '/../../config/database.php';

    header('Content-Type: application/json');

    $pdo = Database::conectar();
$entregaModel = new PedidoEntrega();
$retiradaModel = new PedidoRetirada();


    $dataHoje = date('Y-m-d');

    $entregas = $entregaModel->listarPorData($dataHoje);
    $retiradas = $retiradaModel->listarPorData($dataHoje);

    $todos = array_merge($entregas, $retiradas);

    $agrupados = [
        'Pendente' => [],
        'Produção' => [],
        'Pronto' => [],
    ];

    foreach ($todos as $pedido) {
        if (isset($agrupados[$pedido['status']])) {
            $agrupados[$pedido['status']][] = $pedido;
        }
    }

    echo json_encode($agrupados);
}






    // Aqui permanecem seus métodos de vendedores, produtos e cancelados como no backup original
}
