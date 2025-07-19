<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
</head>
<body>
    <h2>✏️ Editar Produto</h2>
    <form method="POST" action="/florV3/public/index.php?rota=salvar-edicao-produto">
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
        <p>Nome: <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>"></p>
        <p>Código: <input type="text" name="codigo" value="<?= htmlspecialchars($produto['codigo']) ?>"></p>
        <p>Valor: <input type="number" step="0.01" name="valor" value="<?= $produto['valor'] ?>"></p>
        <button type="submit">💾 Salvar</button>
    </form>
    <a href="/florV3/public/index.php?rota=lista-produtos">⬅ Voltar</a>
</body>
</html>
