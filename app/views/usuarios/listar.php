<h2>👥 Lista de Usuários Cadastrados</h2>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= ucfirst($usuario['tipo']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br><br>

<a href="/florV3/public/index.php?rota=painel">
    <button>⬅ Voltar ao Painel</button>
</a>

<a href="/florV3/public/index.php?rota=novo-usuario">
    <button>➕ Cadastrar Novo Usuário</button>
</a>
