<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    public function formularioCadastro() {
        require __DIR__ . '/../views/usuarios/cadastrar.php';
    }

    public function salvarCadastro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            $usuario->criar(
                $_POST['nome'],
                $_POST['email'],
                $_POST['senha'],
                $_POST['tipo']
            );
            header('Location: /florV3/public/index.php?rota=painel&sucesso=1');
            exit;
        }
    }

    public function login() {
        require __DIR__ . '/../views/usuarios/login.php';
    }

public function autenticar() {
    session_start();

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->autenticar($_POST['email'], $_POST['senha']);

    if ($usuario) {
        if (!$usuario['ativo']) {
            echo "<script>alert('Usuário inativo. Contate o administrador.'); window.location.href='/florV3/public/index.php?rota=login';</script>";
            exit;
        }

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        header('Location: /florV3/public/index.php?rota=painel');
        exit;
    } else {
        header('Location: /florV3/public/index.php?rota=login&erro=1');
        exit;
    }
}



    public function logout() {
        session_start();
        session_destroy();
        header('Location: /florV3/public/index.php?rota=login');
    }

    public function listarUsuarios() {
    $usuario = new Usuario();
    $usuarios = $usuario->listarTodos();
    require __DIR__ . '/../views/usuarios/listar.php';
}

public function atualizarStatusUsuario() {
    session_start();

    if ($_SESSION['usuario_tipo'] !== 'admin') {
        echo "Acesso negado.";
        return;
    }

    $id = $_POST['id'] ?? null;
    $ativo = $_POST['ativo'] ?? null;

    if ($id === null || $ativo === null) {
        echo "Dados inválidos.";
        return;
    }

    require_once __DIR__ . '/../models/Usuario.php';

    $usuarioModel = new Usuario();
    $usuarioModel->atualizarAtivo($id, $ativo);
    echo "Status atualizado com sucesso!";
}


}
