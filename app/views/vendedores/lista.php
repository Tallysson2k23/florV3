<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Vendedores - Flor de Cheiro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            width: 100%;
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

        h2 {
            text-align: center;
            margin: 30px 0 20px;
            color: #111;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 15px;
        }

        th {
            background: #111;
            color: white;
        }

        a {
            display: block;
            text-align: center;
            margin: 25px auto;
            text-decoration: none;
            color: #111;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<?php if (isset($_GET['status']) && $_GET['status'] === 'ok'): ?>
<div id="notificacao" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #2ecc71;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.15);
    font-weight: bold;
    z-index: 9999;
">
    ✅ Alterações salvas com sucesso!
</div>
<script>
    setTimeout(() => {
        const notif = document.getElementById('notificacao');
        if (notif) notif.remove();

        // Remove o parâmetro 'status=ok' da URL sem recarregar
        const url = new URL(window.location);
        url.searchParams.delete('status');
        window.history.replaceState({}, document.title, url);
    }, 3000);
</script>
<?php endif; ?>



<body>

    <div class="top-bar">
        <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
    </div>

    <h2>Lista de Vendedores</h2>

    <?php if (!empty($vendedores)): ?>
<form method="post" action="/florV3/public/index.php?rota=salvar-status-vendedores">
<table>
    <tr>
        <th>Nome</th>
        <th>Código</th>
        <th>Ativo</th>
    </tr>
    <?php foreach ($vendedores as $vendedor): ?>
    <tr>
        <td><?= htmlspecialchars($vendedor['nome']) ?></td>
        <td><?= htmlspecialchars($vendedor['codigo']) ?></td>
        <td style="text-align: center;">
            <input type="checkbox"
                   name="ativo[<?= $vendedor['id'] ?>]"
                   value="1"
                   <?= $vendedor['ativo'] ? 'checked' : '' ?>>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<div style="text-align: center; margin-top: 20px;">
    <button type="submit" style="padding: 10px 20px; font-size: 16px; background: #111; color: white; border: none; border-radius: 8px; cursor: pointer;">
        💾 Salvar Alterações
    </button>
</div>
</form>



    <?php else: ?>
    <p style="text-align:center;">Nenhum vendedor cadastrado.</p>
    <?php endif; ?>

    <a href="/florV3/public/index.php?rota=painel">← Voltar para o Painel</a>


<script>
function toggleAtivo(checkbox) {
    const id = checkbox.dataset.id;
    const ativo = checkbox.checked ? 1 : 0;

    fetch(`/florV3/public/index.php?rota=atualizar-status-vendedor`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&ativo=${ativo}`
    })
    .then(response => response.text())
    .then(res => {
        console.log("Status atualizado:", res);
    })
    .catch(err => {
        alert("Erro ao atualizar status");
        checkbox.checked = !checkbox.checked; // desfaz se erro
    });
}
</script>






</body>
</html>
