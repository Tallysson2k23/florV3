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
            color: white;
            font-family: "Brush Script MT", cursive;
            font-size: 28px;
            text-align: center;
            padding: 15px 0;
        }

        .container {
            max-width: 800px;
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
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
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

<div class="top-bar">Flor de Cheiro</div>

<div class="container">
    <h2>📋 Lista de Produtos</h2>

    <table>
        <tr>
            <th>Nome</th>
            <th>Código</th>
            <th>Valor (R$)</th>
        </tr>

        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td><?= htmlspecialchars($produto['codigo']) ?></td>
                <td><?= number_format($produto['valor'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">⬅ Voltar ao Painel</a>
</div>

</body>
</html>
