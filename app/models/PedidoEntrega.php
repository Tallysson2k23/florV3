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

    unset($dados['imprimir']); // Remove campos extras

    // ✅ Garante todos os campos usados no SQL
    $dados['numero_pedido'] = $dados['numero_pedido'] ?? '';
    $dados['tipo'] = $dados['tipo'] ?? '';
    $dados['remetente'] = $dados['remetente'] ?? null;
    $dados['telefone_remetente'] = $dados['telefone_remetente'] ?? null;
    $dados['destinatario'] = $dados['destinatario'] ?? null;
    $dados['telefone_destinatario'] = $dados['telefone_destinatario'] ?? null;
    $dados['endereco'] = $dados['endereco'] ?? null;
    $dados['numero_endereco'] = $dados['numero_endereco'] ?? null;
    $dados['bairro'] = $dados['bairro'] ?? null;
    $dados['referencia'] = $dados['referencia'] ?? null;
    $dados['produtos'] = $dados['produtos'] ?? '';
    $dados['adicionais'] = $dados['adicionais'] ?? null;
    $dados['data_abertura'] = $dados['data_abertura'] ?? date('Y-m-d');
    $dados['hora'] = date('H:i:s');
    $dados['status'] = $dados['status'] ?? 'Pendente';
    $dados['ordem_fila'] = $ordem;
    $dados['vendedor_codigo'] = $dados['vendedor_codigo'] ?? null;
    $dados['obs_produto'] = $dados['obs_produto'] ?? null;
    $dados['quantidade'] = $dados['quantidade'] ?? 1;
    $dados['enviar_para'] = $dados['enviar_para'] ?? null;

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
               remetente AS nome, 
               status, data_abertura, hora, ordem_fila
        FROM {$this->table}
        ORDER BY ordem_fila DESC";

    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


public function atualizarStatus($id, $status, $mensagem = null, $responsavel = null) {
    $campos = 'status = :status';
    $params = [':status' => $status, ':id' => $id];

    if ($status === 'Entregue' && $mensagem !== null) {
        $campos .= ', mensagem_entrega = :mensagem';
        $params[':mensagem'] = $mensagem;
    }

    if (!empty($responsavel)) {
        $campos .= ', responsavel_producao = :responsavel';
        $params[':responsavel'] = $responsavel;
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



public function buscarPorStatusEDataEEnvio(array $statusArray, $data, $enviarPara, $busca = '') {
    $statusPlaceholders = [];
    $params = [];

    foreach ($statusArray as $index => $status) {
        $paramName = ":status{$index}";
        $statusPlaceholders[] = $paramName;
        $params[$paramName] = $status;
    }

    $sql = "SELECT * FROM {$this->table} 
            WHERE status IN (" . implode(",", $statusPlaceholders) . ") 
            AND data_abertura = :data 
            AND enviar_para = :enviar_para 
            AND (remetente ILIKE :busca)";

    $stmt = $this->conn->prepare($sql);
    $params[':data'] = $data;
    $params[':enviar_para'] = $enviarPara;
    $params[':busca'] = "%{$busca}%";

    $stmt->execute($params);
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
public function buscarPorProdutoEData($produto, $data, $statusFiltro) {
    $pdo = Database::conectar();

    $placeholders = implode(',', array_fill(0, count($statusFiltro), '?'));
    $sql = "SELECT * FROM pedidos_entrega 
            WHERE data_abertura = ? 
            AND status IN ($placeholders)
            AND produtos ILIKE ? 
            ORDER BY ordem_fila ASC";

    $stmt = $pdo->prepare($sql);

    $params = array_merge([$data], $statusFiltro, ["%{$produto}%"]);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function buscarAtendimentosPorData($data) {
    $sql = "SELECT id, numero_pedido, tipo, 
                   COALESCE(destinatario, remetente) AS nome, 
                   status, data_abertura, hora, ordem_fila
            FROM {$this->table}
            WHERE data_abertura = :data 
               OR (status = 'Cancelado' AND data_abertura = :data)
            ORDER BY ordem_fila ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':data', $data);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function listarPorData($data) {
    $stmt = $this->conn->prepare("SELECT * FROM pedidos_entrega WHERE data_abertura = :data");
    $stmt->execute([':data' => $data]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






}
