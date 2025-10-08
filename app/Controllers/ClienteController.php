<?php

namespace App\Controllers;

use App\Services\ClienteService;
use App\Repositories\ClienteRepositoryMysql;

class ClienteController
{
    protected $clienteService;

    public function __construct()
    {
        // Aqui você pode usar injeção de dependência mais elaborada se quiser
        $repository = new ClienteRepositoryMysql();
        $this->clienteService = new ClienteService($repository);
    }

    public function index()
    {
        return $this->jsonResponse($this->clienteService->listarClientes());
    }

    public function show($id)
    {
        $cliente = $this->clienteService->buscarClientePorId((int)$id);

        if (!$cliente) {
            return $this->jsonResponse(['erro' => 'Cliente não encontrado'], 404);
        }

        return $this->jsonResponse($cliente);
    }

    public function store(array $dados)
    {
        try {
            $cliente = $this->clienteService->criarCliente($dados);
            return $this->jsonResponse(['mensagem' => 'Cliente criado com sucesso', 'cliente' => $cliente], 201);
        } catch (\Exception $e) {
            return $this->jsonResponse(['erro' => $e->getMessage()], 400);
        }
    }

    public function update($id, array $dados)
    {
        try {
            $cliente = $this->clienteService->atualizarCliente((int)$id, $dados);
            return $this->jsonResponse(['mensagem' => 'Cliente atualizado com sucesso', 'cliente' => $cliente]);
        } catch (\Exception $e) {
            return $this->jsonResponse(['erro' => $e->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        $this->clienteService->deletarCliente((int)$id);
        return $this->jsonResponse(['mensagem' => 'Cliente removido com sucesso']);
    }

    private function jsonResponse($data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
