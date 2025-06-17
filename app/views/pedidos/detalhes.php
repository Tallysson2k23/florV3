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
            height: 60px;
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
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
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
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="container">
    <h2>Detalhes do Pedido</h2>

    <?php if ($dados): ?>

        <!-- Mostrar a MENSAGEM se existir -->
<?php if (isset($dados['mensagem_entrega'])): ?>
    <div class="mensagem-box">
        <strong>Mensagem registrada na entrega:</strong>
        <?= nl2br(htmlspecialchars($dados['mensagem_entrega'] ?: '❌ Nenhuma mensagem informada')) ?>
    </div>
<?php endif; ?>


        <!-- Continua mostrando os outros campos -->
        <ul>
            <?php foreach ($dados as $campo => $valor): ?>
                <?php if ($campo !== 'mensagem_entrega'): // evita mostrar a mensagem duplicada no loop ?>
                    <li><strong><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</strong> <?= htmlspecialchars($valor) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <div class="botoes">
            <a class="btn-voltar" href="/florV3/public/index.php?rota=cancelados">❌ Cancelados</a>
            <a class="btn-voltar" href="/florV3/public/index.php?rota=historico">📜 Histórico</a>
        </div>

    <?php else: ?>
        <p class="nao-encontrado">❌ Pedido não encontrado.</p>
    <?php endif; ?>
</div>

</body>
</html>
