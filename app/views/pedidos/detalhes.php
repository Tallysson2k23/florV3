<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Pedido - Flor de Cheiro</title>
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
            height: 85px;
            background-color: #111;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Brush Script MT", cursive;
            font-size: 28px;
        }

       .container {
    max-width: 700px;
    margin: 0 auto;
    background: #f3f4f6; /* mesma cor do body */
    padding: 30px;
    border-radius: 0px;
}


        h2 {
            text-align: center;
            color: #111;
            margin-bottom: 25px;
            font-size: 24px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            font-size: 16px;
        }

        ul li strong {
            color: #333;
        }

        .botoes {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-voltar {
            background: transparent;
            border: 1px solid #111;
            color: #111;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
        }

        .btn-voltar:hover {
            background: #111;
            color: white;
        }

        .nao-encontrado {
            text-align: center;
            color: #a00;
            margin-top: 40px;
        }

        .mensagem-box {
            background: #fef7e0;
            border: 1px solid #f1c40f;
            padding: 15px;
            border-radius: 8px;
            font-size: 15px;
            color: #333;
            margin-bottom: 20px;
        }

        .mensagem-box strong {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #e67e22;
        }

        .logo-img {
    height: 50px;
    object-fit: contain;
}

.container,
.top-bar,
body > div {
    box-shadow: none !important;
}


    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>


<div class="container">
    <h2>Detalhes do Pedido</h2>

    <?php if ($dados): ?>

        <!-- Mostrar a MENSAGEM se existir 
<?php if (isset($dados['mensagem_entrega'])): ?>
    <div class="mensagem-box">
        <strong>Mensagem registrada na entrega:</strong>
        <?= nl2br(htmlspecialchars($dados['mensagem_entrega'] ?: '❌ Nenhuma mensagem informada')) ?>
    </div>
<?php endif; ?> --> 

        <!-- Continua mostrando os outros campos -->
        <ul>
    <?php
        // Ignorar os campos que serão tratados separadamente no final
$ignorar = [
    'mensagem_entrega', 'nome_vendedor', 'vendedor_codigo',
    'codigo_vendedor', 'data_abertura', 'hora',
    'responsavel', 'responsavel_producao', 'responsavel_produção',
    'adicionais', 'obs_produto', 'quantidade' // agora sempre ocultos
];


        // Exibir todos os outros campos primeiro
        foreach ($dados as $campo => $valor):
            if (in_array($campo, $ignorar)) continue;
    ?>
        <li><strong><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</strong> <?= htmlspecialchars($valor) ?></li>
    <?php endforeach; ?>

    <?php
        // Montar "Vendedor/código"
        $nomeVendedor = $dados['nome_vendedor'] ?? '';
        $codigoVendedor = $dados['vendedor_codigo'] ?? '';
        $vendedorFinal = trim($nomeVendedor . ($codigoVendedor ? " ($codigoVendedor)" : ''));

        if ($vendedorFinal):
    ?>
        <li><strong>Vendedor:</strong> <?= htmlspecialchars($vendedorFinal) ?></li>
    <?php endif; ?>

        <?php
    // Verificar os possíveis campos de responsável
    $responsavel = $dados['responsavel'] ?? ($dados['responsavel_producao'] ?? null);

    if (!empty($responsavel)):
?>
    <li><strong>Responsável pela Produção:</strong> <?= htmlspecialchars($responsavel) ?></li>
<?php endif; ?>


    <?php
        // Exibir data + hora juntos
        $data = $dados['data_abertura'] ?? '';
        $hora = $dados['hora'] ?? '';

        if ($data || $hora):
    ?>
        <li><strong>Data/Hora de abertura:</strong> <?= htmlspecialchars(trim("$data $hora")) ?></li>
    <?php endif; ?>
    
</ul>

<?php if (!empty($historico)): ?>
    <h3 style="margin-top: 30px; color: #444;">📌 Histórico de Status</h3>
    <ul>
        <?php foreach ($historico as $h): ?>
    <li>
        <strong><?= ucfirst($h['status']) ?>:</strong> 
        <?= date('d/m/Y H:i', strtotime($h['data_hora'])) ?>

        <?php if (!empty($h['mensagem'])): ?>
            <div class="mensagem-box" style="margin-top: 10px;">
                <strong>
            <?php
                $statusLower = strtolower($h['status']);
                echo $statusLower === 'retorno' ? '📝 Motivo do retorno:' :
                     ($statusLower === 'cancelado' ? '❌ Motivo do cancelamento:' : '📝 Mensagem:');
            ?>
        </strong>
                <?= nl2br(htmlspecialchars($h['mensagem'])) ?>
            </div>
        <?php endif; ?>
    </li>
<?php endforeach; ?>

    </ul>
<?php endif; ?>



<div class="botoes">
    <a class="btn-voltar" href="/florV3/public/index.php?rota=painel"><- Voltar</a>
    <a class="btn-voltar" href="/florV3/public/index.php?rota=historico">📜 Histórico</a>

    <?php if (isset($_GET['id']) && isset($_GET['tipo'])): ?>
        <a class="btn-voltar" target="_blank"
           href="/florV3/public/index.php?rota=imprimir-cupom-cliente&id=<?= htmlspecialchars($_GET['id']) ?>&tipo=<?= htmlspecialchars($_GET['tipo']) ?>">
            🧾 Cupom do Cliente
        </a>
    <?php endif; ?>
</div>


    <?php else: ?>
        <p class="nao-encontrado">❌ Pedido não encontrado.</p>
    <?php endif; ?>
</div>

</body>
</html>
