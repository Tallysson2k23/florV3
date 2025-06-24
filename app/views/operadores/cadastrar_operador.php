<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Operador</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #111;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        button {
            background: #111;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            background: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Operador</h2>
        <form method="POST" action="/florV3/public/index.php?rota=salvar-operador">
            <input type="text" name="nome" placeholder="Nome do Operador" required>
            <button type="submit">Salvar</button>
        </form>
    </div>
    <br><br>

<div style="text-align:center;">
    <a href="/florV3/public/index.php?rota=lista-operadores">
        <button style="padding: 10px 20px; font-size: 16px;">📄 Ver Lista de Operadores</button>
    </a>
</div>

</body>
</html>
