<!-- app/views/painel.php -->
<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Flor de Cheiro - Painel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .status-container {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .coluna {
    width: 220px; /* ajuste conforme desejar */
    flex: 0 0 auto; /* NÃO cresce automaticamente */
    background: #eee;
    padding: 10px;
    border-radius: 8px;
}


        .coluna h3 {
            text-align: center;
            margin-top: 0;
        }

        .pedido {
            background: white;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .ver-mais {
            text-align: center;
            margin-top: 5px;
            cursor: pointer;
            font-size: 12px;
            color: blue;
        }

        .coluna.pendente    { background-color: #f28b82; }
        .coluna.producao    { background-color: #fbbc04; }
        .coluna.pronto      { background-color: #a7cdfa; }

        .botoes {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .botao-acao {
            background: white;
            border-radius: 10px;
            padding: 15px;
            width: 180px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .botao-acao h4 {
            margin: 0 0 10px;
        }

        .botao-acao button {
            background: green;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .ver-toggle {
    text-align: center;
    margin-top: 5px;
    cursor: pointer;
    font-size: 12px;
    color: blue;
}



        .oculto { display: none; }
    </style>
</head>
<body>

<h2>Status dos Pedidos</h2>

<div class="status-container">
    <?php
    $cores = ['Pendente' => 'pendente', 'Produção' => 'producao', 'Pronto' => 'pronto'];

    foreach ($agrupados as $status => $lista):
        $classe = $cores[$status];
    ?>
    <div class="coluna <?= $classe ?>">
        <h3><?= $status ?></h3>
        <?php foreach ($lista as $i => $pedido): ?>
            <div class="pedido <?= $i >= 4 ? 'oculto' : '' ?>">
                <strong>#<?= $pedido['numero_pedido'] ?></strong><br>
                <?= htmlspecialchars($pedido['nome']) ?><br>
                <?= htmlspecialchars($pedido['produto'] ?? '') ?> - <?= date('d/m', strtotime($pedido['data_abertura'])) ?> às <?= substr($pedido['hora'], 0, 5) ?>
            </div>
        <?php endforeach; ?>
       <?php if (count($lista) > 4): ?>
    <div class="ver-toggle" onclick="togglePedidos(this)">⬇ Ver mais</div>
<?php endif; ?>

    </div>
    <?php endforeach; ?>
</div>

<!-- Botões -->
<div class="botoes">
    <div class="botao-acao">
        <h4>Cadastrar Pedido</h4>
        <a href="/florV3/public/index.php?rota=escolher-tipo"><button>Acessar</button></a>
    </div>
    <div class="botao-acao">
        <h4>Ver Histórico</h4>
        <a href="/florV3/public/index.php?rota=historico"><button>Acessar</button></a>
    </div>
    <div class="botao-acao">
        <h4>Acompanhar Pedidos</h4>
        <a href="/florV3/public/index.php?rota=acompanhamento"><button>Acessar</button></a>
    </div>
    <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
    <div class="botao-acao">
        <h4>Gerenciar Usuários</h4>
        <a href="/florV3/public/index.php?rota=usuarios"><button>Acessar</button></a>
    </div>
    <?php endif; ?>
    <div class="botao-acao">
        <h4>Sair</h4>
        <a href="/florV3/public/index.php?rota=logout"><button style="background: red;">Sair</button></a>
    </div>
</div>
<script>
function togglePedidos(botao) {
    const coluna = botao.parentElement;
    const ocultos = coluna.querySelectorAll('.pedido.oculto');

    const mostrandoMais = botao.dataset.expandido === "true";

    if (mostrandoMais) {
        // Ocultar novamente
        coluna.querySelectorAll('.pedido').forEach((pedido, i) => {
            if (i >= 4) pedido.classList.add('oculto');
        });
        botao.textContent = '⬇ Ver mais';
        botao.dataset.expandido = "false";
    } else {
        // Mostrar todos
        ocultos.forEach(p => p.classList.remove('oculto'));
        botao.textContent = '⬆ Ver menos';
        botao.dataset.expandido = "true";
    }
}
</script>


</body>
</html>
