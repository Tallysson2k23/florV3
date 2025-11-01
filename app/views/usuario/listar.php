<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 20px;
            background-color: #f4f5f7;
        }
        h1 {
            color: #026aa7;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #5aac44;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .button {
            text-decoration: none;
            background-color: #026aa7;
            color: white;
            padding: 10px 16px;
            margin: 5px 6px 15px 0;
            border-radius: 6px;
            display: inline-block;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #045e94;
        }
        .actions a {
            color: #dc3545;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .actions a:hover {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>

<h1>Lista de Usuários</h1>

<a href="/florV2/public/index.php?rota=cadastrar_usuario" class="button">Cadastrar Novo Usuário</a>
<a href="/florV2/public/index.php?rota=painel" class="button">Voltar</a>
<a href="/florV2/public/index.php" class="button">Sair</a>


<br><br>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id']; ?></td>
                <td><?= htmlspecialchars($usuario['nome']); ?></td>
                <td><?= htmlspecialchars($usuario['email']); ?></td>
                <td><?= htmlspecialchars($usuario['tipo']); ?></td>
                <td class="actions">
                    <a href="index.php?rota=excluir-usuario&id=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
