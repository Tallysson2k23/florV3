<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Vendedores - Flor de Cheiro</title>
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
            height: 60px;
            max-width: 100%;
            object-fit: contain;
            display: inline-block;
        }

        h2 {
            text-align: center;
            margin: 30px 0 20px;
            color: #111;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 15px;
        }

        th {
            background: #111;
            color: white;
        }

        a {
            display: block;
            text-align: center;
            margin: 25px auto;
            text-decoration: none;
            color: #111;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
    </div>

    <h2>Lista de Vendedores</h2>

    <?php if (!empty($vendedores)): ?>
    <table>
        <tr>
            <th>Nome</th>
            <th>Código</th>
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
