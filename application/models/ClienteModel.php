<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClienteModel extends CI_Model
{
    protected $table = 'clientes';
    protected $enderecoTable = 'endereco';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retorna todos os clientes (sem join com endereço).     
     *   public function getAll()
    *    {
    *        return $this->db->get($this->table)->result_array();
    *    }
    */
    public function getAll($filtros = [])
    {
        $this->db->select('c.*, e.logradouro, e.bairro, e.localidade, e.uf');
        $this->db->from('clientes c');
        $this->db->join('endereco e', 'c.id = e.id_cliente', 'left');

        if (!empty($filtros['nome'])) {
            $this->db->like('c.nome', $filtros['nome']);
        }
        if (!empty($filtros['email'])) {
            $this->db->like('c.email', $filtros['email']);
        }
        if (!empty($filtros['telefone'])) {
            $this->db->like('c.telefone', $filtros['telefone']);
        }
        if (!empty($filtros['uf'])) {
            $this->db->where('e.uf', $filtros['uf']);
        }

        $query = $this->db->get();
        return $query->result_array();
    }


    /**
     * Retorna cliente por ID com dados do endereço (join).
     */
    public function getById($id)
    {
        $this->db->select('c.*, e.cep, e.logradouro, e.numero, e.bairro, e.localidade, e.uf, e.complemento');
        $this->db->from($this->table . ' c');
        $this->db->join($this->enderecoTable . ' e', 'e.id_cliente = c.id', 'left'); 
        $this->db->where('c.id', $id);

        return $this->db->get()->row_array();
    }

    /**
     * Insere novo cliente.
     */
    public function save(array $data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Atualiza cliente.
     */
    public function update($id, array $data)
    {
        return $this->db->where('id', $id)
                        ->update($this->table, $data);
    }

    /**
     * Alias para update de cliente.
     */
    public function updateCliente($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Atualiza ou insere endereço vinculado a cliente.
     */
    public function updateEndereco($idCliente, array $data)
    {
        // primeiro tenta encontrar por id_cliente
        $exists = $this->db->get_where($this->enderecoTable, ['id_cliente' => $idCliente])->row_array();

        if ($exists) {
            return $this->db->where('id_cliente', $idCliente)
                            ->update($this->enderecoTable, $data);
        }

        // se não achar por id_cliente, tenta localizar por CEP (cache do CepService pode ter inserido sem id_cliente)
        if (!empty($data['cep'])) {
            $byCep = $this->db->get_where($this->enderecoTable, ['cep' => $data['cep']])->row_array();
            if ($byCep) {
                // atualiza a linha existente (se houver coluna id, usa-a; caso contrário usa cep)
                $data['id_cliente'] = $idCliente;
                if (isset($byCep['id'])) {
                    return $this->db->where('id', $byCep['id'])->update($this->enderecoTable, $data);
                }
                return $this->db->where('cep', $data['cep'])->update($this->enderecoTable, $data);
            }
        }

        // caso não exista nem por id_cliente nem por cep, insere novo registro com id_cliente
        $data['id_cliente'] = $idCliente;
        return $this->db->insert($this->enderecoTable, $data);
    }

    /**
     * Busca endereço pelo ID do cliente.
     */
    public function getEnderecoByClienteId($id)
    {
        return $this->db->where('id_cliente', $id)
                        ->get($this->enderecoTable)
                        ->row_array();
    }

    /**
     * Deleta cliente e endereço vinculado.
     */
    public function delete($id)
    {
        $this->db->delete($this->enderecoTable, ['id_cliente' => $id]);
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
