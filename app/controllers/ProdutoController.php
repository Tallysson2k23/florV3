<?php
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../../config/database.php';

class ProdutoController {

    public function cadastrarProduto() {
        require __DIR__ . '/../views/produtos/cadastrar_produto.php';
    }

    public function salvarProduto() {
        $nome = $_POST['nome'] ?? '';

        if ($nome) {
            $db = Database::conectar();
            $produtoModel = new Produto($db);
            $produtoModel->salvar($nome);
        }

        header('Location: /florV3/public/index.php?rota=lista-produtos');
        exit;
    }

    public function listaProdutos() {
        $db = Database::conectar();
        $produtoModel = new Produto($db);
        $produtos = $produtoModel->listarTodos();

        require __DIR__ . '/../views/produtos/lista_produtos.php';
    }
}
