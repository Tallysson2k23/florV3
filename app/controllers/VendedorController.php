<?php
require_once __DIR__ . '/../models/Vendedor.php';
require_once __DIR__ . '/../../config/database.php';

class VendedorController
{
    /** ---------- Views ---------- */
    public function cadastrar()
    {
        require __DIR__ . '/../views/vendedores/cadastrar.php';
    }

    /** Cria vendedor */
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido';
            return;
        }

        $db  = Database::conectar();
        $mdl = new Vendedor($db);

        $nome     = trim($_POST['nome'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');

        if ($nome === '') {
            header('Location: /florV3/public/index.php?rota=cadastrar-vendedor&erro=nome_obrigatorio');
            exit;
        }

        $mdl->salvar($nome, $telefone);
        header('Location: /florV3/public/index.php?rota=lista-vendedores');
        exit;
    }

    /** Lista todos os vendedores */
    public function listar()
    {
        $db  = Database::conectar();
        $mdl = new Vendedor($db);
        $vendedores = $mdl->listarTodos();

        require __DIR__ . '/../views/vendedores/lista.php';
    }

    /** ---------- Ativar / Inativar ---------- */
    public function ativar()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $db  = Database::conectar();
            $mdl = new Vendedor($db);
            $mdl->ativar($id);
        }

        header('Location: /florV3/public/index.php?rota=lista-vendedores');
        exit;
    }

    public function inativar()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $db  = Database::conectar();
            $mdl = new Vendedor($db);
            $mdl->inativar($id);
        }

        header('Location: /florV3/public/index.php?rota=lista-vendedores');
        exit;
    }

    /** ---------- Atualizar status (AJAX individual) ---------- */
    public function atualizarStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido';
            return;
        }

        $id    = (int)($_POST['id'] ?? 0);
        $ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : null;

        if ($id <= 0 || $ativo === null) {
            http_response_code(400);
            echo 'Dados incompletos';
            return;
        }

        $db  = Database::conectar();
        $mdl = new Vendedor($db);
        $ativo ? $mdl->ativar($id) : $mdl->inativar($id);

        echo 'OK';
    }

    /** ---------- Salvar edições em lote (nome, código e status) ---------- */
    public function salvarStatusVendedores()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido';
            return;
        }

        $db  = Database::conectar();
        $mdl = new Vendedor($db);

        $nomes   = $_POST['nome']   ?? [];
        $codigos = $_POST['codigo'] ?? [];
        $ativos  = $_POST['ativo']  ?? [];

        foreach ($nomes as $id => $nome) {
            $id     = (int)$id;
            $nome   = trim($nome ?? '');
            $codigo = trim($codigos[$id] ?? '');
            $ativo  = isset($ativos[$id]) ? 1 : 0;

            

            if ($id <= 0 || $nome === '') continue;
            

            // Atualiza os campos
            $mdl->atualizarCamposBasicos($id, $nome, $codigo, $ativo);
        }

        header('Location: /florV3/public/index.php?rota=lista-vendedores&status=ok');
        exit;
    }

    /** ---------- Excluir vendedor ---------- */
    public function excluirVendedor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido';
            return;
        }

        $id = (int)($_POST['excluir_id'] ?? 0);
        if ($id > 0) {
            $db  = Database::conectar();
            $mdl = new Vendedor($db);
            $mdl->excluir($id);
        }

        header('Location: /florV3/public/index.php?rota=lista-vendedores&status=ok');
        exit;
    }

    /** ---------- Método legado (mantido se ainda for usado em outro lugar) ---------- */
    public function salvarStatusEmLote()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Método não permitido';
            return;
        }

        $db  = Database::conectar();
        $mdl = new Vendedor($db);

        $ativos = array_map('intval', array_keys($_POST['ativo'] ?? []));
        $todos  = $mdl->listarTodos();

        foreach ($todos as $v) {
            $id = (int)$v['id'];
            in_array($id, $ativos, true)
                ? $mdl->ativar($id)
                : $mdl->inativar($id);
        }

        header('Location: /florV3/public/index.php?rota=lista-vendedores&status=ok');
        exit;
    }
}
