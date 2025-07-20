<?php
require_once __DIR__ . '/../../models/Permissao.php';

use app\models\Permissao;

$permissaoModel = new Permissao();

$paginas = [
    'cadastrar-produto' => 'Cadastrar Produto',
    'historico' => 'Histórico',
    'agenda' => 'Agenda',
    'usuarios' => 'Gerenciar Usuários',
    'cancelados' => 'Pedidos Cancelados',
    'cadastrar-vendedor' => 'Cadastrar Vendedor',
    'lista-vendedores' => 'Lista de Vendedores',
    'permissoes' => 'Gerenciar Permissões',
    'cadastrar-pedido' => 'Cadastrar Pedido',
    'acompanhamento' => 'Acompanhar Pedidos',
    'acompanhamento-atendente' => 'Acompanhamento do Atendente',
    'retiradas' => 'Entregues',
];

$tipos = ['admin', 'colaborador', 'colaborador-producao'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST['permissoes'] ?? [];
    $permissaoModel->salvarPermissoes($dados);
    echo "<script>alert('Permissões atualizadas com sucesso!');</script>";
}

$permissoesAtuais = $permissaoModel->listarTodas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Permissões - Flor de Cheiro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background-color: #111;
            text-align: center;
            padding: 15px 0;
        }

        .logo-img {
            height: 60px;
            max-width: 100%;
            object-fit: contain;
            display: inline-block;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            box-shadow: 0 0 8px #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background: #111;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        button {
            padding: 10px 20px;
            margin-top: 20px;
            background: green;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #0d7a0d;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="/florV3/public/assets/img/logo-flor-cortada.png" alt="Flor de Cheiro" class="logo-img">
</div>

<div class="container">
    <h2>Gerenciar Permissões</h2>
    <form method="POST">
        <table>
            <tr>
                <th>Funcionalidade</th>
                <?php foreach ($tipos as $tipo): ?>
                    <th><?= ucfirst($tipo) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($paginas as $chave => $nome): ?>
                <tr>
                    <td><?= $nome ?></td>
                    <?php foreach ($tipos as $tipo): ?>
                        <td>
                            <input type="checkbox" name="permissoes[<?= $chave ?>][]" value="<?= $tipo ?>"
                                <?php
                                foreach ($permissoesAtuais as $p) {
                                    if ($p['pagina'] === $chave && $p['tipo_usuario'] === $tipo) {
                                        echo 'checked';
                                    }
                                }
                                ?>>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <div style="text-align: center;">
            <button type="submit">Salvar Permissões</button>
        </div>
    </form>
</div>

</body>
</html>
