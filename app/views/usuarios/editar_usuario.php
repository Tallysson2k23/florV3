<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f4f5f7;
            padding: 20px;
        }
        h1 {
            color: #026aa7;
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #026aa7;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #045e94;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #026aa7;
        }
    </style>
</head>
<body>

<h1>Editar Usuário</h1>

<form action="index.php?rota=salvar-edicao-usuario" method="POST">
    <input type="hidden" name="id" value="<?= $usuario['id']; ?>">

    <label>Nome:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>

    <label>Tipo:</label>
    <select name="tipo" required>
        <option value="admin" <?= $usuario['tipo'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
        <option value="usuario" <?= $usuario['tipo'] === 'usuario' ? 'selected' : '' ?>>Usuário</option>
    </select>

    <label>Nova Senha (deixe em branco para não alterar):</label>
    <input type="password" name="senha">

    <button type="submit">Salvar Alterações</button>
</form>

<a href="index.php?rota=usuarios">Voltar à lista</a>

</body>
</html>
