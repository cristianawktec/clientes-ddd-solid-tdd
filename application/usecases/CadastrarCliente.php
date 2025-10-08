<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CadastrarCliente
{
    private $repo;
    private $cepService;
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->repo = new ClienteRepository();
        $this->cepService = new CepService();
    }

    /**
     * $input = array com nome, email, telefone, cep, numero, complemento...
     * $file  = $_FILES['imagem'] ou null
     */
    public function execute(array $input, $file = null)
    {
        // criar entidade (vai validar nome/email/telefone)
        $clienteEntity = new ClienteEntity($input);

        // se tiver CEP, busca e adiciona ao entity
        if (!empty($input['cep'])) {
            $end = $this->cepService->buscar($input['cep']);
            if ($end) {
                // adiciona campos extras do form (numero/complemento)
                $end['numero'] = $input['numero'] ?? null;
                $end['complemento'] = $input['complemento'] ?? ($end['complemento'] ?? null);
                $clienteEntity->setEndereco($end);
            }
        }

        // upload imagem (se houver)
        if ($file && isset($file['name']) && $file['name'] !== '') {
            $fileName = $this->handleUpload($file);
            $clienteEntity->setImagem($fileName);
        }

        // persiste via repository
        return $this->repo->salvar($clienteEntity->toArray());
    }

    private function handleUpload($file)
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['encrypt_name'] = TRUE;

        $this->ci->load->library('upload', $config);

        // o do_upload espera o índice do campo 'imagem'
        // se $file veio de $_FILES['imagem'], chamamos com esse índice
        // montamos um campo temporário para a lib do CI
        $_FILES['imagem_temp'] = $file;

        if (!$this->ci->upload->do_upload('imagem_temp')) {
            // limpa o índice temporário
            unset($_FILES['imagem_temp']);
            throw new RuntimeException($this->ci->upload->display_errors('', ''));
        }

        $data = $this->ci->upload->data();
        unset($_FILES['imagem_temp']);
        return $data['file_name'];
    }
}
