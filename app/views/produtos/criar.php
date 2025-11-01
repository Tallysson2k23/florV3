<?php if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Produto - Flor de Cheiro</title>
    <style>
        body {
            background-color: #f4f5f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            justify-content: center;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 440px;
            width: 100%;
        }

        h1 {
            text-align: center;
            font-weight: 500;
            color: #026aa7;
            margin-bottom: 25px;
            font-size: 24px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 6px;
            color: #444;
        }

        input[type="text"],
        textarea {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        button {
            background-color: #5aac44;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #519839;
        }

        .links {
            margin-top: 20px;
            text-align: center;
        }

        .links a {
            color: #026aa7;
            text-decoration: none;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Criar Produto</h1>

    <form method="POST" action="/florV2/public/index.php?rota=criar_produto">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="preco">Preço:</label>
        <input type="text" id="preco" name="preco" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"></textarea>

        <button type="submit">Salvar Produto</button>
    </form>

    <div class="links">
        <a href="/florV2/public/index.php?rota=painel">← Voltar</a> |
        <a href="/florV2/public/index.php?rota=produtos">Lista de Produtos</a>
    </div>
</div>

</body>
</html>
