<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ordem de Produção</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            width: 250px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .rodape {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding-top: 10px;
        }

        .label {
            background-color: #f1f1f1;
            font-weight: bold;
            width: 30%;
        }

        .voltar {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #eee;
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            color: #333;
            border: 1px solid #ccc;
        }

        @media print {
            .voltar {
                display: none;
            }
        }

        .cliente-destaque {
            font-size: 24px !important;
            font-weight: bold !important;
            color: #000;
            text-align: center;
        }

    </style>
</head>
<body onload="window.print()">
    <a href="/florV3/public/index.php?rota=acompanhamento" class="voltar">⬅ Voltar</a>

    <div class="titulo">Ordem de Produção</div>

    <table>
        <tr>
            <td class="label">Nome</td>
            <td class="cliente-destaque"><?= htmlspecialchars($dados['nome'] ?? $dados['destinatario'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Produtos:</td>
            <td><?= htmlspecialchars(($dados['quantidade'] ?? '1') . 'x ' . ($dados['produtos'] ?? '-')) ?></td>
        </tr>
        <tr>
            <td class="label">OBS:</td>
            <td><?= htmlspecialchars($dados['obs_produto'] ?? '-') ?></td>
        </tr>
        <!--
        <tr>
            <td class="label">Complemento</td>
            <td><?= htmlspecialchars($dados['adicionais'] ?? '-') ?></td>
        </tr>
        -->
        <tr>
            <td class="label">N. pedido</td>
            <td><?= htmlspecialchars($dados['numero_pedido'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Data</td>
            <td><?= date('d/m/Y', strtotime($dados['data_abertura'] ?? date('Y-m-d'))) ?></td>
        </tr>
    </table>

    <div class="rodape"><?= strtoupper($tipo) ?></div>

</body>
</html>
