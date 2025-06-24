<?php
require_once __DIR__ . '/../../config/database.php';

class PedidoEntrega {
    private $conn;
    private $table = 'pedidos_entrega';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

public function criar($dados) {
    require_once __DIR__ . '/../helpers/OrdemGlobal.php';
$ordem = OrdemGlobal::getProximaOrdem();


    $sql = "INSERT INTO {$this->table} 
    (numero_pedido, tipo, remetente, telefone_remetente, destinatario, telefone_destinatario,
     endereco, numero_endereco, bairro, referencia, produtos, adicionais, data_abertura, hora, status, ordem_fila, vendedor_codigo, obs_produto, quantidade, enviar_para)
    VALUES 
    (:numero_pedido, :tipo, :remetente, :telefone_remetente, :destinatario, :telefone_destinatario,
     :endereco, :numero_endereco, :bairro, :referencia, :produtos, :adicionais, :data_abertura, :hora, :status, :ordem_fila, :vendedor_codigo, :obs_produto, :quantidade, :enviar_para)";

    $stmt = $this->conn->prepare($sql);

    // ✅ Remover campo extra que não faz parte do SQL
    unset($dados['imprimir']);

    $dados['status'] = $dados['status'] ?? 'Pendente';
    $dados['ordem_fila'] = $ordem;
    $dados['hora'] = date('H:i:s');
    $dados['quantidade'] = $dados['quantidade'] ?? 1;

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($dados);


    return $this->conn->lastInsertId();
}



    public function buscar($termo) {
    $sql = "SELECT id, numero_pedido, tipo, remetente AS nome, telefone_remetente AS telefone, produtos, status,  mensagem_entrega, data_abertura
        FROM {$this->table}
        WHERE numero_pedido ILIKE :termo OR remetente ILIKE :termo
        ORDER BY ordem_fila DESC";



    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function buscarPorId($id) {
    $sql = "SELECT p.*, v.nome AS nome_vendedor, v.codigo AS codigo_vendedor
            FROM {$this->table} p
            LEFT JOIN vendedores v ON p.vendedor_codigo = v.codigo
            WHERE p.id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function listarTodos() {
    $sql = "SELECT id, numero_pedido, tipo, 
                   COALESCE(destinatario, remetente) AS nome, 
                   status, data_abertura, hora, ordem_fila
            FROM {$this->table}
            ORDER BY ordem_fila DESC";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


public function atualizarStatus($id, $status, $mensagem = null) {
    $campos = 'status = :status';
    $params = [':status' => $status, ':id' => $id];

    if ($status === 'Entregue' && $mensagem !== null) {
        $campos .= ', mensagem_entrega = :mensagem';
        $params[':mensagem'] = $mensagem;
    }

    $sql = "UPDATE {$this->table} SET $campos WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
}
public function buscarPorStatus($status) {
    if (is_array($status)) {
        $placeholders = implode(',', array_fill(0, count($status), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE status IN ($placeholders) ORDER BY ordem_fila ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($status);
    } else {
        $sql = "SELECT * FROM {$this->table} WHERE status = ? ORDER BY ordem_fila ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$status]);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function buscarPorStatusEData($statusArray, $data, $busca = '') {
    $placeholders = implode(',', array_fill(0, count($statusArray), '?'));

    $sql = "SELECT id, numero_pedido, tipo,
                   COALESCE(destinatario, remetente) AS nome,
                   status, data_abertura, hora, ordem_fila
            FROM {$this->table} 
            WHERE status IN ($placeholders)
              AND data_abertura = ?";

    $params = array_merge($statusArray, [$data]);

    if (!empty($busca)) {
        $sql .= " AND (
                    numero_pedido ILIKE ? 
                    OR destinatario ILIKE ?
                    OR produtos ILIKE ?
                )";
        $params = array_merge($params, ["%$busca%", "%$busca%", "%$busca%"]);
    }

    $sql .= " ORDER BY ordem_fila ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function buscarPorStatusEDataEEnvio($statusArray, $data, $enviarPara, $busca = '') {
    $sql = "SELECT * FROM {$this->table} WHERE status IN (" . implode(',', array_fill(0, count($statusArray), '?')) . ")
            AND data_abertura = ? AND enviar_para = ?";

    if (!empty($busca)) {
        $sql .= " AND (nome ILIKE ? OR numero_pedido ILIKE ?)";
    }

    $stmt = $this->conn->prepare($sql);


    $i = 1;
    foreach ($statusArray as $status) {
        $stmt->bindValue($i++, $status);
    }
    $stmt->bindValue($i++, $data);
    $stmt->bindValue($i++, $enviarPara);

    if (!empty($busca)) {
        $likeBusca = "%$busca%";
        $stmt->bindValue($i++, $likeBusca);
        $stmt->bindValue($i++, $likeBusca);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function atualizarStatusComResponsavel($id, $status, $responsavel) {
    $sql = "UPDATE {$this->table} SET status = :status, responsavel_producao = :responsavel WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':responsavel' => $responsavel,
        ':id' => $id
    ]);
}


}
