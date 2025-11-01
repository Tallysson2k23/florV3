<?php
require_once __DIR__ . '/../../config/database.php';

class Vendedor
{
    /** @var PDO */
    private $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Cria um novo vendedor gerando código sequencial (V01, V02...).
     */
    public function salvar(string $nome, ?string $telefone = null): bool
    {
        $this->conn->beginTransaction();
        try {
            // Bloqueia sequência de código (seguro em ambientes concorrentes)
            $this->conn->query("SELECT pg_advisory_xact_lock(hashtext('vendedores_codigo_seq'))");

            // Obtém próximo número
            $sqlProx = "
                SELECT COALESCE(MAX(CAST(SUBSTRING(codigo FROM '[0-9]+') AS INTEGER)), 0) + 1 AS proximo
                FROM vendedores
            ";
            $proximo = (int)$this->conn->query($sqlProx)->fetch(PDO::FETCH_ASSOC)['proximo'];
            $codigo  = 'V' . str_pad((string)$proximo, 2, '0', STR_PAD_LEFT);

            $stmt = $this->conn->prepare("
                INSERT INTO vendedores (nome, telefone, codigo, ativo)
                VALUES (:nome, :telefone, :codigo, true)
            ");
            $stmt->execute([
                ':nome'     => $nome,
                ':telefone' => $telefone,
                ':codigo'   => $codigo,
            ]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /** Lista todos em ordem alfabética */
    public function listarTodos(): array
    {
        $stmt = $this->conn->prepare("
            SELECT id, nome, telefone, codigo, ativo
            FROM vendedores
            ORDER BY COALESCE(LOWER(nome), '') ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Lista apenas ativos */
    public function listarAtivos(): array
    {
        $stmt = $this->conn->prepare("
            SELECT id, nome, telefone, codigo, ativo
            FROM vendedores
            WHERE ativo = true
            ORDER BY COALESCE(LOWER(nome), '') ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Ativar */
    public function ativar(int $id): bool
    {
        $stmt = $this->conn->prepare("UPDATE vendedores SET ativo = true WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Inativar */
    public function inativar(int $id): bool
    {
        $stmt = $this->conn->prepare("UPDATE vendedores SET ativo = false WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Atualiza campos básicos (nome, código, ativo, telefone).
     * Usado no formulário de edição em lote.
     */
   public function atualizarCamposBasicos(
    int $id,
    string $nome,
    ?string $codigo,
    int $ativo
): bool {
    $sql = "UPDATE vendedores
               SET nome = :nome,
                   codigo = :codigo,
                   ativo = :ativo
             WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        ':nome'   => $nome,
        ':codigo' => $codigo,
        ':ativo'  => $ativo,
        ':id'     => $id,
    ]);
}


    /**
     * Excluir definitivamente. Se violar FK (erro 23503), faz soft-delete.
     */
    public function excluir(int $id): bool
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM vendedores WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23503') {
                return $this->inativar($id); // soft delete
            }
            throw $e;
        }
    }

    /** Buscar por ID (para futura edição individual, se desejar) */
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT id, nome, telefone, codigo, ativo
            FROM vendedores
            WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
