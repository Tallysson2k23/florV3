<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Usuários - Flor de Cheiro</title>
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
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 15px;
        }

        th {
            background-color: #e4e4e4;
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .botoes {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            text-align: center;
            color: white;
        }

        .btn-voltar { background-color: #555; }
        .btn-voltar:hover { background-color: #333; }

        .btn-novo { background-color: #2ecc71; }
        .btn-novo:hover { background-color: #27ae60; }

        .btn-editar {
            background-color: #3498db;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 13px;
        }

        .btn-editar:hover {
            background-color: #2a7ec4;
        }

        .btn-excluir {
            background-color: #e74c3c;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 13px;
        }

        .btn-excluir:hover {
            background-color: #c0392b;
        }

        td.acao {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="container">
    <h2>👥 Lista de Usuários Cadastrados</h2>

    <table>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Inativo</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= ucfirst($usuario['tipo']) ?></td>
                <td>
                    <input type="checkbox"
                           <?= isset($usuario['ativo']) && !$usuario['ativo'] ? 'checked' : '' ?>
                           onchange="atualizarStatus(<?= isset($usuario['id']) ? (int)$usuario['id'] : 0 ?>, this.checked)">
                </td>
                <td class="acao">
                    <a href="index.php?rota=editar-usuario&id=<?= $usuario['id'] ?>" class="btn-editar">Editar</a>
                    <a href="index.php?rota=excluir-usuario&id=<?= $usuario['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="botoes">
        <a href="/florV3/public/index.php?rota=painel" class="btn btn-voltar">← Voltar ao Painel</a>
        <a href="/florV3/public/index.php?rota=novo-usuario" class="btn btn-novo">➕ Cadastrar Novo Usuário</a>
    </div>
</div>

<script>
function atualizarStatus(usuarioId, inativo) {
    const valorAtivo = inativo ? 0 : 1;

    fetch('/florV3/public/index.php?rota=atualizar-status-usuario', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${encodeURIComponent(usuarioId)}&ativo=${valorAtivo}`
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
    });
}
</script>

</body>
</html>
