<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Retiradas - Flor de Cheiro</title>
    <style>
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

        h2 {
            text-align: center;
            margin: 30px 0 20px;
            font-size: 24px;
            color: #111;
        }

        table {
            margin: 0 auto;
            width: 90%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        th {
            background: #eee;
        }

        .botoes {
            text-align: center;
            margin: 30px 0;
        }

        .botoes a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background: #111;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .botoes a.voltar {
            background: white;
            color: #111;
            border: 1px solid #111;
        }

        .botoes a.voltar:hover {
            background: #111;
            color: white;
        }

        .paginacao {
            text-align: center;
            margin: 20px 0;
        }

        .paginacao a {
            margin: 0 4px;
            text-decoration: none;
            font-weight: bold;
            color: #111;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

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
                <td><?= htmlspecialchars($pedido['nome'] ?? ($pedido['remetente'] ?? '')) ?></td>
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
    <a href="/florV3/public/index.php?rota=historico">Ver Histórico</a>
    <a href="/florV3/public/index.php?rota=painel" class="voltar">← Voltar</a>
</div>

</body>
</html>
