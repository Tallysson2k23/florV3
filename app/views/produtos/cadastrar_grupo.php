<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Grupo - Flor de Cheiro</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            padding: 0;
            margin: 0;
        }

        .top-bar {
            background-color: #111;
            color: white;
            font-size: 28px;
            text-align: center;
            padding: 15px 0;
            font-family: "Brush Script MT", cursive;
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
            color: #111;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn-salvar {
            background-color: #2ecc71;
            color: white;
            border: none;
        }

        .btn-salvar:hover {
            background-color: #27ae60;
        }

        .button-row {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .btn-voltar,
        .btn-listar {
            background-color: #555;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-voltar:hover,
        .btn-listar:hover {
            background-color: #333;
        }

        .msg-sucesso {
            background-color: #2ecc71;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
        }
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="container">

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
        <div class="msg-sucesso" id="msgSucesso">Grupo salvo com sucesso!</div>
        <script>
            const msg = document.getElementById('msgSucesso');
            msg.style.display = 'block';
            setTimeout(() => {
                msg.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>

    <h2>➕ Cadastrar Grupo de Produtos</h2>

    <form action="/florV3/public/index.php?rota=salvar-grupo" method="POST">
        <label>Nome do Grupo:
            <input type="text" name="nome_grupo" required>
        </label>

        <button type="submit" class="btn-salvar">Salvar Grupo</button>
    </form>

    <div class="button-row">
        <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">⬅ Voltar</a>
        <a href="/florV3/public/index.php?rota=lista-grupos" class="btn-listar">📋 Lista de Grupos</a>
    </div>
</div>

</body>
</html>
