<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Grupos</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .top-bar { background-color: #111; color: white; text-align: center; padding: 15px; font-size: 28px; font-family: "Brush Script MT", cursive; }
        .container { max-width: 700px; margin: 30px auto; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ccc; }
        .btn { padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; color: white; }
        .btn-inativar { background-color: #e67e22; }
        .btn-excluir { background-color: #e74c3c; }
        .btn-voltar { display: inline-block; margin-top: 20px; background-color: #555; color: white; padding: 10px; border-radius: 8px; text-decoration: none; }
        .btn-voltar:hover { background-color: #333; }
    </style>
</head>
<body>

<div class="top-bar">Grupos Cadastrados</div>
<div class="container">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($grupos as $grupo): ?>
            <tr>
                <td><?= htmlspecialchars($grupo['nome']) ?></td>
                <td><?= $grupo['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                <td>
<?php if ($grupo['ativo']): ?>
    <a class="btn btn-inativar" href="/florV3/public/index.php?rota=inativar-grupo&id=<?= $grupo['id'] ?>">Inativar</a>
<?php else: ?>
    <a class="btn btn-inativar" style="background-color:#2ecc71;" href="/florV3/public/index.php?rota=ativar-grupo&id=<?= $grupo['id'] ?>">Ativar</a>
<?php endif; ?>

                    <a class="btn btn-excluir" href="/florV3/public/index.php?rota=excluir-grupo&id=<?= $grupo['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/florV3/public/index.php?rota=cadastrar-grupo" class="btn-voltar">⬅ Voltar</a>
</div>

</body>
</html>
