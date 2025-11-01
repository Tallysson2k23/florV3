<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 20px;
            background: #f4f5f7;
        }
        h1 {
            color: #026aa7;
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
        a.button {
            text-decoration: none;
            background-color: #026aa7;
            color: white;
            padding: 10px 16px;
            margin: 5px 6px 15px 0;
            border-radius: 6px;
            display: inline-block;
            transition: background-color 0.2s;
        }
        a.button:hover {
            background-color: #045e94;
        }
        .actions .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            transition: background-color 0.2s;
        }
        .actions .editar {
            background-color: #007bff;
        }
        .actions .editar:hover {
            background-color: #0056b3;
        }
        .actions .excluir {
            background-color: #dc3545;
        }
        .actions .excluir:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h1>Lista de Produtos</h1>

<a href="/florV2/public/index.php?rota=criar_produto" class="button">Adicionar Produto</a>
<a href="/florV2/public/index.php?rota=logout" class="button">Sair</a>

<a href="/florV2/public/index.php?rota=painel" class="button">← Voltar</a>

<br><br>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= $produto['id']; ?></td>
                <td><?= htmlspecialchars($produto['nome']); ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></td>
                <td><?= htmlspecialchars($produto['descricao']); ?></td>
                <td class="actions">
                    <a href="/florV2/public/index.php?rota=editar-produto&id=<?= $produto['id']; ?>" class="btn editar">Editar</a>
                    <a href="/florV2/public/index.php?rota=deletar_produto&id=<?= $produto['id']; ?>" class="btn excluir" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
