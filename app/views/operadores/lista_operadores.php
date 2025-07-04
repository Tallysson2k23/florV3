<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Operadores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            margin: 0;
            padding: 40px;
        }
        .container {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-editar {
            background: #3498db;
            color: white;
        }
        .btn-excluir {
            background: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Lista de Operadores</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($operadores as $operador): ?>
        <tr>
            <td><?= htmlspecialchars($operador['id']) ?></td>
            <td><?= htmlspecialchars($operador['nome']) ?></td>
            <td>
                <a href="/florV3/public/index.php?rota=editar-operador&id=<?= $operador['id'] ?>">
                    <button class="btn-editar">Editar</button>
                </a>
                <a href="#" onclick="confirmarExclusao(<?= $operador['id'] ?>)">
                    <button class="btn-excluir">Excluir</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm("Tem certeza que deseja excluir este operador?")) {
        window.location.href = '/florV3/public/index.php?rota=excluir-operador&id=' + id;
    }
}
</script>

</body>
</html>
