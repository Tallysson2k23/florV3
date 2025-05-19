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
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo']; // ← ESTA LINHA É ESSENCIAL

        header('Location: /florV3/public/index.php?rota=painel');
    } else {
        header('Location: /florV3/public/index.php?rota=login&erro=1');
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


}
