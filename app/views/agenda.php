<?php
require_once __DIR__ . '/../../config/database.php';

$dataSelecionada = $_GET['data'] ?? date('Y-m-d');

$db = new Database();
$pdo = $db->getConnection();

$stmt = $pdo->prepare("
    SELECT 
        numero_pedido, 
        remetente AS nome, 
        produtos AS produto, 
        status, 
        hora, 
        data_abertura,
        'ENTREGA' AS tipo
    FROM pedidos_entrega 
    WHERE data_abertura = ?

    UNION ALL

    SELECT 
        numero_pedido, 
        nome AS nome, 
        produtos AS produto, 
        status, 
        hora, 
        data_abertura,
        'RETIRADA' AS tipo
    FROM pedidos_retirada 
    WHERE data_abertura = ?
");

$stmt->execute([$dataSelecionada, $dataSelecionada]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agenda - Flor de Cheiro</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background-color: #111;
            color: white;
            text-align: center;
            padding: 15px;
            font-family: "Brush Script MT", cursive;
            font-size: 28px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
        }

        .date-picker {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #eee;
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
    <h2>📅 Agenda de Pedidos</h2>

    <div class="date-picker">
        <form method="get" action="/florV3/public/index.php">
    <input type="hidden" name="rota" value="agenda">

    <label for="data">Escolher data:</label>
    <input type="date" name="data" id="data" value="<?= $dataSelecionada ?>" onchange="this.form.submit()">
</form>

    </div>

    <?php if (count($pedidos) > 0): ?>
        <table>
            <tr>
    <th>Nº Pedido</th>
    <th>Nome</th>
    <th>Produto</th>
    <th>Status</th>
    <th>Hora</th>
    <th>Tipo</th>
</tr>

            <?php foreach ($pedidos as $p): ?>
                <tr>
    <td>#<?= $p['numero_pedido'] ?></td>
    <td><?= htmlspecialchars($p['nome']) ?></td>
    <td><?= htmlspecialchars($p['produto']) ?></td>
    <td><?= htmlspecialchars($p['status']) ?></td>
    <td><?= substr($p['hora'], 0, 5) ?></td>
    <td><?= $p['tipo'] ?></td>
</tr>

            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">Nenhum pedido encontrado para esta data.</p>
    <?php endif; ?>

    <div style="text-align: center;">
        <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">⬅ Voltar</a>
    </div>
</div>

</body>
</html>
