<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Operador</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
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

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            margin: 40px auto;
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

        .link-voltar {
            text-align: center;
            margin-top: 20px;
        }

        .link-voltar a button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="container">
    <h2>Cadastro de Operador</h2>
    <form method="POST" action="/florV3/public/index.php?rota=salvar-operador">
        <input type="text" name="nome" placeholder="Nome do Operador" required>
        <button type="submit">Salvar</button>
    </form>
</div>

<div class="link-voltar">
    <a href="/florV3/public/index.php?rota=lista-operadores">
        <button>📄 Ver Lista de Operadores</button>
    </a>
</div>

</body>
</html>
