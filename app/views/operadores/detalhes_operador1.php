<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Operador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            margin: 0;
            padding: 40px;
        }

        .container {
            background: white;
            max-width: 1100px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #111;
            color: white;
        }

        .filtro {
            margin-bottom: 20px;
            text-align: right;
        }

        input[type="text"] {
    padding: 6px 10px;
    font-size: 14px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 180px; /* Aumente este valor conforme necessário */
    max-width: 100%;
}


        .total-comissao {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            font-size: 16px;
        }

        .voltar {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #111;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .voltar:hover {
            background-color: #333;
        }
@media print {
    body {
        background: white !important;
        color: black !important;
    }

    .filtro, .voltar, button, input[type="text"] {
        display: none !important; /* oculta botão, filtro e campos */
    }

    table {
        font-size: 12px;
    }

    .container {
        box-shadow: none !important;
        padding: 0;
        margin: 0;
        width: 100%;
    }

    .total-comissao {
        margin-top: 20px;
        font-size: 14px;
        text-align: right;
    }

    @page {
        margin: 20mm;
    }
}

.filtro-linha {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 10px;
    flex-wrap: wrap;
}

.campo-pesquisa {
    flex: 1;
    display: flex;
    justify-content: center;
    gap: 8px;
    align-items: center;
}

.botao-imprimir {
    display: flex;
    justify-content: flex-end;
}

.botao-imprimir button {
    padding: 8px 16px;
    background-color: #111;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.botao-imprimir button:hover {
    background-color: #333;
}


    </style>
</head>
<body>
<div class="container">
    <h1>Detalhamento - <?= htmlspecialchars($responsavel) ?></h1>
<div class="filtro-linha">
    <div class="campo-pesquisa">
        <label for="filtro-produto">Filtrar produto: </label>
        <input type="text" id="filtro-produto" onkeyup="filtrarProduto()" placeholder="Digite o nome do produto...">
    </div>
    <div class="botao-imprimir">
        <button onclick="window.print()">Imprimir páginas</button>
    </div>
</div>


    <table id="tabela-produtos">
        <tr>
            <th>Data/Hora Produção</th>
            <th>Data/Hora Pronto</th>
            <th>Nº Pedido</th>
            <th>Produto</th>
            <th>Valor</th>
            <th>%</th>
            <th>Valor Comissão</th>
        </tr>

        <?php
        $totalComissao = 0;
        foreach ($registros as $reg) {
            // Divide os produtos (esperado formato: "2x Maracujá (obs), 1x Caixa (obs)")
            $lista = explode(',', $reg['produtos']);
            foreach ($lista as $item) {
                $item = trim($item);
                if (!$item) continue;

// Extrair nome e quantidade corretamente
preg_match('/^(\d+)\s*x\s*([^\(]+)(?:\((.*?)\))?$/i', $item, $match);
$qtd = isset($match[1]) ? (int) $match[1] : 1;
$nomeProduto = isset($match[2]) ? trim($match[2]) : trim($item);

// Adicionalmente remova espaços extras
$nomeProduto = preg_replace('/\s+/', ' ', $nomeProduto);

// Debug
echo "<!-- Buscando produto corrigido: '$nomeProduto' -->";

                

                // Buscar valor e porcentagem do produto
                $pdo = Database::conectar();
$nomeLimpo = mb_strtolower(trim($nomeProduto));
$stmt = $pdo->prepare("SELECT valor, porcentagem FROM produtos WHERE LOWER(TRIM(nome)) = :nome LIMIT 1");
$stmt->execute([':nome' => $nomeLimpo]);
$dadosProduto = $stmt->fetch(PDO::FETCH_ASSOC);

                $valorUnit = $dadosProduto['valor'] ?? 0;
                $porcentagem = $dadosProduto['porcentagem'] ?? 0;
                $valorComissao = ($valorUnit * $porcentagem / 100) * $qtd;
                $totalComissao += $valorComissao;
                ?>

                <tr>
                    <td><?= htmlspecialchars($reg['data_producao']) ?></td>
                    <td><?= htmlspecialchars($reg['data_pronto']) ?></td>
                    <td><?= htmlspecialchars($reg['numero_pedido']) ?></td>
                    <td><?= htmlspecialchars($nomeProduto) ?></td>
                    <td>R$ <?= number_format($valorUnit, 2, ',', '.') ?></td>
                    <td><?= $porcentagem ?>%</td>
                    <td>R$ <?= number_format($valorComissao, 2, ',', '.') ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>

    <div class="total-comissao">
        Total da Comissão: <strong>R$ <?= number_format($totalComissao, 2, ',', '.') ?></strong>
    </div>

    <a href="/florV3/public/index.php?rota=relatorio-operadores&data_inicio=<?= urlencode($dataInicio) ?>&data_fim=<?= urlencode($dataFim) ?>" class="voltar">← Voltar ao Relatório</a>
</div>

<script>
function filtrarProduto() {
    const input = document.getElementById("filtro-produto");
    const filtro = input.value.toLowerCase();
    const linhas = document.querySelectorAll("#tabela-produtos tr:not(:first-child)");

    let total = 0;

    linhas.forEach(linha => {
        const produto = linha.cells[3].textContent.toLowerCase();
        const comissaoText = linha.cells[6].textContent.replace('R$', '').replace('.', '').replace(',', '.').trim();
        const comissao = parseFloat(comissaoText) || 0;

        if (produto.includes(filtro)) {
            linha.style.display = "";
            total += comissao;
        } else {
            linha.style.display = "none";
        }
    });

    const totalFormatado = total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    document.querySelector('.total-comissao strong').textContent = totalFormatado;
}
</script>

</body>
</html>
