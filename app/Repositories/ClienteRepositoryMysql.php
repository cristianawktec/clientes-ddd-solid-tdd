<?php

namespace App\Repositories;

use App\Domain\Entities\ClienteEntity;
use PDO;

class ClienteRepositoryMysql implements ClienteRepositoryInterface
{
    private PDO $conn;
    // últimos SQL e parâmetros usados (apenas para debug)
    public ?string $lastQuery = null;
    public array $lastParams = [];

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function salvar(ClienteEntity $cliente): bool
    {
        try {
            // começa transação para garantir atomicidade entre cliente e endereco
            if (!$this->conn->inTransaction()) {
                $this->conn->beginTransaction();
            }

            $stmt = $this->conn->prepare(
                "INSERT INTO clientes (nome, email, telefone, imagem) VALUES (:nome, :email, :telefone, :imagem)"
            );
            $ok = $stmt->execute([
                'nome'  => $cliente->getNome(),
                'email' => $cliente->getEmail(),
                'telefone' => $cliente->getTelefone(),
                'imagem' => $cliente->getImagem()
            ]);

            if (!$ok) {
                $this->conn->rollBack();
                return false;
            }

            // tenta obter last insert id de forma robusta
            $lastId = (int)$this->conn->lastInsertId();
            if ($lastId === 0) {
                $stmtId = $this->conn->query('SELECT LAST_INSERT_ID()');
                $lastId = (int)$stmtId->fetchColumn();
            }

            // insere endereco se existir no entity
            $end = $cliente->getEndereco();
            if (!empty($end) && is_array($end)) {
                $stmt2 = $this->conn->prepare("INSERT INTO endereco (id_cliente, cep, logradouro, numero, complemento, bairro, localidade, uf) VALUES (:id_cliente, :cep, :logradouro, :numero, :complemento, :bairro, :localidade, :uf)");
                $ok2 = $stmt2->execute([
                    'id_cliente' => $lastId,
                    'cep' => $end['cep'] ?? null,
                    'logradouro' => $end['logradouro'] ?? null,
                    'numero' => $end['numero'] ?? null,
                    'complemento' => $end['complemento'] ?? null,
                    'bairro' => $end['bairro'] ?? null,
                    'localidade' => $end['localidade'] ?? null,
                    'uf' => $end['uf'] ?? null,
                ]);

                if (!$ok2) {
                    $this->conn->rollBack();
                    return false;
                }
            }

            // commit da transação
            if ($this->conn->inTransaction()) {
                $this->conn->commit();
            }

            return true;
        } catch (\Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    public function atualizar(ClienteEntity $cliente): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE clientes SET nome = :nome, email = :email WHERE id = :id"
        );
        return $stmt->execute([
            'nome'  => $cliente->getNome(),
            'email' => $cliente->getEmail(),
            'id'    => $cliente->getId()
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function buscarPorId(int $id): ?ClienteEntity
    {
        $sql = "SELECT c.*, e.cep AS cep, e.logradouro AS logradouro, e.numero AS numero, e.complemento AS complemento, e.bairro AS bairro, e.localidade AS localidade, e.uf AS uf
                FROM clientes c
                LEFT JOIN endereco e ON e.id_cliente = c.id
                WHERE c.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $endereco = null;
        if (!empty($data['cep']) || !empty($data['logradouro'])) {
            $endereco = [
                'cep' => $data['cep'] ?? null,
                'logradouro' => $data['logradouro'] ?? null,
                'numero' => $data['numero'] ?? null,
                'complemento' => $data['complemento'] ?? null,
                'bairro' => $data['bairro'] ?? null,
                'localidade' => $data['localidade'] ?? null,
                'uf' => $data['uf'] ?? null,
            ];
        }

        return new ClienteEntity([
            'id' => $data['id'],
            'nome' => $data['nome'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? null,
            'imagem' => $data['imagem'] ?? null,
            'endereco' => $endereco
        ]);
    }

    public function listarTodos(array $filters = []): array
    {
        $sql = "SELECT * FROM clientes";
        $where = [];
        $params = [];

        if (!empty($filters['nome'])) {
            $where[] = 'nome LIKE :nome';
            $params['nome'] = '%' . $filters['nome'] . '%';
        }
        if (!empty($filters['email'])) {
            $where[] = 'email LIKE :email';
            $params['email'] = '%' . $filters['email'] . '%';
        }
        if (!empty($filters['telefone'])) {
            $where[] = 'telefone LIKE :telefone';
            $params['telefone'] = '%' . $filters['telefone'] . '%';
        }
        if (!empty($filters['uf'])) {
            // busca por uf no endereco via join
            $sql = "SELECT c.* FROM clientes c LEFT JOIN endereco e ON e.id_cliente = c.id";
            $where[] = 'e.uf = :uf';
            $params['uf'] = $filters['uf'];
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

    $stmt = $this->conn->prepare($sql);
    // armazena para debug
    $this->lastQuery = $sql;
    $this->lastParams = $params;
    // debug: log SQL and params (useful during development)
    error_log("[ClienteRepositoryMysql] SQL: " . $sql . " | params: " . json_encode($params));
    $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new ClienteEntity([
                'id' => $row['id'],
                'nome' => $row['nome'],
                'email' => $row['email'],
                'telefone' => $row['telefone'] ?? null,
                'imagem' => $row['imagem'] ?? null,
            ]),
            $rows
        );
    }
}
