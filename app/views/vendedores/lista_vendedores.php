<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Vendedores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #111;
            color: white;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>Lista de Vendedores</h2>

<?php if (!empty($vendedores)): ?>
<table>
    <tr>
        <th>Nome</th>
        <th>Codigo</th>
    </tr>
    <?php foreach ($vendedores as $vendedor): ?>
    <tr>
        <td><?= htmlspecialchars($vendedor['nome']) ?></td>
        <td><?= htmlspecialchars($vendedor['codigo']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p style="text-align:center;">Nenhum vendedor cadastrado.</p>
<?php endif; ?>

<a href="/florV3/public/index.php?rota=painel">← Voltar para o Painel</a>
</body>
</html>
