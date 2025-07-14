<?php
namespace app\models;

use PDO;
use PDOException;

require_once __DIR__ . '/../../config/database.php';

class Permissao {
    private $conn;

    public function __construct() {
        $this->conn = \Database::conectar();
    }

    // Lista todas as permissões cadastradas
    public function listarTodas() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM permissoes");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return []; // Se der erro, retorna array vazio
        }
    }

    // Salva permissões substituindo as antigas
    public function salvarPermissoes($dados) {
        try {
            $this->conn->beginTransaction();

            $this->conn->exec("DELETE FROM permissoes");

            $stmt = $this->conn->prepare("
                INSERT INTO permissoes (pagina, tipo_usuario) 
                VALUES (:pagina, :tipo_usuario)
            ");

            $adminTemPermissao = false;

            foreach ($dados as $pagina => $tipos) {
                foreach ($tipos as $tipo) {
                    if ($pagina === 'permissoes' && $tipo !== 'admin') {
                        continue;
                    }

                    if ($pagina === 'permissoes' && $tipo === 'admin') {
                        $adminTemPermissao = true;
                    }

                    $stmt->execute([
                        ':pagina' => $pagina,
                        ':tipo_usuario' => $tipo
                    ]);
                }
            }

            // Se não veio na lista, insere manualmente a permissão de admin na página de permissões
            if (!$adminTemPermissao) {
                $stmt->execute([
                    ':pagina' => 'permissoes',
                    ':tipo_usuario' => 'admin'
                ]);
            }

            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // Verifica se o tipo de usuário tem permissão para uma página
    public function verificarPermissao($tipoUsuario, $pagina) {
        $stmt = $this->conn->prepare("
            SELECT 1 FROM permissoes 
            WHERE tipo_usuario = :tipo AND pagina = :pagina 
            LIMIT 1
        ");
        $stmt->execute([
            ':tipo' => strtolower($tipoUsuario),
            ':pagina' => strtolower($pagina)
        ]);

        return $stmt->fetchColumn() !== false;
    }
}
