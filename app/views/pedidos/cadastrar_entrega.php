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
    <title>Cadastro de Entrega - Flor de Cheiro</title>
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
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
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

        .obrig {
            color: red;
            margin-left: 2px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
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

    <link rel="stylesheet" href="/florV3/public/assets/css/choices.min.css">
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="form-wrapper">
    <h2>Cadastro de Entrega</h2>

    <form id="form-entrega" method="post" action="/florV3/public/index.php?rota=salvar-entrega">

        <div class="form-group">
            <div>
                <label>Nº Pedido:<span class="obrig">*</span></label>
                <input name="numero_pedido" id="numero_pedido" required value="<?= htmlspecialchars($numeroPedidoPadrao) ?>">

            </div>
            <div>
                <label>Tipo:<span class="obrig">*</span></label>
                <input name="tipo" value="1-Entrega" readonly>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Remetente:<span class="obrig">*</span></label>
                <input name="remetente" required>
            </div>
            <div>
                <label>Telefone do Remetente:<span class="obrig">*</span></label>
                <input name="telefone_remetente" required>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Destinatário:</label>
                <input name="destinatario">
            </div>
            <div>
                <label>Telefone do Destinatário:</label>
                <input name="telefone_destinatario">
            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Endereço:<span class="obrig">*</span></label>
                <input name="endereco" required>
            </div>
            <div>
                <label>Nº:<span class="obrig">*</span></label>
                <input name="numero_endereco" required>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Bairro:<span class="obrig">*</span></label>
                <input name="bairro" required>
            </div>
            <div>
                <label>Referência:</label>
                <input name="referencia">
            </div>
        </div>

        <div class="form-group full">
            <label>Produto:<span class="obrig">*</span></label>
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
                    <th style="padding: 10px; border: 1px solid #ddd;">Quantidade</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Observação</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Remover</th>
                </tr>
            </thead>
            <tbody id="lista-produtos"></tbody>
        </table>
        <br>

        <div class="form-group full">
            <div>
                <label>Data de Abertura:<span class="obrig">*</span></label>
                <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>

        <div class="form-group full">
            <div>
                <label>Vendedor:<span class="obrig">*</span></label>
                <select name="vendedor_codigo" required>
                    <option value="">Selecione</option>
                    <?php foreach ($vendedores as $v): ?>
                        <option value="<?= htmlspecialchars($v['codigo']) ?>">
                            <?= htmlspecialchars($v['codigo']) ?> - <?= htmlspecialchars($v['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <label><strong>Enviar para: <span class="obrig">*</span></strong></label><br>
        <div style="margin-left:20px;">
            <input type="radio" name="enviar_para" id="producao" value="producao" required>
            <label for="producao">Mandar para Produção</label><br>
            
            <input type="radio" name="enviar_para" id="pronta_entrega" value="pronta_entrega" required>
            <label for="pronta_entrega">Pronta Entrega</label>
        </div><br>

        <input type="hidden" name="imprimir" id="imprimir" value="0">

        <div class="actions">
            <button type="button" onclick="confirmarEnvioEntrega()">Enviar</button>
            <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
        </div>
    </form>
</div>

<script>
function camposMinimosOk() {
    const form = document.getElementById('form-entrega');
    if (!form.reportValidity()) return false;
    if (!document.querySelector('#lista-produtos tr')) {
        alert('Adicione pelo menos um produto.');
        return false;
    }
    return true;
}

function confirmarEnvioEntrega() {
    if (!camposMinimosOk()) return;

    const enviarParaSelecionado = document.querySelector('input[name="enviar_para"]:checked');
    if (!enviarParaSelecionado) {
        alert("Por favor, selecione uma opção em 'Enviar para'.");
        return;
    }

    if (!confirm("Deseja realmente enviar o pedido?")) return;
    document.getElementById("imprimir").value = confirm("Deseja imprimir o cupom?") ? "1" : "0";
    document.getElementById("form-entrega").submit();
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


    if (!campo.value.startsWith(prefixo)) {
        campo.value = prefixo;
    }

    campo.addEventListener("keydown", function (e) {
        const pos = campo.selectionStart;
        if ((pos <= prefixo.length) && (e.key === "Backspace" || e.key === "Delete")) {
            e.preventDefault();
        }
        if (pos < prefixo.length && !["ArrowLeft", "ArrowRight", "Tab"].includes(e.key)) {
            e.preventDefault();
            campo.setSelectionRange(campo.value.length, campo.value.length);
        }
    });

    campo.addEventListener("focus", function () {
        setTimeout(() => {
            if (campo.selectionStart < prefixo.length) {
                campo.setSelectionRange(campo.value.length, campo.value.length);
            }
        }, 0);
    });
});
</script>

<script src="/florV3/public/assets/js/choices.min.js"></script>

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
