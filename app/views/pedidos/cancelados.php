<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos Cancelados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            margin: 0 auto;
            width: 90%;
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
            display: block;
            margin: 30px auto;
            text-align: center;
        }
    </style>
</head>
<body>
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
</body>
</html>
