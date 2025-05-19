<h2>👤 Cadastrar Usuário</h2>
<form method="post" action="/florV3/public/index.php?rota=salvar-usuario">
    <label>Nome: <input type="text" name="nome" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Senha: <input type="password" name="senha" required></label><br>
    <label>Tipo:
        <select name="tipo">
            <option value="admin">Admin</option>
            <option value="colaborador">Colaborador</option>
        </select>
    </label><br>
    <button type="submit">Cadastrar</button>
</form>
<a href="/florV3/public/index.php?rota=painel"><button>⬅ Voltar</button></a>
