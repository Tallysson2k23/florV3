<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Produção</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            margin: 0;
            padding: 40px;
        }
        .container {
            background: white;
            max-width: 700px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #111;
            color: white;
        }
        button {
            padding: 8px 16px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }
        button:hover {
            background: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Relatório de Produção</h2>

    <form method="GET" action="/florV3/public/index.php">
    <input type="hidden" name="rota" value="relatorio-operadores">
    <label>Data Inicial: </label>
    <input type="date" name="data_inicio" value="<?= htmlspecialchars($dataInicio ?? '') ?>">
    &nbsp;&nbsp;
    <label>Data Final: </label>
    <input type="date" name="data_fim" value="<?= htmlspecialchars($dataFim ?? '') ?>">
    &nbsp;&nbsp;
    <button type="submit">Buscar</button>
</form>




    <?php if (!empty($resultados)): ?>
        <table>
            <tr>
                <th>Operador</th>
                <th>Total de Pedidos em Produção</th>
            </tr>
            <?php foreach ($resultados as $linha): ?>
            <tr>
                <td><?= htmlspecialchars($linha['responsavel']) ?></td>
                <td><?= $linha['quantidade'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (isset($pesquisado)): ?>
        <p>Nenhum registro encontrado para o período informado.</p>
    <?php endif; ?>
</div>

</body>
</html>
