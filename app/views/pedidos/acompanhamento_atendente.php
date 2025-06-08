<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acompanhamento do Atendente - Flor de Cheiro</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }
        h1 { color: #111; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>

<h1>Acompanhamento do Atendente (Pronto e Entregue)</h1>

<h2>Pedidos de Entrega</h2>
<table>
    <tr>
        <th>Nº Pedido</th>
        <th>Cliente</th>
        <th>Produto</th>
        <th>Status</th>
    </tr>
    <?php foreach ($entregas as $pedido): ?>
    <tr>
        <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
        <td><?= htmlspecialchars($pedido['destinatario']) ?></td>
        <td><?= htmlspecialchars($pedido['produtos']) ?></td>
        <td><?= htmlspecialchars($pedido['status']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Pedidos de Retirada</h2>
<table>
    <tr>
        <th>Nº Pedido</th>
        <th>Cliente</th>
        <th>Produto</th>
        <th>Status</th>
    </tr>
    <?php foreach ($retiradas as $pedido): ?>
    <tr>
        <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
        <td><?= htmlspecialchars($pedido['nome']) ?></td>
        <td><?= htmlspecialchars($pedido['produtos']) ?></td>
        <td><?= htmlspecialchars($pedido['status']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
