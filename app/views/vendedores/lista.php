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
        a:hover { text-decoration: underline; }

        /* botões */
        .btn-excluir {
            padding: 8px 12px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-excluir:hover { background: #c0392b; }

        .btn-salvar {
            padding: 10px 20px;
            font-size: 16px;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-salvar:hover { background: #333; }

        .input-cell {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
</head>

<?php if (isset($_GET['status']) && $_GET['status'] === 'ok'): ?>
<div id="notificacao" style="
    position: fixed; top: 20px; right: 20px;
    background-color: #2ecc71; color: white;
    padding: 15px 20px; border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.15); font-weight: bold; z-index: 9999;">
    ✅ Alterações salvas com sucesso!
</div>
<script>
    setTimeout(() => {
        const url = new URL(window.location);
        url.searchParams.delete('status');
        window.history.replaceState({}, document.title, url);
        const n = document.getElementById('notificacao'); if (n) n.remove();
    }, 3000);
</script>
<?php endif; ?>

<body>
    <div class="top-bar">
        <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
    </div>

    <h2>Lista de Vendedores</h2>

    <?php if (!empty($vendedores)): ?>
    <!-- UM ÚNICO FORM PARA SALVAR EDIÇÕES/ATIVOS -->
    <form method="post" action="/florV3/public/index.php?rota=salvar-status-vendedores">
        <table>
            <tr>
                <th>Nome</th>
                <th>Código</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>

            <?php foreach ($vendedores as $vendedor): ?>
            <tr>
                <td>
                    <input type="text"
                           name="nome[<?= (int)$vendedor['id'] ?>]"
                           value="<?= htmlspecialchars($vendedor['nome']) ?>"
                           class="input-cell">
                </td>
                <td>
                    <input type="text"
                           name="codigo[<?= (int)$vendedor['id'] ?>]"
                           value="<?= htmlspecialchars($vendedor['codigo'] ?? '') ?>"
                           class="input-cell">
                </td>
                <td style="text-align:center;">
                    <input type="checkbox"
                           name="ativo[<?= (int)$vendedor['id'] ?>]"
                           value="1" <?= !empty($vendedor['ativo']) ? 'checked' : '' ?>>
                </td>
                <td style="text-align:center;">
                    <!-- Botão EXCLUIR usando o MESMO FORM: sem forms aninhados -->
                    <button type="submit"
                            name="excluir_id"
                            value="<?= (int)$vendedor['id'] ?>"
                            formaction="/florV3/public/index.php?rota=excluir-vendedor"
                            formmethod="post"
                            onclick="return confirm('Excluir definitivamente este vendedor?');"
                            class="btn-excluir">
                        Excluir
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div style="text-align: center; margin-top: 20px;">
            <button type="submit" class="btn-salvar">💾 Salvar Alterações</button>
        </div>
    </form>

    <?php else: ?>
        <p style="text-align:center;">Nenhum vendedor cadastrado.</p>
    <?php endif; ?>

    <a href="/florV3/public/index.php?rota=painel">← Voltar para o Painel</a>

    <!-- Removido: toggleAtivo() não está sendo usado nessa view -->
</body>
</html>
