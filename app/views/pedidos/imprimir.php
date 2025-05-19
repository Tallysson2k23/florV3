<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cupom</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 250px; /* largura típica de bobina térmica */
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td {
            padding: 3px 5px;
            vertical-align: top;
            border: 1px solid #000; /* BORDA em todas as células */
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        .aviso {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 5px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body onload="window.print();">

<?php if ($tipo === 'entrega'): ?>
    <div class="titulo">Cupom - Entrega</div>
    <table>
        <tr>
            <td><strong>Remetente</strong></td>
            <td><?= $dados['remetente'] ?></td>
            <td><strong>Telefone</strong></td>
            <td><?= $dados['telefone_remetente'] ?></td>
        </tr>
        <tr>
            <td><strong>Destinatário</strong></td>
            <td><?= $dados['destinatario'] ?></td>
            <td><strong>Telefone</strong></td>
            <td><?= $dados['telefone_destinatario'] ?></td>
        </tr>
        <tr>
            <td colspan="4"><strong>Endereço:</strong> <?= $dados['endereco'] ?>, Nº <?= $dados['numero_endereco'] ?></td>
        </tr>
        <tr>
            <td><strong>Referência:</strong></td>
            <td colspan="3"><?= $dados['referencia'] ?></td>
        </tr>
        <tr>
            <td><strong>Bairro:</strong></td>
            <td colspan="3"><?= $dados['bairro'] ?></td>
        </tr>
        <tr>
            <td><strong>Produtos:</strong></td>
            <td colspan="3"><?= $dados['produtos'] ?></td>
        </tr>
        <tr>
            <td><strong>Adicionais:</strong></td>
            <td colspan="3"><?= $dados['adicionais'] ?></td>
        </tr>
        <tr>
            <td><strong>Nº Pedido:</strong></td>
            <td><?= $dados['numero_pedido'] ?></td>
            <td><strong>Data:</strong></td>
            <td><?= date('d/m/Y', strtotime($dados['data_abertura'])) ?> <?= substr($dados['hora'], 0, 5) ?></td>
        </tr>
    </table>

<?php elseif ($tipo === 'retirada'): ?>
    <div class="titulo">Cupom - Retirada</div>
    <table>
        <tr>
            <td><strong>Nome</strong></td>
            <td><?= $dados['nome'] ?></td>
            <td><strong>Telefone</strong></td>
            <td><?= $dados['telefone'] ?></td>
        </tr>
        <tr>
            <td><strong>Produtos:</strong></td>
            <td colspan="3"><?= $dados['produtos'] ?></td>
        </tr>
        <tr>
            <td><strong>Adicionais:</strong></td>
            <td colspan="3"><?= $dados['adicionais'] ?></td>
        </tr>
        <tr>
            <td><strong>Nº Pedido:</strong></td>
            <td><?= $dados['numero_pedido'] ?></td>
            <td><strong>Data/Hora:</strong></td>
            <td><?= date('d/m/Y', strtotime($dados['data_abertura'])) ?> <?= substr($dados['hora'], 0, 5) ?></td>
        </tr>
    </table>

    <div class="aviso">
        Atenção: a retirada do pedido só será efetivada<br>
        mediante a apresentação deste documento!
    </div>
<?php endif; ?>

</body>
</html>
