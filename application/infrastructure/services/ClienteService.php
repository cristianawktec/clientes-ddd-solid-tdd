<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClienteService {
    protected $ci;
    protected $clienteModel;
    protected $cepModel;
    protected $cepService;

    public function __construct()
    {
        // pega instância do CodeIgniter
        $this->ci = &get_instance();

        // carrega models
        $this->ci->load->model('ClienteModel');
        $this->ci->load->model('CepModel');

        $this->clienteModel = $this->ci->ClienteModel;
        $this->cepModel     = $this->ci->CepModel;

        // instancia serviço de CEP
        $this->cepService = new CepService();
    }

    /**
     * Atualiza dados do cliente + endereço
     */
    public function atualizarCliente($id, $dadosCliente, $dadosEndereco)
    {
        // 🔹 Atualiza dados básicos do cliente
        $this->clienteModel->updateCliente($id, [
            'nome'     => $dadosCliente['nome']     ?? null,
            'email'    => $dadosCliente['email']    ?? null,
            'telefone' => $dadosCliente['telefone'] ?? null,
            'imagem'   => $dadosCliente['imagem']   ?? null
        ]);

        // 🔹 Pega endereço atual no banco
        $enderecoAtual = $this->cepModel->getEnderecoByClienteId($id);

        $cep        = $dadosEndereco['cep']        ?? $enderecoAtual['cep'] ?? null;
        $numero     = $dadosEndereco['numero']     ?? $enderecoAtual['numero'] ?? null;
        $complemento= $dadosEndereco['complemento']?? $enderecoAtual['complemento'] ?? null;

        // Busca dados no serviço de CEP (apenas se mudou)
        $enderecoApi = [];
        if (!empty($cep) && $cep !== ($enderecoAtual['cep'] ?? null)) {
            $enderecoApi = $this->cepService->buscar($cep);
        }

        // 🔹 Monta endereço final, sempre preservando valores já existentes
        $endereco = [
            'id_cliente' => $id,
            'cep'        => $cep,
            'logradouro' => $dadosEndereco['logradouro']  ?? $enderecoApi['logradouro'] ?? $enderecoAtual['logradouro'] ?? null,
            'numero'     => $numero,
            'complemento'=> $complemento,
            'bairro'     => $dadosEndereco['bairro']      ?? $enderecoApi['bairro'] ?? $enderecoAtual['bairro'] ?? null,
            'localidade' => $dadosEndereco['localidade']  ?? $enderecoApi['localidade'] ?? $enderecoAtual['localidade'] ?? null,
            'uf'         => $dadosEndereco['uf']          ?? $enderecoApi['uf'] ?? $enderecoAtual['uf'] ?? null
        ];

        // 🔹 Atualiza ou insere endereço
        if ($enderecoAtual) {
            $this->cepModel->updateEndereco($id, $endereco);
        } else {
            $this->cepModel->inserir($endereco);
        }

        return true;
    }
}
