<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verifica se é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: /florV2/public/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 20px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 15px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #333;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Cadastrar Usuário</h1>

<form action="/florV2/public/index.php?rota=salvar_usuario" method="POST">

    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" required>

    <label for="tipo">Tipo de Usuário:</label>
    <select name="tipo" id="tipo" required>
        <option value="funcionario">Funcionário</option>
        <option value="admin">Administrador</option>
    </select><br><br>

    <button type="submit">Cadastrar</button>
</form>

<a href="index.php?rota=listar-usuarios" class="btn btn-secondary">Voltar para Usuários</a>


</body>
</html>
