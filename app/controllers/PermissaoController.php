<?php
require_once __DIR__ . '/../models/Permissao.php';

class PermissaoController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            header('Location: /florV3/public/index.php?rota=acesso-negado');
            exit;
        }

        $permissaoModel = new Permissao();
        $permissoes = $permissaoModel->listarTodas();

        require __DIR__ . '/../views/permissoes/index.php';
    }

    public function salvar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            header('Location: /florV3/public/index.php?rota=acesso-negado');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $permissaoModel = new Permissao();
            $permissaoModel->salvarPermissoes($_POST['permissoes'] ?? []);
            $_SESSION['mensagem_sucesso'] = 'Permissões atualizadas com sucesso!';
            header('Location: /florV3/public/index.php?rota=permissoes');
            exit;
        } else {
            echo "Método não permitido.";
            exit;
        }
    }
}
