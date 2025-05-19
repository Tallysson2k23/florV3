<?php
        require_once __DIR__ . '/../models/PedidoEntrega.php';
        require_once __DIR__ . '/../models/PedidoRetirada.php';
class PedidoController {
    public function cadastrar() {
        require_once __DIR__ . '/../views/pedidos/cadastrar.php';


    }

    public function salvar() {
        // A lógica de salvar virá depois
    }

    public function formEntrega() {
        require __DIR__ . '/../views/pedidos/cadastrar_entrega.php';
    }

    public function formRetirada() {
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
    $porPagina = 10;

    // Buscar dados combinados
    $resultadosEntrega = $entregaModel->buscar($busca);
    $resultadosRetirada = $retiradaModel->buscar($busca);
    $todos = array_merge($resultadosEntrega, $resultadosRetirada);

    // Ordenar por data decrescente
    usort($todos, fn($a, $b) => strtotime($b['data_abertura']) <=> strtotime($a['data_abertura']));

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
    $tipo = $_GET['tipo'] ?? null;
    $dados = null;

    if ($id && $tipo === 'entrega') {
        $model = new PedidoEntrega();
        $dados = $model->buscarPorId($id);
    } elseif ($id && $tipo === 'retirada') {
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

    $pedidosEntrega = $entregaModel->listarTodos();
    $pedidosRetirada = $retiradaModel->listarTodos();

    // Unifica as listas
    $todosPedidos = array_merge($pedidosEntrega, $pedidosRetirada);

    // Ordena do mais recente para o mais antigo
    usort($todosPedidos, function($a, $b) {
        return strtotime($b['data_abertura']) <=> strtotime($a['data_abertura']);
    });

    require __DIR__ . '/../views/pedidos/acompanhamento.php';
}

public function atualizarStatus() {
    require_once __DIR__ . '/../models/PedidoEntrega.php';
    require_once __DIR__ . '/../models/PedidoRetirada.php';

    $id = $_POST['id'] ?? null;
    $tipo = $_POST['tipo'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($id && $tipo && $status) {
        if ($tipo === 'entrega') {
            $model = new PedidoEntrega();
        } else {
            $model = new PedidoRetirada();
        }

        $model->atualizarStatus($id, $status);
    }

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



}


