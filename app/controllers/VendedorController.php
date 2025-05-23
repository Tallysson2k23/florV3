<?php
require_once __DIR__ . '/../models/Vendedor.php';
require_once __DIR__ . '/../config/database.php';

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
}
?>
