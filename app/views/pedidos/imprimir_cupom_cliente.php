<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cupom Cliente</title>
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
            margin-bottom: 10px;
        }

        .aviso {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            border-top: 1px solid #000;
            padding-top: 6px;
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

    </style>
</head>
<body onload="window.print()">
    <a href="/florV3/public/index.php?rota=painel" class="voltar">⬅ Voltar</a>

<body onload="window.print()">

<?php if ($tipo === 'entrega'): ?>
    <div class="titulo">Cupom - Entrega</div>
    <table>
        <tr><td><strong>Remetente:</strong></td><td><?= $dados['remetente'] ?></td></tr>
        <tr><td><strong>Destinatário:</strong></td><td><?= $dados['destinatario'] ?></td></tr>
        <tr><td><strong>Endereço:</strong></td><td><?= $dados['endereco'] ?>, Nº <?= $dados['numero_endereco'] ?></td></tr>
        <tr><td><strong>Bairro:</strong></td><td><?= $dados['bairro'] ?></td></tr>
        <tr><td><strong>Referência:</strong></td><td><?= $dados['referencia'] ?></td></tr>
        <tr><td><strong>Produtos:</strong></td><td><?= $dados['produtos'] ?></td></tr>
        <tr><td><strong>Adicionais:</strong></td><td><?= $dados['adicionais'] ?></td></tr>
        <tr><td><strong>Nº Pedido:</strong></td><td><?= $dados['numero_pedido'] ?></td></tr>
        <tr><td><strong>Data:</strong></td><td><?= date('d/m/Y', strtotime($dados['data_abertura'])) ?></td></tr>
    </table>

<?php elseif ($tipo === 'retirada'): ?>
    <div class="titulo">Cupom - Retirada</div>
    <table>
        <tr><td><strong>Nome:</strong></td><td><?= $dados['nome'] ?></td></tr>
        <tr><td><strong>Telefone:</strong></td><td><?= $dados['telefone'] ?></td></tr>
        <tr><td><strong>Produtos:</strong></td><td><?= $dados['produtos'] ?></td></tr>
        <tr><td><strong>Adicionais:</strong></td><td><?= $dados['adicionais'] ?></td></tr>
        <tr><td><strong>Nº Pedido:</strong></td><td><?= $dados['numero_pedido'] ?></td></tr>
        <tr><td><strong>Data/Hora:</strong></td><td><?= date('d/m/Y', strtotime($dados['data_abertura'])) ?> <?= substr($dados['hora'], 0, 5) ?></td></tr>
    </table>

    <div class="aviso">
        Atenção: a retirada do pedido só será efetivada<br>
        mediante a apresentação deste documento!
    </div>
<?php endif; ?>

</body>
</html>
