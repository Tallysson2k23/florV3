<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Retirada - Flor de Cheiro</title>
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
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

        input {
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
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="form-wrapper">
    <h2>Cadastro de Retirada</h2>

    <form id="form-retirada" method="post" action="/florV3/public/index.php?rota=salvar-retirada">
        <div class="form-group">
            <div>
                <label>Nº Pedido:</label>
                <input name="numero_pedido" required>
            </div>
            <div>
                <label>Tipo:</label>
                <input name="tipo" value="2-Retirada" readonly>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label>Nome:</label>
                <input name="nome" required>
            </div>
            <div>
                <label>Telefone:</label>
                <input name="telefone">
            </div>
        </div>

        <div class="form-group full">
            <div>
                <label>Produtos:</label>
                <input name="produtos">
            </div>
        </div>

        <div class="form-group full">
            <div>
                <label>Adicionais:</label>
                <input name="adicionais">
            </div>
        </div>

        <div class="form-group full">
            <div>
                <label>Data:</label>
                <input type="date" name="data_abertura" value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <input type="hidden" name="imprimir" id="imprimir-retirada" value="0">

        <div class="actions">
            <button type="button" onclick="confirmarEnvioRetirada()">Enviar</button>
            <button type="button" onclick="confirmarCancelamento()">Cancelar</button>
        </div>
    </form>
</div>

<script>
function confirmarEnvioRetirada() {
    if (confirm("Deseja realmente enviar o pedido?")) {
        const imprimir = confirm("Deseja imprimir o cupom?");
        document.getElementById("imprimir-retirada").value = imprimir ? "1" : "0";
        document.getElementById("form-retirada").submit();
    }
}

function confirmarCancelamento() {
    if (confirm("Deseja realmente cancelar? Os dados serão perdidos.")) {
        window.location.href = "/florV3/public/index.php?rota=painel";
    }
}
</script>

</body>
</html>
