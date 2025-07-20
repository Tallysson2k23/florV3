<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Cancelados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            width: 100%;
            background-color: #111;
            text-align: center;
            padding: 15px 0;
        }

        .logo-img {
            height: 52px;
            object-fit: contain;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .voltar {
            text-align: center;
            margin: 30px 0;
        }

        .voltar a {
            color: #000;
            text-decoration: none;
            font-weight: bold;
        }

        .voltar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="container">
    <h2>Pedidos Cancelados</h2>

    <?php if (count($cancelados) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Data</th>
            </tr>
            <?php foreach ($cancelados as $pedido): ?>
                <tr>
                    <td><?= htmlspecialchars($pedido['id']) ?></td>
                    <td>
                        <a href="/florV3/public/index.php?rota=detalhes&id=<?= $pedido['id'] ?>&tipo=<?= strtolower($pedido['tipo']) ?>">
                            <?= htmlspecialchars($pedido['nome'] ?? $pedido['remetente']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($pedido['tipo']) ?></td>
                    <td><?= htmlspecialchars($pedido['data_abertura']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Nenhum pedido cancelado até o momento.</p>
    <?php endif; ?>

    <div class="voltar">
        <a href="/florV3/public/index.php?rota=painel">← Voltar ao Painel</a>
    </div>
</div>

</body>
</html>
