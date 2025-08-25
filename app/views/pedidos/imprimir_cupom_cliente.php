<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cupom Cliente</title>
    <style>
body {
    font-family: Arial, sans-serif;
    font-size: 13px;   /* era 13px – agora maior para todo o cupom */
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
    padding:5px 7px;   /* aumentei o espaçamento pra acompanhar a fonte maior */
    vertical-align: top;
}

.titulo {
    text-align: center;
    font-weight: bold;
    font-size: 17px;   /* era 17px – mais destaque no título */
    margin-bottom: 12px;
}

.aviso {
    text-align: center;
    font-weight: bold;
    font-size: 11px;   /* era 11px – mais legível */
    border-top: 1px solid #000;
    padding-top: 8px;
}

.voltar {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #eee;
    padding: 6px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;   /* um pouco maior que antes */
    color: #333;
    border: 1px solid #ccc;
}

@media print {
    .voltar {
        display: none;
    }
}

.cliente-destaque {
    font-size: 20px !important;  /* era 20px – nome/remetente em grande destaque */
    font-weight: bold !important;
    color: #000;
    text-align: center;
}
</style>

</head>
<body onload="window.print()">
    <a href="/florV3/public/index.php?rota=painel" class="voltar">⬅ Voltar</a>

<?php if ($tipo === 'entrega'): ?>
    <div class="titulo">Cupom - Entrega</div>
    <table>
        <tr>
            <td><strong>Remetente:</strong></td>
            <td class="cliente-destaque"><?= $dados['remetente'] ?></td>
        </tr>
        <tr><td><strong>Contato:</strong></td><td><?= $dados['telefone_remetente'] ?></td></tr>

        <tr><td><strong>Destinatario:</strong></td><td><?= $dados['destinatario'] ?></td></tr>
        <tr><td><strong>Contato:</strong></td><td><?= $dados['telefone_destinatario'] ?></td></tr>

        <tr><td><strong>Endereço:</strong></td><td><?= $dados['endereco'] ?>, Nº <?= $dados['numero_endereco'] ?></td></tr>
        <tr><td><strong>Bairro:</strong></td><td><?= $dados['bairro'] ?></td></tr>
        <tr><td><strong>Referência:</strong></td><td><?= $dados['referencia'] ?></td></tr>
        <?php
$produtos = explode(',', $dados['produtos'] ?? '');
foreach ($produtos as $produto) :
    $produto = trim($produto);
    if (!empty($produto)):
?>
<tr>
    <td><strong>Produto:</strong></td>
    <td><?= htmlspecialchars($produto) ?></td>
</tr>
        <?php if (!empty($dados['adicionais'])): ?>
<tr>
    <td><strong>Adicionais:</strong></td>
    <td><?= nl2br(htmlspecialchars($dados['adicionais'])) ?></td>
</tr>
<?php endif; ?>
<?php endif; endforeach; ?>

 
        <tr><td><strong>Nº Pedido:</strong></td><td><?= $dados['numero_pedido'] ?></td></tr>
        <tr><td><strong>Data:</strong></td><td><?= date('d/m/Y', strtotime($dados['data_abertura'])) ?></td></tr>
        <tr><td><strong>Vendedor:</strong></td><td><?= htmlspecialchars($dados['codigo_vendedor'] ?? '') ?> <?= htmlspecialchars($dados['nome_vendedor'] ?? '') ?></td></tr>
        


    </table>

<?php elseif ($tipo === 'retirada'): ?>
    <div class="titulo">Cupom - Retirada</div>
    <table>
        <tr>
            <td><strong>Nome:</strong></td>
            <td class="cliente-destaque"><?= $dados['nome'] ?></td>
        </tr>
        <tr><td><strong>Contato:</strong></td><td><?= $dados['telefone'] ?></td></tr>
<?php
$produtos = explode(',', $dados['produtos'] ?? '');
foreach ($produtos as $produto) :
    $produto = trim($produto);
    if (!empty($produto)):
?>
<tr>
    <td><strong>Produto:</strong></td>
    <td><?= htmlspecialchars($produto) ?></td>
</tr>
        <?php if (!empty($dados['adicionais'])): ?>
<tr>
    <td><strong>Adicionais:</strong></td>
    <td><?= nl2br(htmlspecialchars($dados['adicionais'])) ?></td>
</tr>
<?php endif; ?>
<?php endif; endforeach; ?>

        <tr><td><strong>Nº Pedido:</strong></td><td><?= $dados['numero_pedido'] ?></td></tr>
        <tr><td><strong>Data/Hora:</strong></td><td><?= date('d/m/Y', strtotime($dados['data_abertura'])) ?> <?= substr($dados['hora'], 0, 5) ?></td></tr>
        <tr>
            <td><strong>Vendedor:</strong></td>
            <td>
                <?= htmlspecialchars($dados['codigo_vendedor'] ?? '') ?>
                <?= htmlspecialchars($dados['nome_vendedor'] ?? '') ?>
            </td>
        </tr>



    </table>

    <div class="aviso">
        Atenção: a retirada do pedido só será efetivada<br>
        mediante a apresentação deste documento!
    </div>
<?php endif; ?>

</body>
</html>
