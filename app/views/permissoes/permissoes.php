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
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 30px; }
        table { border-collapse: collapse; width: 100%; background: #fff; box-shadow: 0 0 8px #ccc; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ccc; }
        th { background: #111; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        h2 { text-align: center; }
        button { padding: 10px 20px; margin-top: 15px; background: green; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
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
</body>
</html>
