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
        $model->criar($_POST);
        header('Location: /florV3/public/index.php?rota=painel&sucesso=1');
        exit;
    }
}


  public function salvarRetirada() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model = new PedidoRetirada();
        $model->criar($_POST);
        header('Location: /florV3/public/index.php?rota=painel&sucesso=1');
        exit;
    }
}

public function historico() {

    $resultados = [];

    if (isset($_GET['busca']) && trim($_GET['busca']) !== '') {
        $busca = trim($_GET['busca']);

        $entregaModel = new PedidoEntrega();
        $retiradaModel = new PedidoRetirada();

        $resultadosEntrega = $entregaModel->buscar($busca);
        $resultadosRetirada = $retiradaModel->buscar($busca);


        // Unificar os resultados
        $resultados = array_merge($resultadosEntrega, $resultadosRetirada);
    }

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


}


