<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'infrastructure/services/CepService.php');

class Cliente extends CI_Controller
{
    private $clienteService;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('ddd_autoload');
        $this->load->library('session');

        // Injeta dependências através de fábricas (preserva SRP / IoC)
        // RepositoryFactory usa DatabaseFactory internamente
        $repo = \App\Infrastructure\RepositoryFactory::createClienteRepository();
        if ($repo === null) {
            // não temos conexão/configuração disponível — setamos null e tratamos nas actions
            log_message('error', 'RepositoryFactory could not create ClienteRepository (DB config missing or connection failed)');
            $this->clienteService = null;
        } else {
            // usa o service namespaced da pasta app/Service
            $this->clienteService = new \App\Services\ClienteService($repo);
        }
    }

public function index()
{
    $this->ensureService();

    // Captura os filtros da query string e remove valores vazios
    $filtros = array_filter([
        'nome'     => $this->input->get('nome'),
        'email'    => $this->input->get('email'),
        'telefone' => $this->input->get('telefone'),
        'uf'       => $this->input->get('uf'),
    ], function($v) { return $v !== null && $v !== ''; });

    // log para debug rápido (ver application/logs)
    log_message('debug', 'Cliente#index filtros: ' . json_encode($filtros));

    // Passa os filtros para o service
    $clientes = $this->clienteService->listarClientes($filtros);

    // Converter entidades para arrays para que as views funcionem
    $clientesArray = array_map(function($c) {
        if (is_object($c) && method_exists($c, 'toArray')) {
            return $c->toArray();
        }
        if (is_array($c)) return $c;
        return [];
    }, $clientes ?: []);

    $this->load->view('clientes/index', [
        'clientes' => $clientesArray,
        'filtros'  => $filtros, // importante: para repopular o form da view
    ]);
}


    public function create()
    {
        $this->ensureService();
        $this->load->view('clientes/create');
    }

    public function store()
    {
        try {
            $this->ensureService();
            $dados = $this->input->post();
            $file = $_FILES['imagem'] ?? null;

            // trata upload (usamos a library do CI para manter comportamento existente)
            if ($file && isset($file['name']) && $file['name'] !== '') {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);
                // cria índice temporário compatível com upload lib
                $_FILES['imagem_temp'] = $file;
                if (!$this->upload->do_upload('imagem_temp')) {
                    unset($_FILES['imagem_temp']);
                    throw new Exception($this->upload->display_errors('', ''));
                }
                $uploadData = $this->upload->data();
                unset($_FILES['imagem_temp']);
                $dados['imagem'] = $uploadData['file_name'];
            }

            // pega os campos de endereco do POST (se houver)
            $dadosEndereco = [
                'cep' => $this->input->post('cep'),
                'logradouro' => $this->input->post('logradouro'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
                'bairro' => $this->input->post('bairro'),
                'cidade' => $this->input->post('cidade'),
                'uf' => $this->input->post('uf')
            ];

            // Persistir via ClienteModel para garantir uso do insert_id e updateEndereco
            $this->load->model('ClienteModel');

            $clienteData = [
                'nome' => $dados['nome'] ?? null,
                'email' => $dados['email'] ?? null,
                'telefone' => $dados['telefone'] ?? null,
                'imagem' => $dados['imagem'] ?? null,
            ];

            $insertId = $this->ClienteModel->save($clienteData);
            if (!$insertId) {
                throw new Exception('Erro ao salvar cliente no banco.');
            }

            // insere/atualiza endereco vinculando ao cliente recém-criado
            $endData = [
                'cep' => $dadosEndereco['cep'] ?? null,
                'logradouro' => $dadosEndereco['logradouro'] ?? null,
                'numero' => $dadosEndereco['numero'] ?? null,
                'complemento' => $dadosEndereco['complemento'] ?? null,
                'bairro' => $dadosEndereco['bairro'] ?? null,
                'localidade' => $dadosEndereco['cidade'] ?? null,
                'uf' => $dadosEndereco['uf'] ?? null,
            ];

            $this->ClienteModel->updateEndereco($insertId, $endData);

            $this->session->set_flashdata('success', 'Cliente cadastrado com sucesso!');
            redirect('cliente');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            $this->load->view('clientes/create', ['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->ensureService();
        $cliente = $this->clienteService->buscarClientePorId($id);
        if (!$cliente) show_404();

        // garantir que a view receba um array
        if (is_object($cliente) && method_exists($cliente, 'toArray')) {
            $cliente = $cliente->toArray();
        }

        $this->load->view('clientes/edit', ['cliente' => $cliente]);
    }

    public function update($id)
    {
        $this->ensureService();
        $dadosCliente = $this->input->post();
        $file = $_FILES['imagem'] ?? null;

        // usa ClienteModel para atualizar cliente e endereco
        $this->load->model('ClienteModel');

        $clienteData = [
            'nome' => $dadosCliente['nome'] ?? null,
            'email' => $dadosCliente['email'] ?? null,
            'telefone' => $dadosCliente['telefone'] ?? null,
        ];

        $this->ClienteModel->updateCliente($id, $clienteData);

        // atualiza endereco
        $endData = [
            'cep' => $dadosCliente['cep'] ?? null,
            'logradouro' => $dadosCliente['logradouro'] ?? null,
            'numero' => $dadosCliente['numero'] ?? null,
            'complemento' => $dadosCliente['complemento'] ?? null,
            'bairro' => $dadosCliente['bairro'] ?? null,
            'localidade' => $dadosCliente['localidade'] ?? $dadosCliente['cidade'] ?? null,
            'uf' => $dadosCliente['uf'] ?? null,
        ];

        $this->ClienteModel->updateEndereco($id, $endData);

        $this->session->set_flashdata('success', 'Cliente atualizado com sucesso!');
        redirect('cliente');
    }

    public function delete($id)
    {
        $this->ensureService();
        $this->clienteService->deletarCliente($id);
        $this->session->set_flashdata('success', 'Cliente removido!');
        redirect('cliente');
    }

    private function ensureService()
    {
        if ($this->clienteService === null) {
            // mostra mensagem amigável em vez de erro fatal
            show_error('Serviço indisponível: verifique a configuração do banco de dados.', 503);
            exit;
        }
    }

    public function consultaCep()
    {
        $cep = $this->input->post('cep');
        if (!$cep) {
            echo json_encode(['error' => 'CEP não informado']);
            return;
        }

        $cepService = new CepService();
        $dados = $cepService->buscar($cep);

        header('Content-Type: application/json');
        echo json_encode($dados);
    }

}
