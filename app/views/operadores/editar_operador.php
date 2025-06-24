<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Operador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            padding: 40px;
        }
        .container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Operador</h2>

    <form method="POST" action="/florV3/public/index.php?rota=atualizar-operador">
        <input type="hidden" name="id" value="<?= $operador['id'] ?>">
        <label>Nome do Operador:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($operador['nome']) ?>" required>
        <button type="submit">Salvar</button>
    </form>
</div>

</body>
</html>
