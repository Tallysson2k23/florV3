<?php
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Grupo.php';

class ProdutoController {

    public function cadastrarProduto() {
        require __DIR__ . '/../views/produtos/cadastrar_produto.php';
    }

    public function salvarProduto() {
        $nome = $_POST['nome'] ?? '';
        $valor = $_POST['valor'] ?? '';
        $codigo = $_POST['codigo'] ?? '';
        $grupo_id = $_POST['grupo_id'] ?? null;
        $porcentagem = $_POST['porcentagem'] ?? null;

        if ($nome && $valor !== '' && $codigo) {
            $db = Database::conectar();
            $produtoModel = new Produto($db);
            $produtoModel->salvar($nome, $valor, $codigo, $grupo_id, $porcentagem);
        }

        header('Location: /florV3/public/index.php?rota=lista-produtos');
        exit;
    }

public function formularioCadastro() {
    $db = Database::conectar();
    $grupoModel = new Grupo($db);
    $grupos = $grupoModel->listarTodosAtivos(); // <- CORRETO AGORA

    require __DIR__ . '/../views/produtos/cadastrar_produto.php';
}

    public function listaProdutos() {
        $db = Database::conectar();
        $produtoModel = new Produto($db);
        $produtos = $produtoModel->listarTodos();

        require __DIR__ . '/../views/produtos/lista_produtos.php';
    }

    public function formularioCadastrarGrupo() {
        require __DIR__ . '/../views/produtos/cadastrar_grupo.php';
    }

public function salvarGrupo() {
    $db = Database::conectar();
    $grupoModel = new Grupo($db);

    $nomeGrupo = $_POST['nome_grupo'] ?? '';

    if (!empty($nomeGrupo)) {
        $grupoModel->salvar($nomeGrupo);
        header("Location: /florV3/public/index.php?rota=cadastrar-grupo&sucesso=1");
        exit;
    } else {
        echo "Nome do grupo é obrigatório.";
    }
}

public function listarGrupos() {
    $db = Database::conectar();
    $grupoModel = new Grupo($db);
    $grupos = $grupoModel->listarTodos();

    require __DIR__ . '/../views/produtos/lista_grupos.php';
}

public function inativarGrupo() {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $db = Database::conectar();
        $grupoModel = new Grupo($db);
        $grupoModel->inativar($id);
    }
    header('Location: /florV3/public/index.php?rota=lista-grupos');
    exit;
}

public function excluirGrupo() {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $db = Database::conectar();
        $grupoModel = new Grupo($db);
        $grupoModel->excluir($id);
    }
    header('Location: /florV3/public/index.php?rota=lista-grupos');
    exit;
}

public function ativarGrupo() {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $db = Database::conectar();
        $grupoModel = new Grupo($db);
        $grupoModel->ativar($id);
    }
    header('Location: /florV3/public/index.php?rota=lista-grupos');
    exit;
}






}
