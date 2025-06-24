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
$dados['enviar_para'] = $_POST['enviar_para'] ?? null;

// Aqui aplicamos a lógica nova:
if ($dados['enviar_para'] === 'pronta_entrega') {
    $dados['status'] = 'Pronto';
} else {
    $dados['status'] = 'Pendente';
}


            $model = new PedidoEntrega();
            $id = $model->criar($dados);

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
$dados['enviar_para'] = $_POST['enviar_para'] ?? null;

// Aqui aplicamos a lógica nova:
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

        $busca = $_GET['busca'] ?? '';
        $dataSelecionada = $_GET['data'] ?? date('Y-m-d');
        $statusFiltro = ['Pendente', 'Produção', 'Pronto'];

        // Aqui aplicamos a lógica nova do seu controller atual
        $pedidosEntrega = $entregaModel->buscarPorStatusEDataEEnvio($statusFiltro, $dataSelecionada, 'producao', $busca);
        $pedidosRetirada = $retiradaModel->buscarPorStatusEDataEEnvio($statusFiltro, $dataSelecionada, 'producao', $busca);

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

        $entregas = $pedidoEntrega->buscarPorStatusEData(['Pronto', 'Entregue'], $data);
        $retiradas = $pedidoRetirada->buscarPorStatusEData(['Pronto', 'Entregue'], $data);

        $entregas = is_array($entregas) ? $entregas : [];
        $retiradas = is_array($retiradas) ? $retiradas : [];

        $todosPedidos = array_merge($entregas, $retiradas);

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

    $model = ($tipo === 'entrega') ? new PedidoEntrega() : new PedidoRetirada();
    $model->atualizarStatus($id, $status, $mensagem);

    // 🚩 AQUI FAZEMOS O REGISTRO NO BANCO DE RESPONSAVEL
    if ($status === 'Produção' && $responsavel) {
        require_once __DIR__ . '/../models/ResponsavelProducao.php';
        $responsavelModel = new ResponsavelProducao();
        $responsavelModel->criar($id, $tipo, $responsavel);
    }

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

    // Aqui permanecem seus métodos de vendedores, produtos e cancelados como no backup original
}
