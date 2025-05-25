<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /florV3/public/index.php?rota=login');
    exit;
}
$usuarioNome = $_SESSION['usuario_nome'] ?? 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Flor de Cheiro - Painel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        /* Cabeçalho */
        .topo {
    background: #111;
    color: white;
    padding: 10px 20px;
    position: relative;  /* importante para posicionar o botão e h1 */
    height: 60px;
}

.topo h1 {
    font-family: "Brush Script MT", cursive;
    font-size: 28px;
    margin: 0;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: 50%;
    transform: translate(-50%, -50%);
}


        .menu-btn {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 24px;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
}


        /* Menu lateral */
        .menu-lateral {
            position: fixed;
            top: 0;
            left: -295px;
            width: 250px;
            height: 100%;
            background: #111;
            color: white;
            padding: 20px;
            transition: left 0.3s ease;
            z-index: 999;
        }

        .menu-lateral.ativo {
            left: 0;
        }

        .menu-lateral .fechar {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }

        .status-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin: 20px;
        }

        .coluna {
            width: 250px;
            flex: 0 0 auto;
            background: #eee;
            padding: 15px;
            border-radius: 8px;
        }

        .coluna h3 {
            text-align: center;
            margin-top: 0;
        }

        .pedido {
            background: white;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .ver-toggle {
            text-align: center;
            margin-top: 5px;
            cursor: pointer;
            font-size: 12px;
            color: blue;
        }

        .coluna.pendente { background-color: #f28b82; }
        .coluna.producao { background-color: #fbbc04; }
        .coluna.pronto   { background-color: #a7cdfa; }

        .botoes {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .botao-acao {
            background: white;
            border-radius: 10px;
            padding: 15px;
            width: 180px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .botao-acao h4 {
            margin: 0 0 10px;
        }

        .botao-acao button {
            background: green;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

       .menu-lateral {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.menu-conteudo {
    margin-top: 20px;
}

.sair-fixado {
    margin-top: 470px; /** para a distancia do botao sair */
    padding-top: 0;
    padding-left: 70px;
}

.btn-sair {
    width: 50%;
    padding: 5px;
    background-color: #B22222;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}


        .oculto { display: none; }
    </style>
</head>
<body>

<div class="topo">
    <button class="menu-btn" onclick="abrirMenu()">☰</button>
    <h1>𝓕𝓵𝓸𝓻 𝓭𝓮 𝓒𝓱𝓮𝓲𝓻𝓸</h1>
</div>
    <!-- MENU <br><br>  🙋🏻‍♂️ Olá -->


<div class="menu-lateral" id="menuLateral">
    <span class="fechar" onclick="fecharMenu()">&times;</span>

    <div class="perfil">
        
        <span><br><br>  🙋🏻‍♂️ Olá <strong><?= htmlspecialchars($usuarioNome) ?></strong></span>
    </div>

    <div class="menu-conteudo">
        <!-- Aqui você pode colocar links adicionais no futuro -->
    </div>

    <div class="sair-fixado">
        <a href="/florV3/public/index.php?rota=logout">
            <button class="btn-sair">🚪 Sair</button>
        </a>
    </div>
</div>

<br><br><br>
<h2 style="text-align: center;">Status dos Pedidos</h2>

<div class="status-container">
    <?php
    $cores = ['Pendente' => 'pendente', 'Produção' => 'producao', 'Pronto' => 'pronto'];
    foreach ($agrupados as $status => $lista):
        $classe = $cores[$status];
    ?>
    <div class="coluna <?= $classe ?>">
        <h3><?= $status ?></h3>
        <?php foreach ($lista as $i => $pedido): ?>
            <div class="pedido <?= $i >= 4 ? 'oculto' : '' ?>">
                <strong>#<?= $pedido['numero_pedido'] ?></strong><br>
                <?= htmlspecialchars($pedido['nome']) ?><br>
                <?= htmlspecialchars($pedido['produto'] ?? '') ?> - <?= date('d/m', strtotime($pedido['data_abertura'])) ?> às <?= substr($pedido['hora'], 0, 5) ?>
            </div>
        <?php endforeach; ?>
        <?php if (count($lista) > 4): ?>
            <div class="ver-toggle" onclick="togglePedidos(this)">⬇ Ver mais</div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<div class="botoes">
    <div class="botao-acao">
        <h4>Cadastrar Pedido</h4>
        <a href="/florV3/public/index.php?rota=escolher-tipo"><button>Acessar</button></a>
    </div>

    <div class="botao-acao">
    <h4>Cadastrar Produto</h4>
    <a href="/florV3/public/index.php?rota=cadastrar-produto">
        <button>Acessar</button>
    </a>
</div>

<div class="botao-acao">
    <h4>Lista de Produtos</h4>
    <a href="/florV3/public/index.php?rota=lista-produtos">
        <button>Acessar</button>
    </a>
</div>

    <div class="botao-acao">
        <h4>Ver Histórico</h4>
        <a href="/florV3/public/index.php?rota=historico"><button>Acessar</button></a>
    </div>
    <div class="botao-acao">
        <h4>Acompanhar Pedidos</h4>
        <a href="/florV3/public/index.php?rota=acompanhamento"><button>Acessar</button></a>
    </div>
    <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
    <div class="botao-acao">
        <h4>Gerenciar Usuários</h4>
        <a href="/florV3/public/index.php?rota=usuarios"><button>Acessar</button></a>
    </div>
    <div class="botao-acao">
    <h4>Retiradas</h4>
    <a href="/florV3/public/index.php?rota=retiradas"><button>Acessar</button></a>
</div>
<div class="botao-acao">
    <h4>Cadastrar Vendedor</h4>
    <a href="/florV3/public/index.php?rota=cadastrar-vendedor"><button>Acessar</button></a>
</div>

<div class="botao-acao">
    <h4>Lista de Vendedores</h4>
    <a href="/florV3/public/index.php?rota=lista-vendedores"><button>Acessar</button></a>
</div>


    
    <?php endif; ?>
<script>
function togglePedidos(botao) {
    const coluna = botao.parentElement;
    const ocultos = coluna.querySelectorAll('.pedido.oculto');
    const mostrandoMais = botao.dataset.expandido === "true";

    if (mostrandoMais) {
        coluna.querySelectorAll('.pedido').forEach((pedido, i) => {
            if (i >= 4) pedido.classList.add('oculto');
        });
        botao.textContent = '⬇ Ver mais';
        botao.dataset.expandido = "false";
    } else {
        ocultos.forEach(p => p.classList.remove('oculto'));
        botao.textContent = '⬆ Ver menos';
        botao.dataset.expandido = "true";
    }
}

function abrirMenu() {
    document.getElementById('menuLateral').classList.add('ativo');
}

function fecharMenu() {
    document.getElementById('menuLateral').classList.remove('ativo');
}
</script>

</body>
</html>
