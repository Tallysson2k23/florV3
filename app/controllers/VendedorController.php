<?php
require_once __DIR__ . '/../models/Vendedor.php';
require_once __DIR__ . '/../../config/database.php';

class VendedorController {
    public function cadastrar() {
        require __DIR__ . '/../views/vendedores/cadastrar.php';
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();
            $vendedorModel = new Vendedor($conn);
            $vendedorModel->salvar($_POST['nome'], $_POST['telefone']);
            header('Location: /florV3/public/index.php?rota=lista-vendedores');
        }
    }

    public function listar() {
        $db = new Database();
        $conn = $db->getConnection();
        $vendedorModel = new Vendedor($conn);
        $vendedores = $vendedorModel->listarTodos();
        require __DIR__ . '/../views/vendedores/lista.php';
    }

public function ativar() {
    if (isset($_GET['id'])) {
        $db = Database::conectar();
        $vendedorModel = new Vendedor($db);
        $vendedorModel->ativar($_GET['id']);
    }
    header('Location: /florV3/public/index.php?rota=lista-vendedores');
    exit;
}

public function inativar() {
    if (isset($_GET['id'])) {
        $db = Database::conectar();
        $vendedorModel = new Vendedor($db);
        $vendedorModel->inativar($_GET['id']);
    }
    header('Location: /florV3/public/index.php?rota=lista-vendedores');
    exit;
}

public function atualizarStatus() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $ativo = $_POST['ativo'] ?? null;

        if ($id !== null && $ativo !== null) {
            $db = Database::conectar();
            $model = new Vendedor($db);
            if ($ativo) {
                $model->ativar($id);
            } else {
                $model->inativar($id);
            }
            echo "OK";
        } else {
            http_response_code(400);
            echo "Dados incompletos";
        }
    } else {
        http_response_code(405);
        echo "Método não permitido";
    }
}

public function salvarStatusEmLote() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = Database::conectar();
        $model = new Vendedor($db);

        // Pegamos todos os IDs marcados como ativos
        $ativos = array_map('intval', array_keys($_POST['ativo'] ?? []));

        // Pegamos todos os vendedores
        $todos = $model->listarTodos();

        foreach ($todos as $vendedor) {
            $id = (int) $vendedor['id'];
            if (in_array($id, $ativos)) {
                $model->ativar($id);
            } else {
                $model->inativar($id);
            }
        }

       header('Location: /florV3/public/index.php?rota=lista-vendedores&status=ok');
 exit;
    }
}













}
?>
