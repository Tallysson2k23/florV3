<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acompanhamento - Flor de Cheiro</title>
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
            background-color: #111;
            color: white;
            font-family: "Brush Script MT", cursive;
            font-size: 28px;
            text-align: center;
            padding: 15px 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            color: #111;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #e4e4e4;
            font-weight: bold;
        }

        select {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 14px;
            border: none;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }

        .status-pendente {
            background-color: rgb(231, 86, 60);
        }

        .status-producao {
            background-color: #f39c12;
        }

        .status-pronto {
            background-color: #3498db;
        }

        button {
            padding: 6px 12px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #333;
        }

        .btn-voltar {
            display: block;
            margin: 30px auto 0;
            background: #555;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-voltar:hover {
            background-color: #222;
        }

        select option {
            color: black;
            background-color: white;
        }

        .filtros {
            text-align: center;
            margin-bottom: 20px;
        }

        .filtros input[type="date"] {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-right: 10px;
        }

        .filtros input[type="text"] {
            padding: 8px;
            width: 300px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        .filtros button {
            padding: 8px 16px;
            background-color: #111;
            color: white;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="container">
    <h2>📦 Acompanhamento de Pedidos</h2>

    <?php
require_once __DIR__ . '/../../models/Operador.php';

$operadorModel = new Operador();
$operadores = $operadorModel->listarTodos();
?>

<!-- Modal de Seleção de Operador -->
<div id="modalResponsavel" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999;">
    <div style="background:white; padding:20px; border-radius:8px; width:400px; margin:100px auto; position:relative;">
        <h3>Selecione o Operador</h3>
        <select id="responsavelSelect" style="width:100%; padding:10px; font-size:16px; background-color:white; color:black;">

            <option value="">Selecione...</option>
            <?php foreach ($operadores as $op): ?>
                <option value="<?= htmlspecialchars($op['nome']) ?>"><?= htmlspecialchars($op['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <div style="margin-top:20px; text-align:right;">
            <button onclick="confirmarResponsavel()">Confirmar</button>
            <button onclick="fecharModal()">Cancelar</button>
        </div>
    </div>
</div>


    <!-- Filtros -->
    <form method="GET" action="/florV3/public/index.php" class="filtros">
        <input type="hidden" name="rota" value="acompanhamento">
        <input type="date" name="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
        <input type="text" name="busca" placeholder="Buscar por nome ou número do pedido" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
        <button type="submit">🔍 Buscar</button>
    </form>

    <!-- Tabela -->
    <table>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Status</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>

        <?php foreach ($todosPedidos as $pedido):
            $statusClasse = '';

            switch (strtolower($pedido['status'] ?? '')) {
                case 'pendente':  $statusClasse = 'status-pendente'; break;
                case 'produção':  $statusClasse = 'status-producao'; break;
                case 'pronto':    $statusClasse = 'status-pronto'; break;
                default:          $statusClasse = ''; break;
            }

            $id    = $pedido['id'] ?? '';
            $nome  = htmlspecialchars($pedido['nome'] ?? '');
            $tipo  = htmlspecialchars($pedido['tipo'] ?? '');
            $status = $pedido['status'] ?? '';
            $data  = htmlspecialchars($pedido['data_abertura'] ?? '');
            $tipoLink = strtolower(substr($tipo, 2));

            // NÃO exibe pedidos com status PRONTO
            if (strtolower($status) === 'pronto') {
                continue;
            }
        ?>
        <tr>
            <td><?= $id ?></td>
            <td>
    <a href="/florV3/public/index.php?rota=detalhes&id=<?= $id ?>&tipo=<?= $tipoLink ?>">
    <?= $nome ?>
</a>

</td>

            <td>
                <select class="<?= $statusClasse ?>"
                        onchange="atualizarStatus(<?= $id ?>, '<?= $tipoLink ?>', this.value)">
                    <?php
                    $opcoes = ['Pendente', 'Produção', 'Pronto'];
                    foreach ($opcoes as $opcao):
                        $selected = strtolower($status) === strtolower($opcao) ? 'selected' : '';
                        echo "<option value=\"$opcao\" $selected>$opcao</option>";
                    endforeach;
                    ?>
                </select>
            </td>
            <td><?= $data ?></td>
            <td>
                <button onclick="confirmarImpressao(<?= $id ?>, '<?= $tipoLink ?>')">🖨️ Imprimir</button>

            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">← Voltar ao Painel</a>
</div>

<script>
let statusTemp = null;
let idTemp = null;
let tipoTemp = null;

function atualizarStatus(id, tipo, status) {
    if (status === "Cancelado") {
        const confirmacao = confirm("Tem certeza que deseja CANCELAR este pedido?");
        if (!confirmacao) return;
    } else if (status === "Pronto") {
        const confirmacao = confirm("Você confirma que este pedido está PRONTO? Ao confirmar, ele sairá da lista.");
        if (!confirmacao) return;
    }

    if (status === "Produção") {
        // Abre o modal e guarda temporariamente as infos
        statusTemp = status;
        idTemp = id;
        tipoTemp = tipo;
        document.getElementById("responsavelSelect").value = "";
        document.getElementById("modalResponsavel").style.display = "block";
        return;
    }

    // Se não for produção, envia normalmente
    enviarStatus(id, tipo, status);
}

function confirmarResponsavel() {
    const responsavel = document.getElementById("responsavelSelect").value;
    if (!responsavel) {
        alert("Selecione um operador!");
        return;
    }

    const modal = document.getElementById("modalResponsavel");
    const acao = modal.getAttribute("data-acao");
    modal.style.display = "none";

    if (acao === "impressao") {
        // Aqui registramos no banco ANTES de imprimir
        fetch('/florV3/public/index.php?rota=registrar-responsavel', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${idImpressaoTemp}&tipo=${tipoImpressaoTemp}&responsavel=${encodeURIComponent(responsavel)}`
        })
        .then(res => res.text())
        .then(data => {
            // Após registrar, redireciona para impressão
            window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${idImpressaoTemp}&tipo=${tipoImpressaoTemp}`, '_blank');
        })
        .catch(err => alert("Erro ao registrar responsável!"));
    } else {
        // Fluxo normal de alteração de status
        let dados = `id=${idTemp}&tipo=${tipoTemp}&status=${encodeURIComponent(statusTemp)}&responsavel=${encodeURIComponent(responsavel)}`;
        fetch('/florV3/public/index.php?rota=atualizar-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: dados
        }).then(res => res.text()).then(data => {
            location.reload();
        });
    }
}



function fecharModal() {
    document.getElementById("modalResponsavel").style.display = "none";
}

// Função padrão para status que não são Produção
function enviarStatus(id, tipo, status) {
    let dados = `id=${id}&tipo=${tipo}&status=${encodeURIComponent(status)}`;

    fetch('/florV3/public/index.php?rota=atualizar-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: dados
    }).then(res => res.text()).then(data => {
        location.reload();
    });
}

let idImpressaoTemp = null;
let tipoImpressaoTemp = null;

function confirmarImpressao(id, tipo) {
    idImpressaoTemp = id;
    tipoImpressaoTemp = tipo;

    // Mostra o mesmo modal de operador que já usamos
    document.getElementById("responsavelSelect").value = "";
    document.getElementById("modalResponsavel").style.display = "block";

    // Só que agora ele irá para um fluxo de impressão
    // Vamos diferenciar isso colocando um flag no modal:
    document.getElementById("modalResponsavel").setAttribute("data-acao", "impressao");
}

</script>


</body>
</html>
