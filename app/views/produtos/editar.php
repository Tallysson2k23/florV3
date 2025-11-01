<?php if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 20px;
            background-color: #f4f5f7;
        }
        h1 {
            color: #026aa7;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        button {
            background-color: #5aac44;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #4b9c3c;
        }
        .cancel-link {
            text-decoration: none;
            color: #026aa7;
            font-size: 14px;
            text-align: center;
            display: block;
            margin-top: 15px;
        }
        .cancel-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Editar Produto</h1>

<form method="POST" action="/florV2/public/index.php?rota=editar-produto">
    <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id']) ?>">

    <label>Nome:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

    <label>Preço:</label>
    <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>

    <label>Descrição:</label>
    <textarea name="descricao" required><?= htmlspecialchars($produto['descricao']) ?></textarea>

    <button type="submit">Salvar Alterações</button>
</form>

<a href="/florV2/public/index.php?rota=produtos" class="cancel-link">Cancelar</a>

</body>
</html>
