<?php

namespace App\Services;

use App\Repositories\ClienteRepositoryInterface;
use App\Domain\Entities\ClienteEntity;

class ClienteService
{
    protected ClienteRepositoryInterface $clienteRepository;

    public function __construct(ClienteRepositoryInterface $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function listarClientes(array $filters = []): array
    {
        return $this->clienteRepository->listarTodos($filters);
    }

    public function buscarClientePorId(int $id): ?ClienteEntity
    {
        return $this->clienteRepository->buscarPorId($id);
    }

    public function criarCliente(array $dados): bool
    {
        $cliente = new ClienteEntity([
            'nome' => $dados['nome'] ?? '',
            'email' => $dados['email'] ?? '',
            'telefone' => $dados['telefone'] ?? null,
            'imagem' => $dados['imagem'] ?? null,
            'endereco' => [
                'cep' => $dados['cep'] ?? null,
                'logradouro' => $dados['logradouro'] ?? null,
                'numero' => $dados['numero'] ?? null,
                'complemento' => $dados['complemento'] ?? null,
                'bairro' => $dados['bairro'] ?? null,
                'localidade' => $dados['cidade'] ?? null,
                'uf' => $dados['uf'] ?? null,
            ]
        ]);

        return $this->clienteRepository->salvar($cliente);
    }

    public function atualizarCliente(int $id, array $dados): bool
    {
        $cliente = $this->clienteRepository->buscarPorId($id);
        if (!$cliente) {
            throw new \Exception("Cliente não encontrado");
        }

        // atualiza apenas alguns campos para simplicidade
        if (isset($dados['nome'])) $cliente->setNome($dados['nome']);
        if (isset($dados['email'])) $cliente->setEmail($dados['email']);
        if (isset($dados['telefone'])) $cliente->setTelefone($dados['telefone']);

        return $this->clienteRepository->atualizar($cliente);
    }

    public function deletarCliente(int $id): bool
    {
        return $this->clienteRepository->excluir($id);
    }


}
