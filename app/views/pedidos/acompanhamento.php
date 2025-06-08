<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acompanhamento - Flor de Cheiro</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            width: 100%;
            background-color: #111;
            color: white;
            font-family: "Brush Script MT", cursive;
            font-size: 28px;
            text-align: center;
            padding: 15px 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            color: #111;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #e4e4e4;
            font-weight: bold;
        }

        select {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 14px;
            border: none;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }

        .status-pendente {
            background-color: rgb(231, 86, 60);
        }

        .status-producao {
            background-color: #f39c12;
        }

        .status-pronto {
            background-color: #3498db;
        }

        button {
            padding: 6px 12px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #333;
        }

        .btn-voltar {
            display: block;
            margin: 30px auto 0;
            background: #555;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-voltar:hover {
            background-color: #222;
        }

        select option {
            color: black;
            background-color: white;
        }

        .filtros {
            text-align: center;
            margin-bottom: 20px;
        }

        .filtros input[type="date"] {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-right: 10px;
        }

        .filtros input[type="text"] {
            padding: 8px;
            width: 300px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        .filtros button {
            padding: 8px 16px;
            background-color: #111;
            color: white;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="container">
    <h2>📦 Acompanhamento de Pedidos</h2>

    <!-- Filtros -->
    <form method="GET" action="/florV3/public/index.php" class="filtros">
        <input type="hidden" name="rota" value="acompanhamento">
        <input type="date" name="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
        <input type="text" name="busca" placeholder="Buscar por nome ou número do pedido" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
        <button type="submit">🔍 Buscar</button>
    </form>

    <!-- Tabela -->
    <table>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Status</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($todosPedidos as $pedido):
            $statusClasse = '';

            switch (strtolower($pedido['status'] ?? '')) {
                case 'pendente':  $statusClasse = 'status-pendente'; break;
                case 'produção':  $statusClasse = 'status-producao'; break;
                case 'pronto':    $statusClasse = 'status-pronto'; break;
                default:          $statusClasse = ''; break;
            }

            $id    = $pedido['id'] ?? '';
            $nome  = htmlspecialchars($pedido['nome'] ?? '');
            $tipo  = htmlspecialchars($pedido['tipo'] ?? '');
            $status = $pedido['status'] ?? '';
            $data  = htmlspecialchars($pedido['data_abertura'] ?? '');
            $tipoLink = strtolower(substr($tipo, 2));

            // NÃO exibe pedidos com status PRONTO
            if (strtolower($status) === 'pronto') {
                continue;
            }
        ?>
        <tr>
            <td><?= $id ?></td>
            <td><?= $nome ?></td>
            <td>
                <select class="<?= $statusClasse ?>"
                        onchange="atualizarStatus(<?= $id ?>, '<?= $tipoLink ?>', this.value)">
                    <?php
                    $opcoes = ['Pendente', 'Produção', 'Pronto'];
                    foreach ($opcoes as $opcao):
                        $selected = strtolower($status) === strtolower($opcao) ? 'selected' : '';
                        echo "<option value=\"$opcao\" $selected>$opcao</option>";
                    endforeach;
                    ?>
                </select>
            </td>
            <td><?= $data ?></td>
            <td>
                <a href="/florV3/public/index.php?rota=imprimir-pedido&id=<?= $id ?>&tipo=<?= $tipoLink ?>" target="_blank">
                    <button>🖨️ Imprimir</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">← Voltar ao Painel</a>
</div>

<script>
function atualizarStatus(id, tipo, status) {
    if (status === "Cancelado") {
        const confirmacao = confirm("Tem certeza que deseja CANCELAR este pedido?");
        if (!confirmacao) return;
    } else if (status === "Pronto") {
        const confirmacao = confirm("Você confirma que este pedido está PRONTO? Ao confirmar, ele sairá da lista.");
        if (!confirmacao) return;
    }

    fetch('/florV3/public/index.php?rota=atualizar-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&tipo=${tipo}&status=${encodeURIComponent(status)}`
    }).then(res => res.text()).then(data => {
        console.log('Status atualizado:', data);
        location.reload();
    });
}
</script>


</body>
</html>
