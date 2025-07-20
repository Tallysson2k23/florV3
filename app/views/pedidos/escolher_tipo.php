<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Escolher Tipo de Pedido - Flor de Cheiro</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .top-bar {
            position: absolute;
            top: 0;
            width: 100%;
            height: 90px;
            background-color: #111;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-img {
            height: 50px;
            object-fit: contain;
        }

        .container {
            margin-top: 100px;
            background: #f3f4f6;
            padding: 40px;
            border-radius: 0;
            box-shadow: none;
            text-align: center;
        }

        .container h3 {
            margin-bottom: 30px;
            font-size: 22px;
            color: #111;
        }

        .container a {
            display: block;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .container button {
            width: 220px;
            padding: 12px;
            font-size: 16px;
            border: none;
            background-color: #111;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .container button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
    </div>

    <div class="container">
        <h3>Escolha o tipo de pedido</h3>

        <a href="/florV3/public/index.php?rota=cadastrar-entrega">
            <button>Entrega</button>
        </a>

        <a href="/florV3/public/index.php?rota=cadastrar-retirada">
            <button>Retirada</button>
        </a>

        <a href="/florV3/public/index.php?rota=painel">
            <button>⬅ Voltar ao Painel</button>
        </a>
    </div>

</body>
</html>
