<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário - Flor de Cheiro</title>
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

        input, select {
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
        }

        .btn-voltar:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="container">
    <h2>👤 Cadastrar Usuário</h2>

    <form method="post" action="/florV3/public/index.php?rota=salvar-usuario">
        <label>Nome:
            <input type="text" name="nome" required>
        </label>

        <label>Email:
            <input type="email" name="email" required>
        </label>

        <label>Senha:
            <input type="password" name="senha" required>
        </label>

        <label>Tipo:
            <select name="tipo">
                <option value="admin">Admin</option>
                <option value="colaborador">Colaborador</option>
            </select>
        </label>

        <button type="submit" class="btn-enviar">Cadastrar</button>
    </form>

    <a href="/florV3/public/index.php?rota=usuarios" class="btn-voltar">⬅ Voltar</a>
</div>

</body>
</html>
