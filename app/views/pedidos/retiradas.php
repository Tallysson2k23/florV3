<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Retiradas - Flor de Cheiro</title>
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
        }
        th {
            background: #eee;
        }
        .botoes {
            text-align: center;
            margin-top: 20px;
        }
        .botoes a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background: #111;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .botoes a.voltar {
            background: white;
            color: black;
            border: 1px solid #333;
        }
        .paginacao {
            text-align: center;
            margin: 20px;
        }
        .paginacao a {
            margin: 0 4px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Pedidos Entregues</h2>

<?php if (isset($pedidosPaginados) && count($pedidosPaginados) > 0): ?>

    <table>
        <tr>
            <th>Tipo</th>
            <th>Nº Pedido</th>
            <th>Nome</th>
            <th>Produto</th>
            <th>Data</th>
        </tr>
        <?php foreach ($pedidosPaginados as $pedido): ?>
            <tr>
                <td><?= htmlspecialchars($pedido['tipo']) ?></td>
                <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
                <td><?= htmlspecialchars($pedido['nome'] ?? $pedido['remetente']) ?></td>
                <td><?= htmlspecialchars($pedido['produto'] ?? $pedido['produtos']) ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($pedido['data_abertura']))) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="paginacao">
        <?php
        $totalPaginas = ceil($total / 10);
        for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?rota=retiradas&pagina=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

<?php else: ?>
    <p style="text-align:center;">Nenhuma retirada registrada com status "Entregue".</p>
<?php endif; ?>

<div class="botoes">
    <a href="/florV3/public/index.php?rota=acompanhamento">Ver todos os pedidos</a>
    <a href="/florV3/public/index.php?rota=painel" class="voltar">← Voltar</a>
</div>

</body>
</html>
