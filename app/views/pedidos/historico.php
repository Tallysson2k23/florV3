<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Pedidos - Flor de Cheiro</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

.top-bar {
    background-color: #111;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
}


.logo-img {
    height: 52px;
    object-fit: contain;
    max-width: 100%;
    display: inline-block;
}


        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
            font-size: 24px;
        }

        form {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        button[type="submit"], #mostrar-todos, .btn-voltar {
            background-color: #111;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover, .btn-voltar:hover {
            background-color: #333;
        }

        .btn-voltar {
            display: block;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 15px;
        }

        th, td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
            color: #333;
        }

        tr:hover {
            background-color: #fafafa;
        }

        .oculto { display: none; }

        #sugestoes {
            list-style: none;
            padding: 0;
            margin-top: 10px;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        #sugestoes li {
            background: #f1f1f1;
            margin-bottom: 4px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
        }

        #sugestoes li:hover {
            background: #e0e0e0;
        }

        .paginacao {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            font-size: 15px;
        }

        .paginacao a {
            text-decoration: none;
            color: #111;
            font-weight: bold;
        }

        .paginacao span {
            color: #555;
        }

        .rodape {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
        }

        .botoes-rodape {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .botoes-rodape button,
        .botoes-rodape .btn-voltar {
            font-size: 14px;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .botoes-rodape button {
            background: #111;
            color: white;
            border: none;
        }

        .botoes-rodape button:hover {
            background: #333;
        }

        .botoes-rodape .btn-voltar {
            background: transparent;
            border: 1px solid #111;
            color: #111;
            font-weight: 500;
        }

        .botoes-rodape .btn-voltar:hover {
            background: #111;
            color: white;
        }

        
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="container">
    <h2>Histórico de Pedidos</h2>

<form method="get" action="/florV3/public/index.php" style="justify-content: center; gap: 10px; flex-wrap: wrap;">
    <input type="hidden" name="rota" value="historico">

    <input type="text" id="campo-busca" name="busca" placeholder="Buscar por nome ou número do pedido" value="<?= $_GET['busca'] ?? '' ?>" style="padding: 10px; width: 260px; border-radius: 8px; border: 1px solid #ccc; font-size: 15px;">

    <input type="date" name="data" value="<?= $_GET['data'] ?? date('Y-m-d') ?>" style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; font-size: 15px;">

    <select name="mes" style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; font-size: 15px;">
        <option value="">Ver mês inteiro</option>
        <?php
        $anoAtual = date('Y');
        for ($i = 1; $i <= 12; $i++):
            $selected = (isset($_GET['mes']) && $_GET['mes'] == $i) ? 'selected' : '';
            $mesFormatado = str_pad($i, 2, '0', STR_PAD_LEFT);
        ?>
            <option value="<?= $i ?>" <?= $selected ?>><?= $mesFormatado ?>/<?= $anoAtual ?></option>
        <?php endfor; ?>
    </select>

    <button type="submit" style="background-color: #111; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 15px; cursor: pointer;">Filtrar</button>

    <a href="/florV3/public/index.php?rota=historico" style="background-color: #111; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-size: 15px; display: inline-block;">Limpar</a>
</form>



    <ul id="sugestoes"></ul>

    <?php if (isset($resultados) && count($resultados) > 0): ?>

        <table>
            <tr>
                <th>Tipo</th>
                <th>Nº Pedido</th>
                <th>Nome / Remetente</th>
            </tr>

            <?php foreach ($resultados as $index => $pedido): ?>
                <tr class="pedido-linha">
                    <td><?= htmlspecialchars($pedido['tipo']) ?></td>
                    <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
                    <td>
                        <a href="/florV3/public/index.php?rota=detalhes&id=<?= $pedido['id'] ?>&tipo=<?= strtolower(substr($pedido['tipo'], 2)) ?>">
                            <?= htmlspecialchars($pedido['nome']) ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="rodape">
            <div class="paginacao">
                <?php if ($pagina > 1): ?>
                    <a href="?rota=historico&pagina=1<?= $busca ? '&busca=' . urlencode($busca) : '' ?>">⏮️</a>
                    <a href="?rota=historico&pagina=<?= $pagina - 1 ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">◀️</a>
                <?php endif; ?>

                <span><?= $inicio + 1 ?> – <?= min($inicio + $porPagina, $total) ?> / <?= $total ?></span>

                <?php if ($pagina < $totalPaginas): ?>
                    <a href="?rota=historico&pagina=<?= $pagina + 1 ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">▶️</a>
                    <a href="?rota=historico&pagina=<?= $totalPaginas ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">⏭️</a>
                <?php endif; ?>
            </div>

            <div class="botoes-rodape">
                <?php if (count($resultados) > 5): ?>
                    <button id="mostrar-todos"> Ver todos os pedidos</button>
                <?php endif; ?>
                <a href="/florV3/public/index.php?rota=painel" class="btn-voltar">⬅ Voltar</a>
            </div>
        </div>

    <?php elseif (isset($_GET['busca'])): ?>
        <p style="text-align:center;">Nenhum resultado encontrado.</p>
    <?php endif; ?>
</div>

<script>
const campoBusca = document.getElementById('campo-busca');
const sugestoes = document.getElementById('sugestoes');

const dados = <?= json_encode(
    array_map(fn($p) => [
        'id' => $p['id'],
        'nome' => $p['nome'],
        'numero' => $p['numero_pedido'],
        'tipo' => strtolower(substr($p['tipo'], 2))
    ], $todos)
) ?>;




campoBusca.addEventListener('input', function () {
    const valor = this.value.toLowerCase();
    sugestoes.innerHTML = '';
    if (valor.length === 0) return;

const filtrados = dados.filter(p =>
    (p.nome + ' ' + p.numero).toLowerCase().includes(valor)
).slice(0, 5);

filtrados.forEach(p => {
    const li = document.createElement('li');
    li.textContent = `${p.nome} - ${p.numero}`;

li.onclick = () => {
    window.location.href =
        `/florV3/public/index.php?rota=detalhes&id=${p.id}&tipo=${p.tipo}`;
};


    sugestoes.appendChild(li);
});
});


const btnMostrar = document.getElementById('mostrar-todos');
if (btnMostrar) {
    btnMostrar.addEventListener('click', function () {
        document.querySelectorAll('.pedido-linha.oculto').forEach(row => row.classList.remove('oculto'));
        btnMostrar.style.display = 'none';
    });
}
</script>

</body>
</html>
