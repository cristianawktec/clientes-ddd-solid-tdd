<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClienteRepository
{
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('ClienteModel');
    }

    public function listar() {
        return $this->ci->ClienteModel->getAll();
    }

    public function buscarPorId($id)
    {
        return $this->ci->ClienteModel->getById($id);
    }

    public function salvar(array $data)
    {
        return $this->ci->ClienteModel->save($data);
    }

    public function atualizar($id, array $data)
    {
        return $this->ci->ClienteModel->update($id, $data);
    }

    public function deletar($id)
    {
        return $this->ci->ClienteModel->delete($id);
    }
}
