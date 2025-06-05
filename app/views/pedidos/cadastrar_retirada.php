<?php
require_once __DIR__ . '/../../models/Produto.php';
require_once __DIR__ . '/../../../config/database.php'; 

$pdo = Database::conectar();
$produtoModel = new Produto($pdo);
$produtos = $produtoModel->listarTodos();
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Retirada - Flor de Cheiro</title>
    <style>
        * {
            box-sizing: border-box;
        }

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
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #111;
        }

        .form-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group.full {
            grid-template-columns: 1fr;
        }

        label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    background-color: white;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}


        .actions {
            text-align: center;
            margin-top: 30px;
        }

        .actions button {
            background-color: #111;
            color: white;
            border: none;
            padding: 12px 24px;
            margin: 0 10px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .actions button:hover {
            background-color: #333;
        }

        @media (max-width: 600px) {
            .form-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="form-wrapper">
    <h2>Cadastro de Retirada</h2>

    <form id="form-retirada" method="post" action="/florV3/public/index.php?rota=salvar-retirada">
        <div class="form-group">
            <div>
                <label>Nº Pedido:</label>
                <input name="numero_pedido" id="numero_pedido" required value="L20">

            </div>
            <div>
                <label>Tipo:</label>
                <input name="tipo" value="2-Retirada" readonly>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Nome:</label>
                <input name="nome" required>
            </div>
            <div>
                <label>Telefone:</label>
                <input name="telefone">
            </div>
        </div>

       <div class="form-group full">
    <div>
        <label>Produto:</label>
        <select name="produtos" required>
            <option value="">Selecione</option>
            <?php foreach ($produtos as $produto): ?>
                <option value="<?= htmlspecialchars($produto['nome']) ?>">
                    <?= htmlspecialchars($produto['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group full">
    <div>
        <label>OBS:</label>
        <textarea name="obs_produto" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 15px;"></textarea>
    </div>
</div>



        <div class="form-group">
    <div>
        <label>Quantidade:</label>
        <input name="quantidade" type="number" min="1" value="1" required>
    </div>
</div>


        <div class="form-group full">
            <div>
                <label>Adicionais:</label>
                <input name="adicionais">
            </div>
        </div>

        <div class="form-group full">
            <div>
                <label>Data:</label>
                <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <!-- Adicione antes do botão -->
<div class="form-group full">
    <label>Vendedor:</label>
    <select name="vendedor_codigo" required>
        <option value="">Selecione</option>
        <?php foreach ($vendedores as $v): ?>
            <option value="<?= htmlspecialchars($v['codigo']) ?>">
                <?= htmlspecialchars($v['codigo']) ?> - <?= htmlspecialchars($v['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

        <input type="hidden" name="imprimir" id="imprimir-retirada" value="0">

        <div class="actions">
            <button type="button" onclick="confirmarEnvioRetirada()">Enviar</button>
            <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
        </div>
    </form>
</div>

<script>
function confirmarEnvioRetirada() {
    if (confirm("Deseja realmente enviar o pedido?")) {
        const imprimir = confirm("Deseja imprimir o cupom?");
        document.getElementById("imprimir-retirada").value = imprimir ? "1" : "0";
        document.getElementById("form-retirada").submit();
    }
}

function confirmarCancelamento() {
    if (confirm("Deseja realmente cancelar? Os dados serão perdidos.")) {
        window.location.href = "/florV3/public/index.php?rota=painel";
    }
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const campo = document.getElementById("numero_pedido");
    const prefixo = "L20";

    // Aplica prefixo se estiver vazio
    if (!campo.value.startsWith(prefixo)) {
        campo.value = prefixo;
    }

    // Bloqueia apagar o prefixo
    campo.addEventListener("keydown", function (e) {
        const pos = campo.selectionStart;

        // Impede apagar prefixo
        if ((pos <= prefixo.length) && (e.key === "Backspace" || e.key === "Delete")) {
            e.preventDefault();
        }

        // Impede digitar antes do prefixo
        if (pos < prefixo.length && !["ArrowLeft", "ArrowRight", "Tab"].includes(e.key)) {
            e.preventDefault();
            campo.setSelectionRange(campo.value.length, campo.value.length);
        }
    });

    // Quando foca, move cursor para depois do prefixo
    campo.addEventListener("focus", function () {
        setTimeout(() => {
            if (campo.selectionStart < prefixo.length) {
                campo.setSelectionRange(campo.value.length, campo.value.length);
            }
        }, 0);
    });
});
</script>

</body>
</html>
