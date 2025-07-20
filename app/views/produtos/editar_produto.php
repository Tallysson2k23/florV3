<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form p {
            margin: 15px 0;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            padding: 12px 24px;
            background-color: #111;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin: 20px auto 0;
        }

        button:hover {
            background-color: #333;
        }

        .voltar {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #111;
            font-weight: bold;
        }

        .voltar:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Editar Produto</h2>
        <form method="POST" action="/florV3/public/index.php?rota=salvar-edicao-produto">
            <input type="hidden" name="id" value="<?= $produto['id'] ?>">

            <p>
                <label>Nome:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>">
            </p>

            <p>
                <label>Código:</label>
                <input type="text" name="codigo" value="<?= htmlspecialchars($produto['codigo']) ?>">
            </p>

            <p>
                <label>Valor (R$):</label>
                <input type="number" step="0.01" name="valor" value="<?= $produto['valor'] ?>">
            </p>

            <p>
                <label>Porcentagem (%):</label>
                <input type="number" step="0.01" name="porcentagem" 
                       value="<?= htmlspecialchars($produto['porcentagem'] ?? 0) ?>" min="0">
            </p>

            <button type="submit">💾 Salvar</button>
        </form>

        <a class="voltar" href="/florV3/public/index.php?rota=lista-produtos">⬅ Voltar</a>
    </div>
</body>
</html>
