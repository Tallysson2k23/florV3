<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Flor de Cheiro</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            margin-bottom: 25px;
            text-align: center;
            font-size: 26px;
            color: #111;
            font-family: "Brush Script MT", cursive;
        }

        .login-container label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 6px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
        }

        .login-container input:focus {
            outline: none;
            border-color: #111;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #111;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-container button:hover {
            background-color: #333;
        }

        .error-msg {
            color: #d8000c;
            background: #ffd2d2;
            padding: 10px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
        }

        .top-bar {
            position: absolute;
            top: 0;
            width: 100%;
            height: 60px;
            background-color: #111;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Brush Script MT", cursive;
            font-size: 28px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<div class="top-bar">Flor de Cheiro</div>

<div class="login-container">
    <h2>Login</h2>

    <?php if (isset($_GET['erro'])): ?>
        <div class="error-msg">Email ou senha incorretos</div>
    <?php endif; ?>

    <form method="post" action="/florV3/public/index.php?rota=autenticar">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
