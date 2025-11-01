<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../config/database.php';

class UsuarioController
{
    /** @var PDO */
    private $db;

    public function __construct()
    {
        // cria conexão com o banco
        $this->db = Database::conectar();
    }

    /** ✅ Listar todos os usuários */
    public function listarUsuarios()
    {
        $usuarioModel = new Usuario($this->db);
        $usuarios = $usuarioModel->listarTodos();

        require __DIR__ . '/../views/usuarios/listar.php';
    }

    /** ✅ Formulário de cadastro */
    public function formularioCadastro()
    {
        require __DIR__ . '/../views/usuarios/cadastrar.php';
    }

    /** ✅ Salvar novo usuário */
    public function salvarCadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel = new Usuario($this->db);
            $usuarioModel->criar(
                $_POST['nome'],
                $_POST['email'],
                $_POST['senha'],
                $_POST['tipo']
            );

            header('Location: /florV3/public/index.php?rota=painel&sucesso=1');
            exit;
        }
    }

    /** ✅ Tela de login */
    public function login()
    {
        require __DIR__ . '/../views/usuarios/login.php';
    }

    /** ✅ Autenticação */
    public function autenticar()
    {
        session_start();

        $usuarioModel = new Usuario($this->db);
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

    /** ✅ Logout */
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /florV3/public/index.php?rota=login');
        exit;
    }

    /** ✅ Atualizar status (ativo/inativo) */
    public function atualizarStatusUsuario()
    {
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

        $usuarioModel = new Usuario($this->db);
        $usuarioModel->atualizarAtivo($id, $ativo);

        echo "Status atualizado com sucesso!";
    }

    /** ✅ Exibir formulário de edição */
    public function editarUsuario()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            echo "ID inválido.";
            return;
        }

        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->buscarPorId($id);

        if (!$usuario) {
            echo "Usuário não encontrado.";
            return;
        }

        require __DIR__ . '/../views/usuarios/editar_usuario.php';
    }

    /** ✅ Salvar alterações de edição */
    public function salvarEdicaoUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método não permitido.";
            return;
        }

        $id    = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tipo  = trim($_POST['tipo'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if ($id <= 0 || $nome === '' || $email === '' || $tipo === '') {
            echo "Campos obrigatórios não preenchidos.";
            return;
        }

        $usuarioModel = new Usuario($this->db);
        $ok = $usuarioModel->atualizarUsuario($id, $nome, $email, $tipo, $senha);

        header('Location: index.php?rota=usuarios' . ($ok ? '&status=ok' : '&status=erro'));
        exit;
    }
}
