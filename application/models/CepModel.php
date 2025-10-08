<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CepModel extends CI_Model
{
    protected $table = 'endereco';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insere um endereço.
     */
    public function inserir(array $dados)
    {
        return $this->db->insert($this->table, $dados);
    }

    /**
     * Retorna todos os endereços.
     */
    public function getAll()
    {
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Busca por CEP.
     */
    public function getByCep($cep = null)
    {
        if (!$cep) {
            return false;
        }

        return $this->db->where('cep', $cep)
                        ->limit(1)
                        ->get($this->table)
                        ->row_array();
    }
}
