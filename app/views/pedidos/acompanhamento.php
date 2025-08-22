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
        .notification-box h4 { margin: 0 0 10px; }
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

        h2 { text-align: center; color: #111; margin-bottom: 20px; }

        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ddd;
        }
        th { background-color: #e4e4e4; font-weight: bold; }

        select {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 14px;
            border: none;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }
        .status-pendente { background-color: rgb(231, 86, 60); }
        .status-producao { background-color: #f39c12; }
        .status-pronto   { background-color: #3498db; }

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
        button:hover { background-color: #333; }

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
        .btn-voltar:hover { background-color: #222; }

        select option { color: black; background-color: white; }

        .filtros { text-align: center; margin-bottom: 20px; }
        .filtros input[type="date"],
        .filtros input[type="text"] {
            padding: 8px; border-radius: 8px; border: 1px solid #ccc; font-size: 14px;
        }
        .filtros input[type="text"] { width: 300px; margin-right: 10px; }
        .filtros button { padding: 8px 16px; background-color: #111; color: white; border-radius: 8px; border: none; cursor: pointer; }

        .botoes-acoes { display: flex; gap: 8px; justify-content: center; }
        .logo-img { height: 52px; max-width: 100%; object-fit: contain; display: inline-block; }

        .ver-produtos {
            text-decoration: underline;
            cursor: pointer;  /* mãozinha */
            color: #111;
            font-weight: 600;
        }

        /* Badge compacto e arredondado para o código */
        .codigo-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:72px;
            padding:4px 10px;
            border-radius:999px;
            font-weight:700;
            font-size:12px;
            line-height:1;
            color:#fff;
            letter-spacing:.3px;
            box-shadow:0 1px 0 rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.06);
        }
        /* Cores por tipo */
        .codigo-entrega  { background:#2ecc71; } /* verde */
        .codigo-retirada { background:#e74c3c; } /* vermelho */

        /* Célula do código */
        th.col-codigo, td.col-codigo { width: 120px; }
        td.col-codigo { padding: 8px; }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="container">
    <h2>📦 Acompanhamento de Pedidos</h2>

    <form method="GET" action="/florV3/public/index.php" class="filtros">
        <input type="hidden" name="rota" value="acompanhamento">
        <input type="date" name="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
        <input type="text" name="produto" placeholder="🔍 Buscar por produto" value="<?= htmlspecialchars($_GET['produto'] ?? '') ?>">
        <button type="submit">🔎 Filtrar</button>
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

    <?php if (empty($todosPedidos)): ?>
        <p style="text-align: center; font-weight: bold; color: red;">Nenhum pedido pendente para hoje.</p>
    <?php endif; ?>

    <div id="lista-pedidos">
        <table>
            <tr>
                <th class="col-codigo">Código</th>
                <th>Cliente</th>
                <th>Status</th>
                <th>Produtos</th>
                <th>Ações</th>
            </tr>

            <?php if (empty($todosPedidos)): ?>
                <p style="text-align:center; font-size:18px; margin: 20px 0; color: #777;">
                    Nenhum pedido encontrado para o filtro atual.
                </p>
            <?php endif; ?>

            <?php foreach ($todosPedidos as $pedido):
                $statusClasse = '';
                switch (strtolower($pedido['status'] ?? '')) {
                    case 'pendente':  $statusClasse = 'status-pendente'; break;
                    case 'produção':  $statusClasse = 'status-producao'; break;
                    case 'pronto':    $statusClasse = 'status-pronto';   break;
                    default:          $statusClasse = '';                break;
                }

                $id   = $pedido['id'] ?? '';
                $nome = '';
                if (!empty($pedido['nome']))          $nome = htmlspecialchars($pedido['nome']);
                elseif (!empty($pedido['remetente'])) $nome = htmlspecialchars($pedido['remetente']);
                elseif (!empty($pedido['destinatario'])) $nome = htmlspecialchars($pedido['destinatario']);

                $tipo      = htmlspecialchars($pedido['tipo'] ?? '');
                $status    = $pedido['status'] ?? '';
                $tipoLink  = strtolower(substr($tipo, 2)); // mantém sua regra original

                // NÃO exibe pedidos com status PRONTO
                if (strtolower($status) === 'pronto') continue;

                // classe de cor do código
                $classeCodigo = '';
                if (!empty($pedido['tipo'])) {
                    if (stripos($pedido['tipo'], 'entrega') !== false)   $classeCodigo = 'codigo-entrega';
                    elseif (stripos($pedido['tipo'], 'retirada') !== false) $classeCodigo = 'codigo-retirada';
                }

                // tooltip produtos
                $prodLista = array_map('trim', explode(',', $pedido['produtos'] ?? ''));
                $prodLista = array_values(array_filter($prodLista, fn($p) => $p !== ''));
                $tooltipProdutos = implode("\n", $prodLista);
            ?>
            <tr>
                <!-- CÓDIGO -->
                <td class="col-codigo">
                    <span class="codigo-badge <?= $classeCodigo ?>">
                        <?= htmlspecialchars($pedido['numero_pedido'] ?? '') ?>
                    </span>
                </td>

                <!-- CLIENTE -->
                <td>
                    <a href="/florV3/public/index.php?rota=detalhes&id=<?= $id ?>&tipo=<?= $tipoLink ?>">
                        <?= $nome ?>
                    </a>
                </td>

                <!-- STATUS -->
                <td>
                    <select class="<?= $statusClasse ?>"
                            onchange="atualizarStatus(<?= $id ?>, '<?= $tipoLink ?>', this.value)">
                        <?php
                        $opcoes = ['Pendente', 'Produção', 'Pronto', 'Cancelado'];
                        foreach ($opcoes as $opcao):
                            $selected = strtolower($status) === strtolower($opcao) ? 'selected' : '';
                            echo "<option value=\"$opcao\" $selected>$opcao</option>";
                        endforeach;
                        ?>
                    </select>
                </td>

                <!-- PRODUTOS -->
                <td>
                    <span class="ver-produtos" title="<?= htmlspecialchars($tooltipProdutos) ?>">
                        Ver produtos
                    </span>
                </td>

                <!-- AÇÕES -->
                <td>
                    <div class="botoes-acoes">
                        <button onclick="confirmarImpressao(<?= $pedido['id'] ?>, '<?= $tipoLink ?>')">🖨️ Imprimir</button>
                        <button onclick="imprimirSegundaVia(<?= $pedido['id'] ?>, '<?= $tipoLink ?>')" style="background-color:#000;">🖨️ 2ª via</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

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
        statusTemp = status;
        idTemp = id;
        tipoTemp = tipo;
        document.getElementById("responsavelSelect").value = "";
        document.getElementById("modalResponsavel").style.display = "block";
        document.getElementById("modalResponsavel").setAttribute("data-acao", "status");
        return;
    }

    if (status === "Cancelado") {
        const motivo = prompt("Informe o motivo do cancelamento:");
        if (!motivo || motivo.trim() === "") {
            alert("O motivo é obrigatório para cancelar o pedido.");
            return;
        }
        let dados = `id=${id}&tipo=${tipo}&status=${encodeURIComponent(status)}&mensagem=${encodeURIComponent(motivo)}`;
        fetch('/florV3/public/index.php?rota=atualizar-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: dados
        }).then(res => res.text()).then(() => location.reload());
        return;
    }

    enviarStatus(id, tipo, status);
}

function confirmarResponsavel() {
    const modal = document.getElementById("modalResponsavel");
    const acao = modal.getAttribute("data-acao");
    if (!acao || (acao !== "impressao" && acao !== "status")) { fecharModal(); return; }

    const responsavel = document.getElementById("responsavelSelect").value;
    if (!responsavel) { alert("Selecione um operador!"); return; }

    modal.style.display = "none";

    if (acao === "impressao" && idImpressaoTemp && tipoImpressaoTemp) {
        fetch('/florV3/public/index.php?rota=atualizar-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${idImpressaoTemp}&tipo=${tipoImpressaoTemp}&status=Produção&responsavel=${encodeURIComponent(responsavel)}`
        }).then(res => res.text())
          .then(() => window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${idImpressaoTemp}&tipo=${tipoImpressaoTemp}`, '_blank'))
          .catch(() => alert("Erro ao registrar responsável!"));
    } else if (acao === "status") {
        let dados = `id=${idTemp}&tipo=${tipoTemp}&status=${encodeURIComponent(statusTemp)}&responsavel=${encodeURIComponent(responsavel)}`;
        fetch('/florV3/public/index.php?rota=atualizar-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: dados
        }).then(res => res.text()).then(() => location.reload());
    }
}

function fecharModal() {
    document.getElementById("modalResponsavel").style.display = "none";
}

function enviarStatus(id, tipo, status) {
    let dados = `id=${id}&tipo=${tipo}&status=${encodeURIComponent(status)}`;
    fetch('/florV3/public/index.php?rota=atualizar-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: dados
    }).then(res => res.text()).then(() => location.reload());
}

let idImpressaoTemp = null;
let tipoImpressaoTemp = null;

function confirmarImpressao(id, tipo) {
    statusTemp = null; idTemp = null; tipoTemp = null;
    idImpressaoTemp = id;
    tipoImpressaoTemp = tipo;
    document.getElementById("responsavelSelect").value = "";
    document.getElementById("modalResponsavel").style.display = "block";
    document.getElementById("modalResponsavel").setAttribute("data-acao", "impressao");
}

function imprimirSegundaVia(id, tipo) {
    const modal = document.getElementById("modalResponsavel");
    modal.removeAttribute("data-acao");
    modal.style.display = "none";
    idImpressaoTemp = null; tipoImpressaoTemp = null;
    statusTemp = null; idTemp = null; tipoTemp = null;
    window.open(`/florV3/public/index.php?rota=imprimir-pedido&id=${id}&tipo=${tipo}`, '_blank');
}

// Notificações
function toggleNotificationBox() {
    const box = document.getElementById('notification-box');
    box.style.display = box.style.display === 'block' ? 'none' : 'block';
}

function carregarNotificacoesFuturas() {
    fetch('/florV3/public/index.php?rota=notificacoes-futuras')
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('notification-list');
            const badge = document.getElementById('notification-count');
            lista.innerHTML = '';
            if (data.length > 0) {
                badge.innerText = data.length;
                badge.style.display = 'inline-block';
                data.forEach(pedido => {
                    const item = document.createElement('div');
                    item.className = 'notification-item';
                    item.style.cursor = 'pointer';
                    if (!pedido.lido) item.style.backgroundColor = '#d5fcd5';
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
carregarNotificacoesFuturas();
setInterval(carregarNotificacoesFuturas, 1000);

// Busca por produto (live)
document.addEventListener("DOMContentLoaded", function () {
    const inputBusca = document.querySelector('input[name="produto"]');
    const containerPedidos = document.querySelector('#lista-pedidos');

    inputBusca.addEventListener("keyup", function () {
        const termo = this.value;
        const data = document.querySelector('input[name="data"]').value;

        fetch(`/florV3/public/index.php?rota=buscar-pedidos-produto&produto=${encodeURIComponent(termo)}&data=${encodeURIComponent(data)}`)
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data)) return;
                let html = `
<table>
<tr>
    <th class="col-codigo">Código</th>
    <th>Cliente</th>
    <th>Status</th>
    <th>Produtos</th>
    <th>Ações</th>
</tr>`;
// ===== ORDEM FIXA DE FILA NO LIVE SEARCH =====
data.sort((a, b) => {
    const oa = (a.ordem_fila ?? Number.MAX_SAFE_INTEGER);
    const ob = (b.ordem_fila ?? Number.MAX_SAFE_INTEGER);
    if (oa === ob) return (parseInt(a.id || 0, 10)) - (parseInt(b.id || 0, 10));
    return oa - ob; // crescente: entrou antes, fica acima
});

data.forEach(pedido => {
    const nome   = pedido.nome ?? '';
    const tipo   = (pedido.tipo ?? '').toLowerCase().replace('p_', '');
    const status = pedido.status ?? '';
    const numero = pedido.numero_pedido ?? '';
    const id     = pedido.id;

    const classeCodigo = tipo.includes('entrega') ? 'codigo-entrega'
                           : (tipo.includes('retirada') ? 'codigo-retirada' : '');

    const produtos = (pedido.produtos ?? '')
        .split(',').map(p => p.trim()).filter(p => p.length > 0).join('\n');

    html += `
<tr data-ordem-fila="${pedido.ordem_fila ?? ''}">
    <td class="col-codigo"><span class="codigo-badge ${classeCodigo}">${numero}</span></td>
    <td><a href="/florV3/public/index.php?rota=detalhes&id=${id}&tipo=${tipo}">${nome}</a></td>
    <td>${status}</td>
    <td><span class="ver-produtos" title="${produtos.replace(/"/g,'&quot;')}">Ver produtos</span></td>
    <td><button onclick="confirmarImpressao(${id}, '${tipo}')">🖨️ Imprimir</button></td>
</tr>`;
});
html += '</table>';
containerPedidos.innerHTML = html || '<p>Nenhum pedido encontrado.</p>';


            })
            .catch(err => console.error("Erro ao buscar pedidos:", err));
    });
});

// Auto atualização
function atualizarTabelaAcompanhamento() {
    const data = document.querySelector('input[name="data"]').value;
    fetch(`/florV3/public/index.php?rota=buscar-pedidos-dia-json&data=${encodeURIComponent(data)}`)
        .then(response => response.json())
        .then(pedidos => {
            if (!Array.isArray(pedidos)) return;
const tabela = document.querySelector('#lista-pedidos');
let html = `
<table>
<tr>
    <th class="col-codigo">Código</th>
    <th>Cliente</th>
    <th>Status</th>
    <th>Produtos</th>
    <th>Ações</th>
</tr>`;

// ===== ORDEM FIXA DE FILA NO AUTO-REFRESH =====
pedidos.sort((a, b) => {
    const oa = (a.ordem_fila ?? Number.MAX_SAFE_INTEGER);
    const ob = (b.ordem_fila ?? Number.MAX_SAFE_INTEGER);
    if (oa === ob) return (parseInt(a.id || 0, 10)) - (parseInt(b.id || 0, 10));
    return oa - ob; // crescente
});

pedidos.forEach(pedido => {
    const id    = pedido.id;
    const nome  = pedido.nome || pedido.remetente || pedido.destinatario || '';
    const tipo  = (pedido.tipo ?? '').toLowerCase().replace('p_', '');
    const numero= pedido.numero_pedido ?? '';
    const status= pedido.status ?? '';

    let statusClasse = '';
    switch ((status || '').toLowerCase()) {
        case 'pendente': statusClasse = 'status-pendente'; break;
        case 'produção': statusClasse = 'status-producao'; break;
        case 'pronto':   statusClasse = 'status-pronto';   break;
    }

    const classeCodigo = tipo.includes('entrega') ? 'codigo-entrega'
                          : (tipo.includes('retirada') ? 'codigo-retirada' : '');

    const produtosTitle = (pedido.produtos ?? '')
        .split(',').map(p => p.trim()).filter(p => p.length > 0).join('\n');

    html += `
<tr data-ordem-fila="${pedido.ordem_fila ?? ''}">
    <td class="col-codigo"><span class="codigo-badge ${classeCodigo}">${numero}</span></td>
    <td><a href="/florV3/public/index.php?rota=detalhes&id=${id}&tipo=${tipo}">${nome}</a></td>
    <td>
        <select class="${statusClasse}" onchange="atualizarStatus(${id}, '${tipo}', this.value)">
            ${['Pendente', 'Produção', 'Pronto', 'Cancelado'].map(opt => {
                const sel = opt.toLowerCase() === (status || '').toLowerCase() ? 'selected' : '';
                return `<option value="${opt}" ${sel}>${opt}</option>`;
            }).join('')}
        </select>
    </td>
    <td><span class="ver-produtos" title="${produtosTitle.replace(/"/g,'&quot;')}">Ver produtos</span></td>
    <td>
        <button onclick="confirmarImpressao(${id}, '${tipo}')">🖨️ Imprimir</button>
        <button onclick="imprimirSegundaVia(${id}, '${tipo}')" style="background-color:#000">🖨️ 2ª via</button>
    </td>
</tr>`;
});
html += '</table>';
tabela.innerHTML = html;

        })
        .catch(err => console.error("Erro ao atualizar pedidos:", err));
}
setInterval(atualizarTabelaAcompanhamento, 17000);
</script>

</body>
</html>
