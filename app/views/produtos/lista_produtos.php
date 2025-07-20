<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos - Flor de Cheiro</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background-color: #111;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-img {
            height: 50px;
            object-fit: contain;
            max-width: 100%;
            display: inline-block;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            margin: 2px;
            display: inline-block;
        }

        .btn-editar {
            background-color: #3498db;
        }

        .btn-inativar {
            background-color: #e74c3c;
        }

        .btn-ativar {
            background-color: #2ecc71;
        }

        .btn-voltar {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background-color: #555;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn-voltar:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="container">
    <h2>📋 Lista de Produtos</h2>

    <table>
        <tr>
            <th>Nome</th>
            <th>Código</th>
            <th>Valor (R$)</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td><?= htmlspecialchars($produto['codigo']) ?></td>
                <td><?= number_format($produto['valor'], 2, ',', '.') ?></td>
                <td><?= $produto['ativo'] ? ' Ativo' : '❌ Inativo' ?></td>
                <td>
                    <a href="/florV3/public/index.php?rota=editar-produto&id=<?= $produto['id'] ?>" class="btn btn-editar"> Editar</a>

                    <?php if ($produto['ativo']): ?>
                        <a href="/florV3/public/index.php?rota=inativar-produto&id=<?= $produto['id'] ?>" class="btn btn-inativar" onclick="return confirm('Deseja inativar este produto?')"> Inativar</a>
                    <?php else: ?>
                        <a href="/florV3/public/index.php?rota=ativar-produto&id=<?= $produto['id'] ?>" class="btn btn-ativar" onclick="return confirm('Deseja reativar este produto?')"> Ativar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">⬅ Voltar ao Painel</a>
</div>

</body>
</html>
