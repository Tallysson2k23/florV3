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
            margin-bottom: 30px;
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
            background-color: #e74c3c;
        }

        .status-producao {
            background-color: #f39c12;
        }

        .status-pronto {
            background-color: #3498db;
        }

        .status-entregue {
            background-color: #2ecc71;
        }

        .status-acaminho {
            background-color: #9b59b6;
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
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="container">
    <h2>📦 Acompanhamento de Pedidos</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Complemento</th>
            <th>Observação</th>
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
                case 'entregue':  $statusClasse = 'status-entregue'; break;
                case 'a caminho': $statusClasse = 'status-acaminho'; break;
                default:          $statusClasse = ''; break;
            }

            $id          = $pedido['id'] ?? '';
            $nome        = htmlspecialchars($pedido['nome'] ?? '');
            $tipo        = htmlspecialchars($pedido['tipo'] ?? '');
            $produto     = htmlspecialchars($pedido['produto'] ?? '');
            $quantidade  = htmlspecialchars($pedido['quantidade'] ?? '');
            $complemento = htmlspecialchars($pedido['complemento'] ?? '');
            $obs         = htmlspecialchars($pedido['obs'] ?? '');
            $status      = $pedido['status'] ?? '';
            $data        = htmlspecialchars($pedido['data_abertura'] ?? '');
            $tipoLink    = strtolower(substr($tipo, 2));
        ?>
        <tr>
            <td><?= $id ?></td>
            <td><?= $nome ?></td>
            <td><?= $tipo ?></td>
            <td><?= $produto ?></td>
            <td><?= $quantidade ?></td>
            <td><?= $complemento ?></td>
            <td><?= $obs ?></td>
            <td>
                <select class="<?= $statusClasse ?>"
                        onchange="atualizarStatus(<?= $id ?>, '<?= $tipoLink ?>', this.value)">
                    <?php
                    $opcoes = ['Pendente', 'Produção', 'Pronto', 'Entregue', 'A Caminho'];
                    foreach ($opcoes as $opcao):
                        $selected = $status === $opcao ? 'selected' : '';
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
    fetch('/florV3/public/index.php?rota=atualizar-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&tipo=${tipo}&status=${encodeURIComponent(status)}`
    }).then(res => res.text()).then(data => {
        console.log('Status atualizado:', data);
        location.reload(); // Atualiza a página para aplicar a nova cor
    });
}
</script>

</body>
</html>
