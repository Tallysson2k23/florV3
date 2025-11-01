<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV2/public/index.php?rota=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5"> <!-- Esta linha atualiza a página a cada 50 segundos -->
    <title>Lista de Pedidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e5e5e5;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #c0c0c0;
            padding: 10px;
        }

        th {
            background-color: #b0b0b0;
            color: #fff;
            text-align: center;
        }

        td {
            text-align: center;
        }

        td.cliente {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        tr:nth-child(odd) {
            background-color: #d9d9d9;
        }

        .btn-voltar {
            text-align: center;
            margin-top: 20px;
        }

        .btn-voltar a {
            background-color: #666;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            display: inline-block;
        }

        .btn-voltar a:hover {
            background-color: #444;
        }

        .status-select {
            padding: 4px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            border: none;
        }

        .status-pendente   { background-color: #e74c3c; color: #fff; }
        .status-producao   { background-color: #f39c12; color: #fff; }
        .status-pronto     { background-color: #2980b9; color: #fff; }
        .status-a-caminho  { background-color: #8e44ad; color: #fff; }
        .status-entregue   { background-color:rgb(26, 170, 86); color: #fff; }
    </style>
</head>
<body>

<h2>Lista de Pedidos</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Complemento</th>
            <th>Observação</th>
            <th>Status</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody id="pedido-body">
        <?php foreach ($pedidos as $pedido): ?>
            
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td class="cliente"><?= htmlspecialchars($pedido['nome']) ?></td>
                <td><?= htmlspecialchars($pedido['tipo']) ?></td>
                <td><?= htmlspecialchars($pedido['produto']) ?></td>
                <td><?= htmlspecialchars($pedido['quantidade']) ?></td>
                <td><?= htmlspecialchars($pedido['complemento']) ?></td>
                <td><?= htmlspecialchars($pedido['obs']) ?></td>
                <td>
                    <select class="status-select" data-id="<?= $pedido['id'] ?>">
                        <?php
                        $statusOptions = ['Pendente', 'Produção', 'Pronto', 'A Caminho', 'Entregue'];
                        foreach ($statusOptions as $opt):
                            $selected = ($pedido['status'] ?? 'Pendente') === $opt ? 'selected' : '';
                            echo "<option value=\"$opt\" $selected>$opt</option>";
                        endforeach;
                        ?>
                    </select>
                </td>
                <td><?= date('d/m/Y', strtotime($pedido['data_abertura'])) ?></td>
            </tr>
                

        <?php endforeach; ?>
    </tbody>
</table>

<div class="btn-voltar">
    <a href="index.php?rota=painel">← Voltar ao Painel</a>
</div>

<script>
function aplicarClasseStatus(select, status) {
    const classes = [
        'status-pendente', 'status-producao',
        'status-pronto', 'status-a-caminho', 'status-entregue'
    ];
    select.classList.remove(...classes);

    switch (status.toLowerCase()) {
        case 'pendente':    select.classList.add('status-pendente'); break;
        case 'produção':    select.classList.add('status-producao'); break;
        case 'pronto':      select.classList.add('status-pronto'); break;
        case 'a caminho':   select.classList.add('status-a-caminho'); break;
        case 'entregue':    select.classList.add('status-entregue'); break;
    }
}

function adicionarEventoStatus(select) {
    aplicarClasseStatus(select, select.value);

    select.addEventListener('change', function () {
        const pedidoId = this.getAttribute('data-id');
        const novoStatus = this.value;

        fetch('index.php?rota=atualizar-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${pedidoId}&status=${encodeURIComponent(novoStatus)}`
        }).then(() => aplicarClasseStatus(this, novoStatus));
    });
}

function carregarPedidos() {
    fetch('index.php?rota=lista-pedidos-json')
        .then(response => response.json())
        .then(pedidos => {
            const tbody = document.getElementById('pedido-body');
            tbody.innerHTML = ''; // Limpa a tabela

            pedidos.forEach(pedido => {
                const status = pedido.status ? pedido.status.trim() : 'Pendente';
                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td>${pedido.id}</td>
                    <td class="cliente">${pedido.nome}</td>
                    <td>${pedido.tipo}</td>
                    <td>${pedido.produto}</td>
                    <td>${pedido.quantidade}</td>
                    <td>${pedido.complemento}</td>
                    <td>${pedido.obs}</td>
                    <td>
                        <select class="status-select" data-id="${pedido.id}">
                            <option value="Pendente"   ${status === 'Pendente' ? 'selected' : ''}>Pendente</option>
                            <option value="Produção"   ${status === 'Produção' ? 'selected' : ''}>Produção</option>
                            <option value="Pronto"     ${status === 'Pronto' ? 'selected' : ''}>Pronto</option>
                            <option value="A Caminho"  ${status === 'A Caminho' ? 'selected' : ''}>A Caminho</option>
                            <option value="Entregue"   ${status === 'Entregue' ? 'selected' : ''}>Entregue</option>
                        </select>
                    </td>
                    <td>${new Date(pedido.data_abertura).toLocaleDateString()}</td>
                `;

                
                tbody.appendChild(tr);



                const select = tr.querySelector('.status-select');
                adicionarEventoStatus(select);
            });
        })
        .catch(error => console.error('Erro ao carregar pedidos:', error));
}

// Aplica estilo aos selects já existentes (caso exista conteúdo carregado inicialmente)
document.querySelectorAll('.status-select').forEach(adicionarEventoStatus);

// Atualiza automaticamente a cada 5 segundos
carregarPedidos();
setInterval(carregarPedidos, 5000);
</script>



</body>
</html>
