<?php
session_start();

use app\models\Configuracao;
require_once __DIR__ . '/../../models/Configuracao.php';
require_once __DIR__ . '/../../../config/database.php';

if ($_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: /florV3/public/index.php?rota=painel");
    exit;
}

$pdo = \Database::conectar();
$configModel = new Configuracao($pdo);

// Inicializa as variáveis
$mensagem = '';
$valorAtual = $configModel->obter('numero_pedido_padrao') ?? 'L20';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoValor = strtoupper(trim($_POST['novo_valor'] ?? ''));

    if ($novoValor !== '') {
        $configModel->atualizar('numero_pedido_padrao', $novoValor);
        $mensagem = "Número do pedido atualizado para <strong>{$novoValor}</strong>";
        $valorAtual = $novoValor; // Atualiza input
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Nº Pedido Padrão</title>
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

        .form-wrapper {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
            color: #111;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .actions button,
        .actions a {
            background-color: #111;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .actions button:hover,
        .actions a:hover {
            background-color: #333;
        }

        .mensagem {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="form-wrapper">
    <h2>Alterar número padrão dos pedidos</h2>

    <form method="post">
        <label>Novo número do pedido:</label>
        <input name="novo_valor" value="<?= htmlspecialchars($valorAtual) ?>" required>

        <div class="actions">
            <button type="submit">Salvar</button>
            <a href="/florV3/public/index.php?rota=painel">Voltar ao Painel</a>
        </div>
    </form>

    <?php if (!empty($mensagem)): ?>
        <p class="mensagem"><?= $mensagem ?></p>
    <?php endif; ?>
</div>

</body>
</html>
