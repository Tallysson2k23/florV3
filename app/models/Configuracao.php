<?php
namespace app\models;

use PDO;

class Configuracao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obter($chave) {
        $stmt = $this->pdo->prepare("SELECT valor FROM configuracoes WHERE chave = :chave");
        $stmt->execute(['chave' => $chave]);
        return $stmt->fetchColumn();
    }

    public function atualizar($chave, $valor) {
        $stmt = $this->pdo->prepare("UPDATE configuracoes SET valor = :valor WHERE chave = :chave");
        return $stmt->execute(['chave' => $chave, 'valor' => $valor]);
    }
}
