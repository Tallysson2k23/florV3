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


.notification-wrapper {
    position: absolute;
    top: 15px;
    right: 30px;
    cursor: pointer;
    z-index: 10000;
}

.notification-bell {
    font-size: 26px;
    color: white;
    position: relative;
}

.notification-badge {
    position: absolute;
    top: -6px;
    right: -10px;
    background: red;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
}

.notification-box {
    display: none;
    position: absolute;
    top: 35px;
    right: 0;
    background: white;
    color: black;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    padding: 10px;
}

.notification-box h4 {
    margin: 0 0 10px;
}

.notification-item {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
    font-size: 14px;
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

        .botoes-acoes {
    display: flex;
    gap: 8px;
    justify-content: center;
}

    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="container">
    <h2>📦 Acompanhamento de Pedidos</h2>
    <form method="GET" action="/florV3/public/index.php" class="filtros">
    <input type="hidden" name="rota" value="acompanhamento">
    <input type="date" name="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
</form>


<div class="notification-wrapper" onclick="toggleNotificationBox()">
    <div class="notification-bell">🔔
        <span class="notification-badge" id="notification-count" style="display:none;">0</span>
    </div>
    <div class="notification-box" id="notification-box">
        <h4>Pedidos Futuros</h4>
        <div id="notification-list"></div>
    </div>
</div>


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


    <!-- Filtro
    <form method="GET" action="/florV3/public/index.php" class="filtros">
        <input type="hidden" name="rota" value="acompanhamento">
        <input type="date" name="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
        <input type="text" name="busca" placeholder="Buscar por nome ou número do pedido" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
        <button type="submit">🔍 Buscar</button>
    </form> -->

    <!-- Tabela -->
     <?php if (empty($todosPedidos)): ?>
    <p style="text-align: center; font-weight: bold; color: red;">Nenhum pedido pendente para hoje.</p>
<?php endif; ?>

    <table>
        <tr>
            <th>Código</th>
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
            $nome = '';

if (!empty($pedido['nome'])) {
    $nome = htmlspecialchars($pedido['nome']);
} elseif (!empty($pedido['remetente'])) {
    $nome = htmlspecialchars($pedido['remetente']);
} elseif (!empty($pedido['destinatario'])) {
    $nome = htmlspecialchars($pedido['destinatario']);
}

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
           <td><?= htmlspecialchars($pedido['numero_pedido'] ?? '') ?></td>
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
                <div>
                <button onclick="confirmarImpressao(<?= $pedido['id'] ?>, '<?= $tipoLink ?>')">🖨️ Imprimir</button>
                <button onclick="imprimirSegundaVia(<?= $pedido['id'] ?>, '<?= $tipoLink ?>')" style="background-color: #000000;"> 🖨️  2ª via </button>
                </div>

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
        document.getElementById("modalResponsavel").setAttribute("data-acao", "status"); // <<< ESSENCIAL
        return;
    }

    // Se não for produção, envia normalmente
    enviarStatus(id, tipo, status);
}


function confirmarResponsavel() {
    const modal = document.getElementById("modalResponsavel");
    const acao = modal.getAttribute("data-acao");

    if (!acao || (acao !== "impressao" && acao !== "status")) {
        fecharModal();
        return;
    }

    const responsavel = document.getElementById("responsavelSelect").value;
    if (!responsavel) {
        alert("Selecione um operador!");
        return;
    }

    modal.style.display = "none";

    if (acao === "impressao" && idImpressaoTemp && tipoImpressaoTemp) {
        // Impressão principal (com alteração de status)
        fetch('/florV3/public/index.php?rota=atualizar-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${idImpressaoTemp}&tipo=${tipoImpressaoTemp}&status=Produção&responsavel=${encodeURIComponent(responsavel)}`
        })
        .then(res => res.text())
        .then(data => {
            window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${idImpressaoTemp}&tipo=${tipoImpressaoTemp}`, '_blank');
        })
        .catch(err => alert("Erro ao registrar responsável!"));
    } else if (acao === "status") {
        // Alteração de status para Produção
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

        // Limpa qualquer dado antigo
    statusTemp = null;
    idTemp = null;
    tipoTemp = null;

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
<script>
function imprimirSegundaVia(id, tipo) {
    // Garante que a ação anterior não interfira
    const modal = document.getElementById("modalResponsavel");
    modal.removeAttribute("data-acao"); // remove qualquer ação anterior
    modal.style.display = "none";

    // Zera variáveis temporárias
    idImpressaoTemp = null;
    tipoImpressaoTemp = null;
    statusTemp = null;
    idTemp = null;
    tipoTemp = null;

    // Vai direto pra impressão sem alterar nada no banco
    window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${id}&tipo=${tipo}`, '_blank');
}


</script>

<!--
<script>
function imprimirPedido(id, tipo) {
    // Altera status para Produção
    fetch('index.php?rota=atualizar-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            id: id,
            tipo: tipo,
            status: 'Produção'
        })
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'OK') {
            // Redireciona para a página de impressão depois de alterar o status
            window.location.href = `index.php?rota=imprimir-pedido&id=${id}&tipo=${tipo}`;
        } else {
            alert('Erro ao alterar status');
        }
    })
    .catch(err => {
        alert('Erro ao conectar com o servidor');
        console.error(err);
    });
}
</script> -->

<script>
// Mostra/esconde a caixa de notificação
function toggleNotificationBox() {
    const box = document.getElementById('notification-box');
    box.style.display = box.style.display === 'block' ? 'none' : 'block';
}

// Requisição para buscar pedidos futuros
function carregarNotificacoesFuturas() {
    fetch('/florV3/public/index.php?rota=notificacoes-futuras')
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('notification-list');
            const badge = document.getElementById('notification-count');

            lista.innerHTML = ''; // Limpa notificações anteriores

            if (data.length > 0) {
                badge.innerText = data.length;
                badge.style.display = 'inline-block';

             data.forEach(pedido => {
    const item = document.createElement('div');
    item.className = 'notification-item';
    item.style.cursor = 'pointer';

    if (!pedido.lido) {
        item.style.backgroundColor = '#d5fcd5'; // Verde clarinho só se ainda NÃO foi lido
    }

    item.innerHTML = `<strong>${pedido.nome}</strong><br>
        Produto: ${pedido.produto}<br>
        Tipo: ${pedido.tipo}<br>
        Data: ${pedido.data}`;

    item.onclick = () => {
        const tipo = pedido.tipo.toLowerCase();
        const id = pedido.id;

        fetch('/florV3/public/index.php?rota=marcar-notificacao-lida', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}&tipo=${tipo}`
        }).then(() => {
            window.location.href = `/florV3/public/index.php?rota=detalhes&id=${id}&tipo=${tipo}`;
        });
    };

    lista.appendChild(item);
});



            } else {
                badge.style.display = 'none';
                lista.innerHTML = '<p>Sem notificações futuras.</p>';
            }
        });
}

// Chamar ao carregar a página e a cada 1 segundos
carregarNotificacoesFuturas();
setInterval(carregarNotificacoesFuturas, 1000);
</script>
<script>
function abrirDetalhesPedido(id, tipo) {
    // Marcar como lido
    fetch('/florV3/public/index.php?rota=marcar-notificacao-lida', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&tipo=${tipo}`
    }).then(response => {
        // Redirecionar após marcar como lido
        window.location.href = `/florV3/public/index.php?rota=detalhes&id=${id}&tipo=${tipo}`;
    }).catch(err => {
        alert("Erro ao marcar como lido");
    });
}
</script>


</body>
</html>
