<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}
$usuarioTipo = $_SESSION['usuario_tipo'] ?? 'colaborador';

require_once __DIR__ . '/../../helpers/verifica_login.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Acompanhamento do Atendente - Flor de Cheiro</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f4f6;
    padding: 0px;
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h1 {
    color: #111;
    font-size: 24px;
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

form {
    text-align: center;
    margin-bottom: 20px;
}

input[type="date"] {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-top: 10px;
}

.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 90%;
    max-width: 1100px;
    margin-bottom: 30px;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

table th {
    background: #f8f9fa;
    font-weight: bold;
    color: #333;
    font-size: 14px;
    text-transform: uppercase;
}

.status-select {
    padding: 8px 16px;
    font-weight: bold;
    color: white;
    border: none;
    border-radius: 20px;
    appearance: none;
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D'10'%20height%3D'6'%20viewBox%3D'0%200%2010%206'%20fill%3D'none'%20xmlns%3D'http%3A//www.w3.org/2000/svg'%3E%3Cpath%20d%3D'M1%201L5%205L9%201'%20stroke%3D'white'%20stroke-width%3D'2'%20stroke-linecap%3D'round'%20stroke-linejoin%3D'round'/%3E%3C/svg%3E");
    font-size: 14px;
    min-width: 120px;
    cursor: pointer;
    text-align: center;
}

.status-select-pronto {
    background-color: #f59e0b !important;
}

.status-select-entregue {
    background-color: #16a34a !important;
}
.status-select-retorno {
    background-color: #dc2626 !important; /* Vermelho forte */
}
.status-select-cancelado {
    background-color: #6b7280 !important; /* Cinza escuro */
}
.fundo-cancelado {
    background-color: #130101ff;
    opacity: 0.8;
}



h2 {
    font-size: 18px;
    margin-top: 10px;
    margin-bottom: 10px;
    text-align: center;
    color: #222;
    position: relative;
    font-weight: bold;
}

h2::before {
    content: "📋";
    margin-right: 8px;
}

.topo {
    width: 100%;
    background: #111;
    color: white;
    padding: 20px 100px;
    font-family: "Brush Script MT", cursive;
    font-size: 28px;
    text-align: center;
    margin-bottom: 50px;
}
</style>
</head>
<body>
<div class="topo">
    Flor de Cheiro
</div>

<form method="get" action="index.php">
    <input type="hidden" name="rota" value="acompanhamento-atendente">
    <label for="data">Selecionar Data:</label>
    <input type="date" name="data" id="data" value="<?= htmlspecialchars($_GET['data'] ?? date('Y-m-d')) ?>" onchange="this.form.submit()">
</form>

<h1>Acompanhamento do Atendente</h1>

<div class="table-card">
<h2>Pedidos</h2>
<table id="tabela-atendente">
    <thead>
        <tr>
            <th>Nº Pedido</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <!-- Aqui o JavaScript vai preencher automaticamente -->
    </tbody>
</table>

</div>

<a href="/florV3/public/index.php?rota=painel" class="voltar-simples">← Voltar</a>

<style>
.voltar-simples {
    display: inline-block;
    padding: 8px 20px;
    background-color: #111;
    color: #fff;
    font-size: 14px;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s;
}
.voltar-simples:hover {
    background-color: #333;
}
</style>

<script>
const tipoUsuario = '<?= $usuarioTipo ?>';

function atualizarStatus(novoStatus, id, tipo) {
    if (novoStatus === 'Cancelado') {
        if (tipoUsuario !== 'admin') {
            alert("Você não tem permissão para cancelar este pedido.");
            // Força recarregar a página para voltar o select para o valor anterior
            window.location.reload();
            return;
        }

        const motivo = prompt("Informe o motivo do cancelamento:");
        if (!motivo || motivo.trim() === "") {
            alert("Motivo obrigatório para cancelar o pedido!");
            return;
        }

        const formData = new FormData();
        formData.append('id', id);
        formData.append('tipo', tipo);
        formData.append('status', novoStatus);
        formData.append('mensagem', motivo.trim());

        enviarStatus(formData, novoStatus, id);
        return;
    }

    // Continua normalmente para os demais status
    const formData = new FormData();
    formData.append('id', id);
    formData.append('tipo', tipo);
    formData.append('status', novoStatus);

    if (novoStatus === 'Entregue') {
        const confirma = confirm("Deseja registrar uma mensagem para este pedido?");
        if (confirma) {
            const msg = prompt("Digite a mensagem:");
            if (msg && msg.trim() !== "") {
                formData.append('mensagem', msg.trim());
            }
        }
    }

    if (novoStatus === 'Retorno') {
        const msg = prompt("Digite o motivo do retorno:");
        if (!msg || msg.trim() === "") {
            alert("Motivo obrigatório para retorno!");
            return;
        }
        formData.append('mensagem', msg.trim());
    }

    enviarStatus(formData, novoStatus, id);
}

function enviarStatus(formData, novoStatus, id) {
    fetch('/florV3/public/index.php?rota=atualizar-status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        if (result === 'OK') {
            const selectElement = document.querySelector(`select[onchange*="atualizarStatus(this.value, ${id},"]`);
            selectElement.classList.remove(
                'status-select-pronto',
                'status-select-entregue',
                'status-select-retorno',
                'status-select-cancelado'
            );

            if (novoStatus === 'Pronto') {
                selectElement.classList.add('status-select-pronto');
            } else if (novoStatus === 'Entregue') {
                selectElement.classList.add('status-select-entregue');
            } else if (novoStatus === 'Retorno') {
                selectElement.classList.add('status-select-retorno');
            } else if (novoStatus === 'Cancelado') {
                selectElement.classList.add('status-select-cancelado');
            }

            // Re-renderiza
            selectElement.style.display = 'none';
            setTimeout(() => {
                selectElement.style.display = 'inline-block';
            }, 10);
        } else {
            alert(result || 'Erro ao atualizar status.');
        }
    });
}
</script>
<script>
function atualizarTabelaAtendente() {
    const data = document.querySelector('input[name="data"]').value;

    fetch(`/florV3/public/index.php?rota=buscar-pedidos-atendente-json&data=${encodeURIComponent(data)}`)
        .then(response => response.json())
        .then(pedidos => {
            const tbody = document.querySelector('#tabela-atendente tbody');
            let html = '';

            pedidos.forEach(pedido => {
                const id = pedido.id;
                const nome = pedido.remetente ?? pedido.nome ?? '';
                const tipo = (pedido.tipo === '1-Entrega' || pedido.tipo?.toLowerCase() === 'entrega') ? 'entrega' : 'retirada';
                const tipoLabel = tipo === 'entrega' ? 'Entrega' : 'Retirada';
                const numero = pedido.numero_pedido ?? '';
                const status = pedido.status ?? '';
                const statusLower = status.toLowerCase();

                let classeStatus = '';
                switch (statusLower) {
                    case 'pronto': classeStatus = 'status-select-pronto'; break;
                    case 'entregue': classeStatus = 'status-select-entregue'; break;
                    case 'retorno': classeStatus = 'status-select-retorno'; break;
                    case 'cancelado': classeStatus = 'status-select-cancelado'; break;
                }

                html += `
                    <tr>
                        <td>${numero}</td>
                        <td>${nome}</td>
                        <td>${tipoLabel}</td>
                        <td>
                            <select onchange="atualizarStatus(this.value, ${id}, '${tipo}')" class="status-select ${classeStatus}">
                                <option value="Pronto" ${status === 'Pronto' ? 'selected' : ''}>Pronto</option>
                                <option value="Entregue" ${status === 'Entregue' ? 'selected' : ''}>Entregue</option>
                                <option value="Retorno" ${status === 'Retorno' ? 'selected' : ''}>Retorno</option>
                                <option value="Cancelado" ${status === 'Cancelado' ? 'selected' : ''}>Cancelado</option>
                            </select>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        })
        .catch(err => console.error('Erro ao atualizar pedidos do atendente:', err));
}

setInterval(atualizarTabelaAtendente, 7000); // atualiza a cada 7 segundos
atualizarTabelaAtendente(); // atualiza imediatamente ao abrir a página


</script>

<!-- 6,0 -->


</body>
</html>



