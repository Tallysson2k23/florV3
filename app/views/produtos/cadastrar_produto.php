<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - Flor de Cheiro</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background-color: #111;
            color: white;
            font-family: "Brush Script MT", cursive;
            font-size: 28px;
            text-align: center;
            padding: 15px 0;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 500;
            color: #333;
        }

        input {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            width: 100%;
        }

        button {
            padding: 12px;
            font-size: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-enviar {
            background-color: #2ecc71;
            color: white;
        }

        .btn-enviar:hover {
            background-color: #27ae60;
        }

        .btn-voltar {
            margin-top: 15px;
            background-color: #555;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            padding: 10px;
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
    <h2>📦 Cadastrar Produto</h2>

    <form action="/florV3/public/index.php?rota=salvar-produto" method="POST">
        <label>Nome do Produto:
            <input type="text" name="nome" required>
        </label>

        <label>Valor (R$):
            <input type="number" name="valor" step="0.01" required>
        </label>

        <label>Código do Produto:
            <input type="text" name="codigo" maxlength="20" required>
        </label>

        <button type="submit" class="btn-enviar">Salvar Produto</button>
    </form>

    <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">⬅ Voltar</a>
</div>

</body>
</html>
