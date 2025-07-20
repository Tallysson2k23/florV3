<?php
require_once __DIR__ . '/../../models/Produto.php';
require_once __DIR__ . '/../../../config/database.php'; 
require_once __DIR__ . '/../../models/Configuracao.php';

$pdo = Database::conectar();
$produtoModel = new Produto($pdo);
$produtos = $produtoModel->listarAtivos();
$configModel = new \app\models\Configuracao($pdo);
$numeroPedidoPadrao = $configModel->obter('numero_pedido_padrao') ?? 'L20';
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

.form-wrapper {
    max-width: 800px;
    margin: 50px auto;
    background: #f3f4f6; /* mesmo fundo do body */
    padding: 40px;
    border-radius: 0;
    box-shadow: none;
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

.top-bar {
    background-color: #111;
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-img {
    height: 50px;
    object-fit: contain;
    max-width: 100%;
    display: inline-block;
}
    </style>
    <!-- Choices CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<!-- Choices JS -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

</head>
<body>


<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="form-wrapper">
    <h2>Cadastro de Retirada</h2>

<!-- AJUSTADO -->
<form id="form-retirada" method="post" action="/florV3/public/index.php?rota=salvar-retirada">

    <div class="form-group">
        <div>
            <label>Nº Pedido: <span style="color:red">*</span></label>
            <input name="numero_pedido" id="numero_pedido" required value="<?= htmlspecialchars($numeroPedidoPadrao) ?>">

        </div>
        <div>
            <label>Tipo:</label>
            <input name="tipo" value="2-Retirada" readonly>
        </div>
    </div>

    <div class="form-group">
        <div>
            <label>Nome: <span style="color:red">*</span></label>
            <input name="nome" required>
        </div>
        <div>
            <label>Telefone:</label>
            <input name="telefone">
        </div>
    </div>

    <div class="form-group full">
        <label>Adicionar Produto: <span style="color:red">*</span></label>
        <select id="produto-seletor">
            <option value="">Selecione...</option>
            <?php foreach ($produtos as $produto): ?>
                <option value="<?= htmlspecialchars($produto['nome']) ?>">
                    <?= htmlspecialchars($produto['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <table style="width:100%; border-collapse: collapse;" id="tabela-produtos">
        <thead>
            <tr style="background: #f0f0f0;">
                <th style="padding: 10px; border: 1px solid #ddd;">Produto</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Quantidade</span></th>
                <th style="padding: 10px; border: 1px solid #ddd;">Observação</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Remover</th>
            </tr>
        </thead>
        <tbody id="lista-produtos"></tbody>
    </table>

    <div class="form-group full">
        <div>
            <label>Data: <span style="color:red">*</span></label>
            <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>" required>
        </div>
    </div>

    <div class="form-group full">
        <label>Vendedor: <span style="color:red">*</span></label>
        <select name="vendedor_codigo" required>
            <option value="">Selecione</option>
            <?php foreach ($vendedores as $v): ?>
                <option value="<?= htmlspecialchars($v['codigo']) ?>">
                    <?= htmlspecialchars($v['codigo']) ?> - <?= htmlspecialchars($v['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <label><strong>Enviar para: <span style="color:red;">*</span></strong></label><br>
    <div style="margin-left:20px;">
        <input type="radio" name="enviar_para" id="producao" value="producao" required>
        <label for="producao">Mandar para Produção</label><br>
        
        <input type="radio" name="enviar_para" id="pronta_entrega" value="pronta_entrega" required>
        <label for="pronta_entrega">Pronta Entrega</label>
    </div><br>

    <input type="hidden" name="imprimir" id="imprimir-retirada" value="0">

    <div class="actions">
        <button type="button" onclick="confirmarEnvioRetirada()">Enviar</button>
        <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
    </div>
</form>

<script>
function camposMinimosRetiradaOk() {
    const form = document.getElementById('form-retirada');

    if (!form.reportValidity()) return false;

    if (!document.querySelector('#lista-produtos tr')) {
        alert('Adicione pelo menos um produto.');
        return false;
    }

    return true;
}

function confirmarEnvioRetirada() {
    if (!camposMinimosRetiradaOk()) return;

    if (confirm("Deseja realmente enviar o pedido?")) {
        document.getElementById("imprimir-retirada").value = confirm("Deseja imprimir o cupom?") ? "1" : "0";
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
    const prefixo = "<?= $numeroPedidoPadrao ?>";

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


<script>
document.addEventListener('DOMContentLoaded', function () {
    const seletor = document.getElementById('produto-seletor');
    const lista = document.getElementById('lista-produtos');

    seletor.addEventListener('change', function () {
        const nome = seletor.value;
        if (!nome) return;

        if (document.querySelector(`tr[data-produto="${nome}"]`)) {
            alert("Este produto já foi adicionado.");
            return;
        }

        const linha = document.createElement('tr');
        linha.setAttribute('data-produto', nome);
        linha.innerHTML = `
            <td style="border: 1px solid #ddd; padding: 8px;">
                <input type="hidden" name="produtos[${nome}][nome]" value="${nome}">
                ${nome}
            </td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                <input type="number" name="produtos[${nome}][quantidade]" value="1" min="1" required style="width: 60px;">
            </td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                <input type="text" name="produtos[${nome}][observacao]" placeholder="Observação...">
            </td>
            <td style="border: 1px solid #ddd; padding: 8px;">
                <button type="button" onclick="this.closest('tr').remove()">❌</button>
            </td>
        `;
        lista.appendChild(linha);
        seletor.value = "";
    });
});
</script>



</body>
</html>
