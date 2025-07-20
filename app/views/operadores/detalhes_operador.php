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
    </style>
</head>
<body>
<div class="container">
    <h1>Detalhamento - <?= htmlspecialchars($responsavel) ?></h1>

    <div class="filtro">
        <label for="filtro-produto">Filtrar produto: </label>
        <input type="text" id="filtro-produto" onkeyup="filtrarProduto()" placeholder="Digite parte do nome do produto...">
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

                // Extrair nome e quantidade
                preg_match('/^(\d+)x\s+(.*?)(?:\s*\((.*?)\))?$/i', $item, $match);
                $qtd = $match[1] ?? 1;
                $nomeProduto = trim($match[2] ?? $item);

                // Buscar valor e porcentagem do produto
                $pdo = Database::conectar();
                $stmt = $pdo->prepare("SELECT valor, porcentagem FROM produtos WHERE nome ILIKE :nome LIMIT 1");
                $stmt->execute([':nome' => "%$nomeProduto%"]);
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

    linhas.forEach(linha => {
        const produto = linha.cells[3].textContent.toLowerCase();
        linha.style.display = produto.includes(filtro) ? "" : "none";
    });
}
</script>
</body>
</html>
