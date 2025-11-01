<?php
$senha_digitada = '123456'; // Senha que o usuário digita
$hash_no_banco = '$2y$10$EhvDP/pvV4ZjSKDnPf3MnePeaaO0Uu3xAGeD8qx7fMp1v3pHq/.CS'; // Hash que veio do banco

if (password_verify($senha_digitada, $hash_no_banco)) {
    echo "✅ Senha correta!";
} else {
    echo "❌ Senha incorreta!";
}
?>
